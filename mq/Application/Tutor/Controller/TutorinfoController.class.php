<?php
namespace Tutor\Controller;
use Think\Controller;
/*
 * 家教个人信息Controller
 */
class TutorinfoController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function getUserid($sessionid) {
		$userid = getFromSession($sessionid . ".subid");
		echo $userid;
	}

	/**
	 * 获取家教的个人信息.
	 */
	public function findtutorinfo($sessionid) {
//		$result = getFromSession("TUTOR_INFO_RESULT");
//		if (empty($result) || count($result)<=3) {
			//		//查询课程数、学生数、已售课程、好评率
			$queryMethod = new \Think\Model();
			$userid = getFromSession($sessionid . ".subid");
			$sql = "select (select count(*) from tutorlesson where userid = '$userid') as lc , (select count(*) from tutorlessonone2one where userid = '$userid') as oc ,(select count(*) from tutorlessonvideo where userid = '$userid') as vc";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$all_lesson = $data[0]['lc'] + $data[0]['oc'] + $data[0]['vc'];
			} else {
				$all_lesson = 0;
			}
			$sql = "select count(*) as stuc from ordermain where saler = '$userid' and status<>0 and status <> 7";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$all_student = $data[0]['stuc'];
			} else {
				$all_student = 0;
			}
			$sql = "select (select count(*) from ordermain where saler = '$userid' and status = 6 and commontype = 0)/(select count(*) from ordermain where saler = '$userid' and status = 6) as goodcommon";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$all_good = $data[0]['goodcommon'];
			} else {
				$all_good = 0;
			}
			//查询家教个人信息
			$sql = "select t.pkid,t.name,t.status,t.authxueli,ti.nickname,ti.sign,t.authpro,t.authzige,ti.headicon,ti.background,ti.teachage,ti.citystr,(select group_concat(tagname) from tutortag where userid = '$userid') as tagname from tutor as t left join tutorinfo as ti on t.userid = ti.userid where t.pkid = '$userid'";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$result = $data[0];
			}

			$result['all_lesson'] = $all_lesson;
			$result['all_student'] = $all_student;
			$result['all_good'] = $all_good;
//			putInSession("TUTOR_INFO_RESULT", $result);
//		}
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	public function findtutorinfobyuserid($userid) {
//		$result = getFromSession("TUTOR_INFO_RESULT".$userid);
//		if (empty($result) || count($result)<=3) {
			//		//查询课程数、学生数、已售课程、好评率
			$queryMethod = new \Think\Model();
			$sql = "select (select count(*) from tutorlesson where userid = '$userid') as lc , (select count(*) from tutorlessonone2one where userid = '$userid') as oc ,(select count(*) from tutorlessonvideo where userid = '$userid') as vc";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$all_lesson = $data[0]['lc'] + $data[0]['oc'] + $data[0]['vc'];
			} else {
				$all_lesson = 0;
			}
			$sql = "select count(*) as stuc from ordermain where saler = '$userid' and status <> 0 and status <> 7";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$all_student = $data[0]['stuc'];
			} else {
				$all_student = 0;
			}
			$sql = "select (select count(*) from ordermain where saler = '$userid' and status = 6 and commontype = 0)/(select count(*) from ordermain where saler = '$userid' and status = 6) as goodcommon";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$all_good = $data[0]['goodcommon'];
			} else {
				$all_good = 0;
			}
			//查询家教个人信息
			$sql = "select t.pkid,t.name,t.status,t.authxueli,ti.nickname,ti.sign,t.authpro,t.authzige,ti.headicon,ti.background,ti.teachage,ti.citystr,(select group_concat(tagname) from tutortag where userid = '$userid') as tagname from tutor as t left join tutorinfo as ti on t.userid = ti.userid where t.pkid = '$userid'";
			$data = $queryMethod -> query($sql);
			if (count($data) > 0) {
				$result = $data[0];
			}

			$result['all_lesson'] = $all_lesson;
			$result['all_student'] = $all_student;
			$result['all_good'] = $all_good;
