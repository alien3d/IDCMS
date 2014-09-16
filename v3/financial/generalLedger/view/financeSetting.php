  

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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/financeSettingController.php");
require_once ($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");
require_once ($newFakeDocumentRoot . "library/class/classDate.php");
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
$previousDay = $dateConvert->getPreviousDate($dateRangeStart, 'day');
$nextDay = $dateConvert->getForwardDate($dateRangeStart, 'day');
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
$previousMonth = $dateConvert->getPreviousDate($dateRangeStart, 'month');
$nextMonth = $dateConvert->getForwardDate($dateRangeStart, 'month');
//year
$previousYear = $dateConvert->getPreviousDate($dateRangeStart, 'year');
$nextYear = $dateConvert->getForwardDate($dateRangeStart, 'year');
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable('financeSetting');
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
$arrayInfo = $translator->getFileInfo();
$applicationId = $arrayInfo['applicationId'];
$moduleId = $arrayInfo['moduleId'];
$folderId = $arrayInfo['folderId']; //future if required 
$leafId = $arrayInfo['leafId'];
$applicationNative = $arrayInfo['applicationNative'];
$folderNative = $arrayInfo['folderNative'];
$moduleNative = $arrayInfo['moduleNative'];
$leafNative = $arrayInfo['leafNative'];
$translator->createLeafBookmark('', '', '', $leafId);
$systemFormat = $translator->getSystemFormat();
$t = $translator->getDefaultTranslation(); // short because code too long  
$leafTranslation = $translator->getLeafTranslation();
$leafAccess = $translator->getLeafAccess();
$financeSettingArray = array();
$countryArray = array();
$financeYearArray = array();
$financePettyCashControlAccountArray = array();
$financeBankControlAccountArray = array();
$financeIncomeControlAccountArray = array();
$financeExpensesControlAccountArray = array();
$financeDebtorControlAccountArray = array();
$financeCreditorControlAccountArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $financeSetting = new \Core\Financial\GeneralLedger\FinanceSetting\Controller\FinanceSettingClass();
        define('LIMIT', 10);
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
            $financeSetting->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $financeSetting->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $financeSetting->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $financeSetting->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $financeSetting->setStartDay($start[2]);
            $financeSetting->setStartMonth($start[1]);
            $financeSetting->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $financeSetting->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $financeSetting->setEndDay($start[2]);
            $financeSetting->setEndMonth($start[1]);
            $financeSetting->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $financeSetting->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $financeSetting->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $financeSetting->setServiceOutput('html');
        $financeSetting->setLeafId($leafId);
        $financeSetting->execute();
        $countryArray = $financeSetting->getCountry();
        $financeYearArray = $financeSetting->getFinanceYear();
        $financePettyCashControlAccountArray = $financeSetting->getFinancePettyCashControlAccount();
        $financeBankControlAccountArray = $financeSetting->getFinanceBankControlAccount();
        $financeIncomeControlAccountArray = $financeSetting->getFinanceIncomeControlAccount();
        $financeExpensesControlAccountArray = $financeSetting->getFinanceExpensesControlAccount();
        $financeDebtorControlAccountArray = $financeSetting->getFinanceDebtorControlAccount();
        $financeCreditorControlAccountArray = $financeSetting->getFinanceCreditorControlAccount();
        if ($_POST['method'] == 'read') {
            $financeSetting->setStart($offset);
            $financeSetting->setLimit($limit); // normal system don't like paging..  
            $financeSetting->setPageOutput('html');
            $financeSettingArray = $financeSetting->read();
            if (isset($financeSettingArray [0]['firstRecord'])) {
                $firstRecord = $financeSettingArray [0]['firstRecord'];
            }
            if (isset($financeSettingArray [0]['nextRecord'])) {
                $nextRecord = $financeSettingArray [0]['nextRecord'];
            }
            if (isset($financeSettingArray [0]['previousRecord'])) {
                $previousRecord = $financeSettingArray [0]['previousRecord'];
            }
            if (isset($financeSettingArray [0]['lastRecord'])) {
                $lastRecord = $financeSettingArray [0]['lastRecord'];
                $endRecord = $financeSettingArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($financeSetting->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($financeSettingArray [0]['total'])) {
                $total = $financeSettingArray [0]['total'];
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
if (isset($_POST['method']) && isset($_POST['type'])) {
    if ($_POST['method'] == 'read' && $_POST['type'] == 'list') {
        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?php
                $template->setLayout(1);
                echo $template->breadcrumb($applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId);
                ?>
            </div>
        </div>
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div id="content" style="opacity: 1;">
            <div class="row">
                <div class="col-xs-10 col-sm-10 col-md-10">
                    &nbsp;
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="left" class="pull-left">
                        <div class="btn-group" align="left">
                            <button class="btn btn-warning" type="button" >
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button" >
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $financeSetting->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')">
                                        <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $financeSetting->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $financeSetting->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')">
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

                <div id="rightViewport" class="col-xs-12 col-sm-12 col-md-12">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                        <input type="hidden" name="financeSettingIdPreview" id="financeSettingIdPreview">
                                        <div class="form-group" id="countryIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="countryIdPreview" id="countryIdPreview">
                                            </div>					</div>					<div class="form-group" id="financeYearIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeYearIdPreview"><?php echo $leafTranslation['financeYearIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeYearIdPreview" id="financeYearIdPreview">
                                            </div>					</div>					<div class="form-group" id="financePettyCashControlAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financePettyCashControlAccountPreview"><?php echo $leafTranslation['financePettyCashControlAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financePettyCashControlAccountPreview" id="financePettyCashControlAccountPreview">
                                            </div>					</div>					<div class="form-group" id="financeBankControlAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeBankControlAccountPreview"><?php echo $leafTranslation['financeBankControlAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeBankControlAccountPreview" id="financeBankControlAccountPreview">
                                            </div>					</div>					<div class="form-group" id="financeIncomeControlAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeIncomeControlAccountPreview"><?php echo $leafTranslation['financeIncomeControlAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeIncomeControlAccountPreview" id="financeIncomeControlAccountPreview">
                                            </div>					</div>					<div class="form-group" id="financeExpensesControlAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeExpensesControlAccountPreview"><?php echo $leafTranslation['financeExpensesControlAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeExpensesControlAccountPreview" id="financeExpensesControlAccountPreview">
                                            </div>					</div>					<div class="form-group" id="financeDebtorControlAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeDebtorControlAccountPreview"><?php echo $leafTranslation['financeDebtorControlAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeDebtorControlAccountPreview" id="financeDebtorControlAccountPreview">
                                            </div>					</div>					<div class="form-group" id="financeCreditorControlAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeCreditorControlAccountPreview"><?php echo $leafTranslation['financeCreditorControlAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeCreditorControlAccountPreview" id="financeCreditorControlAccountPreview">
                                            </div>					</div>					<div class="form-group" id="financeSettingExchangeGraceDayDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="financeSettingExchangeGraceDayPreview"><?php echo $leafTranslation['financeSettingExchangeGraceDayLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="financeSettingExchangeGraceDayPreview" id="financeSettingExchangeGraceDayPreview">
                                            </div>					</div>					<div class="form-group" id="countryCurrencyLocaleDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="countryCurrencyLocalePreview"><?php echo $leafTranslation['countryCurrencyLocaleLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="countryCurrencyLocalePreview" id="countryCurrencyLocalePreview">
                                            </div>					</div>					<div class="form-group" id="isExchangeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isExchangePreview"><?php echo $leafTranslation['isExchangeLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isExchangePreview" id="isExchangePreview">
                                            </div>					</div>					<div class="form-group" id="isOddPeriodDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isOddPeriodPreview"><?php echo $leafTranslation['isOddPeriodLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isOddPeriodPreview" id="isOddPeriodPreview">
                                            </div>					</div>					<div class="form-group" id="isClosingDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isClosingPreview"><?php echo $leafTranslation['isClosingLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isClosingPreview" id="isClosingPreview">
                                            </div>					</div>					<div class="form-group" id="isPostingDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isPostingPreview"><?php echo $leafTranslation['isPostingLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isPostingPreview" id="isPostingPreview">
                                            </div>					</div>     		</div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $financeSetting->getControllerPath(); ?>', '<?php echo $financeSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                    <thead> 
                                        <tr> 
                                            <th width="25px" align="center"><div align="center">#</div></th>
                                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th><?php echo ucwords($leafTranslation['countryIdLabel']); ?></th> 
                                    <th width="100px" align="center"><?php echo ucwords($leafTranslation['financeYearIdLabel']); ?></th> 
                                    <th width="100px" align="center"><?php echo ucwords($leafTranslation['isExchangeLabel']); ?></th> 
                                    <th width="100px" align="center"><?php echo ucwords($leafTranslation['isOddPeriodLabel']); ?></th> 
                                    <th width="100px" align="center"><?php echo ucwords($leafTranslation['isClosingLabel']); ?></th> 
                                    <th width="100px" align="center"><?php echo ucwords($leafTranslation['isPostingLabel']); ?></th> 
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                                    <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                                    </tr> 
                                    </thead> 
                                    <tbody id="tableBody"> 
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($financeSettingArray)) {
                                                $totalRecord = intval(count($financeSettingArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($financeSettingArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($financeSettingArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>                       	
                                                            <td vAlign="top" align="center">
                                                            <div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $financeSetting->getControllerPath(); ?>', '<?php echo $financeSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($financeSettingArray [$i]['financeSettingId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);" value="Edit"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    </div></td>
                                                                    <td vAlign="top"><div align="left">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['countryDescription'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos($financeSettingArray[$i]['countryDescription'], $_POST['query']) !== false) {
                                                                                            echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $financeSettingArray[$i]['countryDescription']);
                                                                                        } else {
                                                                                            echo $financeSettingArray[$i]['countryDescription'];
                                                                                        }
                                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos($financeSettingArray[$i]['countryDescription'], $_POST['character']) !== false) {
                                                                                            echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $financeSettingArray[$i]['countryDescription']);
                                                                                        } else {
                                                                                            echo $financeSettingArray[$i]['countryDescription'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $financeSettingArray[$i]['countryDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $financeSettingArray[$i]['countryDescription'];
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            &nbsp;
                        <?php } ?>
                                                                    </td>
                                                                    <td vAlign="top"><div align="center">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['financeYearYear'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos($financeSettingArray[$i]['financeYearYear'], $_POST['query']) !== false) {
                                                                                            echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $financeSettingArray[$i]['financeYearYear']);
                                                                                        } else {
                                                                                            echo $financeSettingArray[$i]['financeYearYear'];
                                                                                        }
                                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos($financeSettingArray[$i]['financeYearYear'], $_POST['character']) !== false) {
                                                                                            echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $financeSettingArray[$i]['financeYearYear']);
                                                                                        } else {
                                                                                            echo $financeSettingArray[$i]['financeYearYear'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $financeSettingArray[$i]['financeYearYear'];
                                                                                    }
                                                                                } else {
                                                                                    echo $financeSettingArray[$i]['financeYearYear'];
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            &nbsp;
                        <?php } ?>
                                                                    </td>


                                                                    <td vAlign="top"><div align="center">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['isExchange'])) {
                                                                                if ($financeSettingArray[$i]['isExchange'] == 1) {
                                                                                    ?>
                                                                                    <img src="./images/icons/tick.png">
                                                                                <?php } else { ?>
                                                                                    <img src="./images/icons/cross.png">
                                                                            <?php } ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            &nbsp;
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td vAlign="top"><div align="center">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['isOddPeriod'])) {
                                                                                if ($financeSettingArray[$i]['isOddPeriod'] == 1) {
                                                                                    ?>
                                                                                    <img src="./images/icons/tick.png">
                                                                            <?php } else { ?>
                                                                                    <img src="./images/icons/cross.png">
                                                                            <?php } ?>
                                                                            </div>
                        <?php } else { ?>
                                                                            &nbsp;
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td vAlign="top"><div align="center">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['isClosing'])) {
                                                                                if ($financeSettingArray[$i]['isClosing'] == 1) {
                                                                                    ?>
                                                                                    <img src="./images/icons/tick.png">
                                                                            <?php } else { ?>
                                                                                    <img src="./images/icons/cross.png">
                                                                            <?php } ?>
                                                                            </div>
                                                                            <?php } else { ?>
                                                                            &nbsp;
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td vAlign="top"><div align="center">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['isPosting'])) {
                                                                                if ($financeSettingArray[$i]['isPosting'] == 1) {
                                                                                    ?>
                                                                                    <img src="./images/icons/tick.png">
                                                                                <?php } else { ?>
                                                                                    <img src="./images/icons/cross.png">
                                                                                <?php }
                                                                            }
                                                                            ?>&nbsp;</div></td>
                                                                    <td vAlign="top" align="center"><div align="center">
                                                                            <?php
                                                                            if (isset($financeSettingArray[$i]['executeBy'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos($financeSettingArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                            echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $financeSettingArray[$i]['staffName']);
                                                                                        } else {
                                                                                            echo $financeSettingArray[$i]['staffName'];
                                                                                        }
                                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos($financeSettingArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                            echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $financeSettingArray[$i]['staffName']);
                                                                                        } else {
                                                                                            echo $financeSettingArray[$i]['staffName'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $financeSettingArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $financeSettingArray[$i]['staffName'];
                                                                                }
                                                                                ?>
                                                                    <?php } else { ?>
                                                                                &nbsp;
                                                                    <?php } ?>
                                                                        </div></td>
                                                                    <?php
                                                                    if (isset($financeSettingArray[$i]['executeTime'])) {
                                                                        $valueArray = $financeSettingArray[$i]['executeTime'];
                                                                        if ($dateConvert->checkDateTime($valueArray)) {
                                                                            $valueArrayDate = explode(' ', $valueArray);
                                                                            $valueArrayFirst = $valueArrayDate[0];
                                                                            $valueArraySecond = $valueArrayDate[1];
                                                                            $valueDataFirst = explode('-', $valueArrayFirst);
                                                                            $year = $valueDataFirst[0];
                                                                            $month = $valueDataFirst[1];
                                                                            $day = $valueDataFirst[2];
                                                                            $valueDataSecond = explode(':', $valueArraySecond);
                                                                            $hour = $valueDataSecond[0];
                                                                            $minute = $valueDataSecond[1];
                                                                            $second = $valueDataSecond[2];
                                                                            $value = date($systemFormat['systemSettingDateFormat'] . " " . $systemFormat['systemSettingTimeFormat'], mktime($hour, $minute, $second, $month, $day, $year));
                                                                        } else {
                                                                            $value = null;
                                                                        }
                                                                        ?>
                                                                        <td vAlign="top"><?php echo $value; ?></td> 
                                                                    <?php } else { ?>
                                                                        <td>&nbsp;</td> 
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($financeSettingArray[$i]['isDelete']) {
                                                                        $checked = "checked";
                                                                    } else {
                                                                        $checked = NULL;
                                                                    }
                                                                    ?>

                                                        </tr> 
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr> 
                                                        <td colspan="7" vAlign="top" align="center"><?php $financeSetting->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                    </tr> 
                                                <?php
                                                }
                                            } else {
                                                ?> 
                                                <tr> 
                                                    <td colspan="7" vAlign="top" align="center"><?php $financeSetting->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" vAlign="top" align="center"><?php $financeSetting->exceptionMessage($t['loadFailureLabel']); ?></td> 
                                            </tr> 
            <?php
        }
        ?> 
                                    </tbody> 
                                </table> 
                            </div>
                        </div> 
                    </div>

                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $(document).scrollTop(0);
                $('#dateRangeStart').datepicker({
                    format: '<?php echo $systemFormat['systemSettingDateFormat']; ?>'
                }).on('changeDate', function() {
                    $(this).datepicker('hide');
                });
                $('#dateRangeEnd').datepicker({
                    format: '<?php echo $systemFormat['systemSettingDateFormat']; ?>'
                }).on('changeDate', function() {
                    $(this).datepicker('hide');
                });
            });
            function toggleChecked(status) {
                $('input:checkbox').each(function() {
                    $(this).attr('checked', status);
                });
            }
        </script> 
                                           <?php
                                           }
                                       }
                                       if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
                                           ?> 
    <form class="form-horizontal">		<input type="hidden" name="financeSettingId" id="financeSettingId" value="<?php
            if (isset($_POST['financeSettingId'])) {
                echo $_POST['financeSettingId'];
            }
            ?>"> 
        <div class="row"> 
            <div class="col-xs-12 col-sm-12 col-md-12"> 
    <?php
    $template->setLayout(2);
    echo $template->breadcrumb($applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId);
    ?> 
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
                            &nbsp;
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeYearIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeYearId"><strong><?php echo ucfirst($leafTranslation['financeYearIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeYearId" id="financeYearId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeYearArray)) {
                                                    $totalRecord = intval(count($financeYearArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financeYearId'])) {
                                                                if ($financeSettingArray[0]['financeYearId'] == $financeYearArray[$i]['financeYearId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financeYearArray[$i]['financeYearId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $financeYearArray[$i]['financeYearYear']; ?></option> 
                                                            <?php
                                                            $d++;
                                                        }
                                                    } else {
                                                        ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
        <?php
        }
    } else {
        ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block" id="financeYearIdHelpMe"></span>
                                        </div>
                                    </div>


                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="countryIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="countryId"><strong><?php echo ucfirst($leafTranslation['countryIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="countryId" id="countryId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($countryArray)) {
                                                    $totalRecord = intval(count($countryArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['countryId'])) {
                                                                if ($financeSettingArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $countryArray[$i]['countryDescription']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
        <?php
        }
    } else {
        ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                       <?php } ?>
                                            </select>
                                            <span class="help-block" id="countryIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="ccol-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeSettingExchangeGraceDayForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeSettingExchangeGraceDay"><strong><?php echo ucfirst($leafTranslation['financeSettingExchangeGraceDayLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="financeSettingExchangeGraceDay" id="financeSettingExchangeGraceDay"
                                                       value="<?php
                                                   if (isset($financeSettingArray[0]['financeSettingExchangeGraceDay'])) {
                                                       if (isset($financeSettingArray[0]['financeSettingExchangeGraceDay'])) {
                                                           echo htmlentities($financeSettingArray[0]['financeSettingExchangeGraceDay']);
                                                       }
                                                   }
                                                   ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="financeSettingExchangeGraceDayHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financePettyCashControlAccountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financePettyCashControlAccount"><strong><?php echo ucfirst($leafTranslation['financePettyCashControlAccountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financePettyCashControlAccount" id="financePettyCashControlAccount" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financePettyCashControlAccountArray)) {
                                                    $totalRecord = intval(count($financePettyCashControlAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financePettyCashControlAccount'])) {
                                                                if ($financeSettingArray[0]['financePettyCashControlAccount'] == $financePettyCashControlAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financePettyCashControlAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $financePettyCashControlAccountArray[$i]['chartOfAccountNumber']; ?>. <?php echo $financePettyCashControlAccountArray[$i]['chartOfAccountTitle']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
        <?php
        }
    } else {
        ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block" id="financePettyCashControlAccountHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeBankControlAccountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeBankControlAccount"><strong><?php echo ucfirst($leafTranslation['financeBankControlAccountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeBankControlAccount" id="financeBankControlAccount" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeBankControlAccountArray)) {
                                                    $totalRecord = intval(count($financeBankControlAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financeBankControlAccount'])) {
                                                                if ($financeSettingArray[0]['financeBankControlAccount'] == $financeBankControlAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financeBankControlAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $financeBankControlAccountArray[$i]['chartOfAccountNumber']; ?>. <?php echo $financeBankControlAccountArray[$i]['chartOfAccountTitle']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block" id="financeBankControlAccountHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeIncomeControlAccountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeIncomeControlAccount"><strong><?php echo ucfirst($leafTranslation['financeIncomeControlAccountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeIncomeControlAccount" id="financeIncomeControlAccount" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeIncomeControlAccountArray)) {
                                                    $totalRecord = intval(count($financeIncomeControlAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financeIncomeControlAccount'])) {
                                                                if ($financeSettingArray[0]['financeIncomeControlAccount'] == $financeIncomeControlAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financeIncomeControlAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $financeIncomeControlAccountArray[$i]['chartOfAccountNumber']; ?>. <?php echo $financeIncomeControlAccountArray[$i]['chartOfAccountTitle']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
        <?php
        }
    } else {
        ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block" id="financeIncomeControlAccountHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeDebtorControlAccountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeDebtorControlAccount"><strong><?php echo ucfirst($leafTranslation['financeDebtorControlAccountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeDebtorControlAccount" id="financeDebtorControlAccount" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeDebtorControlAccountArray)) {
                                                    $totalRecord = intval(count($financeDebtorControlAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financeDebtorControlAccount'])) {
                                                                if ($financeSettingArray[0]['financeDebtorControlAccount'] == $financeDebtorControlAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financeDebtorControlAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $financeDebtorControlAccountArray[$i]['chartOfAccountNumber']; ?>. <?php echo $financeDebtorControlAccountArray[$i]['chartOfAccountTitle']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block" id="financeDebtorControlAccountHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeCreditorControlAccountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeCreditorControlAccount"><strong><?php echo ucfirst($leafTranslation['financeCreditorControlAccountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeCreditorControlAccount" id="financeCreditorControlAccount" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeCreditorControlAccountArray)) {
                                                    $totalRecord = intval(count($financeCreditorControlAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financeCreditorControlAccount'])) {
                                                                if ($financeSettingArray[0]['financeCreditorControlAccount'] == $financeCreditorControlAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financeCreditorControlAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $financeCreditorControlAccountArray[$i]['chartOfAccountNumber']; ?>. <?php echo $financeCreditorControlAccountArray[$i]['chartOfAccountTitle']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="help-block" id="financeCreditorControlAccountHelpMe"></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="financeExpensesControlAccountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="financeExpensesControlAccount"><strong><?php echo ucfirst($leafTranslation['financeExpensesControlAccountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeExpensesControlAccount" id="financeExpensesControlAccount" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeExpensesControlAccountArray)) {
                                                    $totalRecord = intval(count($financeExpensesControlAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($financeSettingArray[0]['financeExpensesControlAccount'])) {
                                                                if ($financeSettingArray[0]['financeExpensesControlAccount'] == $financeExpensesControlAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $financeExpensesControlAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $financeExpensesControlAccountArray[$i]['chartOfAccountNumber']; ?>. <?php echo $financeExpensesControlAccountArray[$i]['chartOfAccountTitle']; ?></option> 
                <?php
                $d++;
            }
        } else {
            ?>
                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                       <?php
                                                       }
                                                   } else {
                                                       ?>
                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                   <?php } ?>
                                            </select>
                                            <span class="help-block" id="financeExpensesControlAccountHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isExchangeForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isExchange"><strong><?php echo ucfirst($leafTranslation['isExchangeLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="checkbox" name="isExchange" id="isExchange" 
                                                   value="<?php
                                               if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                   if (isset($financeSettingArray[0]['isExchange'])) {
                                                       echo $financeSettingArray[0]['isExchange'];
                                                   }
                                               }
                                                   ?>" <?php
                                                   if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                       if (isset($financeSettingArray[0]['isExchange'])) {
                                                           if ($financeSettingArray[0]['isExchange'] == TRUE || $financeSettingArray[0]['isExchange'] == 1) {
                                                               echo "checked";
                                                           }
                                                       }
                                                   }
                                                   ?>>
                                            <span class="help-block" id="isExchangeHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isOddPeriodForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isOddPeriod"><strong><?php echo ucfirst($leafTranslation['isOddPeriodLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="checkbox" name="isOddPeriod" id="isOddPeriod" 
                                                   value="<?php
                                               if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                   if (isset($financeSettingArray[0]['isOddPeriod'])) {
                                                       echo $financeSettingArray[0]['isOddPeriod'];
                                                   }
                                               }
                                                   ?>" <?php
                                                   if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                       if (isset($financeSettingArray[0]['isOddPeriod'])) {
                                                           if ($financeSettingArray[0]['isOddPeriod'] == TRUE || $financeSettingArray[0]['isOddPeriod'] == 1) {
                                                               echo "checked";
                                                           }
                                                       }
                                                   }
                                                   ?>>
                                            <span class="help-block" id="isOddPeriodHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isClosingForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isClosing"><strong><?php echo ucfirst($leafTranslation['isClosingLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="checkbox" name="isClosing" id="isClosing" 
                                                   value="<?php
                                               if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                   if (isset($financeSettingArray[0]['isClosing'])) {
                                                       echo $financeSettingArray[0]['isClosing'];
                                                   }
                                               }
                                               ?>" <?php
                                               if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                   if (isset($financeSettingArray[0]['isClosing'])) {
                                                       if ($financeSettingArray[0]['isClosing'] == TRUE || $financeSettingArray[0]['isClosing'] == 1) {
                                                           echo "checked";
                                                       }
                                                   }
                                               }
                                                   ?>>
                                            <span class="help-block" id="isClosingHelpMe"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="isPostingForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isPosting"><strong><?php echo ucfirst($leafTranslation['isPostingLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="checkbox" name="isPosting" id="isPosting" 
                                                   value="<?php
                                               if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                   if (isset($financeSettingArray[0]['isPosting'])) {
                                                       echo $financeSettingArray[0]['isPosting'];
                                                   }
                                               }
                                               ?>" <?php
                                               if (isset($financeSettingArray) && is_array($financeSettingArray)) {
                                                   if (isset($financeSettingArray[0]['isPosting'])) {
                                                       if ($financeSettingArray[0]['isPosting'] == TRUE || $financeSettingArray[0]['isPosting'] == 1) {
                                                           echo "checked";
                                                       }
                                                   }
                                               }
                                                   ?>>
                                            <span class="help-block" id="isPostingHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><div class="panel-footer" align="center">

                            <div class="btn-group" align="left">
                                <button type="button"  id="updateRecordButton1" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </button> 

                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $financeSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
                            </div> 
                        </div> 
                        <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php
                           if (isset($firstRecord)) {
                               echo intval($firstRecord);
                           }
                           ?>"> 
                        <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                           if (isset($nextRecord)) {
                               echo intval($nextRecord);
                           }
                           ?>"> 
                        <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php
                           if (isset($previousRecord)) {
                               echo intval($previousRecord);
                           }
                           ?>"> 
                        <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php
                           if (isset($lastRecord)) {
                               echo intval($lastRecord);
                           }
                           ?>"> 
                        <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php
                           if (isset($endRecord)) {
                               echo intval($endRecord);
                           }
                           ?>"> 
                    </div></div></div></div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $(document).scrollTop(0);
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('financeSettingId');
                    validateMeNumeric('countryId');
                    validateMeNumeric('financeYearId');
                    validateMeNumeric('financePettyCashControlAccount');
                    validateMeNumeric('financeBankControlAccount');
                    validateMeNumeric('financeIncomeControlAccount');
                    validateMeNumeric('financeExpensesControlAccount');
                    validateMeNumeric('financeDebtorControlAccount');
                    validateMeNumeric('financeCreditorControlAccount');
                    validateMeNumeric('financeSettingExchangeGraceDay');
                    validateMeAlphaNumeric('countryCurrencyLocale');
                    $('#isExchange').bootstrapSwitch();
                    $('#isOddPeriod').bootstrapSwitch();
                    $('#isClosing').bootstrapSwitch();
                    $('#isPosting').bootstrapSwitch();
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['financeSettingId']) { ?>
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
                                    ;
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $financeSetting->getControllerPath(); ?>','<?php echo $financeSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
                });
            </script> 
<?php } ?> 
   
</form>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/financeSetting.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>