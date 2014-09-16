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
require_once($newFakeDocumentRoot . "v3/humanResource/employment/controller/employeeController.php");
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
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable('employee');
if (isset($_POST['leafId'])) {
    $leafId = intval($_POST['leafId'] * 1);
} else if (isset($_GET['leafId'])) {
    $leafId = intval($_GET['leafId'] * 1);
} else {
    // redirect to main page if no id

    header("index.php");
    exit();
}
if ($leafId == 0) {
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
$employeeArray = array();
$cityArray = array();
$stateArray = array();
$countryArray = array();
$jobArray = array();
$genderArray = array();
$marriageArray = array();
$raceArray = array();
$religionArray = array();
$employmentStatusArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $employee = new \Core\HumanResource\Employment\Employee\Controller\EmployeeClass();
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
            $employee->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $employee->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $employee->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $employee->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $employee->setStartDay($start[2]);
            $employee->setStartMonth($start[1]);
            $employee->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $employee->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $employee->setEndDay($start[2]);
            $employee->setEndMonth($start[1]);
            $employee->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $employee->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $employee->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $employee->setServiceOutput('html');
        $employee->setLeafId($leafId);
        $employee->execute();
        $cityArray = $employee->getCity();
        $stateArray = $employee->getState();
        $countryArray = $employee->getCountry();
        $jobArray = $employee->getJob();
        $genderArray = $employee->getGender();
        $marriageArray = $employee->getMarriage();
        $raceArray = $employee->getRace();
        $religionArray = $employee->getReligion();
        $employmentStatusArray = $employee->getEmploymentStatus();
        if ($_POST['method'] == 'read') {
            $employee->setStart($offset);
            $employee->setLimit($limit); // normal system don't like paging..
            $employee->setPageOutput('html');
            $employeeArray = $employee->read();
            if (isset($employeeArray [0]['firstRecord'])) {
                $firstRecord = $employeeArray [0]['firstRecord'];
            }
            if (isset($employeeArray [0]['nextRecord'])) {
                $nextRecord = $employeeArray [0]['nextRecord'];
            }
            if (isset($employeeArray [0]['previousRecord'])) {
                $previousRecord = $employeeArray [0]['previousRecord'];
            }
            if (isset($employeeArray [0]['lastRecord'])) {
                $lastRecord = $employeeArray [0]['lastRecord'];
                $endRecord = $employeeArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($employee->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($employeeArray [0]['total'])) {
                $total = $employeeArray [0]['total'];
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
                <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <button title="A" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $employee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');">
                        Z
                    </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2 col-lg-2">
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
                                    echo $employee->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $employee->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $employee->getControllerPath();
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
                            <div id="btnList">

                                <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                        class="btn btn-info btn-block"
                                        onClick="showForm(<?php echo $leafId; ?>, '<?php
                                        echo $employee->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>');">
                                    <?php echo $t['newButtonLabel']; ?></button>
                            </div>
                            <br>
                            <label for="queryWidget"></label><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        echo $_POST['query'];
                                                                    }
                                                                    ?>"> <br>
                            <input type="button"  name="searchString" id="searchString"
                                   value="<?php echo $t['searchButtonLabel']; ?>"
                                   class="btn btn-warning btn-block"
                                   onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                   echo $employee->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchString" id="clearSearchString"
                                   value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $employee->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"> <br>

                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center">
                                        <img src="./images/icons/calendar-select-days-span.png"
                                             alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center">
                                        <a href="javascript:void(0)"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
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
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                    </td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                            alt="<?php echo $t['dayTextLabel'] ?>">
                                    </td>
                                    <td align="center">
                                        <a href="javascript:void(0)"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
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
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                    </td>
                                    <td align="center">
                                        <img src="./images/icons/calendar-select-week.png"
                                             alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" title="<?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'current'
                                        );
                                        ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                          echo $employee->getViewPath();
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'next'
                                        );
                                        ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <a href="javascript:void(0)" rel="tooltip"
                                           title="Previous Month <?php echo $previousMonth; ?>"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                    </td>
                                    <td align="center">
                                        <img src="./images/icons/calendar-select-month.png"
                                             alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                    <td align="center">
                                        <a href="javascript:void(0)"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip"
                                           title="Next Month <?php echo $nextMonth; ?>"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <a href="javascript:void(0)" rel="tooltip"
                                           title="Previous Year <?php echo $previousYear; ?>"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                    </td>
                                    <td align="center"><img src="./images/icons/calendar.png"
                                                            alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                    <td align="center">
                                        <a href="javascript:void(0)"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip"
                                           title="Next Year <?php echo $nextYear; ?>"
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $employee->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                            </table>
                            <div class="input-group">
                                <input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                if (isset($_POST['dateRangeStart'])) {
                                    echo $_POST['dateRangeStart'];
                                }
                                ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="startDateImage"></span></div>
                            <br>
                            <div class="input-group">
                                <input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                if (isset($_POST['dateRangeEnd'])) {
                                    echo $_POST['dateRangeEnd'];
                                }
                                ?>" onClick="topPage(175);"  placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>">
                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="endDateImage"></span></div>
                            <br>
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
                                        <input type="hidden" name="employeeIdPreview" id="employeeIdPreview">

                                        <div class="form-group" id="cityIdDiv">
                                            <label for="cityIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['cityIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="cityIdPreview" id="cityIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="stateIdDiv">
                                            <label for="stateIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['stateIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="stateIdPreview" id="stateIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="countryIdDiv">
                                            <label for="countryIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['countryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="countryIdPreview"
                                                       id="countryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="jobIdDiv">
                                            <label for="jobIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['jobIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="jobIdPreview" id="jobIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="genderIdDiv">
                                            <label for="genderIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['genderIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="genderIdPreview"
                                                       id="genderIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="marriageIdDiv">
                                            <label for="marriageIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['marriageIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="marriageIdPreview"
                                                       id="marriageIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="raceIdDiv">
                                            <label for="raceIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['raceIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="raceIdPreview" id="raceIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="religionIdDiv">
                                            <label for="religionIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['religionIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="religionIdPreview"
                                                       id="religionIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employmentStatusIdDiv">
                                            <label for="employmentStatusIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employmentStatusIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employmentStatusIdPreview"
                                                       id="employmentStatusIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="nationalNumberDiv">
                                            <label for="nationalNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['nationalNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="nationalNumberPreview"
                                                       id="nationalNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="licenseNumberDiv">
                                            <label for="licenseNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['licenseNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="licenseNumberPreview"
                                                       id="licenseNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeNumberDiv">
                                            <label for="employeeNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeNumberPreview"
                                                       id="employeeNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeFirstNameDiv">
                                            <label for="employeeFirstNamePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeFirstNameLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeFirstNamePreview"
                                                       id="employeeFirstNamePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeePictureDiv">
                                            <label for="employeePicturePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeePictureLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeePicturePreview"
                                                       id="employeePicturePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeLastNameDiv">
                                            <label for="employeeLastNamePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeLastNameLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeLastNamePreview"
                                                       id="employeeLastNamePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeDateOfBirthDiv">
                                            <label for="employeeDateOfBirthPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeDateOfBirthLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeDateOfBirthPreview"
                                                       id="employeeDateOfBirthPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeDateHiredDiv">
                                            <label for="employeeDateHiredPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeDateHiredLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control" name="employeeDateHiredPreview"
                                                       id="employeeDateHiredPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeDateRetiredDiv">
                                            <label for="employeeDateRetiredPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeDateRetiredLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeDateRetiredPreview"
                                                       id="employeeDateRetiredPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeBusinessPhoneDiv">
                                            <label for="employeeBusinessPhonePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeBusinessPhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeBusinessPhonePreview"
                                                       id="employeeBusinessPhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeHomePhoneDiv">
                                            <label for="employeeHomePhonePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeHomePhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeHomePhonePreview"
                                                       id="employeeHomePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeMobilePhoneDiv">
                                            <label for="employeeMobilePhonePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeMobilePhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeMobilePhonePreview"
                                                       id="employeeMobilePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeFaxNumberDiv">
                                            <label for="employeeFaxNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeFaxNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeFaxNumberPreview"
                                                       id="employeeFaxNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeAddressDiv">
                                            <label for="employeeAddressPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeAddressLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeAddressPreview"
                                                       id="employeeAddressPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeePostCodeDiv">
                                            <label for="employeePostCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeePostCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeePostCodePreview"
                                                       id="employeePostCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeEmailDiv">
                                            <label for="employeeEmailPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeEmailLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control" name="employeeEmailPreview"
                                                       id="employeeEmailPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeFacebookDiv">
                                            <label for="employeeFacebookPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeFacebookLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeFacebookPreview"
                                                       id="employeeFacebookPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeTwitterDiv">
                                            <label for="employeeTwitterPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeTwitterLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control" name="employeeTwitterPreview"
                                                       id="employeeTwitterPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeLinkedInDiv">
                                            <label for="employeeLinkedInPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeLinkedInLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeLinkedInPreview"
                                                       id="employeeLinkedInPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeNotesDiv">
                                            <label for="employeeNotesPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeNotesLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeNotesPreview"
                                                       id="employeeNotesPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeChequePrintingDiv">
                                            <label for="employeeChequePrintingPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeChequePrintingLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="employeeChequePrintingPreview"
                                                       id="employeeChequePrintingPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                    echo $employee->getControllerPath();
                                    ?>', '<?php
                                    echo $employee->getViewPath();
                                    ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  onClick="showMeModal('deletePreview', 0);" class="btn btn-default"
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
                                    <th><?php echo ucwords($leafTranslation['employmentStatusIdLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['employeeNumberLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['employeeFirstNameLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['employeeBusinessPhoneLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['employeeMobilePhoneLabel']); ?></th>
                                    <th width="100px" align="center">
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
                                            if (is_array($employeeArray)) {
                                                $totalRecord = intval(count($employeeArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($employeeArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($employeeArray[$i]['isDraft'] == 1) {
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
                                                                            onClick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $employee->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $employee->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $employeeArray [$i]['employeeId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onClick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['cityDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['stateDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['countryDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['jobTitle']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['genderDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['marriageDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['raceDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['religionDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employmentStatusDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['nationalNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['licenseNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeFirstName']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeePicture']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeLastName']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeDateOfBirth']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeDateHired']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeDateRetired']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeBusinessPhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeHomePhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeMobilePhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeFaxNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeAddress']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeePostCode']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeEmail']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeFacebook']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeTwitter']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeLinkedIn']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeNotes']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $employeeArray [$i]['employeeChequePrinting']
                                                                            );
                                                                            ?>');">
                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($employeeArray[$i]['employmentStatusDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $employeeArray[$i]['employmentStatusDescription'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeArray[$i]['employmentStatusDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employmentStatusDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $employeeArray[$i]['employmentStatusDescription'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeArray[$i]['employmentStatusDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $employeeArray[$i]['employmentStatusDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employmentStatusDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $employeeArray[$i]['employmentStatusDescription'];
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
                                                                    if (isset($employeeArray[$i]['employeeNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($employeeArray[$i]['employeeNumber']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeArray[$i]['employeeNumber']
                                                                                    );
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeNumber'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($employeeArray[$i]['employeeNumber']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeArray[$i]['employeeNumber']
                                                                                        );
                                                                                    } else {
                                                                                        echo $employeeArray[$i]['employeeNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeNumber'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $employeeArray[$i]['employeeNumber'];
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
                                                                    if (isset($employeeArray[$i]['employeeFirstName'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($employeeArray[$i]['employeeFirstName']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeArray[$i]['employeeFirstName']
                                                                                    );
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeFirstName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($employeeArray[$i]['employeeFirstName']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeArray[$i]['employeeFirstName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $employeeArray[$i]['employeeFirstName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeFirstName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $employeeArray[$i]['employeeFirstName'];
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
                                                                    if (isset($employeeArray[$i]['employeeBusinessPhone'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($employeeArray[$i]['employeeBusinessPhone']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeArray[$i]['employeeBusinessPhone']
                                                                                    );
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeBusinessPhone'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($employeeArray[$i]['employeeBusinessPhone']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeArray[$i]['employeeBusinessPhone']
                                                                                        );
                                                                                    } else {
                                                                                        echo $employeeArray[$i]['employeeBusinessPhone'];
                                                                                    }
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeBusinessPhone'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $employeeArray[$i]['employeeBusinessPhone'];
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
                                                                    if (isset($employeeArray[$i]['employeeMobilePhone'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($employeeArray[$i]['employeeMobilePhone']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeArray[$i]['employeeMobilePhone']
                                                                                    );
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeMobilePhone'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($employeeArray[$i]['employeeMobilePhone']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeArray[$i]['employeeMobilePhone']
                                                                                        );
                                                                                    } else {
                                                                                        echo $employeeArray[$i]['employeeMobilePhone'];
                                                                                    }
                                                                                } else {
                                                                                    echo $employeeArray[$i]['employeeMobilePhone'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $employeeArray[$i]['employeeMobilePhone'];
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
                                                                    if (isset($employeeArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($employeeArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $employeeArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $employeeArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $employeeArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $employeeArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $employeeArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($employeeArray[$i]['executeTime'])) {
                                                                $valueArray = $employeeArray[$i]['executeTime'];
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
                                                                ?>
                                                                <td><?php echo $value; ?></td>
                                                            <?php } else { ?>
                                                                <td>&nbsp;</td>
                                                            <?php } ?>
                                                            <?php
                                                            if ($employeeArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <input style="display:none;" type="checkbox" name="employeeId[]"
                                                                       value="<?php echo $employeeArray[$i]['employeeId']; ?>">
                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                               value="<?php echo $employeeArray[$i]['isDelete']; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="11" vAlign="top" align="center"><?php
                                                            $employee->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="11" vAlign="top" align="center"><?php
                                                        $employee->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="11" vAlign="top" align="center"><?php
                                                    $employee->exceptionMessage(
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
                                        onClick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                        echo $employee->getControllerPath();
                                        ?>', '<?php echo $employee->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
                                       onClick="firstRecord(<?php echo $leafId; ?>, '<?php
                                       echo $employee->getControllerPath();
                                       ?>', '<?php
                                       echo $employee->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $employee->getControllerPath();
                                       ?>', '<?php
                                       echo $employee->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $employee->getControllerPath();
                                       ?>', '<?php
                                       echo $employee->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $employee->getControllerPath();
                                       ?>', '<?php
                                       echo $employee->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="employeeId" id="employeeId" value="<?php
                            if (isset($_POST['employeeId'])) {
                                echo $_POST['employeeId'];
                            }
                            ?>">
                            <fieldset>
                                <legend><?php echo $t['informationTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeAll(0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeAll(1);">
                                </legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-xs-9 col-sm-9 col-md-9">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--name-->
                                                        <div class="form-group" id="employeeFirstNameForm">
                                                            <label for="employeeFirstName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['employeeFirstNameLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="employeeFirstName" id="employeeFirstName"
                                                                       onKeyUp="removeMeError('employeeFirstName');" value="<?php
                                                                       if (isset($employeeArray) && is_array($employeeArray)) {
                                                                           if (isset($employeeArray[0]['employeeFirstName'])) {
                                                                               echo htmlentities($employeeArray[0]['employeeFirstName']);
                                                                           }
                                                                       }
                                                                       ?>"> <span class="help-block" id="employeeFirstNameHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!--end name-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--race-->
                                                        <div class="form-group" id="raceIdForm">
                                                            <label for="raceId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['raceIdLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <select name="raceId" id="raceId" class="chzn-select form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($raceArray)) {
                                                                        $totalRecord = intval(count($raceArray));
                                                                        if ($totalRecord > 0) {
                                                                            $d = 1;
                                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                                if (isset($employeeArray[0]['raceId'])) {
                                                                                    if ($employeeArray[0]['raceId'] == $raceArray[$i]['raceId']) {
                                                                                        $selected = "selected";
                                                                                    } else {
                                                                                        $selected = null;
                                                                                    }
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                                ?>
                                                                                <option
                                                                                    value="<?php echo $raceArray[$i]['raceId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                    . <?php echo $raceArray[$i]['raceDescription']; ?></option>
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
                                                                </select> <span class="help-block" id="raceIdHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end race-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--last name-->
                                                        <div class="form-group" id="employeeLastNameForm">
                                                            <label for="employeeLastName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['employeeLastNameLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="employeeLastName" id="employeeLastName"
                                                                      value="<?php
                                                                       if (isset($employeeArray) && is_array($employeeArray)) {
                                                                           if (isset($employeeArray[0]['employeeLastName'])) {
                                                                               echo htmlentities($employeeArray[0]['employeeLastName']);
                                                                           }
                                                                       }
                                                                       ?>"> <span class="help-block" id="employeeLastNameHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!--end last name-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--religion-->
                                                        <div class="form-group" id="religionIdForm">
                                                            <label for="religionId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['religionIdLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <select name="religionId" id="religionId" class="chzn-select form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($religionArray)) {
                                                                        $totalRecord = intval(count($religionArray));
                                                                        if ($totalRecord > 0) {
                                                                            $d = 1;
                                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                                if (isset($employeeArray[0]['religionId'])) {
                                                                                    if ($employeeArray[0]['religionId'] == $religionArray[$i]['religionId']) {
                                                                                        $selected = "selected";
                                                                                    } else {
                                                                                        $selected = null;
                                                                                    }
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                                ?>
                                                                                <option
                                                                                    value="<?php echo $religionArray[$i]['religionId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                    . <?php echo $religionArray[$i]['religionDescription']; ?></option>
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
                                                                </select> <span class="help-block" id="religionIdHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!--end religion-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--gender-->
                                                        <div class="form-group" id="genderIdForm">
                                                            <label for="genderId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['genderIdLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <select name="genderId" id="genderId" class="form-control chzn-select">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($genderArray)) {
                                                                        $totalRecord = intval(count($genderArray));
                                                                        if ($totalRecord > 0) {
                                                                            $d = 1;
                                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                                if (isset($employeeArray[0]['genderId'])) {
                                                                                    if ($employeeArray[0]['genderId'] == $genderArray[$i]['genderId']) {
                                                                                        $selected = "selected";
                                                                                    } else {
                                                                                        $selected = null;
                                                                                    }
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                                ?>
                                                                                <option
                                                                                    value="<?php echo $genderArray[$i]['genderId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                    . <?php echo $genderArray[$i]['genderDescription']; ?></option>
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
                                                                </select> <span class="help-block" id="genderIdHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end gender-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--marriage-->
                                                        <div class="form-group" id="marriageIdForm">
                                                            <label for="marriageId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['marriageIdLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <select name="marriageId" id="marriageId" class="chzn-select form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($marriageArray)) {
                                                                        $totalRecord = intval(count($marriageArray));
                                                                        if ($totalRecord > 0) {
                                                                            $d = 1;
                                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                                if (isset($employeeArray[0]['marriageId'])) {
                                                                                    if ($employeeArray[0]['marriageId'] == $marriageArray[$i]['marriageId']) {
                                                                                        $selected = "selected";
                                                                                    } else {
                                                                                        $selected = null;
                                                                                    }
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                                ?>
                                                                                <option
                                                                                    value="<?php echo $marriageArray[$i]['marriageId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                    . <?php echo $marriageArray[$i]['marriageDescription']; ?></option>
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
                                                                </select><span class="help-block" id="marriageIdHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end marriage-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!-- start national number -->
                                                        <div class="form-group" id="nationalNumberForm">
                                                            <label for="nationalNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['nationalNumberLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="nationalNumber" id="nationalNumber"
                                                                           value="<?php
                                                                           if (isset($employeeArray) && is_array($employeeArray)) {
                                                                               if (isset($employeeArray[0]['nationalNumber'])) {
                                                                                   echo htmlentities($employeeArray[0]['nationalNumber']);
                                                                               }
                                                                           }
                                                                           ?>"> <span class="input-group-addon"><img
                                                                            src="./images/icons/license-key.png"></span>
                                                                </div>
                                                                <span class="help-block" id="nationalNumberHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end national number -->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!-- start license number -->
                                                        <div class="form-group" id="licenseNumberForm">
                                                            <label for="licenseNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['licenseNumberLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="licenseNumber" id="licenseNumber"
                                                                           value="<?php
                                                                           if (isset($employeeArray) && is_array($employeeArray)) {
                                                                               if (isset($employeeArray[0]['licenseNumber'])) {
                                                                                   echo htmlentities($employeeArray[0]['licenseNumber']);
                                                                               }
                                                                           }
                                                                           ?>"> <span class="input-group-addon"><img
                                                                            src="./images/icons/license-key.png"></span>
                                                                </div>
                                                                <span class="help-block" id="licenseNumberHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end license number -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!-- start date of birth -->
                                                        <?php
                                                        if (isset($employeeArray) && is_array($employeeArray)) {
                                                            if (isset($employeeArray[0]['employeeDateOfBirth'])) {
                                                                $valueArray = $employeeArray[0]['employeeDateOfBirth'];
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
                                                        <div class="form-group" id="employeeDateOfBirthForm">
                                                            <label for="employeeDateOfBirth" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['employeeDateOfBirthLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="employeeDateOfBirth"
                                                                           id="employeeDateOfBirth" value="<?php
                                                                           if (isset($value)) {
                                                                               echo $value;
                                                                           }
                                                                           ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                           id="employeeDateOfBirthImage"></span>
                                                                </div>
                                                                <span class="help-block" id="employeeDateOfBirthHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end date of birth -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3">
                                            <!-- picture -->
                                            <div class="form form-group" align="center">
                                                <label for="employeePicture" class="control-label col-xs-4 col-sm-4 col-md-4"><?php
                                                    echo ucfirst(
                                                            $leafTranslation['employeePictureLabel']
                                                    );
                                                    ?></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input type="hidden" class="form-control" name="employeePicture" id="employeePicture"
                                                           value="<?php echo $employeeArray[0]['employeePicture']; ?>">

                                                    <div id="employeePicturePreviewUpload" align="center">
                                                        <ul class="img-thumbnails">
                                                            <li>
                                                                <div class="img-thumbnail img-responsive" align="center">
                                                                    <?php
                                                                    if (empty($employeeArray[0]['employeePicture'])) {
                                                                        $employeePicture = 'Kathleen_Byrne.jpg';
                                                                    }
                                                                    if (isset($employeeArray[0]['employeePicture'])) {
                                                                        if (strlen($employeeArray[0]['employeePicture']) > 0) {
                                                                            ?>
                                                                            <img id="imagePreview"
                                                                                 src="./v3/humanResource/employment/images/<?php echo $employeeArray[0]['employeePicture']; ?>"
                                                                                 width="80"
                                                                                 height="80">
                                                                                 <?php
                                                                             }
                                                                         }
                                                                         ?></div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" align="center">
                                                <div class="col-xs-12 col-sm-12 col-md-12" align="center">
                                                    <div id="employeePictureDiv" class="pull-left" style="text-align:center" align="center">
                                                        <noscript>
                                                        <p>Please enable JavaScript to use file uploader.</p>
                                                        <!-- or put a simple form for upload here -->
                                                        </noscript>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end ` -->
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="officeLegend"><?php echo $t['officeTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('office', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('office', 1);">
                                </legend>
                                <div id="office">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start employee number -->
                                            <div class="form-group" id="employeeNumberForm">
                                                <label for="employeeNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeNumberLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeNumber" id="employeeNumber"
                                                               onKeyUp="removeMeError('employeeNumber');" value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeNumber'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeNumber']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/user-worker.png"></span></div>
                                                    <span class="help-block" id="employeeNumberHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end employee number -->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start job -->
                                            <div class="form-group" id="jobIdForm">
                                                <label for="jobId"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                               echo ucfirst(
                                                                       $leafTranslation['jobIdLabel']
                                                               );
                                                               ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="jobId" id="jobId" class="chzn-select form-control">
                                                        <option value=""></option>
                                                        <?php
                                                        $jobCategoryDescription = null;
                                                        if (is_array($jobArray)) {
                                                            $totalRecord = intval(count($jobArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 0;
                                                                $c = 1;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($d != 0) {
                                                                        if ($jobCategoryDescription != $jobArray[$i]['jobCategoryDescription']) {
                                                                            $c = 1;
                                                                            echo "</optgroup><optgroup label=\"" . $jobArray[$i]['jobCategoryDescription'] . "\">";
                                                                        }
                                                                    } else {
                                                                        echo "<optgroup label=\"" . $jobArray[$i]['jobCategoryDescription'] . "\">";
                                                                    }
                                                                    $jobCategoryDescription = $jobArray[$i]['jobCategoryDescription'];
                                                                    if (isset($employeeArray[0]['jobId'])) {
                                                                        if ($employeeArray[0]['jobId'] == $jobArray[$i]['jobId']) {
                                                                            $selected = "selected";
                                                                        } else {
                                                                            $selected = null;
                                                                        }
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $jobArray[$i]['jobId']; ?>" <?php echo $selected; ?>><?php echo $c; ?>
                                                                        . <?php echo $jobArray[$i]['jobTitle']; ?></option>
                                                                    <?php
                                                                    $d++;
                                                                    $c++;
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
                                                    </select> <span class="help-block" id="jobIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end job -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- employment status -->
                                            <div class="form-group" id="employmentStatusIdForm">
                                                <label for="employmentStatusId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employmentStatusIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="employmentStatusId" id="employmentStatusId" class="chzn-select form-control">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($employmentStatusArray)) {
                                                            $totalRecord = intval(count($employmentStatusArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 0;
                                                                $c = 1;
                                                                $isPaidSalary = null;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($d != 0) {
                                                                        if (intval($isPaidSalary) != intval(
                                                                                        $employmentStatusArray[$i]['isPaidSalary']
                                                                                )
                                                                        ) {
                                                                            $c = 1;
                                                                            echo "</optgroup><optgroup label=\"" . $t['noTextLabel'] . "\">";
                                                                        }
                                                                    } else {
                                                                        echo "<optgroup label=\"" . $t['yesTextLabel'] . "\">";
                                                                    }
                                                                    $isPaidSalary = $employmentStatusArray[$i]['isPaidSalary'];
                                                                    if (isset($employeeArray[0]['employmentStatusId'])) {
                                                                        if ($employeeArray[0]['employmentStatusId'] == $employmentStatusArray[$i]['employmentStatusId']) {
                                                                            $selected = "selected";
                                                                        } else {
                                                                            $selected = null;
                                                                        }
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $employmentStatusArray[$i]['employmentStatusId']; ?>" <?php echo $selected; ?>><?php echo $c; ?>
                                                                        . <?php echo $employmentStatusArray[$i]['employmentStatusDescription']; ?></option>
                                                                    <?php
                                                                    $d++;
                                                                    $c++;
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
                                                    </select> <span class="help-block" id="employmentStatusIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end employment status -->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- cheque printing -->
                                            <div class="form-group" id="employeeChequePrintingForm">
                                                <label for="employeeChequePrinting" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeChequePrintingLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeChequePrinting"
                                                               id="employeeChequePrinting" value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeChequePrinting'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeChequePrinting']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/cheque.png"></span></div>
                                                    <span class="help-block" id="employeeChequePrintingHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end cheque printing -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- Date Hired -->
                                            <?php
                                            if (isset($employeeArray) && is_array($employeeArray)) {
                                                if (isset($employeeArray[0]['employeeDateHired'])) {
                                                    $valueArray = $employeeArray[0]['employeeDateHired'];
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
                                            <div class="form-group" id="employeeDateHiredForm">
                                                <label for="employeeDateHired" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeDateHiredLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeDateHired" id="employeeDateHired"
                                                               value="<?php
                                                               if (isset($value)) {
                                                                   echo $value;
                                                               }
                                                               ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                               id="employeeDateHiredImage"></span>
                                                    </div>
                                                    <div class="control-label col-xs-4 col-sm-4 col-md-4">&nbsp;</div>
                                                    <span class="help-block" id="employeeDateHiredHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end date hired -->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- date retired -->
                                            <?php
                                            if (isset($employeeArray) && is_array($employeeArray)) {
                                                if (isset($employeeArray[0]['employeeDateRetired'])) {
                                                    $valueArray = $employeeArray[0]['employeeDateRetired'];
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
                                            <div class="form-group" id="employeeDateRetiredForm">
                                                <label for="employeeDateRetired" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeDateRetiredLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeDateRetired"
                                                               id="employeeDateRetired"
                                                               value="<?php
                                                               if (isset($value)) {
                                                                   echo $value;
                                                               }
                                                               ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                               id="employeeDateRetiredImage"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeDateRetiredHelpMe"></span>
                                                </div>
                                                <div class="control-label col-xs-4 col-sm-4 col-md-4">&nbsp;</div>
                                            </div>
                                            <!-- end date retired -->
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="addressLegend"><?php echo $t['addressTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('address', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('address', 1);">
                                </legend>
                                <div id="address">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!-- start address -->
                                                    <div class="form-group" id="employeeAddressForm">
                                                        <label for="employeeAddress" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['employeeAddressLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <textarea name="employeeAddress" id="employeeAddress" rows="5"
                                                                      class="form-control"><?php
                                                                          if (isset($employeeArray[0]['employeeAddress'])) {
                                                                              echo htmlentities($employeeArray[0]['employeeAddress']);
                                                                          }
                                                                          ?></textarea> <span class="help-block" id="employeeAddressHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end address -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--City -->
                                                    <div class="form-group" id="cityIdForm">
                                                        <label for="cityId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['cityIdLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="cityId" id="cityId" class="chzn-select form-control">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($cityArray)) {
                                                                    $totalRecord = intval(count($cityArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        $n = 0;
                                                                        $currentStateDescription = null;
                                                                        $group = 0;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            $d++;
                                                                            $n++;
                                                                            if ($i != 0) {
                                                                                if ($currentStateDescription != $cityArray[$i]['stateDescription']) {
                                                                                    $group = 1;
                                                                                    echo "</optgroup><optgroup label=\"" . $cityArray[$i]['stateDescription'] . "\">";
                                                                                }
                                                                            } else {
                                                                                echo "<optgroup label=\"" . $cityArray[$i]['stateDescription'] . "\">";
                                                                            }
                                                                            $currentStateDescription = $cityArray[$i]['stateDescription'];
                                                                            if (isset($employeeArray[0]['cityId'])) {
                                                                                if ($employeeArray[0]['cityId'] == $cityArray[$i]['cityId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $cityArray[$i]['cityId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                . <?php echo $cityArray[$i]['cityDescription']; ?></option>
                                                                            <?php
                                                                            $d++;
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                        <?php
                                                                    }
                                                                    echo "</optgroup>";
                                                                } else {
                                                                    ?>
                                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                <?php } ?>
                                                            </select> <span class="help-block" id="cityIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end city-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--postcode -->
                                                    <div class="form-group" id="employeePostCodeForm">
                                                        <label for="employeePostCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['employeePostCodeLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="employeePostCode"
                                                                       id="employeePostCode"
                                                                       value="<?php
                                                                       if (isset($employeeArray) && is_array($employeeArray)) {
                                                                           if (isset($employeeArray[0]['employeePostCode'])) {
                                                                               echo htmlentities($employeeArray[0]['employeePostCode']);
                                                                           }
                                                                       }
                                                                       ?>" maxlength="16">
                                                                <span class="input-group-addon"><img src="./images/icons/postage-stamp.png"></span>
                                                            </div>
                                                            <span class="help-block" id="employeePostCodeHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end postcode-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--State -->
                                                    <div class="form-group" id="stateIdForm">
                                                        <label for="stateId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['stateIdLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="stateId" id="stateId" class="chzn-select form-control"
                                                                    onChange="getCity('<?php echo $leafId; ?>', '<?php
                                                                    echo $employee->getControllerPath(
                                                                    );
                                                                    ?>', '<?php echo $securityToken; ?>');">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($stateArray)) {
                                                                    $totalRecord = intval(count($stateArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($employeeArray[0]['stateId'])) {
                                                                                if ($employeeArray[0]['stateId'] == $stateArray[$i]['stateId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $stateArray[$i]['stateId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                . <?php echo $stateArray[$i]['stateDescription']; ?></option>
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
                                                            </select> <span class="help-block" id="stateIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end state-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--Country -->
                                                    <div class="form-group" id="countryIdForm">
                                                        <label for="countryId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['countryIdLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="countryId" id="countryId" class="chzn-select form-control">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($countryArray)) {
                                                                    $totalRecord = intval(count($countryArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($employeeArray[0]['countryId'])) {
                                                                                if ($employeeArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                . <?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                                            </select> <span class="help-block" id="countryIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end country-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="contactLegend"><?php echo $t['contactTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded"
                                         onClick="showMeDiv('contact', 0);">&nbsp;<img
                                         src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('contact', 1);">
                                </legend>
                                <div id="contact">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeBusinessPhoneForm">
                                                <label for="employeeBusinessPhone"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                               echo ucfirst(
                                                                       $leafTranslation['employeeBusinessPhoneLabel']
                                                               );
                                                               ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeBusinessPhone"
                                                               id="employeeBusinessPhone"
                                                               value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeBusinessPhone'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeBusinessPhone']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/telephone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeBusinessPhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeHomePhoneForm">
                                                <label for="employeeHomePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeHomePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeHomePhone"
                                                               id="employeeHomePhone" value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeHomePhone'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeHomePhone']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeHomePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeMobilePhoneForm">
                                                <label for="employeeMobilePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeMobilePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeMobilePhone"
                                                               id="employeeMobilePhone"
                                                               value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeMobilePhone'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeMobilePhone']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/mobile-phone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeMobilePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeFaxNumberForm">
                                                <label for="employeeFaxNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeFaxNumberLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeFaxNumber"
                                                               id="employeeFaxNumber" value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeFaxNumber'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeFaxNumber']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone-fax.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeFaxNumberHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="webLegend"><?php echo $t['webTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('web', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('web', 1);">
                                </legend>
                                <div id="web">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeEmailForm">
                                                <label for="employeeEmail" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeEmailLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeEmail" id="employeeEmail"
                                                               value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeEmail'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeEmail']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/email.png"></span></div>
                                                    <span class="help-block" id="employeeEmailHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeFacebookForm">
                                                <label for="employeeFacebook" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeFacebookLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeFacebook"
                                                               id="employeeFacebook" value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeFacebook'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeFacebook']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/facebook.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeFacebookHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeTwitterForm">
                                                <label for="employeeTwitter" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeTwitterLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeTwitter"
                                                               id="employeeTwitter"
                                                               value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeTwitter'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeTwitter']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/twitter.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeTwitterHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="employeeLinkedInForm">
                                                <label for="employeeLinkedIn" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['employeeLinkedInLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="employeeLinkedIn"
                                                               id="employeeLinkedIn" value="<?php
                                                               if (isset($employeeArray) && is_array($employeeArray)) {
                                                                   if (isset($employeeArray[0]['employeeLinkedIn'])) {
                                                                       echo htmlentities($employeeArray[0]['employeeLinkedIn']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/linkedin.png"></span>
                                                    </div>
                                                    <span class="help-block" id="employeeLinkedInHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="notesLegend"><?php echo $t['noteTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded"
                                         onClick="showMeDiv('notes', 0);">&nbsp;<img
                                         src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('notes', 1);">
                                </legend>
                                <div class="row hidden" id="notes">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group" id="employeeNotesForm">
                                            <textarea class="form-control" name="employeeNotes" id="employeeNotes" rows="5"><?php
                                                if (isset($employeeArray) && is_array($employeeArray)) {
                                                    if (isset($employeeArray[0]['employeeNotes'])) {
                                                        echo htmlentities($employeeArray[0]['employeeNotes']);
                                                    }
                                                }
                                                ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
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
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                        ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                       ?></a></li>-->
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
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                        ?></a></li> -->
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
                                   onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $employee->getControllerPath();
                                   ?>', '<?php
                                   echo $employee->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $employee->getViewPath();
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
        </div>
    </form>
    <script type="text/javascript">
        $(document).keypress(function(e) {
    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                // shift+n new record event
                if (e.which === 78 && e.which === 18  && e.shiftKey) {
                    


                    newRecord(<?php echo $leafId; ?>, '<?php echo $employee->getControllerPath(); ?>', '<?php echo $employee->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                // shift+s save event
                if (e.which === 83 && e.which === 18  && e.shiftKey) {
                    


                    updateRecord(<?php echo $leafId; ?>, '<?php echo $employee->getControllerPath(); ?>', '<?php echo $employee->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                // shift+d delete event
                if (e.which === 88 && e.which === 18 && e.shiftKey) {
                    


                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $employee->getControllerPath(); ?>', '<?php echo $employee->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;

                }
    <?php } ?>
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $employee->getControllerPath(); ?>', '<?php echo $employee->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $employee->getControllerPath(); ?>', '<?php echo $employee->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
            }
            

        });
        $(document).ready(function() {
            showMeDiv('address', 0);
            showMeDiv('office', 0);
            showMeDiv('contact', 0);
            showMeDiv('web', 0);
            showMeDiv('notes', 0);
            $("#addressLegend").on('click', function() {
                toggle("address");
            });
            $("#officeLegend").on('click', function() {
                toggle("office");
            });
            $("#contactLegend").on('click', function() {
                toggle("contact");
            });
            $("#webLegend").on('click', function() {
                toggle("web");
            });
            $("#notesLegend").on('click', function() {
                toggle("notes");
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('employeeId');
            validateMeNumeric('cityId');
            validateMeNumeric('stateId');
            validateMeNumeric('countryId');
            validateMeNumeric('jobId');
            validateMeNumeric('genderId');
            validateMeNumeric('marriageId');
            validateMeNumeric('raceId');
            validateMeNumeric('religionId');
            validateMeNumeric('employmentStatusId');
            validateMeAlphaNumeric('nationalNumber');
            validateMeAlphaNumeric('licenseNumber');
            validateMeAlphaNumeric('employeeNumber');
            validateMeAlphaNumeric('employeeFirstName');
            validateMeAlphaNumeric('employeePicture');
            validateMeAlphaNumeric('employeeLastName');
            var a = $('#employeeDateOfBirth').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $("#employeeDateOfBirthImage").on('click', function() {
                a.datepicker('show');
            });
            var b = $('#employeeDateHired').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $("#employeeDateHiredImage").on('click', function() {
                b.datepicker('show');
            });
            var c = $('#employeeDateRetired').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $("#employeeDateRetiredImage").on('click', function() {
                c.datepicker('show');
            });
            validateMeAlphaNumeric('employeeBusinessPhone');
            validateMeAlphaNumeric('employeeHomePhone');
            validateMeAlphaNumeric('employeeMobilePhone');
            validateMeAlphaNumeric('employeeFaxNumber');
            validateMeAlphaNumeric('employeePostCode');
            validateMeAlphaNumeric('employeeEmail');
            validateMeAlphaNumeric('employeeFacebook');
            validateMeAlphaNumeric('employeeTwitter');
            validateMeAlphaNumeric('employeeLinkedIn');
            validateMeAlphaNumeric('employeeNotes');
            validateMeAlphaNumeric('employeeChequePrinting');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton')
                        .removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1')
                            .removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2')
                            .removeClass().addClass('btn dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                    $('#newRecordButton1')
                            .removeClass().addClass('btn btn-success disabled');
                    $('#newRecordButton2')
                            .removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>
                $('#updateRecordButton1')
                        .removeClass().addClass(' btn btn-info disabled');
                $('#updateRecordButton2')
                        .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton4').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#deleteRecordButton')
                        .removeClass().addClass('btn btn-danger disabled')
                        .attr('onClick', '');
                $('#firstRecordButton')
                        .removeClass().addClass('btn btn-default');
                $('#endRecordButton')
                        .removeClass().addClass('btn btn-default');
        <?php
    } else {
        if ($_POST['employeeId']) {
            ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                    $('#newRecordButton3').attr('onClick', '');
                    $('#newRecordButton4').attr('onClick', '');
                    $('#newRecordButton5').attr('onClick', '');
                    $('#newRecordButton6').attr('onClick', '');
                    $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1')
                                .removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#updateRecordButton1')
                                .removeClass().addClass(' btn btn-info disabled');
                        $('#updateRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-info disabled');


                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                        $('#deleteRecordButton')
                                .removeClass().addClass('btn btn-danger')
                                .attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $employee->getControllerPath(); ?>','<?php echo $employee->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#deleteRecordButton')
                                .removeClass().addClass('btn btn-danger disabled')
                                .attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>

            $('#employeePictureDiv').fineUploader({
                request: {
                    endpoint: './v3/humanResource/recruitment/controller/employeeController.php'
                },
                validation: {
                    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                    sizeLimit: 20971520,
                    stopOnFirstInvalidFile: true
                },
                method: 'POST',
                params: {
                    securityToken: '<?php echo $securityToken; ?>',
                    method: 'upload',
                    output: 'json'
                },
                text: {
                    uploadButton: 'Upload a file',
                    cancelButton: 'Cancel',
                    retryButton: 'Retry',
                    failUpload: 'Upload failed',
                    dragZone: 'Drop files here to upload',
                    formatProgress: "{percent}% of {total_size}",
                    waitingForResponse: "Processing..."
                },
                messages: {
                    typeError: "{file} has an invalid extension. Valid extension(s): {extensions}.",
                    sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
                    minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
                    emptyError: "{file} is empty, please select files again without it.",
                    noFilesError: "No files to upload.",
                    onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
                },
                // validation
                // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                // each file size limit in bytes
                // this option isn't supported in all browsers
                sizeLimit: (2 * 1024 * 1024), // max size
                minSizeLimit: 0, // min size
                classes: {
                    success: 'alert alert-success',
                    fail: 'alert alert-error'
                },
                debug: true
            }).on('error', function(event, id, filename, reason) {
                //do something
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'>" + reason + "  : " + filename + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }).
                    on('onCancel', function(id, filename) {
                        var message = "<?php echo $t['cancelButtonLabel']; ?>";
                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'>" + message + "  : " + filename + "</span>");
                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');

                    }).on('complete', function(event, id, filename, responseJSON) {
                if (responseJSON.success === true) {
                    // view image upload
                    $("#infoPanelForm")
                            .html('').empty()
                            .html("<div class='alert alert-success'><img src='./images/icons/smiley-roll.png'> <b>Upload complete </b> : " + filename + "</div>");

                    $("#employeePicturePreviewUpload")
                            .html("").empty()
                            .html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./v3/humanResource/employment/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                    $("#employeePicture")
                            .val('').val(filename);
                } else {
                    $("#infoPanelForm")
                            .html('').empty()
                            .html("<div class='alert alert-error'><img src='./images/icons/smiley-roll-sweat.png'> <b>Filename</b>  : " + filename + " \n<br><br><b>Error Message</b> :" + responseJSON.error + "</div>");

                }
            }).on('submit', function(event, id, filename) {
                $(this).fineUploader('setParams', {'securityToken': '<?php echo $securityToken; ?>',
                    'method': 'upload',
                    'output': 'json'});
                var message = "<?php echo $t['loadingTextLabel']; ?>";
                $("#infoPanelForm")
                        .html('').empty()
                        .html("<div class='alert alert-info'><img src='./images/icons/smiley-roll.png'> " + message + " Id: " + id + "  : " + filename + "</div>");

            });
            // tooogle play play

            // end upload avatar
        });
        function showMeAll(toggle) {
            showMeDiv('address', toggle);
            showMeDiv('office', toggle);
            showMeDiv('contact', toggle);
            showMeDiv('web', toggle);
            showMeDiv('notes', toggle);
        }
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/humanResource/employment/javascript/employee.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>