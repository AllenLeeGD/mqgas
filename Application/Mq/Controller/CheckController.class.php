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
		
		
		$count_sql = "select count(*) as totalrecord from safecheck as m where 1=1 $countquery_sql";
		$condition_sql = "select * from safecheck where 1=1 $query_sql order by checkdate desc limit $iDisplayStart,$iDisplayLength ";
		
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
}
