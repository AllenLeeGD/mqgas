<?php
namespace Mq\Controller;
use Think\Controller;

class CWController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	
	/**
	 * 查询财务收款列表.
	 */
	public function findCwsk() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$cname = $_REQUEST['cname_search'];
		if (!empty($cname)) {
			$query_sql = $query_sql . " and cname like '%$cname%'";
			$countquery_sql = $countquery_sql . " and cname like '%$cname%'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from cwsk where 1=1 $countquery_sql";
		$condition_sql = "select * from cwsk where 1=1 $query_sql order by opttime desc limit $iDisplayStart,$iDisplayLength";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnView = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openView('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;查看&nbsp;</a>";
			$btnEdit = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$opttime = date('Y-m-d',$result[$i]['opttime']);
			$records["aaData"][] = array($result[$i]['cname'] , $opttime ,$result[$i]['totalmoney'],$btnView.$btnEdit.$btnDel);
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
	public function delcwsk($pkid) {
		$dao = M("Cwsk");				
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savecwsk(){
		$obj = getObjFromPost(array("cname","membercode","opttime","totalmoney","bankmsg","remark"));
		$obj['pkid'] = uniqid();
		$obj['opttime'] = strtotime($obj['opttime']);
		$dao = M("Cwsk");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editcwsk(){
		$obj = getObjFromPost(array("pkid","cname","membercode","opttime","totalmoney","bankmsg","remark"));
		$pkid = $obj["pkid"];
		$obj['opttime'] = strtotime($obj['opttime']);
		$dao = M("Cwsk");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	
	public function loadcwsk($pkid){
		$dao = M("Cwsk");
		$result = $dao->where("pkid = '$pkid'")->find();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 查询财务瓶换瓶收据列表.
	 */
	public function findCwphp() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$bname = $_REQUEST['bname_search'];
		$lname = $_REQUEST['lname_search'];
		$snumber = $_REQUEST['snumber_search'];
		if (!empty($bname)) {
			$query_sql = $query_sql . " and bname like '%$bname%'";
			$countquery_sql = $countquery_sql . " and bname like '%$bname%'";
		}
		if (!empty($lname)) {
			$query_sql = $query_sql . " and lname like '%$lname%'";
			$countquery_sql = $countquery_sql . " and lname like '%$lname%'";
		}
		if (!empty($snumber)) {
			$query_sql = $query_sql . " and snumber like '%$snumber%'";
			$countquery_sql = $countquery_sql . " and snumber like '%$snumber%'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from cwphp where 1=1 $countquery_sql";
		$condition_sql = "select * from cwphp where 1=1 $query_sql limit $iDisplayStart,$iDisplayLength";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnView = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openView('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;查看&nbsp;</a>";
			$btnEdit = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$opttime = date('Y-m-d',$result[$i]['opttime']);
			$records["aaData"][] = array($result[$i]['bname'] , $opttime ,$result[$i]['totalmoney'],$result[$i]['snumber'],$result[$i]['lname'],$btnView.$btnEdit.$btnDel);
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
	 * 删除信息.
	 */
	public function delcwphp($pkid) {
		$dao = M("Cwphp");				
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savecwphp(){
		$obj = getObjFromPost(array("bname","lname","snumber","opttime","totalmoney","msg","remark"));
		$obj['pkid'] = uniqid();
		$obj['opttime'] = strtotime($obj['opttime']);
		$dao = M("Cwphp");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editcwphp(){
		$obj = getObjFromPost(array("pkid","bname","lname","snumber","opttime","totalmoney","msg","remark"));
		$pkid = $obj["pkid"];
		$obj['opttime'] = strtotime($obj['opttime']);
		$dao = M("Cwphp");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	
	public function loadcwphp($pkid){
		$dao = M("Cwphp");
		$result = $dao->where("pkid = '$pkid'")->find();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 查询码头收据列表.
	 */
	public function findCwmtsj($type) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$bname = $_REQUEST['bname_search'];
		$lname = $_REQUEST['lname_search'];
		$snumber = $_REQUEST['snumber_search'];
		if (!empty($bname)) {
			$query_sql = $query_sql . " and bname like '%$bname%'";
			$countquery_sql = $countquery_sql . " and bname like '%$bname%'";
		}
		if (!empty($lname)) {
			$query_sql = $query_sql . " and lname like '%$lname%'";
			$countquery_sql = $countquery_sql . " and lname like '%$lname%'";
		}
		if (!empty($snumber)) {
			$query_sql = $query_sql . " and snumber like '%$snumber%'";
			$countquery_sql = $countquery_sql . " and snumber like '%$snumber%'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from cwmtsj where type=$type $countquery_sql";
		$condition_sql = "select * from cwmtsj where type=$type $query_sql limit $iDisplayStart,$iDisplayLength";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnView = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openView('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;查看&nbsp;</a>";
			$btnEdit = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$opttime = date('Y-m-d',$result[$i]['opttime']);
			if($result[$i]['status']==0){
				$status_str = "可用";
			}else{
				$status_str = "作废";
			}
			$records["aaData"][] = array($result[$i]['bname'] , $opttime ,$result[$i]['totalmoney'],$result[$i]['snumber'],$result[$i]['lname'],$status_str,$btnView.$btnEdit.$btnDel);
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
	 * 删除信息.
	 */
	public function delcwmtsj($pkid) {
		$dao = M("Cwmtsj");				
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savecwmtsj(){
		$obj = getObjFromPost(array("bname","lname","snumber","opttime","totalmoney","msg","remark","type","status","syqk"));
		$obj['pkid'] = uniqid();
		$obj['opttime'] = strtotime($obj['opttime']);
		$dao = M("Cwmtsj");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editcwmtsj(){
		$obj = getObjFromPost(array("pkid","bname","lname","snumber","opttime","totalmoney","msg","remark","type","status","syqk"));
		$pkid = $obj["pkid"];
		$obj['opttime'] = strtotime($obj['opttime']);
		$dao = M("Cwmtsj");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	
	public function loadcwmtsj($pkid){
		$dao = M("Cwmtsj");
		$result = $dao->where("pkid = '$pkid'")->find();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
}
