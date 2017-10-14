<?php
namespace Order\Controller;
use Think\Controller;
/**
 * 订单Controller
 */
class OrderController extends Controller {

	public function _initialize() {
		putHeader();
	}

	/**
	 * 根据状态查询订单
	 */
	public function findOrderByStatus($userid, $status) {
		$queryMethod = new \Think\Model();
		if ($status == 'going') {
			$statussql = " and (m.status=0 or m.status=1 or m.status=2 )";
			$statussqlcount = " and (status=0 or status=1 or status=2 )";
		} else if ($status == 'finished') {
			$statussql = " and (m.status=3 or m.status=4 or m.status=5 or m.status=6 )";
			$statussqlcount = " and (status=3 or status=4 or status=5 or status=6 )";
		}
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$countsql = "select count(*) as totalrecord from ordermain where buyer='$userid' $statussqlcount";
		$sql = "select m.*,(select imgpath from orderdetail as d where d.orderid = m.pkid limit 1) as imgpath from ordermain as m where m.buyer='$userid' $statussql order by m.buytime desc limit $startindex,$pagesize";
		$datacount = $queryMethod -> query($countsql);
		$datalist[0] = $queryMethod -> query($sql);
		$recordcount = $datacount[0]['totalrecord'];
		if ($startindex >= $recordcount) {
			$startindex = -1;
		} else {
			$startindex = $startindex + $pagesize;
		}
		$datalist[1] = $startindex;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 取消订单.
	 */
	public function cancleOrder($orderid) {
		$order_dao = M("Ordermain");
		$data['status'] = 6;
		$data['cancletime'] = time();
		$data['ivtime'] = time();
		$order_dao -> where("pkid='$orderid'") -> save($data);
		echo "yes";
	}

	/**
	 * 申请退款订单.
	 */
	public function refundOrder($orderid) {
		$order_dao = M("Ordermain");
		$data['status'] = 2;
		$data['prerefundtime'] = time();
		$data['ivtime'] = time();
		$order_dao -> where("pkid='$orderid'") -> save($data);
		echo "yes";
	}

	/**
	 * 支付订单.
	 */
	public function payOrder($orderid) {
		$order_dao = M("Ordermain");
		$order_data = $order_dao -> where("pkid='$orderid'") -> find();
		$userid = $order_data['buyer'];
		$needmoney = $order_data['price'];
		$wallet_dao = M("Wallet");
		$wallet_data = $wallet_dao -> where("userid = '$userid'") -> find();
		$havemoney = $wallet_data['wallet'];
		if ($havemoney >= $needmoney) {
			$data['status'] = 1;
			$data['paytime'] = time();
			$data['ivtime'] = time();
			$order_dao -> where("pkid='$orderid'") -> save($data);
			$wallet_dao -> where("userid = '$userid'") -> setDec('wallet', $needmoney);
			$log['pkid'] = uniqid();
			$log['userid'] = $userid;
			$log['wallet'] = ($havemoney - $needmoney);
			$log['money'] = $needmoney;
			$log['ivtime'] = time();
			$log['type'] = 2;
			$log['direction'] = 2;
			$log['orderid'] = $orderid;
			$log_dao = M("walletlog");
			$log_dao -> add($log);
			echo "yes";
		} else {
			echo "notenough";
		}
	}

	/**
	 * 根据订单ID加载.
	 */
	public function loadOrderByID($orderid) {
		$order_dao = M("Ordermain");
		$data = $order_dao -> where("pkid='$orderid'") -> find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 根据订单ID加载订单详情.
	 */
	public function loadOrderdetailByID($orderid) {
		$order_dao = M("Orderdetail");
		$data = $order_dao -> where("orderid='$orderid'") -> select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	/**

	 * 将购物车的商品加入订单并支付.

	 */

	public function addCart2Order($userid) {

		$cart_dao = M("Cart");

		$member_dao = M("Memberinfo");

		$order_dao = M("Ordermain");

		$orderdetail_dao = M("Orderdetail");

		$wallet_dao = M("Wallet");

		$walletlog_dao = M("Walletlog");

		$product_dao = M("Product");

		$sum = 0;

		$count = 0;

		$cart_data = $cart_dao -> where("userid='$userid'") -> select();

		for ($i = 0; $i < count($cart_data); $i++) {

			$item = $cart_data[$i];

			$sum = $sum + ($item['numbers'] * $item['price']);

			$count = $count + $item['numbers'];

		}

		$wallet_data = $wallet_dao -> where("userid = '$userid'") -> find();

		if ($sum > $wallet_data['wallet']) {

			echo "notenough";

			exit();

		} else {

			$orderid = uniqid();

			for ($i = 0; $i < count($cart_data); $i++) {

				$item = $cart_data[$i];

				$productid = $item['productid'];

				$product_data = $product_dao -> where("pkid='$productid'") -> find();

				$orderdetail_data['pkid'] = uniqid();

				$orderdetail_data['orderid'] = $orderid;

				$orderdetail_data['productid'] = $productid;

				$orderdetail_data['productcount'] = $item['numbers'];

				$orderdetail_data['productprice'] = $item['price'];

				$orderdetail_data['productname'] = $product_data['pdname'];

				$orderdetail_data['imgpath'] = $product_data['imgpath'];

				//添加订单明细

				$orderdetail_dao -> add($orderdetail_data);

			}

			$member_data = $member_dao -> where("pkid = '$userid'") -> find();

			//添加订单

			$order_data['pkid'] = $orderid;

			$order_data['buyer'] = $userid;

			$order_data['buytime'] = time();

			$order_data['paytime'] = time();

			$order_data['ivtime'] = time();

			$order_data['status'] = 1;

			$order_data['price'] = $sum;

			$order_data['buycount'] = $count;

			$order_data['buyername'] = $member_data['realname'];

			$order_data['buyermobile'] = $member_data['mobile'];

			$order_dao -> add($order_data);

			//扣余额

			$wallet_dao -> where("userid = '$userid'") -> setDec("wallet", $sum);

			$wallet_data = $wallet_dao -> where("userid = '$userid'") -> find();

			//添加walletlog

			$walletlog_data['pkid'] = uniqid();

			$walletlog_data['userid'] = $userid;

			$walletlog_data['wallet'] = $wallet_data['wallet'];

			$walletlog_data['money'] = $sum;

			$walletlog_data['ivtime'] = time();

			$walletlog_data['type'] = 2;

			$walletlog_data['direction'] = 2;

			$walletlog_data['orderid'] = $orderid;

			$walletlog_dao -> add($walletlog_data);

			//删除cart

			$cart_dao -> where("userid='$userid'") -> delete();

			echo "yes";

			exit();

		}

	}

	public function testA(){
		$member = A("Member/Reg");
		$result = $member->hh();
		echo $result;
	}

}