//			putInSession("TUTOR_INFO_RESULT".$userid, $result);
//		}
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	public function loadTeacherPromote(){
		$sessionid = I('post.sessionid');
		$cityid = I('post.cityid');
		if(!empty($sessionid)){
			$cityid = getFromSession($sessionid . ".cityid");
			if(empty($cityid)){
				$cityid = I('post.cityid');
			}
		}
		$queryMethod = new \Think\Model();
		$sql = "select tr.nickname as name,t.pkid,tr.headicon from tutor as t,tutorinfo as tr where t.userid=tr.userid and tr.cityid='$cityid' and t.ispromote=1 order by t.sortno desc,t.likecount desc limit 0,4";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * 查询过往经历.
	 */
	public function findExperience($sessionid) {
		//查询过往经历
		$userid = getFromSession($sessionid . ".subid");
		$queryMethod = new \Think\Model();
		$sql = "select * from tutorexperience where userid = '$userid' order by addtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findExperienceByUserID($userid) {
		//查询过往经历
		$queryMethod = new \Think\Model();
		$sql = "select * from tutorexperience where userid = '$userid' order by addtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 查询成功经历.
	 */
	public function findSuccess($sessionid) {
		$userid = getFromSession($sessionid . ".subid");
		$queryMethod = new \Think\Model();
		$sql = "select * from tutorsuccess where userid = '$userid' order by addtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findSuccessByUserID($userid) {
		$queryMethod = new \Think\Model();
		$sql = "select * from tutorsuccess where userid = '$userid' order by addtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	/**
	 * 查询相册.
	 */
	public function findAlbum($sessionid) {
		//查询过往经历
		$userid = getFromSession($sessionid . ".subid");
		$queryMethod = new \Think\Model();
		$sql = "select * from tutoralbum where userid = '$userid' order by addtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findAlbumByUserID($userid) {
		$queryMethod = new \Think\Model();
		$sql = "select * from tutoralbum where userid = '$userid' order by addtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function loadTutorByFilterPage(){
		$order = I('post.order');
		$sort = I('post.sort');
		$keyword = I('post.keyword');
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$cityid = I('post.cityid');
		$query = new \Think\Model();
		$where = "";
		$orderby = "";
		if($sort=="全部"){
			$where = "";
		}elseif($sort=="本地老师"){
			$where = "where ti.cityid='$cityid'";
		}elseif($sort=="有一对一课程"){
			$where = "where t.one2onecount>0";
		}elseif($sort=="有班课课程"){
			$where = "where t.lessoncount>0";
		}elseif($sort=="有视频课程"){
			$where = "where t.videocount>0";
		}elseif($sort=="男老师"){
			$where = "where ti.sex='男'";
		}elseif($sort=="女老师"){
			$where = "where ti.sex='女'";
		}elseif($sort=="独立老师"){
			$where = "where ti.identity='独立老师'";
		}elseif($sort=="机构老师"){
			$where = "where ti.identity='机构老师'";
		}elseif($sort=="在校学生"){
			$where = "where ti.identity='在校学生'";
		}
		if($keyword!=""){
			if($where == "")
				$where = "where ti.nickname like '%$keyword%'";
			else
				$where = $where." and ti.nickname like '%$keyword%'";
		}
		if($where == "")
			$where = "where t.status=1";
		else
			$where = $where." and t.status=1";
		if($order=="人气最高"){
			$orderby = "order by t.ispromote desc,t.sortno desc,t.saledcount desc";
		}elseif($order=="赞数最高"){
			$orderby = "order by t.ispromote desc,t.sortno desc,t.likecount desc";
		}elseif($order=="价格最低"){
			$orderby = "order by t.ispromote desc,t.sortno desc,t.minprice asc";
		}elseif($order=="价格最高"){
			$orderby = "order by t.ispromote desc,t.sortno desc,t.minprice desc";
		}else{
			$orderby = "order by t.ispromote desc,t.sortno desc,t.saledcount desc";
		}
		$sql = "select t.pkid as userid,t.name,t.saledcount,t.likecount,t.authxueli,t.authzige,authpro,t.newlesson,t.minprice,ti.headicon,ti.teachage,ti.nickname,ti.sex,ti.identity,ti.address,ti.citystr from tutor as t left join tutorinfo as ti on t.userid=ti.userid $where $orderby limit $startindex,$pagesize";
		$countsql = "select count(*) as totalrecord from tutor as t left join tutorinfo as ti on t.userid=ti.userid $where";
		$rs[0] = $query -> query($sql);
		$rscount = $query -> query($countsql);
		$recordcount = $rscount[0]['totalrecord'];
		if($startindex>=$recordcount){
			$startindex = -1;
		}else{
			$startindex = $startindex + $pagesize;
		}	
		$rs[1] = $startindex;
		$rs[2] = $keyword;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($rs,JSON_UNESCAPED_UNICODE);
	}

}
