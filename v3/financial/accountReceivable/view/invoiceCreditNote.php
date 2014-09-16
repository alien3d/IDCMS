<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceCreditNoteController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceCreditNoteDetailController.php");
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

$translator->setCurrentTable(array('invoiceCreditNote', 'invoiceCreditNoteDetail'));
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
$invoiceCreditNoteArray = array();
$businessPartnerArray = array();
$invoiceArray = array();
$_POST['from'] = 'invoiceCreditNote.php';
$_GET['from'] = 'invoiceCreditNote.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoiceCreditNote = new \Core\Financial\AccountReceivable\InvoiceCreditNote\Controller\InvoiceCreditNoteClass();
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
            $invoiceCreditNote->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoiceCreditNote->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoiceCreditNote->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoiceCreditNote->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoiceCreditNote->setStartDay($start[2]);
            $invoiceCreditNote->setStartMonth($start[1]);
            $invoiceCreditNote->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoiceCreditNote->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoiceCreditNote->setEndDay($start[2]);
            $invoiceCreditNote->setEndMonth($start[1]);
            $invoiceCreditNote->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoiceCreditNote->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoiceCreditNote->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoiceCreditNote->setServiceOutput('html');
        $invoiceCreditNote->setLeafId($leafId);
        $invoiceCreditNote->execute();
        $businessPartnerArray = $invoiceCreditNote->getBusinessPartner();
        $invoiceArray = $invoiceCreditNote->getInvoice();
        if ($_POST['method'] == 'read') {
            $invoiceCreditNote->setStart($offset);
            $invoiceCreditNote->setLimit($limit); // normal system don't like paging..  
            $invoiceCreditNote->setPageOutput('html');
            $invoiceCreditNoteArray = $invoiceCreditNote->read();
            if (isset($invoiceCreditNoteArray [0]['firstRecord'])) {
                $firstRecord = $invoiceCreditNoteArray [0]['firstRecord'];
            }
            if (isset($invoiceCreditNoteArray [0]['nextRecord'])) {
                $nextRecord = $invoiceCreditNoteArray [0]['nextRecord'];
            }
            if (isset($invoiceCreditNoteArray [0]['previousRecord'])) {
                $previousRecord = $invoiceCreditNoteArray [0]['previousRecord'];
            }
            if (isset($invoiceCreditNoteArray [0]['lastRecord'])) {
                $lastRecord = $invoiceCreditNoteArray [0]['lastRecord'];
                $endRecord = $invoiceCreditNoteArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoiceCreditNote->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceCreditNoteArray [0]['total'])) {
                $total = $invoiceCreditNoteArray [0]['total'];
            } else {
                $total = 0;
            }
            $navigation->setTotalRecord($total);
        }
    }
}
?>
<script type="text/javascript">
    var t =<?php echo json_encode($translator->getDefaultTranslation()); ?>;
    var leafTranslation =<?php echo json_encode($translator->getLeafTranslation()); ?>;
