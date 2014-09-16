  

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
require_once($newFakeDocumentRoot."v3/financial/businessPartner/controller/businessPartnerCategoryController.php"); 
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
 $translator->setCurrentTable('businessPartnerCategory'); 
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
	$businessPartnerCategoryArray = array(); 
 if (isset($_POST)) {  
    if (isset($_POST['method'])) {  
        $businessPartnerCategory = new \Core\Financial\BusinessPartner\BusinessPartnerCategory\Controller\BusinessPartnerCategoryClass();  
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
         $businessPartnerCategory->setFieldQuery($_POST ['query']); 
     } 
     if (isset($_POST ['filter'])) { 
         $businessPartnerCategory->setGridQuery($_POST ['filter']); 
     }                 
     if (isset($_POST ['character'])) { 
         $businessPartnerCategory->setCharacterQuery($_POST['character']); 
     } 
     if (isset($_POST ['dateRangeStart'])) { 
         $businessPartnerCategory->setDateRangeStartQuery($_POST['dateRangeStart']); 
         //explode the data to get day,month,year 
         $start=explode('-',$_POST ['dateRangeStart']); 
         $businessPartnerCategory->setStartDay($start[2]); 
         $businessPartnerCategory->setStartMonth($start[1]); 
         $businessPartnerCategory->setStartYear($start[0]); 
     } 
     if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd'])> 0) ) { 
         $businessPartnerCategory->setDateRangeEndQuery($_POST['dateRangeEnd']); 
         //explode the data to get day,month,year 
         $start=explode('-',$_POST ['dateRangeEnd']); 
         $businessPartnerCategory->setEndDay($start[2]); 
         $businessPartnerCategory->setEndMonth($start[1]); 
         $businessPartnerCategory->setEndYear($start[0]); 
     } 
     if (isset($_POST ['dateRangeType'])) { 
         $businessPartnerCategory->setDateRangeTypeQuery($_POST['dateRangeType']); 
     } 
     if (isset($_POST ['dateRangeExtraType'])) { 
         $businessPartnerCategory->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']); 
     } 
     $businessPartnerCategory->setServiceOutput('html');  
     $businessPartnerCategory->setLeafId($leafId);  
     $businessPartnerCategory->execute();  
     if ($_POST['method'] == 'read') {  
         $businessPartnerCategory->setStart($offset);  
         $businessPartnerCategory->setLimit($limit); // normal system don't like paging..  
         $businessPartnerCategory->setPageOutput('html');  
         $businessPartnerCategoryArray = $businessPartnerCategory->read();  
         if (isset($businessPartnerCategoryArray [0]['firstRecord'])) {  
         	$firstRecord = $businessPartnerCategoryArray [0]['firstRecord'];  
         }  
         if (isset($businessPartnerCategoryArray [0]['nextRecord'])) {  
         	$nextRecord = $businessPartnerCategoryArray [0]['nextRecord'];  
         }   
         if (isset($businessPartnerCategoryArray [0]['previousRecord'])) {  
             $previousRecord = $businessPartnerCategoryArray [0]['previousRecord'];  
         }   
         if (isset($businessPartnerCategoryArray [0]['lastRecord'])) {  
             $lastRecord = $businessPartnerCategoryArray [0]['lastRecord'];  
         	$endRecord = $businessPartnerCategoryArray [0]['lastRecord'];  
         }   
         $navigation = new \Core\Paging\HtmlPaging();  
         $navigation->setLeafId($leafId);  
         $navigation->setViewPath($businessPartnerCategory->getViewPath());  
         $navigation->setOffset($offset);  
         $navigation->setLimit($limit);  
         $navigation->setSecurityToken($securityToken);  
         $navigation->setLoadingText($t['loadingTextLabel']);  
         $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);  
         $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);  
         if (isset($businessPartnerCategoryArray [0]['total'])) {  
         	$total = $businessPartnerCategoryArray [0]['total'];  
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
      <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','A');">A</button> 
      <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','B');">B</button> 
      <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','C');">C</button> 
      <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','D');">D</button> 
      <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','E');">E</button> 
      <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','F');">F</button> 
      <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','G');">G</button> 
      <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','H');">H</button> 
      <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','I');">I</button> 
      <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','J');">J</button> 
      <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','K');">K</button> 
      <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','L');">L</button> 
      <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','M');">M</button> 
      <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','N');">N</button> 
      <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','O');">O</button> 
      <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','P');">P</button> 
      <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','Q');">Q</button> 
      <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','R');">R</button> 
      <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','S');">S</button> 
      <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','T');">T</button> 
      <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','U');">U</button> 
      <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','V');">V</button> 
      <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','W');">W</button> 
      <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','X');">X</button> 
      <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','Y');">Y</button> 
      <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','Z');">Z</button> 
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
             <ul class="dropdown-menu">
                 <li>
                     <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $securityToken; ?>','excel')">
                         <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                     </a>
                 </li>
                 <li>
                     <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $securityToken; ?>','csv')">
                         <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                     </a>
                 </li>
                 <li>
                     <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $securityToken; ?>','html')">
                         <i class ="pull-right glyphicon glyphicon-download"></i>Html
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
						<button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>','<?php   echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button> 
					</div>
					<label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php if(isset($_POST['query'])) {  echo $_POST['query']; } ?>"><span class="input-group-addon">
<img id="searchTextImage" src="./images/icons/magnifier.png">
</span>
</div>
<br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
     				<button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','0','<?php echo LIMIT; ?>',1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
					<input type="hidden" name="businessPartnerCategoryIdPreview" id="businessPartnerCategoryIdPreview">
					<div class="form-group" id="businessPartnerCategoryCodeDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerCategoryCodePreview"><?php echo $leafTranslation['businessPartnerCategoryCodeLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="businessPartnerCategoryCodePreview" id="businessPartnerCategoryCodePreview">
         			</div>					</div>					<div class="form-group" id="businessPartnerCategoryDescriptionDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerCategoryDescriptionPreview"><?php echo $leafTranslation['businessPartnerCategoryDescriptionLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="businessPartnerCategoryDescriptionPreview" id="businessPartnerCategoryDescriptionPreview">
         			</div>					</div>					<div class="form-group" id="isCreditorDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isCreditorPreview"><?php echo $leafTranslation['isCreditorLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="isCreditorPreview" id="isCreditorPreview">
         			</div>					</div>					<div class="form-group" id="isDebtorDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isDebtorPreview"><?php echo $leafTranslation['isDebtorLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="isDebtorPreview" id="isDebtorPreview">
         			</div>					</div>					<div class="form-group" id="isGlobalDiv">
					<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isGlobalPreview"><?php echo $leafTranslation['isGlobalLabel'];  ?></label>
					<div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<input class="form-control" type="text" name="isGlobalPreview" id="isGlobalPreview">
         			</div>					</div>     		</div> 
     		<div class="modal-footer"> 
         		<button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
         		<button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview',0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
     		</div> 
 		</div> 
     </div> 
 </div> 
<div class="row">
 <div class="col-xs-12 col-sm-12 col-md-12">
 		<div class="panel panel-default">

     <table class ="table table-striped table-condensed table-hover" id="tableData"> 
         <thead> 
             <tr> 
                 <th width="25px" align="center"><div align="center">#</div></th>
                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                  <th width="75px"><div align="center"><?php echo ucwords($leafTranslation['businessPartnerCategoryCodeLabel']); ?></div></th> 
                  <th><?php echo ucwords($leafTranslation['businessPartnerCategoryDescriptionLabel']); ?></th> 
                  <th width="125px"><?php echo ucwords($leafTranslation['isCreditorLabel']); ?></th> 
                  <th width="125px"><?php echo ucwords($leafTranslation['isDebtorLabel']); ?></th> 
                  <th width="125px"><?php echo ucwords($leafTranslation['isGlobalLabel']); ?></th> 
                  <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                  <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                 <th width="25px"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onClick="toggleChecked(this.checked)"></th>
             </tr> 
         </thead> 
         <tbody id="tableBody"> 
             <?php   if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') { 
                 if (is_array($businessPartnerCategoryArray)) { 
                     $totalRecord = intval(count($businessPartnerCategoryArray)); 
                     if ($totalRecord > 0) { 
                         $counter=0; 
                         for ($i = 0; $i < $totalRecord; $i++) { 
                             $counter++; ?>
                            	<tr <?php 
                                           if ($businessPartnerCategoryArray[$i]['isDelete'] == 1) { 
                                                echo "class=\"danger\""; 
                                            } else { 
                                                if ($businessPartnerCategoryArray[$i]['isDraft'] == 1) { 
                                                    echo "class=\"warning\""; 
                                               } 
                                           } 
                                            ?>>
                             	<td vAlign="top" align="center"><div align="center"><?php echo ($counter+$offset); ?>.</div></td>                       	<td vAlign="top" align="center"><div class="btn-group" align="center">
                                 <button type="button"  class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo intval($businessPartnerCategoryArray [$i]['businessPartnerCategoryId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="Edit"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                 <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete('<?php echo rawurlencode($businessPartnerCategoryArray [$i]['businessPartnerCategoryId']); ?>','<?php echo rawurlencode($businessPartnerCategoryArray [$i]['businessPartnerCategoryCode']); ?>','<?php echo rawurlencode($businessPartnerCategoryArray [$i]['businessPartnerCategoryDescription']); ?>','<?php echo rawurlencode($businessPartnerCategoryArray [$i]['isCreditor']); ?>','<?php echo rawurlencode($businessPartnerCategoryArray [$i]['isDebtor']); ?>','<?php echo rawurlencode($businessPartnerCategoryArray [$i]['isGlobal']); ?>')" value="Delete"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button></div></td> 
                                    <td vAlign="top"><div align="center">
   <?php   if(isset($businessPartnerCategoryArray[$i]['businessPartnerCategoryCode'])) { 
               if(isset($_POST['query']) || isset($_POST['character'])) { 
					if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
                   	if(strpos(strtolower($businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']),strtolower($_POST['query'])) !== false){
                       	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']);
						}else { 
                   		echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']; 
						}
                   } else if(isset($_POST['character']) && strlen($_POST['character'])>0) { 
						if(strpos(strtolower($businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']),strtolower($_POST['character'])) !== false){
                       	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']);
						}else{ 
                   		echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']; 
						}
                   }else {
                   	echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']; 
					}
               } else {
                   echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryCode']; 
               } ?>
           </div>
<?php } else { ?>
                                    &nbsp;
<?php } ?>
</td>
                                    <td vAlign="top"><div align="left">
   <?php   if(isset($businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription'])) { 
               if(isset($_POST['query']) || isset($_POST['character'])) { 
					if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
                   	if(strpos(strtolower($businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']),strtolower($_POST['query'])) !== false){
                       	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']);
						}else { 
                   		echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']; 
						}
                   } else if(isset($_POST['character']) && strlen($_POST['character'])>0) { 
						if(strpos(strtolower($businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']),strtolower($_POST['character'])) !== false){
                       	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']);
						}else{ 
                   		echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']; 
						}
                   }else {
                   	echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']; 
					}
               } else {
                   echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']; 
               } ?>
           </div>
<?php } else { ?>
                                    &nbsp;
<?php } ?>
</td>
                                    <td vAlign="top"><div align="left">
   <?php   if(isset($businessPartnerCategoryArray[$i]['isCreditor'])) { 
               if(isset($_POST['query']) || isset($_POST['character'])) { 
					if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
                   	if(strpos(strtolower($businessPartnerCategoryArray[$i]['isCreditor']),strtolower($_POST['query'])) !== false){
                       	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$businessPartnerCategoryArray[$i]['isCreditor']);
						}else { 
                   		echo $businessPartnerCategoryArray[$i]['isCreditor']; 
						}
                   } else if(isset($_POST['character']) && strlen($_POST['character'])>0) { 
						if(strpos(strtolower($businessPartnerCategoryArray[$i]['isCreditor']),strtolower($_POST['character'])) !== false){
                       	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$businessPartnerCategoryArray[$i]['isCreditor']);
						}else{ 
                   		echo $businessPartnerCategoryArray[$i]['isCreditor']; 
						}
                   }else {
                   	echo $businessPartnerCategoryArray[$i]['isCreditor']; 
					}
               } else {
                   echo $businessPartnerCategoryArray[$i]['isCreditor']; 
               } ?>
           </div>
<?php } else { ?>
                                    &nbsp;
<?php } ?>
</td>
                                    <td vAlign="top"><div align="left">
   <?php   if(isset($businessPartnerCategoryArray[$i]['isDebtor'])) { 
               if(isset($_POST['query']) || isset($_POST['character'])) { 
					if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
                   	if(strpos(strtolower($businessPartnerCategoryArray[$i]['isDebtor']),strtolower($_POST['query'])) !== false){
                       	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$businessPartnerCategoryArray[$i]['isDebtor']);
						}else { 
                   		echo $businessPartnerCategoryArray[$i]['isDebtor']; 
						}
                   } else if(isset($_POST['character']) && strlen($_POST['character'])>0) { 
						if(strpos(strtolower($businessPartnerCategoryArray[$i]['isDebtor']),strtolower($_POST['character'])) !== false){
                       	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$businessPartnerCategoryArray[$i]['isDebtor']);
						}else{ 
                   		echo $businessPartnerCategoryArray[$i]['isDebtor']; 
						}
                   }else {
                   	echo $businessPartnerCategoryArray[$i]['isDebtor']; 
					}
               } else {
                   echo $businessPartnerCategoryArray[$i]['isDebtor']; 
               } ?>
           </div>
<?php } else { ?>
                                    &nbsp;
<?php } ?>
</td>
                                    <td vAlign="top"><div align="left">
   <?php   if(isset($businessPartnerCategoryArray[$i]['isGlobal'])) { 
               if(isset($_POST['query']) || isset($_POST['character'])) { 
					if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
                   	if(strpos(strtolower($businessPartnerCategoryArray[$i]['isGlobal']),strtolower($_POST['query'])) !== false){
                       	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$businessPartnerCategoryArray[$i]['isGlobal']);
						}else { 
                   		echo $businessPartnerCategoryArray[$i]['isGlobal']; 
						}
                   } else if(isset($_POST['character']) && strlen($_POST['character'])>0) { 
						if(strpos(strtolower($businessPartnerCategoryArray[$i]['isGlobal']),strtolower($_POST['character'])) !== false){
                       	echo str_replace($_POST['character'],"<span class=\"label label-info\">".$_POST['character']."</span>",$businessPartnerCategoryArray[$i]['isGlobal']);
						}else{ 
                   		echo $businessPartnerCategoryArray[$i]['isGlobal']; 
						}
                   }else {
                   	echo $businessPartnerCategoryArray[$i]['isGlobal']; 
					}
               } else {
                   echo $businessPartnerCategoryArray[$i]['isGlobal']; 
               } ?>
           </div>
<?php } else { ?>
                                    &nbsp;
<?php } ?>
</td>
                                    <td vAlign="top" align="center"><div align="center">
   <?php if(isset($businessPartnerCategoryArray[$i]['executeBy'])) {
           if(isset($_POST['query']) || isset($_POST['character'])) { 
				if(isset($_POST['query']) && strlen($_POST['query'])>0) { 
               	if(strpos($businessPartnerCategoryArray[$i]['staffName'],$_POST['query']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['query']."</span>",$businessPartnerCategoryArray[$i]['staffName']);
               	}else{
               		echo $businessPartnerCategoryArray[$i]['staffName']; 
					}
				} else if (isset($_POST['character']) && strlen($_POST['character'])>0) { 
               	if(strpos($businessPartnerCategoryArray[$i]['staffName'],$_POST['character']) !== false){
                   	echo str_replace($_POST['query'],"<span class=\"label label-info\">".$_POST['character']."</span>",$businessPartnerCategoryArray[$i]['staffName']);
               	}else{
               		echo $businessPartnerCategoryArray[$i]['staffName']; 
					}
           	} else {
               	echo $businessPartnerCategoryArray[$i]['staffName']; 
				}
           } else {
               	echo $businessPartnerCategoryArray[$i]['staffName']; 
			} ?>
                             <?php } else { ?>
                                   &nbsp;
                             <?php } ?>
                              </div></td>
                             <?php if(isset($businessPartnerCategoryArray[$i]['executeTime'])) { 
                                 $valueArray = $businessPartnerCategoryArray[$i]['executeTime'];  
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
  	                             <?php if($businessPartnerCategoryArray[$i]['isDelete']) {
                                 $checked="checked";
                             } else {
                                 $checked=NULL;
                             }  ?>
                            <td vAlign="top">
    <input class="form-control" style="display:none;" type="checkbox" name="businessPartnerCategoryId[]"  value="<?php echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryId']; ?>">
    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $businessPartnerCategoryArray[$i]['isDelete']; ?>">
    
</td>
                    	</tr> 
                  <?php } 
 } else {  ?>
                    <tr> 
                        <td colspan="7" vAlign="top" align="center"><?php $businessPartnerCategory->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                    </tr> 
         <?php    }
 }  else { ?> 
                    <tr> 
                        <td colspan="7" vAlign="top" align="center"><?php $businessPartnerCategory->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                    </tr> 
                    <?php 
                } 
            } else { ?> 
                <tr> 
                    <td colspan="7" vAlign="top" align="center"><?php $businessPartnerCategory->exceptionMessage($t['loadFailureLabel']); ?></td> 
                </tr> 
                <?php 
            } 
          ?> 
             </tbody> 
         </table> 
     </div>
 </div>
 </div>
 <div class="row">
     <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
         <?php $navigation->pagenationv4($offset); ?>
     </div>
     <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
		<button type="button"  class="delete btn btn-warning" onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>')"> 
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
	<form class="form-horizontal">		<input type="hidden" name="businessPartnerCategoryId" id="businessPartnerCategoryId" value="<?php if (isset($_POST['businessPartnerCategoryId'])) {
			echo $_POST['businessPartnerCategoryId'];    
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
								<button type="button"  id="firstRecordbutton"  class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $leafAccess['leafAccessUpdateValue']; ?>','<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
							</div> 
							<div class="btn-group">
								<button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
							</div>
							<div class="btn-group">
								<button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
        					</div>
							<div class="btn-group">
								<button type="button"  id="endRecordbutton"  class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>','<?php echo $leafAccess['leafAccessUpdateValue']; ?>','<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
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
					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="businessPartnerCategoryCodeForm">
						<label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerCategoryCode"><strong><?php echo ucfirst($leafTranslation['businessPartnerCategoryCodeLabel']); ?></strong></label>
						<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
						<div class="input-group">
							<input class="form-control" type="text" name="businessPartnerCategoryCode" id="businessPartnerCategoryCode"  
						onKeyUp="removeMeError('businessPartnerCategoryCode')" 
						value="<?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {  
						if(isset($businessPartnerCategoryArray[0]['businessPartnerCategoryCode'])) { echo htmlentities($businessPartnerCategoryArray[0]['businessPartnerCategoryCode']); } } ?>" maxlength="16">
							<span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
							<span class="help-block" id="businessPartnerCategoryCodeHelpMe"></span>
						</div>
                     </div>
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isCreditorForm">
                      		<label class="control-label col-xs-4 col-sm-4 col-md-4" for="isCreditor"><strong><?php echo ucfirst($leafTranslation['isCreditorLabel']); ?></strong></label>
							<div class="col-xs-8 col-sm-8 col-md-8">
								<input class="form-control" type="checkbox" name="isCreditor" id="isCreditor" 
						value="<?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {
                        if(isset($businessPartnerCategoryArray[0]['isCreditor'])) {
						echo $businessPartnerCategoryArray[0]['isCreditor'];
						}
                    } ?>" <?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {
                        if(isset($businessPartnerCategoryArray[0]['isCreditor'])) {
							if($businessPartnerCategoryArray[0]['isCreditor']==TRUE || $businessPartnerCategoryArray[0]['isCreditor']==1){
							echo "checked";
						}
                    } } ?>>
							<span class="help-block" id="isCreditorHelpMe"></span>
						</div>
                     </div>
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isDebtorForm">
                      		<label class="control-label col-xs-4 col-sm-4 col-md-4" for="isDebtor"><strong><?php echo ucfirst($leafTranslation['isDebtorLabel']); ?></strong></label>
							<div class="col-xs-8 col-sm-8 col-md-8">
								<input class="form-control" type="checkbox" name="isDebtor" id="isDebtor" 
						value="<?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {
                        if(isset($businessPartnerCategoryArray[0]['isDebtor'])) {
						echo $businessPartnerCategoryArray[0]['isDebtor'];
						}
                    } ?>" <?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {
                        if(isset($businessPartnerCategoryArray[0]['isDebtor'])) {
							if($businessPartnerCategoryArray[0]['isDebtor']==TRUE || $businessPartnerCategoryArray[0]['isDebtor']==1){
							echo "checked";
						}
                    } }?>>
							<span class="help-block" id="isDebtorHelpMe"></span>
						</div>
                     </div>
					 	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isGlobalForm">
                      		<label class="control-label col-xs-4 col-sm-4 col-md-4" for="isGlobal"><strong><?php echo ucfirst($leafTranslation['isGlobalLabel']); ?></strong></label>
							<div class="col-xs-8 col-sm-8 col-md-8">
								<input class="form-control" type="checkbox" name="isGlobal" id="isGlobal" 
						value="<?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {
                        if(isset($businessPartnerCategoryArray[0]['isGlobal'])) {
						echo $businessPartnerCategoryArray[0]['isGlobal'];
						}
                    } ?>" <?php if(isset($businessPartnerCategoryArray) && is_array($businessPartnerCategoryArray)) {
                        if(isset($businessPartnerCategoryArray[0]['isGlobal'])) {
							if($businessPartnerCategoryArray[0]['isGlobal']==TRUE || $businessPartnerCategoryArray[0]['isGlobal']==1){
							echo "checked";
						}
                    } }?>>
							<span class="help-block" id="isGlobalHelpMe"></span>
						</div>
                     </div>
             </div>
         </div>
         <div class="row">
             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					
					<div class="form-group" id="businessPartnerCategoryDescriptionForm">
						<div class="col-xs-12 col-sm-12 col-md-12">
