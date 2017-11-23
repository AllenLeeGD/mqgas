<?php
namespace Mq\Controller;
use Think\Controller;

class JMOrderController extends Controller {

	public function _initialize() {
		putHeader();
	}

	public function findProductOrderByStatus($status) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$orderid = $_REQUEST['order_search'];
		$buyername = $_REQUEST['buyername_search'];
		$mobile_search = $_REQUEST['mobile_search'];
		if (!empty($orderid)) {
			$query_sql = $query_sql . " and (o.pkid like '%$orderid%' or d.songqiname like '%$orderid%' or d.carnumber like '%$orderid%')";
			$countquery_sql = $countquery_sql . " and (o.pkid like '%$orderid%' or d.songqiname like '%$orderid%' or d.carnumber like '%$orderid%')";
		}
		if ($buyername!="") {
			$query_sql = $query_sql . " and o.buyername LIKE '%$buyername%'";
			$countquery_sql = $countquery_sql . " and o.buyername LIKE '%$buyername%'";
		}
		if ($mobile_search!="") {
			$date_search = strtotime($mobile_search);
			$query_sql = $query_sql . " and (o.buyermobile LIKE '%$mobile_search%' or from_unixtime(o.buytime,'%Y-%m-%d') = from_unixtime($date_search,'%Y-%m-%d'))";
			$countquery_sql = $countquery_sql . " and (o.buyermobile LIKE '%$mobile_search%' or from_unixtime(o.buytime,'%Y-%m-%d') = from_unixtime($date_search,'%Y-%m-%d'))";
		}
		//如果登录用户角色是门店营业员，则只显示本门店的订单
		$loginuserid = session("userid");
		$user_dao = M("Userinfo");
		$checkuser = $user_dao->where("pid='$loginuserid'")->find();
		if($checkuser['role']==8){
			$mid = $checkuser['did'];
			$query_sql = $query_sql . " and d.mid = '$mid'";
			$countquery_sql = $countquery_sql . " and d.mid = '$mid'";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);		
		if ($status == 0) {//暂存
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=0  $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=0 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 1) {//待分派片区
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=1 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=1 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 2) {//其他的
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and (jmstatus=2 or jmstatus=3 or jmstatus=4 or jmstatus=5 or jmstatus=6)  $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and (jmstatus=2 or jmstatus=3 or jmstatus=4 or jmstatus=5 or jmstatus=6)  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 3) {//待分配
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=2  $countquery_sql";
			$condition_sql = "select o.*,d.mid from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=2 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 4) {//待收款
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and (jmstatus=3 or jmstatus=8) $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and (jmstatus=3 or jmstatus=8) $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}
		if ($status == 5) {//待存款
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=4 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=4  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}if ($status == 6) {//待核款
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=5 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=5  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}if ($status == 7) {//配送中，给话务看
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=3 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=3 $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
		}if ($status == 8) {//已送达，给话务看
			$count_sql = "select count(*) as totalrecord from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=8 $countquery_sql";
			$condition_sql = "select o.* from ordermain as o left join orderjm as d on d.orderid = o.pkid  where status=-7 and jmstatus=8  $query_sql order by buytime desc,pkid limit $iDisplayStart,$iDisplayLength";
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
			$btnEdit = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."')\"><i class='fa fa-pencil'></i> &nbsp;编辑</a></div>";
			$btnSend = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openSend('".$result[$i]['pkid']."')\"><i class='fa fa-bookmark-o'></i> &nbsp;发送</a></div>";
			$btnCancle = "<div class=\"margin-top-10\"><a class='btn btn-xs red default'  data-toggle='modal' onclick=\"openCancle('".$result[$i]['pkid']."')\"><i class='fa fa-ban'></i> &nbsp;取消</a></div>";			
			$btnFen = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openFen('".$result[$i]['pkid']."','".$result[$i]['buyeraddress']."')\"><i class='fa fa-puzzle-piece'></i> &nbsp;分派</a></div>";
			$btnPei = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openPei('".$result[$i]['pkid']."','".$result[$i]['mid']."')\"><i class='fa fa-puzzle-piece'></i> &nbsp;分配</a></div>";
			$btnPrint = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openPeiPrint('".$result[$i]['pkid']."','".$result[$i]['mid']."')\"><i class='fa fa-puzzle-piece'></i> &nbsp;分配并打单</a></div>";
			$btnShou = "<div class=\"margin-top-10\"><a class='btn btn-xs yellow default'  data-toggle='modal' onclick=\"openShou('".$result[$i]['pkid']."')\"><i class='fa fa-ticket'></i> &nbsp;收款</a></div>";
			$btnCun = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openCun('".$result[$i]['pkid']."')\"><i class='fa fa-suitcase'></i> &nbsp;存款</a></div>";
			$btnHe = "<div class=\"margin-top-10\"><a class='btn btn-xs green default'  data-toggle='modal' onclick=\"openHe('".$result[$i]['pkid']."')\"><i class='fa fa-suitcase'></i> &nbsp;核款</a></div>";
			$btnJie = "<div class=\"margin-top-10\"><a class='btn btn-xs blue default'  data-toggle='modal' onclick=\"openJie('".$result[$i]['pkid']."')\"><i class='fa fa-suitcase'></i> &nbsp;月结</a></div>";
			$showBtn = "";
			$pre = "";
			if($status==0){
				$showBtn = $btnEdit.$btnSend.$btnCancle;
			}else if($status==1){
				$showBtn = $btnFen.$btnCancle;
			}else if($status==2){
				$showBtn = $btnCancle;
			}else if($status==3){
				$showBtn = $btnPei.$btnPrint;
			}else if($status==4){
				$showBtn = $btnShou.$btnJie;
				$pre = "<input name='Fruit' type='checkbox' value='".$result[$i]['pkid']."' />";
			}else if($status==5){
				$showBtn = $btnCun;
				$pre = "<input name='Fruit' type='checkbox' value='".$result[$i]['pkid']."' />";
			}else if($status==6){
				$showBtn = $btnHe;
			}else if($status==7){
				$showBtn = "$btnCancle";
			}else if($status==8){
				$showBtn = "$btnCancle";
			}
			$statusStr = getNewStatus($result[$i]['status'], $result[$i]['jmstatus'], $result[$i]['dgsstatus'], $result[$i]['hspstatus']);
			$date_format = date("Y-m-d H:i:s", $result[$i]['buytime']);
			$recardate_format = date("Y-m-d H:i:s", $result[$i]['recardate']);
			$records["aaData"][] = array($pre."单号: " . $result[$i]['pkid'] . "<br/>时间: " . $date_format. "<br/>状态: " .$statusStr,  "<span class='font-highlight-custom'>" . $result[$i]['buyername'] . "</span>", $result[$i]['buyeraddress'], $result[$i]['buyermobile'], $result[$i]['price'], "<div><a class='btn btn-xs default btn-editable' data-toggle='modal' onclick=\"openOrderDetail('" . $result[$i]['pkid'] . "')\">
			<i class='fa fa-search-plus'></i> 详情</a></div>" .$showBtn);
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

	public function findOrderbyPkid($pkid){
		$dao = M("Ordermain");
		$dao_detail = M("Orderdetail");
		$result = $dao->where("pkid='$pkid'")->find();
		$list = $dao_detail->where("orderid = '$pkid'")->select();
		$result['details'] = $list;
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 取消订单.
	 * @bid 订单ID
	 */
	public function cancle($bid){
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = -1;
		$data_jm['cancletime'] = time();
		$data_jm['cancleoptid'] = session("userid");
		$data_jm['cancleoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "取消了订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>");
		echo "yes";
	}
	
	/**
	 * 发送订单.
	 * @bid 订单ID
	 */
	public function send($bid){
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 1;
		$data['sendtime'] = time();
		$data_jm['optorderid'] = session("userid");
		$data_jm['optordername'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "发送了订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>");
		echo "yes";
	}
	
	/**
	 * 分派订单.
	 * @bid 订单ID
	 */
	public function dofen($bid){
		$obj = getObjFromPost(array("did","pid",'dname','pname'));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 2;
		$data_jm['mid'] = $obj['did'];
		$data_jm['mname'] = $obj['dname'];
		$data_jm['pid'] = $obj['pid'];
		$data_jm['pname'] = $obj['pname'];
		$data_jm['fenpaiid'] = session("userid");
		$data_jm['fenpainame'] = session("name");
		$data_jm['fenpaitime'] = time();
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "分派了订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>");
		echo "yes";
	}
	
	public function loadSongqis($did){
		$dao  = M("Songqidaily");
		$result = $dao->where("did = '$did' and from_unixtime(dailydate,'%Y-%m-%d') = date_format(now(), '%Y-%m-%d')")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function loadCars($did){
		$dao  = M("Carsdaily");
		$result = $dao->where("did = '$did' and status=0 and from_unixtime(dailydate,'%Y-%m-%d') = date_format(now(), '%Y-%m-%d')")->select();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 分派订单.
	 * @bid 订单ID
	 */
	public function dopei($bid){
		$obj = getObjFromPost(array("songqiid","carid",'songqiname','carnumber'));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 3;
		$data_jm['songqiid'] = $obj['songqiid'];
		$data_jm['songqiname'] = $obj['songqiname'];
		$data_jm['carid'] = $obj['carid'];
		$data_jm['carnumber'] = $obj['carnumber'];
		$data_jm['setpeopleoptid'] = session("userid");
		$data_jm['setpeopleoptname'] = session("name");
		$data_jm['setpeopleopttime'] = time();
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "分配了订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>");
		echo "yes";
	}
	
	
	/**
	 * 订单收款.
	 * @bid 订单ID
	 */
	public function shou($bid){
		$obj = getObjFromPost(array("shounumber"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 4;
		$data_jm['shoutime'] = time();
		$data_jm['shouoptid'] = session("userid");
		$data_jm['shouoptname'] = session("name");
		$data_jm['shounumber'] = $obj["shounumber"];
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>收款");
		echo "yes";
	}
	
	/**
	 * 批量订单收款.
	 * @bid 订单ID
	 */
	public function plshou(){
		$obj = getObjFromPost(array("shounumber","shous"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 4;
		$data_jm['shoutime'] = time();
		$data_jm['shouoptid'] = session("userid");
		$data_jm['shouoptname'] = session("name");
		$data_jm['shounumber'] = $obj["shounumber"];
		$bids = split(",", $obj['shous']);
		for($i=0;$i<count($bids);$i++){
			$bid = $bids[$i];
			$dao->where("pkid='$bid'")->save($data);
			$dao_jm->where("orderid='$bid'")->save($data_jm);
			addLog(1, session("userid"), "订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>收款");
		}
		
		echo "yes";
	}
	
	/**
	 * 订单月结.
	 * @bid 订单ID
	 */
	public function jie($bid){
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 7;
		$data_jm['shoutime'] = time();
		$data_jm['shouoptid'] = session("userid");
		$data_jm['shouoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>月结");
		echo "yes";
	}
	
	/**
	 * 订单收款.
	 * @bid 订单ID
	 */
	public function cun($bid){
		$obj = getObjFromPost(array("cunmsg","shoutype","shoutypestr"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 5;
		$data_jm['cunmsg'] = $obj['cunmsg'];
		$data_jm['shoutype'] = $obj['shoutype'];
		$data_jm['shoutypestr'] = $obj['shoutypestr'];
		$data_jm['cuntime'] = time();
		$data_jm['cunoptid'] = session("userid");
		$data_jm['cunoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>存款");
		echo "yes";
	}
	
	/**
	 * 批量订单收款.
	 * @bid 订单ID
	 */
	public function plcun(){
		$obj = getObjFromPost(array("cunmsg","shoutype","shoutypestr","cuns"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 5;
		$data_jm['cunmsg'] = $obj['cunmsg'];
		$data_jm['shoutype'] = $obj['shoutype'];
		$data_jm['shoutypestr'] = $obj['shoutypestr'];
		$data_jm['cuntime'] = time();
		$data_jm['cunoptid'] = session("userid");
		$data_jm['cunoptname'] = session("name");
		
		$bids = split(",", $obj['cuns']);
		for($i=0;$i<count($bids);$i++){
			$bid = $bids[$i];
			$dao->where("pkid='$bid'")->save($data);
			$dao_jm->where("orderid='$bid'")->save($data_jm);
			addLog(1, session("userid"), "订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>存款");
		}
		echo "yes";
	}
	
	/**
	 * 订单核款.
	 * @bid 订单ID
	 */
	public function he($bid){
		$obj = getObjFromPost(array("hemsg"));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 6;
		$data_jm['hemsg'] = $obj['hemsg'];
		$data_jm['hetime'] = time();
		$data_jm['heoptid'] = session("userid");
		$data_jm['heoptname'] = session("name");
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>核款");
		echo "yes";
	}
	
	public function findProductOrderByPkid($pkid){
        $querymain = M('Ordermain');
        $datamain = $querymain->join("orderjm as d on d.orderid = ordermain.pkid","LEFT")->where("ordermain.pkid='$pkid'")->field("ordermain.*,d.*")->find();
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
            if(isset($datamain['arrivetime'])&& $datamain['arrivetime']>0){
                $datamain['arrivetime'] = date("Y-m-d H:i", $datamain['arrivetime']);
            }
            $datamain['status'] = getNewStatus($datamain['status'],$datamain['jmstatus'],$datamain['dgsstatus'],$datamain['hspstatus']);
            $datamain['buytime'] = date("Y-m-d H:i", $datamain['buytime']);
			$datamain['ivtime'] = date("Y-m-d H:i", $datamain['ivtime']);
//			$datamain['sendtime'] = date("Y-m-d H:i", $datamain['sendtime']);
            $query = new \Think\Model();
			$sql = "select * from orderdetail where orderid='$pkid'";
			$datamain['itemlist'] = $query -> query($sql);
			$daily_date = $datamain['setpeopleopttime'];
			$daily_carid = $datamain['carid'];
			if(!empty($daily_carid)){
				$query_c = new \Think\Model();
				$sql_c = "select * from carsdaily where carid='$daily_carid' and  DATE_FORMAT(from_unixtime(dailydate),'%Y-%m-%d')=DATE_FORMAT(from_unixtime($daily_date),'%Y-%m-%d')";
				$data_dailycar = $query_c -> query($sql_c);
				if(count($data_dailycar)>0){
					$datamain['sname'] = $data_dailycar[0]['sname'];
					$datamain['yname'] = $data_dailycar[0]['yname'];
				}
			}
            echo json_encode($datamain,JSON_UNESCAPED_UNICODE);
        }else {
            echo "no";
        }
    }
/**
	 * 暂存居民用户流程.
	 */
	function saveOrder($pkid,$status){
		$dao_main = M("Ordermain");
		$dao_jm = M("Orderjm");
		$dao_detail = M("Orderdetail");
		$obj = getObjFromPost(["content"]);
		$items = json_decode(base64_decode($obj["content"]));
		$data_main['buytime']=time();
		$data_main['ivtime']=time();
		$data_main['status']=-7;
		
		$data_main['buyername']=$items->membername;
		$data_main['buyermobile']=$items->mobile;
		$data_main['buyeraddress']=$items->address;
		$data_main['remark']=$items->remark;
		$data_main['type']=0;
		$data_main['userid']=session("userid");
		$data_main['username']=session("name");
		$data_main['jmstatus']=$status;
		
		$details = $items->itemlist;
		$totalcount=0;
		$totalmoney=0;
		$dao_detail->where("orderid='$pkid'")->delete();
		for($i=0;$i<count($details);$i++){
			$detail = $details[$i];
			$data_detail = array();
			$data_detail['pkid'] = uniqid();
			$data_detail['orderid'] = $pkid;
			$data_detail['productcount'] = $detail->productcount;
			$data_detail['bottleprice'] = $detail->bottleprice;
			$data_detail['productname'] = $detail->productname;
			if($detail->productname=="50KG气相" || $detail->productname=="50KG液相"){
				$data_detail['pid'] = $detail->pid;
				$data_detail['pname'] = $detail->pname;
				$data_detail['rid'] = $detail->rid;
				$data_detail['rname'] = $detail->rname;
			}else if($detail->productname=="15KG直阀" || $detail->productname=="15KG角阀"){
				$data_detail['pid'] = $detail->pid;
				$data_detail['pname'] = $detail->pname;
				$data_detail['jid'] = $detail->jid;
				$data_detail['jname'] = $detail->jname;
			}
			$totalcount = $totalcount+intval($detail->productcount);
			$totalmoney= $totalmoney + (intval($detail->productcount)*strval($detail->bottleprice));
			$dao_detail->add($data_detail);
		}
		$data_main['price']=$totalmoney;
		$data_main['buycount']=$totalcount;
		$dao_main->where("pkid='$pkid'")->save($data_main);
		if($status==0){
			$action = "暂存";
		}else if($status==1){
			$action = "发起";
		}
		addLog(1, session("userid"), $action."了订单<a href='javascript:showOrderDetail(\"".$pkid."\",\"jm\")'>".$pkid."</a>");
		echo "yes";
	}

	public function printJMOrder($pkid){
		Vendor('PHPWord.PHPWord');
		$bid= $pkid;
		$obj = getObjFromPost(array("songqiid","carid",'songqiname','carnumber'));
		$dao = M("Ordermain");
		$dao_jm = M("Orderjm");
		$data['jmstatus'] = 3;
		$data_jm['songqiid'] = $obj['songqiid'];
		$data_jm['songqiname'] = $obj['songqiname'];
		$data_jm['carid'] = $obj['carid'];
		$data_jm['carnumber'] = $obj['carnumber'];
		$data_jm['setpeopleoptid'] = session("userid");
		$data_jm['setpeopleoptname'] = session("name");
		$data_jm['setpeopleopttime'] = time();
		$data_jm['printflag'] = 1;
		$dao->where("pkid='$bid'")->save($data);
		$dao_jm->where("orderid='$bid'")->save($data_jm);
		addLog(1, session("userid"), "分配了订单<a href='javascript:showOrderDetail(\"".$bid."\",\"jm\")'>".$bid."</a>");
		
		$PHPWord = new \PHPWord(); 
		$orderControler = A("Mobileorder");
		$order_data = $orderControler->findyewuorderdetail_inner($pkid);
		$orderdetail_data = $orderControler->findcheduiorderdetailitem_inner($pkid);
		
		$section = $PHPWord->createSection(array('pageSizeW'=>3232,'pageSizeH'=>12000,'marginLeft'=>397, 'marginRight'=>397, 'marginTop'=>1088, 'marginBottom'=>1440));
		
		// Add text elements
//		for($k=0;$k<=2;$k++){
			$section->addText(iconv('utf-8','GB2312//IGNORE','             新海燃气'));
			$section->addTextBreak(1);
			
			$section->addText(iconv('utf-8','GB2312//IGNORE', '订单号码:  '));
			$section->addTextBreak(1);
			$section->addText($pkid);
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','总金额:  ￥').$order_data['price']);
			$section->addTextBreak(1);
			if($order_data['jmstatus']==5){//已付款
				$section->addText(iconv('utf-8','GB2312//IGNORE','支付方式:  微信支付'));
				$section->addTextBreak(1);
			}else{
				$section->addText(iconv('utf-8','GB2312//IGNORE','支付方式:  现金支付'));
				$section->addTextBreak(1);
			}
			
			$section->addText(iconv('utf-8','GB2312//IGNORE','客户名称:  ').iconv('utf-8','GB2312//IGNORE',$order_data['buyername']));
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','客户地址:  ').iconv('utf-8','GB2312//IGNORE',$order_data['buyeraddress']));
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','联系电话:  ').iconv('utf-8','GB2312//IGNORE',$order_data['buyermobile']));
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','备    注:  ').iconv('utf-8','GB2312//IGNORE',$order_data['remark']));
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','派送门店:  ').iconv('utf-8','GB2312//IGNORE',$order_data['mname']));
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','派送片区:  ').iconv('utf-8','GB2312//IGNORE',$order_data['pname']));
			$section->addTextBreak(1);
			$section->addText('****************************');
			$section->addTextBreak(1);
			
			for($i=0;$i<count($orderdetail_data);$i++){
				$_item = $orderdetail_data[$i];
				$section->addText(iconv('utf-8','GB2312//IGNORE',$_item['productname']).iconv('utf-8','GB2312//IGNORE','       ￥').$_item['bottleprice'].iconv('utf-8','GB2312//IGNORE',' ×').$_item['productcount']);
				$section->addTextBreak(1);
			}
			
			$section->addText('****************************');
			$section->addTextBreak(1);
			$section->addText('    '.date('Y-m-d H:i:s'));
			$section->addTextBreak(1);
			$section->addText(iconv('utf-8','GB2312//IGNORE','     订气热线:962299'));
			$section->addTextBreak(4);
//		}
		
		
//		$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
//		$PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
		
		header("Content-type: application/vnd.ms-word"); 
        header("Content-Disposition:attachment;filename=orderprinter.docx"); 
        header('Cache-Control: max-age=0'); 
        $objWriter = \PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save('php://output'); 
	}

	/**
	 * 批量订单合计金额.
	 * @bid 订单ID
	 */
	public function countTotal(){
		$obj = getObjFromPost(array("totals"));
		$dao = M("Ordermain");
		$bids = split(",", $obj['totals']);
		$pkids = "";
		for($i=0;$i<count($bids);$i++){
			$bid = $bids[$i];
			if($i==0){
				$pkids = "'".$bid."'";
			}else{
				$pkids = $pkids . "," . "'".$bid."'";
			}
		}
		$result = $dao->where("pkid in ($pkids)")->sum('price');
		echo $result;
	}
}
