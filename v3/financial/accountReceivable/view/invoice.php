<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet"  type="text/css" href="css/smartadmin-production.css" />
<?php
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {  
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot."v3/financial/accountReceivable/controller/invoiceController.php"); 
 require_once($newFakeDocumentRoot."v3/financial/accountReceivable/controller/invoiceDetailController.php"); 
 require_once($newFakeDocumentRoot."v3/financial/accountReceivable/controller/invoiceTransactionController.php"); 
 require_once($newFakeDocumentRoot."v3/financial/accountReceivable/controller/invoiceFollowUpController.php"); 
 require_once($newFakeDocumentRoot."v3/financial/accountReceivable/controller/invoiceAttachmentController.php"); 
 require_once ($newFakeDocumentRoot."library/class/classNavigation.php");  
 require_once ($newFakeDocumentRoot."library/class/classShared.php");  
 require_once ($newFakeDocumentRoot."library/class/classDate.php");  
 $dateConvert = new \Core\Date\DateClass();
$dateRangeStart = null;
if (isset($_POST['dateRangeStart'])) {
 $dateRangeStart = $_POST['dateRangeStart'];
 // some error handling to avoid error
 if (isset($_POST['dateRangeEnd'])) {
     if (strlen($_POST['dateRangeEnd']) > 0) {
         if (isset($_POST['dateRangeType'])) {
             if ($_POST['dateRangeType'] != 'between' && $_POST['dateRangeType'] != 'week') {
                 $dateRangeStart = date('d-m-Y');
                 $_POST['dateRangeStart'] = date('d-m-Y');
                 unset($_POST['dateRangeEnd']);
             }
         }
     }
 }
} else {
 $dateRangeStart = date('d-m-Y');
 }
 //day
 $previousDay   = $dateConvert->getPreviousDate($dateRangeStart, 'day');
 $nextDay       = $dateConvert->getForwardDate($dateRangeStart, 'day');
 //week
 $dateRangeStartPreviousWeek = $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous');
 $dateRangeStartPreviousWeekArray = explode(">", $dateRangeStartPreviousWeek);
 $dateRangeStartPreviousWeekStartDay = $dateRangeStartPreviousWeekArray[0];
 $dateRangeEndPreviousWeekEndDay = $dateRangeStartPreviousWeekArray[1];
 $dateRangeStartWeek = $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current');
 $dateRangeStartWeekArray = explode(">", $dateRangeStartWeek);
 $dateRangeStartDay = $dateRangeStartWeekArray[0];
 $dateRangeEndDay = $dateRangeStartWeekArray[1];
 $dateRangeEndForwardWeek = $dateConvert->getCurrentWeekInfo($dateRangeStart, 'forward');
 $dateRangeEndForwardWeekArray = explode(">", $dateRangeEndForwardWeek);
 $dateRangeEndForwardWeekStartDay = $dateRangeEndForwardWeekArray[0];
 $dateRangeEndForwardWeekEndDay = $dateRangeEndForwardWeekArray[1];
//month
 $previousMonth   = $dateConvert->getPreviousDate($dateRangeStart, 'month');
 $nextMonth       = $dateConvert->getForwardDate($dateRangeStart, 'month');
//year
 $previousYear   = $dateConvert->getPreviousDate($dateRangeStart, 'year');
 $nextYear       = $dateConvert->getForwardDate($dateRangeStart, 'year');
 $translator = new \Core\shared\SharedClass();   
 $template = new \Core\shared\SharedTemplate();  
 $translator->setCurrentDatabase('icore');   
$tableName[]='invoice';
$tableName[]='invoiceDetail';
$tableName[]='invoiceTransaction';
$tableName[]='invoiceFollowUp';
$tableName[]='invoiceAttachment';
 $translator->setCurrentTable($tableName); 
 $_POST['from']="invoice.php";
 $_GET['from']="invoice.php";
 if (isset($_POST['leafId'])) {
 	$leafId = intval($_POST['leafId'] * 1);
 } else if (isset($_GET['leafId'])) {
 	$leafId = intval($_GET['leafId'] * 1);
 } else {
 	// redirect to main page if no id
 	header("index.php");
 	exit();
 }
 if ($leafId === 0) {
 	// might injection.cut off
 	header("index.php");
 	exit();
 }
 $translator->setLeafId($leafId);
 $translator->execute();    
 $securityToken = $translator->getSecurityToken();
 $arrayInfo         =   $translator->getFileInfo(); 
 $applicationId     =   $arrayInfo['applicationId']; 
 $moduleId          =   $arrayInfo['moduleId']; 
 $folderId     =   $arrayInfo['folderId']; //future if required 
 $leafId          =   $arrayInfo['leafId']; 
 $applicationNative =   $arrayInfo['applicationNative']; 
 $folderNative      =   $arrayInfo['folderNative']; 
 $moduleNative      =   $arrayInfo['moduleNative']; 
 $leafNative        =   $arrayInfo['leafNative']; 
 $translator->createLeafBookmark('', '', '', $leafId); 
 $systemFormat = $translator->getSystemFormat();   
 $t = $translator->getDefaultTranslation(); // short because code too long  
 $leafTranslation = $translator->getLeafTranslation(); 
 $leafAccess = $translator->getLeafAccess(); 
	$invoiceArray = array(); 
	$businessPartnerArray = array();
	$invoiceProjectArray = array();
	$paymentTermArray = array();
 if (isset($_POST)) {  
    if (isset($_POST['method'])) {  
        $invoice = new \Core\Financial\AccountReceivable\Invoice\Controller\InvoiceClass();  
     define('LIMIT',10);
     if (isset($_POST['offset'])) {  
         $offset = $_POST['offset'];  
     } else {
         $offset = 0;  
     }
     if (isset($_POST['limit'])) {  
         $limit = $_POST['limit'];  
     } else {
         $limit = LIMIT;  
     }
     if (isset($_POST ['query'])) { 
         $invoice->setFieldQuery($_POST ['query']); 
     } 
     if (isset($_POST ['filter'])) { 
         $invoice->setGridQuery($_POST ['filter']); 
     }                 
     if (isset($_POST ['character'])) { 
         $invoice->setCharacterQuery($_POST['character']); 
     } 
     if (isset($_POST ['dateRangeStart'])) { 
         $invoice->setDateRangeStartQuery($_POST['dateRangeStart']); 
         //explode the data to get day,month,year 
         $start=explode('-',$_POST ['dateRangeStart']); 
         $invoice->setStartDay($start[2]); 
         $invoice->setStartMonth($start[1]); 
         $invoice->setStartYear($start[0]); 
     } 
     if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd'])> 0) ) { 
         $invoice->setDateRangeEndQuery($_POST['dateRangeEnd']); 
         //explode the data to get day,month,year 
         $start=explode('-',$_POST ['dateRangeEnd']); 
         $invoice->setEndDay($start[2]); 
         $invoice->setEndMonth($start[1]); 
         $invoice->setEndYear($start[0]); 
     } 
     if (isset($_POST ['dateRangeType'])) { 
         $invoice->setDateRangeTypeQuery($_POST['dateRangeType']); 
     } 
     if (isset($_POST ['dateRangeExtraType'])) { 
         $invoice->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']); 
     } 
     $invoice->setServiceOutput('html');  
     $invoice->setLeafId($leafId);  
     $invoice->execute();  
     $businessPartnerArray = $invoice->getBusinessPartner();
     $invoiceProjectArray = $invoice->getInvoiceProject();
     $paymentTermArray = $invoice->getPaymentTerm();
     if ($_POST['method'] == 'read') {  
         $invoice->setStart($offset);  
         $invoice->setLimit($limit); // normal system don't like paging..  
         $invoice->setPageOutput('html');  
         $invoiceArray = $invoice->read();  
         if (isset($invoiceArray [0]['firstRecord'])) {  
         	$firstRecord = $invoiceArray [0]['firstRecord'];  
         }  
         if (isset($invoiceArray [0]['nextRecord'])) {  
         	$nextRecord = $invoiceArray [0]['nextRecord'];  
         }   
         if (isset($invoiceArray [0]['previousRecord'])) {  
             $previousRecord = $invoiceArray [0]['previousRecord'];  
         }   
         if (isset($invoiceArray [0]['lastRecord'])) {  
             $lastRecord = $invoiceArray [0]['lastRecord'];  
         	$endRecord = $invoiceArray [0]['lastRecord'];  
         }   
         $navigation = new \Core\Paging\HtmlPaging();  
         $navigation->setLeafId($leafId);  
         $navigation->setViewPath($invoice->getViewPath());  
         $navigation->setOffset($offset);  
         $navigation->setLimit($limit);  
         $navigation->setSecurityToken($securityToken);  
         $navigation->setLoadingText($t['loadingTextLabel']);  
         $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);  
         $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);  
         if (isset($invoiceArray [0]['total'])) {  
         	$total = $invoiceArray [0]['total'];  
         } else {  
         	$total = 0;  
         }  
            $navigation->setTotalRecord($total);  
        }  
    }  
 }  
