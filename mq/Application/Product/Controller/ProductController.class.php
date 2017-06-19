<?php
namespace Product\Controller;
use Think\Controller;
/**
 * 产品Controller
 */
class ProductController extends Controller {

	public function _initialize() {
		putHeader();
	}

	/**
	 * 根据类型加载产品.
	 */
	public function loadProduct($sessionid,$typeid) {
		if($typeid=="all"){
			$con_sql = " updowntag=1";
		}else{
			$con_sql = " typeid = '$typeid' and updowntag=1";
		}
		$startindex = I('post.startindex');
		$pagesize = I('post.pagesize');
		$mdquery = M('Product');
		$recordcount =  $mdquery->where($con_sql)->count();
		$datalist[0] = $mdquery->where($con_sql)->order('sortno desc')->limit($startindex,$pagesize)->select();
		
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
	 * 根据类型加载产品.
	 */
	public function loadProducttype() {
		$producttype_dao = M("Producttype");
		$result = $producttype_dao->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 加载用户购物车简介.
	 */
	public function loadcartsimple($userid) {
		$producttype_dao = M("Cart");
		$data = $producttype_dao->where("userid='$userid'")->select();
		$count=0;
		$sum=0;
		for($i=0;$i<count($data);$i++){
			$item = $data[$i];
			$count = $count + $item['numbers'];
			$sum = $sum + ($item['numbers']*$item['price']);
		}
		$result['count'] = $count;
		$result['sum'] = $sum;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}		
	/**
	 * 添加商品到购物车.
	 */
	public function addProduct2Cart($userid,$productid,$price){
		$cart_dao = M("Cart");
		$data = $cart_dao->where("userid='$userid' and productid = '$productid'")->select();
		if(count($data)>0){
			$cart_dao->where("userid='$userid' and productid = '$productid'")->setInc('numbers',1);
		}else{
			$cartdata['pkid'] = uniqid();
			$cartdata['userid'] = $userid;
			$cartdata['productid'] = $productid;
			$cartdata['numbers'] = 1;
			$cartdata['price'] = $price;
			$cart_dao->add($cartdata);
		}
		echo "yes";
	}
	
	/**
	 * 根据ID加载产品.
	 */
	public function loadProducyById($productid){
		$product_dao = M("Product");
		$data = $product_dao->where("pkid = '$productid'")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 添加商品到购物车.
	 */
	public function addProduct2CartByNumbers($userid,$productid,$price,$numbers){
		$cart_dao = M("Cart");
		$data = $cart_dao->where("userid='$userid' and productid = '$productid'")->select();
		if(count($data)>0){
			$cart_dao->where("userid='$userid' and productid = '$productid'")->setInc('numbers',$numbers);
		}else{
			$cartdata['pkid'] = uniqid();
			$cartdata['userid'] = $userid;
			$cartdata['productid'] = $productid;
			$cartdata['numbers'] = $numbers;
			$cartdata['price'] = $price;
			$cart_dao->add($cartdata);
		}
		echo "yes";
	}

	/**
	 * 加载用户购物车.
	 */
	public function loadcart($userid) {
		$queryMethod = new \Think\Model();
		$sql = "select c.*,p.pdname,p.imgpath from cart as c left join product as p on c.productid = p.pkid where c.userid = '$userid'";
		$data = $queryMethod -> query($sql);
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 删除用户购物车商品.
	 */
	public function deleteproductfromcart($cartid) {
		$cart_dao = M("Cart");
		$cart_dao->where("pkid = '$cartid'")->delete();
		echo "yes";
	}
	
	/**
	 * 删除用户购物车商品.
	 */
	public function desCart($cartid) {
		$cart_dao = M("Cart");
		$cart_dao->where("pkid = '$cartid'")->setDec('numbers',1);
		echo "yes";
	}
	
	/**
	 * 删除用户购物车商品.
	 */
	public function plusCart($cartid) {
		$cart_dao = M("Cart");
		$cart_dao->where("pkid = '$cartid'")->setInc('numbers',1);
		echo "yes";
	}	
	
	/**
	 * 首页获取服务产品数据.
	 */
	public function loadProductindex() {
		$product_dao = M("Product");
		$result[0] = $product_dao->where("typeid='uuid01' and updowntag=1")->order("sortno desc")->limit(0,4)->select();
		$result[1] = $product_dao->where("typeid<>'uuid01' and updowntag=1")->order("sortno desc")->limit(0,4)->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}		

}
