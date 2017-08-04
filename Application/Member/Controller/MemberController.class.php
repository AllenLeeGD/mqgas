<?php
namespace Member\Controller;
use Think\Controller;

class MemberController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findDGSMembers($keyword){
		$query = new \Think\Model();
		if($keyword=="emptynull"){
			$keyword='';	
		}
        $sql = "select * from memberinfo where membertype=3 and realname like '%$keyword%'";
		$data = $query->query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}

}
