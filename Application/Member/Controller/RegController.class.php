<?php
namespace Member\Controller;
use Think\Controller;

class RegController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function login($openid,$nickname,$headicon){
		$member_dao = M("Memberinfo");
		$deopenid = base64_decode($openid);
		$data = $member_dao->where("openid='$deopenid'")->find();
		if(empty($data)){//未注册过
			$new['pkid'] = uniqid();
			$new['nickname'] = base64_decode($nickname);
			$new['realname'] = base64_decode($nickname);
			$new['headicon'] = base64_decode($headicon);
			$new['levelid'] = "lv001";
			$new['level'] = "l1";
			$new['status'] = 0;
			$new['openid'] = base64_decode($openid);
			$new['regtime'] = time();
			$member_dao->add($new);
			header("Location: " . EDU_PREFIX . "/index.html?userid=" . $new['pkid'] . "&usertype=" . $new['status'] ."&openid=" .$new['openid']);
		}else{
			header("Location: " . EDU_PREFIX . "/index.html?userid=" . $data['pkid'] . "&usertype=" . $data['status'] ."&openid=" .$data['openid']);			
		}
	}
	
	public function bind($openid,$nickname,$headicon){
		$member_dao = M("Memberinfo");
		$deopenid = base64_decode($openid);
		$data = $member_dao->where("openid='$deopenid'")->find();
		if(empty($data)){//未注册过
			$new['pkid'] = uniqid();
			$new['nickname'] = base64_decode($nickname);
			$new['realname'] = base64_decode($nickname);
			$new['headicon'] = base64_decode($headicon);
			$new['levelid'] = "lv001";
			$new['level'] = "l1";
			$new['status'] = 0;
			$new['openid'] = base64_decode($openid);
			$new['regtime'] = time();
			$member_dao->add($new);
			header("Location: " . EDU_PREFIX . "/bindstepone.html?userid=" . $new['pkid'] . "&usertype=" . $new['status'] ."&openid=" .$new['openid']);
		}else{
			header("Location: " . EDU_PREFIX . "/bindstepone.html?userid=" . $data['pkid'] . "&usertype=" . $data['status'] ."&openid=" .$data['openid']);			
		}
	}
	
	public function checkbind($openid,$mobile){
		$vipdao = M("Vipmember");
		$check = $vipdao->where("mobile='$mobile'")->find();
		if(!empty($check)){
			$memberdao = M("Memberinfo");
			$data['nickname'] = $check['name'];
			$data['realname'] = $check['name'];
			$data['mobile'] = $check['mobile'];
			$data['address'] = $check['address'];
			if($check['type']=='normal'){
				$data['level'] = "l1";	
			}else if($check['type']=='vip'){
				$data['level'] = "l2";	
			}else if($check['type']=='bankvip'){
				$data['level'] = "l5";	
			}else if($check['type']=='banknormal'){
				$data['level'] = "l4";	
			}else if($check['type']=='inter'){
				$data['level'] = "l3";	
			}
			
			$memberdao->where("openid='$openid'")->save($data);			
			echo "yes";
		}else{
			echo "no";
		}
	}
	
	public function recharge($userid,$money){
		$wallet_dao = M("Wallet");
		$walletlog_dao = M("Walletlog");
		$wallet_dao->where("userid='$userid'")->setInc("wallet",$money);
		$setting_dao = M("Setting");
		$vipmoney = $setting_dao->where("setkey='vipmoney'")->find();
		$wallet_data = $wallet_dao->where("userid='$userid'")->find();
		if($money>=$vipmoney['setval']){
			$member_dao = M("Memberinfo");
			$member_data = $member_dao->where("pkid='$userid'")->find();
			$member_data['status'] = 3;
			$member_dao->where("pkid='$userid'")->save($member_data);
		}
		$log['pkid'] = uniqid();
		$log['userid'] = $userid;
		$log['wallet'] = $wallet_data['wallet'];
		$log['money'] = $money;
		$log['ivtime'] = time();
		$log['type'] = 1;
		$log['direction'] = 1;
		$walletlog_dao->add($log);
		echo "yes";
	}

	public function hh(){
		return "helloworld";
	}
}
