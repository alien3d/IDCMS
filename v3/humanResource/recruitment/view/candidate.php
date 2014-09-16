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
require_once($newFakeDocumentRoot . "v3/humanResource/recruitment/controller/candidateController.php");
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
$translator->setCurrentTable('candidate');
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
$candidateArray = array();
$cityArray = array();
$stateArray = array();
$countryArray = array();
$genderArray = array();
$marriageArray = array();
$raceArray = array();
$religionArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $candidate = new \Core\HumanResource\Recruitment\Candidate\Controller\CandidateClass();
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
            $candidate->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $candidate->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $candidate->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $candidate->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $candidate->setStartDay($start[2]);
            $candidate->setStartMonth($start[1]);
            $candidate->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $candidate->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $candidate->setEndDay($start[2]);
            $candidate->setEndMonth($start[1]);
            $candidate->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $candidate->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $candidate->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $candidate->setServiceOutput('html');
        $candidate->setLeafId($leafId);
        $candidate->execute();
        $cityArray = $candidate->getCity();
        $stateArray = $candidate->getState();
        $countryArray = $candidate->getCountry();
        $genderArray = $candidate->getGender();
        $marriageArray = $candidate->getMarriage();
        $raceArray = $candidate->getRace();
        $religionArray = $candidate->getReligion();
        if ($_POST['method'] == 'read') {
            $candidate->setStart($offset);
            $candidate->setLimit($limit); // normal system don't like paging..
            $candidate->setPageOutput('html');
            $candidateArray = $candidate->read();
            if (isset($candidateArray [0]['firstRecord'])) {
                $firstRecord = $candidateArray [0]['firstRecord'];
            }
            if (isset($candidateArray [0]['nextRecord'])) {
                $nextRecord = $candidateArray [0]['nextRecord'];
            }
            if (isset($candidateArray [0]['previousRecord'])) {
                $previousRecord = $candidateArray [0]['previousRecord'];
            }
            if (isset($candidateArray [0]['lastRecord'])) {
                $lastRecord = $candidateArray [0]['lastRecord'];
                $endRecord = $candidateArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($candidate->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($candidateArray [0]['total'])) {
                $total = $candidateArray [0]['total'];
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
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $candidate->getViewPath();
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
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $candidate->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $candidate->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $candidate->getControllerPath();
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
                                            onClick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $candidate->getViewPath();
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
                                       echo $candidate->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $candidate->getViewPath();
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
                                               echo $candidate->getViewPath();
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
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
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
                                               echo $candidate->getViewPath();
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
                                                              echo $candidate->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png"
                                                 alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Month <?php echo $nextMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Year <?php echo $nextYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $candidate->getViewPath();
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
                                           echo $candidate->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                           onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $candidate->getViewPath();
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
                                        <input type="hidden" name="candidateIdPreview" id="candidateIdPreview">

                                        <div class="form-group" id="cityIdDiv">
                                            <label for="cityIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['cityIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="cityIdPreview"
                                                       id="cityIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="stateIdDiv">
                                            <label for="stateIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['stateIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="stateIdPreview"
                                                       id="stateIdPreview">
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
                                                <input class="form-control" type="text" name="raceIdPreview"
                                                       id="raceIdPreview">
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
                                        <div class="form-group" id="candidateFirstNameDiv">
                                            <label for="candidateFirstNamePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateFirstNameLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateFirstNamePreview"
                                                       id="candidateFirstNamePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateLastNameDiv">
                                            <label for="candidateLastNamePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateLastNameLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateLastNamePreview"
                                                       id="candidateLastNamePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateEmailDiv">
                                            <label for="candidateEmailPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateEmailLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateEmailPreview"
                                                       id="candidateEmailPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateBusinessPhoneDiv">
                                            <label for="candidateBusinessPhonePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateBusinessPhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="candidateBusinessPhonePreview" id="candidateBusinessPhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateHomePhoneDiv">
                                            <label for="candidateHomePhonePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateHomePhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateHomePhonePreview"
                                                       id="candidateHomePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateMobilePhoneDiv">
                                            <label for="candidateMobilePhonePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateMobilePhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateMobilePhonePreview"
                                                       id="candidateMobilePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateFaxNumberDiv">
                                            <label for="candidateFaxNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateFaxNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateFaxNumberPreview"
                                                       id="candidateFaxNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateAddressDiv">
                                            <label for="candidateAddressPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateAddressLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateAddressPreview"
                                                       id="candidateAddressPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidatePostCodeDiv">
                                            <label for="candidatePostCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidatePostCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidatePostCodePreview"
                                                       id="candidatePostCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateWebPageDiv">
                                            <label for="candidateWebPagePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateWebPageLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateWebPagePreview"
                                                       id="candidateWebPagePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateFacebookDiv">
                                            <label for="candidateFacebookPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateFacebookLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateFacebookPreview"
                                                       id="candidateFacebookPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateTwitterDiv">
                                            <label for="candidateTwitterPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateTwitterLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateTwitterPreview"
                                                       id="candidateTwitterPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateLinkedInDiv">
                                            <label for="candidateLinkedInPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateLinkedInLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateLinkedInPreview"
                                                       id="candidateLinkedInPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="candidateNotesDiv">
                                            <label for="candidateNotesPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['candidateNotesLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="candidateNotesPreview"
                                                       id="candidateNotesPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onClick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $candidate->getControllerPath();
                                            ?>', '<?php
                                            echo $candidate->getViewPath();
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
                                    <th width="100px"><?php echo ucwords($leafTranslation['candidateFirstNameLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['candidateLastNameLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['candidateEmailLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['candidateBusinessPhoneLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['candidateHomePhoneLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['candidateMobilePhoneLabel']); ?></th>
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
                                            if (is_array($candidateArray)) {
                                                $totalRecord = intval(count($candidateArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($candidateArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($candidateArray[$i]['isDraft'] == 1) {
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
                                                                            echo $candidate->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $candidate->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $candidateArray [$i]['candidateId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onClick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['cityDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['stateDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['countryDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['genderDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['marriageDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['raceDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['religionDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateFirstName']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateLastName']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateEmail']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateBusinessPhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateHomePhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateMobilePhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateFaxNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateAddress']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidatePostCode']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateWebPage']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateFacebook']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateTwitter']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $candidateArray [$i]['candidateLinkedIn']
                                                                            );
                                                                            ?>', '<?php echo rawurlencode($candidateArray [$i]['candidateNotes']); ?>');">
                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($candidateArray[$i]['candidateFirstName'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($candidateArray[$i]['candidateFirstName']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['candidateFirstName']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateFirstName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($candidateArray[$i]['candidateFirstName']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['candidateFirstName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['candidateFirstName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateFirstName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['candidateFirstName'];
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
                                                                    if (isset($candidateArray[$i]['candidateLastName'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($candidateArray[$i]['candidateLastName']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['candidateLastName']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateLastName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($candidateArray[$i]['candidateLastName']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['candidateLastName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['candidateLastName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateLastName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['candidateLastName'];
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
                                                                    if (isset($candidateArray[$i]['candidateEmail'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($candidateArray[$i]['candidateEmail']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['candidateEmail']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateEmail'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($candidateArray[$i]['candidateEmail']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['candidateEmail']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['candidateEmail'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateEmail'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['candidateEmail'];
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
                                                                    if (isset($candidateArray[$i]['candidateBusinessPhone'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($candidateArray[$i]['candidateBusinessPhone']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['candidateBusinessPhone']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateBusinessPhone'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($candidateArray[$i]['candidateBusinessPhone']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['candidateBusinessPhone']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['candidateBusinessPhone'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateBusinessPhone'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['candidateBusinessPhone'];
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
                                                                    if (isset($candidateArray[$i]['candidateHomePhone'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($candidateArray[$i]['candidateHomePhone']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['candidateHomePhone']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateHomePhone'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($candidateArray[$i]['candidateHomePhone']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['candidateHomePhone']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['candidateHomePhone'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateHomePhone'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['candidateHomePhone'];
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
                                                                    if (isset($candidateArray[$i]['candidateMobilePhone'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($candidateArray[$i]['candidateMobilePhone']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['candidateMobilePhone']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateMobilePhone'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($candidateArray[$i]['candidateMobilePhone']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['candidateMobilePhone']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['candidateMobilePhone'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['candidateMobilePhone'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['candidateMobilePhone'];
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
                                                                    if (isset($candidateArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($candidateArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $candidateArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $candidateArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $candidateArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $candidateArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $candidateArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $candidateArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $candidateArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($candidateArray[$i]['executeTime'])) {
                                                                $valueArray = $candidateArray[$i]['executeTime'];
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
                                                            if ($candidateArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <label>
                                                                    <input style="display:none;" type="checkbox" name="candidateId[]"
                                                                           value="<?php echo $candidateArray[$i]['candidateId']; ?>">
                                                                </label> <label>
                                                                    <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                   value="<?php echo $candidateArray[$i]['isDelete']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="11" vAlign="top" align="center"><?php
                                                            $candidate->exceptionMessage(
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
                                                        $candidate->exceptionMessage(
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
                                                    $candidate->exceptionMessage(
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
                                        echo $candidate->getControllerPath();
                                        ?>', '<?php echo $candidate->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
                                       echo $candidate->getControllerPath();
                                       ?>', '<?php
                                       echo $candidate->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $candidate->getControllerPath();
                                       ?>', '<?php
                                       echo $candidate->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $candidate->getControllerPath();
                                       ?>', '<?php
                                       echo $candidate->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $candidate->getControllerPath();
                                       ?>', '<?php
                                       echo $candidate->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="candidateId" id="candidateId" value="<?php
                            if (isset($_POST['candidateId'])) {
                                echo $_POST['candidateId'];
                            }
                            ?>">
                            <fieldset>
                                <legend><?php echo $t['informationTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeAll(0)">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeAll(1)">
                                </legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="row col-md-9">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="control-label col-md-6">
                                                        <!--name-->
                                                        <div class="form-group" id="candidateFirstNameForm">
                                                            <label for="candidateFirstName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['candidateFirstNameLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="candidateFirstName" id="candidateFirstName"
                                                                       onKeyUp="removeMeError('candidateFirstName');" value="<?php
                                                                       if (isset($candidateArray) && is_array($candidateArray)) {
                                                                           echo htmlentities($candidateArray[0]['candidateFirstName']);
                                                                       }
                                                                       ?>"> <span class="help-block" id="candidateFirstNameHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!--end name-->
                                                    </div>
                                                    <div class="control-label col-md-6">
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
                                                                                if ($candidateArray[0]['raceId'] == $raceArray[$i]['raceId']) {
                                                                                    $selected = "selected";
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
                                                    <div class="control-label col-md-6">
                                                        <!--last name-->
                                                        <div class="form-group" id="candidateLastNameForm">
                                                            <label for="candidateLastName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['candidateLastNameLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="candidateLastName" id="candidateLastName"
                                                                       onKeyUp="removeMeError('candidateLastName');" value="<?php
                                                                       if (isset($candidateArray) && is_array($candidateArray)) {
                                                                           echo htmlentities($candidateArray[0]['candidateLastName']);
                                                                       }
                                                                       ?>"> <span class="help-block" id="candidateLastNameHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!--end last name-->
                                                    </div>
                                                    <div class="control-label col-md-6">
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
                                                                                if ($candidateArray[0]['religionId'] == $religionArray[$i]['religionId']) {
                                                                                    $selected = "selected";
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
                                                    <div class="control-label col-md-6">
                                                        <!--gender-->
                                                        <div class="form-group" id="genderIdForm">
                                                            <label for="genderId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['genderIdLabel']
                                                                    );
                                                                    ?></strong></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <select name="genderId" id="genderId" class="chzn-select form-control">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($genderArray)) {
                                                                        $totalRecord = intval(count($genderArray));
                                                                        if ($totalRecord > 0) {
                                                                            $d = 1;
                                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                                if ($candidateArray[0]['genderId'] == $genderArray[$i]['genderId']) {
                                                                                    $selected = "selected";
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
                                                    <div class="control-label col-md-6">
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
                                                                                if ($candidateArray[0]['marriageId'] == $marriageArray[$i]['marriageId']) {
                                                                                    $selected = "selected";
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
                                                                </select> <span class="help-block" id="marriageIdHelpMe"></span>
                                                            </div>
                                                        </div>
                                                        <!-- end marriage-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <!-- picture -->
                                        <div class="form-group" align="center">
                                            <label for="candidatePicture" class="control-label col-xs-4 col-sm-4 col-md-4"><?php
                                                echo ucfirst(
                                                        $leafTranslation['candidatePictureLabel']
                                                );
                                                ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="hidden" name="candidatePicture" id="candidatePicture" value="<?php echo $candidateArray[0]['candidatePicture']; ?>">

                                                <div id="candidatePicturePreviewUpload" align="center">
                                                    <ul class="img-thumbnails">
                                                        <li>
                                                            <div class="img-thumbnail" align="center">
                                                                <?php
                                                                if (empty($candidateArray[0]['candidatePicture'])) {
                                                                    $candidateArray[0]['candidatePicture'] = 'Kathleen_Byrne.jpg';
                                                                }
                                                                if ($candidateArray[0]['candidatePicture']) {
                                                                    if (strlen($candidateArray[0]['candidatePicture']) > 0) {
                                                                        ?>
                                                                        <img id="imagePreview"
                                                                             src="./v3/humanResource/recruitment/images/<?php echo $candidateArray[0]['candidatePicture']; ?>"
                                                                             width="80" height="80">
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
                                                <div id="candidatePictureDiv" class="pull-left" style="text-align:center" align="center">
                                                    <noscript>
                                                    <p>Please enable JavaScript to use file uploader.</p>
                                                    <!-- or put a simple form for upload here -->
                                                    </noscript>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end picture -->
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="addressLegend"><?php echo $t['addressTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('address', 0)">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('address', 1)">
                                </legend>
                                <div id="address">
                                    <div class="row">
                                        <div class="control-label col-md-6">
                                            <!-- start office address -->
                                            <div class="form-group" id="candidateAddressForm">
                                                <label for="candidateAddress" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateAddressLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <textarea class="form-control" name="candidateAddress" id="candidateAddress"><?php
                                                        if (isset($candidateArray[0]['candidateAddress'])) {
                                                            echo htmlentities($candidateArray[0]['candidateAddress']);
                                                        }
                                                        ?></textarea> <span class="help-block" id="candidateAddressHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end office address -->
                                        </div>
                                    </div>
                                    <div class="control-label col-md-6">
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
                                                                        if ($candidateArray[0]['cityId'] == $cityArray[$i]['cityId']) {
                                                                            $selected = "selected";
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
                                                <div class="form-group" id="candidatePostCodeForm">
                                                    <label for="candidatePostCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['candidatePostCodeLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="candidatePostCode"
                                                                   id="candidatePostCode" value="<?php
                                                                   if (isset($candidateArray) && is_array($candidateArray)) {
                                                                       echo htmlentities($candidateArray[0]['candidatePostCode']);
                                                                   }
                                                                   ?>" maxlength="16">
                                                            <span class="input-group-addon"><img src="./images/icons/postage-stamp.png"></span>
                                                        </div>
                                                        <span class="help-block" id="candidatePostCodeHelpMe"></span>
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
                                                        <select name="stateId" id="stateId" class="chzn-select form-control">
                                                            <option value=""></option>
                                                            <?php
                                                            if (is_array($stateArray)) {
                                                                $totalRecord = intval(count($stateArray));
                                                                if ($totalRecord > 0) {
                                                                    $d = 1;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        if ($candidateArray[0]['stateId'] == $stateArray[$i]['stateId']) {
                                                                            $selected = "selected";
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
                                                                        if ($candidateArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                            $selected = "selected";
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
                            </fieldset>
                            <fieldset>
                                <legend id="contactLegend"><?php echo $t['contactTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('contact', 0)">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('contact', 1)">
                                </legend>
                                <div id="contact">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateBusinessPhoneForm">
                                                <label for="candidateBusinessPhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateBusinessPhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateBusinessPhone"
                                                               id="candidateBusinessPhone" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateBusinessPhone']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone.png"></span></div>
                                                    <span class="help-block" id="candidateBusinessPhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateHomePhoneForm">
                                                <label for="candidateHomePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateHomePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateHomePhone"
                                                               id="candidateHomePhone" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateHomePhone']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone.png"></span></div>
                                                    <span class="help-block" id="candidateHomePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateMobilePhoneForm">
                                                <label for="candidateMobilePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateMobilePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateMobilePhone"
                                                               id="candidateMobilePhone" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateMobilePhone']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/mobile-phone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateMobilePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateFaxNumberForm">
                                                <label for="candidateFaxNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateFaxNumberLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateFaxNumber"
                                                               id="candidateFaxNumber" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateFaxNumber']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone-fax.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateFaxNumberHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="webLegend"><?php echo $t['webTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('web', 0)">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('web', 1)">
                                </legend>
                                <div id="web">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateEmailForm">
                                                <label for="candidateEmail" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateEmailLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateEmail" id="candidateEmail"
                                                               value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateEmail']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/email.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateEmailHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateFacebookForm">
                                                <label for="candidateFacebook" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateFacebookLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateFacebook"
                                                               id="candidateFacebook" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateFacebook']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img class="" src="./images/icons/facebook.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateFacebookHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateTwitterForm">
                                                <label for="candidateTwitter" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateTwitterLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateTwitter"
                                                               id="candidateTwitter" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateTwitter']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img class="" src="./images/icons/twitter.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateTwitterHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateLinkedInForm">
                                                <label for="candidateLinkedIn" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateLinkedInLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateLinkedIn"
                                                               id="candidateLinkedIn" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateLinkedIn']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img class="" src="./images/icons/linkedin.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateLinkedInHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateWebPageForm">
                                                <label for="candidateWebPage" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['candidateWebPageLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="candidateWebPage"
                                                               id="candidateWebPage" value="<?php
                                                               if (isset($candidateArray) && is_array($candidateArray)) {
                                                                   echo htmlentities($candidateArray[0]['candidateWebPage']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img class="" src="./images/icons/website.png"></span>
                                                    </div>
                                                    <span class="help-block" id="candidateWebPageHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="notesLegend"><?php echo $t['noteTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('notes', 0)">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('notes', 1)">
                                </legend>
                                <div class="row" id="notes">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="candidateNotesForm">
                                            <label for="candidateNotes" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                    echo ucfirst(
                                                            $leafTranslation['candidateNotesLabel']
                                                    );
                                                    ?></strong></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <textarea class="form-control" name="candidateNotes" id="candidateNotes"><?php
                                                    if (isset($candidateArray) && is_array($candidateArray)) {
                                                        echo htmlentities($candidateArray[0]['candidateNotes']);
                                                    }
                                                    ?></textarea> <span class="help-block" id="candidateNotesHelpMe"></span>
                                            </div>
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
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                       ?></a></li>-->
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
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                       ?></a></li> -->
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
                                   echo $candidate->getControllerPath();
                                   ?>', '<?php
                                   echo $candidate->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $candidate->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php
                    if (isset($firstRecord)) {
                        echo $firstRecord;
                    }
                    ?>"> <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                           if (isset($nextRecord)) {
                               echo $nextRecord;
                           }
                           ?>"> <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php
                           if (isset($previousRecord)) {
                               echo $previousRecord;
                           }
                           ?>"> <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php
                           if (isset($lastRecord)) {
                               echo $lastRecord;
                           }
                           ?>"> <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php
                           if (isset($endRecord)) {
                               echo $endRecord;
                           }
                           ?>">
                </div>
                <script type="text/javascript">
                    $(document).keypress(function(e) {

                        // shift+n new record event
                        if (e.which === 78 && e.which === 18  && e.shiftKey) {
                            

    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                newRecord(<?php echo $leafId; ?>, '<?php echo $candidate->getControllerPath(); ?>', '<?php echo $candidate->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                            return false;
                        }
                        // shift+s save event
                        if (e.which === 83 && e.which === 18  && e.shiftKey) {
                            

    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                updateRecord(<?php echo $leafId; ?>, '<?php echo $candidate->getControllerPath(); ?>', '<?php echo $candidate->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                            return false;
                        }
                        // shift+d delete event
                        if (e.which === 88 && e.which === 18 && e.shiftKey) {
                            

    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                deleteRecord(<?php echo $leafId; ?>, '<?php echo $candidate->getControllerPath(); ?>', '<?php echo $candidate->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

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
                                previousRecord(<?php echo $leafId; ?>, '<?php echo $candidate->getControllerPath(); ?>', '<?php echo $candidate->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                                
                                return false;
                                break;
                            case 39:
                                nextRecord(<?php echo $leafId; ?>, '<?php echo $candidate->getControllerPath(); ?>', '<?php echo $candidate->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                                
                                return false;
                                break;
                        }
                        

                    });
                    $(document).ready(function() {
                        showMeDiv('address', 0);
                        showMeDiv('contact', 0);
                        showMeDiv('web', 0);
                        showMeDiv('notes', 0);
                        $("#addressLegend").on('click', function() {
                            toggle("address");
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
                        validateMeNumeric('candidateId');
                        validateMeNumeric('cityId');
                        validateMeNumeric('stateId');
                        validateMeNumeric('countryId');
                        validateMeNumeric('genderId');
                        validateMeNumeric('marriageId');
                        validateMeNumeric('raceId');
                        validateMeNumeric('religionId');
                        validateMeAlphaNumeric('candidateFirstName');
                        validateMeAlphaNumeric('candidateLastName');
                        validateMeAlphaNumeric('candidateEmail');
                        validateMeAlphaNumeric('candidateBusinessPhone');
                        validateMeAlphaNumeric('candidateHomePhone');
                        validateMeAlphaNumeric('candidateMobilePhone');
                        validateMeAlphaNumeric('candidateFaxNumber');
                        validateMeAlphaNumeric('candidatePostCode');
                        validateMeAlphaNumeric('candidateWebPage');
                        validateMeAlphaNumeric('candidateFacebook');
                        validateMeAlphaNumeric('candidateTwitter');
                        validateMeAlphaNumeric('candidateLinkedIn');
                        validateMeAlphaNumeric('candidateNotes');
    <?php if ($_POST['method'] == "new") { ?>
                            $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                                $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                                $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                                $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                                $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['candidateId']) {
            ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                $('#newRecordButton3').attr('onClick', '');
                                $('#newRecordButton4').attr('onClick', '');
                                $('#newRecordButton5').attr('onClick', '');
                                $('#newRecordButton6').attr('onClick', '');
                                $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                    $('#updateRecordButton3').attr('onClick', '');
                                    $('#updateRecordButton4').attr('onClick', '');
                                    $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $candidate->getControllerPath(); ?>','<?php echo $candidate->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                        $('#candidatePictureDiv').fineUploader({
                            request: {
                                endpoint: './v3/humanResource/recruitment/controller/candidateController.php'
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
                            if (responseJSON.success == true) {
                                // view image upload
                                $("#infoPanelForm").html('').empty().html("<div class='alert alert-success'><img src='./images/icons/smiley-roll.png'> <b>Upload complete </b> : " + filename + "</div>");

                                $("#candidatePicturePreviewUpload").html("").empty().html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./v3/humanResource/recruitment/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                                $("#candidatePicture").val('').val(filename);
                            } else {
                                $("#infoPanelForm").html('').empty().html("<div class='alert alert-error'><img src='./images/icons/smiley-roll-sweat.png'> <b>Filename</b>  : " + filename + " \n<br><br><b>Error Message</b> :" + responseJSON.error + "</div>")
                            }
                        }).on('submit', function(event, id, filename) {
                            $(this).fineUploader('setParams', {'securityToken': '<?php echo $securityToken; ?>',
                                'method': 'upload',
                                'output': 'json'});
                            var message = "<?php echo $t['loadigTextLabel']; ?>";
                            $("#infoPanelForm").html('').empty().html("<div class='alert alert-info'><img src='./images/icons/smiley-roll.png'> " + message + " Id: " + id + "  : " + filename + "</div>")

                        });

                        // end upload avatar
                    });
                    function showMeAll(toggle) {
                        showMeDiv('address', toggle);
                        showMeDiv('contact', toggle);
                        showMeDiv('web', toggle);
                        showMeDiv('notes', toggle);
                    }
                </script>
            </div></div></form>
<?php } ?>
<script type="text/javascript" src="./v3/humanResource/recruitment/javascript/candidate.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>