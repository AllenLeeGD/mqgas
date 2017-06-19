<?php
namespace Tutor\Controller;
use Think\Controller;
/*
 * 教师管理Cotroller
 */
class TutoradminController extends Controller {
	
	public function _initialize() {
		putHeader();
	}
	/**
	 * 下拉加载过往经历
	*/
	public function loadExperienceByUseridPage($sessionid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutorexperience');
		$userid = getFromSession($sessionid . ".subid");
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('fromdate desc')->limit($startindex,$pagesize)->select();
		
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
	public function loadExperienceByUseridPageByUserID($userid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutorexperience');
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('fromdate desc')->limit($startindex,$pagesize)->select();
		
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
	/**
	 * 添加过往经历.
	 */
	public function saveexperience($sessionid){
		$data = getObjFromPost(['fromdate','todate','remarks']);
		$userid = getFromSession($sessionid . ".subid");
		$mdquery = M('Tutorexperience');
		$data['pkid']=uniqid();
		$data['userid']=$userid;
		$data['addtime']=time();
		$res = $mdquery->add($data);
		if($res !== false){
			echo "yes";
		}else{
			echo "no";
		}
		
	}
	/**
	 * 下拉加载成功分享
	*/
	public function loadSuccessByUseridPage($sessionid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutorsuccess');
		$userid = getFromSession($sessionid . ".subid");
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('addtime desc')->limit($startindex,$pagesize)->select();
		
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
	public function loadSuccessByUseridPageByUserID($userid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutorsuccess');
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('addtime desc')->limit($startindex,$pagesize)->select();
		
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
	/**
	 * 删除过往经历
	 */
	public function delexperience($sessionid) {
		$data = getObjFromPost(['pkid']);
		$userid = getFromSession($sessionid . ".subid");
		$pkid = $data['pkid'];
		$mdquery = M('Tutorexperience');
		$res = $mdquery->where("pkid='$pkid' and userid='$userid'")->delete();
		echo "yes";
	}
	/**
	 * 添加成功分享.
	 */
	public function savesuccess($sessionid){
		$data = getObjFromPost(['title','yearmonth','remarks']);
		$userid = getFromSession($sessionid . ".subid");
		$mdquery = M('Tutorsuccess');
		$data['pkid']=uniqid();
		$data['userid']=$userid;
		$data['addtime']=time();
		$res = $mdquery->add($data);
		if($res !== false){
			echo "yes";
		}else{
			echo "no";
		}
	}
	/**
	 * 删除成功经历
	 */
	public function delsuccess($sessionid) {
		$data = getObjFromPost(['pkid']);
		$userid = getFromSession($sessionid . ".subid");
		$pkid = $data['pkid'];
		$mdquery = M('Tutorsuccess');
		$res = $mdquery->where("pkid='$pkid' and userid='$userid'")->delete();
		echo "yes";
	}
	/**
	 * 添加相册
	 */
	public function addAlbumPhoto($sessionid){
		$data = getObjFromPost(['picname']);
		$userid = getFromSession($sessionid . ".subid");
		$mdquery = M('Tutoralbum');
		$data['pkid']=uniqid();
		$data['userid']=$userid;
		$data['addtime']=time();
		$res = $mdquery->add($data);
		echo "yes";
	}
	/**
	 * 下拉加载相册
	*/
	public function loadAlbumByUseridPage($sessionid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutoralbum');
		$userid = getFromSession($sessionid . ".subid");
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('addtime desc')->limit($startindex,$pagesize)->select();
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
	/**
	 * 删除相册
	 */
	public function delalbum($sessionid) {
		$data = getObjFromPost(['pkid']);
		$userid = getFromSession($sessionid . ".subid");
		$pkid = $data['pkid'];
		$mdquery = M('Tutoralbum');
		$res = $mdquery->where("pkid='$pkid' and userid='$userid'")->delete();
		echo "yes";
	}
	/**
	 * 下拉加载标签
	*/
	public function loadTagByUseridPage($sessionid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutortag');
		$userid = getFromSession($sessionid . ".subid");
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('addtime desc')->limit($startindex,$pagesize)->select();
		
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
	/**
	 * 添加标签
	 */
	public function savetag($sessionid){
		$data = getObjFromPost(['tagname']);
		$userid = getFromSession($sessionid . ".subid");
		$mdquery = M('Tutortag');
		$data['pkid']=uniqid();
		$data['userid']=$userid;
		$data['addtime']=time();
		$res = $mdquery->add($data);
		if($res !== false){
			echo "yes";
		}else{
			echo "no";
		}
	}
	/**
	 * 删除标签
	 */
	public function deltag($sessionid) {
		$data = getObjFromPost(['pkid']);
		$userid = getFromSession($sessionid . ".subid");
		$pkid = $data['pkid'];
		$mdquery = M('Tutortag');
		$res = $mdquery->where("pkid='$pkid' and userid='$userid'")->delete();
		echo "yes";
	}
	/**
	 * 下拉加载地址
	*/
	public function loadAddressByUseridPage($sessionid){
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Tutoraddress');
		$userid = getFromSession($sessionid . ".subid");
		$recordcount =  $mdquery->where("userid='$userid'")->count();
		$datalist[0] = $mdquery->where("userid='$userid'")->order('addtime desc')->limit($startindex,$pagesize)->select();
		
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
	public function loadAddressByPkid($pkid){
		$userid = getFromSession($sessionid.".subid");
		$mdadd = M('Tutoraddress');
		$data = $mdadd->where("pkid='$pkid'")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function loadAddressByUserID($sessionid){
		$userid = getFromSession($sessionid.".subid");
		$mdadd = M('Tutoraddress');
		$data = $mdadd->where("userid='$userid'")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	/**
	 * 添加地址
	 */
	public function saveaddress($sessionid){
		$data = getObjFromPost(['pkid','title','address']);
		$userid = getFromSession($sessionid . ".subid");
		$pkid = $data['pkid'];
		$mdquery = M('Tutoraddress');
		if($pkid == ''){
			$data['pkid']=uniqid();
			$data['userid']=$userid;
			$data['addtime']=time();
			$res = $mdquery->add($data);
		}else{
			$data['userid']=$userid;
			$data['addtime']=time();
			$res = $mdquery->where("pkid='$pkid' and userid='$userid'")->save($data);
		}
		if($res !== false){
			echo "yes";
		}else{
			echo "no";
		}
	}
	/**
	 * 删除地址
	 */
	public function deladdress($sessionid) {
		$data = getObjFromPost(['pkid']);
		$userid = getFromSession($sessionid . ".subid");
		$pkid = $data['pkid'];
		$mdquery = M('Tutoraddress');
		$res = $mdquery->where("pkid='$pkid' and userid='$userid'")->delete();
		echo "yes";
	}
}
