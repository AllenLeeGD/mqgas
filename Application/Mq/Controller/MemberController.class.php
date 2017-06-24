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
		$level_dao->where("pkid='l1'")->save($data_1);
		
		$data_2['levelname'] = $obj['l2name'];
		$data_2['price'] = $obj['l2price'];
		$level_dao->where("pkid='l2'")->save($data_2);
		
		$data_3['levelname'] = $obj['l3name'];
		$data_3['price'] = $obj['l3price'];
		$level_dao->where("pkid='l3'")->save($data_3);
		
		$data_4['levelname'] = $obj['l4name'];
		$data_4['price'] = $obj['l4price'];
		$level_dao->where("pkid='l4'")->save($data_4);
		
		$data_5['levelname'] = $obj['l5name'];
		$data_5['price'] = $obj['l5price'];
		$level_dao->where("pkid='l5'")->save($data_5);
		
		$data_6['levelname'] = $obj['l6name'];
		$data_6['price'] = $obj['l6price'];
		$level_dao->where("pkid='l6'")->save($data_6);
		
		$data_7['levelname'] = $obj['l7name'];
		$data_7['price'] = $obj['l7price'];
		$level_dao->where("pkid='l7'")->save($data_7);
		
		$data_8['levelname'] = $obj['l8name'];
		$data_8['price'] = $obj['l8price'];
		$level_dao->where("pkid='l8'")->save($data_8);
		
		$data_9['levelname'] = $obj['l9name'];
		$data_9['price'] = $obj['l9price'];
		$level_dao->where("pkid='l9'")->save($data_9);
		
		$data_10['levelname'] = $obj['l10name'];
		$data_10['price'] = $obj['l10price'];
		$level_dao->where("pkid='l10'")->save($data_10);
		addLog(3,session("userid"),"更改了参数设置");
		echo "yes";
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
				$query_sql = $query_sql . " m.mobile like '%$mobile%'";
				$countquery_sql = $countquery_sql . " mobile like '%$mobile%'";
			}
			else{
				$query_sql = $query_sql . " and m.mobile like '%$mobile%'";
				$countquery_sql = $countquery_sql . " and mobile like '%$mobile%'";
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
		$condition_sql = "select m.*,l.levelname from memberinfo as m left join level as l on m.level=l.pkid $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength";
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
			$records["aaData"][] = array("<div class=\"product-label\"><span><a style=\"cursor:pointer;\" data-toggle='modal' onclick=\"openMemberDetail('" . $result[$i]['pkid'] . "')\">".$result[$i]['realname']."</a></span></div>","<div class=\"product-label\" style=\"text-align:center;\"><span>".$result[$i]['mobile']."</span></div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['levelname']."</div>","<div class=\"product-label\" style=\"text-align:center;\">".$result[$i]['point']."</div>","<div class=\"product-label\" style=\"text-align:center;\">".$getcoupon."</div>",$btnEdit);
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
        $sql = "select m.*,l.levelname from memberinfo as m left join level as l on m.level=l.pkid where m.pkid='$pkid'";
		$data = $query->query($sql);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
	}
	
	public function saveMember(){
		$obj = getObjFromPost(array("pkid","realname","mobile","address","level"));
		$dao = M("Memberinfo");
		$pkid = $obj["pkid"];
		$dao->where("pkid='$pkid'")->save($obj);
		addLog(2, session("userid"), "更改了用户 ".$obj['realname']."(".$obj['mobile'].")的信息");
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
}
