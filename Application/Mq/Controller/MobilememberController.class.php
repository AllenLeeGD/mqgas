<?php
namespace Mq\Controller;
use Think\Controller;

class MobilememberController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function login(){
		$obj = getObjFromPost(["code","password"]);
		$dao_main = M("Userinfo");
		$code = base64_decode($obj["code"]);
		$password = md5(base64_decode($obj["password"]));
		$result = $dao_main->where("(name='".$code."' or mobile='".$code."') and password='".$password."'")->find();
		if(empty($result)){
			echo "no";
		}else{
			echo "yes-".$result['role']."-".$result['realname']."-".$result['pid'];	
		}
		
	}

}
