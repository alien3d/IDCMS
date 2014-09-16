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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/budgetController.php");
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

$translator->setCurrentTable('budget');

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
$budgetArray = array();
$chartOfAccountArray = array();
$financeYearArray = array();
$_POST['from'] = 'budget.php';
$_GET['from'] = 'budget.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $budget = new \Core\Financial\GeneralLedger\Budget\Controller\BudgetClass();
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
            $budget->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $budget->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $budget->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $budget->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $budget->setStartDay($start[2]);
            $budget->setStartMonth($start[1]);
            $budget->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $budget->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $budget->setEndDay($start[2]);
            $budget->setEndMonth($start[1]);
            $budget->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $budget->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $budget->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $budget->setServiceOutput('html');
        $budget->setLeafId($leafId);
        $budget->execute();
        $chartOfAccountArray = $budget->getChartOfAccount();
        $financeYearArray = $budget->getFinanceYear();
        if ($_POST['method'] == 'read') {
            $budget->setStart($offset);
            $budget->setLimit($limit); // normal system don't like paging..
            $budget->setPageOutput('html');
            $budgetArray = $budget->read();
            if (isset($budgetArray [0]['firstRecord'])) {
                $firstRecord = $budgetArray [0]['firstRecord'];
            }
            if (isset($budgetArray [0]['nextRecord'])) {
                $nextRecord = $budgetArray [0]['nextRecord'];
            }
            if (isset($budgetArray [0]['previousRecord'])) {
                $previousRecord = $budgetArray [0]['previousRecord'];
            }
            if (isset($budgetArray [0]['lastRecord'])) {
                $lastRecord = $budgetArray [0]['lastRecord'];
                $endRecord = $budgetArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($budget->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($budgetArray [0]['total'])) {
                $total = $budgetArray [0]['total'];
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
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button">
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $budget->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $budget->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $budget->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'html');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Html&nbsp;&nbsp;
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                                <div id="btnList">
                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                            class="btn btn-info btn-block"
                                            onclick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $budget->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');">
                                        <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                    echo $_POST['query'];
                                }
                                ?>"> <br>
                                <input type="button"  name="searchString" id="searchString"
                                       value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block"
                                       onclick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $budget->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $budget->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"> <br>

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-days-span.png"
                                                 alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
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
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Day <?php echo $previousDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-week.png"
                                                 alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $budget->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png"
                                                 alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Month <?php echo $nextMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Year <?php echo $nextYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budget->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                </table>


                                <div>
                                    <label for="dateRangeStart"></label><input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" onclick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                    <label for="dateRangeEnd"></label><input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" onclick="topPage(175);"><br>
                                    <input type="button"  name="searchDate" id="searchDate"
                                           value="<?php echo $t['searchButtonLabel']; ?>"
                                           class="btn btn-warning btn-block"
                                           onclick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                           echo $budget->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                           onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $budget->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                                </div>
                            
                        </div>
                    </div>
                </div>
                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="budgetIdPreview" id="budgetIdPreview">

                                        <div class="form-group" id="chartOfAccountIdDiv">
                                            <label for="chartOfAccountIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="chartOfAccountIdPreview"
                                                       id="chartOfAccountIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="financeYearIdDiv">
                                            <label for="financeYearIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['financeYearIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="financeYearIdPreview"
                                                       id="financeYearIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthOneDiv">
                                            <label for="budgetTargetMonthOnePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthOneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthOnePreview"
                                                       id="budgetTargetMonthOnePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthTwoDiv">
                                            <label for="budgetTargetMonthTwoPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthTwoLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthTwoPreview"
                                                       id="budgetTargetMonthTwoPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthThreeDiv">
                                            <label for="budgetTargetMonthThreePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthThreeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthThreePreview" id="budgetTargetMonthThreePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthFourthDiv">
                                            <label for="budgetTargetMonthFourthPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthFourthLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFourthPreview" id="budgetTargetMonthFourthPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthFifthDiv">
                                            <label for="budgetTargetMonthFifthPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthFifthLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFifthPreview" id="budgetTargetMonthFifthPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthSixDiv">
                                            <label for="budgetTargetMonthSixPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthSixLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthSixPreview"
                                                       id="budgetTargetMonthSixPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthSevenDiv">
                                            <label for="budgetTargetMonthSevenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthSevenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSevenPreview" id="budgetTargetMonthSevenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthEightDiv">
                                            <label for="budgetTargetMonthEightPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthEightLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthEightPreview" id="budgetTargetMonthEightPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthNineDiv">
                                            <label for="budgetTargetMonthNinePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthNineLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthNinePreview"
                                                       id="budgetTargetMonthNinePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthTenDiv">
                                            <label for="budgetTargetMonthTenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthTenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthTenPreview"
                                                       id="budgetTargetMonthTenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthElevenDiv">
                                            <label for="budgetTargetMonthElevenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthElevenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthElevenPreview" id="budgetTargetMonthElevenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthTwelveDiv">
                                            <label for="budgetTargetMonthTwelvePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthTwelveLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthTwelvePreview" id="budgetTargetMonthTwelvePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthThirteenDiv">
                                            <label for="budgetTargetMonthThirteenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthThirteenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthThirteenPreview"
                                                       id="budgetTargetMonthThirteenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthFourteenDiv">
                                            <label for="budgetTargetMonthFourteenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthFourteenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFourteenPreview"
                                                       id="budgetTargetMonthFourteenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthFifteenDiv">
                                            <label for="budgetTargetMonthFifteenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthFifteenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFifteenPreview" id="budgetTargetMonthFifteenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthSixteenDiv">
                                            <label for="budgetTargetMonthSixteenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthSixteenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSixteenPreview" id="budgetTargetMonthSixteenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthSeventeenDiv">
                                            <label for="budgetTargetMonthSeventeenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthSeventeenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSeventeenPreview"
                                                       id="budgetTargetMonthSeventeenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetMonthEighteenDiv">
                                            <label for="budgetTargetMonthEighteenPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetMonthEighteenLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthEighteenPreview"
                                                       id="budgetTargetMonthEighteenPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetTargetTotalYearDiv">
                                            <label for="budgetTargetTotalYearPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTargetTotalYearLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetTargetTotalYearPreview"
                                                       id="budgetTargetTotalYearPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="budgetVersionDiv">
                                            <label for="budgetVersionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetVersionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="budgetVersionPreview"
                                                       id="budgetVersionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isLockDiv">
                                            <label for="isLockPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isLockLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isLockPreview"
                                                       id="isLockPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $budget->getControllerPath();
                                            ?>', '<?php
                                            echo $budget->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0);"
                                            data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
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
                                            <th width="25px" align="center">
                                    <div align="center">#</div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th><?php echo ucwords($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                    <th>
                                    <div align="center"><?php echo ucwords($leafTranslation['financeYearIdLabel']); ?></div>
                                    </th>
                                    <th>
                                    <div align="center"><?php echo ucwords($leafTranslation['budgetTargetTotalYearLabel']); ?></div>
                                    </th>
                                    <th>
                                    <div align="center"><?php echo ucwords($leafTranslation['budgetActualTotalYearLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                    </th>
                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                               onChange="toggleChecked(this.checked);">
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($budgetArray)) {
                                                $totalRecord = intval(count($budgetArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($budgetArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($budgetArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $budget->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $budget->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $budgetArray [$i]['budgetId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onclick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['chartOfAccountTitle']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['financeYearYear']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthOne']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthOne']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthTwo']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthTwo']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthThree']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthThree']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthFourth']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthFourth']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthFifth']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthFifth']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthSix']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthSix']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthSeven']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthSeven']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthEight']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthEight']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthNine']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthNine']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthTen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthTen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthEleven']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthEleven']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthTwelve']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthTwelve']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthThirteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthThirteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthFourteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthFourteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthFifteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthFifteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthSixteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthSixteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthSeventeen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthSeventeen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetMonthEighteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualMonthEighteen']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetTargetTotalYear']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetActualTotalYear']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetArray [$i]['budgetVersion']
                                                                            );
                                                                            ?>', '<?php echo rawurlencode($budgetArray [$i]['isLock']); ?>');">
                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($budgetArray[$i]['chartOfAccountTitle'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetArray[$i]['chartOfAccountNumber'] . " - " . $budgetArray[$i]['chartOfAccountTitle'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($budgetArray[$i]['financeYearYear'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $budgetArray[$i]['financeYearYear'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetArray[$i]['financeYearYear']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetArray[$i]['financeYearYear'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $budgetArray[$i]['financeYearYear'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetArray[$i]['financeYearYear']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetArray[$i]['financeYearYear'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetArray[$i]['financeYearYear'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetArray[$i]['financeYearYear'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>

                                                            <?php
                                                            $d = $budgetArray[$i]['budgetTargetTotalYear'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($budgetArray[$i]['budgetTargetTotalYear']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td>
                                                                <div class="pull-right"><?php echo $d; ?></div>
                                                            </td>
                                                            <?php
                                                            $e = $budgetArray[$i]['budgetActualTotalYear'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $e = $a->format($budgetArray[$i]['budgetActualTotalYear']);
                                                                } else {
                                                                    $e = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $e = number_format($e);
                                                            }
                                                            ?>
                                                            <td>
                                                                <div class="pull-right"><?php echo $e; ?></div>
                                                            </td>
                                                            <td>
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($budgetArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($budgetArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $budgetArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($budgetArray[$i]['executeTime'])) {
                                                                $valueArray = $budgetArray[$i]['executeTime'];
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
                                                                    $value = date(
                                                                            $systemFormat['systemSettingDateFormat'] . " " . $systemFormat['systemSettingTimeFormat'], mktime($hour, $minute, $second, $month, $day, $year)
                                                                    );
                                                                } else {
                                                                    $value = null;
                                                                }
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td><?php echo $value; ?></td>
                                                            <?php
                                                            if ($budgetArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <input style="display:none;" type="checkbox" name="budgetId[]"
                                                                       value="<?php echo $budgetArray[$i]['budgetId']; ?>">
                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                               value="<?php echo $budgetArray[$i]['isDelete']; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="11" valign="top" align="center"><?php
                                                            $budget->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="11" valign="top" align="center"><?php
                                                        $budget->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="11" valign="top" align="center"><?php
                                                    $budget->exceptionMessage(
                                                            $t['loadFailureLabel']
                                                    );
                                                    ?></td>
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
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left"><?php $navigation->pagenationv4($offset); ?></div>
                        <div class="col-xs-3 col-sm-3 col-md-3 pagination">
                            <div class="pull-right">
                                <button class="delete btn btn-warning" type="button" 
                                        onclick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                        echo $budget->getControllerPath();
                                        ?>', '<?php echo $budget->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                    <i class="glyphicon glyphicon-white glyphicon-trash"></i>
                                </button>
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

                    </script>
                </div>
            </div>
        </div>
        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <form class="form-horizontal">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?php
                $template->setLayout(2);
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
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div align="right">

                                <div class="btn-group">
                                    <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budget->getControllerPath();
                                       ?>', '<?php
                                       echo $budget->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budget->getControllerPath();
                                       ?>', '<?php
                                       echo $budget->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budget->getControllerPath();
                                       ?>', '<?php
                                       echo $budget->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budget->getControllerPath();
                                       ?>', '<?php
                                       echo $budget->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="budgetId" id="budgetId" value="<?php
                            if (isset($_POST['budgetId'])) {
                                echo $_POST['budgetId'];
                            }
                            ?>">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="chartOfAccountIdForm">
                                        <label for="chartOfAccountId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['chartOfAccountIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="chartOfAccountId" id="chartOfAccountId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($chartOfAccountArray)) {
                                                    $totalRecord = intval(count($chartOfAccountArray));
                                                    $d = 0;
                                                    $currentChartOfAccountTypeDescription = null;
                                                    if ($totalRecord > 0) {
                                                        $d++;
                                                        if ($i != 0) {
                                                            if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                            }
                                                        } else {
                                                            echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                        }
                                                        $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($budgetArray[0]['chartOfAccountId'])) {
                                                                if ($budgetArray[0]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?>
                                                                -  <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                            <?php
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
                                            </select> <span class="help-block" id="chartOfAccountIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="financeYearIdForm">
                                        <label for="financeYearId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['financeYearIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financeYearId" id="financeYearId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financeYearArray)) {
                                                    $totalRecord = intval(count($financeYearArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($budgetArray[0]['financeYearId'])) {
                                                                if ($budgetArray[0]['financeYearId'] == $financeYearArray[$i]['financeYearId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $financeYearArray[$i]['financeYearId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $financeYearArray[$i]['financeYearYear']; ?></option>
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
                                            </select> <span class="help-block" id="financeYearIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthOneForm">
                                        <label for="budgetTargetMonthOne" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthOneLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthOne" id="budgetTargetMonthOne"
                                                   value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthOne'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthOne']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthOneHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthOneForm">
                                        <label for="budgetActualMonthOne" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthOneLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthOne"
                                                   id="budgetActualMonthOne" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthOne'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthOne']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthOneHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthTwoForm">
                                        <label for="budgetTargetMonthTwo" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthTwoLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthTwo" id="budgetTargetMonthTwo"
                                                   value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthTwo'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthTwo']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthTwoHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthTwoForm">
                                        <label for="budgetActualMonthTwo" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthTwoLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthTwo"
                                                   id="budgetActualMonthTwo" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthTwo'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthTwo']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthTwoHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthThreeForm">
                                        <label for="budgetTargetMonthThree" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthThreeLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthThree"
                                                   id="budgetTargetMonthThree" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthThree'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthThree']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthThreeHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthThreeForm">
                                        <label for="budgetActualMonthThree" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthThreeLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthThree"
                                                   id="budgetActualMonthThree" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthThree'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthThree']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthThreeHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthFourthForm">
                                        <label for="budgetTargetMonthFourth" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthFourthLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthFourth"
                                                   id="budgetTargetMonthFourth" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthFourth'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthFourth']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthFourthHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthFourthForm">
                                        <label for="budgetActualMonthFourth" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthFourthLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthFourth"
                                                   id="budgetActualMonthFourth" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthFourth'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthFourth']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthFourthHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthFifthForm">
                                        <label for="budgetTargetMonthFifth" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthFifthLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthFifth"
                                                   id="budgetTargetMonthFifth" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthFifth'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthFifth']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthFifthHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthFifthForm">
                                        <label for="budgetActualMonthFifth" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthFifthLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthFifth"
                                                   id="budgetActualMonthFifth" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthFifth'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthFifth']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthFifthHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthSixForm">
                                        <label for="budgetTargetMonthSix" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthSixLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthSix" id="budgetTargetMonthSix"
                                                   value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthSix'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthSix']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthSixHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthSixForm">
                                        <label for="budgetActualMonthSix" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthSixLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthSix"
                                                   id="budgetActualMonthSix" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthSix'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthSix']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthSixHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthSevenForm">
                                        <label for="budgetTargetMonthSeven" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthSevenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthSeven"
                                                   id="budgetTargetMonthSeven" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthSeven'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthSeven']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthSevenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthSevenForm">
                                        <label for="budgetActualMonthSeven" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthSevenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthSeven"
                                                   id="budgetActualMonthSeven" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthSeven'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthSeven']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthSevenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthEightForm">
                                        <label for="budgetTargetMonthEight" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthEightLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthEight"
                                                   id="budgetTargetMonthEight" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthEight'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthEight']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthEightHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthEightForm">
                                        <label for="budgetActualMonthEight" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthEightLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthEight"
                                                   id="budgetActualMonthEight" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthEight'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthEight']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthEightHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthNineForm">
                                        <label for="budgetTargetMonthNine" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthNineLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthNine" id="budgetTargetMonthNine"
                                                   value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthNine'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthNine']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthNineHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthNineForm">
                                        <label for="budgetActualMonthNine" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthNineLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthNine"
                                                   id="budgetActualMonthNine" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthNine'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthNine']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthNineHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthTenForm">
                                        <label for="budgetTargetMonthTen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthTenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthTen" id="budgetTargetMonthTen"
                                                   value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthTen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthTen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthTenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthTenForm">
                                        <label for="budgetActualMonthTen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthTenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthTen"
                                                   id="budgetActualMonthTen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthTen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthTen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthTenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthElevenForm">
                                        <label for="budgetTargetMonthEleven" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthElevenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthEleven"
                                                   id="budgetTargetMonthEleven" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthEleven'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthEleven']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthElevenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthElevenForm">
                                        <label for="budgetActualMonthEleven" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthElevenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthEleven"
                                                   id="budgetActualMonthEleven" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthEleven'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthEleven']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthElevenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthTwelveForm">
                                        <label for="budgetTargetMonthTwelve" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthTwelveLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthTwelve"
                                                   id="budgetTargetMonthTwelve" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthTwelve'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthTwelve']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthTwelveHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthTwelveForm">
                                        <label for="budgetActualMonthTwelve" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthTwelveLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthTwelve"
                                                   id="budgetActualMonthTwelve" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthTwelve'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthTwelve']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthTwelveHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthThirteenForm">
                                        <label for="budgetTargetMonthThirteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthThirteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthThirteen"
                                                   id="budgetTargetMonthThirteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthThirteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthThirteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthThirteenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthThirteenForm">
                                        <label for="budgetActualMonthThirteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthThirteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthThirteen"
                                                   id="budgetActualMonthThirteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthThirteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthThirteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthThirteenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthFourteenForm">
                                        <label for="budgetTargetMonthFourteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthFourteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthFourteen"
                                                   id="budgetTargetMonthFourteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthFourteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthFourteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthFourteenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthFourteenForm">
                                        <label for="budgetActualMonthFourteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthFourteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthFourteen"
                                                   id="budgetActualMonthFourteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthFourteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthFourteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthFourteenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthFifteenForm">
                                        <label for="budgetTargetMonthFifteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthFifteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthFifteen"
                                                   id="budgetTargetMonthFifteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthFifteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthFifteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthFifteenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthFifteenForm">
                                        <label for="budgetActualMonthFifteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthFifteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthFifteen"
                                                   id="budgetActualMonthFifteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthFifteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthFifteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthFifteenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthSixteenForm">
                                        <label for="budgetTargetMonthSixteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthSixteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthSixteen"
                                                   id="budgetTargetMonthSixteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthSixteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthSixteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthSixteenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthSixteenForm">
                                        <label for="budgetActualMonthSixteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthSixteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthSixteen"
                                                   id="budgetActualMonthSixteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthSixteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthSixteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthSixteenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthSeventeenForm">
                                        <label for="budgetTargetMonthSeventeen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthSeventeenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthSeventeen"
                                                   id="budgetTargetMonthSeventeen"  value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthSeventeen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthSeventeen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthSeventeenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthSeventeenForm">
                                        <label for="budgetActualMonthSeventeen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthSeventeenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthSeventeen"
                                                   id="budgetActualMonthSeventeen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthSeventeen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthSeventeen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthSeventeenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetMonthEighteenForm">
                                        <label for="budgetTargetMonthEighteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetMonthEighteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetMonthEighteen"
                                                   id="budgetTargetMonthEighteen" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetMonthEighteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetMonthEighteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetMonthEighteenHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualMonthEighteenForm">
                                        <label for="budgetActualMonthEighteen" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualMonthEighteenLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualMonthEighteen"
                                                   id="budgetActualMonthEighteen"  value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualMonthEighteen'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualMonthEighteen']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualMonthEighteenHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTargetTotalYearForm">
                                        <label for="budgetTargetTotalYear" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTargetTotalYearLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetTargetTotalYear" id="budgetTargetTotalYear"
                                                   value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetTargetTotalYear'])) {
                                                           echo htmlentities($budgetArray[0]['budgetTargetTotalYear']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetTargetTotalYearHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetActualTotalYearForm">
                                        <label for="budgetActualTotalYear" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetActualTotalYearLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input readonly type="text" class="form-control disabled" name="budgetActualTotalYear"
                                                   id="budgetActualTotalYear" value="<?php
                                                   if (isset($budgetArray) && is_array($budgetArray)) {
                                                       if (isset($budgetArray[0]['budgetActualTotalYear'])) {
                                                           echo htmlentities($budgetArray[0]['budgetActualTotalYear']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="budgetActualTotalYearHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetVersionForm">
                                        <label for="budgetVersion" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetVersionLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="budgetVersion" id="budgetVersion" value="<?php
                                            if (isset($budgetArray) && is_array($budgetArray)) {
                                                if (isset($budgetArray[0]['budgetVersion'])) {
                                                    echo htmlentities($budgetArray[0]['budgetVersion']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="budgetVersionHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isLockForm">
                                        <label for="isLock" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isLockLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div id="isCloseSwitch" class="switch" data-on-label="<?php echo $t['openTextLabel']; ?>"
                                                 data-off-label="<?php echo $t['closeTextLabel']; ?>" data-on="success" data-off="danger">

                                                <input class="form-control" type="checkbox" name="isLock" id="isLock" value="<?php
                                                if (isset($budgetArray) && is_array($budgetArray)) {
                                                    if (isset($budgetArray[0]['isLock'])) {
                                                        echo $budgetArray[0]['isLock'];
                                                    }
                                                }
                                                ?>" <?php
                                                       if (isset($budgetArray) && is_array($budgetArray)) {
                                                           if (isset($budgetArray[0]['isLock'])) {
                                                               if ($budgetArray[0]['isLock'] == TRUE || $budgetArray[0]['isLock'] == 1) {
                                                                   echo "checked";
                                                               }
                                                           }
                                                       }
                                                       ?>></div>
                                            <span class="help-block" id="isLockHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?>
                                </a>
                                <a id="newRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                   class="btn dropdown-toggle btn-success disabled"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li>
                                        <a id="newRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?>
                                        </a></li>
                                    <li>
                                        <a id="newRecordButton4" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?>
                                        </a></li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                           ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                           ?></a></li>-->
                                    <li>
                                        <a id="newRecordButton7" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?>
                                        </a></li>
                                </ul>
                            </div>
                            <div class="btn-group" align="left">
                                <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                        class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?>
                                </a>
                                <a id="updateRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                   class="btn dropdown-toggle btn-info disabled"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li>
                                        <a id="updateRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?>
                                        </a></li>
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                           ?></a></li> -->
                                    <li>
                                        <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled">
                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $budget->getControllerPath();
                                   ?>', '<?php
                                   echo $budget->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $budget->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php
                if (isset($firstRecord)) {
                    echo intval($firstRecord);
                }
                ?>"> <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                       if (isset($nextRecord)) {
                           echo intval($nextRecord);
                       }
                       ?>"> <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php
                       if (isset($previousRecord)) {
                           echo intval($previousRecord);
                       }
                       ?>"> <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php
                       if (isset($lastRecord)) {
                           echo intval($lastRecord);
                       }
                       ?>"> <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php
                       if (isset($endRecord)) {
                           echo intval($endRecord);
                       }
                       ?>">
            </div>
        </div></form>
    <script type="text/javascript">
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            $('.switch')['bootstrapSwitch']();

            validateMeNumeric('budgetId');
            validateMeNumeric('chartOfAccountId');
            validateMeNumeric('financeYearId');
            validateMeCurrency('budgetTargetMonthOne');
            validateMeCurrency('budgetTargetMonthTwo');
            validateMeCurrency('budgetTargetMonthThree');
            validateMeCurrency('budgetTargetMonthFourth');
            validateMeCurrency('budgetTargetMonthFifth');
            validateMeCurrency('budgetTargetMonthSix');
            validateMeCurrency('budgetTargetMonthSeven');
            validateMeCurrency('budgetTargetMonthEight');
            validateMeCurrency('budgetTargetMonthNine');
            validateMeCurrency('budgetTargetMonthTen');
            validateMeCurrency('budgetTargetMonthEleven');
            validateMeCurrency('budgetTargetMonthTwelve');
            validateMeCurrency('budgetTargetMonthThirteen');
            validateMeCurrency('budgetTargetMonthFourteen');
            validateMeCurrency('budgetTargetMonthFifteen');
            validateMeCurrency('budgetTargetMonthSixteen');
            validateMeCurrency('budgetTargetMonthSeventeen');
            validateMeCurrency('budgetTargetMonthEighteen');
            validateMeCurrency('budgetTargetTotalYear');
            validateMeAlphaNumeric('budgetVersion');
            validateMeNumeric('isLock');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>
                $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton4').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                $('#firstRecordButton').removeClass().addClass('btn btn-default');
                $('#endRecordButton').removeClass().addClass('btn btn-default');
        <?php
    } else {
        if ($_POST['budgetId']) {
            ?>$('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                            $('#newRecordButton3').attr('onClick', '');
                            $('#newRecordButton4').attr('onClick', '');
                            $('#newRecordButton5').attr('onClick', '');
                            $('#newRecordButton6').attr('onClick', '');
                            $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                $('#updateRecordButton3').attr('onClick', '');
                                $('#updateRecordButton4').attr('onClick', '');
                                $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $budget->getControllerPath(); ?>','<?php echo $budget->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                });
    </script>

<?php } ?>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/budget.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>