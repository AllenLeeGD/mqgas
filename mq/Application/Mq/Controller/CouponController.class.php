<?php
namespace Mq\Controller;
use Think\Controller;

class CouponController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findPointSetting(){
		$dao = M("Sysparam");
		$data = $dao->where("kkey='pointset'")->find();
		echo $data['vvalue'];
	}

	public function savePointSetting(){
		$obj = getObjFromPost(array("costvalue"));
		$dao = M("Sysparam");
		$data['vvalue'] = $obj['costvalue'];
		$dao->where("kkey='pointset'")->save($data);
		echo "yes";
	}
	
	public function addcoupon(){
		$obj = getObjFromPost(array("costvalue","countnumber","enddate","usergroup"));
		$dao = M("Coupon");
		$numbers = $obj['countnumber'];
		for($i=0;$i<$numbers;$i++){
			$data['pkid'] = uniqid();
			$data['usevalue'] = $obj['costvalue'];
			$data['enddate'] = strtotime($obj['enddate']);
			$data['status'] = 0;
			$data['usergroup'] = $obj['usergroup'];
			$data['addtime'] = time();
			$dao->add($data);
		}
		echo "yes";
	}
	
	public function findCouponStatus() {
		$queryMethod = new \Think\Model();
		$sql = "select usergroup,levelname,usevalue,status,count(*) as c from coupon left join level on usergroup=level.pkid group by usergroup,usevalue,status";		
		$result = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function findMyCoupons($userid) {
		$dao = M("Coupon");
		$result = $dao->where("userid='$userid' and status=1 and enddate > UNIX_TIMESTAMP()")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 领取优惠券.
	 */
	public function getMyCoupons($userid){
		$dao = M("Memberinfo");
		$check = $dao->where("pkid='$userid' ")->find();
		if(empty($check['coupondate'])){
			$level = $check['level'];
			$coupon_dao = M("Coupon");
			$coupons = $coupon_dao->where("status=0 and usergroup='$level'")->order("RAND()")->limit(10)->select();
			for($i=0;$i<count($coupons);$i++){
				$item = $coupons[$i];
				$id = $item['pkid'];
				$item['status']=1;
				$item['userid']=$userid;
				$item['addtime']=time();
				$coupon_dao->where("pkid='$id'")->save($item);
			}
			if(count($coupons)>0){
				$member['coupondate'] = time();
				$dao->where("pkid='$userid'")->save($member);	
			}			
			header('Content-type: text/json');
			header('Content-type: application/json');
			echo json_encode($coupons, JSON_UNESCAPED_UNICODE);
		}else{
			echo "has";
		}
	}
}