<textarea class="form-control" name="businessPartnerCategoryDescription" id="businessPartnerCategoryDescription" onKeyUp="removeMeError('businessPartnerCategoryDescription')"><?php if(isset($businessPartnerCategoryArray[0]['businessPartnerCategoryDescription'])) {

																		echo htmlentities($businessPartnerCategoryArray[0]['businessPartnerCategoryDescription']);
 
																	  } ?></textarea>
							<span class="help-block" id="businessPartnerCategoryDescriptionHelpMe"></span>
						</div>
					</div>
             </div>
         </div>
	  </div><div class="panel-footer" align="center">
         <div class="btn-group" align="left">
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
        <div class="btn-group" align="left">
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
            <button type="button"  id="resetRecordbutton"  class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>,'<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
        </div> 
        <div class="btn-group">
            <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button> 
        </div> 
        <div class="btn-group">
            <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',0,<?php echo LIMIT; ?>,1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
        </div> 
	</div> 
    <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php if(isset($firstRecord)) { echo intval($firstRecord); } ?>"> 
    <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php if(isset($nextRecord)) { echo intval($nextRecord); } ?>"> 
    <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php if(isset($previousRecord)) { echo intval($previousRecord); } ?>"> 
    <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php if(isset($lastRecord)) { echo intval($lastRecord); } ?>"> 
    <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php if(isset($endRecord)) { echo intval($endRecord); } ?>"> 
