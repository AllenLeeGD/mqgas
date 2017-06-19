<?php
namespace Order\Controller;
use Think\Controller;
/**
 * 订单Controller
 */
class WalletController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function loadwallet($userid){
		$wallet_dao = M("Wallet");
		$data = $wallet_dao->where("userid='$userid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	public function loadwalletlog($userid){
		$wallet_dao = M("Walletlog");
		$data = $wallet_dao->where("userid='$userid'")->order("ivtime desc")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
}