?><script type="text/javascript">
 var t =<?php echo json_encode($translator->getDefaultTranslation()); ?>;
 var leafTranslation =<?php echo json_encode($translator->getLeafTranslation()); ?>;
 </script><?php 
 if(isset($_POST['method']) && isset($_POST['type'])) {
  	if ($_POST['method'] == 'read' && $_POST['type'] == 'list') { ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
         <?php   $template->setLayout(1);
                 echo $template->breadcrumb($applicationNative, $moduleNative, $folderNative, $leafNative,$securityToken,$applicationId,$moduleId,$folderId,$leafId); ?>
		</div>
 	</div>
 	<div id="infoErrorRowFluid" class="row hidden">
		<div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
	</div>
 <div id="content" style="opacity: 1;">
 <div class="row">
     <div align="left" class="btn-group col-xs-10 col-sm-10 col-md-10 pull-left"> 
      <button title="A" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','A');">A</button> 
      <button title="B" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','B');">B</button> 
      <button title="C" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','C');">C</button> 
      <button title="D" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','D');">D</button> 
      <button title="E" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','E');">E</button> 
      <button title="F" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','F');">F</button> 
      <button title="G" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','G');">G</button> 
      <button title="H" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','H');">H</button> 
      <button title="I" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','I');">I</button> 
      <button title="J" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','J');">J</button> 
      <button title="K" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','K');">K</button> 
      <button title="L" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','L');">L</button> 
      <button title="M" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','M');">M</button> 
      <button title="N" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','N');">N</button> 
      <button title="O" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','O');">O</button> 
      <button title="P" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','P');">P</button> 
      <button title="Q" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','Q');">Q</button> 
      <button title="R" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','R');">R</button> 
      <button title="S" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','S');">S</button> 
      <button title="T" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','T');">T</button> 
      <button title="U" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','U');">U</button> 
      <button title="V" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','V');">V</button> 
      <button title="W" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','W');">W</button> 
      <button title="X" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','X');">X</button> 
      <button title="Y" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','Y');">Y</button> 
      <button title="Z" class="btn btn-success" type="button" onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','Z');">Z</button> 
 </div>
 <div class="col-xs-2 col-sm-2 col-md-2">
     <div align="right" class="pull-right">
         <div class="btn-group">
             <button class="btn btn-warning" type="button">
                 <i class="glyphicon glyphicon-print glyphicon-white"></i>
             </button>
             <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button">
                 <span class="caret"></span>
             </button>
			 <?php if($leafAccess['leafAccessPrintValue']==1) { ?>
             <ul class="dropdown-menu">
                 <li>
                     <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $securityToken; ?>','excel')">
                         <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                     </a>
                 </li>
                 <li>
                     <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $securityToken; ?>','csv')">
                         <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                     </a>
                 </li>
					</ul>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
	</div>
	<div class="row">
     <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3"> 
		<div class="panel panel-default">
			<div class="panel-body">
				<div id="btnList">
				<?php  if($leafAccess['leafAccessCreateValue']==1) { ?>
						<button type="button" name="newRecordButton" id="newRecordButton" class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>','<?php   echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button> 
				 <?php } ?>
					</div>
					<label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php if(isset($_POST['query'])) {  echo $_POST['query']; } ?>"><span class="input-group-addon">
<img id="searchTextImage" src="./images/icons/magnifier.png">
</span>
</div>
<br>					<button type="button" name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
     				<button type="button" name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','0','<?php echo LIMIT; ?>',1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
     				<table class="table table table-striped table-condensed table-hover">
         				<tr>
             				<td>&nbsp;</td>
             				<td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
             				<td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','01-01-1979','<?php echo  date('d-m-Y'); ?>','between','')"><?php  echo $t['anyTimeTextLabel']; ?></a></td>
             				<td>&nbsp;</td>         				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $previousDay; ?>','','day','next')">&laquo;</a></td>
             				<td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStart; ?>','','day','')"><?php  echo $t['todayTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $nextDay; ?>','','day','next')">&raquo;</a></td>
         				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart,'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStartPreviousWeekStartDay ; ?>','<?php echo $dateRangeEndPreviousWeekEndDay ; ?>','week','previous')">&laquo;</a> </td>
             				<td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart,'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStartDay; ?>','<?php echo $dateRangeEndDay; ?>','week','')"><?php  echo $t['weekTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart,'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeEndForwardWeekStartDay ; ?>','<?php echo $dateRangeEndForwardWeekEndDay ; ?>','week','next')">&raquo;</a></td>
        				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $previousMonth; ?>','','month','previous')">&laquo;</a></td> 
             				<td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStart; ?>','','month','')"><?php  echo $t['monthTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $nextMonth; ?>','','month','next')">&raquo;</a></td>
         				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $previousYear; ?>','','year','previous')">&laquo;</a></td> 
             				<td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStart; ?>','','year','')"><?php  echo $t['yearTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $nextYear; ?>','','year','next')">&raquo;</a></td>
         				</tr>
					</table>
         				<div class="input-group"><input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php if(isset($_POST['dateRangeStart'])) { echo $_POST['dateRangeStart']; } ?>" onClick="topPage(125)"  placeholder="<?php echo $t['startDateTextLabel']; ?>"><span class="input-group-addon">
<img id="startDateImage" src="./images/icons/calendar.png">
</span>
</div><br>
         				<div class="input-group"><input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php if(isset($_POST['dateRangeEnd'])) { echo $_POST['dateRangeEnd']; } ?>" onClick="topPage(175)" placeholder="<?php echo $t['endDateTextLabel']; ?>"><span class="input-group-addon">
<img id="endDateImage" src="./images/icons/calendar.png">
</span>
</div><br>
						<button type="button" name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
						<button type="button" name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',0,<?php echo LIMIT; ?>,1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
			</div>
		</div>
	</div>
 <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
	<div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
					<h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="invoiceIdPreview" id="invoiceIdPreview">
					<div class="form-group" id="invoiceIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceIdPreview"><?php echo $leafTranslation['invoiceIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="invoiceIdPreview" id="invoiceIdPreview">
         			</div>					</div>					<div class="form-group" id="businessPartnerIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
         			</div>					</div>					<div class="form-group" id="invoiceProjectIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceProjectIdPreview"><?php echo $leafTranslation['invoiceProjectIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="invoiceProjectIdPreview" id="invoiceProjectIdPreview">
         			</div>					</div>					<div class="form-group" id="paymentTermIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="paymentTermIdPreview"><?php echo $leafTranslation['paymentTermIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="paymentTermIdPreview" id="paymentTermIdPreview">
         			</div>					</div>     		</div> 
     		<div class="modal-footer"> 
                 <?php  if($leafAccess['leafAccessDeleteValue']==1) { ?>
         		<button type="button" class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
				<?php } ?>
         		<button type="button" class="btn btn-default" onClick="showMeModal('deletePreview',0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
     		</div> 
 		</div> 
     </div> 
 </div> 
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12">
     <div class="panel panel-default"><table class ="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
         <thead> 
             <tr> 
                 <th width="25px" align="center"><div align="center">#</div></th>
                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                  <th width="100px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th> 
                  <th width="100px"><?php echo ucwords($leafTranslation['invoiceProjectIdLabel']); ?></th> 
                  <th width="100px"><?php echo ucwords($leafTranslation['paymentTermIdLabel']); ?></th> 
                 <th width="25px"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onClick="toggleChecked(this.checked)"></th>
             </tr> 
         </thead> 
         <tbody id="tableBody"> 
             <?php   if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') { 
                 if (is_array($invoiceArray)) { 
                     $totalRecord = intval(count($invoiceArray)); 
                     if ($totalRecord > 0) { 
                         $counter=0; 
                         for ($i = 0; $i < $totalRecord; $i++) { 
                             $counter++; ?>
                            	<tr <?php 
                                           if ($invoiceArray[$i]['isDelete'] == 1) { 
                                                echo "class=\"danger\""; 
                                            } else { 
                                                if ($invoiceArray[$i]['isDraft'] == 1) { 
                                                    echo "class=\"warning\""; 
                                               } 
                                           } 
                                            ?>>
                             	<td vAlign="top" align="center"><div align="center"><?php echo ($counter+$offset); ?>.</div></td>                       	<td vAlign="top" align="center"><div class="btn-group" align="center">
<?php if($leafAccess['leafAccessUpdateValue']==1) { ?>
                                 <button type="button" class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo intval($invoiceArray [$i]['invoiceId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);" value="Edit"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
<?php }
if($leafAccess['leafAccessDeleteValue']==1) { ?>
                                 <button type="button" class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete(}
'<?php echo rawurlencode($invoiceArray [$i]['invoiceId']); ?>','<?php echo rawurlencode($invoiceArray [$i]['businessPartnerCompany']); ?>','<?php echo rawurlencode($invoiceArray [$i]['invoiceProjectDescription']); ?>','<?php echo rawurlencode($invoiceArray [$i]['paymentTermDescription']); ?>')" value="Delete"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button><?php } ?></div></td> 
                                    <td vAlign="top"><div align="left">
<?php  if(isset($invoiceArray[$i]['businessPartnerCompany'])) { 
           if(isset($_POST['query']) ||  isset($_POST['character'])){ 
				if(isset($_POST['query']) && strlen($_POST['query'])>0) {
               	if(strpos($invoiceArray[$i]['businessPartnerCompany'],$_POST['query']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$invoiceArray[$i]['businessPartnerCompany']);
					}else {
                   	echo $invoiceArray[$i]['businessPartnerCompany']; 
					}
               } else if (isset($_POST['character']) && strlen($_POST['character'])>0) { 
					if(strpos($invoiceArray[$i]['businessPartnerCompany'],$_POST['character']) !== false){
                   	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$invoiceArray[$i]['businessPartnerCompany']);
					}else{
                   	echo $invoiceArray[$i]['businessPartnerCompany']; 
					}
               }else{
                   echo $invoiceArray[$i]['businessPartnerCompany']; 
				}
           } else {
                echo $invoiceArray[$i]['businessPartnerCompany']; 
           } ?>
           </div>
 <?php } else {  ?>
                                     &nbsp;
 <?php } ?>
 </td>
                                    <td vAlign="top"><div align="left">
<?php  if(isset($invoiceArray[$i]['invoiceProjectDescription'])) { 
           if(isset($_POST['query']) ||  isset($_POST['character'])){ 
				if(isset($_POST['query']) && strlen($_POST['query'])>0) {
               	if(strpos($invoiceArray[$i]['invoiceProjectDescription'],$_POST['query']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$invoiceArray[$i]['invoiceProjectDescription']);
					}else {
                   	echo $invoiceArray[$i]['invoiceProjectDescription']; 
					}
               } else if (isset($_POST['character']) && strlen($_POST['character'])>0) { 
					if(strpos($invoiceArray[$i]['invoiceProjectDescription'],$_POST['character']) !== false){
                   	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$invoiceArray[$i]['invoiceProjectDescription']);
					}else{
                   	echo $invoiceArray[$i]['invoiceProjectDescription']; 
					}
               }else{
                   echo $invoiceArray[$i]['invoiceProjectDescription']; 
				}
           } else {
                echo $invoiceArray[$i]['invoiceProjectDescription']; 
           } ?>
           </div>
 <?php } else {  ?>
                                     &nbsp;
 <?php } ?>
 </td>
                                    <td vAlign="top"><div align="left">
<?php  if(isset($invoiceArray[$i]['paymentTermDescription'])) { 
           if(isset($_POST['query']) ||  isset($_POST['character'])){ 
				if(isset($_POST['query']) && strlen($_POST['query'])>0) {
               	if(strpos($invoiceArray[$i]['paymentTermDescription'],$_POST['query']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$invoiceArray[$i]['paymentTermDescription']);
					}else {
                   	echo $invoiceArray[$i]['paymentTermDescription']; 
					}
               } else if (isset($_POST['character']) && strlen($_POST['character'])>0) { 
					if(strpos($invoiceArray[$i]['paymentTermDescription'],$_POST['character']) !== false){
                   	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$invoiceArray[$i]['paymentTermDescription']);
					}else{
                   	echo $invoiceArray[$i]['paymentTermDescription']; 
					}
               }else{
                   echo $invoiceArray[$i]['paymentTermDescription']; 
				}
           } else {
                echo $invoiceArray[$i]['paymentTermDescription']; 
           } ?>
           </div>
 <?php } else {  ?>
                                     &nbsp;
 <?php } ?>
 </td>
                             <?php if($invoiceArray[$i]['isDelete']) {
                                 $checked="checked";
                             } else {
                                 $checked=NULL;
                             }  ?>
                            <td vAlign="top">
    <input class="form-control" style="display:none;" type="checkbox" name="invoiceId[]"  value="<?php echo $invoiceArray[$i]['invoiceId']; ?>">
    <?php  if($leafAccess['leafAccessDeleteValue']==1) { ?>
	<input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $invoiceArray[$i]['isDelete']; ?>">
    <?php } ?>
</td>
                    	</tr> 
                  <?php } 
 } else {  ?>
                    <tr> 
                        <td colspan="7" vAlign="top" align="center"><?php $invoice->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                    </tr> 
         <?php    }
 }  else { ?> 
                    <tr> 
                        <td colspan="7" vAlign="top" align="center"><?php $invoice->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                    </tr> 
                    <?php 
                } 
            } else { ?> 
                <tr> 
                    <td colspan="7" vAlign="top" align="center"><?php $invoice->exceptionMessage($t['loadFailureLabel']); ?></td> 
                </tr> 
                <?php 
            } 
          ?> 
             </tbody> 
         </table></div>
     </div>
 </div>
 <div class="row">
     <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
         <?php $navigation->pagenationv4($offset); ?>
     </div>
     <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
        <?php  if($leafAccess['leafAccessDeleteValue']==1) { ?>
		<button type="button" class="delete btn btn-warning" onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>')"> 
			<i class="glyphicon glyphicon-white glyphicon-trash"></i> 
		</button> 
        <?php } ?>
     </div>
 </div> 
  <script type="text/javascript"> 
     $(document).ready(function(){ 
         $(document).scrollTop(0);
         $('#dateRangeStart').datepicker({  
             format :'<?php echo  $systemFormat['systemSettingDateFormat']; ?>'  
         }).on('changeDate', function () {
             $(this).datepicker('hide');
         });  
         $('#dateRangeEnd').datepicker({  
             format :'<?php echo  $systemFormat['systemSettingDateFormat']; ?>'  
         }).on('changeDate', function () {
             $(this).datepicker('hide');
         });   
     }); 
     function toggleChecked(status) {
         $('input:checkbox').each( function() {
             $(this).attr('checked',status);
         });
     } 
 </script> 
 </div>
</div>
</div>
    <?php } }  
 if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') { ?> 
