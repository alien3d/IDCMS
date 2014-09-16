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
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseInvoiceDebitNoteController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseInvoiceDebitNoteDetailController.php");
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

$translator->setCurrentTable(array('purchaseInvoiceDebitNote', 'purchaseInvoiceDebitNoteDetail'));
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
$purchaseInvoiceDebitNoteArray = array();
$businessPartnerArray = array();
$purchaseInvoiceArray = array();
$_POST['from'] = 'purchaseInvoiceDebitNotePost.php';
$_GET['from'] = 'purchaseInvoiceDebitNotePost.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $purchaseInvoiceDebitNote = new \Core\Financial\AccountPayable\PurchaseInvoiceDebitNote\Controller\PurchaseInvoiceDebitNoteClass();
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
            $purchaseInvoiceDebitNote->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $purchaseInvoiceDebitNote->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $purchaseInvoiceDebitNote->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $purchaseInvoiceDebitNote->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $purchaseInvoiceDebitNote->setStartDay($start[2]);
            $purchaseInvoiceDebitNote->setStartMonth($start[1]);
            $purchaseInvoiceDebitNote->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $purchaseInvoiceDebitNote->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $purchaseInvoiceDebitNote->setEndDay($start[2]);
            $purchaseInvoiceDebitNote->setEndMonth($start[1]);
            $purchaseInvoiceDebitNote->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $purchaseInvoiceDebitNote->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $purchaseInvoiceDebitNote->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $purchaseInvoiceDebitNote->setServiceOutput('html');
        $purchaseInvoiceDebitNote->setLeafId($leafId);
        $purchaseInvoiceDebitNote->execute();
        $businessPartnerArray = $purchaseInvoiceDebitNote->getBusinessPartner();
        $purchaseInvoiceArray = $purchaseInvoiceDebitNote->getPurchaseInvoice();
        if ($_POST['method'] == 'read') {
            $purchaseInvoiceDebitNote->setStart($offset);
            $purchaseInvoiceDebitNote->setLimit($limit); // normal system don't like paging..  
            $purchaseInvoiceDebitNote->setPageOutput('html');
            $purchaseInvoiceDebitNoteArray = $purchaseInvoiceDebitNote->read();
            if (isset($purchaseInvoiceDebitNoteArray [0]['firstRecord'])) {
                $firstRecord = $purchaseInvoiceDebitNoteArray [0]['firstRecord'];
            }
            if (isset($purchaseInvoiceDebitNoteArray [0]['nextRecord'])) {
                $nextRecord = $purchaseInvoiceDebitNoteArray [0]['nextRecord'];
            }
            if (isset($purchaseInvoiceDebitNoteArray [0]['previousRecord'])) {
                $previousRecord = $purchaseInvoiceDebitNoteArray [0]['previousRecord'];
            }
            if (isset($purchaseInvoiceDebitNoteArray [0]['lastRecord'])) {
                $lastRecord = $purchaseInvoiceDebitNoteArray [0]['lastRecord'];
                $endRecord = $purchaseInvoiceDebitNoteArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($purchaseInvoiceDebitNote->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($purchaseInvoiceDebitNoteArray [0]['total'])) {
                $total = $purchaseInvoiceDebitNoteArray [0]['total'];
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
                    <div align="right" class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button"> <span class="caret"></span> </button>
                            <ul class="dropdown-menu">
                                <li> <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')"> <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007 </a> </li>
                                <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')"> <i class ="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
                                <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')"> <i class ="pull-right glyphicon glyphicon-download"></i>Html </a> </li>
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
                                    <form class="form-horizontal">
                                        <input type="hidden" name="purchaseInvoiceDebitNoteIdPreview" id="purchaseInvoiceDebitNoteIdPreview">
                                        <div class="form-group" id="businessPartnerIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceIdPreview"><?php echo $leafTranslation['purchaseInvoiceIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceIdPreview" id="purchaseInvoiceIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="documentNumberPreview" id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceDebitNoteAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceDebitNoteAmountPreview"><?php echo $leafTranslation['purchaseInvoiceDebitNoteAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceDebitNoteAmountPreview" id="purchaseInvoiceDebitNoteAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="referenceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="referenceNumberPreview"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview" id="referenceNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceDebitNoteDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceDebitNoteDatePreview"><?php echo $leafTranslation['purchaseInvoiceDebitNoteDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceDebitNoteDatePreview" id="purchaseInvoiceDebitNoteDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceDebitNoteDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceDebitNoteDescriptionPreview"><?php echo $leafTranslation['purchaseInvoiceDebitNoteDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceDebitNoteDescriptionPreview" id="purchaseInvoiceDebitNoteDescriptionPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                    <thead>
                                        <tr>
                                            <th width="25px" align="center"><div align="center">#</div></th>
                                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['purchaseInvoiceDebitNoteDateLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                    <th><div align="left"><?php echo ucwords($leafTranslation['purchaseInvoiceDebitNoteDescriptionLabel']); ?></div></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['purchaseInvoiceDebitNoteAmountLabel']); ?></div></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onclick="toggleChecked(this.checked)"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        $totalPurchaseInvoiceDebitNote = 0;
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {

                                            if (is_array($purchaseInvoiceDebitNoteArray)) {
                                                $totalRecord = intval(count($purchaseInvoiceDebitNoteArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($purchaseInvoiceDebitNoteArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($purchaseInvoiceDebitNoteArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td valign="top" align="center"><?php if ($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteCode'] != 'UNBL') { ?><div class="btn-group" align="center">
                                                                        <button type="button"  class="btn btn-warning btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDebitNoteId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDebitNoteId']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['businessPartnerCompany']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDescription']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDebitNoteTitle']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['documentNumber']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDebitNoteAmount']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['referenceNumber']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDebitNoteDate']); ?>', '<?php echo rawurlencode($purchaseInvoiceDebitNoteArray [$i]['purchaseInvoiceDebitNoteDescription']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                    </div><?php } ?></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseInvoiceDebitNoteArray[$i]['documentNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceDebitNoteArray[$i]['documentNumber']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceDebitNoteArray[$i]['documentNumber']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceDebitNoteArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceDebitNoteArray[$i]['documentNumber'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            if (isset($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDate'])) {
                                                                $valueArray = $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDate'];
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
                                                                    if (isset($purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            $d = $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteAmount'];
                                                            $totalPurchaseInvoiceDebitNote += $d;
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteAmount']);
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
                                                                    if (isset($purchaseInvoiceDebitNoteArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseInvoiceDebitNoteArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseInvoiceDebitNoteArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceDebitNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceDebitNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceDebitNoteArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceDebitNoteArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?></div></td>
                                                            <?php
                                                            if (isset($purchaseInvoiceDebitNoteArray[$i]['executeTime'])) {
                                                                $valueArray = $purchaseInvoiceDebitNoteArray[$i]['executeTime'];
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
                                                            if ($purchaseInvoiceDebitNoteArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td valign="top"><?php if ($purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteCode'] != 'UNBL') { ?><input class="form-control" style="display:none;" type="checkbox" name="purchaseInvoiceDebitNoteId[]"  value="<?php echo $purchaseInvoiceDebitNoteArray[$i]['purchaseInvoiceDebitNoteId']; ?>">
                                                                    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $purchaseInvoiceDebitNoteArray[$i]['isDelete']; ?>"><?php } ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="10" valign="top" align="center"><?php $purchaseInvoiceDebitNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="10" valign="top" align="center"><?php $purchaseInvoiceDebitNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" valign="top" align="center"><?php $purchaseInvoiceDebitNote->exceptionMessage($t['loadFailureLabel']); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="10">&nbsp;</td>
                                        </tr>
                                        <tr class="success">
                                            <td colspan="6"><div class="pull-right"><b><?php echo $t['totalTextLabel']; ?> :</b></div></td>
                                            <td><div class="pull-right"><strong>
                                                        <?php
                                                        if (class_exists('NumberFormatter')) {
                                                            $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                            $d = $a->format($totalPurchaseInvoiceDebitNote);
                                                        } else {
                                                            $d = number_format($totalPurchaseInvoiceDebitNote) . " You can assign Currency Format ";
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
                            <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
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
    $purchaseInvoiceDebitNoteDetail = new \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Controller\PurchaseInvoiceDebitNoteDetailClass();
    $purchaseInvoiceDebitNoteDetail->setServiceOutput('html');
    $purchaseInvoiceDebitNoteDetail->setLeafId($leafId);
    $purchaseInvoiceDebitNoteDetail->execute();
    $purchaseInvoiceArray = $purchaseInvoiceDebitNoteDetail->getPurchaseInvoice();
    $chartOfAccountArray = $purchaseInvoiceDebitNoteDetail->getChartOfAccount();
    $businessPartnerArray = $purchaseInvoiceDebitNoteDetail->getBusinessPartner();
    $purchaseInvoiceDebitNoteDetail->setStart(0);
    $purchaseInvoiceDebitNoteDetail->setLimit(999999); // normal system don't like paging..  
    $purchaseInvoiceDebitNoteDetail->setPageOutput('html');
    if ($_POST['purchaseInvoiceDebitNoteId']) {
        $purchaseInvoiceDebitNoteDetailArray = $purchaseInvoiceDebitNoteDetail->read();
    }
    ?>
    <form class="form-horizontal">
        <input type="hidden" name="purchaseInvoiceDebitNoteId" id="purchaseInvoiceDebitNoteId" value="<?php
        if (isset($_POST['purchaseInvoiceDebitNoteId'])) {
            echo $_POST['purchaseInvoiceDebitNoteId'];
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
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $purchaseInvoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $purchaseInvoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
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
                                            <select name="businessPartnerId" id="businessPartnerId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($businessPartnerArray)) {
                                                    $totalRecord = intval(count($businessPartnerArray));
                                                    $businessPartnerCategoryDescription = null;
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
                                                            if (isset($purchaseInvoiceArray[0]['businessPartnerId'])) {
                                                                if ($purchaseInvoiceArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $businessPartnerArray[$i]['businessPartnerCompany']; ?></option>
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
                                    <div class="col-xs-4 col-sm-4 col-md-6 col-lg-4 form-group" id="purchaseInvoiceIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceId"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="purchaseInvoiceId" id="purchaseInvoiceId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                $purchaseInvoiceProjectTitle = null;
                                                if (is_array($purchaseInvoiceArray)) {
                                                    $totalRecord = intval(count($purchaseInvoiceArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($i != 0) {
                                                                if ($purchaseInvoiceProjectTitle != $purchaseInvoiceArray[$i]['$purchaseInvoiceProjectTitle']) {
                                                                    echo "</optgroup><optgroup label=\"" . $purchaseInvoiceArray[$i]['$purchaseInvoiceProjectTitle'] . "\">";
                                                                }
                                                            } else {
                                                                echo "<optgroup label=\"" . $purchaseInvoiceArray[$i]['purchaseInvoiceProjectTitle'] . "\">";
                                                            }
                                                            $purchaseInvoiceProjectTitle = $purchaseInvoiceArray[$i]['purchaseInvoiceProjectTitle'];
                                                            if (isset($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceId'])) {
                                                                if ($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceId'] == $purchaseInvoiceArray[$i]['purchaseInvoiceId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $purchaseInvoiceArray[$i]['purchaseInvoiceId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $purchaseInvoiceArray[$i]['purchaseInvoiceDescription']; ?></option>
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
                                            <span class="help-block" id="purchaseInvoiceIdHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-6 col-lg-4 form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumber"><strong><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                       class="form-control" disabled
                                                       value="<?php
                                                       if (isset($$purchaseInvoiceDebitNoteArray) && is_array($purchaseInvoiceDebitNoteArray)) {
                                                           if (isset($purchaseInvoiceDebitNoteArray[0]['documentNumber'])) {
                                                               echo htmlentities($purchaseInvoiceDebitNoteArray[0]['documentNumber']);
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
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="referenceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="referenceNumber"><strong><?php echo ucfirst($leafTranslation['referenceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber" value="<?php
                                            if (isset($purchaseInvoiceDebitNoteArray) && is_array($purchaseInvoiceDebitNoteArray)) {
                                                if (isset($purchaseInvoiceDebitNoteArray[0]['referenceNumber'])) {
                                                    echo htmlentities($purchaseInvoiceDebitNoteArray[0]['referenceNumber']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="referenceNumberHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    if (isset($purchaseInvoiceDebitNoteArray) && is_array($purchaseInvoiceDebitNoteArray)) {

                                        if (isset($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceDebitNoteDate'])) {
                                            $valueArray = $purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceDebitNoteDate'];
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
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="purchaseInvoiceDebitNoteDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDebitNoteDate"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceDebitNoteDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceDebitNoteDate" id="purchaseInvoiceDebitNoteDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="purchaseInvoiceDebitNoteDateImage"></span></div>
                                            <span class="help-block" id="purchaseInvoiceDebitNoteDateHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="purchaseInvoiceDebitNoteAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDebitNoteAmount"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceDebitNoteAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceDebitNoteAmount" id="purchaseInvoiceDebitNoteAmount" onkeyup="removeMeError('purchaseInvoiceDebitNoteAmount')"  value="<?php
                                                if (isset($purchaseInvoiceDebitNoteArray) && is_array($purchaseInvoiceDebitNoteArray)) {
                                                    if (isset($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceDebitNoteAmount'])) {
                                                        echo htmlentities($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceDebitNoteAmount']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="purchaseInvoiceDebitNoteAmountHelpMe"></span> </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="purchaseInvoiceDebitNoteDescriptionForm">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <textarea class="form-control" name="purchaseInvoiceDebitNoteDescription" id="purchaseInvoiceDebitNoteDescription"><?php
                                            if (isset($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceDebitNoteDescription'])) {
                                                echo htmlentities($purchaseInvoiceDebitNoteArray[0]['purchaseInvoiceDebitNoteDescription']);
                                            }
                                            ?></textarea>
                                        <span class="help-block" id="purchaseInvoiceDebitNoteDescriptionHelpMe"></span> </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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
        </div>
        <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="purchaseInvoiceDebitNoteDetailIdPreview" id="purchaseInvoiceDebitNoteDetailIdPreview">
                        <div class="form-group" id="purchaseInvoiceIdDiv">
                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceIdPreview"><?php echo $leafTranslation['purchaseInvoiceIdLabel']; ?></label>
                            <div class="col-xs-8 col-sm-8 col-md-8">
                                <input class="form-control" type="text" name="purchaseInvoiceIdPreview" id="purchaseInvoiceIdPreview">
                            </div>
                        </div>
                        <div class="form-group" id="chartOfAccountIdDiv">
                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                            <div class="col-xs-8 col-sm-8 col-md-8">
                                <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                            </div>
                        </div>
                        <div class="form-group" id="purchaseInvoiceDebitNoteDetailAmountDiv">
                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDebitNoteDetailAmountPreview"><?php echo $leafTranslation['purchaseInvoiceDebitNoteDetailAmountLabel']; ?></label>
                            <div class="col-xs-8 col-sm-8 col-md-8">
                                <input class="form-control" type="text" name="purchaseInvoiceDebitNoteDetailAmountPreview" id="purchaseInvoiceDebitNoteDetailAmountPreview">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNoteDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                        <button type="button"  class="btn btn-primary" onclick="showMeModal('deleteDetailPreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6" align="left">
                <button type="button"  name="hideMaster" id="hideMaster" onclick="toggle('masterForm');"
                        class="btn btn-info"> <?php echo $t['hideUnHideTextLabel']; ?> </button>
                <button type="button"  name="trialBalance" id="trialBalance"
                        class="btn btn-info"><?php echo $t['trialBalanceTextLabel']; ?></button>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" align="right">
                <button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onclick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>');" value="<?php echo $t['newButtonLabel']; ?>">
                    <i class="glyphicon glyphicon-plus  glyphicon-white"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                    <thead>
                        <tr>
                            <th width="25px" align="center"><div align="center">#</div></th>
                    <th width="125px" align="center"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                    <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                    <th><?php echo ucfirst($leafTranslation['purchaseInvoiceDebitNoteDetailAmountLabel']); ?></th>
                    <th width="200px"><div align="center"><?php echo $t['debitTextLabel']; ?></div></th>
                    <th width="200px"><div align="center"><?php echo $t['creditTextLabel']; ?></div></th>
                    </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                        $totalDebit = 0;
                        $totalCredit = 0;
                        if ($_POST['purchaseInvoiceDebitNoteId']) {
                            if (is_array($purchaseInvoiceDebitNoteDetailArray)) {
                                $totalRecordDetail = intval(count($purchaseInvoiceDebitNoteDetailArray));
                                if ($totalRecordDetail > 0) {
                                    $counter = 0;
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                    for ($j = 0; $j < $totalRecordDetail; $j++) {
                                        $counter++;
                                        ?>
                                        <tr id="<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>">
                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                            <td valign="top" align="center"><div class="btn-group" align="center">
                                                    <input type="hidden" name="purchaseInvoiceDebitNoteDetailId[]" id="purchaseInvoiceDebitNoteDetailId<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>" value="<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>">
                                                    <input type="hidden" name="purchaseInvoiceDebitNoteId[]" id="purchaseInvoiceDebitNoteId<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>" value="<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteId']; ?>">
                                                    <button type="button"  class="btn btn-warning btn-mini" title="Edit" onclick="showFormUpdateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceDebitNoteDetailArray [$j]['purchaseInvoiceDebitNoteDetailId']); ?>')"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                    <button type="button"  class="btn btn-danger btn-mini" title="Delete" onclick="showModalDeleteDetail('<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>')"><i class="glyphicon glyphicon-trash  glyphicon-white"></i></button>
                                                    <div id="miniInfoPanel<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>"></div>
                                                </div></td>
                                            <td valign="top"><div class="form-group" id="chartOfAccountId<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>Detail">
                                                    <select name="chartOfAccountId[]" id="chartOfAccountId<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($chartOfAccountArray)) {
                                                            $totalRecord = intval(count($chartOfAccountArray));
                                                            $currentChartOfAccountTypeDescription = null;
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($i != 0) {
                                                                        if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                            echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                                                        }
                                                                    } else {
                                                                        echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                                                    }
                                                                    $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                                    if ($purchaseInvoiceDebitNoteDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = NULL;
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                <?php
                                                            }
                                                            echo "</optgroup>\n";
                                                        } else {
                                                            ?>
                                                            <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div></td>
                                            <td valign="top"><input class="form-control" style="text-align:right" type="text" name="purchaseInvoiceDebitNoteDetailAmount[]" id="purchaseInvoiceDebitNoteDetailAmount<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>"   value="<?php
                                                if (isset($purchaseInvoiceDebitNoteDetailArray) && is_array($purchaseInvoiceDebitNoteDetailArray)) {
                                                    echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailAmount'];
                                                }
                                                ?>"></td>
                                                <?php
                                                $debit = 0;
                                                $credit = 0;
                                                $x = 0;
                                                $y = 0;
                                                $d = $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailAmount'];
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
                                            <td valign="middle"><div id="debit_<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>"
                                                                     class="pull-right"><?php echo $debit; ?></div></td>
                                            <td valign="middle"><div id="credit_<?php echo $purchaseInvoiceDebitNoteDetailArray[$j]['purchaseInvoiceDebitNoteDetailId']; ?>"
                                                                     class="pull-right"><?php echo $credit; ?></div></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" valign="top" align="center"><?php $purchaseInvoiceDebitNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" valign="top" align="center"><?php $purchaseInvoiceDebitNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
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
                            <td colspan="4">&nbsp;</td>
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
    </form>
    <script type="text/javascript">
        $(document).keypress(function(e) {
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoiceDebitNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
            }
        });
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('purchaseInvoiceDebitNoteId');
            validateMeNumeric('businessPartnerId');
            validateMeNumeric('purchaseInvoiceId');

            validateMeCurrency('purchaseInvoiceDebitNoteAmount');
            validateMeAlphaNumeric('referenceNumber');
            $('#purchaseInvoiceDebitNoteDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('purchaseInvoiceDebitNoteDescription');
            validateMeNumericRange('purchaseInvoiceDebitNoteDetailId');
            validateMeNumericRange('chartOfAccountId');
            validateMeCurrencyRange('purchaseInvoiceDebitNoteDetailAmount');

        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/accountPayable/javascript/purchaseInvoiceDebitNotePost.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>