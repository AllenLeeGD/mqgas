<?php
namespace Mq\Controller;
use Think\Controller;

class CheckController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	/**
	 * 查询客户安检及回访列表.
	 */
	public function findCheckrecall() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$name = $_REQUEST['name_search'];
		$mobile = $_REQUEST['mobile_search'];
		if (!empty($name)) {
			$query_sql = $query_sql . " and m.realname like '%$name%'";
			$countquery_sql = $countquery_sql . " and m.realname like '%$name%'";
		}
		if (!empty($mobile)) {
			$query_sql = $query_sql . " and m.mobile LIKE '%$mobile%'";
			$countquery_sql = $countquery_sql . " and m.mobile LIKE '%$mobile%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from memberinfo as m where 1=1 $countquery_sql order by m.regtime desc";
		$condition_sql = "select m.pkid,m.realname,m.mobile,(select max(checkdate) from safecheck as s where s.memberid = m.pkid) as checkdate,(select max(optdate) from recall as r where r.memberid = m.pkid) as optdate from memberinfo as m where 1=1 $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnChecksafe = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openCheck('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;安检&nbsp;</a>";
			$btnRecall = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openRecall('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;回访&nbsp;</a>";			
			if(empty($result[$i]['checkdate'])){
				$checkdate = "";
			}else{
				$checkdate = date('Y-m-d',$result[$i]['checkdate']);
			}
			
			if(empty($result[$i]['optdate'])){
				$optdate = "";
			}else{
				$optdate = date('Y-m-d',$result[$i]['optdate']);
			}
			$records["aaData"][] = array($result[$i]['realname'],$result[$i]['mobile'],$checkdate,$optdate,$btnChecksafe.$btnRecall);
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
	 * 查询客户安检列表.
	 */
	public function findCheck($memberid) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$checkdate = $_REQUEST['checkdate_search'];
		if (!empty($checkdate) && ($checkdate == date('Y-m-d',strtotime($checkdate))) ) {
			$checkdate = strtotime($checkdate);
			$query_sql = $query_sql . " and checkdate = $checkdate";
			$countquery_sql = $countquery_sql . " and checkdate = $checkdate";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		
		$count_sql = "select count(*) as totalrecord from safecheck as m where memberid='$memberid' $countquery_sql";
		$condition_sql = "select * from safecheck where memberid='$memberid' $query_sql order by checkdate desc limit $iDisplayStart,$iDisplayLength ";
		
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
			if(empty($result[$i]['checkdate'])){
				$checkdate = "";
			}else{
				$checkdate = date('Y-m-d',$result[$i]['checkdate']);
			}			
			$records["aaData"][] = array($checkdate,$result[$i]['remark'],$btnEdit.$btnDel);
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
	public function delcheck($pkid) {
		$dao = M("Safecheck");
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savecheck(){
		$obj = getObjFromPost(array("checkdate","memberid","remark"));
		$obj['checkdate'] = strtotime($obj['checkdate']);
		$obj['pkid'] = uniqid();
		$dao = M("Safecheck");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editcheck(){
		$obj = getObjFromPost(array("pkid","checkdate","remark"));
		$obj['checkdate'] = strtotime($obj['checkdate']);
		$pkid = $obj["pkid"];
		$dao = M("Safecheck");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loadcheck($pkid){
		$dao = M("Safecheck");
		$result = $dao->where("pkid = '$pkid'")->find();
		$result['checkdate'] = date('Y-m-d',$result['checkdate']);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	
	/**
	 * 查询客户安检列表.
	 */
	public function findRecall($memberid) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$optdate = $_REQUEST['optdate_search'];
		if (!empty($optdate) && ($optdate == date('Y-m-d',strtotime($optdate))) ) {
			$optdate = strtotime($optdate);
			$query_sql = $query_sql . " and optdate = $optdate";
			$countquery_sql = $countquery_sql . " and optdate = $optdate";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		
		$count_sql = "select count(*) as totalrecord from recall as m where memberid='$memberid' $countquery_sql";
		$condition_sql = "select * from recall where memberid='$memberid' $query_sql order by optdate desc limit $iDisplayStart,$iDisplayLength ";
		
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
			$records["aaData"][] = array($optdate,$result[$i]['remark'],$btnEdit.$btnDel);
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
	public function delrecall($pkid) {
		$dao = M("Recall");
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function saverecall(){
		$obj = getObjFromPost(array("optdate","memberid","remark","departmentid","dname"));
		$obj['optdate'] = strtotime($obj['optdate']);
		$obj['pkid'] = uniqid();
		$obj['addmemberid'] = session("userid");
		$obj['addmembername'] = session("name");
		$dao = M("Recall");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editrecall(){
		$obj = getObjFromPost(array("pkid","optdate","remark","departmentid","dname"));
		$obj['optdate'] = strtotime($obj['optdate']);
		$pkid = $obj["pkid"];
		$dao = M("Recall");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loadrecall($pkid){
		$dao = M("Recall");
		$result = $dao->where("pkid = '$pkid'")->find();
		$result['optdate'] = date('Y-m-d',$result['optdate']);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 查询待处理的回访列表.
	 */
	public function findRecallopt() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$dname = $_REQUEST['dname_search'];
		if (!empty($dname)) {
			$query_sql = $query_sql . " and dname like '%$dname%'";
			$countquery_sql = $countquery_sql . " and dname like '%$dname%'";
		}
		$status_search = $_REQUEST['status_search'];
		if ($status_search=="未处理") {
			$query_sql = $query_sql . " and status = 0";
			$countquery_sql = $countquery_sql . " and status = 0";
		}else if ($status_search=="已处理"){
			$query_sql = $query_sql . " and status = 1";
			$countquery_sql = $countquery_sql . " and status = 1";
		}
		$optdate = $_REQUEST['optdate_search'];
		if (!empty($optdate) && ($optdate == date('Y-m-d',strtotime($optdate))) ) {
			$optdate = strtotime($optdate);
			$query_sql = $query_sql . " and optdate = $optdate";
			$countquery_sql = $countquery_sql . " and optdate = $optdate";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from recall as m where 1=1 $countquery_sql order by status desc";
		$condition_sql = "select * from recall where 1=1 $query_sql order by status desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnOpt = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openOpt('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;处理回访&nbsp;</a>";
			$btnView = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openView('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-eye'></i> &nbsp;查看回访&nbsp;</a>";			
			if(empty($result[$i]['checkdate'])){
				$checkdate = "";
			}else{
				$checkdate = date('Y-m-d',$result[$i]['checkdate']);
			}
			
			if(empty($result[$i]['optdate'])){
				$optdate = "";
			}else{
				$optdate = date('Y-m-d',$result[$i]['optdate']);
			}
			if($result[$i]['status'] ==0){
				$s_status = "未处理";
			}else{
				$s_status = "已处理";
			}
			$s_remark = $result[$i]['remark'];
//			if(strlen($s_remark)>=20){
//				$s_remark = substr($s_remark,0,23)."...";
//			}
			$records["aaData"][] = array($optdate,$result[$i]['dname'],$s_remark,$s_status,$btnOpt.$btnView);
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

	public function optrecall(){
		$obj = getObjFromPost(array("pkid","optremark"));
		$pkid = $obj["pkid"];
		$obj["optmemberid"] = session("userid");
		$obj["optmembername"] = session("name");
		$obj["opttime"] = time();
		$obj["status"] = 1;
		$dao = M("Recall");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
}