<?php
$invoiceDetail = new \Core\Financial\AccountReceivable\InvoiceDetail\Controller\InvoiceDetailClass();  
 $invoiceDetail->setServiceOutput('html');  
 $invoiceDetail->setLeafId($leafId);  
 $invoiceDetail->execute();  
 $invoiceDetail->setStart(0);  
 $invoiceDetail->setLimit(999999);
 $invoiceDetail->setPageOutput('html');  
if(isset($_POST['invoiceId'])) { 
 $invoiceDetailArray = $invoiceDetail->read();  
 }
 $productArray = $invoiceDetail->getProduct();
 $unitOfMeasurementArray = $invoiceDetail->getUnitOfMeasurement();
 $discountArray = $invoiceDetail->getDiscount();
 $taxArray = $invoiceDetail->getTax();
$invoiceTransaction = new \Core\Financial\AccountReceivable\InvoiceTransaction\Controller\InvoiceTransactionClass();  
 $invoiceTransaction->setServiceOutput('html');  
 $invoiceTransaction->setLeafId($leafId);  
 $invoiceTransaction->execute();  
 $invoiceTransaction->setStart(0);  
 $invoiceTransaction->setLimit(999999);
 $invoiceTransaction->setPageOutput('html');  
if(isset($_POST['invoiceId'])) { 
 $invoiceTransactionArray = $invoiceTransaction->read();  
 }
 $countryArray = $invoiceTransaction->getCountry();
 $chartOfAccountArray = $invoiceTransaction->getChartOfAccount();
