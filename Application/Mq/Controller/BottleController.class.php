<?php
namespace Mq\Controller;
use Think\Controller;

class BottleController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	/**
	 * 查询客户列表.
	 */
	public function findbottlemain() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$name = $_REQUEST['name_search'];
		$mobile = $_REQUEST['mobile_search'];
		$membertype = $_REQUEST['membertype_search'];
		if (!empty($name)) {
			$query_sql = $query_sql . " and m.realname like '%$name%'";
			$countquery_sql = $countquery_sql . " and m.realname like '%$name%'";
		}
		if (!empty($mobile)) {
			$query_sql = $query_sql . " and m.mobile LIKE '%$mobile%'";
			$countquery_sql = $countquery_sql . " and m.mobile LIKE '%$mobile%'";
		}
		if (!empty($membertype)) {
			$query_sql = $query_sql . " and m.membertype = $membertype";
			$countquery_sql = $countquery_sql . " and m.membertype = $membertype";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from memberinfo as m where 1=1 $countquery_sql order by m.regtime desc";
		$condition_sql = "select * from memberinfo as m where 1=1 $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnbottle = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openBottle('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$result[$i]['mobile']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;钢瓶设置&nbsp;</a>";					
			if($result[$i]['membertype'] == 1){
				$membertype = "居民用户";
			}else if($result[$i]['membertype'] == 2){
				$membertype = "小工商";
			}else if($result[$i]['membertype'] == 3){
				$membertype = "大工商";
			}
			$records["aaData"][] = array($result[$i]['realname'],$result[$i]['mobile'],$membertype,$btnbottle);
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
	 * 查询客户钢瓶明细列表.
	 */
	public function findBottle($memberid) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$optdate = $_REQUEST['optdate_search'];
		$pname = $_REQUEST['pname_search'];
		$fname = $_REQUEST['fname_search'];
		$type = $_REQUEST['type_search'];
		if (!empty($optdate) && ($optdate == date('Y-m-d',strtotime($optdate))) ) {
			$optdate = strtotime($optdate);
			$query_sql = $query_sql . " and optdate = $optdate";
			$countquery_sql = $countquery_sql . " and optdate = $optdate";
		}
		if (!empty($pname)) {
			$query_sql = $query_sql . " and pname like '%$pname%'";
			$countquery_sql = $countquery_sql . " and pname like '%$pname%'";
		}
		if (!empty($fname)) {
			$query_sql = $query_sql . " and fname like '%$fname%'";
			$countquery_sql = $countquery_sql . " and fname like '%$fname%'";
		}
		if (!empty($type)) {
			$query_sql = $query_sql . " and type = $type";
			$countquery_sql = $countquery_sql . " and type like $type";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		
		$count_sql = "select count(*) as totalrecord from bottle as m where memberid='$memberid' $countquery_sql";
		$condition_sql = "select * from bottle where memberid='$memberid' $query_sql order by optdate desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";			
			if(empty($result[$i]['optdate'])){
				$optdate = "";
			}else{
				$optdate = date('Y-m-d',$result[$i]['optdate']);
			}			
			$records["aaData"][] = array($optdate,$result[$i]['pname'],$result[$i]['fname'],getTypeStr($result[$i]['type']),$result[$i]['optnumber'],$btnEdit.$btnDel);
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
	 * 删除门店片区信息.
	 */
	public function delbottle($pkid) {
		$dao = M("Bottle");
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savebottle(){
		$obj = getObjFromPost(array("receipt","pid","pname","jid","jname","rid","rname","optdate","optnumber","price","incash","outcash","changetype","type","fid","fname","departmentid","deparmentname","remark","memberid","membername"));		
		$obj['pkid'] = uniqid();
		$obj['userid'] = session("userid");
		$obj['username'] = session("name");
		$obj['optdate'] = time();
		$dao = M("Bottle");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editbottle(){
		$obj = getObjFromPost(array("pkid","receipt","pid","pname","jid","jname","rid","rname","optdate","optnumber","price","incash","outcash","changetype","type","fid","fname","departmentid","deparmentname","remark","memberid","membername"));
		$pkid = $obj['pkid'];
		$obj['userid'] = session("userid");
		$obj['username'] = session("name");
		$obj['optdate'] = time();
		$dao = M("Bottle");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loadbottle($pkid){
		$dao = M("Bottle");
		$result = $dao->join("memberinfo on memberinfo.pkid = bottle.memberid","LEFT")->where("bottle.pkid = '$pkid'")->field("bottle.*,memberinfo.mobile")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
}
