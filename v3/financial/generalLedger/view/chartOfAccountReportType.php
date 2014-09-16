<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/chartOfAccountReportTypeController.php");
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

$translator->setCurrentTable('chartofaccountreporttype');

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
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $chartOfAccountReportType = new \Core\Financial\GeneralLedger\ChartOfAccountReportType\Controller\ChartOfAccountReportTypeClass( );
        define("LIMIT", 10);
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
            $chartOfAccountReportType->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $chartOfAccountReportType->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $chartOfAccountReportType->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $chartOfAccountReportType->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode("-", $_POST ['dateRangeStart']);
            $chartOfAccountReportType->setStartDay($start[2]);
            $chartOfAccountReportType->setStartMonth($start[1]);
            $chartOfAccountReportType->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $chartOfAccountReportType->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode("-", $_POST ['dateRangeEnd']);
            $chartOfAccountReportType->setEndDay($start[2]);
            $chartOfAccountReportType->setEndMonth($start[1]);
            $chartOfAccountReportType->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $chartOfAccountReportType->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $chartOfAccountReportType->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $chartOfAccountReportType->setServiceOutput('html');
        $chartOfAccountReportType->setLeafId($leafId);
        $chartOfAccountReportType->execute();
        if ($_POST['method'] == 'read') {
            $chartOfAccountReportType->setStart($offset);
            $chartOfAccountReportType->setLimit($limit); // normal system don't like paging..
            $chartOfAccountReportType->setPageOutput('html');
            $chartOfAccountReportTypeArray = $chartOfAccountReportType->read();
            if (isset($chartOfAccountReportTypeArray [0]['firstRecord'])) {
                $firstRecord = $chartOfAccountReportTypeArray [0]['firstRecord'];
            }
            if (isset($chartOfAccountReportTypeArray [0]['nextRecord'])) {
                $nextRecord = $chartOfAccountReportTypeArray [0]['nextRecord'];
            }
            if (isset($chartOfAccountReportTypeArray [0]['previousRecord'])) {
                $previousRecord = $chartOfAccountReportTypeArray [0]['previousRecord'];
            }
            if (isset($chartOfAccountReportTypeArray [0]['lastRecord'])) {
                $lastRecord = $chartOfAccountReportTypeArray [0]['lastRecord'];
                $endRecord = $chartOfAccountReportTypeArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($chartOfAccountReportType->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($chartOfAccountReportTypeArray [0]['total'])) {
                $total = $chartOfAccountReportTypeArray [0]['total'];
            } else {
                $total = 0;
            }
            $navigation->setTotalRecord($total);
        }
    }
}
?>
<script type='text/javascript'>
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
                echo $template->breadcrumb(
                        $applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId
                );
                ?>
            </div>
        </div>
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div class="row">
            <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10">
                <button title='A' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'A');">A</button>
                <button title='B' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'B');">B</button>
                <button title='C' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'C');">C</button>
                <button title='D' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'D');">D</button>
                <button title='E' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'E');">E</button>
                <button title='F' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'F');">F</button>
                <button title='G' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'G');">G</button>
                <button title='H' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'H');">H</button>
                <button title='I' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'I');">I</button>
                <button title='J' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'J');">J</button>
                <button title='K' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'K');">K</button>
                <button title='L' class="btn btn-success btn-sm" type="button" 
                        onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'L');">L</button>
                <button title='M' class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'M');">M</button>
                <button title='N' class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath();
                        ?>', '<?php echo $securityToken; ?>', 'N');">N</button>
                <button title='O' class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'O');">O</button>
                <button title='P' class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'P');">P</button>
                <button title='Q'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button>
                <button title='R'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'R');">R</button>
                <button title='S'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'S');">S</button>
                <button title='T'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'T');">T</button>
                <button title='U'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'U');">U</button>
                <button title='V'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'V');">V</button>
                <button title='W'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'W');">W</button>
                <button title='X'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'X');">X</button>
                <button title='Y'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button>
                <button title='Z'
                        class="btn btn-success btn-sm"
                        type="button" 
                        onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                        echo $chartOfAccountReportType->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button>
            </div>
            <div class="control-label col-xs-2 col-sm-2 col-md-2">
                <div class="pull-right">
                    <div class='btn-group'>
                        <button class='btn-warning'> <i class='glyphicon glyphicon-print glyphicon-white'></i> </button>
                        <button data-toggle="dropdown" class='btn-warning dropdown-toggle'> <span class='caret'></span> </button>
                        <ul class='dropdown-menu'>
                            <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                echo $chartOfAccountReportType->getControllerPath();
                                ?>', '<?php echo $securityToken; ?>', 'excel');"> <i class='pull-right glyphicon glyphicon-download'></i>Excel 2007 </a> </li>
                            <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                echo $chartOfAccountReportType->getControllerPath();
                                ?>', '<?php echo $securityToken; ?>', 'csv');"> <i class='pull-right glyphicon glyphicon-download'></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a> </li>
                            <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                echo $chartOfAccountReportType->getControllerPath();
                                ?>', '<?php echo $securityToken; ?>', 'html');"> <i class='pull-right glyphicon glyphicon-download'></i>&nbsp;&nbsp;Html&nbsp;&nbsp; </a> </li>
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
                            <div id='btnList'>
                                <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                        class=" btn btn-info btn-xs"
                                        onclick="showForm(<?php echo $leafId; ?>, '<?php
                                        echo $chartOfAccountReportType->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>');"> <?php echo $t['newButtonLabel']; ?></button>
                            </div>
                            <hr>
                            <input type="text" class="form-control" name="queryWidget" id="queryWidget" value="<?php
                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                echo $_POST['query'];
                            }
                            ?>">
                            <br>
                            <input type="button"  name="searchString" id="searchString"
                                   value="<?php echo $t['searchButtonLabel']; ?>"
                                   class="btn btn-warning btn-block"
                                   onclick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountReportType->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchString" id="clearSearchString"
                                   value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                   onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountReportType->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">
                            <hr>
                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                          echo $chartOfAccountReportType->getViewPath();
                                                          ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                                          echo date(
                                                                  'd-m-Y'
                                                          );
                                                          ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title='Previous Day <?php echo $previousDay; ?>'
                                                         onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                         echo $chartOfAccountReportType->getViewPath();
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                          echo $chartOfAccountReportType->getViewPath();
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title='Next Day <?php echo $nextDay; ?>'
                                                        onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                        echo $chartOfAccountReportType->getViewPath();
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title='Previous Week<?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'previous'
                                        );
                                        ?>' onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                         echo $chartOfAccountReportType->getViewPath();
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png"></td>
                                    <td align="center"><a href="javascript:void(0)" title='<?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'current'
                                        );
                                        ?>' onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                          echo $chartOfAccountReportType->getViewPath();
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title='Next Week <?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'next'
                                        );
                                        ?>' onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                        echo $chartOfAccountReportType->getViewPath();
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title='Previous Month <?php echo $previousMonth; ?>'
                                                         onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                         echo $chartOfAccountReportType->getViewPath();
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-month.png"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                          echo $chartOfAccountReportType->getViewPath();
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                        title='Next Month <?php echo $nextMonth; ?>'
                                                        onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                        echo $chartOfAccountReportType->getViewPath();
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title='Previous Year <?php echo $previousYear; ?>'
                                                         onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                         echo $chartOfAccountReportType->getViewPath();
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar.png"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                          echo $chartOfAccountReportType->getViewPath();
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                        title='Next Year <?php echo $nextYear; ?>'
                                                        onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                        echo $chartOfAccountReportType->getViewPath();
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
                                </tr>
                            </table>
                            <div>
                                <label for="dateRangeStart"></label>
                                <input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                if (isset($_POST['dateRangeStart'])) {
                                    echo $_POST['dateRangeStart'];
                                }
                                ?>" onclick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                <label for="dateRangeEnd"></label>
                                <input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                if (isset($_POST['dateRangeEnd'])) {
                                    echo $_POST['dateRangeEnd'];
                                }
                                ?>" onclick="topPage(175);">
                                <br>
                                <input type="button"  name="searchDate" id="searchDate"
                                       value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block"
                                       onclick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountReportType->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountReportType->getViewPath();
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
                            <div class='modal-body'>
                                <form class="form-horizontal">
                                    <input type="hidden" name='chartOfAccountReportTypeIdPreview'
                                           id='chartOfAccountReportTypeIdPreview'>
                                    <div class='form-group' id='chartOfAccountReportTypeCodeDiv'>
                                        <label for="chartOfAccountReportTypeCodePreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountReportTypeCodeLabel']; ?></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text"
                                                   name='chartOfAccountReportTypeCodePreview'
                                                   id='chartOfAccountReportTypeCodePreview'>
                                        </div>
                                    </div>
                                    <div class='form-group' id='chartOfAccountReportTypeDescriptionDiv'>
                                        <label for="chartOfAccountReportTypeDescriptionDiv"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountReportTypeDescriptionlabel']; ?></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text"
                                                   name='chartOfAccountReportTypeDescriptionPreview'
                                                   id='chartOfAccountReportTypeDescPreview'>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class='modal-footer'>
                                <button type="button"  class="btn btn-danger"
                                        onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountReportType->getControllerPath(); ?>', '<?php echo $chartOfAccountReportType->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
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
                                        <th width="25px" align="center">#</th>
                                        <th width="100px"><?php echo ucfirst($t['actionTextLabel']); ?></th>
                                        <th width="100px"><?php
                                            echo ucwords(
                                                    $leafTranslation['chartOfAccountReportTypeCodeLabel']
                                            );
                                            ?></th>
                                        <th><?php
                                            echo ucwords(
                                                    $leafTranslation['chartOfAccountReportTypeDescriptionlabel']
                                            );
                                            ?></th>
                                        <th width="100px"> <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                </th>
                                <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                <th width="25px" align="center"> <input type="checkbox" name="check_all" id="check_all"
                                                                        alt="Check Record" onChange="toggleChecked(this.checked);">
                                </th>
                                </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($chartOfAccountReportTypeArray)) {
                                            $totalRecord = intval(count($chartOfAccountReportTypeArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td>" . ($counter + $offset) . "</td>";
                                                    echo "<td><div class='btn-group'>
                                 <a class=' btn-warning btn-xs' title='Edit' onClick=\"showFormUpdate(\"" . $leafId . "\",\"" . $chartOfAccountReportType->getControllerPath(
                                                    ) . "\",\"" . $chartOfAccountReportType->getViewPath(
                                                    ) . "\",\"" . $securityToken . "\",\"" . intval(
                                                            $chartOfAccountReportTypeArray [$i]['chartOfAccountReportTypeId']
                                                    ) . "\",\"" . $leafAccess['leafAccessUpdateValue'] . "\")\"><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>
                                 <a class=' btn-danger btn-xs' title='Delete' onClick=\"showModalDelete(\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['chartOfAccountReportTypeId']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['chartOfAccountReportTypeSequence']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['chartOfAccountReportTypeCode']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['chartOfAccountReportTypeDescription']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isDefault']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isNew']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isDraft']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isUpdate']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isDelete']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isActive']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isApproved']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isReview']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['isPost']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['executeBy']
                                                    ) . "\",\"" . rawurlencode(
                                                            $chartOfAccountReportTypeArray [$i]['executeTime']
                                                    ) . "\')\"><i class='glyphicontrash  glyphicon-white'></i></a></td>";

                                                    if (isset($chartOfAccountReportTypeArray[$i]['chartOfAccountReportTypeCode'])) {
                                                        echo "<td><div class=\"pull-left\">" . $chartOfAccountReportTypeArray[$i]['chartOfAccountReportTypeCode'] . "</div></td>";
                                                    } else {
                                                        echo "<td>&nbsp;</td>";
                                                    }
                                                    if (isset($chartOfAccountReportTypeArray[$i]['chartOfAccountReportTypeDescription'])) {
                                                        echo "<td><div class=\"pull-left\">" . $chartOfAccountReportTypeArray[$i]['chartOfAccountReportTypeDescription'] . "</div></td>";
                                                    } else {
                                                        echo "<td>&nbsp;</td>";
                                                    }
                                                    if (isset($chartOfAccountReportTypeArray[$i]['executeBy'])) {
                                                        echo "<td>" . $chartOfAccountReportTypeArray[$i]['staffName'] . "</td>";
                                                    } else {
                                                        echo "<td>&nbsp;</td>";
                                                    }
                                                    if (isset($chartOfAccountReportTypeArray[$i]['executeTime'])) {
                                                        $valueArray = $chartOfAccountReportTypeArray[$i]['executeTime'];
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
                                                        echo "<td>" . $value . "</td>";
                                                    } else {
                                                        echo "<td>&nbsp;</td>";
                                                    }
                                                    if ($chartOfAccountReportTypeArray[$i]['isDelete']) {
                                                        $checked = 'checked';
                                                    } else {
                                                        $checked = null;
                                                    }
                                                    echo "<td>
    <input style='display:none;' type=\"checkbox\" name='chartOfAccountReportTypeId[]'  value='" . $chartOfAccountReportTypeArray[$i]['chartOfAccountReportTypeId'] . "'>
    <input " . $checked . " type=\"checkbox\" name='isDelete[]' value='" . $chartOfAccountReportTypeArray[$i]['isDelete'] . "'>
    
</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" valign="top" align="center"><?php
                                                        $chartOfAccountReportType->exceptionMessage(
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
                                                    $chartOfAccountReportType->exceptionMessage(
                                                            $t['recordNotFoundLabel']
                                                    );
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6"><?php
                                                $chartOfAccountReportType->exceptionMessage(
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
                    <div class="col-xs-9 col-sm-9 col-md-9 pull-left">
                        <?php $navigation->pagenationv4($offset); ?>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 pagination">
                        <div class="pull-right">
                            <button class="delete btn btn-warning" type="button" 
                                    onclick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountReportType->getControllerPath();
                                    ?>', '<?php
                                    echo $chartOfAccountReportType->getViewPath();
                                    ?>', '<?php echo $securityToken; ?>');"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
                        </div>
                    </div>
                </div>
                <script type='text/javascript'>
                    $(document).ready(function() {
                        $('#dateRangeStart').datepicker({
                            format: 'd-m-yyyy'
                        });
                        $('#dateRangeEnd').datepicker({
                            format: 'd-m-yyyy'
                        });
                    });

                </script>
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
                        <div class="panel-body">
                            <input type="hidden" name='chartOfAccountReportTypeId' id='chartOfAccountReportTypeId' value="<?php
                            if (isset($_POST['chartOfAccountReportTypeId'])) {
                                echo $_POST['chartOfAccountReportTypeId'];
                            }
                            ?>">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id='chartOfAccountReportTypeCodeForm'>
                                        <label for="chartOfAccountReportTypeCode"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountReportTypeCodeLabel']; ?></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="chartOfAccountReportTypeCode"
                                                   id="chartOfAccountReportTypeCode" onkeyup="removeMeError('chartOfAccountReportTypeCode');"
                                                   value="<?php
                                                   if (isset($chartOfAccountReportTypeArray) && is_array(
                                                                   $chartOfAccountReportTypeArray
                                                           )
                                                   ) {
                                                       echo $chartOfAccountReportTypeArray[0]['chartOfAccountReportTypeCode'];
                                                   }
                                                   ?>" maxlength="16">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group" id='chartOfAccountReportTypeDescriptionForm'>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <textarea class="form-control" name='chartOfAccountReportTypeDescription'
                                                          id='chartOfAccountReportTypeDescription'><?php
                                                              if (isset($chartOfAccountReportTypeArray) && is_array($chartOfAccountReportTypeArray)) {
                                                                  echo $chartOfAccountReportTypeArray[0]['chartOfAccountReportTypeDescription'];
                                                              }
                                                              ?>
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer" align="center">
                                    <div class="btn-group">
                                        <div class='btn-group'>
                                            <div class='btn-group' align="left"> <a id='newRecordButton1' href="javascript:void(0)" class="btn btn-success disabled"><i
                                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> <a id='newRecordButton2' href="javascript:void(0)" data-toggle="dropdown"
                                                                                                                                                  class="btn dropdown-toggle btn-success disabled"><span class="caret"></span></a>
                                                <ul class='dropdown-menu'>
                                                    <li> <a id="newRecordButton3" href="javascript:void(0)"><i
                                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?> </a> </li>
                                                    <li> <a id="newRecordButton4" href="javascript:void(0)"><i
                                                                class='glyphicon glyphicon-edit'></i><?php echo $t['newUpdateButtonLabel']; ?> </a> </li>
    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class='glyphicon glyphicon-print'></i><?php // echo $t['newPrintButtonLabel'];                             ?></a></li>-->
    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class='glyphicon glyphicon-print'></i><?php // echo $t['newUpdatePrintButtonLabel'];                            ?></a></li>-->
                                                    <li> <a id="newRecordButton7" href="javascript:void(0)"><i
                                                                class='glyphicon glyphicon-list'></i><?php echo $t['newListingButtonLabel']; ?> </a> </li>
                                                </ul>
                                            </div>
                                            <div class='btn-group' align="left"> <a id='updateRecordButton1' href="javascript:void(0)" class="btn btn-info disabled"><i
                                                        class="glyphicon glyphicon-edit glyphicon-white"></i><?php echo $t['updateButtonLabel']; ?> </a> <a id='updateRecordButton2' href="javascript:void(0)" data-toggle="dropdown"
                                                                                                                                                    class="btn dropdown-toggle btn-info disabled"><span class='caret'></span></a>
                                                <ul class='dropdown-menu'>
                                                    <li> <a id="updateRecordButton3" href="javascript:void(0)"><i
                                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?> </a></li>
    <!---<li><a id='updateRecordButton4' href="javascript:void(0)" class="disabled"><i class='glyphicon glyphicon-print'></i><?php //echo $t['updateButtonPrintLabel'];                             ?></a></li> -->
                                                    <li> <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                                class='glyphicon glyphicon-list-alt'></i> <?php echo $t['updateListingButtonLabel']; ?> </a></li>
                                                </ul>
                                            </div>
                                            <div class='btn-group'>
                                                <button type="button"  id='deleteRecordButton' class="btn btn-danger disabled"><i
                                                        class='glyphicon glyphicon-trash glyphicon-white'></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                                            </div>
                                            <div class='btn-group'> <a id='resetRecordButton' href="javascript:void(0)" class=" btn btn-info"
                                                                       onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $chartOfAccountReportType->getControllerPath();
                                                                       ?>', '<?php echo $chartOfAccountReportType->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </a> </div>

                                            <div class='btn-group'> <a id='listRecordButton' href="javascript:void(0)" class=" btn btn-info"
                                                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                                                       echo $chartOfAccountReportType->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>);"><i
                                                        class='glyphicon glyphicon-list glyphicon-white'></i> <?php echo $t['gridButtonLabel']; ?> </a> </div>
                                            <div class='btn-group'> <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                                                       onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $chartOfAccountReportType->getControllerPath();
                                                                       ?>', '<?php echo $chartOfAccountReportType->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </a> </div>
                                            <div class='btn-group'> <a id='previousRecordButton' href="javascript:void(0)" class=' btn btn-info disabled'
                                                                       onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $chartOfAccountReportType->getControllerPath();
                                                                       ?>', '<?php echo $chartOfAccountReportType->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </a> </div>
                                            <div class='btn-group'> <a id='nextRecordButton' href="javascript:void(0)" class=' btn btn-info disabled'
                                                                       onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $chartOfAccountReportType->getControllerPath();
                                                                       ?>', '<?php echo $chartOfAccountReportType->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-forward glyphicon-white"></i><?php echo $t['nextButtonLabel']; ?> </a> </div>
                                            <div class='btn-group'> <a id='endRecordButton' href="javascript:void(0)" class=" btn btn-info"
                                                                       onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $chartOfAccountReportType->getControllerPath();
                                                                       ?>', '<?php echo $chartOfAccountReportType->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </a> </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name='firstRecord' id='firstRecord' value="<?php
                                    if (isset($firstRecord)) {
                                        echo $firstRecord;
                                    }
                                    ?>">
                                    <input type="hidden" name='nextRecord' id='nextRecord' value="<?php
                                    if (isset($nextRecord)) {
                                        echo $nextRecord;
                                    }
                                    ?>">
                                    <input type="hidden" name='previousRecord' id='previousRecord' value="<?php
                                    if (isset($previousRecord)) {
                                        echo $previousRecord;
                                    }
                                    ?>">
                                    <input type="hidden" name='lastRecord' id='lastRecord' value="<?php
                                    if (isset($lastRecord)) {
                                        echo $lastRecord;
                                    }
                                    ?>">
                                    <input type="hidden" name='endRecord' id='endRecord' value="<?php
                                           if (isset($endRecord)) {
                                               echo $endRecord;
                                           }
                                           ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type='text/javascript'>
        $(document).keypress(function(e) {

            // shift+n new record event
            if (e.which === 78 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    newRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountReportType->getControllerPath(); ?>', '<?php echo $chartOfAccountReportType->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                return false;
            }
            // shift+s save event
            if (e.which === 83 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    updateRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountReportType->getControllerPath(); ?>', '<?php echo $chartOfAccountReportType->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                return false;
            }
            // shift+d delete event
            if (e.which === 88 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountReportType->getControllerPath(); ?>', '<?php echo $chartOfAccountReportType->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

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
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountReportType->getControllerPath(); ?>', '<?php echo $chartOfAccountReportType->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountReportType->getControllerPath(); ?>', '<?php echo $chartOfAccountReportType->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                    break;
            }


        });
        $(document).ready(function() {
            $(".chzn-select").chosen();
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('chartOfAccountReportTypeId');
            validateMeNumeric('chartOfAccountReportTypeSequence');
            validateMeAlphaNumeric('chartOfAccountReportTypeCode');
            validateMeAlphaNumeric('chartOfAccountReportTypeDescription');
    <?php if ($_POST['method'] == 'new') { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"" + 1 + "\")");
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"" + 1 + "\")");
                    $('#newRecordButton4').attr('onClick', "newRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"" + 2 + "\")");
                    $('#newRecordButton5').attr('onClick', "newRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"" + 3 + "\")");
                    $('#newRecordButton6').attr('onClick', "newRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"" + 4 + "\")");
                    $('#newRecordButton7').attr('onClick', "newRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"" + 5 + "\")");
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
        if ($_POST['chartOfAccountReportTypeId']) {
            ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                    $('#newRecordButton3').attr('onClick', '');
                    $('#newRecordButton4').attr('onClick', '');
                    $('#newRecordButton5').attr('onClick', '');
                    $('#newRecordButton6').attr('onClick', '');
                    $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",1,\"<?php echo $leafAccess['leafAccessDeleteValue']; ?>\")");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",1,\"<?php echo $leafAccess['leafAccessDeleteValue']; ?>\")");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",2,\"<?php echo $leafAccess['leafAccessDeleteValue']; ?>\")");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",3,\"<?php echo $leafAccess['leafAccessDeleteValue']; ?>\")");
            <?php } else { ?>
                        $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(\"<?php echo $leafId; ?>\",\"<?php echo $chartOfAccountReportType->getControllerPath(); ?>\",\"<?php echo $chartOfAccountReportType->getViewPath(); ?>\",\"<?php echo $securityToken; ?>\",\"<?php echo $leafAccess['leafAccessDeleteValue']; ?>\")");
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
<script type='text/javascript' src='./v3/financial/generalLedger/javascript/chartofaccountreporttype.js'></script>
