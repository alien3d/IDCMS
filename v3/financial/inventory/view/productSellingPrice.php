  

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
require_once($newFakeDocumentRoot."v3/financial/inventory/controller/productSellingPriceController.php"); 
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
 $translator->setCurrentTable('productSellingPrice'); 
 if (isset($_POST['leafId'])) {
 	$leafId = @intval($_POST['leafId'] * 1);
 } else if (isset($_GET['leafId'])) {
 	$leafId = @intval($_GET['leafId'] * 1);
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
	$productSellingPriceArray = array(); 
	$productArray = array();
	$countryArray = array();
	$stateArray = array();
	$unitOfMeasurementArray = array();
 if (isset($_POST)) {  
    if (isset($_POST['method'])) {  
        $productSellingPrice = new \Core\Financial\Inventory\ProductSellingPrice\Controller\ProductSellingPriceClass();  
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
         $productSellingPrice->setFieldQuery($_POST ['query']); 
     } 
     if (isset($_POST ['filter'])) { 
         $productSellingPrice->setGridQuery($_POST ['filter']); 
     }                 
     if (isset($_POST ['character'])) { 
         $productSellingPrice->setCharacterQuery($_POST['character']); 
     } 
     if (isset($_POST ['dateRangeStart'])) { 
         $productSellingPrice->setDateRangeStartQuery($_POST['dateRangeStart']); 
         //explode the data to get day,month,year 
         $start=explode('-',$_POST ['dateRangeStart']); 
         $productSellingPrice->setStartDay($start[2]); 
         $productSellingPrice->setStartMonth($start[1]); 
         $productSellingPrice->setStartYear($start[0]); 
     } 
     if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd'])> 0) ) { 
         $productSellingPrice->setDateRangeEndQuery($_POST['dateRangeEnd']); 
         //explode the data to get day,month,year 
         $start=explode('-',$_POST ['dateRangeEnd']); 
         $productSellingPrice->setEndDay($start[2]); 
         $productSellingPrice->setEndMonth($start[1]); 
         $productSellingPrice->setEndYear($start[0]); 
     } 
     if (isset($_POST ['dateRangeType'])) { 
         $productSellingPrice->setDateRangeTypeQuery($_POST['dateRangeType']); 
     } 
     if (isset($_POST ['dateRangeExtraType'])) { 
         $productSellingPrice->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']); 
     } 
     $productSellingPrice->setServiceOutput('html');  
     $productSellingPrice->setLeafId($leafId);  
     $productSellingPrice->execute();  
     $productArray = $productSellingPrice->getProduct();
     $countryArray = $productSellingPrice->getCountry();
     $stateArray = $productSellingPrice->getState();
     $unitOfMeasurementArray = $productSellingPrice->getUnitOfMeasurement();
     if ($_POST['method'] == 'read') {  
         $productSellingPrice->setStart($offset);  
         $productSellingPrice->setLimit($limit); // normal system don't like paging..  
         $productSellingPrice->setPageOutput('html');  
         $productSellingPriceArray = $productSellingPrice->read();  
         if (isset($productSellingPriceArray [0]['firstRecord'])) {  
         	$firstRecord = $productSellingPriceArray [0]['firstRecord'];  
         }  
         if (isset($productSellingPriceArray [0]['nextRecord'])) {  
         	$nextRecord = $productSellingPriceArray [0]['nextRecord'];  
         }   
         if (isset($productSellingPriceArray [0]['previousRecord'])) {  
             $previousRecord = $productSellingPriceArray [0]['previousRecord'];  
         }   
         if (isset($productSellingPriceArray [0]['lastRecord'])) {  
             $lastRecord = $productSellingPriceArray [0]['lastRecord'];  
         	$endRecord = $productSellingPriceArray [0]['lastRecord'];  
         }   
         $navigation = new \Core\Paging\HtmlPaging();  
         $navigation->setLeafId($leafId);  
         $navigation->setViewPath($productSellingPrice->getViewPath());  
         $navigation->setOffset($offset);  
         $navigation->setLimit($limit);  
         $navigation->setSecurityToken($securityToken);  
         $navigation->setLoadingText($t['loadingTextLabel']);  
         $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);  
         $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);  
         if (isset($productSellingPriceArray [0]['total'])) {  
         	$total = $productSellingPriceArray [0]['total'];  
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
      <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','A');">A</button> 
      <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','B');">B</button> 
      <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','C');">C</button> 
      <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','D');">D</button> 
      <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','E');">E</button> 
      <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','F');">F</button> 
      <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','G');">G</button> 
      <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','H');">H</button> 
      <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','I');">I</button> 
      <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','J');">J</button> 
      <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','K');">K</button> 
      <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','L');">L</button> 
      <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','M');">M</button> 
      <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','N');">N</button> 
      <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','O');">O</button> 
      <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','P');">P</button> 
      <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','Q');">Q</button> 
      <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','R');">R</button> 
      <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','S');">S</button> 
      <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','T');">T</button> 
      <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','U');">U</button> 
      <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','V');">V</button> 
      <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','W');">W</button> 
      <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','X');">X</button> 
      <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','Y');">Y</button> 
      <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','Z');">Z</button> 
 </div>
 <div class="col-xs-2 col-sm-2 col-md-2">
     <div align="left" class="pull-left">
         <div class="btn-group">
             <button class="btn btn-warning" type="button" >
                 <i class="glyphicon glyphicon-print glyphicon-white"></i>
             </button>
             <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button" >
                 <span class="caret"></span>
             </button>
             <ul class="dropdown-menu">
                 <li>
                     <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $securityToken; ?>','excel')">
                         <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                     </a>
                 </li>
                 <li>
                     <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $securityToken; ?>','csv')">
                         <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                     </a>
                 </li>
					</ul>
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
						<button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>','<?php   echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button> 
					</div>
					<label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php if(isset($_POST['query'])) {  echo $_POST['query']; } ?>"><span class="input-group-addon">
<img id="searchTextImage" src="./images/icons/magnifier.png">
</span>
</div>
<br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
     				<button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','0','<?php echo LIMIT; ?>',1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
     				<table class="table table table-striped table-condensed table-hover">
         				<tr>
             				<td>&nbsp;</td>
             				<td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
             				<td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','01-01-1979','<?php echo  date('d-m-Y'); ?>','between','')"><?php  echo $t['anyTimeTextLabel']; ?></a></td>
             				<td>&nbsp;</td>         				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $previousDay; ?>','','day','next')">&laquo;</a></td>
             				<td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStart; ?>','','day','')"><?php  echo $t['todayTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $nextDay; ?>','','day','next')">&raquo;</a></td>
         				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart,'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStartPreviousWeekStartDay ; ?>','<?php echo $dateRangeEndPreviousWeekEndDay ; ?>','week','previous')">&laquo;</a> </td>
             				<td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart,'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStartDay; ?>','<?php echo $dateRangeEndDay; ?>','week','')"><?php  echo $t['weekTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart,'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeEndForwardWeekStartDay ; ?>','<?php echo $dateRangeEndForwardWeekEndDay ; ?>','week','next')">&raquo;</a></td>
        				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $previousMonth; ?>','','month','previous')">&laquo;</a></td> 
             				<td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStart; ?>','','month','')"><?php  echo $t['monthTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $nextMonth; ?>','','month','next')">&raquo;</a></td>
         				</tr>
         				<tr>
             				<td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $previousYear; ?>','','year','previous')">&laquo;</a></td> 
             				<td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $dateRangeStart; ?>','','year','')"><?php  echo $t['yearTextLabel']; ?></a></td>
             				<td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $nextYear; ?>','','year','next')">&raquo;</a></td>
         				</tr>
					</table>
         				<div class="input-group"><input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php if(isset($_POST['dateRangeStart'])) { echo $_POST['dateRangeStart']; } ?>" onClick="topPage(125)"  placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><span class="input-group-addon">
