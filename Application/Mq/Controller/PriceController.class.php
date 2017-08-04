<?php
namespace Mq\Controller;
use Think\Controller;

class PriceController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	/**
	 * 查询客户安检及回访列表.
	 */
	public function findpricemain() {
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
		$condition_sql = "select m.pkid,m.realname,m.mobile,m.membertype from memberinfo as m where 1=1 $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnPriceset = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openSet('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$result[$i]['mobile']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;设置价格&nbsp;</a>";					
			if($result[$i]['membertype'] == 1){
				$membertype = "居民用户";
			}else if($result[$i]['membertype'] == 2){
				$membertype = "小工商";
			}else if($result[$i]['membertype'] == 3){
				$membertype = "大工商";
			}
			
			$records["aaData"][] = array($result[$i]['realname'],$result[$i]['mobile'],$membertype,$btnPriceset);
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
	 * 查询客户价格列表.
	 */
	public function findPrice($memberid) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$name = $_REQUEST['name_search'];
		if (!empty($name)) {
			$query_sql = $query_sql . " and name like '%$name%'";
			$countquery_sql = $countquery_sql . " and name like '%$name%'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		
		$count_sql = "select count(*) as totalrecord from price as m where memberid='$memberid' $countquery_sql";
		$condition_sql = "select * from price where memberid='$memberid' $query_sql limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."','".$result[$i]['memberid']."','".$result[$i]['membername']."','".$result[$i]['mobile']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";			
					
			$records["aaData"][] = array($result[$i]['name'],$result[$i]['remark'],$btnEdit.$btnDel);
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
	 * 删除价格信息.
	 */
	public function delprice($pkid) {
		$dao = M("Price");
		$old = $dao->where("pkid='$pkid'")->find();
		addLog(7,session("userid"),"删除了客户".$old["membername"]."(".$old["mobile"].")的价格:".$old["name"]."(".$old["price"].")");
		$dao->where("pkid='$pkid'")->delete();
		$subdao = M("Price2type");
		$subdao->where("priceid ='$pkid'")->delete();	
		echo "yes";
	}
	
	public function saveprice(){
		$obj = getObjFromPost(array("memberid","membername","mobile","name","price","remark","pid","pname","jid","jname","qid","qname","rid","rname","ordershow","type"));				
		$dao = M("Price");
		$pricedata = $obj;
		$pricedata['pkid'] = uniqid();
		$dao->add($pricedata);	
		$price2type_dao = M("Price2type");
		
		if(!empty($obj["pid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pricedata['pkid'];
			$pdata["typeid"] = $obj["pid"];
			$pdata["typename"] = $obj["pname"];
			$price2type_dao->add($pdata);
		}
		
		if(!empty($obj["jid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pricedata['pkid'];
			$pdata["typeid"] = $obj["jid"];
			$pdata["typename"] = $obj["jname"];
			$price2type_dao->add($pdata);
		}
		
		if(!empty($obj["qid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pricedata['pkid'];
			$pdata["typeid"] = $obj["qid"];
			$pdata["typename"] = $obj["qname"];
			$price2type_dao->add($pdata);
		}
		
		if(!empty($obj["rid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pricedata['pkid'];
			$pdata["typeid"] = $obj["rid"];
			$pdata["typename"] = $obj["rname"];
			$price2type_dao->add($pdata);
		}
		addLog(7,session("userid"),"新增了客户".$obj["membername"]."(".$obj["mobile"].")的价格:".$obj["name"]."(".$obj["price"].")");
		echo "yes";
	}
	
	public function editprice($pkid){
		$obj = getObjFromPost(array("memberid","membername","mobile","name","price","remark","pid","pname","jid","jname","qid","qname","rid","rname","ordershow","type"));				
		$dao = M("Price");
		$pricedata = $obj;
		$dao->where("pkid='$pkid'")->save($pricedata);	
		$price2type_dao = M("Price2type");
		$price2type_dao->where("priceid ='$pkid'")->delete();		
		if(!empty($obj["pid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pkid;
			$pdata["typeid"] = $obj["pid"];
			$pdata["typename"] = $obj["pname"];
			$price2type_dao->add($pdata);
		}
		
		if(!empty($obj["jid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pkid;
			$pdata["typeid"] = $obj["jid"];
			$pdata["typename"] = $obj["jname"];
			$price2type_dao->add($pdata);
		}
		
		if(!empty($obj["qid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pkid;
			$pdata["typeid"] = $obj["qid"];
			$pdata["typename"] = $obj["qname"];
			$price2type_dao->add($pdata);
		}
		
		if(!empty($obj["rid"])){
			$pdata["pkid"] = uniqid();
			$pdata["priceid"] = $pkid;
			$pdata["typeid"] = $obj["rid"];
			$pdata["typename"] = $obj["rname"];
			$price2type_dao->add($pdata);
		}
		addLog(7,session("userid"),"修改了客户".$obj["membername"]."(".$obj["mobile"].")的价格:".$obj["name"]."(".$obj["price"].")");
		echo "yes";
	}
	
	public function loadprice($pkid){
		$dao = M("Price");
		$result = $dao->where("pkid = '$pkid'")->find();
		$price2type_dao = M("Price2type");
		$types = $price2type_dao->where("priceid = '$pkid'")->select();
		$type_dao = M("Gastype");
		for($i=0;$i<count($types);$i++){
			$item = $types[$i];
			$typeid = $item["typeid"];
			$typedata = $type_dao->where("pkid = '$typeid'")->find();			
			if($typedata['type']==1 && $typedata['classify']==1){
				$result['pid'] = $item['typeid'];
				$result['pname'] = $item['typename'];
			}
			if($typedata['type']==2 && $typedata['classify']==1){
				$result['jid'] = $item['typeid'];
				$result['jname'] = $item['typename'];
			}
			if($typedata['type']==3 && $typedata['classify']==1){
				$result['qid'] = $item['typeid'];
				$result['qname'] = $item['typename'];
			}
			if($typedata['type']==4 && $typedata['classify']==1){
				$result['rid'] = $item['typeid'];
				$result['rname'] = $item['typename'];
			}
		}
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 查询燃气类型
	 */
	public function loadgastype($classify,$type){
		$dao = M("Gastype");
		$result = $dao->where("classify = '$classify' and type = '$type'")->select();		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	
	
}
