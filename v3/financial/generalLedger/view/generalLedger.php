<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from Linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/generalLedgerController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
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

$translator->setCurrentTable('generalLedger');

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
$generalLedgerArray = array();
$_POST['from'] = 'generalLedger.php';
$_GET['from'] = 'generalLedger.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $generalLedger = new \Core\Financial\GeneralLedger\GeneralLedger\Controller\GeneralLedgerClass();
        define('LIMIT', 1000);
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
            $generalLedger->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $generalLedger->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $generalLedger->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $generalLedger->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $generalLedger->setStartDay($start[2]);
            $generalLedger->setStartMonth($start[1]);
            $generalLedger->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $generalLedger->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $generalLedger->setEndDay($start[2]);
            $generalLedger->setEndMonth($start[1]);
            $generalLedger->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $generalLedger->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $generalLedger->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $generalLedger->setServiceOutput('html');
        $generalLedger->setLeafId($leafId);
        $generalLedger->execute();
        if ($_POST['method'] == 'read') {
            $generalLedger->setStart($offset);
            $generalLedger->setLimit($limit); // normal system don't like paging..
            $generalLedger->setPageOutput('html');
            $generalLedgerArray = $generalLedger->read();
            if (isset($generalLedgerArray [0]['firstRecord'])) {
                $firstRecord = $generalLedgerArray [0]['firstRecord'];
            }
            if (isset($generalLedgerArray [0]['nextRecord'])) {
                $nextRecord = $generalLedgerArray [0]['nextRecord'];
            }
            if (isset($generalLedgerArray [0]['previousRecord'])) {
                $previousRecord = $generalLedgerArray [0]['previousRecord'];
            }
            if (isset($generalLedgerArray [0]['lastRecord'])) {
                $lastRecord = $generalLedgerArray [0]['lastRecord'];
                $endRecord = $generalLedgerArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($generalLedger->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($generalLedgerArray [0]['total'])) {
                $total = $generalLedgerArray [0]['total'];
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
</script><?php
if (isset($_POST['method']) && isset($_POST['type'])) {
    if ($_POST['method'] == 'read' && $_POST['type'] == 'list') {
        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?php
                $template->setLayout(1);
                echo $template->breadcrumb(
                        $applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId
                );
                ?>
            </div>
        </div>
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div id="content" style="opacity: 1;">
            <div class="row">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <div class="pull-left">
                        <a href="javascript:void(0)" onClick="filterCategory(1)"><span class="label label-default"><?php
                                echo ucfirst(
                                        $t['assetTextLabel']
                                );
                                ?></span></a>&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onClick="filterCategory(2)"><span class="label label-primary"><?php
                                echo ucfirst(
                                        $t['liabilityTextLabel']
                                );
                                ?></span></a>&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onClick="filterCategory(3)"><span class="label label-success"><?php
                                echo ucfirst(
                                        $t['incomeTextLabel']
                                );
                                ?></span></a>&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onClick="filterCategory(4)"><span class="label label-info"><?php
                                echo ucfirst(
                                        $t['expensesTextLabel']
                                );
                                ?></span></a>&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onClick="filterCategory(5)"><span class="label label-info"><?php
                                echo ucfirst(
                                        $t['returnEarningTextLabel']
                                );
                                ?></span></a>&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onClick="filterReport(1)"><span class="label label-warning"><?php
                                echo ucfirst(
                                        $t['balanceSheetTextLabel']
                                );
                                ?></span></a>&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0)" onClick="filterReport(2)"><span class="label label-danger"><?php
                                echo ucfirst(
                                        $t['profitAndLossTextLabel']
                                );
                                ?></span></a>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button" >
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" >
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $generalLedger->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $generalLedger->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;
                </div>
            </div>
            <div class="row">
                <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-forizontal">
                                <label for="queryWidget"></label><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                    echo $_POST['query'];
                                }
                                ?>"> <br>
                                <input type="button"  name="searchString" id="searchString"
                                       value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $generalLedger->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $generalLedger->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"> <br>

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $generalLedger->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                            echo date(
                                                    'd-m-Y'
                                            );
                                            ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $generalLedger->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['weekTextLabel'] ?>">
                                        </td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $generalLedger->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png"
                                                 alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $generalLedger->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $generalLedger->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $generalLedger->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                </table>


                                <div>
                                    <label for="dateRangeStart"></label><input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                    <label for="dateRangeEnd"></label><input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" onClick="topPage(175);"><br>
                                    <input type="button"  name="searchDate" id="searchDate"
                                           value="<?php echo $t['searchButtonLabel']; ?>"
                                           class="btn btn-warning btn-block"
                                           onClick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                           echo $generalLedger->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                           onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $generalLedger->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal"
                                            aria-hidden="false">&times;</button>
                                    <h4 class="modal-title">&nbsp;</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="generalLedgerIdPreview" id="generalLedgerIdPreview">

                                        <div class="form-group" id="journalNumberDiv">
                                            <label for="journalNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['journalNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="journalNumberPreview"
                                                       id="journalNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label for="documentNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['documentNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="documentNumberPreview"
                                                       id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="generalLedgerTitleDiv">
                                            <label for="generalLedgerTitlePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['generalLedgerTitleLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="generalLedgerTitlePreview"
                                                       id="generalLedgerTitlePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="generalLedgerDescriptionDiv">
                                            <label for="generalLedgerDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['generalLedgerDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="generalLedgerDescriptionPreview" id="generalLedgerDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="generalLedgerDateDiv">
                                            <label for="generalLedgerDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['generalLedgerDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="generalLedgerDatePreview"
                                                       id="generalLedgerDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="countryCurrencyCodeDiv">
                                            <label for="countryCurrencyCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['countryCurrencyCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="countryCurrencyCodePreview"
                                                       id="countryCurrencyCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="transactionTypeCodeDiv">
                                            <label for="transactionTypeCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['transactionTypeCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="transactionTypeCodePreview"
                                                       id="transactionTypeCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="transactionTypeDescriptionDiv">
                                            <label for="transactionTypeDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['transactionTypeDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="transactionTypeDescriptionPreview"
                                                       id="transactionTypeDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="foreignAmountDiv">
                                            <label for="foreignAmountPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['foreignAmountLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="foreignAmountPreview"
                                                       id="foreignAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="localAmountDiv">
                                            <label for="localAmountPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['localAmountLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="localAmountPreview"
                                                       id="localAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountCategoryDescriptionDiv">
                                            <label for="chartOfAccountCategoryDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountCategoryDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountCategoryDescriptionPreview"
                                                       id="chartOfAccountCategoryDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountTypeDescriptionDiv">
                                            <label for="chartOfAccountTypeDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountTypeDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountTypeDescriptionPreview"
                                                       id="chartOfAccountTypeDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountNumberDiv">
                                            <label for="chartOfAccountNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="chartOfAccountNumberPreview"
                                                       id="chartOfAccountNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountDescriptionDiv">
                                            <label for="chartOfAccountDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountDescriptionPreview"
                                                       id="chartOfAccountDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerDescriptionDiv">
                                            <label for="businessPartnerDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerDescriptionPreview"
                                                       id="businessPartnerDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="executeNameDiv">
                                            <label for="executeNamePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['executeNameLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="executeNamePreview"
                                                       id="executeNamePreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  onClick="showMeModal('deletePreview', 0);" class="btn btn-default"
                                            data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                    <thead>
                                        <tr class="info">
                                            <th width="25px" align="center">
                                    <div align="center">#</div>
                                    </th>
                                    <th width="25px" align="center">
                                    <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['journalNumberLabel']); ?></th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['generalLedgerDateLabel']); ?></div>
                                    </th>
                                    <th width="150px"><?php echo ucwords($leafTranslation['businessPartnerDescriptionLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['chartOfAccountDescriptionLabel']); ?></th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($t['debitTextLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($t['creditTextLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($t['generalLedgerAmountLabel']); ?></div>
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($generalLedgerArray)) {
                                                $totalRecord = intval(count($generalLedgerArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($generalLedgerArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($generalLedgerArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td vAlign="top" align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                            </td>
                                                            <td vAlign="top">
                                                                <div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-danger btn-xs" title="View" onClick="showModalDelete('<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['generalLedgerId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['journalNumber']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['documentNumber']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['generalLedgerTitle']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['generalLedgerDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['generalLedgerDate']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['countryId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['countryCurrencyCode']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['transactionTypeId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['transactionTypeCode']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['transactionTypeDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['foreignAmount']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['localAmount']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountCategoryId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountCategoryDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountTypeId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountTypeDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountNumber']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['chartOfAccountDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['businessPartnerId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['businessPartnerDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $generalLedgerArray [$i]['executeName']
                                                                    );
                                                                    ?>');"><i class="glyphicon glyphicon-camera glyphicon-white"></i></button>
                                                                </div>
                                                            </td>
                                                            <td vAlign="top">
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($generalLedgerArray[$i]['journalNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($generalLedgerArray[$i]['journalNumber']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $generalLedgerArray[$i]['journalNumber']
                                                                                    );
                                                                                } else {
                                                                                    echo $generalLedgerArray[$i]['journalNumber'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($generalLedgerArray[$i]['journalNumber']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $generalLedgerArray[$i]['journalNumber']
                                                                                        );
                                                                                    } else {
                                                                                        echo $generalLedgerArray[$i]['journalNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $generalLedgerArray[$i]['journalNumber'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $generalLedgerArray[$i]['journalNumber'];
                                                                        }
                                                                        ?>

                                                                    <?php } else { ?>
                                                                        -
                                                                    <?php } ?>
                                                                </div>
                                                            </td>

                                                            <?php
                                                            if (isset($generalLedgerArray[$i]['generalLedgerDate'])) {
                                                                $valueArray = $generalLedgerArray[$i]['generalLedgerDate'];
                                                                if ($dateConvert->checkDate($valueArray)) {
                                                                    $valueData = explode('-', $valueArray);
                                                                    $year = $valueData[0];
                                                                    $month = $valueData[1];
                                                                    $day = $valueData[2];
                                                                    $value = date(
                                                                            $systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year)
                                                                    );
                                                                } else {
                                                                    $value = null;
                                                                }
                                                                ?>
                                                                <td align="center">
                                                                    <div align="center"><?php echo $value; ?></div>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td>
                                                                    <div class="pull-left">-</div>
                                                                </td>
                                                            <?php } ?>
                                                            <td vAlign="top">
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($generalLedgerArray[$i]['businessPartnerDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $generalLedgerArray[$i]['businessPartnerDescription']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $generalLedgerArray[$i]['businessPartnerDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $generalLedgerArray[$i]['businessPartnerDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $generalLedgerArray[$i]['businessPartnerDescription']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $generalLedgerArray[$i]['businessPartnerDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $generalLedgerArray[$i]['businessPartnerDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $generalLedgerArray[$i]['businessPartnerDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $generalLedgerArray[$i]['businessPartnerDescription'];
                                                                        }
                                                                        ?>

                                                                    <?php } else { ?>
                                                                        -
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                            <td vAlign="top">
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($generalLedgerArray[$i]['chartOfAccountDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $generalLedgerArray[$i]['chartOfAccountNumber'] . " - " . $generalLedgerArray[$i]['chartOfAccountDescription']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $generalLedgerArray[$i]['chartOfAccountNumber'] . " - " . $generalLedgerArray[$i]['chartOfAccountDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $generalLedgerArray[$i]['chartOfAccountDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $generalLedgerArray[$i]['chartOfAccountNumber'] . " - " . $generalLedgerArray[$i]['chartOfAccountDescription']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $generalLedgerArray[$i]['chartOfAccountNumber'] . " - " . $generalLedgerArray[$i]['chartOfAccountDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $generalLedgerArray[$i]['chartOfAccountNumber'] . " - " . $generalLedgerArray[$i]['chartOfAccountDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $generalLedgerArray[$i]['chartOfAccountNumber'] . " - " . $generalLedgerArray[$i]['chartOfAccountDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $generalLedgerArray[$i]['chartOfAccountNumber'] . " - <span style=\"font-weight:100\">" . $generalLedgerArray[$i]['chartOfAccountDescription'] . "</span><br>
										<a href=\"javascript:void(0)\" onClick=\"loadLeftSpecial('" . $generalLedgerArray[$i]['leafId'] . "','" . $securityToken . "','" . $generalLedgerArray[$i]['tableNameId'] . "','" . $generalLedgerArray[$i]['tableName'] . "','" . $generalLedgerArray[$i]['leafName'] . "')\">" . $generalLedgerArray[$i]['documentNumber'] . " - <span style=\"font-weight:100\">" . $generalLedgerArray[$i]['generalLedgerTitle'] . "</span></a>";
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            $debit = 0;
                                                            $credit = 0;

                                                            $d = $generalLedgerArray[$i]['localAmount'];
                                                            $totalAll +=$d;
                                                            if ($d > 0) {
                                                                $debit = $d;
                                                                $totalDebit += $debit;
                                                            } else {
                                                                $credit = $d;
                                                                $totalCredit += $credit;
                                                            }
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    if ($d > 0) {
                                                                        $debit = $a->format($debit);
                                                                    } else {
                                                                        $credit = $a->format($credit);
                                                                    }
                                                                    $amount = $a->format($d);
                                                                } else {
                                                                    if ($d > 0) {
                                                                        $debit = number_format($debit) . " You can assign Currency Format ";
                                                                        $credit = 0;
                                                                    } else {
                                                                        $credit = number_format($credit) . " You can assign Currency Format ";
                                                                        $debit = 0;
                                                                    }
                                                                }
                                                            } else {
                                                                if ($d > 0) {
                                                                    $debit = number_format($debit);
                                                                    $credit = 0;
                                                                } else {
                                                                    $credit = number_format($credit);
                                                                    $debit = 0;
                                                                }
                                                            }
                                                            ?>
                                                            <td vAlign="top">
                                                                <div class="pull-right"><?php echo $debit; ?></div>
                                                            </td>
                                                            <td vAlign="top">
                                                                <div class="pull-right"><?php echo $credit; ?></div>
                                                            </td>
                                                            <td vAlign="top">
                                                                <div class="pull-right"><?php echo $amount; ?></div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="10" vAlign="top" align="center"><?php
                                                            $generalLedger->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="10" vAlign="top" align="center"><?php
                                                        $generalLedger->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" vAlign="top" align="center"><?php
                                                    $generalLedger->exceptionMessage(
                                                            $t['loadFailureLabel']
                                                    );
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                        if (class_exists('NumberFormatter')) {
                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);

                                                $totalDebit = $a->format($totalDebit);
                                                $totalCredit = $a->format($totalCredit);
                                                $totalAll = $a->format($totalAll);
                                            } else {
                                                $totalDebit = number_format($totalDebit) . " You can assign Currency Format ";
                                                $totalCredit = number_format($totalCredit) . " You can assign Currency Format ";
                                            }
                                        } else {
                                            $totalDebit = number_format(abs($totalDebit));
                                            $totalCredit = number_format(abs($totalCredit));
                                        }
                                        if ($totalDebit == abs($totalCredit)) {
                                            $balanceColor = 'success';
                                        } else {
                                            $balanceColor = 'warning';
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="9">&nbsp;</td>
                                        </tr>
                                        <tr class="<?php echo $balanceColor; ?>">
                                            <td colspan="6"><?php echo $t['totalTextLabel']; ?></td>
                                            <td>
                                                <div class="pull-right"><?php echo $totalDebit; ?></div>
                                            </td>
                                            <td>
                                                <div class="pull-right"><?php echo $totalCredit; ?></div>
                                            </td>
                                            <td>
                                                <div class="pull-right"><?php echo $totalAll; ?></div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
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
                        function loadLeftSpecial(leafId, securityToken, tableNameId, tableName, leafName) {
                            $("#centerViewport").html('');
                            var url = './v3/portal/main/controller/portalController.php';
                            var data;
                            $("#centerViewport").load(url,
                                    {
                                        start: 0,
                                        limit: 10,
                                        method: 'read',
                                        type: 'list',
                                        detail: 'body',
                                        leafId: leafId,
                                        pageType: 'leaf',
                                        securityToken: securityToken,
                                        tableNameId: tableNameId,
                                        tableName: tableName,
                                        leafName: leafName
                                    },
                            function(response, status, xhr) {
                                if (status == "error") {
                                    var msg = "Sorry but there was an error: ";
                                    $("#centerViewport").html('').empty().html("<div id=infoPanel><div class='alert alert-error'><a class='close' data-dismiss='alert'>×</a>" + msg + xhr.status + " " + xhr.statusText + "</div></div>");

                                } else {
                                    var x = response.search("false");
                                    if (x > 0) {
                                        if (data) {
                                            data = json_parse(response);
                                            if (data.success === false) {
                                                $("#centerViewport").html('').empty().html("<div id=infoPanel><div class=\'alert alert-error\'><a class='close' data-dismiss='alert'>×</a><img src=\'./images/icons/smiley-roll-sweat.png\'> " + data.message + "</div></div>");
                                            }
                                        }
                                    }
                                }
                            }
                            );
                        }
                    </script>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/generalLedger.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>