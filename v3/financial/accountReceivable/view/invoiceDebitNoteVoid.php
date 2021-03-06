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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceDebitNoteController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceDebitNoteDetailController.php");
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

$translator->setCurrentTable(array('invoiceDebitNote', 'invoiceDebitNoteDetail'));
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
$invoiceDebitNoteArray = array();
$businessPartnerArray = array();
$invoiceArray = array();
$_POST['from'] = 'invoiceDebitNoteVoid.php';
$_GET['from'] = 'invoiceDebitNoteVoid.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoiceDebitNote = new \Core\Financial\AccountReceivable\InvoiceDebitNote\Controller\InvoiceDebitNoteClass();
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
            $invoiceDebitNote->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoiceDebitNote->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoiceDebitNote->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoiceDebitNote->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoiceDebitNote->setStartDay($start[2]);
            $invoiceDebitNote->setStartMonth($start[1]);
            $invoiceDebitNote->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoiceDebitNote->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoiceDebitNote->setEndDay($start[2]);
            $invoiceDebitNote->setEndMonth($start[1]);
            $invoiceDebitNote->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoiceDebitNote->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoiceDebitNote->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoiceDebitNote->setServiceOutput('html');
        $invoiceDebitNote->setLeafId($leafId);
        $invoiceDebitNote->execute();
        $businessPartnerArray = $invoiceDebitNote->getBusinessPartner();
        $invoiceArray = $invoiceDebitNote->getInvoice();
        if ($_POST['method'] == 'read') {
            $invoiceDebitNote->setStart($offset);
            $invoiceDebitNote->setLimit($limit); // normal system don't like paging..  
            $invoiceDebitNote->setPageOutput('html');
            $invoiceDebitNoteArray = $invoiceDebitNote->read();
            if (isset($invoiceDebitNoteArray [0]['firstRecord'])) {
                $firstRecord = $invoiceDebitNoteArray [0]['firstRecord'];
            }
            if (isset($invoiceDebitNoteArray [0]['nextRecord'])) {
                $nextRecord = $invoiceDebitNoteArray [0]['nextRecord'];
            }
            if (isset($invoiceDebitNoteArray [0]['previousRecord'])) {
                $previousRecord = $invoiceDebitNoteArray [0]['previousRecord'];
            }
            if (isset($invoiceDebitNoteArray [0]['lastRecord'])) {
                $lastRecord = $invoiceDebitNoteArray [0]['lastRecord'];
                $endRecord = $invoiceDebitNoteArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoiceDebitNote->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceDebitNoteArray [0]['total'])) {
                $total = $invoiceDebitNoteArray [0]['total'];
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
                                <li> <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')"> <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007 </a> </li>
                                <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')"> <i class ="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
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
                            <label for="queryWidget"></label><div class="input-group"><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                    echo $_POST['query'];
                                }
                                ?>"><span class="input-group-addon"><img src="./images/icons/magnifier.png" id="searchTextDateImage"></span></div><br>
                            <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                   class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                   echo $invoiceDebitNote->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchString" id="clearSearchString" value="<?php echo $t['clearButtonLabel']; ?>"
                                   class="btn btn-info btn-block" onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $invoiceDebitNote->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">

                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
                                </tr>
                            </table><div class="input-group"><input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                if (isset($_POST['dateRangeStart'])) {
                                    echo $_POST['dateRangeStart'];
                                }
                                ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="startDateImage"></span></div><br>
                            <div class="input-group"><input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                if (isset($_POST['dateRangeEnd'])) {
                                    echo $_POST['dateRangeEnd'];
                                }
                                ?>" onClick="topPage(175);" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="endDateImage"></span></div><br>
                            <input type="button"  name="searchDate" id="searchDate" value="<?php echo $t['searchButtonLabel']; ?>"
                                   class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                   echo $invoiceDebitNote->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchDate" id="clearSearchDate" value="<?php echo $t['clearButtonLabel']; ?>"
                                   class="btn btn-info btn-block" onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $invoiceDebitNote->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                        </div>
                    </div>
                </div>
                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['viewRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="invoiceDebitNoteIdPreview" id="invoiceDebitNoteIdPreview">
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
                                        <div class="form-group" id="invoiceDebitNoteAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDebitNoteAmountPreview"><?php echo $leafTranslation['invoiceDebitNoteAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDebitNoteAmountPreview" id="invoiceDebitNoteAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="referenceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="referenceNumberPreview"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview" id="referenceNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceDebitNoteDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDebitNoteDatePreview"><?php echo $leafTranslation['invoiceDebitNoteDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDebitNoteDatePreview" id="invoiceDebitNoteDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceDebitNoteDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDebitNoteDescriptionPreview"><?php echo $leafTranslation['invoiceDebitNoteDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDebitNoteDescriptionPreview" id="invoiceDebitNoteDescriptionPreview">
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

                                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['invoiceDebitNoteDateLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['invoiceDebitNoteDescriptionLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['invoiceDebitNoteAmountLabel']); ?></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>

                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            $totalInvoiceDebitNote = 0;
                                            if (is_array($invoiceDebitNoteArray)) {
                                                $totalRecord = intval(count($invoiceDebitNoteArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($invoiceDebitNoteArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($invoiceDebitNoteArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td valign="top" align="center"><div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-warning btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($invoiceDebitNoteArray [$i]['invoiceDebitNoteId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                </div></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($invoiceDebitNoteArray[$i]['documentNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($invoiceDebitNoteArray[$i]['documentNumber']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceDebitNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($invoiceDebitNoteArray[$i]['documentNumber']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceDebitNoteArray[$i]['documentNumber']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceDebitNoteArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceDebitNoteArray[$i]['documentNumber'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>

                                                            <?php
                                                            if (isset($invoiceDebitNoteArray[$i]['invoiceDebitNoteDate'])) {
                                                                $valueArray = $invoiceDebitNoteArray[$i]['invoiceDebitNoteDate'];
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
                                                                    if (isset($invoiceDebitNoteArray[$i]['businessPartnerCompany'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($invoiceDebitNoteArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceDebitNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($invoiceDebitNoteArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceDebitNoteArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceDebitNoteArray[$i]['businessPartnerCompany'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>

                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceDebitNoteArray[$i]['invoiceDebitNoteDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            $d = $invoiceDebitNoteArray[$i]['invoiceDebitNoteAmount'];
                                                            $totalInvoiceDebitNote += $d;
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($invoiceDebitNoteArray[$i]['invoiceDebitNoteAmount']);
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
                                                                    if (isset($invoiceDebitNoteArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($invoiceDebitNoteArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceDebitNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($invoiceDebitNoteArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceDebitNoteArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $invoiceDebitNoteArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceDebitNoteArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceDebitNoteArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?></div></td>
                                                            <?php
                                                            if (isset($invoiceDebitNoteArray[$i]['executeTime'])) {
                                                                $valueArray = $invoiceDebitNoteArray[$i]['executeTime'];
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
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="9" valign="top" align="center"><?php $invoiceDebitNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="9" valign="top" align="center"><?php $invoiceDebitNote->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="9" valign="top" align="center"><?php $invoiceDebitNote->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                                                            $d = $a->format($totalInvoiceDebitNote);
                                                        } else {
                                                            $d = number_format($totalInvoiceDebitNote) . " You can assign Currency Format ";
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
                        <div class="col-xs-12 col-sm-12 col-md-12 pull-left" align="left">
                            <?php $navigation->pagenationv4($offset); ?>
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
    $invoiceDebitNoteDetail = new \Core\Financial\AccountReceivable\InvoiceDebitNoteDetail\Controller\InvoiceDebitNoteDetailClass();
    $invoiceDebitNoteDetail->setServiceOutput('html');
    $invoiceDebitNoteDetail->setLeafId($leafId);
    $invoiceDebitNoteDetail->execute();
    $invoiceArray = $invoiceDebitNoteDetail->getInvoice();
    $chartOfAccountArray = $invoiceDebitNoteDetail->getChartOfAccount();
    $businessPartnerArray = $invoiceDebitNoteDetail->getBusinessPartner();
    $invoiceDebitNoteDetail->setStart(0);
    $invoiceDebitNoteDetail->setLimit(999999); // normal system don't like paging..  
    $invoiceDebitNoteDetail->setPageOutput('html');
    if ($_POST['invoiceDebitNoteId']) {
        $invoiceDebitNoteDetailArray = $invoiceDebitNoteDetail->read();
    }
    ?>
    <form class="form-horizontal">
        <input type="hidden" name="invoiceDebitNoteId" id="invoiceDebitNoteId" value="<?php
        if (isset($_POST['invoiceDebitNoteId'])) {
            echo $_POST['invoiceDebitNoteId'];
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
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $invoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $invoiceDebitNoteDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
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
                                            <select name="businessPartnerId" id="businessPartnerId" class="form-control  chzn-select" readonly disabled>
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
                                                            if (isset($invoiceDebitNoteArray[0]['businessPartnerId'])) {
                                                                if ($invoiceDebitNoteArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
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
                                            <select name="invoiceId" id="invoiceId" class="form-control  chzn-select" readonly disabled>
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
                                                            if (isset($invoiceDebitNoteArray[0]['invoiceId'])) {
                                                                if ($invoiceDebitNoteArray[0]['invoiceId'] == $invoiceArray[$i]['invoiceId']) {
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
                                                       class="form-control" disabled readonly
                                                       value="<?php
                                                       if (isset($invoiceDebitNoteArray) && is_array($invoiceDebitNoteArray)) {
                                                           if (isset($invoiceDebitNoteArray[0]['documentNumber'])) {
                                                               echo htmlentities($invoiceDebitNoteArray[0]['documentNumber']);
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

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    if (isset($invoiceDebitNoteArray) && is_array($invoiceDebitNoteArray)) {

                                        if (isset($invoiceDebitNoteArray[0]['invoiceDebitNoteDate'])) {
                                            $valueArray = $invoiceDebitNoteArray[0]['invoiceDebitNoteDate'];
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
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="invoiceDebitNoteDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDebitNoteDate"><strong><?php echo ucfirst($leafTranslation['invoiceDebitNoteDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceDebitNoteDate" id="invoiceDebitNoteDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" readonly disabled>
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceDebitNoteDateImage"></span></div>
                                            <span class="help-block" id="invoiceDebitNoteDateHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="invoiceDebitNoteAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDebitNoteAmount"><strong><?php echo ucfirst($leafTranslation['invoiceDebitNoteAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceDebitNoteAmount" id="invoiceDebitNoteAmount"  readonly disabled value="<?php
                                                if (isset($invoiceDebitNoteArray) && is_array($invoiceDebitNoteArray)) {
                                                    if (isset($invoiceDebitNoteArray[0]['invoiceDebitNoteAmount'])) {
                                                        echo htmlentities($invoiceDebitNoteArray[0]['invoiceDebitNoteAmount']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceDebitNoteAmountHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="referenceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="referenceNumber"><strong><?php echo ucfirst($leafTranslation['referenceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber"  readonly disabled value="<?php
                                            if (isset($invoiceDebitNoteArray) && is_array($invoiceDebitNoteArray)) {
                                                if (isset($invoiceDebitNoteArray[0]['referenceNumber'])) {
                                                    echo htmlentities($invoiceDebitNoteArray[0]['referenceNumber']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="referenceNumberHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    <textarea class="form-control" name="invoiceDebitNoteDescription" id="invoiceDebitNoteDescription"  readonly disabled>
                                        <?php
                                        if (isset($invoiceDebitNoteArray[0]['invoiceDebitNoteDescription'])) {
                                            echo htmlentities($invoiceDebitNoteArray[0]['invoiceDebitNoteDescription']);
                                        }
                                        ?></textarea>
                                    <span class="help-block" id="invoiceDebitNoteDescriptionHelpMe"></span> </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">

                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th width="25px" align="center"><div align="center">#</div></th>
                        <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                        <th><?php echo ucfirst($leafTranslation['invoiceDebitNoteDetailAmountLabel']); ?></th>
                        <th width="200px"><div align="center"><?php echo $t['debitTextLabel']; ?></div></th>
                        <th width="200px"><div align="center"><?php echo $t['creditTextLabel']; ?></div></th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            $totalDebit = 0;
                            $totalCredit = 0;
                            if ($_POST['invoiceDebitNoteId']) {
                                if (is_array($invoiceDebitNoteDetailArray)) {
                                    $totalRecordDetail = intval(count($invoiceDebitNoteDetailArray));
                                    if ($totalRecordDetail > 0) {
                                        $counter = 0;
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                                            $counter++;
                                            ?>
                                            <tr id="<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>">
                                                <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>

                                                <td valign="top"  class="form-group" id="chartOfAccountId<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>Detail"><select name="chartOfAccountId[]" id="chartOfAccountId<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                        <option value=""></option>
                                                        <?php
                                                        $currentChartOfAccountTypeDescription = null;
                                                        if (is_array($chartOfAccountArray)) {
                                                            $totalRecord = intval(count($chartOfAccountArray));
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
                                                                    if ($invoiceDebitNoteDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
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
                                                <td valign="top"  class="form-group" id="invoiceDebitNoteDetailAmount<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="invoiceDebitNoteDetailAmount[]" id="invoiceDebitNoteDetailAmount<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>"   value="<?php
                                                    if (isset($invoiceDebitNoteDetailArray) && is_array($invoiceDebitNoteDetailArray)) {
                                                        echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailAmount'];
                                                    }
                                                    ?>"></td>
                                                    <?php
                                                    $debit = 0;
                                                    $credit = 0;
                                                    $x = 0;
                                                    $y = 0;
                                                    $d = $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailAmount'];
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
                                                <td valign="middle"><div id="debit_<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>"
                                                                         class="pull-right"><?php echo $debit; ?></div></td>
                                                <td valign="middle"><div id="credit_<?php echo $invoiceDebitNoteDetailArray[$j]['invoiceDebitNoteDetailId']; ?>"
                                                                         class="pull-right"><?php echo $credit; ?></div></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" valign="top" align="center"><?php $invoiceDebitNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" valign="top" align="center"><?php $invoiceDebitNoteDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
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
    </div></form>
    <script type="text/javascript">
        $(document).keypress(function(e) {
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $invoiceDebitNote->getControllerPath(); ?>', '<?php echo $invoiceDebitNote->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    return false;
                    break;
            }
        });
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            $('#invoiceDebitNoteDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoiceDebitNoteVoid.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>