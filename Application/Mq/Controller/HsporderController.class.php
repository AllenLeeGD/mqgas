<?php
namespace Mq\Controller;
use Think\Controller;

class HsporderController extends Controller {

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
		
		//根据业务员进行过滤
		$username = session("name");
		$userid = session("userid");
		if($username != "superadmin"){
			$query_sql = $query_sql . " and o.userid = '$userid'";
			$countquery_sql = $countquery_sql . " and o.userid = '$userid'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);	
		if ($status == 0) {//待入库
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderhsp as d on d.orderid = o.pkid  where status=-8 and (hspstatus=0 or hspstatus=1) $countquery_sql";
			$condition_sql = "select o.*,d.recarnumber,d.recaroptname,d.recardate from ordermain as o left join orderhsp as d on d.orderid = o.pkid  where status=-8 and (hspstatus=0 or hspstatus=1) $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 1) {//已完成
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderhsp as d on d.orderid = o.pkid  where status=-8 and (hspstatus=2 or hspstatus=3) $countquery_sql";
			$condition_sql = "select o.*,d.recarnumber,d.recaroptname,d.recardate from ordermain as o left join orderhsp as d on d.orderid = o.pkid  where status=-8 and (hspstatus=2 or hspstatus=3) $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
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
			$btnIn = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openIn('".$result[$i]['pkid']."')\"><i class='fa fa-bookmark-o'></i> &nbsp;入库</a></div>";
			$btnCancle = "<div class=\"margin-top-10\"><a class='btn btn-xs red default'  data-toggle='modal' onclick=\"openCancle('".$result[$i]['pkid']."')\"><i class='fa fa-bookmark-o'></i> &nbsp;取消</a></div>";
			$showBtn = "";
			if($result[$i]['hspstatus']==0 || $result[$i]['hspstatus']==1){
				$showBtn = $btnIn;
			}
			
			$admintype = session("admin_type");
			if($admintype=="admin" || $admintype=="huawu" || $admintype=="biz"){
				$showBtn = $showBtn . $btnCancle;
			}
			
			$date_format = date("Y-m-d H:i:s", $result[$i]['buytime']);
			$recardate_format = date("Y-m-d H:i:s", $result[$i]['recardate']);
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
        $datamain = $querymain->join("orderhsp as d on d.orderid = ordermain.pkid","LEFT")->where("ordermain.pkid='$pkid'")->find();
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
	
	public function doin($bid){
		$obj = getObjFromPost(["incarnumber","cun","huiempty","huifull","huishou"]);
		$dao_main = M("Ordermain");
		$dao_dgs = M("Orderhsp");
		$data_main['hspstatus'] = 2;
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
		addLog(1, session("userid"), "入库了订单<a href='javascript:showOrderDetail(\"".$bid."\",\"hsp\")'>".$bid."</a>");
		echo "yes";
	}
	
	public function cancle($bid){
		$dao = M("Ordermain");
		$data['hspstatus'] = -1;
		$dao->where("pkid='$bid'")->save($data);
		$hsp_dao = M("Orderhsp");
		$data_hsp['cancletime'] = time();
		$data_hsp['cancleopt'] = session('userid');
		$data_hsp['cancleoptname'] = session("name");
		$hsp_dao->where("orderid='$bid'")->save($data_hsp);
		echo "yes";
	}
}