</script>
<?php
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

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class=" panel panel-default">
                        <div class="panel-body">
                            <div class="col-xs-1 col-sm-1 col-md-1">
                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-success"
                                        onClick="showForm(<?php echo $leafId; ?>, '<?php
                                        echo $invoiceCreditNote->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>');">
                                    <?php echo $t['newButtonLabel']; ?></button>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3">
                                <div class="input-group"><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                        echo $_POST['query'];
                                    }
                                    ?>"><span class="input-group-btn"><button type="button" name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                            class="btn btn-warning " onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                            echo $invoiceCreditNote->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"><i class="glyphicon glyphicon-zoom-in"></i></button></span></div>

                            </div>	 
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="input-group"><input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="startDateImage"></span></div>

                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div class="input-group"><input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>">
                                    <span class="input-group-addon"><img src="./images/icons/calendar.png" id="endDateImage"></span></div>																	 
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <input type="button"  name="searchDate" id="searchDate" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                       echo $invoiceCreditNote->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2">
                                <div align="left" class="pull-left">
                                    <div class="btn-group">								
                                        <button class="btn btn-warning" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                                        <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button"> <span class="caret"></span> </button>
                                        <ul class="dropdown-menu">
                                            <li> <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')"> <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007 </a> </li>
                                            <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')"> <i class ="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <form class="form-horizontal">
                                        <input type="hidden" name="invoiceCreditNoteIdPreview" id="invoiceCreditNoteIdPreview">
                                        <div class="form-group" id="businessPartnerIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceIdPreview"><?php echo $leafTranslation['invoiceIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceIdPreview" id="invoiceIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="documentNumberPreview" id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceCreditNoteAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceCreditNoteAmountPreview"><?php echo $leafTranslation['invoiceCreditNoteAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceCreditNoteAmountPreview" id="invoiceCreditNoteAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="referenceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="referenceNumberPreview"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview" id="referenceNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceCreditNoteDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceCreditNoteDatePreview"><?php echo $leafTranslation['invoiceCreditNoteDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceCreditNoteDatePreview" id="invoiceCreditNoteDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceCreditNoteDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceCreditNoteDescriptionPreview"><?php echo $leafTranslation['invoiceCreditNoteDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceCreditNoteDescriptionPreview" id="invoiceCreditNoteDescriptionPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                        <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <?php } ?>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
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
                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['invoiceCreditNoteDateLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['invoiceCreditNoteDescriptionLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['invoiceCreditNoteAmountLabel']); ?></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onclick="toggleChecked(this.checked)"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            $totalInvoiceCreditNote = 0;
                                            if (is_array($invoiceCreditNoteArray)) {
                                                $totalRecord = intval(count($invoiceCreditNoteArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($invoiceCreditNoteArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($invoiceCreditNoteArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td valign="top" align="center"><?php if ($invoiceCreditNoteArray[$i]['invoiceCreditNoteCode'] != 'UNBL') { ?><div class="btn-group" align="center">
                                                                        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                                                            <button type="button"  class="btn btn-warning btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($invoiceCreditNoteArray [$i]['invoiceCreditNoteId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <?php } ?>
                                                                        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                                                            <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($invoiceCreditNoteArray [$i]['invoiceCreditNoteId']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['businessPartnerCompany']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['invoiceDescription']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['from']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['invoiceCreditNoteTitle']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['documentNumber']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['invoiceCreditNoteAmount']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['referenceNumber']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['invoiceCreditNoteDate']); ?>', '<?php echo rawurlencode($invoiceCreditNoteArray [$i]['invoiceCreditNoteDescription']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                        <?php } ?>
                                                                    </div><?php } ?></td>



                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($invoiceCreditNoteArray[$i]['documentNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($invoiceCreditNoteArray[$i]['documentNumber']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceCreditNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($invoiceCreditNoteArray[$i]['documentNumber']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceCreditNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceCreditNoteArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceCreditNoteArray[$i]['documentNumber'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            if (isset($invoiceCreditNoteArray[$i]['invoiceCreditNoteDate'])) {
                                                                $valueArray = $invoiceCreditNoteArray[$i]['invoiceCreditNoteDate'];
                                                                if ($dateConvert->checkDate($valueArray)) {
                                                                    $valueData = explode('-', $valueArray);
                                                                    $year = $valueData[0];
                                                                    $month = $valueData[1];
                                                                    $day = $valueData[2];
                                                                    $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                                                } else {
                                                                    $value = null;
                                                                }
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td valign="top"><?php echo $value; ?></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($invoiceCreditNoteArray[$i]['businessPartnerCompany'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($invoiceCreditNoteArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceCreditNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($invoiceCreditNoteArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceCreditNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>

                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceCreditNoteArray[$i]['invoiceCreditNoteDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            $d = $invoiceCreditNoteArray[$i]['invoiceCreditNoteAmount'];
                                                            $totalInvoiceCreditNote += $d;
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($invoiceCreditNoteArray[$i]['invoiceCreditNoteAmount']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td valign="top"><div align="right"><?php echo$d; ?></div></td>


                                                            <td valign="top" align="center"><div align="center">
                                                                    <?php
                                                                    if (isset($invoiceCreditNoteArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($invoiceCreditNoteArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceCreditNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($invoiceCreditNoteArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceCreditNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $invoiceCreditNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceCreditNoteArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceCreditNoteArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?></div></td>
                                                            <?php
                                                            if (isset($invoiceCreditNoteArray[$i]['executeTime'])) {
                                                                $valueArray = $invoiceCreditNoteArray[$i]['executeTime'];
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
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td valign="top"><?php echo $value; ?></td>
                                                            <?php
                                                            if ($invoiceCreditNoteArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td valign="top"><?php if ($invoiceCreditNoteArray[$i]['invoiceCreditNoteCode'] != 'UNBL') { ?><input class="form-control" style="display:none;" type="checkbox" name="invoiceCreditNoteId[]"  value="<?php echo $invoiceCreditNoteArray[$i]['invoiceCreditNoteId']; ?>">
                                                                    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $invoiceCreditNoteArray[$i]['isDelete']; ?>"><?php } ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="10" valign="top" align="center"><?php $invoiceCreditNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="10" valign="top" align="center"><?php $invoiceCreditNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" valign="top" align="center"><?php $invoiceCreditNote->exceptionMessage($t['loadFailureLabel']); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="success">
                                            <td colspan="6"><div class="pull-right"><b><?php echo $t['totalTextLabel']; ?> :</b></div></td>
                                            <td><div class="pull-right"><strong>
                                                        <?php
                                                        if (class_exists('NumberFormatter')) {
                                                            $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                            $d = $a->format($totalInvoiceCreditNote);
                                                        } else {
                                                            $d = number_format($totalInvoiceCreditNote) . " You can assign Currency Format ";
                                                        }
                                                        echo $d;
                                                        ?>
                                                    </strong></div></td>
                                            <td colspan="3">&nbsp;</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
                            <?php $navigation->pagenationv4($offset); ?>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
                            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
                            <?php } ?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            window.scrollTo(0, 0);
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

                    </script>
                </div>
            </div>
        </div>
        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <?php
    $invoiceCreditNoteDetail = new \Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Controller\InvoiceCreditNoteDetailClass();
    $invoiceCreditNoteDetail->setServiceOutput('html');
    $invoiceCreditNoteDetail->setLeafId($leafId);
    $invoiceCreditNoteDetail->execute();
    $invoiceArray = $invoiceCreditNoteDetail->getInvoice();
    $chartOfAccountArray = $invoiceCreditNoteDetail->getChartOfAccount();
    $businessPartnerArray = $invoiceCreditNoteDetail->getBusinessPartner();
    $invoiceCreditNoteDetail->setStart(0);
    $invoiceCreditNoteDetail->setLimit(999999); // normal system don't like paging..  
    $invoiceCreditNoteDetail->setPageOutput('html');
    if ($_POST['invoiceCreditNoteId']) {
        $invoiceCreditNoteDetailArray = $invoiceCreditNoteDetail->read();
    }
    ?>
    <form class="form-horizontal">
        <input type="hidden" name="invoiceCreditNoteId" id="invoiceCreditNoteId" value="<?php
        if (isset($_POST['invoiceCreditNoteId'])) {
            echo $_POST['invoiceCreditNoteId'];
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
                    <div class="panel panel-info" id="masterForm">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <div class="btn-group"> <button id="newRecordButton1" class="btn btn-success disabled" type="button"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group"> <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a> </li>
                                        <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];        ?></a> </li> -->
                                        <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?></a> </li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
                                </div>
                            </div>
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $invoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $invoiceCreditNote->getViewPath(); ?>', '<?php echo $invoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"> </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="businessPartnerIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="businessPartnerId"><strong><?php echo ucfirst($leafTranslation['businessPartnerIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="businessPartnerId" id="businessPartnerId" class="form-control  chzn-select"onChange="getInvoice(<?php echo $leafId; ?>, '<?php echo $invoiceCreditNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>');">
                                                <option value=""></option>
                                                <?php
                                                $businessPartnerCategoryDescription = null;
                                                if (is_array($businessPartnerArray)) {
                                                    $totalRecord = intval(count($businessPartnerArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($i != 0) {
                                                                if ($businessPartnerCategoryDescription != $businessPartnerArray[$i]['businessPartnerCategoryDescription']) {
                                                                    echo "</optgroup><optgroup label=\"" . $businessPartnerArray[$i]['businessPartnerCategoryDescription'] . "\">";
                                                                }
                                                            } else {
                                                                echo "<optgroup label=\"" . $businessPartnerArray[$i]['businessPartnerCategoryDescription'] . "\">";
                                                            }
                                                            $businessPartnerCategoryDescription = $businessPartnerArray[$i]['businessPartnerCategoryDescription'];
                                                            if (isset($invoiceCreditNoteArray[0]['businessPartnerId'])) {
                                                                if ($invoiceCreditNoteArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $businessPartnerArray[$i]['businessPartnerCompany']; ?></option>
                                                            <?php
                                                            $d++;
                                                        }
                                                        echo "</optgroup>";
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
                                            <span class="help-block" id="businessPartnerIdHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="invoiceIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceId"><strong><?php echo ucfirst($leafTranslation['invoiceIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="invoiceId" id="invoiceId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                $invoiceProjectTitle = null;
                                                if (is_array($invoiceArray)) {
                                                    $totalRecord = intval(count($invoiceArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($i != 0) {
                                                                if ($invoiceProjectTitle != $invoiceArray[$i]['$invoiceProjectTitle']) {
                                                                    echo "</optgroup><optgroup label=\"" . $invoiceArray[$i]['$invoiceProjectTitle'] . "\">";
                                                                }
                                                            } else {
                                                                echo "<optgroup label=\"" . $invoiceArray[$i]['invoiceProjectTitle'] . "\">";
                                                            }
                                                            $invoiceProjectTitle = $invoiceArray[$i]['invoiceProjectTitle'];
                                                            if (isset($invoiceCreditNoteArray[0]['invoiceId'])) {
                                                                if ($invoiceCreditNoteArray[0]['invoiceId'] == $invoiceArray[$i]['invoiceId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $invoiceArray[$i]['invoiceId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $invoiceArray[$i]['invoiceDescription']; ?></option>
                                                            <?php
                                                            $d++;
                                                        }
                                                        echo "</optgroup>";
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
                                            <span class="help-block" id="invoiceIdHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumber"><strong><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                <?php
                                                if (!isset($_POST['invoiceCreditNoteId'])) {
                                                    echo "disabled";
                                                }
                                                ?>
                                                       class=" form-control  <?php
                                                       if (!isset($_POST['invoiceCreditNoteId'])) {
                                                           echo "disabled";
                                                       }
                                                       ?>"
                                                       value="<?php
                                                       if (isset($invoiceCreditNoteArray) && is_array($invoiceCreditNoteArray)) {
                                                           if (isset($invoiceCreditNoteArray[0]['documentNumber'])) {
                                                               echo htmlentities($invoiceCreditNoteArray[0]['documentNumber']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/document-number.png"></span></div>
                                            <span class="help-block" id="documentNumberHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    if (isset($invoiceCreditNoteArray) && is_array($invoiceCreditNoteArray)) {

                                        if (isset($invoiceCreditNoteArray[0]['invoiceCreditNoteDate'])) {
                                            $valueArray = $invoiceCreditNoteArray[0]['invoiceCreditNoteDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        }
                                    } else {
                                        $value = null;
                                    }
                                    ?>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="invoiceCreditNoteDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceCreditNoteDate"><strong><?php echo ucfirst($leafTranslation['invoiceCreditNoteDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceCreditNoteDate" id="invoiceCreditNoteDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceCreditNoteDateImage"></span></div>
                                            <span class="help-block" id="invoiceCreditNoteDateHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="invoiceCreditNoteAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceCreditNoteAmount"><strong><?php echo ucfirst($leafTranslation['invoiceCreditNoteAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceCreditNoteAmount" id="invoiceCreditNoteAmount" onkeyup="removeMeError('invoiceCreditNoteAmount', 4)"  value="<?php
                                                if (isset($invoiceCreditNoteArray) && is_array($invoiceCreditNoteArray)) {
                                                    if (isset($invoiceCreditNoteArray[0]['invoiceCreditNoteAmount'])) {
                                                        echo htmlentities($invoiceCreditNoteArray[0]['invoiceCreditNoteAmount']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceCreditNoteAmountHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="referenceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="referenceNumber"><strong><?php echo ucfirst($leafTranslation['referenceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber" value="<?php
                                            if (isset($invoiceCreditNoteArray) && is_array($invoiceCreditNoteArray)) {
                                                if (isset($invoiceCreditNoteArray[0]['referenceNumber'])) {
                                                    echo htmlentities($invoiceCreditNoteArray[0]['referenceNumber']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="referenceNumberHelpMe"></span> </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="invoiceCreditNoteDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea class="form-control" name="invoiceCreditNoteDescription" id="invoiceCreditNoteDescription" placeholder="<?php echo ucfirst($leafTranslation['invoiceCreditNoteDescriptionLabel']); ?>"><?php
                                                if (isset($invoiceCreditNoteArray[0]['invoiceCreditNoteDescription'])) {
                                                    echo htmlentities($invoiceCreditNoteArray[0]['invoiceCreditNoteDescription']);
                                                }
                                                ?></textarea></div>
                                    </div>
                                </div>
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
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="invoiceCreditNoteDetailIdPreview" id="invoiceCreditNoteDetailIdPreview">
                            <div class="form-group" id="chartOfAccountIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="invoiceCreditNoteDetailAmountDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceCreditNoteDetailAmountPreview"><?php echo $leafTranslation['invoiceCreditNoteDetailAmountLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="invoiceCreditNoteDetailAmountPreview" id="invoiceCreditNoteDetailAmountPreview">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $invoiceCreditNoteDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                            <button type="button"  class="btn btn-primary" onclick="showMeModal('deleteDetailPreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6" align="left">
                                    <button type="button"  name="hideMaster" id="hideMaster" onclick="toggle('masterForm');"
                                            class="btn btn-info"> <?php echo $t['hideUnHideTextLabel']; ?> </button>
                                    <button type="button"  name="trialBalance" id="trialBalance"
                                            class="btn btn-info"><?php echo $t['trialBalanceTextLabel']; ?></button>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6" align="right">
                                    <button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onclick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>');" value="<?php echo $t['newButtonLabel']; ?>">
                                        <i class="glyphicon glyphicon-plus  glyphicon-white"></i></button>
                                </div>
                            </div>
                            <br>
                            <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                <thead>
                                    <tr>
                                        <th width="125px" align="center"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['invoiceCreditNoteDetailAmountLabel']); ?></th>
                                <th width="200px"><div align="center"><?php echo $t['debitTextLabel']; ?></div></th>
                                <th width="200px"><div align="center"><?php echo $t['creditTextLabel']; ?></div></th>
                                </tr>
                                <tr>
                                    <?php
                                    $disabledDetail = null;
                                    if (isset($_POST['invoiceCreditNoteId']) && (strlen($_POST['invoiceCreditNoteId']) > 0)) {
                                        $disabledDetail = null;
                                    } else {
                                        $disabledDetail = "disabled";
                                    }
                                    ?>
                                <tr>
                                    <td valign="top">&nbsp;</td>
                                    <td valign="top" class="form-group" id="chartOfAccountId9999Detail">
                                        <select name="chartOfAccountId[]" id="chartOfAccountId9999" class="chzn-select form-control ">
                                            <option value=""></option>
                                            <?php
                                            $currentChartOfAccountTypeDescription = null;
                                            if (is_array($chartOfAccountArray)) {
                                                $totalRecord = intval(count($chartOfAccountArray));
                                                if ($totalRecord > 0) {
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        if ($i != 0) {
                                                            if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                            }
                                                        } else {
                                                            echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                        }
                                                        $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                        ?>
                                                        <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>"><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                        <?php
                                                    }
                                                    echo "</optgroup>\n";
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
                                        <span class="help-block" id="chartOfAccountId9999HelpMe"></span></td>
                                    <td valign="top" class="form-group" id="invoiceCreditNoteDetailAmount9999Detail">
                                        <input class="form-control input-sm" <?php echo $disabledDetail; ?> type="text" name="invoiceCreditNoteDetailAmount[]" id="invoiceCreditNoteDetailAmount9999">
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                    if ($_POST['invoiceCreditNoteId']) {
                                        if (is_array($invoiceCreditNoteDetailArray)) {
                                            $totalRecordDetail = intval(count($invoiceCreditNoteDetailArray));
                                            if ($totalRecordDetail > 0) {
                                                $counter = 0;
                                                $totalDebit = 0;
                                                $totalCredit = 0;
                                                for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                    $counter++;
                                                    ?>
                                                    <tr id="<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>">
                                                        <td valign="top" align="center"><div class="btn-group" align="center">
                                                                <input type="hidden" name="invoiceCreditNoteDetailId[]" id="invoiceCreditNoteDetailId<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>" value="<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>">
                                                                <input type="hidden" name="invoiceCreditNoteId[]" id="invoiceCreditNoteId<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>" value="<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteId']; ?>">
                                                                <button type="button"  class="btn btn-warning btn-mini" title="Edit" onclick="showFormUpdateDetail('<?php echo $leafId; ?>', '<?php echo $invoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($invoiceCreditNoteDetailArray [$j]['invoiceCreditNoteDetailId']); ?>')"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                <button type="button"  class="btn btn-danger btn-mini" title="Delete" onclick="showModalDeleteDetail('<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>')"><i class="glyphicon glyphicon-trash  glyphicon-white"></i></button>
                                                                <div id="miniInfoPanel<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>"></div>
                                                            </div></td>
                                                        <td valign="top"  class="form-group" id="chartOfAccountId<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>Detail"><select name="chartOfAccountId[]" id="chartOfAccountId<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($chartOfAccountArray)) {
                                                                    $totalRecord = intval(count($chartOfAccountArray));
                                                                    if ($totalRecord > 0) {
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if ($i != 0) {
                                                                                if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                                    echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                                                }
                                                                            } else {
                                                                                echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                                            }
                                                                            $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                                            if ($invoiceCreditNoteDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = NULL;
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                                            <?php
                                                                        }
                                                                        echo "</optgroup>\n";
                                                                    } else {
                                                                        ?>
                                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                <?php } ?>
                                                            </select></td>
                                                        <td valign="top"  class="form-group" id="invoiceCreditNoteDetailAmount<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="invoiceCreditNoteDetailAmount[]" id="invoiceCreditNoteDetailAmount<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>"   value="<?php
                                                            if (isset($invoiceCreditNoteDetailArray) && is_array($invoiceCreditNoteDetailArray)) {
                                                                echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailAmount'];
                                                            }
                                                            ?>"></td>
                                                            <?php
                                                            $debit = 0;
                                                            $credit = 0;
                                                            $x = 0;
                                                            $y = 0;
                                                            $d = $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailAmount'];
                                                            if ($d > 0) {
                                                                $x = $d;
                                                            } else {
                                                                $y = $d;
                                                            }
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    if ($d > 0) {
                                                                        $debit = $a->format($d);
                                                                    } else {
                                                                        $credit = $a->format($d);
                                                                    }
                                                                } else {
                                                                    if ($d > 0) {
                                                                        $debit = number_format($d) . " You can assign Currency Format ";
                                                                    } else {
                                                                        $credit = number_format($d) . " You can assign Currency Format ";
                                                                    }
                                                                }
                                                            } else {
                                                                if ($d > 0) {
                                                                    $debit = number_format($d);
                                                                } else {
                                                                    $credit = number_format($d);
                                                                }
                                                            }
                                                            $totalDebit += $x;
                                                            $totalCredit += $y;
                                                            ?>
                                                        <td valign="middle"><div id="debit_<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNotelDetailId']; ?>"
                                                                                 class="pull-right"><?php echo $debit; ?></div></td>
                                                        <td valign="middle"><div id="credit_<?php echo $invoiceCreditNoteDetailArray[$j]['invoiceCreditNoteDetailId']; ?>"
                                                                                 class="pull-right"><?php echo $credit; ?></div></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" valign="top" align="center"><?php $invoiceCreditNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="6" valign="top" align="center"><?php $invoiceCreditNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    if ($totalDebit == abs($totalCredit)) {
                                        $balanceColor = 'success';
                                    } else {
                                        $balanceColor = 'warning';
                                    }
                                    ?>
                                    <tr id="totalDetail" class="<?php echo $balanceColor; ?>">
                                        <td colspan="3">&nbsp;</td>
                                        <td><div class="pull-right" id="totalDebit">
                                                <?php
                                                if (class_exists('NumberFormatter')) {
                                                    if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                        $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                        $totalDebit = $a->format($totalDebit);
                                                        $totalCredit = $a->format($totalCredit);
                                                    } else {
                                                        $totalDebit = number_format($totalDebit) . " You can assign Currency Format ";
                                                        $totalCredit = number_format($totalCredit) . " You can assign Currency Format ";
                                                    }
                                                } else {
                                                    $totalDebit = number_format($totalDebit);
                                                    $totalCredit = number_format($totalCredit);
                                                }
                                                echo $totalDebit;
                                                ?>
                                            </div></td>
                                        <td><div class="pull-right" id="totalCredit"><?php echo $totalCredit; ?></div></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                            </table>

                        </div>
                    </div>

                </div></div>
    </div></form>
    <script type="text/javascript">

        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('invoiceCreditNoteId');
            validateMeNumeric('businessPartnerId');
            validateMeNumeric('invoiceId');


            validateMeCurrency('invoiceCreditNoteAmount');
            validateMeAlphaNumeric('referenceNumber');
            $('#invoiceCreditNoteDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('invoiceCreditNoteDescription');
            validateMeNumericRange('invoiceCreditNoteDetailId');
            validateMeNumericRange('invoiceId');
            validateMeNumericRange('invoiceCreditNoteId');
            validateMeNumericRange('chartOfAccountId');
            validateMeCurrencyRange('invoiceCreditNoteDetailAmount');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['invoiceCreditNoteId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').addClass('btn dropdown-toggle btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton2').removeClass();
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoiceCreditNote->getControllerPath(); ?>','<?php echo $invoiceCreditNote->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoiceCreditNote.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>