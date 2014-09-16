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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/chartOfAccountSliceController.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/chartOfAccountSliceDetailController.php");
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

$translator->setCurrentTable(array('chartOfAccountSlice', 'chartOfAccountSliceDetail'));

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
$chartOfAccountSliceArray = array();
$chartOfAccountArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $chartOfAccountSlice = new \Core\Financial\GeneralLedger\ChartOfAccountSlice\Controller\ChartOfAccountSliceClass( );
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
            $chartOfAccountSlice->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $chartOfAccountSlice->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $chartOfAccountSlice->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $chartOfAccountSlice->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $chartOfAccountSlice->setStartDay($start[2]);
            $chartOfAccountSlice->setStartMonth($start[1]);
            $chartOfAccountSlice->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $chartOfAccountSlice->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $chartOfAccountSlice->setEndDay($start[2]);
            $chartOfAccountSlice->setEndMonth($start[1]);
            $chartOfAccountSlice->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $chartOfAccountSlice->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $chartOfAccountSlice->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $chartOfAccountSlice->setServiceOutput('html');
        $chartOfAccountSlice->setLeafId($leafId);
        $chartOfAccountSlice->execute();
        $chartOfAccountArray = $chartOfAccountSlice->getChartOfAccount();
        if ($_POST['method'] == 'read') {
            $chartOfAccountSlice->setStart($offset);
            $chartOfAccountSlice->setLimit($limit); // normal system don't like paging..
            $chartOfAccountSlice->setPageOutput('html');
            $chartOfAccountSliceArray = $chartOfAccountSlice->read();
            if (isset($chartOfAccountSliceArray [0]['firstRecord'])) {
                $firstRecord = $chartOfAccountSliceArray [0]['firstRecord'];
            }
            if (isset($chartOfAccountSliceArray [0]['nextRecord'])) {
                $nextRecord = $chartOfAccountSliceArray [0]['nextRecord'];
            }
            if (isset($chartOfAccountSliceArray [0]['previousRecord'])) {
                $previousRecord = $chartOfAccountSliceArray [0]['previousRecord'];
            }
            if (isset($chartOfAccountSliceArray [0]['lastRecord'])) {
                $lastRecord = $chartOfAccountSliceArray [0]['lastRecord'];
                $endRecord = $chartOfAccountSliceArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($chartOfAccountSlice->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($chartOfAccountSliceArray [0]['total'])) {
                $total = $chartOfAccountSliceArray [0]['total'];
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
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountSlice->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');">
                        Z
                    </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
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
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountSlice->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountSlice->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountSlice->getControllerPath();
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
                                            echo $chartOfAccountSlice->getViewPath();
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
                                       echo $chartOfAccountSlice->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountSlice->getViewPath();
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
                                               echo $chartOfAccountSlice->getViewPath();
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
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
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
                                               echo $chartOfAccountSlice->getViewPath();
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
                                                              echo $chartOfAccountSlice->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png"
                                                 alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Month <?php echo $nextMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Year <?php echo $nextYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $chartOfAccountSlice->getViewPath();
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
                                           echo $chartOfAccountSlice->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                           onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $chartOfAccountSlice->getViewPath();
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
                                    <form class="form-horizontal">
                                        <input type="hidden" name="chartOfAccountSliceIdPreview" id="chartOfAccountSliceIdPreview">

                                        <div class="form-group" id="chartOfAccountIdDiv">
                                            <label for="chartOfAccountIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="chartOfAccountIdPreview"
                                                       id="chartOfAccountIdPreview">
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
                                        <div class="form-group" id="chartOfAccountSliceDateDiv">
                                            <label for="chartOfAccountSliceDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountSliceDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountSliceDatePreview" id="chartOfAccountSliceDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountSliceAmountDiv">
                                            <label for="chartOfAccountSliceAmountPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountSliceAmountLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountSliceAmountPreview"
                                                       id="chartOfAccountSliceAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountSliceDescriptionDiv">
                                            <label for="chartOfAccountSliceDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountSliceDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountSliceDescriptionPreview"
                                                       id="chartOfAccountSliceDescriptionPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $chartOfAccountSlice->getControllerPath();
                                            ?>', '<?php
                                            echo $chartOfAccountSlice->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
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
                                    <th width="150px"><?php echo ucwords($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['chartOfAccountSliceDateLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['chartOfAccountSliceAmountLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['chartOfAccountSliceDescriptionLabel']); ?></th>
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
                                            if (is_array($chartOfAccountSliceArray)) {
                                                $totalRecord = intval(count($chartOfAccountSliceArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($chartOfAccountSliceArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($chartOfAccountSliceArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $chartOfAccountSlice->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $chartOfAccountSlice->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $chartOfAccountSliceArray [$i]['chartOfAccountSliceId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onclick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $chartOfAccountSliceArray [$i]['chartOfAccountSliceId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $chartOfAccountSliceArray [$i]['chartOfAccountTitle']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $chartOfAccountSliceArray [$i]['documentNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $chartOfAccountSliceArray [$i]['chartOfAccountSliceDate']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $chartOfAccountSliceArray [$i]['chartOfAccountSliceAmount']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $chartOfAccountSliceArray [$i]['chartOfAccountSliceDescription']
                                                                            );
                                                                            ?>');">
                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($chartOfAccountSliceArray[$i]['chartOfAccountTitle'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle']
                                                                                    );
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $chartOfAccountSliceArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountSliceArray[$i]['chartOfAccountTitle'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td align="center">
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($chartOfAccountSliceArray[$i]['documentNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($chartOfAccountSliceArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountSliceArray[$i]['documentNumber']
                                                                                    );
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($chartOfAccountSliceArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountSliceArray[$i]['documentNumber']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountSliceArray[$i]['documentNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['documentNumber'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $chartOfAccountSliceArray[$i]['documentNumber'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($chartOfAccountSliceArray[$i]['chartOfAccountSliceDate'])) {
                                                                $valueArray = $chartOfAccountSliceArray[$i]['chartOfAccountSliceDate'];
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
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td align="center"><?php echo $value; ?></td>
                                                            <?php
                                                            $d = $chartOfAccountSliceArray[$i]['chartOfAccountSliceAmount'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($chartOfAccountSliceArray[$i]['chartOfAccountSliceAmount']);
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
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $chartOfAccountSliceArray[$i]['chartOfAccountSliceDescription'];
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
                                                                    if (isset($chartOfAccountSliceArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $chartOfAccountSliceArray[$i]['staffName'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountSliceArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $chartOfAccountSliceArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountSliceArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountSliceArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountSliceArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $chartOfAccountSliceArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($chartOfAccountSliceArray[$i]['executeTime'])) {
                                                                $valueArray = $chartOfAccountSliceArray[$i]['executeTime'];
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
                                                            if ($chartOfAccountSliceArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <input style="display:none;" type="checkbox" name="chartOfAccountSliceId[]"
                                                                       value="<?php echo $chartOfAccountSliceArray[$i]['chartOfAccountSliceId']; ?>">
                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                               value="<?php echo $chartOfAccountSliceArray[$i]['isDelete']; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="10" valign="top" align="center"><?php
                                                            $chartOfAccountSlice->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="10" valign="top" align="center"><?php
                                                        $chartOfAccountSlice->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" valign="top" align="center"><?php
                                                    $chartOfAccountSlice->exceptionMessage(
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
                                        echo $chartOfAccountSlice->getControllerPath();
                                        ?>', '<?php
                                        echo $chartOfAccountSlice->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>');">
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
    <?php
    $chartOfAccountSliceDetail = new \Core\Financial\GeneralLedger\ChartOfAccountSliceDetail\Controller\ChartOfAccountSliceDetailClass( );
    $chartOfAccountSliceDetail->setServiceOutput('html');
    $chartOfAccountSliceDetail->setLeafId($leafId);
    $chartOfAccountSliceDetail->execute();
    $chartOfAccountArray = $chartOfAccountSliceDetail->getChartOfAccount();
    $chartOfAccountSliceDetail->setStart(0);
    $chartOfAccountSliceDetail->setLimit(999999); // normal system don't like paging..
    $chartOfAccountSliceDetail->setPageOutput('html');
    if (isset($_POST['chartOfAccountSliceId'])) {
        $chartOfAccountSliceDetailArray = $chartOfAccountSliceDetail->read();
    }
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
                                       echo $chartOfAccountSlice->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountSlice->getViewPath();
                                       ?>', '<?php
                                       echo $chartOfAccountSliceDetail->getControllerPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountSlice->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountSlice->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountSlice->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountSlice->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountSlice->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountSlice->getViewPath();
                                       ?>', '<?php
                                       echo $chartOfAccountSliceDetail->getControllerPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="chartOfAccountSliceId" id="chartOfAccountSliceId" value="<?php
                            if (isset($_POST['chartOfAccountSliceId'])) {
                                echo $_POST['chartOfAccountSliceId'];
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
                                            <select name="chartOfAccountId" id="chartOfAccountId" class="chzn-select form-control"
                                                    onChange="getChartOfAccountAmount(<?php echo $leafId; ?>, '<?php
                                                    echo $chartOfAccountSlice->getControllerPath();
                                                    ?>', '<?php echo $securityToken; ?>');">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($chartOfAccountArray)) {
                                                    $d = 0;
                                                    $currentChartOfAccountTypeDescription = null;
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
                                                            if (isset($chartOfAccountSliceArray[0]['chartOfAccountId'])) {
                                                                if ($chartOfAccountSliceArray[0]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
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
                                                                - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                            </select> <span class="help-block" id="chartOfAccountIdHelpMe"></span>
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
                                                <input type="text" class="form-control" name="documentNumber" id="documentNumber"
                                                       disabled
                                                       value="<?php
                                                       if (isset($chartOfAccountSliceArray) && is_array($chartOfAccountSliceArray)) {
                                                           if (isset($chartOfAccountSliceArray[0]['documentNumber'])) {
                                                               echo htmlentities($chartOfAccountSliceArray[0]['documentNumber']);
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
                                    <?php
                                    if (isset($chartOfAccountSliceArray) && is_array($chartOfAccountSliceArray)) {
                                        if (isset($chartOfAccountSliceArray[0]['chartOfAccountSliceDate'])) {
                                            $valueArray = $chartOfAccountSliceArray[0]['chartOfAccountSliceDate'];
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
                                        } else {
                                            $value = null;
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="chartOfAccountSliceDateForm">
                                        <label for="chartOfAccountSliceDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['chartOfAccountSliceDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="chartOfAccountSliceDate"
                                                       id="chartOfAccountSliceDate" value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                       id="chartOfAccountSliceDateImage"></span>
                                            </div>
                                            <span class="help-block" id="chartOfAccountSliceDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="chartOfAccountSliceAmountForm">
                                        <label for="chartOfAccountSliceAmount"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['chartOfAccountSliceAmountLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="chartOfAccountSliceAmount"
                                                       id="chartOfAccountSliceAmount" value="<?php
                                                       if (isset($chartOfAccountSliceArray) && is_array($chartOfAccountSliceArray)) {
                                                           if (isset($chartOfAccountSliceArray[0]['chartOfAccountSliceAmount'])) {
                                                               echo htmlentities($chartOfAccountSliceArray[0]['chartOfAccountSliceAmount']);
                                                           }
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/currency.png"></span>
                                                <span class="help-block" id="chartOfAccountSliceAmountHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="chartOfAccountSliceDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea rows="5" class="form-control" name="chartOfAccountSliceDescription"
                                                      id="chartOfAccountSliceDescription"><?php
                                                          if (isset($chartOfAccountSliceArray[0]['chartOfAccountSliceDescription'])) {
                                                              echo htmlentities($chartOfAccountSliceArray[0]['chartOfAccountSliceDescription']);
                                                          }
                                                          ?></textarea> <span class="help-block" id="chartOfAccountSliceDescriptionHelpMe"></span>
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
                                        </a>
                                    </li>
                                    <li>
                                        <a id="newRecordButton4" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?>
                                        </a></li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                           ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                           ?></a></li>-->
                                    <li>
                                        <a id="newRecordButton7" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?>
                                        </a>
                                    </li>
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
                                    <li>
                                        <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                        </a></li>
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
                                   echo $chartOfAccountSlice->getControllerPath();
                                   ?>', '<?php
                                   echo $chartOfAccountSlice->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="postRecordbutton"  href="javascript:void(0)" class="btn btn-warning disabled"><i
                                        class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountSlice->getViewPath();
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
            <div class="modal hide" id="deleteDetailPreview" tabindex="-1">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="chartOfAccountSliceDetailIdPreview" id="chartOfAccountSliceDetailIdPreview">

                    <div class="form-group" id="chartOfAccountIdDiv">
                        <label for="chartOfAccountIdPreview"
                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <input class="form-control" type="text" name="chartOfAccountIdPreview"
                                   id="chartOfAccountIdPreview">
                        </div>
                    </div>
                    <div class="form-group" id="journalNumberDiv">
                        <label for="journalNumberPreview"
                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['journalNumberLabel']; ?></label>

                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <input class="form-control" type="text" name="journalNumberPreview"
                                   id="journalNumberPreview">
                        </div>
                    </div>
                    <div class="form-group" id="chartOfAccountSliceDetailAmountDiv">
                        <label for="chartOfAccountSliceDetailAmountPreview"
                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountSliceDetailAmountLabel']; ?></label>

                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <input class="form-control" type="text" name="chartOfAccountSliceDetailAmountPreview"
                                   id="chartOfAccountSliceDetailAmountPreview">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail(<?php echo $leafId; ?>, '<?php
                    echo $chartOfAccountSliceDetail->getControllerPath();
                    ?>', '<?php
                    echo $chartOfAccountSliceDetail->getViewPath();
                    ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                    <button type="button"  onclick="showMeModal('deleteDetailPreview', 0);" class="btn btn-default"
                            data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="button"  name="hideMaster" id="hideMaster" onclick="toggle('masterForm');"
                            class="btn btn-info">
                                <?php echo $t['hideUnHideTextLabel']; ?>
                    </button>
                    <span id="trialBalance" class="label label-info"><?php echo $t['trialBalanceTextLabel']; ?></span> <br> <br>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th width="25px" align="center">
                        <div align="center">#</div>
                        </th>
                        <th width="50px"><?php echo ucfirst($t['actionTextLabel']); ?></th>
                        <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                        <th width="150px"><?php echo ucfirst($leafTranslation['chartOfAccountSliceDetailAmountLabel']); ?></th>
                        <th width="200px"><?php echo $t['debitTextLabel']; ?></th>
                        <th width="200px"><?php echo $t['creditTextLabel']; ?></th>
                        </tr>
                        <tr>
                            <?php
                            $disabledDetail = null;
                            if (isset($_POST['chartOfAccountSliceId']) && (strlen($_POST['chartOfAccountSliceId']) > 0)) {
                                $disabledDetail = null;
                            } else {
                                $disabledDetail = "disabled";
                            }
                            ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td valign="middle" align="center">
                                <div align="center">
                                    <button class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>"
                                            onclick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php
                                            echo $chartOfAccountSliceDetail->getControllerPath();
                                            ?>', '<?php echo $securityToken; ?>');"><i class="glyphicon glyphicon-plus  glyphicon-white"></i></button>

                                    <div id="miniInfoPanel9999"></div>
                                </div>
                            </td>
                            <td valign="top" class="form-group" id="chartOfAccountId9999DetailForm">
                                <select name="chartOfAccountId[]" id="chartOfAccountId9999" class="chzn-select form-control"
                                        <?php echo $disabledDetail; ?>>
                                    <option value=""></option>
                                    <?php
                                    if (is_array($chartOfAccountArray)) {
                                        $d = 0;
                                        $currentChartOfAccountTypeDescription = null;
                                        $totalRecord = intval(count($chartOfAccountArray));
                                        if ($totalRecord > 0) {
                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                $d++;
                                                if ($i != 0) {
                                                    if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                        echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                    }
                                                } else {
                                                    echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                }
                                                $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                ?>
                                                <option
                                                    value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>"><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?>
                                                    - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                </select><span class="help-block" id="chartOfAccountId9999HelpMe"></span></div>
                            </td>
                            <td valign="top" class="form-group" id="chartOfAccountSliceDetailAmount9999Detail">
                                <input class="form-control"   <?php echo $disabledDetail; ?> type="text"
                                       name="chartOfAccountSliceDetailAmount[]" id="chartOfAccountSliceDetailAmount9999">
                            </td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr class="success">
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <?php
                            $totalDebit = 0;
                            $totalCredit = 0;
                            if (isset($_POST['chartOfAccountSliceId'])) {
                                if (is_array($chartOfAccountSliceDetailArray)) {
                                    $totalRecordDetail = intval(count($chartOfAccountSliceDetailArray));
                                    if ($totalRecordDetail > 0) {
                                        $counter = 0;

                                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                                            $counter++;
                                            ?>
                                            <tr id="<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>">
                                                <td>
                                                    <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <input type="hidden" name="chartOfAccountSliceDetailId[]"
                                                               id="chartOfAccountSliceDetailId<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"
                                                               value="<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>">
                                                        <input type="hidden" name="chartOfAccountSliceId[]"
                                                               id="chartOfAccountSliceId<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"
                                                               value="<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceId']; ?>">
                                                        <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                onclick="showFormUpdateDetail(<?php echo $leafId; ?>, '<?php
                                                                echo $chartOfAccountSliceDetail->getControllerPath();
                                                                ?>', '<?php echo $securityToken; ?>', '<?php
                                                                echo intval(
                                                                        $chartOfAccountSliceDetailArray [$j]['chartOfAccountSliceDetailId']
                                                                );
                                                                ?>');">
                                                            <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                onclick="showModalDeleteDetail('<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>');">
                                                            <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                        <div
                                                            id="miniInfoPanel<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"></div>
                                                    </div>
                                                </td>
                                                <td class="form-group"
                                                    id="chartOfAccountId<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>DetailForm">
                                                    <select name="chartOfAccountId[]"
                                                            id="chartOfAccountId<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"
                                                            class="chzn-select form-control">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($chartOfAccountArray)) {
                                                            $d = 0;
                                                            $currentChartOfAccountTypeDescription = null;
                                                            $totalRecord = intval(count($chartOfAccountArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    $d++;
                                                                    if ($i != 0) {
                                                                        if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                            echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                                        }
                                                                    } else {
                                                                        echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                                    }
                                                                    $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                                    if (isset($chartOfAccountSliceDetailArray[$j]['chartOfAccountId'])) {
                                                                        if ($chartOfAccountSliceDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
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
                                                                        - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" style="text-align:right" type="text"
                                                           name="chartOfAccountSliceDetailAmount[]"
                                                           id="chartOfAccountSliceDetailAmount<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"
                                                           value="<?php
                                                           if (isset($chartOfAccountSliceDetailArray) && is_array(
                                                                           $chartOfAccountSliceDetailArray
                                                                   )
                                                           ) {
                                                               if (isset($chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailAmount'])) {
                                                                   echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailAmount'];
                                                               }
                                                           }
                                                           ?>"></td>
                                                    <?php
                                                    $debit = 0;
                                                    $credit = 0;
                                                    $x = 0;
                                                    $y = 0;
                                                    $d = $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailAmount'];
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
                                                <td>
                                                    <div
                                                        id="debit_<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"
                                                        class="pull-right"><?php echo $debit; ?></div>
                                                </td>
                                                <td>
                                                    <div
                                                        id="credit_<?php echo $chartOfAccountSliceDetailArray[$j]['chartOfAccountSliceDetailId']; ?>"
                                                        class="pull-right"><?php echo $credit; ?></div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" valign="top" align="center"><?php
                                                $chartOfAccountSliceDetail->exceptionMessage(
                                                        $t['recordNotFoundLabel']
                                                );
                                                ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" valign="top" align="center"><?php
                                            $chartOfAccountSliceDetail->exceptionMessage(
                                                    $t['recordNotFoundLabel']
                                            );
                                            ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                            if ($totalDebit == abs($totalCredit)) {
                                $balanceColor = 'success';
                            } else {
                                $balanceColor = 'warning';
                            }
                            ?>
                            <tr class="<?php echo $balanceColor; ?>" id="totalBalance">
                                <td colspan="4">&nbsp;</td>
                                <td align="right">
                                    <div class="pull-right" id="totalDebit"><?php
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
                                        ?></div>
                                </td>
                                <td align="right">
                                    <div class="pull-right" id="totalCredit"><?php echo $totalCredit; ?></div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
        </div></form>
    <script type="text/javascript">
        $(document).keypress(function(e) {

            // shift+n new record event
            if (e.which === 78 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    newRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountSlice->getControllerPath(); ?>', '<?php echo $chartOfAccountSlice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                return false;
            }
            // shift+s save event
            if (e.which === 83 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    updateRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountSlice->getControllerPath(); ?>', '<?php echo $chartOfAccountSlice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                return false;
            }
            // shift+d delete event
            if (e.which === 88 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountSlice->getControllerPath(); ?>', '<?php echo $chartOfAccountSlice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
    <?php } ?>
            }
            // shift+f.find event
            if (e.which === 18 && e.shiftKey) {
                findRecord();

                return false;
            }
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountSlice->getControllerPath(); ?>', '<?php echo $chartOfAccountSlice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountSlice->getControllerPath(); ?>', '<?php echo $chartOfAccountSlice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
            }


        });
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('chartOfAccountSliceId');
            validateMeNumeric('chartOfAccountId');

            $('#chartOfAccountSliceDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeCurrency('chartOfAccountSliceAmount');
            validateMeNumericRange('chartOfAccountSliceDetailId');
            validateMeNumericRange('chartOfAccountSliceId');
            validateMeNumericRange('chartOfAccountId');
            validateMeAlphaNumericRange('journalNumber');
            validateMeCurrencyRange('chartOfAccountSliceDetailAmount');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['chartOfAccountSliceId']) {
            ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                    $('#newRecordButton3').attr('onClick', '');
                    $('#newRecordButton4').attr('onClick', '');
                    $('#newRecordButton5').attr('onClick', '');
                    $('#newRecordButton6').attr('onClick', '');
                    $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountSlice->getControllerPath(); ?>','<?php echo $chartOfAccountSlice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');

            <?php } ?>
            <?php
        }
    }
    ?>
            function getChartOfAccountAmount(leafId, url, securityToken) {
                // un hide button search
                // unlimited for searching because  lazy paging.
                $.ajax({
                    type: 'GET',
                    url: url,
                    data: {
                        offset: 0,
                        limit: 99999,
                        method: 'chartOfAccountAmount',
                        type: 'filter',
                        securityToken: securityToken,
                        leafId: leafId,
                        chartOfAccountId: $("#chartOfAccountId").val()
                    },
                    beforeSend: function() {
                        var smileyRoll = './images/icons/smiley-roll.png';
                        var $infoPanel = $('#infoPanel');
                        $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        var smileyRoll = './images/icons/smiley-roll.png';
                        var smileyLol = './images/icons/smiley-lol.png';
                        var $infoPanel = $('#infoPanel');
                        var success = data.success;
                        var message = data.message;
                        if (data.success === false) {
                            $infoPanel.html('').empty().html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");

                        } else {
                            $("#chartOfAccountSliceAmount").val().val(data.totalFigure);
                            $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();

                        }
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    },
                    error: function(xhr) {
                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                        $('#infoError').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                    }
                });
            }
        });
    </script>

<?php } ?>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/chartOfAccountSlice.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>