$invoiceFollowUp = new \Core\Financial\AccountReceivable\InvoiceFollowUp\Controller\InvoiceFollowUpClass();  
 $invoiceFollowUp->setServiceOutput('html');  
 $invoiceFollowUp->setLeafId($leafId);  
 $invoiceFollowUp->execute();  
 $invoiceFollowUp->setStart(0);  
 $invoiceFollowUp->setLimit(999999);
 $invoiceFollowUp->setPageOutput('html');  
if(isset($_POST['invoiceId'])) { 
 $invoiceFollowUpArray = $invoiceFollowUp->read();  
 }
 $followUpArray = $invoiceFollowUp->getFollowUp();
$invoiceAttachment = new \Core\Financial\AccountReceivable\InvoiceAttachment\Controller\InvoiceAttachmentClass();  
 $invoiceAttachment->setServiceOutput('html');  
 $invoiceAttachment->setLeafId($leafId);  
 $invoiceAttachment->execute();  
 $invoiceAttachment->setStart(0);  
 $invoiceAttachment->setLimit(999999);
 $invoiceAttachment->setPageOutput('html');  
if(isset($_POST['invoiceId'])) { 
 $invoiceAttachmentArray = $invoiceAttachment->read();  
 }
 ?>
<form class="form-horizontal">
 <input type="hidden" name="invoiceId" id="invoiceId" value="<?php if (isset($_POST['invoiceId'])) {
			echo $_POST['invoiceId'];    
		}  ?>"> 
 <div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
   <?php $template->setLayout(2); 
    echo	$template->breadcrumb($applicationNative, $moduleNative, $folderNative, $leafNative,$securityToken,$applicationId,$moduleId,$folderId,$leafId);   ?> 
   </div> 
   </div> 
   <div id="infoErrorRowFluid" class="row hidden">
    <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
   </div>
 	<div id="content" style="opacity: 1;">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="pull-left">
                         <div class="btn-group" align="left">
                          <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
                          <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class=caret></span></a> 
                          <ul class="dropdown-menu"> 
                           <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php  echo $t['newContinueButtonLabel']; ?></a> </li> 
                           <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php  echo $t['newUpdateButtonLabel']; ?></a> </li> 
                           <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php  echo $t['newListingButtonLabel']; ?> </a></li> 
                          </ul> 
                         </div> 
                         <div class="btn-group" align="left">
                          <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php  echo $t['updateButtonLabel']; ?> </a> 
                          <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a> 
                          <ul class="dropdown-menu"> 
                           <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php  echo $t['updateButtonLabel']; ?></a> </li> 
                           <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php  echo $t['updateListingButtonLabel']; ?></a> </li> 
                          </ul> 
                         </div> 
                        <div class="btn-group">
                          <button type="button" id="deleteRecordButton" class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button> 
                         </div> 
                         <div class="btn-group">
                           <button type="button" id="resetRecordButton" class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
                          </div> 
                          <div class="btn-group">
                           <button type="button" id="postRecordButton" class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button> 
                           </div> 
                          <div class="btn-group">
                           <button type="button" id="listRecordButton" class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',0,<?php echo LIMIT; ?>,1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
                          </div> 
                        </div>
						<div align="right">
                          <div class="btn-group">
                           <button type="button" id="firstRecordButton" class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>',<?php echo $securityToken; ?>','<?php echo $leafAccess['leafAccessUpdateValue']; ?>','<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
							</div> 
							<div class="btn-group">
								<button type="button" id="previousRecordButton" class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
							</div>
							<div class="btn-group">
								<button type="button" id="nextRecordButton" class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
        					</div>
							<div class="btn-group">
								<button type="button" id="endRecordButton" class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>','<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $leafAccess['leafAccessUpdateValue']; ?>','<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
							</div>
						</div>
					</div>
					<div class="panel-body">
	<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
