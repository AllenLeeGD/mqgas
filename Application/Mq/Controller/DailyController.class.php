<?php
namespace Mq\Controller;
use Think\Controller;

class DailyController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	/**
	 * 查询门店片区列表.
	 */
	public function findCarsdaily() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$dailydate = $_REQUEST['dailydate_search'];
		$dname = $_REQUEST['dname_search'];
		$carnumber = $_REQUEST['carnumber_search'];
		if (!empty($dailydate) && ($dailydate == date('Y-m-d',strtotime($dailydate))) ) {
			$dailydate = strtotime($dailydate);
			$query_sql = $query_sql . " and dailydate = $dailydate";
			$countquery_sql = $countquery_sql . " and dailydate = $dailydate";
		}
		if (!empty($dname)) {
			$query_sql = $query_sql . " and dname LIKE '%$dname%'";
			$countquery_sql = $countquery_sql . " and dname LIKE '%$dname%'";
		}
		if (!empty($carnumber)) {
			$query_sql = $query_sql . " and carnumber LIKE '%$carnumber%'";
			$countquery_sql = $countquery_sql . " and carnumber LIKE '%$carnumber%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from carsdaily where status=0 $countquery_sql order by dailydate desc";
		$condition_sql = "select * from carsdaily where status=0 $query_sql order by dailydate desc limit $iDisplayStart,$iDisplayLength ";
		
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
			$dailydate = date('Y-m-d',$result[$i]['dailydate']);
			$records["aaData"][] = array($dailydate ,  $result[$i]['dname'],$result[$i]['pname'],$result[$i]['carnumber'],$result[$i]['sname'],$result[$i]['yname'],$btnEdit.$btnDel);
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
	public function delcarsdaily($pkid) {
		$dao = M("Carsdaily");
		$data['status'] = -1;
		$dao->where("pkid='$pkid'")->save($data);
		echo "yes";
	}
	
	public function savecarsdaily(){
		$obj = getObjFromPost(array("dailydate","carid","carnumber","did","dname","pid","pname","sid","sname","yid","yname","carcourse","oilprice","cost","remark","dailyrun","errorrecord"));
		$obj['dailydate'] = strtotime($obj['dailydate']);
		$obj['pkid'] = uniqid();
		$dao = M("Carsdaily");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editcarsdaily(){
		$obj = getObjFromPost(array("pkid","dailydate","carid","carnumber","did","dname","pid","pname","sid","sname","yid","yname","carcourse","oilprice","cost","remark","dailyrun","errorrecord"));
		$obj['dailydate'] = strtotime($obj['dailydate']);
		$pkid = $obj["pkid"];
		$dao = M("Carsdaily");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loadcars(){
		$dao = M("Cars");
		$result = $dao->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loaddepartment(){
		$dao = M("Department");
		$result = $dao->where("flag = 1")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadpianqu($did){
		$dao = M("Departmentdetail");
		$result = $dao->where("did = '$did'")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadsiji(){
		$dao = M("Userinfo");
		$result = $dao->where("role=5")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadyayun(){
		$dao = M("Userinfo");
		$result = $dao->where("role=7")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadyewu(){
		$dao = M("Userinfo");
		$result = $dao->where("role=2")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadcarsdaily($pkid){
		$dao = M("Carsdaily");
		$result = $dao->where("pkid = '$pkid'")->find();
		$result['dailydate'] = date('Y-m-d',$result['dailydate']);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	
	
	/**
	 * 查询送气工日常列表.
	 */
	public function findSongqisDaily() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$dailydate = $_REQUEST['dailydate_search'];
		$dname = $_REQUEST['dname_search'];
		$sname = $_REQUEST['sname_search'];
		if (!empty($dailydate) && ($dailydate == date('Y-m-d',strtotime($dailydate))) ) {
			$dailydate = strtotime($dailydate);
			$query_sql = $query_sql . " and dailydate = $dailydate";
			$countquery_sql = $countquery_sql . " and dailydate = $dailydate";
		}
		if (!empty($dname)) {
			$query_sql = $query_sql . " and dname LIKE '%$dname%'";
			$countquery_sql = $countquery_sql . " and dname LIKE '%$dname%'";
		}
		if (!empty($sname)) {
			$query_sql = $query_sql . " and sname LIKE '%$sname%'";
			$countquery_sql = $countquery_sql . " and sname LIKE '%$sname%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from songqidaily where status=0 $countquery_sql order by dailydate desc";
		$condition_sql = "select * from songqidaily where status=0 $query_sql order by dailydate desc limit $iDisplayStart,$iDisplayLength ";
		
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
			$dailydate = date('Y-m-d',$result[$i]['dailydate']);
			$records["aaData"][] = array($dailydate ,  $result[$i]['dname'],$result[$i]['pname'],$result[$i]['sname'],$btnEdit.$btnDel);
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
	 * 删除送气工日常信息.
	 */
	public function delsongqidaily($pkid) {
		$dao = M("Songqidaily");
		$data['status'] = -1;
		$dao->where("pkid='$pkid'")->save($data);
		echo "yes";
	}
	
	public function savesongqidaily(){
		$obj = getObjFromPost(array("dailydate","did","dname","pid","pname","sid","sname","remark"));
		$obj['dailydate'] = strtotime($obj['dailydate']);
		$obj['pkid'] = uniqid();
		$dao = M("Songqidaily");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editsongqidaily(){
		$obj = getObjFromPost(array("pkid","dailydate","did","dname","pid","pname","sid","sname","remark"));
		$obj['dailydate'] = strtotime($obj['dailydate']);
		$pkid = $obj["pkid"];
		$dao = M("Songqidaily");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loadsongqi(){
		$dao = M("Userinfo");
		$result = $dao->where("role=6")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadsongqidaily($pkid){
		$dao = M("Songqidaily");
		$result = $dao->where("pkid = '$pkid'")->find();
		$result['dailydate'] = date('Y-m-d',$result['dailydate']);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
}
