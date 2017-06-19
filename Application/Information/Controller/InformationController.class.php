<?php
namespace Information\Controller;
use Think\Controller;

class InformationController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findInformationByPage(){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Information');
		$recordcount =  $mdquery->count();
		$datalist[0] = $mdquery->order('sortno desc,addtime desc')->limit($startindex,$pagesize)->select();
		for($a=0;$a<count($datalist[0]);$a++){
			$datalist[0][$a]['addtime'] = date('Y-m-d H:i:s',$datalist[0][$a]['addtime'] );
		}
		if($startindex>=$recordcount){
			$startindex = -1;
		}else{
			$startindex = $startindex + $pagesize;
		}
		$datalist[1] = $startindex;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist,JSON_UNESCAPED_UNICODE);
	}
	public function findInformationByPkid($pkid){
		$mdquery = M('Information');
		$obj = $mdquery->where("pkid='$pkid'")->find();
		$obj['addtime'] = date('Y-m-d H:i:s',$obj['addtime']);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($obj, JSON_UNESCAPED_UNICODE);
	}
	public function findInformationTop1(){
		$mdquery = M('Information');
		$obj = $mdquery->where('status=1')->order('sortno desc,addtime desc')->limit(0,1)->find();
		$obj['addtime'] = date('Y-m-d H:i:s',$obj['addtime']);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($obj, JSON_UNESCAPED_UNICODE);
	}
	public function addExperience(){
        $data = getObjFromPost(['uname','content','contact','contactperson','userid']);
		$mlcp = D('Experience');
		$obj = $mlcp->where("userid='".$data['userid']."'")->find();
		if($obj['userid']){
			echo '0';
			exit;
		}
		$data['pkid'] = uniqid();
		$data['feedtime'] = time();
		$mlcp->add($data);  
		echo '1';
    }

}
