<?php
namespace Mq\Controller;
use Think\Controller;

class MobileorderController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function send(){
		$obj = getObjFromPost(["clientid","clientname","userid","username","address","mobile","remark","ordercontent"]);
		$dao_main = M("Ordermain");
		$dao_dgs = M("Orderdgs");
		$dao_detail = M("Orderdetail");
		
		$data_main['pkid'] = uniqid();
		$data_main['buyer'] = $obj['clientid'];
		$data_main['buytime'] = time();
		$data_main['ivtime'] = time();
		$data_main['userid'] = $obj['userid'];
		$data_main['username'] = $obj['username'];
		$data_main['dgsstatus'] = 0;
		$data_main['hspstatus'] = -1;
		$data_main['type'] = 1;
		$data_main['status'] = -9;
		$data_main['buyername'] = $obj['clientname'];
		$data_main['buyermobile'] = $obj['mobile'];
		$data_main['buyeraddress'] = $obj['address'];
		$data_main['remark'] = $obj['remark'];		
		$dao_main->add($data_main);
		
		$data_dgs['pkid'] = uniqid();
		$data_dgs['orderid'] = $data_main['pkid'];
		$dao_dgs->add($data_dgs);
		
		$ordercontent = json_decode(base64_decode($obj['ordercontent']));
		for($i=0;$i<count($ordercontent);$i++){
			$item=$ordercontent[$i];
			$data_detail["pkid"]=uniqid();
			$data_detail["orderid"]=$data_main['pkid'];
			$data_detail["pid"]=$item->pid;
			$data_detail["pname"]=$item->pname;
			$data_detail["jid"]=$item->jid;
			$data_detail["jname"]=$item->jname;
			$data_detail["rid"]=$item->rid;
			$data_detail["rname"]=$item->rname;
			$data_detail["fid"]=$item->fid;
			$data_detail["fname"]=$item->fname;
			$data_detail["qid"]=$item->qid;
			$data_detail["qname"]=$item->qname;
			$data_detail["productcount"]=$item->numbers;			
			$data_detail["productname"]=$item->bottle;
			$data_detail["productweight"]=$item->weight;
			if(!empty($item->numbers)){
				$data_detail["bottleprice"]=$item->price;
				$data_detail["weightprice"]=0;	
			}
			if(!empty($item->weight)){
				$data_detail["weightprice"]=$item->price;	
				$data_detail["bottleprice"]=0;
			}
			$dao_detail->add($data_detail);
		}
		echo "yes";
	}

	public function sendhsp(){
		$obj = getObjFromPost(["clientid","clientname","userid","username","address","mobile","remark","ordercontent"]);
		$dao_main = M("Ordermain");
		$dao_dgs = M("Orderhsp");
		$dao_detail = M("Orderdetail");
		
		$data_main['pkid'] = uniqid();
		$data_main['buyer'] = $obj['clientid'];
		$data_main['buytime'] = time();
		$data_main['ivtime'] = time();
		$data_main['userid'] = $obj['userid'];
		$data_main['username'] = $obj['username'];
		$data_main['hspstatus'] = 0;
		$data_main['dgsstatus'] = -1;
		$data_main['type'] = 2;
		$data_main['status'] = -8;
		$data_main['buyername'] = $obj['clientname'];
		$data_main['buyermobile'] = $obj['mobile'];
		$data_main['buyeraddress'] = $obj['address'];
		$data_main['remark'] = $obj['remark'];		
		$dao_main->add($data_main);
		
		$data_dgs['pkid'] = uniqid();
		$data_dgs['orderid'] = $data_main['pkid'];
		$dao_dgs->add($data_dgs);
		
		$ordercontent = json_decode(base64_decode($obj['ordercontent']));
		for($i=0;$i<count($ordercontent);$i++){
			$item=$ordercontent[$i];
			$data_detail["pkid"]=uniqid();
			$data_detail["orderid"]=$data_main['pkid'];
			$data_detail["pid"]=$item->pid;
			$data_detail["pname"]=$item->pname;
			$data_detail["jid"]=$item->jid;
			$data_detail["jname"]=$item->jname;
			$data_detail["rid"]=$item->rid;
			$data_detail["rname"]=$item->rname;
			$data_detail["qid"]=$item->qid;
			$data_detail["qname"]=$item->qname;
			$data_detail["productcount"]=$item->numbers;			
			$data_detail["productname"]=$item->bottle;		
			$data_detail["bottleprice"]=0;
			$data_detail["weightprice"]=0;				
			$dao_detail->add($data_detail);
		}
		echo "yes";
	}

	function getPrice($memberid,$pid,$rid,$qid,$jid){
		$query = new \Think\Model();
		$condition_sql = "select p.*,GROUP_CONCAT(t.typeid SEPARATOR ',') newtypeid,
		GROUP_CONCAT(t.gastype SEPARATOR ',') newgastype from price as p left join price2type as t on p.pkid = t.priceid 
 		group by priceid having p.memberid = '".$memberid."'";
		$result = $query -> query($condition_sql);
		
		for($i=0;$i<count($result);$i++){
			$newtypeid = $result[$i]['newtypeid'];
			$newgastype = $result[$i]['newgastype'];
			$newgastypes = explode(",",$newgastype);
			$temp = "";
			for($j=0;$j<count($newgastypes);$j++){
				if($j==0){
					if($newgastypes[$j]=='pid'){
						$temp = $pid;	
					}else if($newgastypes[$j]=='rid'){
						$temp = $rid;	
					}else if($newgastypes[$j]=='qid'){
						$temp = $qid;	
					}else if($newgastypes[$j]=='jid'){
						$temp = $jid;	
					}
				}else{
					if($newgastypes[$j]=='pid'){
						$temp=$temp.",".$pid;	
					}else if($newgastypes[$j]=='rid'){
						$temp=$temp.",".$rid;
					}else if($newgastypes[$j]=='qid'){
						$temp=$temp.",".$qid;
					}else if($newgastypes[$j]=='jid'){
						$temp=$temp.",".$jid;
					}
				}
			}
			if($temp==$newtypeid){
				echo $result[$i]["price"];
				exit;
			}
		}
	}
	
	function findcheduiorder(){
		$dao_main = M("Ordermain");
		$dao_dgs = M("Orderdgs");
		$dao_dgs = M("Orderdetail");
		$datalist = $dao_main->where("(dgsstatus=0 or hspstatus=0) and (status=-9 or status=-8)")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function findcheduiorderdetail($pkid){
		$dao_main = M("Ordermain");
		$datalist = $dao_main->where("pkid = '$pkid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function findcheduiorderdetailitem($pkid){
		$dao_main = M("Orderdetail");
		$datalist = $dao_main->where("orderid = '$pkid'")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function sendcar($pkid){
		$dao_main = M("Ordermain");
		$check = $dao_main->where("pkid='".$pkid."'")->find();
		$obj = getObjFromPost(["recarnumber","recardate","recaroptid","recaroptname"]);
		$data_dgs['recarnumber'] = base64_decode($obj['recarnumber']);
		$data_dgs['recardate'] = strtotime(base64_decode($obj['recardate']));
		$data_dgs['recaroptid'] =$obj['recaroptid'];
		$data_dgs['recaroptname'] = base64_decode($obj['recaroptname']);
		$data_dgs['recaroptdate'] =time();
		if($check['type']==1){
			$dao_dgs = M("Orderdgs");
			$dao_dgs->where("orderid = '".$pkid."'")->save($data_dgs);
			$data_main['dgsstatus'] = 1;			
		}else if($check['type']==2){
			$dao_dgs = M("Orderhsp");
			$dao_dgs->where("orderid = '".$pkid."'")->save($data_dgs);
			$data_main['hspstatus'] = 1;
		}
		$dao_main->where("pkid = '".$pkid."'")->save($data_main);
		echo "yes";
	}
	
	function findyewuorder($userid,$status){
		$dao_main = M("Ordermain");
		if($status=="my"){
			$query = " and (dgsstatus=3 or hspstatus=2)";
		}else{
			$query = " and (dgsstatus=0 or dgsstatus=1 or dgsstatus=2 or dgsstatus=4 or hspstatus=0 or hspstatus=1 or hspstatus=3)";
		}
		$datalist = $dao_main->where("(status=-9 or status=-8)".$query." and userid='".$userid."'")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}

	function findyewuorderdetail($pkid){
		$dao_main = M("Ordermain");
		$datalist = $dao_main->join("orderdgs as d on d.orderid = ordermain.pkid","LEFT")->where("ordermain.pkid = '$pkid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function findyewuorderdetailhsp($pkid){
		$dao_main = M("Ordermain");
		$datalist = $dao_main->join("orderhsp as d on d.orderid = ordermain.pkid","LEFT")->where("ordermain.pkid = '$pkid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function setFinalPrice($orderid){
		$dao_main = M("Ordermain");
		$dao_detail = M("Orderdetail");
		$check =  $dao_main->where("pkid='".$orderid."'")->find();
		if($check["type"]==1){//大工商
			$data['dgsstatus'] = 4;	
		}else if($check["type"]==2){//回收空瓶
			$data['hspstatus'] = 3;
		}
		$datalist = $dao_main->where("pkid='".$orderid."'")->save($data);
		$obj = getObjFromPost(["contents"]);
		$items = json_decode(base64_decode($obj["contents"]));
		for($i=0;$i<count($items);$i++){
			$item = $items[$i];
			$itemid = $item->pkid;
			if($item->type=="bottle"){
				$data_detail['bottleprice'] = $item->value;
			}else if($item->type=="weight"){
				$data_detail['weightprice'] = $item->value;
			}
			$dao_detail->where("pkid='".$itemid."'")->save($data_detail);
		}
		echo "yes";
	}

}