<img id="startDateImage" src="./images/icons/calendar.png">
</span>
</div><br>
         				<div class="input-group"><input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php if(isset($_POST['dateRangeEnd'])) { echo $_POST['dateRangeEnd']; } ?>" onClick="topPage(175)" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>"><span class="input-group-addon">
<img id="endDateImage" src="./images/icons/calendar.png">
</span>
</div><br>
						<button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
						<button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>',0,<?php echo LIMIT; ?>,1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
			</div>
		</div>
	</div>
 <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
	<div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
					<h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="productSellingPriceIdPreview" id="productSellingPriceIdPreview">
					<div class="form-group" id="productIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productIdPreview"><?php echo $leafTranslation['productIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="productIdPreview" id="productIdPreview">
         			</div>					</div>					<div class="form-group" id="countryIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="countryIdPreview" id="countryIdPreview">
         			</div>					</div>					<div class="form-group" id="stateIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="stateIdPreview"><?php echo $leafTranslation['stateIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="stateIdPreview" id="stateIdPreview">
         			</div>					</div>					<div class="form-group" id="unitOfMeasurementIdDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="unitOfMeasurementIdPreview"><?php echo $leafTranslation['unitOfMeasurementIdLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="unitOfMeasurementIdPreview" id="unitOfMeasurementIdPreview">
         			</div>					</div>					<div class="form-group" id="productSellingPriceQuantityDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productSellingPriceQuantityPreview"><?php echo $leafTranslation['productSellingPriceQuantityLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="productSellingPriceQuantityPreview" id="productSellingPriceQuantityPreview">
         			</div>					</div>					<div class="form-group" id="productSellingPricePriceDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productSellingPricePricePreview"><?php echo $leafTranslation['productSellingPricePriceLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="productSellingPricePricePreview" id="productSellingPricePricePreview">
         			</div>					</div>					<div class="form-group" id="productSellingPriceStartDateDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productSellingPriceStartDatePreview"><?php echo $leafTranslation['productSellingPriceStartDateLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="productSellingPriceStartDatePreview" id="productSellingPriceStartDatePreview">
         			</div>					</div>					<div class="form-group" id="productSellingPriceEndDateDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productSellingPriceEndDatePreview"><?php echo $leafTranslation['productSellingPriceEndDateLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="productSellingPriceEndDatePreview" id="productSellingPriceEndDatePreview">
         			</div>					</div>     		</div> 
     		<div class="modal-footer"> 
         		<button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
         		<button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview',0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
     		</div> 
 		</div> 
     </div> 
 </div> 
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12">
     <table class ="table table-striped table-condensed table-hover" id="tableData"> 
         <thead> 
             <tr> 
                 <th width="25px" align="center"><div align="center">#</div></th>
                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                  <th width="125px"><?php echo ucwords($leafTranslation['productIdLabel']); ?></th> 
                  <th width="125px"><?php echo ucwords($leafTranslation['productSellingPricePriceLabel']); ?></th> 
                  <th width="125px"><?php echo ucwords($leafTranslation['productSellingPriceStartDateLabel']); ?></th> 
                  <th width="125px"><?php echo ucwords($leafTranslation['productSellingPriceEndDateLabel']); ?></th> 
                  <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                  <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                 <th width="25px"><label for="check_all"></label><input class="form-control" type="checkbox" name="check_all" id="check_all" alt="Check Record" onClick="toggleChecked(this.checked)"></th>
             </tr> 
         </thead> 
         <tbody id="tableBody"> 
             <?php   if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') { 
                 if (is_array($productSellingPriceArray)) { 
                     $totalRecord = intval(count($productSellingPriceArray)); 
                     if ($totalRecord > 0) { 
                         $counter=0; 
                         for ($i = 0; $i < $totalRecord; $i++) { 
                             $counter++; ?>
                            	<tr <?php 
                                           if ($productSellingPriceArray[$i]['isDelete'] == 1) { 
                                                echo "class=\"danger\""; 
                                            } else { 
                                                if ($productSellingPriceArray[$i]['isDraft'] == 1) { 
                                                    echo "class=\"warning\""; 
                                               } 
                                           } 
                                            ?>>
                             	<td vAlign="top" align="center"><div align="center"><?php echo ($counter+$offset); ?>.</div></td>                       	<td vAlign="top" align="center"><div class="btn-group" align="center">
                                 <button type="button"  class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo intval($productSellingPriceArray [$i]['productSellingPriceId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="Edit"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                 <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete('<?php echo rawurlencode($productSellingPriceArray [$i]['productSellingPriceId']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['productDescription']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['countryDescription']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['stateDescription']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['unitOfMeasurementDescription']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['productSellingPriceQuantity']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['productSellingPricePrice']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['productSellingPriceStartDate']); ?>','<?php echo rawurlencode($productSellingPriceArray [$i]['productSellingPriceEndDate']); ?>')" value="Delete"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button></div></td> 
                                    <td vAlign="top"><div align="left">
<?php  if(isset($productSellingPriceArray[$i]['productDescription'])) { 
           if(isset($_POST['query']) ||  isset($_POST['character'])){ 
				if(isset($_POST['query']) && strlen($_POST['query'])>0) {
               	if(strpos($productSellingPriceArray[$i]['productDescription'],$_POST['query']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$productSellingPriceArray[$i]['productDescription']);
					}else {
                   	echo $productSellingPriceArray[$i]['productDescription']; 
					}
               } else if (isset($_POST['character']) && strlen($_POST['character'])>0) { 
					if(strpos($productSellingPriceArray[$i]['productDescription'],$_POST['character']) !== false){
                   	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$productSellingPriceArray[$i]['productDescription']);
					}else{
                   	echo $productSellingPriceArray[$i]['productDescription']; 
					}
               }else{
                   echo $productSellingPriceArray[$i]['productDescription']; 
				}
           } else {
                echo $productSellingPriceArray[$i]['productDescription']; 
           } ?>
           </div>
 <?php } else {  ?>
                                     &nbsp;
 <?php } ?>
 </td>


                             <?php $d = $productSellingPriceArray[$i]['productSellingPricePrice']; 
                                   if(class_exists('NumberFormatter')) {
                                       if(is_array($systemFormat) && $systemFormat['languageCode'] !='') {
                                           $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY );
                                           $d = $a->format($productSellingPriceArray[$i]['productSellingPricePrice']);
                                       } else {
                                           $d = number_format($d)." You can assign Currency Format "; 
                                       }
                                   } else {
                                      $d = number_format($d);
                                   } ?>
    <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                             <?php if(isset($productSellingPriceArray[$i]['productSellingPriceStartDate'])) { 
                                 $valueArray 		= 	$productSellingPriceArray[$i]['productSellingPriceStartDate']; 
                                 if ($dateConvert->checkDate($valueArray)) {
                                   $valueData 		= 	explode('-',$valueArray);  
                                   $year 				= 	$valueData[0];  
                                   $month 			= 	$valueData[1];   
                                   $day 				= 	$valueData[2];  
                                   $value				=	date($systemFormat['systemSettingDateFormat'],mktime(0,0,0,$month,$day,$year));
                                 }else { 
                                   $value=null;
                                 }  ?>
                                 <td vAlign="top"><?php echo $value; ?></td> 
                             <?php } else { ?>
                                  <td vAlign="top"><div align="left">&nbsp;</div></td> 
                              <?php } ?>
                             <?php if(isset($productSellingPriceArray[$i]['productSellingPriceEndDate'])) { 
                                 $valueArray 		= 	$productSellingPriceArray[$i]['productSellingPriceEndDate']; 
                                 if ($dateConvert->checkDate($valueArray)) {
                                   $valueData 		= 	explode('-',$valueArray);  
                                   $year 				= 	$valueData[0];  
                                   $month 			= 	$valueData[1];   
                                   $day 				= 	$valueData[2];  
                                   $value				=	date($systemFormat['systemSettingDateFormat'],mktime(0,0,0,$month,$day,$year));
                                 }else { 
                                   $value=null;
                                 }  ?>
                                 <td vAlign="top"><?php echo $value; ?></td> 
                             <?php } else { ?>
                                  <td vAlign="top"><div align="left">&nbsp;</div></td> 
                              <?php } ?>
                                    <td vAlign="top" align="center"><div align="center">
   <?php if(isset($productSellingPriceArray[$i]['executeBy'])) {
           if(isset($_POST['query']) || isset($_POST['character'])) { 
				if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
               	if(strpos($productSellingPriceArray[$i]['staffName'],$_POST['query']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$productSellingPriceArray[$i]['staffName']);
               	}else{
               		echo $productSellingPriceArray[$i]['staffName']; 
					}
				} else if (isset($_POST['character']) && strlen($_POST['character'])>0) { 
               	if(strpos($productSellingPriceArray[$i]['staffName'],$_POST['character']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['character']."</span>",$productSellingPriceArray[$i]['staffName']);
               	}else{
               		echo $productSellingPriceArray[$i]['staffName']; 
					}
           	} else {
               	echo $productSellingPriceArray[$i]['staffName']; 
				}
           } else {
               	echo $productSellingPriceArray[$i]['staffName']; 
			} ?>
                             <?php } else { ?>
                                   &nbsp;
                             <?php } ?>
                              </div></td>
                             <?php if(isset($productSellingPriceArray[$i]['executeTime'])) { 
                                 $valueArray = $productSellingPriceArray[$i]['executeTime'];  
                                 if ($dateConvert->checkDateTime($valueArray)) {
                                   $valueArrayDate 	=   explode(' ',$valueArray);  
                                   $valueArrayFirst 	=   $valueArrayDate[0];         
                                   $valueArraySecond	=   $valueArrayDate[1];          
                                   $valueDataFirst 	=   explode('-',$valueArrayFirst);  
                                   $year              =   $valueDataFirst[0];               
                                   $month             =   $valueDataFirst[1];            
                                   $day               =   $valueDataFirst[2];                
                                   $valueDataSecond 	=   explode(':',$valueArraySecond);  
                                   $hour              =   $valueDataSecond[0];  
                                   $minute            =   $valueDataSecond[1];  
                                   $second            =   $valueDataSecond[2];  
                                   $value = date($systemFormat['systemSettingDateFormat']." ".$systemFormat['systemSettingTimeFormat'],mktime($hour,$minute,$second,$month,$day,$year)); 
                                 } else { 
                                   $value=null;
                                 } ?>
                                	<td vAlign="top"><?php echo $value; ?></td> 
                             <?php } else { ?>
                                 <td>&nbsp;</td> 
                             <?php } ?>
  	                             <?php if($productSellingPriceArray[$i]['isDelete']) {
                                 $checked="checked";
                             } else {
                                 $checked=NULL;
                             }  ?>
                            <td vAlign="top">
    <input class="form-control" style="display:none;" type="checkbox" name="productSellingPriceId[]"  value="<?php echo $productSellingPriceArray[$i]['productSellingPriceId']; ?>">
    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $productSellingPriceArray[$i]['isDelete']; ?>">
    
</td>
                    	</tr> 
                  <?php } 
 } else {  ?>
                    <tr> 
                        <td colspan="7" vAlign="top" align="center"><?php $productSellingPrice->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                    </tr> 
         <?php    }
 }  else { ?> 
                    <tr> 
                        <td colspan="7" vAlign="top" align="center"><?php $productSellingPrice->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                    </tr> 
                    <?php 
                } 
            } else { ?> 
                <tr> 
                    <td colspan="7" vAlign="top" align="center"><?php $productSellingPrice->exceptionMessage($t['loadFailureLabel']); ?></td> 
                </tr> 
                <?php 
            } 
          ?> 
             </tbody> 
         </table> 
     </div>
 </div>
 <div class="row">
     <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
         <?php $navigation->pagenationv4($offset); ?>
     </div>
     <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
		<button type="button"  class="delete btn btn-warning" onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>')"> 
			<i class="glyphicon glyphicon-white glyphicon-trash"></i> 
		</button> 
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
	<form class="form-horizontal">		<input type="hidden" name="productSellingPriceId" id="productSellingPriceId" value="<?php if (isset($_POST['productSellingPriceId'])) {
			echo $_POST['productSellingPriceId'];    
		}  ?>"> 
		<div class="row"> 
			<div class="col-xs-12 col-sm-12 col-md-12"> 
			<?php		$template->setLayout(2); 
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
						<div align="right">
							<div class="btn-group">
								<button type="button"  id="firstRecordbutton"  class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $leafAccess['leafAccessUpdateValue']; ?>','<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
							</div> 
							<div class="btn-group">
								<button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
							</div>
							<div class="btn-group">
								<button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
        					</div>
							<div class="btn-group">
								<button type="button"  id="endRecordbutton"  class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $leafAccess['leafAccessUpdateValue']; ?>','<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
							</div>
						</div>
					</div>
					<div class="panel-body">
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12">
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="productIdForm">
						<label class="control-label col-xs-4 col-sm-4 col-md-4" for="productId"><strong><?php echo ucfirst($leafTranslation['productIdLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<select name="productId" id="productId" class="form-control  chzn-select">
								<option value=""></option>
								<?php	if (is_array($productArray)) {
											$totalRecord = intval(count($productArray));
											if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($productSellingPriceArray[0]['productId'])) {
														if($productSellingPriceArray[0]['productId']==$productArray[$i]['productId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
                         		<option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $productArray[$i]['productDescription']; ?></option> 
                     			<?php               $d++;
                                     			}
                             				}   else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php		}
                             			} else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php } ?>
                     		</select>
							<span class="help-block" id="productIdHelpMe"></span>
						</div>
                     </div>
                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="countryIdForm">
						<label class="control-label col-xs-4 col-sm-4 col-md-4" for="countryId"><strong><?php echo ucfirst($leafTranslation['countryIdLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<select name="countryId" id="countryId" class="form-control  chzn-select">
								<option value=""></option>
								<?php	if (is_array($countryArray)) {
											$totalRecord = intval(count($countryArray));
											if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($productSellingPriceArray[0]['countryId'])) {
														if($productSellingPriceArray[0]['countryId']==$countryArray[$i]['countryId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
                         		<option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $countryArray[$i]['countryDescription']; ?></option> 
                     			<?php               $d++;
                                     			}
                             				}   else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php		}
                             			} else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php } ?>
                     		</select>
							<span class="help-block" id="countryIdHelpMe"></span>
						</div>
                     </div>
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="stateIdForm">
						<label class="control-label col-xs-4 col-sm-4 col-md-4" for="stateId"><strong><?php echo ucfirst($leafTranslation['stateIdLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<select name="stateId" id="stateId" class="form-control  chzn-select">
								<option value=""></option>
								<?php	if (is_array($stateArray)) {
											$totalRecord = intval(count($stateArray));
											if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($productSellingPriceArray[0]['stateId'])) {
														if($productSellingPriceArray[0]['stateId']==$stateArray[$i]['stateId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
                         		<option value="<?php echo $stateArray[$i]['stateId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $stateArray[$i]['stateDescription']; ?></option> 
                     			<?php               $d++;
                                     			}
                             				}   else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php		}
                             			} else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php } ?>
                     		</select>
							<span class="help-block" id="stateIdHelpMe"></span>
						</div>
                     </div>
                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="unitOfMeasurementIdForm">
						<label class="control-label col-xs-4 col-sm-4 col-md-4" for="unitOfMeasurementId"><strong><?php echo ucfirst($leafTranslation['unitOfMeasurementIdLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<select name="unitOfMeasurementId" id="unitOfMeasurementId" class="form-control  chzn-select">
								<option value=""></option>
								<?php	if (is_array($unitOfMeasurementArray)) {
											$totalRecord = intval(count($unitOfMeasurementArray));
											if($totalRecord > 0 ){ 
												$d=1;
												for ($i = 0; $i < $totalRecord; $i++) {
													if(isset($productSellingPriceArray[0]['unitOfMeasurementId'])) {
														if($productSellingPriceArray[0]['unitOfMeasurementId']==$unitOfMeasurementArray[$i]['unitOfMeasurementId']){
															$selected="selected";
														} else {
                                             				$selected=NULL;
														}
													} else {
                                             			$selected=NULL;
                                         			} ?>
                         		<option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option> 
                     			<?php               $d++;
                                     			}
                             				}   else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php		}
                             			} else { ?>
                         		<option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                     			<?php } ?>
                     		</select>
							<span class="help-block" id="unitOfMeasurementIdHelpMe"></span>
						</div>
                     </div>
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="productSellingPriceQuantityForm">
                 		<label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSellingPriceQuantity"><strong><?php echo ucfirst($leafTranslation['productSellingPriceQuantityLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<div class="input-group">
							<input class="form-control" type="text" name="productSellingPriceQuantity" id="productSellingPriceQuantity"
							value="<?php	if(isset($productSellingPriceArray[0]['productSellingPriceQuantity'])) {
																					if(isset($productSellingPriceArray[0]['productSellingPriceQuantity'])) {
																					echo htmlentities($productSellingPriceArray[0]['productSellingPriceQuantity']);
																				} 
																				} ?>">
							<span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
							<span class="help-block" id="productSellingPriceQuantityHelpMe"></span>
						</div>
                     </div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="productSellingPricePriceForm">
						<label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSellingPricePrice"><strong><?php echo ucfirst($leafTranslation['productSellingPricePriceLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<div class="input-group">
								<input class="form-control" type="text" name="productSellingPricePrice" id="productSellingPricePrice" onKeyUp="removeMeError('productSellingPricePrice')"  value="<?php if(isset($productSellingPriceArray) && is_array($productSellingPriceArray)) {  if(isset($productSellingPriceArray[0]['productSellingPricePrice'])) { echo htmlentities($productSellingPriceArray[0]['productSellingPricePrice']); }  } ?>">
							<span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
							<span class="help-block" id="productSellingPricePriceHelpMe"></span>
						</div>
					</div>
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                 <?php if(isset($productSellingPriceArray) && is_array($productSellingPriceArray)) {
                        
   if(isset($productSellingPriceArray[0]['productSellingPriceStartDate'])) {
   $valueArray = $productSellingPriceArray[0]['productSellingPriceStartDate']; 
   if ($dateConvert->checkDate($valueArray)) {
       $valueData = explode('-', $valueArray); 
       $year = $valueData[0]; 
       $month = $valueData[1];
       $day = $valueData[2];
       $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
   } else {
      $value =null; 
   }
 } } else { $value=null; } ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="productSellingPriceStartDateForm">
                 		<label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSellingPriceStartDate"><strong><?php echo ucfirst($leafTranslation['productSellingPriceStartDateLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<div class="input-group">
								<input class="form-control" type="text" name="productSellingPriceStartDate" id="productSellingPriceStartDate" value="<?php if(isset($value)) {
                        echo $value;
                    } ?>" >
								<span class="input-group-addon"><img src="./images/icons/calendar.png" id="productSellingPriceStartDateImage"></span></div>
								<span class="help-block" id="productSellingPriceStartDateHelpMe"></span>
						</div>
                     </div>
                 <?php if(isset($productSellingPriceArray) && is_array($productSellingPriceArray)) {
                        
   if(isset($productSellingPriceArray[0]['productSellingPriceEndDate'])) {
   $valueArray = $productSellingPriceArray[0]['productSellingPriceEndDate']; 
   if ($dateConvert->checkDate($valueArray)) {
       $valueData = explode('-', $valueArray); 
       $year = $valueData[0]; 
       $month = $valueData[1];
       $day = $valueData[2];
       $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
   } else {
      $value =null; 
   }
 } } else { $value=null; } ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="productSellingPriceEndDateForm">
                 		<label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSellingPriceEndDate"><strong><?php echo ucfirst($leafTranslation['productSellingPriceEndDateLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8">
							<div class="input-group">
								<input class="form-control" type="text" name="productSellingPriceEndDate" id="productSellingPriceEndDate" value="<?php if(isset($value)) {
                        echo $value;
                    } ?>" >
								<span class="input-group-addon"><img src="./images/icons/calendar.png" id="productSellingPriceEndDateImage"></span></div>
								<span class="help-block" id="productSellingPriceEndDateHelpMe"></span>
						</div>
                     </div>
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
             </div>
         </div>
	  </div><div class="panel-footer" align="center">
         <div class="btn-group">
            <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
            <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class=caret></span></a> 
            <ul class="dropdown-menu"> 
                <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php  echo $t['newContinueButtonLabel']; ?></a> </li> 
                <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php  echo $t['newUpdateButtonLabel']; ?></a> </li> 
                <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel']; ?></a> </li>--> 
                <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel']; ?></a> </li>--> 
                <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php  echo $t['newListingButtonLabel']; ?> </a></li> 
            </ul> 
        </div> 
        <div class="btn-group">
            <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php  echo $t['updateButtonLabel']; ?> </a> 
            <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a> 
            <ul class="dropdown-menu"> 
                <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php  echo $t['updateButtonLabel']; ?></a> </li> 
             <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel']; ?></a> </li> -->
                <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php  echo $t['updateListingButtonLabel']; ?></a> </li> 
            </ul> 
        </div> 
        <div class="btn-group">
            <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button> 
        </div> 
        <div class="btn-group">
            <button type="button"  id="resetRecordbutton"  class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>,'<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
        </div> 
        <div class="btn-group">
            <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button> 
        </div> 
        <div class="btn-group">
            <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php echo $securityToken; ?>',0,<?php echo LIMIT; ?>,1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
        </div> 
	</div> 
    <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php if(isset($firstRecord)) { echo intval($firstRecord); } ?>"> 
    <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php if(isset($nextRecord)) { echo intval($nextRecord); } ?>"> 
    <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php if(isset($previousRecord)) { echo intval($previousRecord); } ?>"> 
    <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php if(isset($lastRecord)) { echo intval($lastRecord); } ?>"> 
    <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php if(isset($endRecord)) { echo intval($endRecord); } ?>"> 
