<?php
namespace Tutor\Controller;
use Think\Controller;
/*
 * 订单Controller
 */
class OrderController extends Controller {

	public function _initialize() {
		putHeader();
	}

	/**
	 * 获取家教名下的订单.
	 * @param sessionid 客户端sessionID
	 * @param status 订单状态 100标示全部
	 */
	public function getTutorOrders($sessionid,$status,$type) {
		$queryMethod = new \Think\Model();
		$userid = getFromSession($sessionid.".subid");
		if($status=='1' || $status=='5'){
			//班课
			$statussql = "";
			if($status=='1'){
				$statussql = "o.status=1";
			}else if($status=='5'){
				$statussql = "o.status=5 or o.status=6";
			}
			if($type==1){
				$sql = "select o.*,l.name as lessonname,si.name,si.headicon from ordermain as o left join tutorlesson as l on o.lessonid = l.pkid 
				 left join studentinfo as si on o.buyer = si.pkid where saler = '$userid' and ($statussql) and o.type=$type order by o.buytime desc";	
			}else if($type==2){//1v1
				$sql = "select o.*,l.name as lessonname,si.name,si.headicon from ordermain as o left join tutorlessonone2one as l on o.lessonid = l.pkid 
				 left join studentinfo as si on o.buyer = si.pkid where saler = '$userid' and ($statussql) and o.type=$type order by o.buytime desc";
			}else if($type==3){//视频课程
				$sql = "select o.*,l.name as lessonname,si.name,si.headicon from ordermain as o left join tutorlessonvideo as l on o.lessonid = l.pkid 
				 left join studentinfo as si on o.buyer = si.pkid where saler = '$userid' and ($statussql) and o.type=$type order by o.buytime desc";
			}
			$data = $queryMethod -> query($sql);
		}else if($status=='2'){
			//班课
			if($type==1){
				$sql = "select o.*,l.name as lessonname,si.name,si.headicon,r.reason,r.money from ordermain as o left join tutorlesson as l on o.lessonid = l.pkid 
				 left join studentinfo as si on o.buyer = si.pkid left join orderrefund as r on o.pkid =  r.orderid 
				 where saler = '$userid' and o.status=$status and o.type=$type order by o.buytime desc";	
			}else if($type==2){//1v1
				$sql = "select o.*,l.name as lessonname,si.name,si.headicon,r.reason,r.money from ordermain as o left join tutorlessonone2one as l on o.lessonid = l.pkid 
				 left join studentinfo as si on o.buyer = si.pkid  left join orderrefund as r on o.pkid =  r.orderid 
				 where saler = '$userid' and o.status=$status and o.type=$type order by o.buytime desc";
			}else if($type==3){//视频课程
				$sql = "select o.*,l.name as lessonname,si.name,si.headicon,r.reason,r.money from ordermain as o left join tutorlessonvideo as l on o.lessonid = l.pkid 
				 left join studentinfo as si on o.buyer = si.pkid left join orderrefund as r on o.pkid =  r.orderid  
				 where saler = '$userid' and o.status=$status and o.type=$type order by o.buytime desc";
			}
			$data = $queryMethod -> query($sql);
		}
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 根据订单ID获取信息.
	 * @param orderid 订单ID.
	 * @param type 课程类型.
	 */
	public function getTutorOrder($orderid,$type){
		$queryMethod = new \Think\Model();
		//班课
		if($type==1){
			$sql = "select o.*,l.name as lessonname,si.name,si.headicon,orf.money,orf.reason from ordermain as o left join tutorlesson as l on o.lessonid = l.pkid 
			 left join studentinfo as si on o.buyer = si.pkid left join orderrefund as orf on orf.orderid = o.pkid where o.pkid = '$orderid'";	
		}else if($type==2){//1v1
			$sql = "select o.*,l.name as lessonname,si.name,si.headicon,orf.money,orf.reason from ordermain as o left join tutorlessonone2one as l on o.lessonid = l.pkid 	
			 left join studentinfo as si on o.buyer = si.pkid left join orderrefund as orf on orf.orderid = o.pkid where o.pkid = '$orderid'";
		}
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 计算个人订单的数量.
	 * @sessionid 
	 * @type 用户类型.
	 */
	public function countOrder($sessionid,$type){
		$userid = getFromSession($sessionid.".subid");
		if($type == "tutor"){
			$dao = M('Tutor');
		}else if($type == "org"){
			$dao = M('Organization');
		}else if($type == "stu"){
			$dao = M('Student');
		}
		$data = $dao->where("pkid='$userid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 提醒学生消课时.
	 */
	public function reminderCustomer($sessonid){
		$userid = getFromSession($sessonid.".subid");
		$data = getObjFromPost(["orderid","startdate","enddate","remark","lessoncount","custid","custname"]);
		$message = M('Message');
		$start = $data['startdate'];
		$end = date("H:i", strtotime($data['enddate']));;
		$lessoncount = $data['lessoncount'];
		$orderid = $data['orderid'];
		$queryMethod = new \Think\Model();
		$sql = "select t.name,ti.headicon from 	ordermain as o left join tutor as t on o.saler = t.pkid left join tutorinfo as ti on t.userid = ti.userid where o.pkid = '$orderid'";
		$teacher = $queryMethod -> query($sql);
		$msg['pkid']=uniqid();
		$msg['sender']=$userid;
		//获取学生ID
		$stuinfo_dao = M("Studentinfo");
		$stu_dao = M("Student");
		$info_id = $data['custid'];
		$stuinfo_data = $stuinfo_dao->where("pkid = '$info_id'")->find();
		$stu_user_id = $stuinfo_data['userid'];
		$stu_data = $stu_dao->where("userid = '$stu_user_id'")->find();
		$receiver_id = $stu_data['pkid'];
		
		$msg['receiver']=$receiver_id;
		$msg['title'] = "系统通知";
		$msg['ivtime'] = time();
		$msg['type'] = 1;
		$msg['sendername'] = $teacher[0]["name"];
		$msg['senderheadicon'] = $teacher[0]["headicon"];
		$teachername = $teacher[0]["name"];
		$msg['content'] = "尊敬的学生您好，".$teachername."邀请你确认课时消费，时间为$start~$end 共消费".$lessoncount."课时，如果实属请确认消费。";
		$param = json_encode($data,JSON_UNESCAPED_UNICODE);
		$msg['params']=$param;
		$msg['btns'] = "[{'value':'查看详情','url':'../student/ordersstudent.html'}]";
		$message->add($msg);
		echo "yes";
	}
	
	/**
	 * 同意退款.
	 */
	public function agreerefund(){
		$data = getObjFromPost(["orderid"]);
		$orderid = $data['orderid'];
		$queryMethod = new \Think\Model();
		$sql = "select mo.*,of.money,s.pkid as studentid from ordermain as mo left join orderrefund as of on mo.pkid = of.orderid 
		left join studentinfo as si on mo.buyer = si.pkid left join student as s on s.userid = si.userid  where mo.pkid = '$orderid'";
		$orderdata = $queryMethod -> query($sql);
		$order_dao = M("Ordermain");
		$refund_dao = M('Orderrefund');
		$wallet_dao  = M('Wallet');
		$walletlog_dao = M('Walletlog');
		$tutor_dao = M('Tutor');
		$student_dao = M('Student');
		$order_data['status']=4;//同意退款
		$order_dao->where("pkid='$orderid'")->data($order_data)->save();
		$refund_data['status']=1;//同意退款
		$refund_data['agreetime']=time();
		$refund_dao->where("orderid='$orderid'")->data($refund_data)->save();
		
		$student_id = $orderdata[0]["studentid"];
		$teacher_id = $orderdata[0]["saler"];
		$lesson_id = $orderdata[0]["lessonid"];
		$lesson_type = $orderdata[0]["type"];
		$money = $orderdata[0]["money"];
		
		//更新钱包余额
		$wallet_dao->where("userid='$student_id'")->setInc('wallet',$money);
		$wallet_dao->where("userid='$teacher_id'")->setDec('freezenwallet',$money);
		
		$wallet_t_data = $wallet_dao->where("userid='$teacher_id'")->find();
		$wallet_s_data = $wallet_dao->where("userid='$student_id'")->find();
		
		if($lesson_type==1){
			$lesson_dao = M('Tutorlesson');
		}else if($lesson_type==2){
			$lesson_dao = M('Tutorlessonone2one');
		}else if($lesson_type==3){
			$lesson_dao = M('Tutorlessonvideo');
		}
		
		$lesson_data = $lesson_dao->where("pkid = '$lesson_id'")->find();
		$lessonname = $lesson_data['name'];
		
		//写入钱包操作日志
		$walletlog_t_data["pkid"]=uniqid();
		$walletlog_t_data["userid"]=$teacher_id;
		$walletlog_t_data["wallet"]=$wallet_t_data['wallet'];
		$walletlog_t_data["freezenwallet"]=$wallet_t_data['freezenwallet'];
		$walletlog_t_data["money"]=$money;
		$walletlog_t_data["ivtime"]=time();
		$walletlog_t_data["type"]=2;
		$walletlog_t_data["direction"]=2;
		$walletlog_t_data["lessonid"]=$lesson_id;
		$walletlog_t_data["lessontype"]=$lesson_type;
		$walletlog_t_data["lessonname"]=$lessonname;
		$walletlog_dao->add($walletlog_t_data);
		
		$walletlog_s_data["pkid"]=uniqid();
		$walletlog_s_data["userid"]=$student_id;
		$walletlog_s_data["wallet"]=$wallet_s_data['wallet'];
		$walletlog_s_data["money"]=$money;
		$walletlog_s_data["ivtime"]=time();
		$walletlog_s_data["type"]=2;
		$walletlog_s_data["direction"]=1;
		$walletlog_s_data["lessonid"]=$lesson_id;
		$walletlog_s_data["lessontype"]=$lesson_type;
		$walletlog_s_data["lessonname"]=$lessonname;
		$walletlog_dao->add($walletlog_s_data);
		
		//更新订单数量
		$tutor_dao->where("pkid='$teacher_id'")->setDec('refundcount',1);
		$student_dao->where("pkid='$student_id'")->setDec('refundcount',1);
		$student_dao->where("pkid='$student_id'")->setInc('completecount',1);
		
		//发送系统消息
		$message = M("Message");
		$msg['pkid']=uniqid();
		$msg['receiver']=$student_id;
		$msg['title'] = "系统通知";
		$msg['ivtime'] = time();
		$msg['type'] = 1;
		$msg['content'] = "尊敬的用户您好，您的课程 ".$lessonname." 退款申请 ".$money."元 已批准";
		$message->add($msg);
		
		echo "yes";
	}

	/**
	 * 拒绝退款.
	 */
	public function refuseRefund(){
		$data = getObjFromPost(["orderid","refusereason"]);
		$orderid = $data['orderid'];
		$order_dao = M('Ordermain');
		$refund_dao = M('Orderrefund');
		$student_dao = M('Student');
		$tutor_dao = M('Tutor');
		$order_data['status'] = 3;
		$order_dao->where("pkid = '$orderid'")->data($order_data)->save();
		$refund_data['status'] = 2;
		$refund_data['refusereason'] = $data['refusereason'];
		$refund_data['disagreetime'] = time();
		$refund_dao->where("orderid='$orderid'")->data($refund_data)->save();
		
		$queryMethod = new \Think\Model();
		$sql = "select o.saler,s.pkid as buyer from ordermain as o left join studentinfo as si on o.buyer=si.pkid left join student as s 
		 on s.userid = si.userid where o.pkid = '$orderid'";
		$orderdata = $queryMethod -> query($sql);
		$student_id = $orderdata[0]['buyer'];
		$teacher_id = $orderdata[0]['saler'];
		$tutor_dao->where("pkid='$teacher_id'")->setDec('refundcount',1);
//		$student_dao->where("pkid='$student_id'")->setDec('refundcount',1);

		//发送系统消息
		$message = M("Message");
		$refund_checkdata = $refund_dao->where("orderid='$orderid'")->find();
		$msg['pkid']=uniqid();
		$msg['receiver']=$student_id;
		$msg['title'] = "系统通知";
		$msg['ivtime'] = time();
		$msg['type'] = 1;
		$msg['content'] = "尊敬的用户您好，您的课程退款申请 ".$refund_checkdata['money']."元 已被拒绝,理由是 ".$refund_checkdata['refusereason'];
		$message->add($msg);
		echo "yes";
	}
	
	/**
	 * 获取好评.
	 */
	public function findGoodComment($sessionid,$limit){
		$userid = getFromSession($sessionid.".subid");
		$queryMethod = new \Think\Model();
		if($limit==0){
			$limitstr = "";
		}else{
			$limitstr = "limit ".$limit;
		}
		$sql = "select si.headicon,si.name as nickname from ordermain as o left join studentinfo as si on o.buyer = si.pkid where o.saler = '$userid' and o.commontype=0 $limitstr";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function findGoodCommentByUserID($userid,$limit){
		$queryMethod = new \Think\Model();
		if($limit==0){
			$limitstr = "";
		}else{
			$limitstr = "limit ".$limit;
		}
		$sql = "select si.headicon,si.nickname from ordermain as o left join studentinfo as si on o.buyer = si.pkid where o.saler = '$userid' and o.commontype=0 $limitstr";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	/**
	 * 查询用户钱包的交易记录.
	 */
	public function findWalletLog($sessionid){
		$userid = getFromSession($sessionid.".subid");
		$wallet_log_dao = M('walletlog');
		$data = $wallet_log_dao->where("userid='$userid'")->order("ivtime desc")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 查询用户钱包的余额.
	 */
	public function findWallet($sessionid){
		$wallet_dao = M('Wallet');
		$userid = getFromSession($sessionid.".subid");
		$data = $wallet_dao->where("userid = '$userid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取最新的订单状态.
	 */
	public function findLastestOrder($sessionid){
		$userid = getFromSession($sessionid.".subid");
		$queryMethod = new \Think\Model();
		$sql = "select o.*,si.headicon,s.name,l.name as lessonname,ol.name as onename,vl.name as videoname 
		 from ordermain as o left join tutorlesson as l on o.lessonid = l.pkid left join tutorlessonone2one as ol 
		on o.lessonid = ol.pkid left join tutorlessonvideo as vl on o.lessonid = vl.pkid left join studentinfo as si on 
		 o.buyer = si.pkid left join student as s on si.userid = s.userid where o.saler = '$userid' and o.status<>0 and o.status<>7 order by o.buytime desc limit 2";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取最新的消课时信息.
	 */
	public function findLastestOrderClasstime($sessionid){
		$userid = getFromSession($sessionid.".subid");
		$queryMethod = new \Think\Model();
		$sql = "select oc.*,o.type,si.headicon,s.name,l.name as lessonname,ol.name as onename,vl.name as videoname 
		 from orderclasstime as oc left join tutorlesson as l on oc.lessonid = l.pkid left join tutorlessonone2one as ol 
		on oc.lessonid = ol.pkid left join tutorlessonvideo as vl on oc.lessonid = vl.pkid left join
		 ordermain as o on oc.orderid = o.pkid left join studentinfo as si on 
		 o.buyer = si.pkid left join student as s on si.userid = s.userid where o.saler = '$userid' order by o.buytime desc limit 2";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取最新的消课时信息.
	 */
	public function findLastestNotice(){
		$notice_dao = M('Notice');
		$data = $notice_dao->order("ivtime desc")->limit(2)->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 购买课程.首先在ordermain插入一条数据，状态是未付款，然后跳转到支付页面.
	 */
	function buyLessons($sessionid){
		$obj = getObjFromPost(['lessonid','tid','type','price','buycount','saletype','oneway']);
		$userid = getFromSession($sessionid.".userid");
		$subid = getFromSession($sessionid.".subid");
		$stuinfo_dao = M('Studentinfo');
		$data_info = $stuinfo_dao->where("userid = '$userid'")->find();
		$data['pkid'] = uniqid();
		$data['lessonid'] = $obj['lessonid'];
		$data['buyer'] = $data_info['pkid'];
		$data['saler'] = $obj['tid'];
		$data['type'] = $obj['type'];
		$data['saletype'] = $obj['saletype'];
		$data['buytime'] = time();
		$data['remainder'] = $obj['buycount'];
		$data['ivtime'] = time();
		$data['status'] = 0;
		$data['oneway'] = $obj['oneway'];
		$data['price'] = $obj['price'];
		$data['buycount'] = $obj['buycount'];
		$order_dao = M('Ordermain');
		$order_dao->add($data);
		$stu_dao = M("Student");
		$stu_dao->where("pkid = '$subid'")->setInc("unpaycount",1);
		echo "yes.".$data['pkid'];
	}
}
