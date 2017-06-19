<?php
namespace Tutor\Controller;
use Think\Controller;
/**
 * 机构Controller
 */
class LessonController extends Controller {

	public function _initialize() {
		putHeader();
	}

	/**
	 * 添加课程.
	 */
	public function addClassStepOne($sessionid) {
		$data = getObjFromPost(["name","address","price","count","minpeople","maxpeople","photoshow","lessonid","cityid","target","suits"]);		
		$userid = getFromSession($sessionid . ".subid");
		$cityid = getFromSession($sessionid . ".cityid");
		$lesson = M('Tutorlesson');
		$data["userid"] = $userid;
		$data["committime"] = time();
		$lessonid = $data["lessonid"];
		$result;
		if(empty($lessonid)){
			$data["pkid"] = uniqid();
			$data["addtime"] = time();
			$data["cityid"] = $cityid;
			$result = $data["pkid"];
			$lesson -> data($data) -> add();
		}else{
			$obj = $lesson->where("pkid='$lessonid'") ->select();
			if($obj[0]['status']==1){
				echo "review";
				exit;
			}
			$result = $lessonid;
			$lesson->where("pkid='$lessonid'") -> data($data) -> save();
		}		
		echo "yes".$result;
	}
	
	/**
	 * 添加课程.
	 */
	public function addClassStepTwo($sessionid) {
		$data = getObjFromPost(["plan","refund","insert","detail","lessonid"]);
		$lessonid = $data["lessonid"];
		$lesson = M('Tutorlesson');
		$obj = $lesson->where("pkid='$lessonid'") ->select();
		if($obj[0]['status']==1){
			echo "review";
			exit;
		}
		$lesson ->where("pkid='$lessonid'")->data($data) -> save();
		echo "yes".$lessonid;
	}
	
	/**
	 * 添加课程.
	 */
	public function addClassStepThird($sessionid) {
		$data = getObjFromPost(["imgs","lessonid"]);
		$lessonid = $data["lessonid"];
		$lesson = M('Tutorlesson');
		$obj = $lesson->where("pkid='$lessonid'") ->select();
		if($obj[0]['status']==1){
			echo "review";
			exit;
		}
		$lessonimg = M('Tutorlessonimg');
		$lessonimg->where("lessonid = '$lessonid'")->delete();
		$imgs = explode(",",$data['imgs']);
		if(!empty($imgs) && sizeof($imgs)>0){
			for($i=0;$i<sizeof($imgs);$i++){
				$item = $imgs[$i];
				$row['pkid']=uniqid();
				$row['lessonid']=$lessonid;
				$row['imgpath']=$item;
				if(!empty($item) && $item!=''){
					$lessonimg ->add($row);	
				}				
			}
		}
		echo "yes";
	}	
	