</div></div></div></div>
</form>
    <script type="text/javascript"> 
	
         $(document).ready(function(){  
             $(document).scrollTop(0);
             $(".chzn-select").chosen({ search_contains: true });
             $(".chzn-select-deselect").chosen({allow_single_deselect:true});
         validateMeNumeric('productSellingPriceId'); 
         validateMeNumeric('productId'); 
         validateMeNumeric('countryId'); 
         validateMeNumeric('stateId'); 
         validateMeNumeric('unitOfMeasurementId'); 
         validateMeNumeric('productSellingPriceQuantity'); 
         validateMeCurrency('productSellingPricePrice'); 
         $('#productSellingPriceStartDate').datepicker({ 
             format :"<?php echo  $systemFormat['systemSettingDateFormat']; ?>"
         }).on('changeDate', function () {
             $(this).datepicker('hide');
         });   
         $('#productSellingPriceEndDate').datepicker({ 
             format :"<?php echo  $systemFormat['systemSettingDateFormat']; ?>"
         }).on('changeDate', function () {
             $(this).datepicker('hide');
         });   
         <?php if($_POST['method']=="new") { ?> 
             $('#resetRecordButton').removeClass().addClass('btn btn-info'); 
         <?php if($leafAccess['leafAccessCreateValue']==1) { ?> 
             $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1)"); 
             $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success'); 
             $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1)"); 
             $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',2)"); 
             $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',3)"); 
             $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',4)"); 
             $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',5)"); 
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
         <?php } else  if ($_POST['productSellingPriceId']) { ?> 
             $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', ''); 
             $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled'); 
             $('#newRecordButton3').attr('onClick', ''); 
             $('#newRecordButton4').attr('onClick', ''); 
             $('#newRecordButton5').attr('onClick', ''); 
             $('#newRecordButton6').attr('onClick', ''); 
             $('#newRecordButton7').attr('onClick', ''); 
         <?php if($leafAccess['leafAccessUpdateValue']==1) { ?> 
             $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
	;
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info'); 
             $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productSellingPrice->getControllerPath(); ?>','<?php echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
         <?php }  else { ?> 
             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', ''); 
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled'); 
             $('#updateRecordButton3').attr('onClick', ''); 
             $('#updateRecordButton4').attr('onClick', ''); 
             $('#updateRecordButton5').attr('onClick', ''); 
         <?php } ?> 
         <?php if($leafAccess['leafAccessDeleteValue']==1) { ?> 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $productSellingPrice->getControllerPath(); ?>','<?php  echo $productSellingPrice->getViewPath(); ?>','<?php  echo $securityToken; ?>',<?php  echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
         <?php }  else { ?> 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', ''); 
         <?php } ?>  
      <?php } ?>  
         }); 
    </script> 
<?php } ?> 

<script type="text/javascript" src="./v3/financial/inventory/javascript/productSellingPrice.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>