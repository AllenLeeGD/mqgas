<?php
namespace Mq\Controller;
use Think\Controller;

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
			$statussql = " and (m.status=0 or m.status=1 or m.status=2 or m.status=5 or m.status=6 or m.status=7)";
			$statussqlcount = " and (status=0 or status=1 or status=2 or status=5 or status=6 or status=7)";
		} else if ($status == 'finished') {
			$statussql = " and (m.status=3 or m.status=4 or m.status=8)";
			$statussqlcount = " and (status=3 or status=4 or status=8)";
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

	public function refundOrder(){
		$obj = getObjFromPost(["orderid","remark"]);
		$orderid = $obj['orderid'];
		$order_dao = M("Ordermain");
		$order_data['status'] = 2;
		$order_data['prerefundtime'] = time();
		$order_data['remark'] = $obj['remark'];
		$order_dao->where("pkid='$orderid'")->save($order_data);
		echo "yes";
		
	}
	
	public function cancleOrder($orderid){
		$order_dao = M("Ordermain");
		$order_data['status'] = -1;
		$order_data['ivtime'] = time();
		$order_dao->where("pkid='$orderid'")->save($order_data);
		echo "yes";
	}
	
	public function findProductOrderByStatus($status) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$orderid = $_REQUEST['keyword_search'];
		$buyername = $_REQUEST['buyername_search'];
		$querystatus = $_REQUEST['status_search'];
		if (!empty($orderid)) {
			$query_sql = $query_sql . " and pkid like '%$orderid%'";
			$countquery_sql = $countquery_sql . " and pkid like '%$orderid%'";
		}
		if ($buyername!="") {
			$query_sql = $query_sql . " and buyername LIKE '%$buyername%'";
			$countquery_sql = $countquery_sql . " and buyername LIKE '%$buyername%'";
		}
		if ($querystatus!="") {
			if($querystatus == "p0"){
				$query_sql = $query_sql . " and paytype = 0";
				$countquery_sql = $countquery_sql . " and paytype = 0";
			}else if($querystatus == "p1"){
				$query_sql = $query_sql . " and paytype = 1";
				$countquery_sql = $countquery_sql . " and paytype = 1";
			}else{
				$query_sql = $query_sql . " and status = $querystatus";
				$countquery_sql = $countquery_sql . " and status = $querystatus";	
			}			
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		if ($status == 0) {
			$count_sql = "select count(*) as totalrecord from ordermain where (status=0 or status=1 or status=2 or status=7) $countquery_sql";
			$condition_sql = "select * from ordermain  where (status=0 or status=1 or status=2 or status=7) $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 1) {
			$count_sql = "select count(*) as totalrecord from ordermain where (status=3 or status=4 or status=5) $countquery_sql";
			$condition_sql = "select * from ordermain where (status=3 or status=4 or status=5) $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 2) {
			$count_sql = "select count(*) as totalrecord from ordermain where status=8 $countquery_sql";
			$condition_sql = "select * from ordermain where status=8 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
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
			$pattypestr = $result[$i]['paytype']==0?"微信支付":"现金支付";
			$records["aaData"][] = array("单号: " . $result[$i]['pkid'] . "<br/>时间: " . $date_format,  "<span class='font-highlight-custom'>￥" . $result[$i]['price'] . "</span>", "×" . $result[$i]['buycount'], $result[$i]['buyername']."(".$pattypestr.")", getStatus($result[$i]['status']). "<br/><span>优惠金额: ￥".($result[$i]['coupon'])."</span><br/><span bid=" . $result[$i]['pkid'] . " class='font-highlight-custom'>总额: ￥" . ($result[$i]['price']*$result[$i]['buycount']-$result[$i]['coupon']) . "</span>", "<div><a class='btn btn-xs default btn-editable' data-toggle='modal' onclick=\"openOrderDetail('" . $result[$i]['pkid'] . "')\">
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

	
	protected function getOptBtn($status, $bid,$index) {
		$opt_btn = "";
		if ($status == 0 || $status == 1 || $status == 7) {
			$opt_btn = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openOrderConfirm('".$bid."','".$index."')\"><i class='fa fa-bookmark-o'></i> &nbsp;安排派送</a></div>";
		} else if ($status == 2) {
			$opt_btn = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default btn-editable'  href='#large' data-toggle='modal' onclick=\"openOrderRefund('".$bid."','".$index."')\"><i class='fa fa-cny'></i> &nbsp;同意退款</a></div>&nbsp;&nbsp;<div><a class='btn btn-xs red default btn-editable'  href='#large' data-toggle='modal' onclick=\"openOrderCancelRefund('".$bid."','".$index."')\"><i class='fa fa-umbrella'></i> &nbsp;拒绝退款</a></div>";
		}else if ($status == 5) {
			$opt_btn = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default btn-editable'  href='#large' data-toggle='modal' onclick=\"openOrderComplete('".$bid."','".$index."')\"><i class='fa fa-flag-checkered'></i> &nbsp;已送达</a></div>";
		}
		return $opt_btn;
	}
	
	public function countProductOrder($status) {
		$query = new \Think\Model();
		$count_sql = "";
		if ($status == 0) {
			$count_sql = "select count(*) from ordermain where status=1 or status=2 or status=0";
		}
		if ($status == 1) {
			$count_sql = "select count(*) from ordermain where status=3 or status=4 or status=5 ";
		}
		if($count_sql!="")
		{
			$result = $query -> query($count_sql);
			echo $result[0]['count(*)'];
		}else{
			echo "-";
		}
	}
	
	public function findProductOrderByPkid($pkid){
        $querymain = M('Ordermain');
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
//			$datamain['sendtime'] = date("Y-m-d H:i", $datamain['sendtime']);
            $query = new \Think\Model();
			$sql = "select * from orderdetail where orderid='$pkid'";
			$datamain['itemlist'] = $query -> query($sql);
            echo json_encode($datamain,JSON_UNESCAPED_UNICODE);
        }else {
            echo "no";
        }
    }

	public function send($bid){
		$obj = getObjFromPost(["content"]);
		//获取用户积分
		$pointdao = M("Sysparam");
		$pdata = $pointdao->where("kkey='pointset'")->find();
		$point = $pdata['vvalue'];
		$memberdao = M("Memberinfo");
		
		$dao = M("Ordermain");
		$check = $dao->where("pkid='$bid'")->find();
		if($check['status'] == 0){
			if($check['paytype']==0){
				$data['status']=6;	
			}else{
				$data['status']=5;	
			}
		}else if($check['status'] == 7){
			$data['status']=5;
		}
		$data['sendtime'] = time();
		$data['returnmsg'] = $obj["content"];
		$dao->where("pkid='$bid'")->save($data);
		$userid = $check['buyer'];
		$memberdata = $memberdao->where("pkid='$userid'")->find();
		$memberdata['point'] = $memberdata['point']+$point;
		$memberdao->where("pkid='$userid'")->save($memberdata);
		addLog(1, session("userid"), "派送了订单".$bid);
		echo "yes";
	}
	
	public function complete($bid){
		//获取用户积分
//		$pointdao = M("Sysparam");
//		$pdata = $pointdao->where("kkey='pointset'")->find();
//		$point = $pdata['vvalue'];
//		$memberdao = M("Memberinfo");
		
		$dao = M("Ordermain");
		$data['status']=8;
		$data['ivtime'] = time();
		$dao->where("pkid='$bid'")->save($data);
//		$userid = $check['buyer'];
//		$memberdata = $memberdao->where("pkid='$userid'")->find();
//		$memberdata['point'] = $memberdata['point']+$point;
//		$memberdao->where("pkid='$userid'")->save($memberdata);
		addLog(1, session("userid"), "完成派送订单".$bid);
		echo "yes";
	}
	
	public function refundProductOrder($pkid){
		$obj = getObjFromPost(["content"]);
		$dao = M("Ordermain");
		$data['status']=4;
		$data['refundtime'] = time();
		$data['refundremark'] = $obj['content'];
		$dao->where("pkid='$pkid'")->save($data);
		echo "yes";
	}
	
	public function refuseProductOrder($pkid){
		$obj = getObjFromPost(["content"]);
		$dao = M("Ordermain");
		$data['status']=3;
		$data['refusetime'] = time();
		$data['refuseremark'] = $obj['content'];
		$dao->where("pkid='$pkid'")->save($data);
		echo "yes";
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

	public function pay($orderid,$money){
		$order_dao = M("Ordermain");
		$check = $order_dao->where("pkid='$orderid'")->find();
		if($check['status']==6){
			$data['status'] = 7;
			$order_dao->where("pkid='$orderid'")->save($data);					
		}
		echo "yes";
	}

	public function paynotify($outtradeno){
		$order_dao = M("Ordermain");
		$check = $order_dao->where("pkid='$outtradeno'")->find();
		if($check['status']==6){
			$data['status'] = 7;
			$order_dao->where("pkid='$outtradeno'")->save($data);					
		}
	}
}
