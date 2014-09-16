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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/budgetTransferController.php");
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

$translator->setCurrentTable(array('budget', 'budgetTransfer'));

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
$budgetTransferArray = array();
$budgetTransferFromArray = array();
$budgetTransferToArray = array();
$financeYearArray = array();
$financePeriodRangeArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $budgetTransfer = new \Core\Financial\GeneralLedger\BudgetTransfer\Controller\BudgetTransferClass();
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
            $budgetTransfer->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $budgetTransfer->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $budgetTransfer->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $budgetTransfer->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $budgetTransfer->setStartDay($start[2]);
            $budgetTransfer->setStartMonth($start[1]);
            $budgetTransfer->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $budgetTransfer->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $budgetTransfer->setEndDay($start[2]);
            $budgetTransfer->setEndMonth($start[1]);
            $budgetTransfer->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $budgetTransfer->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $budgetTransfer->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $budgetTransfer->setServiceOutput('html');
        $budgetTransfer->setLeafId($leafId);
        $budgetTransfer->execute();
        $budgetTransferFromArray = $budgetTransfer->getBudgetTransferFrom();
        $budgetTransferToArray = $budgetTransfer->getBudgetTransferTo();
        $financeYearArray = $budgetTransfer->getFinanceYear();
        $financePeriodRangeArray = $budgetTransfer->getFinancePeriodRange();
        $isOddPeriod = $budgetTransfer->getIsOddPeriod();
        if ($_POST['method'] == 'read') {
            $budgetTransfer->setStart($offset);
            $budgetTransfer->setLimit($limit); // normal system don't like paging..
            $budgetTransfer->setPageOutput('html');
            $budgetTransferArray = $budgetTransfer->read();
            if (isset($budgetTransferArray [0]['firstRecord'])) {
                $firstRecord = $budgetTransferArray [0]['firstRecord'];
            }
            if (isset($budgetTransferArray [0]['nextRecord'])) {
                $nextRecord = $budgetTransferArray [0]['nextRecord'];
            }
            if (isset($budgetTransferArray [0]['previousRecord'])) {
                $previousRecord = $budgetTransferArray [0]['previousRecord'];
            }
            if (isset($budgetTransferArray [0]['lastRecord'])) {
                $lastRecord = $budgetTransferArray [0]['lastRecord'];
                $endRecord = $budgetTransferArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($budgetTransfer->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($budgetTransferArray [0]['total'])) {
                $total = $budgetTransferArray [0]['total'];
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
                <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10">
                    <button title="A" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $budgetTransfer->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');">
                        Z
                    </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
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
                                    echo $budgetTransfer->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $budgetTransfer->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $budgetTransfer->getControllerPath();
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
                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-forizontal">
                                <div id="btnList">
                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                        class="btn btn-info btn-block"
                                            onclick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $budgetTransfer->getViewPath();
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
                                       echo $budgetTransfer->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $budgetTransfer->getViewPath();
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
                                               echo $budgetTransfer->getViewPath();
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
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
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
                                               echo $budgetTransfer->getViewPath();
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
                                                              echo $budgetTransfer->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png"
                                                 alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Month <?php echo $nextMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Year <?php echo $nextYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $budgetTransfer->getViewPath();
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
                                           echo $budgetTransfer->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                           onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $budgetTransfer->getViewPath();
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
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="budgetTransferIdPreview" id="budgetTransferIdPreview">

                                    <div class="form-group" id="budgetTransferFromDiv">
                                        <label for="budgetTransferFromPreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTransferFromLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="budgetTransferFromPreview"
                                                   id="budgetTransferFromPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="budgetTransferToDiv">
                                        <label for="budgetTransferToPreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTransferToLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="budgetTransferToPreview"
                                                   id="budgetTransferToPreview">
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
                                    <div class="form-group" id="financePeriodRangeIdDiv">
                                        <label for="financePeriodRangeIdPreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['financePeriodRangeIdLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="financePeriodRangeIdPreview"
                                                   id="financePeriodRangeIdPreview">
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
                                    <div class="form-group" id="budgetTransferDateDiv">
                                        <label for="budgetTransferDatePreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTransferDateLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="budgetTransferDatePreview"
                                                   id="budgetTransferDatePreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="budgetTransferAmountDiv">
                                        <label for="budgetTransferAmountPreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTransferAmountLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="budgetTransferAmountPreview"
                                                   id="budgetTransferAmountPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="budgetTransferCommentDiv">
                                        <label for="budgetTransferCommentPreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['budgetTransferCommentLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="budgetTransferCommentPreview"
                                                   id="budgetTransferCommentPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                    <button type="button"  class="btn btn-danger"
                                            onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $budgetTransfer->getControllerPath();
                                            ?>', '<?php
                                            echo $budgetTransfer->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <?php } ?>
                                    <button type="button"  onclick="showMeModal('deletePreview', 0);" class="btn btn-default"
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
                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['budgetTransferFromLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['budgetTransferToLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['budgetTransferAmountLabel']); ?></th>
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
                                            if (is_array($budgetTransferArray)) {
                                                $totalRecord = intval(count($budgetTransferArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($budgetTransferArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($budgetTransferArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" align="center">
                                                                    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $budgetTransfer->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $budgetTransfer->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $budgetTransferArray [$i]['budgetTransferId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <?php } ?>
                                                                    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onclick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['budgetTransferId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['budgetTransferFrom']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['budgetTransferTo']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['financeYearYear']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['financePeriodRangePeriod']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['documentNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['budgetTransferDate']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['budgetTransferAmount']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $budgetTransferArray [$i]['budgetTransferComment']
                                                                            );
                                                                            ?>');">
                                                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i></button><?php } ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($budgetTransferArray[$i]['budgetTransferFromDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $budgetTransferArray[$i]['budgetTransferFromDescription']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetTransferArray[$i]['budgetTransferFromDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['budgetTransferFromDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $budgetTransferArray[$i]['budgetTransferFromDescription']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetTransferArray[$i]['budgetTransferFromDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetTransferArray[$i]['budgetTransferFromDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['budgetTransferFromDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetTransferArray[$i]['budgetTransferFromDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($budgetTransferArray[$i]['documentNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($budgetTransferArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetTransferArray[$i]['documentNumber']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($budgetTransferArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetTransferArray[$i]['documentNumber']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetTransferArray[$i]['documentNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['documentNumber'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetTransferArray[$i]['documentNumber'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($budgetTransferArray[$i]['budgetTransferToDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($budgetTransferArray[$i]['budgetTransferToDescription']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetTransferArray[$i]['budgetTransferToDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['budgetTransferToDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $budgetTransferArray[$i]['budgetTransferToDescription']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetTransferArray[$i]['budgetTransferToDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetTransferArray[$i]['budgetTransferToDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['budgetTransferToDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetTransferArray[$i]['budgetTransferToDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            $d = $budgetTransferArray[$i]['budgetTransferAmount'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($budgetTransferArray[$i]['budgetTransferAmount']);
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
                                                            <td align="center">
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($budgetTransferArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $budgetTransferArray[$i]['staffName'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $budgetTransferArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $budgetTransferArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $budgetTransferArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $budgetTransferArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $budgetTransferArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $budgetTransferArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($budgetTransferArray[$i]['executeTime'])) {
                                                                $valueArray = $budgetTransferArray[$i]['executeTime'];
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
                                                            if ($budgetTransferArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <input style="display:none;" type="checkbox" name="budgetTransferId[]"
                                                                       value="<?php echo $budgetTransferArray[$i]['budgetTransferId']; ?>">
                                                                <label>
                                                                    <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                   value="<?php echo $budgetTransferArray[$i]['isDelete']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="13" valign="top" align="center"><?php
                                                            $budgetTransfer->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="13" valign="top" align="center"><?php
                                                        $budgetTransfer->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="13" valign="top" align="center"><?php
                                                    $budgetTransfer->exceptionMessage(
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
                                <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                <button class="delete btn btn-warning" type="button" 
                                        onclick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                        echo $budgetTransfer->getControllerPath();
                                        ?>', '<?php echo $budgetTransfer->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                    <i class="glyphicon glyphicon-white glyphicon-trash"></i>
                                </button>
                                <?php } ?>
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
    <div class="modal fade" id="budgetPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                    <h4 class="modal-title"><?php echo $t['budgetRecordMessageLabel']; ?></h4>
                </div>
                <div class="modal-body">
                    <div id="previewMiniTransaction" style="" class="">
                    </div>
                    <div id="previewSimple" style="" class="">
                        <form class="form-horizontal">
                            <input type="hidden" name="budgetIdPreview" id="budgetIdPreview">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="chartOfAccountIdDiv">
                                        <label for="chartOfAccountIdSimplePreview"
                                               class="control-label col-sm-6"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="chartOfAccountIdSimplePreview"
                                                   id="chartOfAccountIdSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="financeYearIdDiv">
                                        <label for="financeYearIdSimplePreview"
                                               class="control-label col-sm-6"><?php echo $leafTranslation['financeYearIdLabel']; ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="financeYearIdSimplePreview"
                                                   id="financeYearIdSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthOneDiv">
                                        <label for="budgetTargetMonthOneSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['januaryTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="budgetTargetMonthOneSimplePreview"
                                                   id="budgetTargetMonthOneSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthTwoDiv">
                                        <label for="budgetTargetMonthTwoSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['februaryTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="budgetTargetMonthTwoSimplePreview"
                                                   id="budgetTargetMonthTwoSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthThreeDiv">
                                        <label for="budgetTargetMonthThreeSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['marchTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthThreeSimplePreview" id="budgetTargetMonthThreeSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthFourthDiv">
                                        <label for="budgetTargetMonthFourthSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['aprilTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthFourthSimplePreview" id="budgetTargetMonthFourthSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthFifthDiv">
                                        <label for="budgetTargetMonthFifthSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['mayTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthFifthSimplePreview" id="budgetTargetMonthFifthSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthSixDiv">
                                        <label for="budgetTargetMonthSixSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['junTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="budgetTargetMonthSixSimplePreview"
                                                   id="budgetTargetMonthSixSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthSevenDiv">
                                        <label for="budgetTargetMonthSevenSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['julyTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthSevenSimplePreview" id="budgetTargetMonthSevenSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthEightDiv">
                                        <label for="budgetTargetMonthEightSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['augustTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthEightSimplePreview" id="budgetTargetMonthEightSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthNineDiv">
                                        <label for="budgetTargetMonthNineSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['septemberTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="budgetTargetMonthNineSimplePreview"
                                                   id="budgetTargetMonthNineSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthTenDiv">
                                        <label for="budgetTargetMonthTenSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['octoberTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text" name="budgetTargetMonthTenSimplePreview"
                                                   id="budgetTargetMonthTenSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthElevenDiv">
                                        <label for="budgetTargetMonthElevenSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['novemberTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthElevenSimplePreview" id="budgetTargetMonthElevenSimplePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthTwelveDiv">
                                        <label for="budgetTargetMonthTwelveSimplePreview" class="control-label col-sm-6"><?php
                                            echo ucfirst(
                                                    $t['decemberTextLabel']
                                            );
                                            ?></label>

                                        <div class="col-sm-6">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthTwelveSimplePreview" id="budgetTargetMonthTwelveSimplePreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="previewDetail" style="" class="">
                        <form class="form-horizontal">
                            <input type="hidden" name="budgetIdPreview" id="budgetIdPreview">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="chartOfAccountIdDiv">
                                        <label for="chartOfAccountIdDetailPreview"
                                               class="control-label col-sm-4"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="chartOfAccountIdDetailPreview"
                                                   id="chartOfAccountIdDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="financeYearIdDiv">
                                        <label for="financeYearIdDetailPreview"
                                               class="control-label col-sm-4"><?php echo $leafTranslation['financeYearIdLabel']; ?></label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="financeYearIdDetailPreview"
                                                   id="financeYearIdDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthOneDiv">
                                        <label for="budgetTargetMonthOneDetailPreview"
                                               class="control-label col-sm-4">1. </label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="budgetTargetMonthOneDetailPreview"
                                                   id="budgetTargetMonthOneDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthTwoDiv">
                                        <label for="budgetTargetMonthTwoDetailPreview"
                                               class="control-label col-sm-4">2.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="budgetTargetMonthTwoDetailPreview"
                                                   id="budgetTargetMonthTwoDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthThreeDiv">
                                        <label for="budgetTargetMonthThreeDetailPreview"
                                               class="control-label col-sm-4">3.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthThreeDetailPreview" id="budgetTargetMonthThreeDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthFourthDiv">
                                        <label for="budgetTargetMonthFourthDetailPreview"
                                               class="control-label col-sm-4">4.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthFourthDetailPreview" id="budgetTargetMonthFourthDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthFifthDiv">
                                        <label for="budgetTargetMonthFifthDetailPreview"
                                               class="control-label col-sm-4">5.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthFifthDetailPreview" id="budgetTargetMonthFifthDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthSixDiv">
                                        <label for="budgetTargetMonthSixDetailPreview"
                                               class="control-label col-sm-4">6.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="budgetTargetMonthSixDetailPreview"
                                                   id="budgetTargetMonthSixDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthSevenDetailDiv">
                                        <label for="budgetTargetMonthSevenDetailPreview"
                                               class="control-label col-sm-4">7.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthSevenDetailPreview" id="budgetTargetMonthSevenDetailPreview">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthEightDiv">
                                        <label for="budgetTargetMonthEightDetailPreview"
                                               class="control-label col-sm-4">8.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthEightDetailPreview" id="budgetTargetMonthEightDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthNineDiv">
                                        <label for="budgetTargetMonthNineDetailPreview"
                                               class="control-label col-sm-4">9.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="budgetTargetMonthNineDetailPreview"
                                                   id="budgetTargetMonthNineDetailPreview">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthTenDiv">
                                        <label for="budgetTargetMonthTenDetailPreview"
                                               class="control-label col-sm-4">10.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" name="budgetTargetMonthTenDetailPreview"
                                                   id="budgetTargetMonthTenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthElevenDiv">
                                        <label for="budgetTargetMonthElevenDetailPreview"
                                               class="control-label col-sm-4">11.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthElevenDetailPreview" id="budgetTargetMonthElevenDetailPreview">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthTwelveDiv">
                                        <label for="budgetTargetMonthTwelveDetailPreview"
                                               class="control-label col-sm-4">12.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthTwelveDetailPreview" id="budgetTargetMonthTwelveDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthThirteenDiv">
                                        <label for="budgetTargetMonthThirteenDetailPreview"
                                               class="control-label col-sm-4">13.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthThirteenDetailPreview"
                                                   id="budgetTargetMonthThirteenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthFourteenDiv">
                                        <label for="budgetTargetMonthFourteenDetailPreview"
                                               class="control-label col-sm-4">14.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthFourteenDetailPreview"
                                                   id="budgetTargetMonthFourteenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthFifteenDiv">
                                        <label for="budgetTargetMonthFifteenDetailPreview"
                                               class="control-label col-sm-4">15.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthFifteenDetailPreview" id="budgetTargetMonthFifteenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthSixteenDiv">
                                        <label for="budgetTargetMonthSixteenDetailPreview"
                                               class="control-label col-sm-4">16.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthSixteenDetailPreview" id="budgetTargetMonthSixteenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthSeventeenDiv">
                                        <label for="budgetTargetMonthSeventeenDetailPreview"
                                               class="control-label col-sm-4">17.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthSeventeenDetailPreview"
                                                   id="budgetTargetMonthSeventeenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" id="budgetTargetMonthEighteenDiv">
                                        <label for="budgetTargetMonthEighteenDetailPreview"
                                               class="control-label col-sm-4">18.</label>

                                        <div class="col-sm-8">
                                            <input class="form-control" type="text"
                                                   name="budgetTargetMonthEighteenDetailPreview"
                                                   id="budgetTargetMonthEighteenDetailPreview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  name="showSimpleButtonHeader" id="showSimpleButtonHeader" class="btn btn-info"
                            onclick="toggleBudget(1);"><?php echo $t['previewSimpleBudgetTextlabel']; ?></button>
                    &nbsp;
                    <button type="button"  name="showDetailButtonHeader" id="showDetailButtonHeader" class="btn btn-info"
                            onclick="toggleBudget(2);"><?php echo $t['previewDetailBudgetTextlabel']; ?></button>
                    &nbsp;
                    <button type="button"  name="showDetailButtonHeader" id="showDetailButtonHeader" class="btn btn-info"
                            onclick="toggleBudget(3);"><?php echo $t['previewMiniTransactionTextlabel']; ?></button>

                </div>
            </div>
        </div>
    </div>
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
                                       echo $budgetTransfer->getControllerPath();
                                       ?>', '<?php
                                       echo $budgetTransfer->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budgetTransfer->getControllerPath();
                                       ?>', '<?php
                                       echo $budgetTransfer->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budgetTransfer->getControllerPath();
                                       ?>', '<?php
                                       echo $budgetTransfer->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $budgetTransfer->getControllerPath();
                                       ?>', '<?php
                                       echo $budgetTransfer->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="budgetTransferId" id="budgetTransferId" value="<?php
                            if (isset($_POST['budgetTransferId'])) {
                                echo $_POST['budgetTransferId'];
                            }
                            ?>">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?php
                                    if (isset($budgetTransferArray) && is_array($budgetTransferArray)) {
                                        if (isset($budgetTransferArray[0]['budgetTransferDate'])) {
                                            $valueArray = $budgetTransferArray[0]['budgetTransferDate'];
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
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTransferDateForm">
                                        <label for="budgetTransferDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTransferDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="budgetTransferDate" id="budgetTransferDate"
                                                       value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                       id="budgetTransferDateImage"></span>
                                            </div>
                                            <span class="help-block" id="budgetTransferDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="documentNumberForm">
                                        <label for="documentNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['documentNumberLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control disabled" name="documentNumber" id="documentNumber"
                                                       value="<?php
                                                       if (isset($budgetTransferArray) && is_array($budgetTransferArray)) {
                                                           if (isset($budgetTransferArray[0]['documentNumber'])) {
                                                               echo htmlentities($budgetTransferArray[0]['documentNumber']);
                                                           }
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/document-number.png"></span>
                                            </div>
                                            <span class="help-block" id="documentNumberHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
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
                                                        $currentChartOfAccountTypeDescription = null;
                                                        $group = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($budgetTransferArray[0]['financeYearId'])) {
                                                                if ($budgetTransferArray[0]['financeYearId'] == $financeYearArray[$i]['financeYearId']) {
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="financePeriodRangeIdForm">
                                        <label for="financePeriodRangeId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['financePeriodRangeIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="financePeriodRangeId" id="financePeriodRangeId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($financePeriodRangeArray)) {
                                                    $totalRecord = intval(count($financePeriodRangeArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($budgetTransferArray[0]['financePeriodRangeId'])) {
                                                                if ($budgetTransferArray[0]['financePeriodRangeId'] == $financePeriodRangeArray[$i]['financePeriodRangeId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            if ($isOddPeriod == 1) {
                                                                $valueData = explode(
                                                                        '-', $financePeriodRangeArray[$i]['financePeriodRangeStartDate']
                                                                );
                                                                $year = $valueData[0];
                                                                $month = $valueData[1];
                                                                $day = $valueData[2];
                                                                $financePeriodStartDate = date(
                                                                        $systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year)
                                                                );

                                                                $valueData = explode(
                                                                        '-', $financePeriodRangeArray[$i]['financePeriodRangeEndDate']
                                                                );
                                                                $year = $valueData[0];
                                                                $month = $valueData[1];
                                                                $day = $valueData[2];
                                                                $financePeriodEndDate = date(
                                                                        $systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year)
                                                                );
                                                                ?>
                                                                <option
                                                                    value="<?php echo $financePeriodRangeArray[$i]['financePeriodRangeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                    . [<?php echo $financePeriodRangeArray[$i]['financePeriodRangePeriod']; ?>] -
                                                                    [ <?php echo $financePeriodStartDate; ?> ~ <?php echo $financePeriodEndDate; ?>]
                                                                </option>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <option
                                                                    value="<?php echo $financePeriodRangeArray[$i]['financePeriodRangeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                    . [<?php echo $financePeriodRangeArray[$i]['financePeriodRangePeriod']; ?>] -
                                                                    [<?php
                                                                    echo date(
                                                                            'F', mktime(
                                                                                    0, 0, 0, $financePeriodRangeArray[$i]['financePeriodRangePeriod'], 1, $financePeriodRangeArray[$i]['financeYearYear']
                                                                            )
                                                                    );
                                                                    ?>
                                                                    ]
                                                                </option>
                                                                <?php
                                                                $d++;
                                                            }
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
                                            </select> <span class="help-block" id="financePeriodRangeIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTransferFromForm">
                                        <label for="budgetTransferFrom" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTransferFromLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <select name="budgetTransferFrom" id="budgetTransferFrom" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($budgetTransferFromArray)) {
                                                    $totalRecord = intval(count($budgetTransferFromArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $d++;
                                                            if ($i != 0) {
                                                                if ($currentChartOfAccountTypeDescription != $budgetTransferToArray[$i]['chartOfAccountTypeDescription']) {
                                                                    $group = 1;
                                                                    echo "</optgroup><optgroup label=\"" . $budgetTransferToArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                                }
                                                            } else {
                                                                echo "<optgroup label=\"" . $budgetTransferToArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                            }
                                                            $currentChartOfAccountTypeDescription = $budgetTransferToArray[$i]['chartOfAccountTypeDescription'];
                                                            if (isset($budgetTransferArray[0]['budgetTransferFrom'])) {
                                                                if ($budgetTransferArray[0]['budgetTransferFrom'] == $budgetTransferFromArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $budgetTransferFromArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $budgetTransferFromArray[$i]['chartOfAccountNumber']; ?>
                                                                . <?php echo $budgetTransferFromArray[$i]['chartOfAccountTitle']; ?></option>
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
                                            </select> <span class="help-block" id="budgetTransferFromHelpMe"></span>
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2">
                                            <button type="button"  name="previewBudgetTransferFrombutton"  id="previewBudgetTransferFrombutton" 
                                                    class="btn btn-info"
                                                    onclick="previewBudgetTransfer('<?php echo $leafId; ?>', '<?php
                                                    echo $budgetTransfer->getControllerPath();
                                                    ?>', '<?php echo $securityToken; ?>', 'from');"><?php echo $t['viewButtonLabel']; ?></button>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTransferToForm">
                                        <label for="budgetTransferTo" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTransferToLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <select name="budgetTransferTo" id="budgetTransferTo" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($budgetTransferToArray)) {
                                                    $totalRecord = intval(count($budgetTransferToArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 0;
                                                        $currentChartOfAccountTypeDescription = null;
                                                        $group = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $d++;
                                                            if ($i != 0) {
                                                                if ($currentChartOfAccountTypeDescription != $budgetTransferToArray[$i]['chartOfAccountTypeDescription']) {
                                                                    $group = 1;
                                                                    echo "</optgroup><optgroup label=\"" . $budgetTransferToArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                                }
                                                            } else {
                                                                echo "<optgroup label=\"" . $budgetTransferToArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                            }
                                                            $currentChartOfAccountTypeDescription = $budgetTransferToArray[$i]['chartOfAccountTypeDescription'];
                                                            if (isset($budgetTransferArray[0]['budgetTransferTo'])) {
                                                                if ($budgetTransferArray[0]['budgetTransferTo'] == $budgetTransferToArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $budgetTransferToArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $budgetTransferToArray[$i]['chartOfAccountNumber']; ?>
                                                                . <?php echo $budgetTransferToArray[$i]['chartOfAccountTitle']; ?></option>
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
                                            </select> <span class="help-block" id="budgetTransferToHelpMe"></span>
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2">
                                            <button type="button"  name="previewBudgetTransferFromTo" id="previewBudgetTransferFromTo"
                                                    class="btn btn-info"
                                                    onclick="previewBudgetTransfer('<?php echo $leafId; ?>', '<?php
                                                    echo $budgetTransfer->getControllerPath();
                                                    ?>', '<?php echo $securityToken; ?>', 'to');"><?php echo $t['viewButtonLabel']; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="budgetTransferAmountForm">
                                        <label for="budgetTransferAmount" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['budgetTransferAmountLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="budgetTransferAmount"
                                                       id="budgetTransferAmount" value="<?php
                                                       if (isset($budgetTransferArray[0]['budgetTransferAmount'])) {
                                                           echo $budgetTransferArray[0]['budgetTransferAmount'];
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/currency.png"></span>
                                            </div>
                                            <span class="help-block" id="budgetTransferAmountHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="budgetTransferCommentForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea rows="5" class="form-control" name="budgetTransferComment"
                                                      id="budgetTransferComment"><?php
                                                          if (isset($budgetTransferArray[0]['budgetTransferComment'])) {
                                                              echo htmlentities($budgetTransferArray[0]['budgetTransferComment']);
                                                          }
                                                          ?></textarea> <span class="help-block" id="budgetTransferCommentHelpMe"></span>
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
                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $budgetTransfer->getControllerPath();
                                   ?>', '<?php
                                   echo $budgetTransfer->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>

                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $budgetTransfer->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?>
                                </a>
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
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    window.scrollTo(0, 0);
                    // hide some element popup until click
                    showMeDiv('previewDetail', 0);
                    showMeDiv('previewMiniTransaction', 0);
                    showMeDiv('previewSimple', 0);
                    // end hide element popup until click
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('budgetTransferId');
                    validateMeNumeric('budgetTransferFrom');
                    validateMeNumeric('budgetTransferTo');
                    validateMeNumeric('financeYearId');
                    validateMeNumeric('financePeriodRangeId');

                    $('#budgetTransferDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    validateMeCurrency('budgetTransferAmount');
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['budgetTransferId']) {
            ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                            $('#newRecordButton3').attr('onClick', '');
                            $('#newRecordButton4').attr('onClick', '');
                            $('#newRecordButton5').attr('onClick', '');
                            $('#newRecordButton6').attr('onClick', '');
                            $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                $('#updateRecordButton3').attr('onClick', '');
                                $('#updateRecordButton4').attr('onClick', '');
                                $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>

                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $budgetTransfer->getControllerPath(); ?>','<?php echo $budgetTransfer->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                });
                function previewBudgetTransfer(leafId, url, securityToken, type) {
                    var chartOfAccountId;
                    if (type === 'from') {
                        chartOfAccountId = $("#budgetTransferFrom").val();
                    } else {
                        chartOfAccountId = $("#budgetTransferTo").val();
                    }
                    if (chartOfAccountId.length === 0) {
                        alert(t['chooseDropDownTextLabel']);
                        return false;
                    }
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            offset: 0,
                            limit: 99999,
                            method: 'read',
                            financeYearId: $("#financeYearId").val(),
                            financePeriodRangeId: $("#financePeriodRangeId").val(),
                            chartOfAccountId: chartOfAccountId,
                            type: 'filter',
                            securityToken: securityToken,
                            leafId: leafId,
                            filter: 'budgetAmountByYear'
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var $infoPanel = $('#infoPanel');
                            $infoPanel
                                    .html('').empty()
                                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            var smileyLol = './images/icons/smiley-lol.png';
                            var $infoPanel = $('#infoPanel');
                            var success = data.success;
                            var message = data.message;
                            if (success === false) {
                                $infoPanel
                                        .html('').empty()
                                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
                            } else {
                                if (data.data !== undefined || data.data !== null) {
                                    if (data.data) {
                                        $('#chartOfAccountIdSimplePreview').val(decodeURIComponent(data.data.chartOfAccountNumber) + " - " + decodeURIComponent(data.data.chartOfAccountTitle));
                                        $('#chartOfAccountIdDetailPreview').val(decodeURIComponent(data.data.chartOfAccountNumber) + " - " + decodeURIComponent(data.data.chartOfAccountTitle));

                                        $('#financeYearIdSimplePreview').val(data.data.financeYearYear);
                                        $('#financeYearIdDetailPreview').val(data.data.financeYearYear);

                                        $('#budgetTargetMonthOneSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthOne));
                                        $('#budgetTargetMonthOneDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthOne));

                                        $('#budgetTargetMonthTwoSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthTwo));
                                        $('#budgetTargetMonthTwoDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthTwo));

                                        $('#budgetTargetMonthThreeSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthThree));
                                        $('#budgetTargetMonthThreeDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthThree));

                                        $('#budgetTargetMonthFourthSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFourth));
                                        $('#budgetTargetMonthFourthDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFourth));

                                        $('#budgetTargetMonthFifthSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFifth));
                                        $('#budgetTargetMonthFifthDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFifth));

                                        $('#budgetTargetMonthSixSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSix));
                                        $('#budgetTargetMonthSixDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSix));

                                        $('#budgetTargetMonthSevenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSeven));
                                        $('#budgetTargetMonthSevenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSeven));

                                        $('#budgetTargetMonthEightSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthEight));
                                        $('#budgetTargetMonthEightDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthEight));

                                        $('#budgetTargetMonthNineSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthNine));
                                        $('#budgetTargetMonthNineDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthNine));

                                        $('#budgetTargetMonthTenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthTen));
                                        $('#budgetTargetMonthTenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthTen));

                                        $('#budgetTargetMonthElevenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthEleven));
                                        $('#budgetTargetMonthElevenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthEleven));

                                        $('#budgetTargetMonthTwelveSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthTwelve));
                                        $('#budgetTargetMonthTwelveDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthTwelve));

                                        $('#budgetTargetMonthThirteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthThirteen));
                                        $('#budgetTargetMonthThirteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthThirteen));

                                        $('#budgetTargetMonthFourteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFourteen));
                                        $('#budgetTargetMonthFourteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFourteen));

                                        $('#budgetTargetMonthFifteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFifteen));
                                        $('#budgetTargetMonthFifteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFifteen));

                                        $('#budgetTargetMonthSixteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSixteen));
                                        $('#budgetTargetMonthSixteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSixteen));

                                        $('#budgetTargetMonthSeventeenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSeventeen));
                                        $('#budgetTargetMonthSeventeenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSeventeen));

                                        $('#budgetTargetMonthEighteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthEighteen));
                                        $('#budgetTargetMonthEighteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthEighteen));
                                    }
                                }
                                $("#previewMiniTransaction")
                                        .html('').empty()
                                        .html(
                                                decodeURIComponent("<b>" + data.data.chartOfAccountNumber) + "</b> - " + decodeURIComponent(data.data.chartOfAccountTitle) + "<br>" + data.miniStatement);
                                showMeDiv('previewMiniTransaction', 0);
                                showMeDiv('previewSimple', 0);
                                showMeDiv('previewDetail', 0);
                                showMeModal('budgetPreview', 1);
                                showMeDiv('previewMiniTransaction', 0);
                                showMeDiv('previewSimple', 0);
                                showMeDiv('previewDetail', 0);
                                $("#previewDetail").hide();
                                $("#previewSimple").hide();
                                $infoPanel
                                        .html('').empty()
                                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError')
                                    .html('').empty()
                                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid')
                                    .removeClass().addClass('row-fluid');
                        }
                    });

                }
                function toggleBudget(val) {
                    if (val === 1) {
                        showMeDiv('previewMiniTransaction', 0);
                        showMeDiv('previewSimple', 1);
                        showMeDiv('previewDetail', 0);
                    } else if (val === 2) {
                        showMeDiv('previewMiniTransaction', 0);
                        showMeDiv('previewSimple', 0);
                        showMeDiv('previewDetail', 1);
                    } else if (val === 3) {
                        showMeDiv('previewMiniTransaction', 1);
                        showMeDiv('previewSimple', 0);
                        showMeDiv('previewDetail', 0);
                    }

                }

            </script>
        </div>
    </form><?php } ?>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/budgetTransfer.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>