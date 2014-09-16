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
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseInvoiceCreditNoteController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseInvoiceCreditNoteDetailController.php");
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

$translator->setCurrentTable(array('purchaseInvoiceCreditNote', 'purchaseInvoiceCreditNoteDetail'));
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
$purchaseInvoiceCreditNoteArray = array();
$businessPartnerArray = array();
$purchaseInvoiceArray = array();
$_POST['from'] = 'purchaseInvoiceCreditNotePost.php';
$_GET['from'] = 'purchaseInvoiceCreditNotePost.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $purchaseInvoiceCreditNote = new \Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Controller\PurchaseInvoiceCreditNoteClass();
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
            $purchaseInvoiceCreditNote->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $purchaseInvoiceCreditNote->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $purchaseInvoiceCreditNote->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $purchaseInvoiceCreditNote->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $purchaseInvoiceCreditNote->setStartDay($start[2]);
            $purchaseInvoiceCreditNote->setStartMonth($start[1]);
            $purchaseInvoiceCreditNote->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $purchaseInvoiceCreditNote->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $purchaseInvoiceCreditNote->setEndDay($start[2]);
            $purchaseInvoiceCreditNote->setEndMonth($start[1]);
            $purchaseInvoiceCreditNote->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $purchaseInvoiceCreditNote->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $purchaseInvoiceCreditNote->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $purchaseInvoiceCreditNote->setServiceOutput('html');
        $purchaseInvoiceCreditNote->setLeafId($leafId);
        $purchaseInvoiceCreditNote->execute();
        $businessPartnerArray = $purchaseInvoiceCreditNote->getBusinessPartner();
        $purchaseInvoiceArray = $purchaseInvoiceCreditNote->getPurchaseInvoice();
        if ($_POST['method'] == 'read') {
            $purchaseInvoiceCreditNote->setStart($offset);
            $purchaseInvoiceCreditNote->setLimit($limit); // normal system don't like paging..  
            $purchaseInvoiceCreditNote->setPageOutput('html');
            $purchaseInvoiceCreditNoteArray = $purchaseInvoiceCreditNote->read();
            if (isset($purchaseInvoiceCreditNoteArray [0]['firstRecord'])) {
                $firstRecord = $purchaseInvoiceCreditNoteArray [0]['firstRecord'];
            }
            if (isset($purchaseInvoiceCreditNoteArray [0]['nextRecord'])) {
                $nextRecord = $purchaseInvoiceCreditNoteArray [0]['nextRecord'];
            }
            if (isset($purchaseInvoiceCreditNoteArray [0]['previousRecord'])) {
                $previousRecord = $purchaseInvoiceCreditNoteArray [0]['previousRecord'];
            }
            if (isset($purchaseInvoiceCreditNoteArray [0]['lastRecord'])) {
                $lastRecord = $purchaseInvoiceCreditNoteArray [0]['lastRecord'];
                $endRecord = $purchaseInvoiceCreditNoteArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($purchaseInvoiceCreditNote->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($purchaseInvoiceCreditNoteArray [0]['total'])) {
                $total = $purchaseInvoiceCreditNoteArray [0]['total'];
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
                        <button type="button"  class="delete btn btn-primary" onclick="postGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-wrench"></i> </button>

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
                                    <h4 class="modal-title"><?php echo ucwords($t['viewRecordMessageLabel']); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="purchaseInvoiceCreditNoteIdPreview" id="purchaseInvoiceCreditNoteIdPreview">
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
                                        <div class="form-group" id="purchaseInvoiceAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceAmountPreview"><?php echo $leafTranslation['purchaseInvoiceAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceAmountPreview" id="purchaseInvoiceAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="referenceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="referenceNumberPreview"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview" id="referenceNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceCreditNoteDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceCreditNoteDatePreview"><?php echo $leafTranslation['purchaseInvoiceCreditNoteDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceCreditNoteDatePreview" id="purchaseInvoiceCreditNoteDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceDescriptionPreview"><?php echo $leafTranslation['purchaseInvoiceDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceDescriptionPreview" id="purchaseInvoiceDescriptionPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
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
                                    <th width="150px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['purchaseInvoiceCreditNoteDateLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['purchaseInvoiceDescriptionLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['purchaseInvoiceCreditNoteAmountLabel']); ?></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onclick="toggleChecked(this.checked)"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($purchaseInvoiceCreditNoteArray)) {
                                                $totalRecord = intval(count($purchaseInvoiceCreditNoteArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($purchaseInvoiceCreditNoteArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($purchaseInvoiceCreditNoteArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td valign="top" align="center"><?php if ($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteCode'] != 'UNBL') { ?><div class="btn-group" align="center">
                                                                        <?php if ($leafAccess['leafAccessPostValue'] == 1) { ?>
                                                                            <button type="button"  class="btn btn-primary btn-sm" title="Post" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceCreditNoteArray [$i]['purchaseInvoiceCreditNoteId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-wrench glyphicon-white"></i></button>
                                                                        <?php } ?>
                                                                        <?php if ($leafAccess['leafAccessEditValue'] == 1) { ?>
                                                                            <button type="button"  class="btn btn-warning btn-sm" title="View" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceCreditNoteArray [$i]['purchaseInvoiceCreditNoteId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <?php } ?>
                                                                    </div><?php } ?></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseInvoiceCreditNoteArray[$i]['documentNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceCreditNoteArray[$i]['documentNumber']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceCreditNoteArray[$i]['documentNumber']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceCreditNoteArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceCreditNoteArray[$i]['documentNumber'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            if (isset($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDate'])) {
                                                                $valueArray = $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDate'];
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
                                                                    if (isset($purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceCreditNoteArray[$i]['businessPartnerCompany'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>


                                                            <?php
                                                            $d = $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteAmount'];
                                                            $totalPurchaseInvoiceCreditNote += $d;
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteAmount']);
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
                                                                    if (isset($purchaseInvoiceCreditNoteArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseInvoiceCreditNoteArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseInvoiceCreditNoteArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceCreditNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceCreditNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceCreditNoteArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceCreditNoteArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?></div></td>
                                                            <?php
                                                            if (isset($purchaseInvoiceCreditNoteArray[$i]['executeTime'])) {
                                                                $valueArray = $purchaseInvoiceCreditNoteArray[$i]['executeTime'];
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
                                                            if ($purchaseInvoiceCreditNoteArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td valign="top"><?php if ($purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteCode'] != 'UNBL') { ?><input class="form-control" style="display:none;" type="checkbox" name="purchaseInvoiceCreditNoteId[]"  value="<?php echo $purchaseInvoiceCreditNoteArray[$i]['purchaseInvoiceCreditNoteId']; ?>">
                                                                    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $purchaseInvoiceCreditNoteArray[$i]['isDelete']; ?>"><?php } ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="13" valign="top" align="center"><?php $purchaseInvoiceCreditNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="13" valign="top" align="center"><?php $purchaseInvoiceCreditNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="13" valign="top" align="center"><?php $purchaseInvoiceCreditNote->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                                                            $d = $a->format($totalPurchaseInvoiceCreditNote);
                                                        } else {
                                                            $d = number_format($totalPurchaseInvoiceCreditNote) . " You can assign Currency Format ";
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
                            <?php if ($leafAccess['leafAccessPostValue'] == 1) { ?>
                                <button type="button"  class="delete btn btn-primary" onclick="postGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-wrench"></i> </button>
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
    $purchaseInvoiceCreditNoteDetail = new \Core\Financial\AccountPayable\PurchaseInvoiceCreditNoteDetail\Controller\PurchaseInvoiceCreditNoteDetailClass();
    $purchaseInvoiceCreditNoteDetail->setServiceOutput('html');
    $purchaseInvoiceCreditNoteDetail->setLeafId($leafId);
    $purchaseInvoiceCreditNoteDetail->execute();
    $purchaseInvoiceArray = $purchaseInvoiceCreditNoteDetail->getPurchaseInvoice();
    $chartOfAccountArray = $purchaseInvoiceCreditNoteDetail->getChartOfAccount();
    $businessPartnerArray = $purchaseInvoiceCreditNoteDetail->getBusinessPartner();
    $purchaseInvoiceCreditNoteDetail->setStart(0);
    $purchaseInvoiceCreditNoteDetail->setLimit(999999); // normal system don't like paging..  
    $purchaseInvoiceCreditNoteDetail->setPageOutput('html');
    if ($_POST['purchaseInvoiceCreditNoteId']) {
        $purchaseInvoiceCreditNoteDetailArray = $purchaseInvoiceCreditNoteDetail->read();
    }
    ?>
    <form class="form-horizontal">
        <input type="hidden" name="purchaseInvoiceCreditNoteId" id="purchaseInvoiceCreditNoteId" value="<?php
        if (isset($_POST['purchaseInvoiceCreditNoteId'])) {
            echo $_POST['purchaseInvoiceCreditNoteId'];
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
                    <div class="panel panel-primary">
                        <div class="panel panel-info" id="masterForm">
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $purchaseInvoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $purchaseInvoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"> </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="businessPartnerIdForm">
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceIdForm">
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
                                                            if (isset($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceId'])) {
                                                                if ($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceId'] == $purchaseInvoiceArray[$i]['purchaseInvoiceId']) {
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumber"><strong><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                <?php
                                                if (!isset($_POST['purchaseInvoiceCreditNoteId'])) {
                                                    echo "disabled";
                                                }
                                                ?>
                                                       class=" form-control  <?php
                                                       if (!isset($_POST['purchaseInvoiceCreditNoteId'])) {
                                                           echo "disabled";
                                                       }
                                                       ?>"
                                                       value="<?php
                                                       if (isset($documentNumberArray) && is_array($purchaseInvoiceCreditNoteArray)) {
                                                           if (isset($purchaseInvoiceCreditNoteArray[0]['documentNumber'])) {
                                                               echo htmlentities($purchaseInvoiceCreditNoteArray[0]['documentNumber']);
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceAmount"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceAmount" id="purchaseInvoiceAmount"  readonly value="<?php
                                                if (isset($purchaseInvoiceCreditNoteArray) && is_array($purchaseInvoiceCreditNoteArray)) {
                                                    if (isset($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceAmount'])) {
                                                        echo htmlentities($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceAmount']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="purchaseInvoiceAmountHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="referenceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="referenceNumber"><strong><?php echo ucfirst($leafTranslation['referenceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber"  readonly value="<?php
                                            if (isset($purchaseInvoiceCreditNoteArray) && is_array($purchaseInvoiceCreditNoteArray)) {
                                                if (isset($purchaseInvoiceCreditNoteArray[0]['referenceNumber'])) {
                                                    echo htmlentities($purchaseInvoiceCreditNoteArray[0]['referenceNumber']);
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
                                    if (isset($purchaseInvoiceCreditNoteArray) && is_array($purchaseInvoiceCreditNoteArray)) {

                                        if (isset($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceCreditNoteDate'])) {
                                            $valueArray = $purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceCreditNoteDate'];
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceCreditNoteDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceCreditNoteDate"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceCreditNoteDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceCreditNoteDate" id="purchaseInvoiceCreditNoteDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="purchaseInvoiceCreditNoteDateImage"></span></div>
                                            <span class="help-block" id="purchaseInvoiceCreditNoteDateHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceDescriptionForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDescription"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceDescriptionLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea class="form-control" name="purchaseInvoiceDescription" id="purchaseInvoiceDescription"  readonly><?php
                                                if (isset($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceDescription'])) {
                                                    echo htmlentities($purchaseInvoiceCreditNoteArray[0]['purchaseInvoiceDescription']);
                                                }
                                                ?></textarea>
                                            <span class="help-block" id="purchaseInvoiceDescriptionHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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
                        <input type="hidden" name="purchaseInvoiceCreditNoteDetailIdPreview" id="purchaseInvoiceCreditNoteDetailIdPreview">
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
                        <div class="form-group" id="purchaseInvoiceCreditNoteDetailAmountDiv">
                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceCreditNoteDetailAmountPreview"><?php echo $leafTranslation['purchaseInvoiceCreditNoteDetailAmountLabel']; ?></label>
                            <div class="col-xs-8 col-sm-8 col-md-8">
                                <input class="form-control" type="text" name="purchaseInvoiceCreditNoteDetailAmountPreview" id="purchaseInvoiceCreditNoteDetailAmountPreview">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNoteDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
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
                <button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onclick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>');" value="<?php echo $t['newButtonLabel']; ?>">
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
                    <th><?php echo ucfirst($leafTranslation['purchaseInvoiceCreditNoteDetailAmountLabel']); ?></th>
                    <th width="200px"><div align="center"><?php echo $t['debitTextLabel']; ?></div></th>
                    <th width="200px"><div align="center"><?php echo $t['creditTextLabel']; ?></div></th>
                    </tr>
                    <tr>
                        </thead>
                    <tbody id="tableBody">
                        <?php
                        $totalDebit = 0;
                        $totalCredit = 0;
                        if ($_POST['purchaseInvoiceCreditNoteId']) {
                            if (is_array($purchaseInvoiceCreditNoteDetailArray)) {
                                $totalRecordDetail = intval(count($purchaseInvoiceCreditNoteDetailArray));
                                if ($totalRecordDetail > 0) {
                                    $counter = 0;
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                    for ($j = 0; $j < $totalRecordDetail; $j++) {
                                        $counter++;
                                        ?>
                                        <tr id="<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>">
                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                            <td valign="top" align="center"><div class="btn-group" align="center">
                                                    <input type="hidden" name="purchaseInvoiceCreditNoteDetailId[]" id="purchaseInvoiceCreditNoteDetailId<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>" value="<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>">
                                                    <input type="hidden" name="purchaseInvoiceCreditNoteId[]" id="purchaseInvoiceCreditNoteId<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>" value="<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteId']; ?>">
                                                    <button type="button"  class="btn btn-warning btn-mini" title="Edit" onclick="showFormUpdateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceCreditNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceCreditNoteDetailArray [$j]['purchaseInvoiceCreditNoteDetailId']); ?>')"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                    <button type="button"  class="btn btn-danger btn-mini" title="Delete" onclick="showModalDeleteDetail('<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>')"><i class="glyphicon glyphicon-trash  glyphicon-white"></i></button>
                                                    <div id="miniInfoPanel<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>"></div>
                                                </div></td>
                                            <td valign="top"  class="form-group" id="chartOfAccountId<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>Detail"><select name="chartOfAccountId[]" id="chartOfAccountId<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>" class="form-control chzn-select inpu-sm">
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
                                                                if ($purchaseInvoiceCreditNoteDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
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
                                            <td valign="top"  class="form-group" id="purchaseInvoiceCreditNoteDetailAmount<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="purchaseInvoiceCreditNoteDetailAmount[]" id="purchaseInvoiceCreditNoteDetailAmount<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>"   value="<?php
                                                if (isset($purchaseInvoiceCreditNoteDetailArray) && is_array($purchaseInvoiceCreditNoteDetailArray)) {
                                                    echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailAmount'];
                                                }
                                                ?>"></td>
                                                <?php
                                                $debit = 0;
                                                $credit = 0;
                                                $x = 0;
                                                $y = 0;
                                                $d = $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailAmount'];
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
                                            <td valign="middle"><div id="debit_<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>"
                                                                     class="pull-right"><?php echo $debit; ?></div></td>
                                            <td valign="middle"><div id="credit_<?php echo $purchaseInvoiceCreditNoteDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>"
                                                                     class="pull-right"><?php echo $credit; ?></div></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" valign="top" align="center"><?php $purchaseInvoiceCreditNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" valign="top" align="center"><?php $purchaseInvoiceCreditNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
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
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoiceCreditNote->getControllerPath(); ?>', '<?php echo $purchaseInvoiceCreditNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
            }


        });
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('purchaseInvoiceCreditNoteId');
            validateMeNumeric('businessPartnerId');
            validateMeNumeric('purchaseInvoiceId');

            validateMeCurrency('purchaseInvoiceAmount');
            validateMeAlphaNumeric('referenceNumber');
            $('#purchaseInvoiceCreditNoteDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('purchaseInvoiceDescription');
            validateMeNumericRange('purchaseInvoiceCreditNoteDetailId');
            validateMeNumericRange('purchaseInvoiceId');
            validateMeNumericRange('purchaseInvoiceCreditNoteId');
            validateMeNumericRange('chartOfAccountId');
            validateMeCurrencyRange('purchaseInvoiceCreditNoteDetailAmount');

        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/accountPayable/javascript/purchaseInvoiceCreditNotePost.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>