</div></div></div>
    <script type="text/javascript"> 
         $(document).ready(function(){  
             $(document).scrollTop(0);
             $(".chzn-select").chosen({ search_contains: true });
             $(".chzn-select-deselect").chosen({allow_single_deselect:true});
         validateMeNumeric('businessPartnerCategoryId'); 
             validateMeAlphaNumeric('businessPartnerCategoryCode'); 
             validateMeAlphaNumeric('businessPartnerCategoryDescription'); 
             $("[name='isCreditor']").bootstrapSwitch();
            $("[name='isDebtor']").bootstrapSwitch();
             $("[name='isGlobal']").bootstrapSwitch();
         <?php if($_POST['method']=="new") { ?> 
             $('#resetRecordButton').removeClass().addClass('btn btn-info'); 
         <?php if($leafAccess['leafAccessCreateValue']==1) { ?> 
             $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',1)"); 
             $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success'); 
             $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',1)"); 
             $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',2)"); 
             $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',3)"); 
             $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',4)"); 
             $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php  echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',5)"); 
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
         <?php } else  if ($_POST['businessPartnerCategoryId']) { ?> 
             $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', ''); 
             $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled'); 
             $('#newRecordButton3').attr('onClick', ''); 
             $('#newRecordButton4').attr('onClick', ''); 
             $('#newRecordButton5').attr('onClick', ''); 
             $('#newRecordButton6').attr('onClick', ''); 
             $('#newRecordButton7').attr('onClick', ''); 
         <?php if($leafAccess['leafAccessUpdateValue']==1) { ?> 
             $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
	;
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info'); 
             $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
         <?php }  else { ?> 
             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', ''); 
             $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled'); 
             $('#updateRecordButton3').attr('onClick', ''); 
             $('#updateRecordButton4').attr('onClick', ''); 
             $('#updateRecordButton5').attr('onClick', ''); 
         <?php } ?> 
         <?php if($leafAccess['leafAccessDeleteValue']==1) { ?> 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $businessPartnerCategory->getControllerPath(); ?>','<?php  echo $businessPartnerCategory->getViewPath(); ?>','<?php  echo $securityToken; ?>',<?php  echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
         <?php }  else { ?> 
             $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', ''); 
         <?php } ?>  
      <?php } ?>  
         }); 
    </script> 
<?php } ?> 
</div></div>
</form>
<script type="text/javascript" src="./v3/financial/businessPartner/javascript/businessPartnerCategory.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>