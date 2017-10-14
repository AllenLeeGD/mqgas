<?php
namespace Mq\Controller;
use Think\Controller;

class MemberController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function addOrder(){
		$obj = getObjFromPost(array("userid","realname","mobile","address","remark","paytype","sendtime","price","buycount","coupon","couponnumbers"));
		$member_data['realname'] = $obj['realname'];
		$member_data['mobile'] = $obj['mobile'];
		$member_data['address'] = $obj['address'];
		$member_dao = M("Memberinfo");
		$userid = $obj['userid'];
		$member_dao->where("pkid='$userid'")->save($member_data);
		
		$order_dao = M("Ordermain");
		$order_data['pkid'] = date('YmdHis')."".rand(1000,9999);
		$order_data['buyer'] = $userid;
		$order_data['buytime'] = time();
		$order_data['ivtime'] = time();
		if($obj['paytype']=="0"){
			$order_data['status'] = 6;
		}else{
			$order_data['status'] = 0;
		}
		
		$order_data['price'] = $obj['price'];
		$order_data['coupon'] = $obj['coupon'];
		$order_data['buycount'] = $obj['buycount'];
		$order_data['buyername'] = $obj['realname'];
		$order_data['buyermobile'] = $obj['mobile'];
		$order_data['buyeraddress'] = $obj['address'];
		$order_data['paytype'] = $obj['paytype'];
		$order_data['sendtime'] = $obj['sendtime'];
		$order_data['remark'] = $obj['remark'];
		$order_dao->add($order_data);
		$coupons = explode(",",$obj['couponnumbers']);
		$c_dao = M("Coupon");
		for($i=0;$i<count($coupons);$i++){
			$item = $coupons[$i];
			$itemdata['status'] = 3;
			$itemdata['orderid'] = $order_data['pkid'];
			$itemdata['usetime'] = time();
			$c_dao->where("pkid='$item'")->save($itemdata);
		}
		echo "yes-".$order_data['pkid'];
		
	}
	
	public function loadUserinfo($pkid){
		$memberinfo = M("Memberinfo");
		$member_data = $memberinfo->where("pkid='$pkid'")->find();
		$levelid = $member_data['level'];
		$leveldao = M("Level");
		$level_data = $leveldao->where("pkid='$levelid'")->find();
		$result['price']=$level_data['price'];
		$result['realname'] = $member_data['realname'];
		$result['mobile'] = $member_data['mobile'];
		$result['address'] = $member_data['address'];
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadAccount($pkid){
		$memberinfo = M("Memberinfo");
		$member_data = $memberinfo->where("pkid='$pkid'")->find();
		$result['headicon']=$member_data['headicon'];
		$result['realname'] = $member_data['realname'];
		
		$order_dao = M("Ordermain");
		$ordercount = $order_dao->where("buyer='$pkid' and (status=0 or status=1 or status=5 or status=6 or status=7)")->count();
		
		$result['ordercount'] = $ordercount;
		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	} 

	public function loadSetting($pkid){
		$memberinfo = M("Memberinfo");
		$member_data = $memberinfo->where("pkid='$pkid'")->find();
		$result['headicon']=$member_data['headicon'];
		$result['realname'] = $member_data['realname'];
		$result['mobile'] = $member_data['mobile'];
		$result['address'] = $member_data['address'];
		
		$levelid = $member_data['level'];
		$leveldao = M("Level");
		$level_data = $leveldao->where("pkid='$levelid'")->find();
		
		$result['levelname'] = $level_data['levelname'];
		
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	} 
	
	public function saveSetting(){
		$obj = getObjFromPost(array("pkid","realname","mobile","address","headicon"));
		$dao = M("Memberinfo");
		$pkid = $obj['pkid'];
		$dao->where("pkid='$pkid'")->save($obj);
		echo "yes";
	}
	
	public function findSetting(){
		$level_dao = M("Level");
		$result = $level_dao->order("oo")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	public function saveUserSetting(){
		$obj = getObjFromPost(array("l1name","l1price","l2name","l2price","l3name","l3price","l4name","l4price","l5name","l5price","l6name","l6price","l7name","l7price","l8name","l8price","l9name","l9price","l10name","l10price"));
		$level_dao = M("Level");
		$data_1['levelname'] = $obj['l1name'];
		$data_1['price'] = $obj['l1price'];
		$changemsg="";
		
		$old1 = $level_dao->where("pkid='l1'")->find();
		if($old1['levelname'] != $obj['l1name']){
			$changemsg = $changemsg.$old1['levelname']." -> ".$obj['l1name']."<br/>";
		}
		if($old1['price'] != $obj['l1price']){
			$changemsg = $changemsg.$obj['l1name'].":".$old1['price']." -> ".$obj['l1name'].":".$obj['l1price']."<br/>";
		}
		
		$level_dao->where("pkid='l1'")->save($data_1);
		
		$data_2['levelname'] = $obj['l2name'];
		$data_2['price'] = $obj['l2price'];
		
		$old2 = $level_dao->where("pkid='l2'")->find();
		if($old2['levelname'] != $obj['l2name']){
			$changemsg = $changemsg.$old2['levelname']." -> ".$obj['l2name']."<br/>";
		}
		if($old2['price'] != $obj['l2price']){
			$changemsg = $changemsg.$obj['l2name'].":".$old2['price']." -> ".$obj['l2name'].":".$obj['l2price']."<br/>";
		}
		
		$level_dao->where("pkid='l2'")->save($data_2);
		
		$data_3['levelname'] = $obj['l3name'];
		$data_3['price'] = $obj['l3price'];
		
		$old3 = $level_dao->where("pkid='l3'")->find();
		if($old3['levelname'] != $obj['l3name']){
			$changemsg = $changemsg.$old3['levelname']." -> ".$obj['l3name']."<br/>";
		}
		if($old3['price'] != $obj['l3price']){
			$changemsg = $changemsg.$obj['l3name'].":".$old3['price']." -> ".$obj['l3name'].":".$obj['l3price']."<br/>";
		}
		
		$level_dao->where("pkid='l3'")->save($data_3);
		
		$data_4['levelname'] = $obj['l4name'];
		$data_4['price'] = $obj['l4price'];
		
		$old4 = $level_dao->where("pkid='l4'")->find();
		if($old4['levelname'] != $obj['l4name']){
			$changemsg = $changemsg.$old4['levelname']." -> ".$obj['l4name']."<br/>";
		}
		if($old4['price'] != $obj['l4price']){
			$changemsg = $changemsg.$obj['l4name'].":".$old4['price']." -> ".$obj['l4name'].":".$obj['l4price']."<br/>";
		}
		
		$level_dao->where("pkid='l4'")->save($data_4);
		
		$data_5['levelname'] = $obj['l5name'];
		$data_5['price'] = $obj['l5price'];
		
		$old5 = $level_dao->where("pkid='l5'")->find();
		if($old5['levelname'] != $obj['l5name']){
			$changemsg = $changemsg.$old5['levelname']." -> ".$obj['l5name']."<br/>";
		}
		if($old5['price'] != $obj['l5price']){
			$changemsg = $changemsg.$obj['l5name'].":".$old5['price']." -> ".$obj['l5name'].":".$obj['l5price']."<br/>";
		}
		
		$level_dao->where("pkid='l5'")->save($data_5);
		
		$data_6['levelname'] = $obj['l6name'];
		$data_6['price'] = $obj['l6price'];
		
		$old6 = $level_dao->where("pkid='l6'")->find();
		if($old6['levelname'] != $obj['l6name']){
			$changemsg = $changemsg.$old6['levelname']." -> ".$obj['l6name']."<br/>";
		}
		if($old6['price'] != $obj['l6price']){
			$changemsg = $changemsg.$obj['l6name'].":".$old6['price']." -> ".$obj['l6name'].":".$obj['l6price']."<br/>";
		}
		
		$level_dao->where("pkid='l6'")->save($data_6);
		
		$data_7['levelname'] = $obj['l7name'];
		$data_7['price'] = $obj['l7price'];
		
		$old7 = $level_dao->where("pkid='l7'")->find();
		if($old7['levelname'] != $obj['l7name']){
			$changemsg = $changemsg.$old7['levelname']." -> ".$obj['l7name']."<br/>";
		}
		if($old7['price'] != $obj['l7price']){
			$changemsg = $changemsg.$obj['l7name'].":".$old7['price']." -> ".$obj['l7name'].":".$obj['l7price']."<br/>";
		}
		
		$level_dao->where("pkid='l7'")->save($data_7);
		
		$data_8['levelname'] = $obj['l8name'];
		$data_8['price'] = $obj['l8price'];
		
		$old8 = $level_dao->where("pkid='l8'")->find();
		if($old8['levelname'] != $obj['l8name']){
			$changemsg = $changemsg.$old8['levelname']." -> ".$obj['l8name']."<br/>";
		}
		if($old8['price'] != $obj['l8price']){
			$changemsg = $changemsg.$obj['l8name'].":".$old8['price']." -> ".$obj['l8name'].":".$obj['l8price']."<br/>";
		}
		
		$level_dao->where("pkid='l8'")->save($data_8);
		
		$data_9['levelname'] = $obj['l9name'];
		$data_9['price'] = $obj['l9price'];
		
		$old9 = $level_dao->where("pkid='l9'")->find();
		if($old9['levelname'] != $obj['l9name']){
			$changemsg = $changemsg.$old9['levelname']." -> ".$obj['l9name']."<br/>";
		}
		if($old9['price'] != $obj['l9price']){
			$changemsg = $changemsg.$obj['l9name'].":".$old9['price']." -> ".$obj['l9name'].":".$obj['l9price']."<br/>";
		}
		
		$level_dao->where("pkid='l9'")->save($data_9);
		
		$data_10['levelname'] = $obj['l10name'];
		$data_10['price'] = $obj['l10price'];
		
		$old10 = $level_dao->where("pkid='l10'")->find();
		if($old10['levelname'] != $obj['l10name']){
			$changemsg = $changemsg.$old10['levelname']." -> ".$obj['l10name']."<br/>";
		}
		if($old10['price'] != $obj['l10price']){
			$changemsg = $changemsg.$obj['l10name'].":".$old10['price']." -> ".$obj['l10name'].":".$obj['l10price']."<br/>";
		}
		
		$level_dao->where("pkid='l10'")->save($data_10);
		
		addLog(3,session("userid"),"<a href='javascript:showMember(\"".$changemsg."\")'>更改了参数设置</a>");
		echo "yes";
	}
	
	
	public function findMember() {
		$query_sql = "";
		$countquery_sql = "";
		$realname = $_REQUEST['realname_search'];
		$mobile = $_REQUEST['mobile_search'];
		$yewuname = $_REQUEST['yewuname_search'];
		$membertype = $_REQUEST['membertype_search'];
		if (!empty($realname)) {
			$query_sql = $query_sql . " and m.realname LIKE '%$realname%'";
			$countquery_sql = $countquery_sql . " and realname LIKE '%$realname%'";			
		}
		if (!empty($mobile)) {
			$query_sql = $query_sql . " and m.mobile like '%$mobile%'";
			$countquery_sql = $countquery_sql . " and mobile like '%$mobile%'";			
		}
		if (!empty($yewuname)) {
			$query_sql = $query_sql . " and m.yewuname like '%$yewuname%'";
			$countquery_sql = $countquery_sql . " and yewuname like '%$yewuname%'";
		}
		if (!empty($membertype)) {
			$query_sql = $query_sql . " and m.membertype = '$membertype'";
			$countquery_sql = $countquery_sql . " and membertype = '$membertype'";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		$query = new \Think\Model();
		$count_sql = "select count(*) as totalrecord ".
		"from memberinfo where 1=1 $countquery_sql";
		$resultcount = $query -> query($count_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$condition_sql = "select m.*,l.levelname from memberinfo as m left join level as l on m.level=l.pkid where 1=1 $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength";
		$result = $query -> query($condition_sql);
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$sBtn = "";
		$jsparams = "realname:$realname,mobile:$mobile,idno:$idno";
		for ($i = 0; $i < count($result); $i++) {
			if(!empty($result[$i]['coupondate'])){
				$getcoupon="已领用";
			}else{
				$getcoupon="未领用";
			}
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openMemberEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnOut = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openMemberOut('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa  fa-eraser'></i> &nbsp;退户&nbsp;</a>";
			if($result[$i]['membertype']==1){
				$membertype = "居民";
			}else if($result[$i]['membertype']==2){
				$membertype = "小工商";
			}else if($result[$i]['membertype']==3){
				$membertype = "大工商";
			}
			$realname = $result[$i]['realname'];
			$btns = $btnEdit;
			if($result[$i]['status']==-1){
				$realname = $realname."(已退户)";
			}else{
				$btns = $btnEdit.$btnOut;
			}
			$mobile = $result[$i]['mobile'];
			if(strlen($mobile)>30){
				$mobile = "<a title='$mobile'>".substr($mobile, 0,30)."......</a>";
			}
			$records["aaData"][] = array("<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openMemberDetail('" . $result[$i]['pkid'] . "')\">".$realname."</a></span></div>","<div class=\"product-label\" style=\"text-align:center;\"><span>".$mobile."</span></div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['levelname']."</div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['yewuname']."</div>",$membertype,$btns);
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
	
	protected function getBottleType($type){
		if($type==1){
			return "退户瓶";
		}else if($type==2){
			return "还瓶";
		}else if($type==3){
			return "回收杂瓶";
		}else if($type==4){
			return "回流瓶";
		}else if($type==5){
			return "入重瓶";
		}else if($type==6){
			return "借出瓶";
		}else if($type==7){
			return "押金瓶";
		}else if($type==8){
			return "回收杂瓶";
		}else if($type==9){
			return "回流瓶";
		}else if($type==10){
			return "售重瓶";
		}
	}
	
	protected function getBottleProduct($item){
		return $item['pname'].$item['jname'].$item['rname'].$item['fname'].$item['gpname'];
	}

	public function findMemberByPkid($pkid) {
        $query = new \Think\Model();
        $sql = "select m.*,l.levelname from memberinfo as m left join level as l on m.level=l.pkid where m.pkid='$pkid'";
		$data = $query->query($sql);
		$gp_dao = M("Bottle");
		$aj_dao = M("Safecheck");
		$jg_dao = M("Price");
		$gp_data = $gp_dao->where("memberid='$pkid'")->select();
		$aj_data = $aj_dao->where("memberid='$pkid'")->select();
		$jg_data = $jg_dao->where("memberid='$pkid'")->select();
		$gp_result="";$aj_result="";$jp_result="";
		for($i=0;$i<count($gp_data);$i++){
			$_item = $gp_data[$i];
			$gp_result = $gp_result.date('Y-m-d', $_item['optdate'])." ".$this ->getBottleType($_item['type'])." (".$this ->getBottleProduct($_item).") ".$_item['optnumber']."瓶<br/>";
		}
		
		for($i=0;$i<count($aj_data);$i++){
			$_item = $aj_data[$i];
			$aj_result = $aj_result.date('Y-m-d', $_item['checkdate'])." 进行了安检,备注信息:".$_item['remark']."<br/>";
		}
		
		for($i=0;$i<count($jg_data);$i++){
			$_item = $jg_data[$i];
			$jp_result = $jp_result.$_item['name'].($_item['type']==0?" 每瓶":" 每吨")." 价格:".$_item['price']."<br/>";
		}
		
		$data[0]['gangping']=$gp_result;
		$data[0]['anjian']=$aj_result;
		$data[0]['jiage']=$jp_result;
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	
	public function addMember(){
		$obj = getObjFromPost(array("realname","code","storename","membertype","detailtype","yewuid","yewuname","mobile","address","level","yue","zhangqi"));
		$dao = M("Memberinfo");
		$obj["pkid"] = uniqid();
		$obj["regtime"] = time();
		$obj["nickname"] = $obj["realname"];
		$obj["headicon"] = "../images/nohead.png";
		$dao->where("pkid='$pkid'")->add($obj);
		addLog(2, session("userid"), "新增了客户 ".$obj['realname']."(".$obj['mobile'].")的信息");
		echo "yes";
	}
	
	protected function getMemberType($membertype){
		if($membertype==1){
			return "居民用户";
		}else if($membertype==2){
			return "小工商";
		}else if($membertype==3){
			return "大工商";
		}
	}
	
	protected function getDetailtype($detailtype){
		if($detailtype==1){
			return "代理商";
		}else if($detailtype==2){
			return "来料加工";
		}else if($detailtype==3){
			return "门店";
		}else if($detailtype==4){
			return "门店气";
		}else if($detailtype==5){
			return "民用气";
		}else if($detailtype==6){
			return "直营代理";
		}
	}
	
	protected function yue($yue){
		if($yue==0){
			return "非月结";
		}else if($yue==1){
			return "月结";
		}
	}
	
	public function saveMember(){
		$obj = getObjFromPost(array("pkid","realname","code","storename","membertype","detailtype","yewuid","yewuname","mobile","address","level","yue","zhangqi"));
		$dao = M("Memberinfo");
		$pkid = $obj["pkid"];
		$old = $dao->where("pkid='$pkid'")->find();
		
		if($old['realname'] != $obj['realname']){
			$changedata = "客户姓名:".$old['realname']."->".$obj['realname']."<br>";	
		}
		if($old['code'] != $obj['code']){
			$changedata = $changedata."客户编码:".$old['code']."->".$obj['code']."<br>";	
		}
		if($old['storename'] != $obj['storename']){
			$changedata = $changedata."店铺名称:".$old['storename']."->".$obj['storename']."<br>";	
		}if($old['membertype'] != $obj['membertype']){
			$changedata = $changedata."客户分类:".$this->getMemberType($old['membertype'])."->".$this->getMemberType($obj['membertype'])."<br>";	
		}if($old['detailtype'] != $obj['detailtype']){
			$changedata = $changedata."详细分类:".$this->getDetailtype($old['detailtype'])."->".$this->getDetailtype($obj['detailtype'])."<br>";	
		}if($old['yewuname'] != $obj['yewuname']){
			$changedata = $changedata."业务员:".$old['yewuname']."->".$obj['yewuname']."<br>";	
		}if($old['mobile'] != $obj['mobile']){
			$changedata = $changedata."联系电话:".$old['mobile']."->".$obj['mobile']."<br>";	
		}if($old['address'] != $obj['address']){
			$changedata = $changedata."送货地址:".$old['address']."->".$obj['address']."<br>";	
		}if($old['level'] != $obj['level']){
			$changedata = $changedata."会员等级:".$old['level']."->".$obj['level']."<br>";	
		}if($old['yue'] != $obj['yue']){
			$changedata = $changedata."月结客户:".$this->yue($old['yue'])."->".$this->yue($obj['yue'])."<br>";	
		}if($old['zhangqi'] != $obj['zhangqi']){
			$changedata = $changedata."核定账期:".$old['zhangqi']."->".$obj['zhangqi']."<br>";	
		}
		
		$dao->where("pkid='$pkid'")->save($obj);
		addLog(2, session("userid"), "<a href='javascript:showMember(\"".$changedata."\")'>更改了客户 ".$obj['realname']."(".$obj['mobile'].")的信息</a>");
		echo "yes";
	}
	
	public function out($bid){
		$dao = M("Memberinfo");
		$obj = getObjFromPost(array("content"));
		$data['status'] = -1;
		$data['outreason'] = $obj['content'];
		$data['outdate'] = time();
		$dao->where("pkid = '$bid'")->save($data);
		$result = $dao->where("pkid = '$bid'")->find();
		addLog(2, session("userid"), "给客户 ".$result['realname']."(".$result['mobile'].")办理了退户");
		echo "yes";
	}
	
	public function importExcel(){
		// 导入csv格式的数据
       $file = '/www/web/mq/public_html/info/1.xls';
        // 判断文件是什么格式
	    $type = pathinfo($file); 
	    $type = strtolower($type["extension"]);
	    $type=$type==='csv' ? $type : 'Excel5';
	    ini_set('max_execution_time', '0');
	    Vendor('PHPExcel.PHPExcel');
	    // 判断使用哪种格式
	    $objReader = \PHPExcel_IOFactory::createReader($type);
	    $objPHPExcel = $objReader->load($file); 
	    $sheet = $objPHPExcel->getSheet(0); 
	    // 取得总行数 
	    $highestRow = $sheet->getHighestRow();     
	    // 取得总列数      
	    $highestColumn = $sheet->getHighestColumn(); 
	    //循环读取excel文件,读取一条,插入一条
	    $data=array();
	    //从第一行开始读取数据
	    for($j=0;$j<=$highestRow;$j++){
	        //从A列读取数据
	        for($k='A';$k<=$highestColumn;$k++){
	            // 读取单元格
	            $data[$j][]=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
	        } 
	    }  
	    $dao = M("Vipmember");
        for($i = 1;$i<count($data);$i++){
        		$d['pkid'] = uniqid();
        		$d['name'] = $data[$i][1];
        		$d['mobile'] = $data[$i][2];
        		$d['address'] = $data[$i][3];
        		$d['type'] = 'inter';
        		$dao->add($d);
        }
        echo "done";
	}

	public function findMemberPriceinfo($memberid){
		$dao = M("Price");
		$result = $dao->where("memberid='$memberid'")->select();
		$varstring="";
		for($i=0;$i<count($result);$i++){
			$item = $result[$i];
			if($item['type']==0){
				$typestr="每瓶";
			}else{
				$typestr="每吨";
			}
			$varstring=$varstring.$item['name']." ".$typestr." ".$item['price']."\n";
		}
		echo $varstring;
	}
}
