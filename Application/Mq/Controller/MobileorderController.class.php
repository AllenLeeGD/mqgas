<?php
namespace Mq\Controller;
use Think\Controller;

class MobileorderController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function send(){
		$obj = getObjFromPost(["clientid","clientname","userid","username","address","mobile","remark","startpoint","ordercontent"]);
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
		$data_dgs['startpoint'] = $obj['startpoint'];		
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
		$datalist = $dao_main->alias("m")->join("orderdgs as d on d.orderid = m.pkid","LEFT")
		->where("(dgsstatus=0 or hspstatus=0) and (status=-9 or status=-8)")->field("m.*,d.startpoint")->order("buytime desc")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function findcheduiorderdetail($pkid){
		$dao_main = M("Ordermain");
		$datalist = $dao_main->where("pkid = '$pkid'")->find();
		if($datalist['status']==-9){
			$daodgs = M("Orderdgs");
			$dgs = $daodgs->where("orderid='$pkid'")->find();
			$datalist['startpoint']=$dgs["startpoint"];
		}
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
	
	function findyewuorder($userid,$status,$searchdate){
		$dao_main = M("Ordermain");
		if($status=="my"){
			$query = " and (dgsstatus=3 or hspstatus=2)";
		}else{
			$query = " and (dgsstatus=0 or dgsstatus=1 or dgsstatus=2 or dgsstatus=4 or hspstatus=0 or hspstatus=1 or hspstatus=3)";
		}
		if($searchdate != "empty"){
			$query = $query . " and from_unixtime(buytime,'%Y-%m-%d') = '$searchdate'"; 
		}
		$datalist = $dao_main->where("(status=-9 or status=-8)".$query." and userid='".$userid."'")->order("buytime desc")->select();
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
	
	
	function findpeisong($userid,$status,$searchdate){
		$dao_main = M("Ordermain");
		if($status=="my"){
			$query = " and jmstatus=3";
		}else{
			$query = " and (jmstatus=4 or jmstatus=5 or jmstatus=6)"; 
		}
		if($searchdate != "empty"){
			$query = $query . " and from_unixtime(o.buytime,'%Y-%m-%d') = '$searchdate'"; 
		}
		$datalist = $dao_main->alias("o")->join("orderjm as j on j.orderid = o.pkid","LEFT")
		->join("carsdaily as c on j.carid = c.carid",'LEFT')
		->field("o.*,j.setpeopleopttime")->where("(o.status=-7)".$query." and (j.songqiid='".$userid."' or ((c.sid='".$userid.
		"' or c.yid='".$userid."') and from_unixtime(c.dailydate,'%Y-%m-%d') = date_format(now(), '%Y-%m-%d')) )")
		->order("o.buytime desc")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	
	function findjmorderdetail($pkid){
		$dao_main = M("Ordermain");
		$datalist = $dao_main->join("orderjm as j on j.orderid = ordermain.pkid","LEFT")->join("userinfo as u on u.pid = j.songqiid","LEFT")->where("ordermain.pkid = '$pkid'")->field("ordermain.*,j.mname,j.pname,j.fenpaitime,j.songqiname,j.carnumber,j.setpeopleopttime,u.worknumber")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}
	//客户扫描二维码支付回调
	public function qrpay($orderid){
		$dao = M("Ordermain");
		$data['jmstatus'] = 5;//流程直接到"门店已存款"
		$data['paytype'] = 0;//订单改为微信支付
		$dao->where("pkid='$orderid'")->save($data);
		
		$dao_jm=M("Orderjm");
		$data_jm['shoutime'] = time();
		$data_jm['shouoptname'] = "微信扫码支付";
		$data_jm['cuntime'] = time();
		$data_jm['cunoptname'] = "微信扫码支付";
		$data_jm['cunmsg'] = "微信扫码支付";
		$dao_jm->where("orderid='$orderid'")->save($data_jm);
		
	}

	//客户扫描二维码支付回调
	public function checkwxorder($orderid){
		$dao = M("Ordermain");
		$data['jmstatus'] = 5;//流程直接到"门店已存款"
		$data['paytype'] = 0;//订单改为微信支付
		$check = $dao->where("pkid='$orderid'")->find();
		
		if($check['jmstatus'] == 5){
			echo "yes";
		}else{
			echo "no";
		}
		
	}
	
	public function arrive($orderid){
		$dao_main = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data_main['jmstatus'] = 8;
		$data_jm['arrivetime'] = time();
		$dao_main->where("pkid='$orderid'")->save($data_main);
		$dao_jm->where("orderid='$orderid'")->save($data_jm);
	}
	
	
	public function sendJm(){
		$obj = getObjFromPost(["clientid","clientname","userid","username","address","mobile","remark","startpoint","ordercontent"]);
		$dao_main = M("Ordermain");
		$dao_jm = M("Orderjm");
		$dao_detail = M("Orderdetail");
		
		$data_main['pkid'] = uniqid();
		$data_main['buyer'] = $obj['clientid'];
		$data_main['buytime'] = time();
		$data_main['ivtime'] = time();
		$data_main['userid'] = $obj['userid'];
		$data_main['username'] = $obj['username'];
		$data_main['type'] = 0;
		$data_main['status'] = -7;
		
		$data_main['jmstatus'] = 1;
		$data_main['buyername'] = $obj['clientname'];
		$data_main['buyermobile'] = $obj['mobile'];
		$data_main['buyeraddress'] = $obj['address'];
		$data_main['remark'] = $obj['remark'];
				
		
		
		$data_jm['pkid'] = uniqid();
		$data_jm['orderid'] = $data_main['pkid'];
		$data_jm['optorderid'] = $obj['userid'];
		$data_jm['optordername'] =  $obj['username'];
		$dao_jm->add($data_jm);
		
		$ordercontent = json_decode(base64_decode($obj['ordercontent']));
		$totalcount=0;
		$totalmoney=0;
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
			$totalcount = $totalcount+intval($data_detail["productcount"]);
			$totalmoney= $totalmoney + (intval($data_detail["productcount"])*strval($data_detail["bottleprice"]));
			$dao_detail->add($data_detail);
		}
		$data_main['price']=$totalmoney;
		$data_main['buycount']=$totalcount;
		$dao_main->add($data_main);
		echo "yes";
	}

}
