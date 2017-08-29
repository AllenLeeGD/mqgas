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
			$records["aaData"][] = array($optdate,$result[$i]['pname'].$result[$i]['jname'].$result[$i]['rname'],$result[$i]['fname'],getTypeStr($result[$i]['type']),$result[$i]['optnumber'],$btnEdit.$btnDel);
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
		$obj = getObjFromPost(array("receipt","pid","pname","jid","gpid","gpname","jname","rid","rname","optdate","optnumber","price","changetype","type","fid","fname","departmentid","deparmentname","remark","memberid","membername"));		
		$obj['pkid'] = uniqid();
		$obj['userid'] = session("userid");
		$obj['username'] = session("name");
		$obj['optdate'] = time();
		$dao = M("Bottle");
		$dao->add($obj);		
		echo "yes";
	}
	
	public function editbottle(){
		$obj = getObjFromPost(array("pkid","receipt","pid","pname","jid","gpid","gpname","jname","rid","rname","optdate","optnumber","price","changetype","type","fid","fname","departmentid","deparmentname","remark","memberid","membername"));
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
//		$condition_sql = "select a.optdate ,a.receipt,a.membername,
//		(case when a.type=6 then a.optnumber when a.type=2 then a.optnumber*-1 when a.changetype=3 then a.optnumber when a.changetype=9 then a.optnumber*-1 else 0 end) as optnumber,
//		 a.pname,a.jname,a.rname,a.fname,a.type,a.changetype ,a.gpid,a.gpname,
//(select sum(case when b.type=6 then b.optnumber when b.type=2 then b.optnumber*-1 when b.changetype=3 then optnumber when b.changetype=9 then optnumber*-1 else 0 end) 
//from bottle as b where b.memberid = a.memberid and b.pname = a.pname and b.jname = a.jname 
// and b.fname = a.fname and b.rname = a.rname and b.optdate<=a.optdate) as total
//from bottle as a  where a.optdate>=$sdate and a.optdate<=$edate and a.departmentid='$departmentid' order by a.membername,a.pname,a.jname,a.rname,a.fname";
		$condition_sql = "select a.optdate ,a.receipt,a.membername,a.optnumber,
		 a.pname,a.jname,a.rname,a.fname,a.type,a.changetype ,a.gpid,a.gpname,(select sum(b.optnumber)  
from bottle as b where b.memberid = a.memberid and b.pname = a.pname and b.jname = a.jname 
 and b.fname = a.fname and b.rname = a.rname and b.optdate<=a.optdate) as total
from bottle as a  where a.optdate>=$sdate and a.optdate<=$edate and a.departmentid='$departmentid'
 and (a.type = 2 or a.type=6 or a.changetype=3 or a.changetype=9) order by a.membername,a.pname,a.jname,a.rname,a.fname";
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

	public function analysekcmx($departmentid,$startdate,$enddate){
		$query = new \Think\Model();
		$sdate = strtotime($startdate);
		$edate = strtotime($enddate);
		$condition_sql = "select sum(case when type=1 and optnumber>0 then optnumber when type=1 and optnumber<0 then optnumber*-1 else '' end)  as t1,
sum(case when type=2 and optnumber>0 then optnumber when type=2 and optnumber<0 then optnumber*-1 else '' end)  as t2,
sum(case when type=3 and optnumber>0 then optnumber when type=3 and optnumber<0 then optnumber*-1 else '' end)  as t3,
sum(case when type=4 and optnumber>0 then optnumber when type=4 and optnumber<0 then optnumber*-1 else '' end)  as t4,
sum(case when type=5 and optnumber>0 then optnumber when type=5 and optnumber<0 then optnumber*-1 else '' end)  as t5,
sum(case when type=6 and optnumber>0 then optnumber when type=6 and optnumber<0 then optnumber*-1 else '' end)  as t6,
sum(case when type=7 and optnumber>0 then optnumber when type=7 and optnumber<0 then optnumber*-1 else '' end)  as t7,
sum(case when type=8 and optnumber>0 then optnumber when type=8 and optnumber<0 then optnumber*-1 else '' end)  as t8,
sum(case when type=9 and optnumber>0 then optnumber when type=9 and optnumber<0 then optnumber*-1 else '' end)  as t9,
sum(case when type=10 and optnumber>0 then optnumber when type=10 and optnumber<0 then optnumber*-1 else '' end)  as t10,
 FROM_UNIXTIME(optdate,'%Y-%m-%d') as optdate,pname,jname,rname from bottle 
   where optdate>=$sdate and optdate<=$edate and departmentid='$departmentid' group by FROM_UNIXTIME(optdate,'%Y-%m-%d'),pname,rname,jname order by optdate";
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
		$optdate = "";		
		for($i=0;$i<count($result);$i++){
			$_item = $result[$i];
			$optdatenew = $_item['optdate'];
			if($i==0){
				$optdate = $optdatenew;
				$objPHPExcel->setActiveSheetIndex($sheetcount)
				->setCellValue('A1',$optdate )  
				->setCellValue('C1', '今日收瓶')
				->setCellValue('H1', '今日调出')
				->setCellValue('C'.$rowcount,"退户瓶")
				->setCellValue('D'.$rowcount,"还瓶")
				->setCellValue('E'.$rowcount,"回收杂瓶")
				->setCellValue('F'.$rowcount,"回流瓶")
				->setCellValue('G'.$rowcount,"入重瓶")
				->setCellValue('H'.$rowcount,"借出瓶")
				->setCellValue('I'.$rowcount,"押金瓶")
				->setCellValue('J'.$rowcount,"回收杂瓶")
				->setCellValue('K'.$rowcount,"回流瓶")
				->setCellValue('L'.$rowcount,"售重瓶")
				->setCellValue('A'.($rowcount+1),"50KG")
				->setCellValue('A'.($rowcount+3),"15KG")
				->setCellValue('A'.($rowcount+5),"5KG")
				->setCellValue('A'.($rowcount+6),"2KG")
				->setCellValue('B'.($rowcount+1),"气相")
				->setCellValue('B'.($rowcount+2),"液相")
				->setCellValue('B'.($rowcount+3),"直阀")
				->setCellValue('B'.($rowcount+4),"角阀");
				//合并单元格
				$objPHPExcel->getActiveSheet()->mergeCells('A1:B2');
				$objPHPExcel->getActiveSheet()->mergeCells('C1:G1');
				$objPHPExcel->getActiveSheet()->mergeCells('H1:L1');
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowcount+1).':A'.($rowcount+2));
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowcount+3).':A'.($rowcount+4));
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowcount+7).':L'.($rowcount+7));
				$objPHPExcel->getActiveSheet()->setTitle("库存明细");
				$rowcount = $rowcount-1;//调整格式
			}
			if($optdate!=$optdatenew){
				$rowcount = $rowcount+9;				
				$optdate = $optdatenew;
       			$objPHPExcel->setActiveSheetIndex($sheetcount)
				->setCellValue('A'.$rowcount,$optdate )  
				->setCellValue('C'.$rowcount, '今日收瓶')
				->setCellValue('H'.$rowcount, '今日调出')
				->setCellValue('C'.($rowcount+1),"退户瓶")
				->setCellValue('D'.($rowcount+1),"还瓶")
				->setCellValue('E'.($rowcount+1),"回收杂瓶")
				->setCellValue('F'.($rowcount+1),"回流瓶")
				->setCellValue('G'.($rowcount+1),"入重瓶")
				->setCellValue('H'.($rowcount+1),"借出瓶")
				->setCellValue('I'.($rowcount+1),"押金瓶")
				->setCellValue('J'.($rowcount+1),"回收杂瓶")
				->setCellValue('K'.($rowcount+1),"回流瓶")
				->setCellValue('L'.($rowcount+1),"售重瓶")
				->setCellValue('A'.($rowcount+2),"50KG")
				->setCellValue('A'.($rowcount+4),"15KG")
				->setCellValue('A'.($rowcount+6),"5KG")
				->setCellValue('A'.($rowcount+7),"2KG")
				->setCellValue('B'.($rowcount+2),"气相")
				->setCellValue('B'.($rowcount+3),"液相")
				->setCellValue('B'.($rowcount+4),"直阀")
				->setCellValue('B'.($rowcount+5),"角阀");
				//合并单元格
				$objPHPExcel->getActiveSheet()->mergeCells('A'.$rowcount.':B'.($rowcount+1));
				$objPHPExcel->getActiveSheet()->mergeCells('C'.$rowcount.':G'.$rowcount);
				$objPHPExcel->getActiveSheet()->mergeCells('H'.$rowcount.':L'.$rowcount);
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowcount+2).':A'.($rowcount+3));
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowcount+4).':A'.($rowcount+5));
				$objPHPExcel->getActiveSheet()->mergeCells('A'.($rowcount+8).':L'.($rowcount+8));			
			}
			
			if($_item['pname']=="50KG" && $_item['rname']=="气相" ){
				$temp_rowcount = $rowcount+1;
			}else if($_item['pname']=="50KG" && $_item['rname']=="液相" ){
				$temp_rowcount = $rowcount+2;
			}else if($_item['pname']=="15KG" && $_item['jname']=="直阀" ){
				$temp_rowcount = $rowcount+3;
			}else if($_item['pname']=="15KG" && $_item['jname']=="角阀" ){
				$temp_rowcount = $rowcount+4;
			}else if($_item['pname']=="5KG" ){
				$temp_rowcount = $rowcount+5;
			}else if($_item['pname']=="2KG" ){
				$temp_rowcount = $rowcount+6;
			}
			if($rowcount!=2){
				$temp_rowcount++;
			}
			$objPHPExcel->getActiveSheet()
						->setCellValue('C'.$temp_rowcount,emptyZero($_item['t1']))
						->setCellValue('D'.$temp_rowcount,emptyZero($_item['t2']))
						->setCellValue('E'.$temp_rowcount,emptyZero($_item['t3']))
						->setCellValue('F'.$temp_rowcount,emptyZero($_item['t4']))
						->setCellValue('G'.$temp_rowcount,emptyZero($_item['t5']))
						->setCellValue('H'.$temp_rowcount,emptyZero($_item['t6']))
						->setCellValue('I'.$temp_rowcount,emptyZero($_item['t7']))
						->setCellValue('J'.$temp_rowcount,emptyZero($_item['t8']))
						->setCellValue('K'.$temp_rowcount,emptyZero($_item['t9']))
						->setCellValue('L'.$temp_rowcount,emptyZero($_item['t10']));
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
		}
		
		header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="jpmx.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
	    $objWriter->save('php://output');
	}

	/**
	 * 进销存(气)报表，不计算空瓶回收,以下单时间为准计算日期
	 */
	public function jxcq($departmentid,$startdate,$enddate){
		$obj = getObjFromPost(array("qx","yx","zf","jf","twokg","fivekg"));
		$kc_qx = intval($obj["qx"]);
		$kc_yx = intval($obj["yx"]);
		$kc_zf = intval($obj["zf"]);
		$kc_jf = intval($obj["jf"]);
		$kc_twokg = intval($obj["twokg"]);
		$kc_fivekg = intval($obj["fivekg"]);
		$query = new \Think\Model();
		$sdate = strtotime($startdate);
		$edate = strtotime($enddate);
		$condition_sql = "select m.buytime,d.productname,d.productcount,d.productweight,m.buyer,j.mid from orderdetail as d 
 left join ordermain as m on d.orderid = m.pkid left join orderjm as j on j.orderid = d.orderid  where m.buytime>=$sdate and m.buytime<=$edate and (m.buyer='$departmentid' or j.mid='$departmentid' ) order by m.buytime";
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
		$rowcount=7;
		$objPHPExcel->setActiveSheetIndex($sheetcount)
					->setCellValue('A1','进销存(气)' )  ;
		$objPHPExcel->getActiveSheet()->setTitle("进销存(气)");
		$objPHPExcel->getActiveSheet()->mergeCells('A1:Y1');
		
		$objPHPExcel->getActiveSheet()
					->setCellValue('A2','日期' )  
           			->setCellValue('B2', '入库')
           			->setCellValue('J2', '销售') 
            			->setCellValue('R2', '库存');
		$objPHPExcel->getActiveSheet()->mergeCells('B2:I2');
		$objPHPExcel->getActiveSheet()->mergeCells('J2:Q2');
		$objPHPExcel->getActiveSheet()->mergeCells('R2:Y2');
		
		$objPHPExcel->getActiveSheet()
					->setCellValue('A3','规格' )  
           			->setCellValue('B3', '瓶数')
					->setCellValue('I3', '总重量(吨)')
           			->setCellValue('J3', '瓶数') 
					->setCellValue('Q3', '总重量(吨)')
            			->setCellValue('R3', '瓶数')
					->setCellValue('Y3', '总重量(吨)');
		$objPHPExcel->getActiveSheet()->mergeCells('B3:H3');
		$objPHPExcel->getActiveSheet()->mergeCells('J3:P3');
		$objPHPExcel->getActiveSheet()->mergeCells('R3:X3');
		
		$objPHPExcel->getActiveSheet()
					->setCellValue('B4','50KG' )  
           			->setCellValue('D4', '14.5KG')
					->setCellValue('J4', '50KG')
           			->setCellValue('L4', '14.5KG') 
					->setCellValue('R4', '50KG')
            			->setCellValue('T4', '14.5KG');
		$objPHPExcel->getActiveSheet()->mergeCells('B4:C4');
		$objPHPExcel->getActiveSheet()->mergeCells('D4:E4');
		$objPHPExcel->getActiveSheet()->mergeCells('J4:K4');
		$objPHPExcel->getActiveSheet()->mergeCells('L4:M4');
		$objPHPExcel->getActiveSheet()->mergeCells('R4:S4');
		$objPHPExcel->getActiveSheet()->mergeCells('T4:U4');
		
		$objPHPExcel->getActiveSheet()
					->setCellValue('B5','气相' )  
           			->setCellValue('C5', '液相')
					->setCellValue('D5', '直阀')
           			->setCellValue('E5', '角阀') 
					->setCellValue('F4', '5KG')
            			->setCellValue('G4', '15KG叉车瓶')
					->setCellValue('H4', '2KG')
					->setCellValue('J5', '气相')
					->setCellValue('K5', '液相')
					->setCellValue('L5', '直阀')
					->setCellValue('M5', '角阀')
					->setCellValue('N4', '5KG')
					->setCellValue('O4', '15KG叉车瓶')
					->setCellValue('P4', '2KG')
					->setCellValue('R5', '气相')
					->setCellValue('S5', '液相')
					->setCellValue('T5', '直阀')
					->setCellValue('U5', '角阀')
					->setCellValue('V4', '5KG')
					->setCellValue('W4', '15KG叉车瓶')
					->setCellValue('X4', '2KG');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:A5');
		$objPHPExcel->getActiveSheet()->mergeCells('I3:I5');
		$objPHPExcel->getActiveSheet()->mergeCells('Q3:Q5');
		$objPHPExcel->getActiveSheet()->mergeCells('Y3:Y5');
		$objPHPExcel->getActiveSheet()->mergeCells('F4:F5');
		$objPHPExcel->getActiveSheet()->mergeCells('G4:G5');
		$objPHPExcel->getActiveSheet()->mergeCells('H4:H5');
		$objPHPExcel->getActiveSheet()->mergeCells('N4:N5');
		$objPHPExcel->getActiveSheet()->mergeCells('O4:O5');
		$objPHPExcel->getActiveSheet()->mergeCells('P4:P5');
		$objPHPExcel->getActiveSheet()->mergeCells('V4:V5');
		$objPHPExcel->getActiveSheet()->mergeCells('W4:W5');
		$objPHPExcel->getActiveSheet()->mergeCells('X4:X5');
		
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$objPHPExcel->getActiveSheet()
					->setCellValue('A6','上月结余' )  
					->setCellValue('B6','0' )  
           			->setCellValue('C6','0')
					->setCellValue('D6', '0')
           			->setCellValue('E6', '0') 
					->setCellValue('F6', '0')
            			->setCellValue('G6', '0')
					->setCellValue('H6', '0')
					->setCellValue('I6', '-')
					->setCellValue('J6', '0')
					->setCellValue('K6', '0')
					->setCellValue('L6', '0')
					->setCellValue('M6', '0')
					->setCellValue('N6', '0')
					->setCellValue('O6', '0')
					->setCellValue('P6', '0')
					->setCellValue('Q6', '-')
					->setCellValue('R6', $kc_qx)
					->setCellValue('S6', $kc_yx)
					->setCellValue('U6', $kc_jf)
					->setCellValue('T6', $kc_zf)
					->setCellValue('V6', $kc_fivekg)
					->setCellValue('W6', '0')
					->setCellValue('X6', $kc_twokg)
					->setCellValue('Y6', computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
		$date_str;$r_qx;$r_yx;$r_zf;$r_jf;$r_five;$r_two;$r_z;$x_qx;$x_yx;$x_zf;$x_jf;$x_five;$x_two;$x_z;
		for($i=0;$i<count($result);$i++){
			$_item = $result[$i];
			$itemDate = date('Y-m-d',$_item['buytime']);
			if(empty($date_str)){//第一条记录,直接填入				
				$date_str = $itemDate;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,$date_str);
				if($_item["buyer"]==$departmentid){//入库
					if(strrpos($_item["productname"],"50KG气相")!==false){
						$r_qx = $_item["productcount"];
						$kc_qx = intval($r_qx)+$kc_qx;
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowcount,intval($r_qx));
						
					}else if(strrpos($_item["productname"],"50KG液相")!==false){
						$r_yx = $_item["productcount"];
						$kc_yx = intval($r_yx)+$kc_yx;
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowcount,intval($r_yx));
						
					}else if(strrpos($_item["productname"],"15KG角阀")!==false){
						$r_jf = $_item["productcount"];
						$kc_jf = intval($r_jf)+$kc_jf;
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowcount,intval($r_jf));
						
					}else if(strrpos($_item["productname"],"15KG直阀")!==false){
						$r_zf = $_item["productcount"];
						$kc_zf = intval($r_zf)+$kc_zf;
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowcount,intval($r_zf));
						
					}else if(strrpos($_item["productname"],"5KG")!==false){
						$r_five = $_item["productcount"];
						$kc_fivekg = intval($r_five)+$kc_fivekg;
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowcount,intval($r_five));
						
					}else if(strrpos($_item["productname"],"2KG")!==false){
						$r_two = $_item["productcount"];
						$kc_twokg = intval($r_two)+$kc_twokg;
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowcount,intval($r_two));
						
					}
					
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,intval($kc_qx));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,intval($kc_yx));
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,intval($kc_jf));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,intval($kc_zf));
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,intval($kc_fivekg));
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$rowcount,intval($kc_twokg));
					$r_z = computeWeight($r_qx,$r_yx,$r_zf,$r_jf,$r_two,$r_five);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowcount,$r_z);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowcount,computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
				}else if($_item["mid"]==$departmentid){//销售
					if(strrpos($_item["productname"],"50KG气相")!==false){
						$x_qx = $_item["productcount"];
						$kc_qx = $kc_qx-intval($x_qx);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,intval($x_qx));
					}else if(strrpos($_item["productname"],"50KG液相")!==false){
						$x_yx = $_item["productcount"];
						$kc_yx = $kc_yx-intval($x_yx);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowcount,intval($x_yx));
					}else if(strrpos($_item["productname"],"15KG角阀")!==false){
						$x_jf = $_item["productcount"];
						$kc_jf = $kc_jf-intval($x_jf);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$rowcount,intval($x_jf));
					}else if(strrpos($_item["productname"],"15KG直阀")!==false){
						$x_zf = $_item["productcount"];
						$kc_zf = $kc_zf-intval($r_zf);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$rowcount,intval($x_zf));
					}else if(strrpos($_item["productname"],"5KG")!==false){
						$x_five = $_item["productcount"];
						$kc_fivekg = $kc_fivekg-intval($x_five);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$rowcount,intval($x_five));
					}else if(strrpos($_item["productname"],"2KG")!==false){
						$x_two = $_item["productcount"];
						$kc_twokg = $kc_twokg - intval($x_two);
						$objPHPExcel->getActiveSheet()->setCellValue('P'.$rowcount,intval($x_two));
					}
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,intval($kc_qx));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,intval($kc_yx));
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,intval($kc_jf));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,intval($kc_zf));
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,intval($kc_fivekg));
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$rowcount,intval($kc_twokg));
					$x_z = computeWeight($x_qx,$x_yx,$x_zf,$x_jf,$x_two,$x_five);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowcount,$x_z);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowcount,computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
				}
			}else if($date_str==$itemDate){//同一天的其他交易记录，进行累加计算
				if($_item["buyer"]==$departmentid){//入库
					if(strrpos($_item["productname"],"50KG气相")!==false){
						$r_qx = intval($_item["productcount"])+intval($r_qx);
						$kc_qx = intval($r_qx)+$kc_qx;
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowcount,intval($r_qx));
					}else if(strrpos($_item["productname"],"50KG液相")!==false){
						$r_yx = intval($_item["productcount"])+intval($r_yx);
						$kc_yx = intval($r_yx)+$kc_yx;
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowcount,intval($r_yx));
					}else if(strrpos($_item["productname"],"15KG角阀")!==false){
						$r_jf = intval($_item["productcount"])+intval($r_jf);
						$kc_jf = intval($r_jf)+$kc_jf;
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowcount,intval($r_jf));
					}else if(strrpos($_item["productname"],"15KG直阀")!==false){
						$r_zf = intval($_item["productcount"])+intval($r_zf);
						$kc_zf = intval($r_zf)+$kc_zf;
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowcount,intval($r_zf));
					}else if(strrpos($_item["productname"],"5KG")!==false){
						$r_five = intval($_item["productcount"])+intval($r_five);
						$kc_fivekg = intval($r_five)+$kc_fivekg;
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowcount,intval($r_five));
					}else if(strrpos($_item["productname"],"2KG")!==false){
						$r_two = intval($_item["productcount"])+intval($r_two);
						$kc_twokg = intval($r_two)+$kc_twokg;
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowcount,intval($r_two));
					}
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,intval($kc_qx));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,intval($kc_yx));
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,intval($kc_jf));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,intval($kc_zf));
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,intval($kc_fivekg));
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$rowcount,intval($kc_twokg));
					$r_z = computeWeight($r_qx,$r_yx,$r_zf,$r_jf,$r_two,$r_five);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowcount,$r_z);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowcount,computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
				}else if($_item["mid"]==$departmentid){//销售
					if(strrpos($_item["productname"],"50KG气相")!==false){
						$kc_qx = $kc_qx-intval($_item["productcount"]);
						$x_qx = intval($_item["productcount"])+intval($x_qx);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,$x_qx);
					}else if(strrpos($_item["productname"],"50KG液相")!==false){
						$kc_yx = $kc_yx-intval($_item["productcount"]);
						$x_yx = intval($_item["productcount"])+intval($x_yx);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowcount,$x_yx);
					}else if(strrpos($_item["productname"],"15KG角阀")!==false){
						$kc_jf = $kc_jf-intval($_item["productcount"]);
						$x_jf = intval($_item["productcount"])+intval($x_jf);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$rowcount,$x_jf);
					}else if(strrpos($_item["productname"],"15KG直阀")!==false){
						$kc_zf = $kc_zf-intval($_item["productcount"]);
						$x_zf = intval($_item["productcount"])+intval($x_zf);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$rowcount,$x_zf);
					}else if(strrpos($_item["productname"],"5KG")!==false){
						$kc_fivekg = $kc_fivekg-intval($_item["productcount"]);
						$x_five = intval($_item["productcount"])+intval($x_five);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$rowcount,$x_five);
					}else if(strrpos($_item["productname"],"2KG")!==false){
						$kc_twokg = $kc_twokg-intval($_item["productcount"]);
						$x_two = intval($_item["productcount"])+intval($x_two);
						$objPHPExcel->getActiveSheet()->setCellValue('P'.$rowcount,$x_two);
					}
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,intval($kc_qx));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,intval($kc_yx));
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,intval($kc_jf));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,intval($kc_zf));
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,intval($kc_fivekg));
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$rowcount,intval($kc_twokg));
					$x_z = computeWeight($x_qx,$x_yx,$x_zf,$x_jf,$x_two,$x_five);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowcount,$x_z);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowcount,computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
				}
			}else if($date_str!=$itemDate){//下一天的交易记录,直接填入
				$rowcount++;
				//初始化所有变量
				$r_qx=0;$r_yx=0;$r_jf=0;$r_zf=0;$r_five=0;$r_two=0;$r_z=0;
				$x_qx=0;$x_yx=0;$x_jf=0;$x_zf=0;$x_five=0;$x_two=0;$x_z=0;
				$date_str = $itemDate;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowcount,$itemDate);
				if($_item["buyer"]==$departmentid){//入库
					if(strrpos($_item["productname"],"50KG气相")!==false){
						$r_qx = $_item["productcount"];
						$kc_qx = intval($r_qx)+$kc_qx;
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowcount,intval($r_qx));
					}else if(strrpos($_item["productname"],"50KG液相")!==false){
						$r_yx = $_item["productcount"];
						$kc_yx = intval($r_yx)+$kc_yx;
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowcount,intval($r_yx));
					}else if(strrpos($_item["productname"],"15KG角阀")!==false){
						$r_jf = $_item["productcount"];
						$kc_jf = intval($r_jf)+$kc_jf;
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowcount,intval($r_jf));
					}else if(strrpos($_item["productname"],"15KG直阀")!==false){
						$r_zf = $_item["productcount"];
						$kc_zf = intval($r_zf)+$kc_zf;
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowcount,intval($r_zf));
					}else if(strrpos($_item["productname"],"5KG")!==false){
						$r_five = $_item["productcount"];
						$kc_fivekg = intval($r_five)+$kc_fivekg;
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowcount,intval($r_five));
					}else if(strrpos($_item["productname"],"2KG")!==false){
						$r_two = $_item["productcount"];
						$kc_twokg = intval($r_two)+$kc_twokg;
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowcount,intval($r_two));
					}
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,intval($kc_qx));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,intval($kc_yx));
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,intval($kc_jf));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,intval($kc_zf));
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,intval($kc_fivekg));
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$rowcount,intval($kc_twokg));
					$r_z = computeWeight($r_qx,$r_yx,$r_zf,$r_jf,$r_two,$r_five);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowcount,$r_z);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowcount,computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
				}else if($_item["mid"]==$departmentid){//销售
					if(strrpos($_item["productname"],"50KG气相")!==false){
						$x_qx = $_item["productcount"];
						$kc_qx = $kc_qx-intval($x_qx);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowcount,intval($x_qx));
					}else if(strrpos($_item["productname"],"50KG液相")!==false){
						$x_yx = $_item["productcount"];
						$kc_yx = $kc_yx - intval($x_yx);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowcount,intval($x_yx));
					}else if(strrpos($_item["productname"],"15KG角阀")!==false){
						$x_jf = $_item["productcount"];
						$kc_jf = $kc_jf - intval($x_jf);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$rowcount,intval($x_jf));
					}else if(strrpos($_item["productname"],"15KG直阀")!==false){
						$x_zf = $_item["productcount"];
						$kc_zf = $kc_zf - intval($x_zf);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$rowcount,intval($x_zf));
					}else if(strrpos($_item["productname"],"5KG")!==false){
						$x_five = $_item["productcount"];
						$kc_fivekg = $kc_fivekg - intval($x_five);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$rowcount,intval($x_five));
					}else if(strrpos($_item["productname"],"2KG")!==false){
						$x_two = $_item["productcount"];
						$kc_twokg = $kc_twokg - intval($x_two);
						$objPHPExcel->getActiveSheet()->setCellValue('P'.$rowcount,intval($x_two));
					}
					$objPHPExcel->getActiveSheet()->setCellValue('R'.$rowcount,intval($kc_qx));
					$objPHPExcel->getActiveSheet()->setCellValue('S'.$rowcount,intval($kc_yx));
					$objPHPExcel->getActiveSheet()->setCellValue('U'.$rowcount,intval($kc_jf));
					$objPHPExcel->getActiveSheet()->setCellValue('T'.$rowcount,intval($kc_zf));
					$objPHPExcel->getActiveSheet()->setCellValue('V'.$rowcount,intval($kc_fivekg));
					$objPHPExcel->getActiveSheet()->setCellValue('X'.$rowcount,intval($kc_twokg));
					$x_z = computeWeight($x_qx,$x_yx,$x_zf,$x_jf,$x_two,$x_five);
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowcount,$x_z);
					$objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowcount,computeWeight($kc_qx,$kc_yx,$kc_jf,$kc_zf,$kc_twokg,$kc_fivekg));
				}
			}
		}
		
		header('Content-Type: application/vnd.ms-excel');  
	    header('Content-Disposition: attachment;filename="pfmx.xls"');  
	    header('Cache-Control: max-age=0');  
	  
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
	    $objWriter->save('php://output');
	}

}