<header>
<ul id="myTab" class="nav nav-tabs pull-right in tab-content">
<li class="active"><a href="#invoice"  data-toggle="tab">invoice</a></li>



<li><a href="#invoiceDetail"  data-toggle="tab">invoiceDetail</a></li>



<li><a href="#invoiceTransaction"  data-toggle="tab">invoiceTransaction</a></li>



<li><a href="#invoiceFollowUp"  data-toggle="tab">invoiceFollowUp</a></li>



<li><a href="#invoiceAttachment"  data-toggle="tab">invoiceAttachment</a></li>
</ul>
</header>



<div class="tab-content">


<div class="tab-pane active" id="invoice">
   <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
 </div>
</div>




<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="businessPartnerIdForm">
 <label class="control-label col-xs-4 col-sm-4 col-md-4" for="businessPartnerId"><strong><?php echo ucfirst($leafTranslation['businessPartnerIdLabel']); ?></strong></label>
 <div class="col-xs-8 col-sm-8 col-md-8">
  <select name="businessPartnerId" id="businessPartnerId" class="form-control  chzn-select">
   <option value=""></option>
   <?php	if (is_array($businessPartnerArray)) {
   $totalRecord = intval(count($businessPartnerArray));
   if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($invoiceArray[0]['businessPartnerId'])) {
														if($invoiceArray[0]['businessPartnerId']==$businessPartnerArray[$i]['businessPartnerId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
 <option value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $businessPartnerArray[$i]['businessPartnerCompany']; ?></option> 
 <?php               $d++;
   }
    }   else { ?>
    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
   <?php		}
   } else { ?>
   <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
  <?php } ?>
  </select>
  <span class="help-block" id="businessPartnerIdHelpMe"></span>
 </div>
</div>


<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceProjectIdForm">
 <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceProjectId"><strong><?php echo ucfirst($leafTranslation['invoiceProjectIdLabel']); ?></strong></label>
 <div class="col-xs-8 col-sm-8 col-md-8">
  <select name="invoiceProjectId" id="invoiceProjectId" class="form-control  chzn-select">
   <option value=""></option>
   <?php	if (is_array($invoiceProjectArray)) {
   $totalRecord = intval(count($invoiceProjectArray));
   if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($invoiceArray[0]['invoiceProjectId'])) {
														if($invoiceArray[0]['invoiceProjectId']==$invoiceProjectArray[$i]['invoiceProjectId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
 <option value="<?php echo $invoiceProjectArray[$i]['invoiceProjectId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $invoiceProjectArray[$i]['invoiceProjectDescription']; ?></option> 
 <?php               $d++;
   }
    }   else { ?>
    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
   <?php		}
   } else { ?>
   <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
  <?php } ?>
  </select>
  <span class="help-block" id="invoiceProjectIdHelpMe"></span>
 </div>
</div>
 </div>
</div>




<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="paymentTermIdForm">
 <label class="control-label col-xs-4 col-sm-4 col-md-4" for="paymentTermId"><strong><?php echo ucfirst($leafTranslation['paymentTermIdLabel']); ?></strong></label>
 <div class="col-xs-8 col-sm-8 col-md-8">
  <select name="paymentTermId" id="paymentTermId" class="form-control  chzn-select">
   <option value=""></option>
   <?php	if (is_array($paymentTermArray)) {
   $totalRecord = intval(count($paymentTermArray));
   if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($invoiceArray[0]['paymentTermId'])) {
														if($invoiceArray[0]['paymentTermId']==$paymentTermArray[$i]['paymentTermId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
 <option value="<?php echo $paymentTermArray[$i]['paymentTermId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $paymentTermArray[$i]['paymentTermDescription']; ?></option> 
 <?php               $d++;
   }
    }   else { ?>
    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
   <?php		}
   } else { ?>
   <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
  <?php } ?>
  </select>
  <span class="help-block" id="paymentTermIdHelpMe"></span>
 </div>
</div>
  </div>
 </div>
</div>



