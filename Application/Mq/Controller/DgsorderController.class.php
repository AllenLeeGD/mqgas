<?php
namespace Mq\Controller;
use Think\Controller;

class DgsorderController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	public function findProductOrderByStatus($status) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$orderid = $_REQUEST['order_search'];
		$buyername = $_REQUEST['buyername_search'];
		$optname_search = $_REQUEST['optname_search'];
		if (!empty($orderid)) {
			$query_sql = $query_sql . " and o.pkid like '%$orderid%'";
			$countquery_sql = $countquery_sql . " and o.pkid like '%$orderid%'";
		}
		if ($buyername!="") {
			$query_sql = $query_sql . " and o.buyername LIKE '%$buyername%'";
			$countquery_sql = $countquery_sql . " and o.buyername LIKE '%$buyername%'";
		}
		if ($optname_search!="") {
			$query_sql = $query_sql . " and d.recaroptname LIKE '%$optname_search%'";
			$countquery_sql = $countquery_sql . " and d.recaroptname LIKE '%$optname_search%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);		
		if ($status == 0) {//待出库
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and dgsstatus=1  $countquery_sql";
			$condition_sql = "select o.*,d.recarnumber,d.recaroptname,d.recardate from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and dgsstatus=1 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 1) {//待入库
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and dgsstatus=2 $countquery_sql";
			$condition_sql = "select o.*,d.recarnumber,d.recaroptname,d.recardate from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and dgsstatus=2 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 2) {//待备货
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and dgsstatus=0 $countquery_sql";
			$condition_sql = "select o.*,d.recarnumber,d.recaroptname,d.recardate from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and dgsstatus=0 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 3) {//已完成
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and (dgsstatus=3 or dgsstatus=4) $countquery_sql";
			$condition_sql = "select o.*,d.recarnumber,d.recaroptname,d.recardate from ordermain as o left join orderdgs as d on d.orderid = o.pkid  where status=-9 and (dgsstatus=3 or dgsstatus=4) $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		//操作按钮
		for ($i = 0; $i < count($result); $i++) {
			$btnOut = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openOut('".$result[$i]['pkid']."')\"><i class='fa fa-bookmark-o'></i> &nbsp;出库</a></div>";
			$btnIn = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openIn('".$result[$i]['pkid']."')\"><i class='fa fa-bookmark-o'></i> &nbsp;入库</a></div>";
			$showBtn = "";
			if($result[$i]['dgsstatus']==1){
				$showBtn = $btnOut;
			}else if($result[$i]['dgsstatus']==2){
				$showBtn = $btnIn;
			}
			$date_format = date("Y-m-d H:i:s", $result[$i]['buytime']);
			if(!empty($result[$i]['recardate'])){
				$recardate_format = date("Y-m-d H:i:s", $result[$i]['recardate']);	
			}else{
				$recardate_format="";
			}
			
			$records["aaData"][] = array("单号: " . $result[$i]['pkid'] . "<br/>时间: " . $date_format,  "<span class='font-highlight-custom'>" . $result[$i]['buyername'] . "</span>", $result[$i]['recarnumber'], $recardate_format, $result[$i]['recaroptname'], "<div><a class='btn btn-xs default btn-editable' data-toggle='modal' onclick=\"openOrderDetail('" . $result[$i]['pkid'] . "')\">
			<i class='fa fa-search-plus'></i> 详情</a></div>" .$showBtn);
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records);
	}	
	
	public function findProductOrderByPkid($pkid){
        $querymain = M('Ordermain');
        $datamain = $querymain->join("orderdgs as d on d.orderid = ordermain.pkid","LEFT")->where("ordermain.pkid='$pkid'")->find();
        if(empty($datamain)!=1){
            
            $datamain['buytime'] = date("Y-m-d H:i", $datamain['buytime']);
			$datamain['recardate'] = date("Y-m-d H:i", $datamain['recardate']);
//			$datamain['sendtime'] = date("Y-m-d H:i", $datamain['sendtime']);
            $query = new \Think\Model();
			$sql = "select * from orderdetail where orderid='$pkid'";
			$datamain['itemlist'] = $query -> query($sql);
            echo json_encode($datamain,JSON_UNESCAPED_UNICODE);
        }else {
            echo "no";
        }
    }

	public function send($bid){
		$obj = getObjFromPost(["carnumber","outjing","outmao","outpi","outtype"]);
		$dao_main = M("Ordermain");
		$dao_dgs = M("Orderdgs");
		$data_main['dgsstatus'] = 2;
		$data_dgs = $obj;
		$data_dgs["carnumber"] = base64_decode($obj['carnumber']);
		$data_dgs["outoptname"] = session("name");
		$data_dgs["outoptid"] = session("userid");
		$data_dgs["outoptdate"] = time();
		$dao_dgs->where("orderid='".$bid."'")->save($data_dgs);
		$dao_main->where("pkid='".$bid."'")->save($data_main);
		echo "yes";
	}
	
	public function doin($bid){
		$obj = getObjFromPost(["incarnumber","cun","huiempty","huifull","huishou","weight"]);
		$dao_main = M("Ordermain");
		$dao_dgs = M("Orderdgs");
		$data_main['dgsstatus'] = 3;
		$data_dgs["incarnumber"] = base64_decode($obj['incarnumber']);
		$data_dgs["cun"] = base64_decode($obj['cun']);
		$data_dgs["huiempty"] = base64_decode($obj['huiempty']);
		$data_dgs["huifull"] = base64_decode($obj['huifull']);
		$data_dgs["huishou"] = base64_decode($obj['huishou']);
		$data_dgs["weight"] = base64_decode($obj['weight']);
		$data_dgs["inoptname"] = session("name");
		$data_dgs["inoptid"] = session("userid");
		$data_dgs["inoptdate"] = time();
		$dao_dgs->where("orderid='".$bid."'")->save($data_dgs);
		$dao_main->where("pkid='".$bid."'")->save($data_main);
		echo "yes";
	}
	
	
}