	/**
	 * 加载课程信息.
	 */
	public function loadLesson($lessonid){
		$lesson = M('Tutorlesson');
		$data = $lesson ->where("pkid='$lessonid'")->find();
		$queryMethod = new \Think\Model();
		$sql = "SELECT s.name from tutorlessonsubject as t left join subjecttype as s on t.subjectid=s.pkid where t.lessonid='$lessonid'";
		$subs = $queryMethod -> query($sql);
		$subjectstr ;
		for($i=0;$i<count($subs);$i++){
			if($i==0){
				$subjectstr=$subs[$i]['name'];
			}else{
				$subjectstr=$subjectstr.",".$subs[$i]['name'];
			}
		}
		$data["subject"]=$subjectstr;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载课程信息.
	 */
	public function loadAllVideoLesson($sessionid){
		$userid = getFromSession($sessionid.".subid");
		$queryMethod = new \Think\Model();
		$sql = "select v.pkid,v.name,v.count,v.price,v.introduce,v.status,v.detail,(select imgpath from tutorlessonvideoimg as i where i.lessonid = v.pkid order by sortno limit 1) as imgpath from tutorlessonvideo as v where userid = '$userid' order by reviewtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function loadAllVideoLessonByUserID($userid){
		$queryMethod = new \Think\Model();
		$sql = "select v.pkid,v.name,v.count,v.price,v.introduce,v.status,v.detail,(select imgpath from tutorlessonvideoimg as i where i.lessonid = v.pkid order by sortno limit 1) as imgpath from tutorlessonvideo as v where userid = '$userid' order by reviewtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	
	/**
	 * 加载课程的封面
	 */
	public function loadLessonImg($lessonid){
		$lesson = M('Tutorlessonimg');
		$data = $lesson->where("lessonid = '$lessonid'")->order("sortno")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 删除课程封面的图片.
	 */
	public function deleteLessonImg($sessionid){
		$data = getObjFromPost(["pkid"]);
		$pkid = $data["pkid"];
		$lesson = M('Tutorlessonimg');
		$lesson ->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	/**
	 * 删除课程封面的图片.
	 */
	public function deleteVideoLesson($sessionid){
		$data = getObjFromPost(["lessonid"]);
		$userid = getFromSession($sessionid . ".subid");
		$lessonid = $data["lessonid"];
		//检查订单是否有此课程
		$order_dao = M("Ordermain");
		$ordercheck = $order_dao->where("lessonid = '$lessonid'  and status <> 7")->count();
		if(!empty($ordercheck) || $ordercheck>0)
		{
			echo "saling";
			exit;
		}
		$lesson = M('Tutorlessonvideo');
		$lesson ->where("pkid='$lessonid'")->delete();//删除
		$lessonunion = M('Lessonunion');
		$lessonunion->where("lessonid='$lessonid'")->delete();
		$minprice = $lessonunion->where("userid='$userid' and lessonclass='tutor'")->min('price');
		$videocount = $lessonunion->where("userid='$userid' and lessontype='video' and lessonclass='tutor'")->count();
        if(!$minprice)
		{
			$minprice = 0;
		}
		if(!$videocount)
		{
			$videocount = 0;
		}
		$sql = "update tutor set videocount='$videocount',minprice='$minprice' where userid='$userid'";
        $query = new \Think\Model();
        $res = $query->execute($sql);
		echo "yes";
	}
	
	/**
	 * 获取城市信息.
	 */
	public function getCityInfo($district){
		getCityInfo($district);
	}
	
	/**
	 * 添加1v1课程.
	 */
	public function addOne2OneClass($sessionid) {
		$data = getObjFromPost(["name","detail","teachersmprice","studentsmprice","locationprice","photoshow","lessonid"]);		
		$userid = getFromSession($sessionid . ".subid");
		$cityid = getFromSession($sessionid . ".cityid");
		$lesson = M('Tutorlessonone2one');
		$data["userid"] = $userid;
		$lessonid = $data["lessonid"];
		$result;
		if(empty($lessonid)){
			$data["pkid"] = uniqid();
			$data["addtime"] = time();
			$data["cityid"] = $cityid;
			$lessonid=$data["pkid"];
			$lesson -> data($data) -> add();
			//更新tutor表的newlesson最新课程字段，统计lessonunion表的最低价格，更新minprice字段,对应的课程类型计数+1
            /*$lessonunion = M('Lessonunion');
			$minprice = $lessonunion->where("userid='$userid'")->min('price');
            if(!$minprice)
			{
				$minprice = 0;
			}
            $sql = "update tutor set newlesson='".$data['name']."',one2onecount=one2onecount+1,minprice='$minprice' where userid='$userid'";
            $query = new \Think\Model();
            $res = $query->execute($sql);*/
		}else{
			$objlist = $lesson->where("pkid='$lessonid'")->select();
			if($objlist[0]['status']==1){
				echo "review".$lessonid;
				exit;
			}
			$result = $lessonid;
			$lesson->where("pkid='$lessonid'") -> data($data) -> save();
		}		
		echo "yes".$lessonid;
	}
	
	/**
	 * 加载1v1课程信息.
	 */
	public function loadOne2OneLesson($lessonid){
		$lesson = M('Tutorlessonone2one');
		$data = $lesson ->where("pkid='$lessonid'")->find();
		$queryMethod = new \Think\Model();
		$sql = "SELECT s.name from tutorlessonsubject as t left join subjecttype as s on t.subjectid=s.pkid where t.lessonid='$lessonid'";
		$subs = $queryMethod -> query($sql);
		$subjectstr ;
		for($i=0;$i<count($subs);$i++){
			if($i==0){
				$subjectstr=$subs[$i]['name'];
			}else{
				$subjectstr=$subjectstr.",".$subs[$i]['name'];
			}
		}
		$data["subject"]=$subjectstr;
		$tutor_dao = M('Tutor');
		$tutorinfo_dao = M('Tutorinfo');
		$userinfo_dao = M('Userinfo');
		$tid =  $data['userid'];
		$tutor_data = $tutor_dao->where("pkid = '$tid'")->find();
		$tutoruserid = $tutor_data['userid'];
		$tutorinfo_data = $tutorinfo_dao->where("userid = '$tutoruserid'")->find();
		$userid = $tutor_data['userid'];
		$userinfo_data = $userinfo_dao->where("pkid = '$userid'")->find();
		$data["tname"]=$tutorinfo_data['nickname'];
		$data["tmobile"]=$userinfo_data['mobile'];
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载所有的1v1课程信息.
	 */
	public function loadAllOne2OneLesson($sessionid){
		$userid = getFromSession($sessionid.".subid");		
		$queryMethod = new \Think\Model();
		$sql = "SELECT t.*,(select group_concat(type.name separator ',') from tutorlessonsubject as s left join subjecttype as type
		on type.pkid=s.subjectid where s.lessonid = t.pkid)  
		as subjectname from tutorlessonone2one as t  where userid='$userid' order by t.reviewtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function loadAllOne2OneLessonByUserID($userid){	
		$queryMethod = new \Think\Model();
		$sql = "SELECT t.*,(select group_concat(type.name separator ',') from tutorlessonsubject as s left join subjecttype as type
		on type.pkid=s.subjectid where s.lessonid = t.pkid)  
		as subjectname from tutorlessonone2one as t  where userid='$userid' order by t.reviewtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	public function delOne2OneLesson($sessionid){
		$data = getObjFromPost(["lessonid"]);
		$userid = getFromSession($sessionid . ".subid");
		$lessonid = $data["lessonid"];
		//检查订单是否有此课程
		$order_dao = M("Ordermain");
		$ordercheck = $order_dao->where("lessonid = '$lessonid' and status <> 7")->count();
		if(!empty($ordercheck) || $ordercheck>0)
		{
			echo "saling";
			exit;
		}
		$lesson = M('Tutorlessonone2one');
		/*$obj = $lesson->where("pkid = '$lessonid'")->select();
		if($obj[0]['status']==1){
			echo "review";
			exit;
		}*/
		$lesson->where("pkid = '$lessonid'")->delete();//删除课程
		$lessonunion = M('Lessonunion');
		$lessonunion->where("lessonid='$lessonid'")->delete();
		$minprice = $lessonunion->where("userid='$userid'")->min('price');
		$one2onecount = $lessonunion->where("userid='$userid' and lessontype='one2one' and lessonclass='tutor'")->count();
        if(!$minprice)
		{
			$minprice = 0;
		}
		if(!$one2onecount)
		{
			$one2onecount = 0;
		}
		$sql = "update tutor set one2onecount='$one2onecount',minprice='$minprice' where userid='$userid'";
        $query = new \Think\Model();
        $res = $query->execute($sql);
		echo "yes";
	}
	
	/**
	 * 添加1v1课程.
	 */
	public function addVideoClassStepOne($sessionid) {
		$data = getObjFromPost(["name","detail","price","lessonid","introduce","imgs"]);		
		$userid = getFromSession($sessionid . ".subid");
		$cityid = getFromSession($sessionid . ".cityid");
		$lesson = M('Tutorlessonvideo');
		$imgDao = M('Tutorlessonvideoimg');
		$data["userid"] = $userid;
		$lessonid = $data["lessonid"];
		$result;
		$imgs = json_decode(base64_decode($data['imgs']));
		if(empty($lessonid)){
			$data["pkid"] = uniqid();
			$data["addtime"] = time();
			$data['cityid'] = $cityid;
			$lesson -> data($data) -> add();
			$result = $data['pkid'];
			for($i=0;$i<count($imgs);$i++){
				$img['pkid'] = uniqid();
				$img['lessonid'] = $result;
				$img['imgpath'] = $imgs[$i];
				$img['sortno'] = $i;
				$imgDao->add($img);
			}
		}else{
			$obj = $lesson->where("pkid='$lessonid'") ->select();
			if($obj[0]['status']==1){
				echo "review";
				exit;
			}
			$result = $lessonid;
			$lesson->where("pkid='$lessonid'") -> data($data) -> save();
			$imgDao->where("lessonid='$lessonid'")->delete();
			for($i=0;$i<count($imgs);$i++){
				$img['pkid'] = uniqid();
				$img['lessonid'] = $result;
				$img['imgpath'] = $imgs[$i];
				$img['sortno'] = $i;
				$imgDao->add($img);
			}
		}		
		echo "yes".$result;
	}

	/**
	 * 添加1v1课程.
	 */
	public function addVideoClassStepTwo($lessonid) {
		$data = getObjFromPost(["imgs"]);		
		$imgDao = M('Tutorlessonvideoframe');
		$imgs = json_decode(base64_decode($data['imgs']));
		$imgDao->where("lessonid='$lessonid'")->delete();
		for($i=0;$i<count($imgs);$i++){
			$img['pkid'] = uniqid();
			$img['lessonid'] = $lessonid;
			$img['framepath'] = $imgs[$i]->video;
			$img['sortno'] = $i;
			$img['free'] = $imgs[$i]->free;
			$img['title'] = $imgs[$i]->name;
			$imgDao->add($img);
		}
		echo "yes";
	}

	
	
	/**
	 * 加载视频课程信息.
	 */
	public function loadVideoLesson($lessonid){
		$lesson = M('Tutorlessonvideo');
		$data = $lesson ->where("pkid='$lessonid'")->find();
		$queryMethod = new \Think\Model();
		$sql = "SELECT s.name from tutorlessonsubject as t left join subjecttype as s on t.subjectid=s.pkid where t.lessonid='$lessonid'";
		$subs = $queryMethod -> query($sql);
		$subjectstr ;
		for($i=0;$i<count($subs);$i++){
			if($i==0){
				$subjectstr=$subs[$i]['name'];
			}else{
				$subjectstr=$subjectstr.",".$subs[$i]['name'];
			}
		}
		$data["subject"]=$subjectstr;
		//查询老师的信息
		$tutor_dao = M('Tutor');
		$tutor_id = $data['userid'];
		$tutor_data = $tutor_dao->where("pkid='$tutor_id'")->find();
		
		$tutorinfo_dao = M('Tutorinfo');
		$tutor_userid_id = $tutor_data['userid'];
		$tutor_info_data = $tutorinfo_dao->where("userid='$tutor_userid_id'")->find();
		$data['headicon']=$tutor_info_data['headicon'];
		$data['tutorname']=$tutor_info_data['nickname'];
		//查询用户电话
		$userinfo_dao = M("Userinfo");
		$userinfo_data = $userinfo_dao->where("pkid='$tutor_userid_id'")->find();
		$data['tmobile'] = $userinfo_data['mobile'];
		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载视频课程封面.
	 */
	public function loadVideoLessonImg($lessonid){
		$lesson = M('Tutorlessonvideoimg');
		$data = $lesson ->where("lessonid='$lessonid'")->order('sortno')->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载视频课程封面.
	 */
	public function loadVideoLessonFrame($lessonid){
		$lesson = M('Tutorlessonvideoframe');
		$data = $lesson ->where("lessonid='$lessonid'")->order('sortno')->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载班课课程.
	 */
	public function loadAlllesson($session){
		$lesson = M('Tutorlesson');
		$tutorid = getFromSession($session.".subid");
		$data = $lesson ->where("userid='$tutorid'")->order("committime desc")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载所有班课课程（带封面）.
	 */
	public function loadAllLessonWithImg($session){
		$queryMethod = new \Think\Model();
		$tutorid = getFromSession($session.".subid");
		$sql = "SELECT l.*,(select imgpath from tutorlessonimg as i  where i.lessonid = l.pkid order by sortno limit 1 ) as imgpath from tutorlesson as l where userid = '$tutorid' order by reviewtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function loadAllLessonWithImgByUserID($userid){
		$queryMethod = new \Think\Model();
		$tutorid = $userid;
		$sql = "SELECT l.*,(select imgpath from tutorlessonimg as i  where i.lessonid = l.pkid order by sortno limit 1 ) as imgpath from tutorlesson as l where userid = '$tutorid' order by reviewtime desc";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	/**
	 * 删除班课.
	 */
	public function deleteLesson($sessionid){
		$data = getObjFromPost(["lessonid"]);
		$userid = getFromSession($sessionid . ".subid");
		$lessonid = $data["lessonid"];
		//检查订单是否有此课程
		$order_dao = M("Ordermain");
		$ordercheck = $order_dao->where("lessonid = '$lessonid' and status <> 7")->count();
		if(!empty($ordercheck) || $ordercheck>0)
		{
			echo "saling";
			exit;
		}
		$lesson = M('Tutorlesson');
		$lesson->where("pkid='$lessonid'")->delete();//删除
		$lessonunion = M('Lessonunion');
		$lessonunion->where("lessonid='$lessonid'")->delete();
		$minprice = $lessonunion->where("userid='$userid'")->min('price');
		$lessoncount = $lessonunion->where("userid='$userid' and lessontype='lesson' and lessonclass='tutor'")->count();
        if(!$minprice)
		{
			$minprice = 0;
		}
		if(!$lessoncount)
		{
			$lessoncount = 0;
		}
		$sql = "update tutor set lessoncount='$lessoncount',minprice='$minprice' where userid='$userid'";
        $query = new \Think\Model();
        $res = $query->execute($sql);
		echo "yes";
	}

	/**
	 * 提交审核班课.
	 */
	public function reviewLesson($lessonid){
		//首先判断排课是否排满
		$lesson = M('Tutorlesson');
		$lessondate = M('Tutorlessondate');
		$timecount = $lessondate->where("lessonid='$lessonid'")->sum('`long`');
		$lessondata = $lesson->where("pkid='$lessonid'")->find();
		if($timecount != $lessondata['count']){
			echo "notfull";
			exit;
		}
		$data['status']=2;
		$lesson->where("pkid='$lessonid'")->data($data)->save();
		echo "yes";
	}
	
	/**
	 * 提交审核班课.
	 */
	public function reviewOne2OneLesson($lessonid){
		//首先判断排课是否排满
		$lesson = M('Tutorlessonone2one');
		$data['status']=2;
		$lesson->where("pkid='$lessonid'")->data($data)->save();
		echo "yes";
	}
	
	/**
	 * 提交审核班课.
	 */
	public function reviewVideoLesson($lessonid){
		//首先判断排课是否排满
		$lesson = M('Tutorlessonvideo');
		$data['status']=2;
		$lesson->where("pkid='$lessonid'")->data($data)->save();
		echo "yes";
	}
	
	/**
	 * 关闭班课.
	 */
	public function closeLesson($sessionid){
		$data = getObjFromPost(["lessonid"]);
		$userid = getFromSession($sessionid . ".subid");
		$lessonid = $data["lessonid"];
		//检查订单是否有此课程
		$order_dao = M("Ordermain");
		$ordercheck = $order_dao->where("lessonid = '$lessonid' and status <> 7 and status <> 4")->count();
//		if(!empty($ordercheck) || $ordercheck>0)
//		{
//			echo "saling";
//			exit;
//		}
		$lesson = M('Tutorlesson');
		$data['status']=0;
		$lesson->where("pkid='$lessonid'")->data($data)->save();
		$lessonunion = M('Lessonunion');
		$lessonunion->where("lessonid='$lessonid'")->delete();
		$minprice = $lessonunion->where("userid='$userid'")->min('price');
		$lessoncount = $lessonunion->where("userid='$userid' and lessontype='lesson' and lessonclass='tutor'")->count();
        if(!$minprice)
		{
			$minprice = 0;
		}
		if(!$lessoncount)
		{
			$lessoncount = 0;
		}
		$sql = "update tutor set lessoncount='$lessoncount',minprice='$minprice' where userid='$userid'";
        $query = new \Think\Model();
        $res = $query->execute($sql);
		echo "yes";
	}
	
	/**
	 * 关闭班课.
	 */
	public function closeVideoLesson($sessionid){
		$data = getObjFromPost(["lessonid"]);
		$userid = getFromSession($sessionid . ".subid");
		$lessonid = $data["lessonid"];
		//检查订单是否有此课程
		$order_dao = M("Ordermain");
		$ordercheck = $order_dao->where("lessonid = '$lessonid' and status <> 7 and status <> 4")->count();
//		if(!empty($ordercheck) || $ordercheck>0)
//		{
//			echo "saling";
//			exit;
//		}
		$lesson = M('tutorlessonvideo');
		$data['status']=0;
		$lesson->where("pkid='$lessonid'")->data($data)->save();
		$lessonunion = M('Lessonunion');
		$lessonunion->where("lessonid='$lessonid'")->delete();
		$minprice = $lessonunion->where("userid='$userid'")->min('price');
		$videocount = $lessonunion->where("userid='$userid' and lessontype='video' and lessonclass='tutor'")->count();
        if(!$minprice)
		{
			$minprice = 0;
		}
		if(!$videocount)
		{
			$videocount = 0;
		}
		$sql = "update tutor set videocount='$videocount',minprice='$minprice' where userid='$userid'";
        $query = new \Think\Model();
        $res = $query->execute($sql);
		echo "yes";
	}
	
	/**
	 * 关闭班课.
	 */
	public function closeOne2OneLesson($sessionid){
		$data = getObjFromPost(["lessonid"]);
		$userid = getFromSession($sessionid . ".subid");
		$lessonid = $data["lessonid"];
		//检查订单是否有此课程
		$order_dao = M("Ordermain");
		$ordercheck = $order_dao->where("lessonid = '$lessonid' and status <> 7 and status <> 4")->count();
//		if(!empty($ordercheck) || $ordercheck>0)
//		{
//			echo "saling";
//			exit;
//		}
		$lesson = M('Tutorlessonone2one');
		$data['status']=0;
		$lesson->where("pkid='$lessonid'")->data($data)->save();
		$lessonunion = M('Lessonunion');//统计关闭后的课程最低价格，放入家教表
		$lessonunion->where("lessonid='$lessonid'")->delete();
		$minprice = $lessonunion->where("userid='$userid'")->min('price');
		$one2onecount = $lessonunion->where("userid='$userid' and lessontype='one2one' and lessonclass='tutor'")->count();
        if(!$minprice)
		{
			$minprice = 0;
		}
		if(!$one2onecount)
		{
			$one2onecount = 0;
		}
		$sql = "update tutor set one2onecount='$one2onecount',minprice='$minprice' where userid='$userid'";
        $query = new \Think\Model();
        $res = $query->execute($sql);
		echo "yes";
	}
	
	/**
	 * 添加单词课程时间.
	 */
	 public function addLessonTimesetOnce($lessonid){
	 	$data = getObjFromPost(['starttime','long','pkid']);
		$data['starttime']=strtotime($data['starttime']);
		$lesson = M('Tutorlessondate');
		if(empty($data['pkid'])){
			$data['pkid']=uniqid();				
		}else{
			$pkid = $data['pkid'];
			$lesson->where("pkid='$pkid'")->data($data)->save();
			$les = M('Tutorlesson');
			$lesdata['status']=0;
			$les->where("pkid='$lessonid'")->data($lesdata)->save();
			echo "yes";
			exit;
		}
		$data["lessonid"	]=$lessonid;
		//判断是否已经排满课时
		$les = M('Tutorlesson');
		$oldles = $les->where("pkid='$lessonid'")->find();
		$queryMethod = new \Think\Model();
		$sql = "select sum(`long`) as count from tutorlessondate where lessonid = '$lessonid'";
		$queryresult = $queryMethod -> query($sql);
		if(empty($queryresult) || count($queryresult)==0){
			$count = 0;
		}else{
			$count = $queryresult[0]['count'];	
		}
		if(($count+$data['long'])<=$oldles['count']){
			$lesson->add($data);
			echo "yes";
		}else{
			echo "full";
		}
	 }
	 
	 /**
	  * 加载班课课程时间设置.(列表)
	  */
	 public function loadLessonTimeset($lessonid){
	 	$lesson = M('Tutorlessondate');
		$result = $lesson->where("lessonid='$lessonid'")->order("starttime")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	 }
	 
	 /**
	  * 加载班课课程时间设置.(修改)
	  */
	 public function loadTimeset($pkid){
	 	$lesson = M('Tutorlessondate');
		$result = $lesson->where("pkid='$pkid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	 }
	 
	 /**
	  * 加载课程剩余课时.
	  */
	 public function loadTimesetCount($lessonid){
	 	$lesson = M('Tutorlessondate');
		$les = M('Tutorlesson');
	 	$oldles = $les->where("pkid='$lessonid'")->find();
		$queryMethod = new \Think\Model();
		$sql = "select sum(`long`) as count from tutorlessondate where lessonid = '$lessonid'";
		$data = $queryMethod -> query($sql);
		if(empty($data) || count($data)==0){
			$count = 0;
		}else{
			$count = $data[0]['count'];	
		}		
		$result['total']=$oldles['count'];
		$result['remain']=$oldles['count']-$count;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	 }

	public function cleantimeset($lessonid){
		$lesson = M('Tutorlessondate');
		$lesson->where("lessonid='$lessonid'")->delete();
		echo "yes";
	}
	 
	 public function deleteTimeset($pkid){
	 	$lesson = M('Tutorlessondate');
		$lesson->where("pkid='$pkid'")->delete();
		echo "yes";
	 }
	 
	 /**
	  * 批量添加课程时间设置.
	  */
	 public function addLessonTimesetMulti($lessonid){
	 	$data = getObjFromPost(["startdate","enddate","lessontime","lon","week"]);
		$startdate = strtotime($data['startdate']);
		$enddate = strtotime($data['enddate']);
		$lessontime = strtotime($data['lessontime']);
		$weeks = split(",", $data['week']);
		$weekmap ;
		for($j=0;$j<count($weeks);$j++){
			$weekmap[$weeks[$j]]=$weeks[$j];
		}
		$i=0;
		//获取剩余次数
		$lesson = M('Tutorlessondate');
		$les = M('Tutorlesson');
	 	$oldles = $les->where("pkid='$lessonid'")->find();
		$queryMethod = new \Think\Model();
		$sql = "select sum(`long`) as count from tutorlessondate where lessonid = '$lessonid'";
		$queryresult = $queryMethod -> query($sql);
		if(empty($queryresult) || count($queryresult)==0){
			$count = 0;
		}else{
			$count = $queryresult[0]['count'];	
		}
		
		$result['total']=$oldles['count'];
		$remain=$oldles['count']-$count;
		
		while(true){
			if($remain==0){
				break;
			}
			$newDay = strtotime("+$i days",$startdate);
			if($newDay > $enddate){
				break;
			}
			$getweek = date('w',$newDay);
			if($weekmap[$getweek] != null){
				$newdate['pkid']=uniqid();
				$newdate['lessonid']=$lessonid;
				$hour = date('H',$lessontime);
				$minute = date('i',$lessontime);
				$newdate['starttime']=strtotime("+$hour hour +$minute minute",$newDay);
				//单词课时大于剩余课时
				if($data['lon']<=$remain){
					$newdate['long']=$data['lon'];
					$remain=$remain-$data['lon'];
				}else{
					$newdate['long']=$remain;
					$remain=0;
				}
				$lesson->add($newdate);
				
			}
			$i++;
		}
	 	echo "yes";
	 }
	
	/**
	 * 增加
	 */
	public function addSubject($lessonid){
		$data = getObjFromPost(["subjects"]);
		$subject = M('Tutorlessonsubject');
		$subject->where("lessonid='$lessonid'")->delete();
		for($i=0;$i<count($data["subjects"]);$i++){
			$new['pkid']=uniqid();
			$new['lessonid']=$lessonid;
			$new['type']=1;
			$new['subjectid']=$data["subjects"][$i];
			$subject->add($new);
		}
		echo 'yes';
	}
	
	/**
	 * 设置授课时间.
	 */
	public function addOne2OneTimeset($sessionid){
		$timeset = M('Tutorone2onelessontimeset');
		$data = getObjFromPost(["timeset","remark"]);
		$data['userid']=getFromSession($sessionid.".subid");
		$userid = $data['userid'];
		$old = $timeset->where("userid='$userid'")->find();
		if(empty($old)){
			$data['pkid']=uniqid();
			$timeset->add($data);	
		}else{
			$timeset->where("userid='$userid'")->data($data)->save();
		}
		echo "yes";
	}
	
	/**
	 * 加载1v1课程授课时间.
	 */
	public function loadOne2OneTimeset($sessionid){
		$timeset = M('Tutorone2onelessontimeset');
		$userid = getFromSession($sessionid.".subid");
		$data = $timeset->where("userid='$userid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	public function loadOne2OneTimesetByUserID($userid){
		$timeset = M('Tutorone2onelessontimeset');
		$data = $timeset->where("userid='$userid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 上传
	 */
	public function upload() {
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 204800000;
		// 设置附件上传大小
//		$upload -> exts = array('mp4','MP4');
		// 设置附件上传类型
		$upload -> rootPath = './UploadVideo/';
		// 设置附件上传根目录
		// 上传文件
		$info = $upload -> upload();
		if (!$info) {// 上传错误提示错误信息
			$this -> show("上传内容不符合要求");
//			$this -> show("aaaError:" . $upload -> getError());
		} else {// 上传成功
			foreach ($info as $file) {
				echo $file['savepath'] . $file['savename'];
			}
		}
	}
	
	/**
	 * 判断当前家教账户是否已经认证.
	 */
	public function isAuth($sessionid){
		$userid = getFromSession($sessionid . ".subid");
		$tutor = M('Tutor');
		$data = $tutor->where("pkid='$userid'")->find();
		if($data['status']==1){
			echo "yes";
		}else{
			echo "no";
		}
	}
	
	public function loadLessonPreview($lessonid){
		$queryMethod = new \Think\Model();
		$sql = "select l.*,ti.nickname as teachername,t.pkid as teacherid,u.mobile,(select starttime from tutorlessondate as d where d.lessonid = l.pkid order by starttime limit 1) 
		as starttime,(select sum(tld.long) from tutorlessondate as tld where lessonid = l.pkid) as sumtime from tutorlesson as l left join tutor as t 
		on l.userid=t.pkid left join userinfo as u on t.userid = u.pkid left join tutorinfo as ti on ti.userid = t.userid where l.pkid = '$lessonid'";
		$data = $queryMethod -> query($sql);
		$district = $data[0]['cityid'];
		$subquery = new \Think\Model();
		$condition_sql = "select a.cityname as p,b.cityname as c,c.cityname as d from basecityinfo as a left join basecityinfo as b on a.pcityid = b.cityid
	 left join basecityinfo as c on b.pcityid = c.cityid where a.cityid='$district'";
		$result = $subquery -> query($condition_sql);
		if (!empty($result) && !empty($result[0])) {
			$data[0]['cityaddress']=$result[0]['d'] . " " . $result[0]['c'] . ' ' . $result[0]['p'];
		} else{
			$data[0]['cityaddress']="";
		}
		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}

}
