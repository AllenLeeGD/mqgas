<?php
namespace Mq\Controller;
use Think\Controller;

class JMOrderController extends Controller {

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
		$mobile_search = $_REQUEST['mobile_search'];
		if (!empty($orderid)) {
			$query_sql = $query_sql . " and o.pkid like '%$orderid%'";
			$countquery_sql = $countquery_sql . " and o.pkid like '%$orderid%'";
		}
		if ($buyername!="") {
			$query_sql = $query_sql . " and o.buyername LIKE '%$buyername%'";
			$countquery_sql = $countquery_sql . " and o.buyername LIKE '%$buyername%'";
		}
		if ($mobile_search!="") {
			$query_sql = $query_sql . " and o.buyermobile LIKE '%$mobile_search%'";
			$countquery_sql = $countquery_sql . " and o.buyermobile LIKE '%$mobile_search%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);		
		if ($status == 0) {//暂存
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=0  $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=0 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 1) {//待分派片区
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=1 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=1 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 2) {//其他的
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and (jmstatus=2 or jmstatus=3 or jmstatus=4 or jmstatus=5 or jmstatus=6)  $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and (jmstatus=2 or jmstatus=3 or jmstatus=4 or jmstatus=5 or jmstatus=6)  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 3) {//待分配
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=2  $countquery_sql";
			$condition_sql = "select o.*,d.mid from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=2 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 4) {//待收款
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=3 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=3 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 5) {//待存款
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=4 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=4  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}if ($status == 6) {//待核款
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=5 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=5  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
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
			$btnEdit = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openOut('".$result[$i]['pkid']."')\"><i class='fa fa-pencil'></i> &nbsp;编辑</a></div>";
			$btnSend = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openSend('".$result[$i]['pkid']."')\"><i class='fa fa-bookmark-o'></i> &nbsp;发送</a></div>";
			$btnCancle = "<div class=\"margin-top-10\"><a class='btn btn-xs red default'  data-toggle='modal' onclick=\"openCancle('".$result[$i]['pkid']."')\"><i class='fa fa-ban'></i> &nbsp;取消</a></div>";			
			$btnFen = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openFen('".$result[$i]['pkid']."','".$result[$i]['buyeraddress']."')\"><i class='fa fa-puzzle-piece'></i> &nbsp;分派</a></div>";
			$btnPei = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openPei('".$result[$i]['pkid']."','".$result[$i]['mid']."')\"><i class='fa fa-puzzle-piece'></i> &nbsp;分配</a></div>";
			$btnShou = "<div class=\"margin-top-10\"><a class='btn btn-xs yellow default'  data-toggle='modal' onclick=\"openShou('".$result[$i]['pkid']."')\"><i class='fa fa-ticket'></i> &nbsp;收款</a></div>";
			$btnCun = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openCun('".$result[$i]['pkid']."')\"><i class='fa fa-suitcase'></i> &nbsp;存款</a></div>";
			$btnHe = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openHe('".$result[$i]['pkid']."')\"><i class='fa fa-suitcase'></i> &nbsp;核款</a></div>";
			$showBtn = "";
			if($status==0){
				$showBtn = $btnEdit.$btnSend.$btnCancle;
			}else if($status==1){
				$showBtn = $btnFen.$btnCancle;
			}else if($status==2){
				$showBtn = $btnCancle;
			}else if($status==3){
				$showBtn = $btnPei;
			}else if($status==4){
				$showBtn = $btnShou;
			}else if($status==5){
				$showBtn = $btnCun;
			}else if($status==6){
				$showBtn = $btnHe;
			}
			$statusStr = getNewStatus($result[$i]['status'], $result[$i]['jmstatus'], $result[$i]['dgsstatus'], $result[$i]['hspstatus']);
			$date_format = date("Y-m-d H:i:s", $result[$i]['buytime']);
			$recardate_format = date("Y-m-d H:i:s", $result[$i]['recardate']);
			$records["aaData"][] = array("单号: " . $result[$i]['pkid'] . "<br/>时间: " . $date_format. "<br/>状态: " .$statusStr,  "<span class='font-highlight-custom'>" . $result[$i]['buyername'] . "</span>", $result[$i]['buyeraddress'], $result[$i]['buyermobile'], $result[$i]['price'], "<div><a class='btn btn-xs default btn-editable' data-toggle='modal' onclick=\"openOrderDetail('" . $result[$i]['pkid'] . "')\">
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

	/**
	 * 取消订单.
	 * @bid 订单ID
	 */
	public function cancle($bid){
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = -1;
		$data_jm['cancletime'] = time();
		$data_jm['cancleoptid'] = session("userid");
		$data_jm['cancleoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
	
	/**
	 * 发送订单.
	 * @bid 订单ID
	 */
	public function send($bid){
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 1;
		$data['sendtime'] = time();
		$data_jm['optorderid'] = session("userid");
		$data_jm['optordername'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
	
	/**
	 * 分派订单.
	 * @bid 订单ID
	 */
	public function dofen($bid){
		$obj = getObjFromPost(array("did","pid",'dname','pname'));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 2;
		$data_jm['mid'] = $obj['did'];
		$data_jm['mname'] = $obj['dname'];
		$data_jm['pid'] = $obj['pid'];
		$data_jm['pname'] = $obj['pname'];
		$data_jm['fenpaiid'] = session("userid");
		$data_jm['fenpainame'] = session("name");
		$data_jm['fenpaitime'] = time();
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
	
	public function loadSongqis($did){
		$dao  = M("Songqidaily");
		$result = $dao->where("did = '$did' and from_unixtime(dailydate,'%Y-%m-%d') = date_format(now(), '%Y-%m-%d')")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadCars($did){
		$dao  = M("Carsdaily");
		$result = $dao->where("did = '$did' and from_unixtime(dailydate,'%Y-%m-%d') = date_format(now(), '%Y-%m-%d')")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 分派订单.
	 * @bid 订单ID
	 */
	public function dopei($bid){
		$obj = getObjFromPost(array("songqiid","carid",'songqiname','carnumber'));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 3;
		$data_jm['songqiid'] = $obj['songqiid'];
		$data_jm['songqiname'] = $obj['songqiname'];
		$data_jm['carid'] = $obj['carid'];
		$data_jm['carnumber'] = $obj['carnumber'];
		$data_jm['setpeopleoptid'] = session("userid");
		$data_jm['setpeopleoptname'] = session("name");
		$data_jm['setpeopleopttime'] = time();
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
	
	/**
	 * 订单收款.
	 * @bid 订单ID
	 */
	public function shou($bid){
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 4;
		$data_jm['shoutime'] = time();
		$data_jm['shouoptid'] = session("userid");
		$data_jm['shouoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
	
	/**
	 * 订单收款.
	 * @bid 订单ID
	 */
	public function cun($bid){
		$obj = getObjFromPost(array("cunmsg"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 5;
		$data_jm['cunmsg'] = $obj['cunmsg'];
		$data_jm['cuntime'] = time();
		$data_jm['cunoptid'] = session("userid");
		$data_jm['cunoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
	
	/**
	 * 订单核款.
	 * @bid 订单ID
	 */
	public function he($bid){
		$obj = getObjFromPost(array("hemsg"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 6;
		$data_jm['hemsg'] = $obj['hemsg'];
		$data_jm['hetime'] = time();
		$data_jm['heoptid'] = session("userid");
		$data_jm['heoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		echo "yes";
	}
}
