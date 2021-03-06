<?php
namespace Mq\Controller;
use Think\Controller;

class RoleController extends Controller {

	public function _initialize() {
		putHeader();
	}


	/**
	 * 查询话务、业务人员列表.
	 */
	public function findRoleByStatus($status) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$realname = $_REQUEST['realname_search'];
		$mobile = $_REQUEST['mobile_search'];
		$name = $_REQUEST['name_search'];
		if (!empty($realname)) {
			$query_sql = $query_sql . " and realname like '%$realname%'";
			$countquery_sql = $countquery_sql . " and realname like '%$realname%'";
		}
		if ($mobile!="") {
			$query_sql = $query_sql . " and mobile LIKE '%$mobile%'";
			$countquery_sql = $countquery_sql . " and mobile LIKE '%$mobile%'";
		}
		if ($name!="") {
			$query_sql = $query_sql . " and name LIKE '%$name%'";
			$countquery_sql = $countquery_sql . " and name LIKE '%$name%'";			
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		if ($status == 'huawu') {
			$roleval=1;
		}else if ($status == 'biz') {
			$roleval=2;
		}else if ($status == 'caiwu') {
			$roleval=3;
		}else if ($status == 'piaofang') {
			$roleval=4;
		}else if ($status == 'siji') {
			$roleval=5;
		}else if ($status == 'songqi') {
			$roleval=6;
		}else if ($status == 'yayun') {
			$roleval=7;
		}else if ($status == 'yingye') {
			$roleval=8;
		}else if ($status == 'chedui') {
			$roleval=9;
		}else if ($status == 'guanli') {
			$roleval=10;
		}
		$count_sql = "select count(*) as totalrecord from userinfo where role=$roleval $countquery_sql";
		$condition_sql = "select * from userinfo where role=$roleval $query_sql limit $iDisplayStart,$iDisplayLength";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pid']."','".$iDisplayStart."','".$status."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$btnReset = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openResetConfirm('".$result[$i]['pid']."','".$iDisplayStart."')\"><i class='fa fa-refresh'></i> &nbsp;重置密码&nbsp;</a>";
			$records["aaData"][] = array($result[$i]['realname'] ,  $result[$i]['name'],$result[$i]['mobile'],$btnEdit.$btnDel.$btnReset);
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
	 * 删除员工信息.
	 */
	public function delworker($pkid) {
		$dao = M("Userinfo");		
		$userdata = $dao->where("pid='$pkid'")->find();
		addLog(4,session("userid"),"删除员工".$userdata['realname']."(".$userdata['name'].")");
		$dao->where("pid='$pkid'")->delete();
		echo "yes";
	}

	/**
	 * 重置员工密码.
	 */
	public function resetworker($pkid) {
		$dao = M("Userinfo");
		$data['password'] = md5("123456");
		$dao->where("pid='$pkid'")->save($data);
		$userdata = $dao->where("pid='$pkid'")->find();
		addLog(4,session("userid"),"重置员工".$userdata['realname']."(".$userdata['name'].")的密码");
		echo "yes";
	}

	public function saveUserSetting(){
		$obj = getObjFromPost(array("name","realname","mobile","email","password","worknumber","role","did"));
		$obj['pid'] = uniqid();
		$obj['isadmin'] = 0;
		$obj['password'] = md5($obj['password']);
		$dao = M("Userinfo");
		$dao->add($obj);
		addLog(4,session("userid"),"新增员工".$obj['realname']."(".$obj['name'].")信息");
		echo "yes";
	}
	
	public function editUserSetting(){
		$obj = getObjFromPost(array("pid","name","realname","mobile","email","worknumber","did"));
		$dao = M("Userinfo");
		$pid = $obj['pid'];
		$dao->where("pid='$pid'")->save($obj);
		addLog(4,session("userid"),"修改员工".$obj['realname']."(".$obj['name'].")信息");
		echo "yes";
	}
	
	public function loadUserSetting($pid){
		$dao = M("Userinfo");
		$result = $dao->where("pid='$pid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 查询门店片区列表.
	 */
	public function findDepartment() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$dname = $_REQUEST['dname_search'];
		$pname = $_REQUEST['pname_search'];
		if (!empty($dname)) {
			$query_sql = $query_sql . " and dname like '%$dname%'";
			$countquery_sql = $countquery_sql . " and dname like '%$dname%'";
		}
		if ($pname!="") {
			$query_sql = $query_sql . " and pname LIKE '%$pname%'";
			$countquery_sql = $countquery_sql . " and pname LIKE '%$pname%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from departmentdetail where 1=1 $countquery_sql";
		$condition_sql = "select * from departmentdetail where 1=1 $query_sql limit $iDisplayStart,$iDisplayLength";
		
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
			
			$records["aaData"][] = array($result[$i]['dname'] ,  $result[$i]['pname'],$result[$i]['remark'],$btnEdit.$btnDel);
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
	public function deldepartmentdetail($pkid) {
		$dao = M("Departmentdetail");				
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savedepartmentdetail(){
		$obj = getObjFromPost(array("pname","dname","did","remark"));
		$obj['pkid'] = uniqid();
		$dao = M("Departmentdetail");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editdepartmentdetail(){
		$obj = getObjFromPost(array("pkid","pname","dname","did","remark"));
		$pkid = $obj["pkid"];
		$dao = M("Departmentdetail");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loaddepartment(){
		$dao = M("Department");
		$result = $dao->where("flag = 1")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loaddepartmentdetail($pkid){
		$dao = M("Departmentdetail");
		$result = $dao->where("pkid = '$pkid'")->find();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 查询车辆列表.
	 */
	public function findCars() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$carnumber = $_REQUEST['carnumber_search'];
		if (!empty($carnumber)) {
			$query_sql = $query_sql . " and carnumber like '%$carnumber%'";
			$countquery_sql = $countquery_sql . " and carnumber like '%$carnumber%'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from cars where 1=1 $countquery_sql";
		$condition_sql = "select * from cars where 1=1 $query_sql limit $iDisplayStart,$iDisplayLength";
		
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
			
			$records["aaData"][] = array($result[$i]['carnumber'] ,  $result[$i]['type'],$result[$i]['remark'],$btnEdit.$btnDel);
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
	 * 删除车辆信息.
	 */
	public function delcar($pkid) {
		$dao = M("Cars");				
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savecar(){
		$obj = getObjFromPost(array("carnumber","type","remark"));
		$obj['pkid'] = uniqid();
		$dao = M("Cars");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editcar(){
		$obj = getObjFromPost(array("pkid","carnumber","type","remark"));
		$pkid = $obj["pkid"];
		$dao = M("Cars");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	
	public function loadcar($pkid){
		$dao = M("Cars");
		$result = $dao->where("pkid = '$pkid'")->find();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
}
