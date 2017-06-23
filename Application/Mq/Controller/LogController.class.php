<?php
namespace Mq\Controller;
use Think\Controller;

class LogController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findLogsByStatus($status) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$username = $_REQUEST['username_search'];
		$remark = $_REQUEST['remark_search'];
		if (!empty($username)) {
			$query_sql = $query_sql . " and username like '%$username%'";
			$countquery_sql = $countquery_sql . " and username like '%$username%'";
		}
		if ($remark!="") {
			$query_sql = $query_sql . " and remark LIKE '%$remark%'";
			$countquery_sql = $countquery_sql . " and remark LIKE '%$remark%'";			
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$count_sql = "select count(*) as totalrecord from optlog where typeval=$status $countquery_sql";
		$condition_sql = "select * from optlog where typeval=$status $query_sql order by opttime desc limit $iDisplayStart,$iDisplayLength";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$opttime = date('Y-m-d H:i:s',$result[$i]['opttime']);		
			$records["aaData"][] = array($result[$i]['username'] , $opttime ,$result[$i]['remark'],"");
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

}
