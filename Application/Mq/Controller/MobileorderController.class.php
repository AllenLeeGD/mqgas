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
		$data_main['dgsstatus'] = 0;
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
			$data_detail["bottleprice"]=0;
			$data_detail["productname"]=$item->bottle;
			$data_detail["weightprice"]=0;
			$data_detail["productweight"]=$item->weight;
			$dao_detail->add($data_detail);
		}
		echo "yes";
	}

}