<div class="tab-pane fade" id="invoiceDetail">
 <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
  <thead>
   <tr>
     <th><?php echo ucwords($leafTranslation['productIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['unitOfMeasurementIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['discountIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['taxIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailQuantityLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailDescriptionLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailPriceLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailDiscountLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailTaxLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailTotalPriceLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailStartDateLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceDetailEndDateLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['isRule78Label']); ?></th>
     <th><?php echo ucwords($leafTranslation['isRecurringLabel']); ?></th>
 </tr>
</thead>

<tbody>
 <tr>
 <td vAlign="top" align="left">
	<select name="productId[]" id="productId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($productArray)) {
          $totalRecord = intval(count($productArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($productId_0==$productArray[$i]['productId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $productArray[$i]['productDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="unitOfMeasurementId[]" id="unitOfMeasurementId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($unitOfMeasurementArray)) {
          $totalRecord = intval(count($unitOfMeasurementArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($unitOfMeasurementId_0==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="discountId[]" id="discountId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($discountArray)) {
          $totalRecord = intval(count($discountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($discountId_0==$discountArray[$i]['discountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $discountArray[$i]['discountId']; ?>" <?php echo $selected; ?>><?php echo $discountArray[$i]['discountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="taxId[]" id="taxId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($taxArray)) {
          $totalRecord = intval(count($taxArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($taxId_0==$taxArray[$i]['taxId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $taxArray[$i]['taxId']; ?>" <?php echo $selected; ?>><?php echo $taxArray[$i]['taxDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity_0" value="<?php echo $invoiceDetailQuantity_0; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription_0" value="<?php echo $invoiceDetailDescription_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice_0" value="<?php echo $invoiceDetailPrice_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount_0" value="<?php echo $invoiceDetailDiscount_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTax[]" id="invoiceDetailTax_0" value="<?php echo $invoiceDetailTax_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice_0" value="<?php echo $invoiceDetailTotalPrice_0; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailStartDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailStartDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailStartDate"  id="invoiceDetailStartDate"  value="<?php echo $value; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailEndDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailEndDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailEndDate"  id="invoiceDetailEndDate"  value="<?php echo $value; ?>"></td>
    <td vAlign="top" align="center"><input class="form-control" type="checkbox" name="isRecurring[]" id="isRecurring_0" value="<?php echo $isRecurring+0; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="productId[]" id="productId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($productArray)) {
          $totalRecord = intval(count($productArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($productId_1==$productArray[$i]['productId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $productArray[$i]['productDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="unitOfMeasurementId[]" id="unitOfMeasurementId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($unitOfMeasurementArray)) {
          $totalRecord = intval(count($unitOfMeasurementArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($unitOfMeasurementId_1==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="discountId[]" id="discountId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($discountArray)) {
          $totalRecord = intval(count($discountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($discountId_1==$discountArray[$i]['discountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $discountArray[$i]['discountId']; ?>" <?php echo $selected; ?>><?php echo $discountArray[$i]['discountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="taxId[]" id="taxId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($taxArray)) {
          $totalRecord = intval(count($taxArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($taxId_1==$taxArray[$i]['taxId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $taxArray[$i]['taxId']; ?>" <?php echo $selected; ?>><?php echo $taxArray[$i]['taxDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity_1" value="<?php echo $invoiceDetailQuantity_1; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription_1" value="<?php echo $invoiceDetailDescription_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice_1" value="<?php echo $invoiceDetailPrice_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount_1" value="<?php echo $invoiceDetailDiscount_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTax[]" id="invoiceDetailTax_1" value="<?php echo $invoiceDetailTax_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice_1" value="<?php echo $invoiceDetailTotalPrice_1; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailStartDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailStartDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailStartDate"  id="invoiceDetailStartDate"  value="<?php echo $value; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailEndDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailEndDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailEndDate"  id="invoiceDetailEndDate"  value="<?php echo $value; ?>"></td>
    <td vAlign="top" align="center"><input class="form-control" type="checkbox" name="isRecurring[]" id="isRecurring_1" value="<?php echo $isRecurring+1; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="productId[]" id="productId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($productArray)) {
          $totalRecord = intval(count($productArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($productId_2==$productArray[$i]['productId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $productArray[$i]['productDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="unitOfMeasurementId[]" id="unitOfMeasurementId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($unitOfMeasurementArray)) {
          $totalRecord = intval(count($unitOfMeasurementArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($unitOfMeasurementId_2==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="discountId[]" id="discountId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($discountArray)) {
          $totalRecord = intval(count($discountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($discountId_2==$discountArray[$i]['discountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $discountArray[$i]['discountId']; ?>" <?php echo $selected; ?>><?php echo $discountArray[$i]['discountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="taxId[]" id="taxId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($taxArray)) {
          $totalRecord = intval(count($taxArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($taxId_2==$taxArray[$i]['taxId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $taxArray[$i]['taxId']; ?>" <?php echo $selected; ?>><?php echo $taxArray[$i]['taxDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity_2" value="<?php echo $invoiceDetailQuantity_2; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription_2" value="<?php echo $invoiceDetailDescription_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice_2" value="<?php echo $invoiceDetailPrice_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount_2" value="<?php echo $invoiceDetailDiscount_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTax[]" id="invoiceDetailTax_2" value="<?php echo $invoiceDetailTax_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice_2" value="<?php echo $invoiceDetailTotalPrice_2; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailStartDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailStartDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailStartDate"  id="invoiceDetailStartDate"  value="<?php echo $value; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailEndDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailEndDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailEndDate"  id="invoiceDetailEndDate"  value="<?php echo $value; ?>"></td>
    <td vAlign="top" align="center"><input class="form-control" type="checkbox" name="isRecurring[]" id="isRecurring_2" value="<?php echo $isRecurring+2; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="productId[]" id="productId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($productArray)) {
          $totalRecord = intval(count($productArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($productId_3==$productArray[$i]['productId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $productArray[$i]['productDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="unitOfMeasurementId[]" id="unitOfMeasurementId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($unitOfMeasurementArray)) {
          $totalRecord = intval(count($unitOfMeasurementArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($unitOfMeasurementId_3==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="discountId[]" id="discountId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($discountArray)) {
          $totalRecord = intval(count($discountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($discountId_3==$discountArray[$i]['discountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $discountArray[$i]['discountId']; ?>" <?php echo $selected; ?>><?php echo $discountArray[$i]['discountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="taxId[]" id="taxId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($taxArray)) {
          $totalRecord = intval(count($taxArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($taxId_3==$taxArray[$i]['taxId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $taxArray[$i]['taxId']; ?>" <?php echo $selected; ?>><?php echo $taxArray[$i]['taxDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity_3" value="<?php echo $invoiceDetailQuantity_3; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription_3" value="<?php echo $invoiceDetailDescription_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice_3" value="<?php echo $invoiceDetailPrice_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount_3" value="<?php echo $invoiceDetailDiscount_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTax[]" id="invoiceDetailTax_3" value="<?php echo $invoiceDetailTax_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice_3" value="<?php echo $invoiceDetailTotalPrice_3; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailStartDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailStartDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailStartDate"  id="invoiceDetailStartDate"  value="<?php echo $value; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailEndDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailEndDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailEndDate"  id="invoiceDetailEndDate"  value="<?php echo $value; ?>"></td>
    <td vAlign="top" align="center"><input class="form-control" type="checkbox" name="isRecurring[]" id="isRecurring_3" value="<?php echo $isRecurring+3; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="productId[]" id="productId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($productArray)) {
          $totalRecord = intval(count($productArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($productId_4==$productArray[$i]['productId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $productArray[$i]['productDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="unitOfMeasurementId[]" id="unitOfMeasurementId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($unitOfMeasurementArray)) {
          $totalRecord = intval(count($unitOfMeasurementArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($unitOfMeasurementId_4==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="discountId[]" id="discountId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($discountArray)) {
          $totalRecord = intval(count($discountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($discountId_4==$discountArray[$i]['discountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $discountArray[$i]['discountId']; ?>" <?php echo $selected; ?>><?php echo $discountArray[$i]['discountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="taxId[]" id="taxId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($taxArray)) {
          $totalRecord = intval(count($taxArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($taxId_4==$taxArray[$i]['taxId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $taxArray[$i]['taxId']; ?>" <?php echo $selected; ?>><?php echo $taxArray[$i]['taxDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity_4" value="<?php echo $invoiceDetailQuantity_4; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription_4" value="<?php echo $invoiceDetailDescription_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice_4" value="<?php echo $invoiceDetailPrice_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount_4" value="<?php echo $invoiceDetailDiscount_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTax[]" id="invoiceDetailTax_4" value="<?php echo $invoiceDetailTax_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice_4" value="<?php echo $invoiceDetailTotalPrice_4; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailStartDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailStartDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailStartDate"  id="invoiceDetailStartDate"  value="<?php echo $value; ?>"></td>
<?php if(isset($Array[$i]['invoiceDetailEndDate'])) { 
 $valueArray = $Array[$i]['invoiceDetailEndDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceDetailEndDate"  id="invoiceDetailEndDate"  value="<?php echo $value; ?>"></td>
    <td vAlign="top" align="center"><input class="form-control" type="checkbox" name="isRecurring[]" id="isRecurring_4" value="<?php echo $isRecurring+4; ?>"></td>
    </tr>
  </tbody>
 </table></div>



<div class="tab-pane fade" id="invoiceTransaction">
 <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
  <thead>
   <tr>
     <th><?php echo ucwords($leafTranslation['countryIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['chartOfAccountIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceTransactionPrincipalAmountLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceTransactionInterestAmountLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceTransactionCoupunRateAmountLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceTransactionTaxAmountLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceTransactionAmountLabel']); ?></th>
 </tr>
</thead>

<tbody>
 <tr>
 <td vAlign="top" align="left">
	<select name="countryId[]" id="countryId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($countryArray)) {
          $totalRecord = intval(count($countryArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($countryId_0==$countryArray[$i]['countryId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $countryArray[$i]['countryDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="chartOfAccountId[]" id="chartOfAccountId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($chartOfAccountArray)) {
          $totalRecord = intval(count($chartOfAccountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($chartOfAccountId_0==$chartOfAccountArray[$i]['chartOfAccountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="journalNumber[]" id="journalNumber_0" value="<?php echo $journalNumber_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionPrincipalAmount[]" id="invoiceTransactionPrincipalAmount_0" value="<?php echo $invoiceTransactionPrincipalAmount_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionInterestAmount[]" id="invoiceTransactionInterestAmount_0" value="<?php echo $invoiceTransactionInterestAmount_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionCoupunRateAmount[]" id="invoiceTransactionCoupunRateAmount_0" value="<?php echo $invoiceTransactionCoupunRateAmount_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionTaxAmount[]" id="invoiceTransactionTaxAmount_0" value="<?php echo $invoiceTransactionTaxAmount_0; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionAmount[]" id="invoiceTransactionAmount_0" value="<?php echo $invoiceTransactionAmount_0; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="countryId[]" id="countryId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($countryArray)) {
          $totalRecord = intval(count($countryArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($countryId_1==$countryArray[$i]['countryId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $countryArray[$i]['countryDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="chartOfAccountId[]" id="chartOfAccountId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($chartOfAccountArray)) {
          $totalRecord = intval(count($chartOfAccountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($chartOfAccountId_1==$chartOfAccountArray[$i]['chartOfAccountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="journalNumber[]" id="journalNumber_1" value="<?php echo $journalNumber_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionPrincipalAmount[]" id="invoiceTransactionPrincipalAmount_1" value="<?php echo $invoiceTransactionPrincipalAmount_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionInterestAmount[]" id="invoiceTransactionInterestAmount_1" value="<?php echo $invoiceTransactionInterestAmount_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionCoupunRateAmount[]" id="invoiceTransactionCoupunRateAmount_1" value="<?php echo $invoiceTransactionCoupunRateAmount_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionTaxAmount[]" id="invoiceTransactionTaxAmount_1" value="<?php echo $invoiceTransactionTaxAmount_1; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionAmount[]" id="invoiceTransactionAmount_1" value="<?php echo $invoiceTransactionAmount_1; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="countryId[]" id="countryId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($countryArray)) {
          $totalRecord = intval(count($countryArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($countryId_2==$countryArray[$i]['countryId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $countryArray[$i]['countryDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="chartOfAccountId[]" id="chartOfAccountId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($chartOfAccountArray)) {
          $totalRecord = intval(count($chartOfAccountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($chartOfAccountId_2==$chartOfAccountArray[$i]['chartOfAccountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="journalNumber[]" id="journalNumber_2" value="<?php echo $journalNumber_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionPrincipalAmount[]" id="invoiceTransactionPrincipalAmount_2" value="<?php echo $invoiceTransactionPrincipalAmount_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionInterestAmount[]" id="invoiceTransactionInterestAmount_2" value="<?php echo $invoiceTransactionInterestAmount_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionCoupunRateAmount[]" id="invoiceTransactionCoupunRateAmount_2" value="<?php echo $invoiceTransactionCoupunRateAmount_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionTaxAmount[]" id="invoiceTransactionTaxAmount_2" value="<?php echo $invoiceTransactionTaxAmount_2; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionAmount[]" id="invoiceTransactionAmount_2" value="<?php echo $invoiceTransactionAmount_2; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="countryId[]" id="countryId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($countryArray)) {
          $totalRecord = intval(count($countryArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($countryId_3==$countryArray[$i]['countryId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $countryArray[$i]['countryDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="chartOfAccountId[]" id="chartOfAccountId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($chartOfAccountArray)) {
          $totalRecord = intval(count($chartOfAccountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($chartOfAccountId_3==$chartOfAccountArray[$i]['chartOfAccountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="journalNumber[]" id="journalNumber_3" value="<?php echo $journalNumber_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionPrincipalAmount[]" id="invoiceTransactionPrincipalAmount_3" value="<?php echo $invoiceTransactionPrincipalAmount_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionInterestAmount[]" id="invoiceTransactionInterestAmount_3" value="<?php echo $invoiceTransactionInterestAmount_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionCoupunRateAmount[]" id="invoiceTransactionCoupunRateAmount_3" value="<?php echo $invoiceTransactionCoupunRateAmount_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionTaxAmount[]" id="invoiceTransactionTaxAmount_3" value="<?php echo $invoiceTransactionTaxAmount_3; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionAmount[]" id="invoiceTransactionAmount_3" value="<?php echo $invoiceTransactionAmount_3; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="countryId[]" id="countryId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($countryArray)) {
          $totalRecord = intval(count($countryArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($countryId_4==$countryArray[$i]['countryId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $countryArray[$i]['countryDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
 <td vAlign="top" align="left">
	<select name="chartOfAccountId[]" id="chartOfAccountId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($chartOfAccountArray)) {
          $totalRecord = intval(count($chartOfAccountArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($chartOfAccountId_4==$chartOfAccountArray[$i]['chartOfAccountId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="journalNumber[]" id="journalNumber_4" value="<?php echo $journalNumber_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionPrincipalAmount[]" id="invoiceTransactionPrincipalAmount_4" value="<?php echo $invoiceTransactionPrincipalAmount_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionInterestAmount[]" id="invoiceTransactionInterestAmount_4" value="<?php echo $invoiceTransactionInterestAmount_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionCoupunRateAmount[]" id="invoiceTransactionCoupunRateAmount_4" value="<?php echo $invoiceTransactionCoupunRateAmount_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionTaxAmount[]" id="invoiceTransactionTaxAmount_4" value="<?php echo $invoiceTransactionTaxAmount_4; ?>"></td>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceTransactionAmount[]" id="invoiceTransactionAmount_4" value="<?php echo $invoiceTransactionAmount_4; ?>"></td>
    </tr>
  </tbody>
 </table></div>



<div class="tab-pane fade" id="invoiceFollowUp">
 <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
  <thead>
   <tr>
     <th><?php echo ucwords($leafTranslation['followUpIdLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceFollowUpDateLabel']); ?></th>
     <th><?php echo ucwords($leafTranslation['invoiceFollowUpDescriptionLabel']); ?></th>
 </tr>
</thead>

<tbody>
 <tr>
 <td vAlign="top" align="left">
	<select name="followUpId[]" id="followUpId_0" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($followUpArray)) {
          $totalRecord = intval(count($followUpArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($followUpId_0==$followUpArray[$i]['followUpId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $followUpArray[$i]['followUpId']; ?>" <?php echo $selected; ?>><?php echo $followUpArray[$i]['followUpDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<?php if(isset($Array[$i]['invoiceFollowUpDate'])) { 
 $valueArray = $Array[$i]['invoiceFollowUpDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDate"  id="invoiceFollowUpDate"  value="<?php echo $value; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDescription[]" id="invoiceFollowUpDescription_0" value="<?php echo $invoiceFollowUpDescription_0; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="followUpId[]" id="followUpId_1" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($followUpArray)) {
          $totalRecord = intval(count($followUpArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($followUpId_1==$followUpArray[$i]['followUpId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $followUpArray[$i]['followUpId']; ?>" <?php echo $selected; ?>><?php echo $followUpArray[$i]['followUpDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<?php if(isset($Array[$i]['invoiceFollowUpDate'])) { 
 $valueArray = $Array[$i]['invoiceFollowUpDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDate"  id="invoiceFollowUpDate"  value="<?php echo $value; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDescription[]" id="invoiceFollowUpDescription_1" value="<?php echo $invoiceFollowUpDescription_1; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="followUpId[]" id="followUpId_2" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($followUpArray)) {
          $totalRecord = intval(count($followUpArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($followUpId_2==$followUpArray[$i]['followUpId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $followUpArray[$i]['followUpId']; ?>" <?php echo $selected; ?>><?php echo $followUpArray[$i]['followUpDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<?php if(isset($Array[$i]['invoiceFollowUpDate'])) { 
 $valueArray = $Array[$i]['invoiceFollowUpDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDate"  id="invoiceFollowUpDate"  value="<?php echo $value; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDescription[]" id="invoiceFollowUpDescription_2" value="<?php echo $invoiceFollowUpDescription_2; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="followUpId[]" id="followUpId_3" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($followUpArray)) {
          $totalRecord = intval(count($followUpArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($followUpId_3==$followUpArray[$i]['followUpId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $followUpArray[$i]['followUpId']; ?>" <?php echo $selected; ?>><?php echo $followUpArray[$i]['followUpDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<?php if(isset($Array[$i]['invoiceFollowUpDate'])) { 
 $valueArray = $Array[$i]['invoiceFollowUpDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDate"  id="invoiceFollowUpDate"  value="<?php echo $value; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDescription[]" id="invoiceFollowUpDescription_3" value="<?php echo $invoiceFollowUpDescription_3; ?>"></td>
    </tr>
 <tr>
 <td vAlign="top" align="left">
	<select name="followUpId[]" id="followUpId_4" class="chzn-select form-control">
          <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
          <?php if (is_array($followUpArray)) {
          $totalRecord = intval(count($followUpArray));
          if($totalRecord > 0 ){ 
          for ($i = 0; $i < $totalRecord; $i++) {
              if($followUpId_4==$followUpArray[$i]['followUpId']){
           $selected='selected';
          } else {
            $selected=NULL;
          } ?>
 <option value="<?php echo $followUpArray[$i]['followUpId']; ?>" <?php echo $selected; ?>><?php echo $followUpArray[$i]['followUpDescription']; ?></option>
          <?php }
     }   else { ?>
		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
       <?php }     } else { ?>
	<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>";
<?php } ?>"
</select></td>
<?php if(isset($Array[$i]['invoiceFollowUpDate'])) { 
 $valueArray = $Array[$i]['invoiceFollowUpDate'];
 $valueData = explode('-', $valueArray);
 $year = $valueData[0];
 $month = $valueData[1]; 
 $day = $valueData[2];
 $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
 } else { $value=null; }  ?>
 <td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDate"  id="invoiceFollowUpDate"  value="<?php echo $value; ?>"></td>
<td vAlign="top" align="center"><input class="form-control" type="text" name="invoiceFollowUpDescription[]" id="invoiceFollowUpDescription_4" value="<?php echo $invoiceFollowUpDescription_4; ?>"></td>
    </tr>
  </tbody>
 </table></div>



<div class="tab-pane fade" id="invoiceAttachment">
 <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
  <thead>
   <tr>
 </tr>
</thead>

<tbody>
 <tr>
    </tr>
 <tr>
    </tr>
 <tr>
    </tr>
 <tr>
    </tr>
 <tr>
    </tr>
  </tbody>
 </table></div>
 	  </ul>
      <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php if(isset($firstRecord)) { echo intval($firstRecord); } ?>"> 
      <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php if(isset($nextRecord)) { echo intval($nextRecord); } ?>"> 
      <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php if(isset($previousRecord)) { echo intval($previousRecord); } ?>"> 
      <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php if(isset($lastRecord)) { echo intval($lastRecord); } ?>"> 
      <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php if(isset($endRecord)) { echo intval($endRecord); } ?>"> 
     </div>
    </div>
   </div>
  </div>
 </div>
</form>



<script type="text/javascript"> 
 $(document).ready(function(){  
  $('#myTab a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
  })
 $(document).scrollTop(0);
 $(".chzn-select").chosen({ search_contains: true });
 $(".chzn-select-deselect").chosen({allow_single_deselect:true});
 validateMeNumeric('invoiceId'); 
 validateMeNumeric('businessPartnerId'); 
 validateMeNumeric('invoiceProjectId'); 
 validateMeNumeric('paymentTermId'); 
 <?php if($_POST['method']=="new") { ?> 
  $('#resetRecordButton').removeClass().addClass('btn btn-info'); 
 <?php if($leafAccess['leafAccessCreateValue']==1) { ?> 
   $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1)"); 
   $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success'); 
   $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1)"); 
   $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',2)"); 
   $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',3)"); 
   $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',4)"); 
   $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',5)"); 
 <?php } else { ?>
             $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', ''); 
             $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled'); 
         <?php } ?>             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
             $('#updateRecordButton1').attr('onClick', ''); 
             $('#updateRecordButton2').attr('onClick', ''); 
             $('#updateRecordButton3').attr('onClick', ''); 
             $('#updateRecordButton4').attr('onClick', ''); 
             $('#updateRecordButton5').attr('onClick', ''); 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', ''); 
             $('#firstRecordButton').removeClass().addClass('btn btn-default');  
             $('#endRecordButton').removeClass().addClass('btn btn-default'); 
 <?php } else  if ($_POST['invoiceId']) { ?> 
             $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', ''); 
             $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled'); 
             $('#newRecordButton3').attr('onClick', ''); 
             $('#newRecordButton4').attr('onClick', ''); 
             $('#newRecordButton5').attr('onClick', ''); 
             $('#newRecordButton6').attr('onClick', ''); 
             $('#newRecordButton7').attr('onClick', ''); 
 <?php if($leafAccess['leafAccessUpdateValue']==1) { ?> 
             $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
	;
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info'); 
             $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
 <?php }  else { ?> 
             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', ''); 
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled'); 
             $('#updateRecordButton3').attr('onClick', ''); 
             $('#updateRecordButton4').attr('onClick', ''); 
             $('#updateRecordButton5').attr('onClick', ''); 
 <?php } ?> 
 <?php if($leafAccess['leafAccessDeleteValue']==1) { ?> 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php  echo $invoice->getViewPath(); ?>','<?php  echo $securityToken; ?>',<?php  echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
 <?php }  else { ?> 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', ''); 
  <?php } ?>  
 <?php } ?>  
         }); 
    </script> 
<?php } ?> 
<script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoice.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>