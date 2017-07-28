<?php
namespace Mq\Controller;
use Think\Controller;

class BottleController extends Controller {

	public function _initialize() {
		putHeader();
	}
	
	/**
	 * 查询客户列表.
	 */
	public function findbottlemain() {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$name = $_REQUEST['name_search'];
		$mobile = $_REQUEST['mobile_search'];
		$membertype = $_REQUEST['membertype_search'];
		if (!empty($name)) {
			$query_sql = $query_sql . " and m.realname like '%$name%'";
			$countquery_sql = $countquery_sql . " and m.realname like '%$name%'";
		}
		if (!empty($mobile)) {
			$query_sql = $query_sql . " and m.mobile LIKE '%$mobile%'";
			$countquery_sql = $countquery_sql . " and m.mobile LIKE '%$mobile%'";
		}
		if (!empty($membertype)) {
			$query_sql = $query_sql . " and m.membertype = $membertype";
			$countquery_sql = $countquery_sql . " and m.membertype = $membertype";
		}
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		$count_sql = "select count(*) as totalrecord from memberinfo as m where 1=1 $countquery_sql order by m.regtime desc";
		$condition_sql = "select * from memberinfo as m where 1=1 $query_sql order by regtime desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnbottle = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openBottle('".$result[$i]['pkid']."','".$result[$i]['realname']."','".$result[$i]['mobile']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;钢瓶设置&nbsp;</a>";					
			if($result[$i]['membertype'] == 1){
				$membertype = "居民用户";
			}else if($result[$i]['membertype'] == 2){
				$membertype = "小工商";
			}else if($result[$i]['membertype'] == 3){
				$membertype = "大工商";
			}
			$records["aaData"][] = array($result[$i]['realname'],$result[$i]['mobile'],$membertype,$btnbottle);
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

	/**
	 * 查询客户钢瓶明细列表.
	 */
	public function findBottle($memberid) {
		$query = new \Think\Model();
		$condition_sql = "";
		$count_sql = "";
		$query_sql = "";
		$countquery_sql = "";
		$optdate = $_REQUEST['optdate_search'];
		$pname = $_REQUEST['pname_search'];
		$fname = $_REQUEST['fname_search'];
		$type = $_REQUEST['type_search'];
		if (!empty($optdate) && ($optdate == date('Y-m-d',strtotime($optdate))) ) {
			$optdate = strtotime($optdate);
			$query_sql = $query_sql . " and optdate = $optdate";
			$countquery_sql = $countquery_sql . " and optdate = $optdate";
		}
		if (!empty($pname)) {
			$query_sql = $query_sql . " and pname like '%$pname%'";
			$countquery_sql = $countquery_sql . " and pname like '%$pname%'";
		}
		if (!empty($fname)) {
			$query_sql = $query_sql . " and fname like '%$fname%'";
			$countquery_sql = $countquery_sql . " and fname like '%$fname%'";
		}
		if (!empty($type)) {
			$query_sql = $query_sql . " and type = $type";
			$countquery_sql = $countquery_sql . " and type like $type";
		}
		
		$iDisplayLength = intval($_REQUEST['iDisplayLength']);
		$iDisplayStart = intval($_REQUEST['iDisplayStart']);
		
		
		$count_sql = "select count(*) as totalrecord from bottle as m where memberid='$memberid' $countquery_sql";
		$condition_sql = "select * from bottle where memberid='$memberid' $query_sql order by optdate desc limit $iDisplayStart,$iDisplayLength ";
		
		$resultcount = $query -> query($count_sql);
		$result = $query -> query($condition_sql);
		$iTotalRecords = $resultcount[0]['totalrecord'];
		$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
		$sEcho = intval($_REQUEST['sEcho']);
		$records = array();
		$records["aaData"] = array();
		$jsparams = "keyword:$orderid,buyername:$updowntag";
		
		for ($i = 0; $i < count($result); $i++) {
			$btnEdit = "<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openEdit('".$result[$i]['pkid']."','".$iDisplayStart."','".$jsparams."')\"><i class='fa fa-pencil'></i> &nbsp;编辑&nbsp;</a>";
			$btnDel = "&nbsp;&nbsp;<a class='btn btn-xs default'  data-toggle='modal' onclick=\"openDelConfirm('".$result[$i]['pkid']."','".$iDisplayStart."')\"><i class='fa fa-times'></i> &nbsp;删除&nbsp;</a>";			
			if(empty($result[$i]['optdate'])){
				$optdate = "";
			}else{
				$optdate = date('Y-m-d',$result[$i]['optdate']);
			}			
			$records["aaData"][] = array($optdate,$result[$i]['pname'],$result[$i]['fname'],getTypeStr($result[$i]['type']),$result[$i]['optnumber'],$btnEdit.$btnDel);
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
	
	/**
	 * 删除门店片区信息.
	 */
	public function delbottle($pkid) {
		$dao = M("Bottle");
		$dao->where("pkid='$pkid'")->delete();
		echo "yes";
	}
	
	public function savebottle(){
		$obj = getObjFromPost(array("receipt","pid","pname","jid","gpid","gpname","jname","rid","rname","optdate","optnumber","price","incash","outcash","changetype","type","fid","fname","departmentid","deparmentname","remark","memberid","membername"));		
		$obj['pkid'] = uniqid();
		$obj['userid'] = session("userid");
		$obj['username'] = session("name");
		$obj['optdate'] = time();
		$dao = M("Bottle");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editbottle(){
		$obj = getObjFromPost(array("pkid","receipt","pid","pname","jid","gpid","gpname","jname","rid","rname","optdate","optnumber","price","incash","outcash","changetype","type","fid","fname","departmentid","deparmentname","remark","memberid","membername"));
		$pkid = $obj['pkid'];
		$obj['userid'] = session("userid");
		$obj['username'] = session("name");
		$obj['optdate'] = time();
		$dao = M("Bottle");
		$dao->where("pkid='$pkid'")->save($obj);		
		echo "yes";
	}
	
	public function loadbottle($pkid){
		$dao = M("Bottle");
		$result = $dao->join("memberinfo on memberinfo.pkid = bottle.memberid","LEFT")->where("bottle.pkid = '$pkid'")->field("bottle.*,memberinfo.mobile")->find();
		header('Content-type: text/json');
		header('Content-type: application/json');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
	}
	
	public function analyse($departmentid,$startdate,$enddate){
		$query = new \Think\Model();
		$sdate = strtotime($startdate);
		$edate = strtotime($enddate);
		$condition_sql = "select d.`code` as dcode,m.code,m.status,m.realname,b.optdate,b.pname,b.jname,b.fname,b.rname,b.gpid,b.gpname,b.receipt,b.deparmentname,b.membername,b.changetype,b.optnumber,b.price,b.optnumber*b.price as sumprice,b.username,b.remark from bottle as b left join department as d on b.departmentid = d.pkid left join memberinfo as m ON
 m.pkid = b.memberid where b.optdate>=$sdate and b.optdate<=$edate and departmentid='$departmentid'";
		$result = $query -> query($condition_sql);		
		Vendor('PHPExcel.PHPExcel');
		$objPHPExcel = new \PHPExcel();  
		// Set properties    
   	    $objPHPExcel->getProperties()->setCreator("ctos")  
            ->setLastModifiedBy("ctos")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
            ->setKeywords("office 2007 openxml php")  
            ->setCategory("Test result file");  
		
		$typename="";
		$sheetcount=0;
		$rowcount=2;
		$objPHPExcel->setActiveSheetIndex($sheetcount)
					->setCellValue('A1','部门编码' )  
           			->setCellValue('B1', '客户编码')
           			->setCellValue('C1', '钢瓶编码') 
            			->setCellValue('D1', '类型')  
            			->setCellValue('E1', '实际日期')  
            			->setCellValue('F1', '单据编号')
					->setCellValue('G1', '发货地点')
					->setCellValue('H1', '客户名称')
					->setCellValue('I1', '变动形式')
					->setCellValue('J1', '钢瓶类型')
					->setCellValue('K1', '数量')
					->setCellValue('L1', '单价')
					->setCellValue('M1', '销售金额(含税)')
					->setCellValue('N1', '经办人')
					->setCellValue('O1', '备注');
				$objPHPExcel->getActiveSheet()->setTitle("瓶阀明细");
		for($i=0;$i<count($result);$i++){
			$_item = $result[$i];
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,$_item['dcode'])
						->setCellValue('B'.$rowcount,$_item['code'])
						->setCellValue('C'.$rowcount,gpcode($_item['pname'],$_item['gpname'],$_item['jname'],$_item['rname'],$_item['fname']))
						->setCellValue('D'.$rowcount,memberType($_item['status']))
						->setCellValue('E'.$rowcount,date('Y-m-d',$_item['optdate']))
						->setCellValue('F'.$rowcount,$_item['receipt'])
						->setCellValue('G'.$rowcount,$_item['deparmentname'])
						->setCellValue('H'.$rowcount,$_item['realname'])
						->setCellValue('I'.$rowcount,changetype($_item['changetype']))
						->setCellValue('J'.$rowcount,gpcodename($_item['pname'],$_item['gpname'],$_item['jname'],$_item['rname'],$_item['fname']))
						->setCellValue('K'.$rowcount,$_item['optnumber'])
						->setCellValue('M'.$rowcount,$_item['sumprice'])
						->setCellValue('L'.$rowcount,$_item['price'])
						->setCellValue('N'.$rowcount,$_item['username'])
						->setCellValue('O'.$rowcount,$_item['remark']);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
			$rowcount++;
		}
		
		header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="pfmx.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
	    $objWriter->save('php://output');
	}


	public function analysejpmx($departmentid,$startdate,$enddate){
		$query = new \Think\Model();
		$sdate = strtotime($startdate);
		$edate = strtotime($enddate);
		$condition_sql = "select a.optdate ,a.receipt,a.membername,
		(case when a.type=6 then a.optnumber when a.type=2 then a.optnumber*-1 when a.changetype=3 then a.optnumber when a.changetype=9 then a.optnumber*-1 else 0 end) as optnumber,
		 a.pname,a.jname,a.rname,a.fname,a.type,a.changetype ,a.gpid,a.gpname,
(select sum(case when b.type=6 then b.optnumber when b.type=2 then b.optnumber*-1 when b.changetype=3 then optnumber when b.changetype=9 then optnumber*-1 else 0 end) 
from bottle as b where b.memberid = a.memberid and b.pname = a.pname and b.jname = a.jname 
 and b.fname = a.fname and b.rname = a.rname and b.optdate<=a.optdate) as total
from bottle as a  where a.optdate>=$sdate and a.optdate<=$edate and a.departmentid='$departmentid' order by a.membername,a.pname,a.jname,a.rname,a.fname";
		$result = $query -> query($condition_sql);		
		Vendor('PHPExcel.PHPExcel');
		$objPHPExcel = new \PHPExcel();  
		// Set properties    
   	    $objPHPExcel->getProperties()->setCreator("ctos")  
            ->setLastModifiedBy("ctos")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
            ->setKeywords("office 2007 openxml php")  
            ->setCategory("Test result file");  
		
		$typename="";
		$sheetcount=0;
		$rowcount=2;
		$classifyname = "";
		for($i=0;$i<count($result);$i++){
			$_item = $result[$i];
			$classifynamenew = gpcodename($_item['pname'],$_item['gpname'],$_item['jname'],$_item['rname'],$_item['fname']);
			if($i==0){
				$classifyname = $classifynamenew;
				$objPHPExcel->setActiveSheetIndex($sheetcount)
				->setCellValue('A1','日期' )  
				->setCellValue('B1', '单据号')
				->setCellValue('C1', '客户名称') 
				->setCellValue('D1', '数量')  
				->setCellValue('E1', '累计');
				$objPHPExcel->getActiveSheet()->setTitle($classifyname);
				$sheetcount++;
			}
			if($classifyname!=$classifynamenew){
				$classifyname = $classifynamenew;
				$msgWorkSheet = new \PHPExcel_Worksheet($objPHPExcel, $classifyname); //创建一个工作表
       			$objPHPExcel->addSheet($msgWorkSheet); //插入工作表
       			$objPHPExcel->setActiveSheetIndex($sheetcount)
       				->setCellValue('A1','日期' )  
					->setCellValue('B1', '单据号')
					->setCellValue('C1', '客户名称') 
					->setCellValue('D1', '数量')  
					->setCellValue('E1', '累计');
				$sheetcount++;
				$rowcount=2;
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,date("Y-m-d",$_item['optdate']))
						->setCellValue('B'.$rowcount,$_item['receipt'])
						->setCellValue('C'.$rowcount,$_item['membername'])
						->setCellValue('D'.$rowcount,$_item['optnumber'])
						->setCellValue('E'.$rowcount,$_item['total']);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$rowcount++;
		}
		
		header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="jpmx.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
	    $objWriter->save('php://output');
	}
}
