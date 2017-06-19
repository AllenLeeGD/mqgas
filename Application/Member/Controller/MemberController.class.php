<?php
namespace Member\Controller;
use Think\Controller;

class MemberController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findMemberByPkid($pkid){
		$query = new \Think\Model();
        $sql = "select m.nickname,m.pkid,m.imgpath,l.levelname,(select count(*) from ordermain where (status=0 or status=1 or status=2) and buyer='$pkid') as numoforder from memberinfo as m,levelsetting as l where m.levelid=l.pkid and m.pkid='$pkid'";
		$data = $query->query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findMemberSettingByPkid($pkid){
		$query = new \Think\Model();
        $sql = "select * from memberinfo where pkid='$pkid'";
		$data = $query->query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function saveHeadicon(){
		$mlcp = D('Memberinfo');
		$data['pkid'] = I('post.pkid');
		$data['imgpath'] = I('post.imgpath');
		$pkid = $data['pkid'];
		$mlcp->where("pkid='$pkid'")->save($data);  
		echo '1';
	}
	public function saveNickname(){
		$mlcp = D('Memberinfo');
		$data['pkid'] = I('post.pkid');
		$data['nickname'] = I('post.nickname');
		$pkid = $data['pkid'];
		$mlcp->where("pkid='$pkid'")->save($data);  
		echo '1';
	}
	public function saveInfo(){
		$mlcp = D('Memberinfo');
		$data['pkid'] = I('post.pkid');
		$data['realname'] = I('post.realname');
		$data['idno'] = I('post.idno');
		$data['mobile'] = I('post.mobile');
		$data['status'] = 2;
		$pkid = $data['pkid'];
		$mlcp->where("pkid='$pkid' and (status=0 or status=4)")->save($data);  
		echo '1';
	}

}
