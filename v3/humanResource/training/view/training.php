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
require_once($newFakeDocumentRoot . "v3/humanResource/training/controller/trainingController.php");
require_once($newFakeDocumentRoot . "v3/humanResource/training/controller/trainingEmployeeController.php");
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
$translator->setCurrentTable(array('training', 'trainingEmployee'));
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
$trainingArray = array();
$trainingTypeArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $training = new \Core\HumanResource\Training\Training\Controller\TrainingClass();
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
            $training->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $training->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $training->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $training->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $training->setStartDay($start[2]);
            $training->setStartMonth($start[1]);
            $training->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $training->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $training->setEndDay($start[2]);
            $training->setEndMonth($start[1]);
            $training->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $training->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $training->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $training->setServiceOutput('html');
        $training->setLeafId($leafId);
        $training->execute();
        $trainingTypeArray = $training->getTrainingType();
        if ($_POST['method'] == 'read') {
            $training->setStart($offset);
            $training->setLimit($limit); // normal system don't like paging..
            $training->setPageOutput('html');
            $trainingArray = $training->read();
            if (isset($trainingArray [0]['firstRecord'])) {
                $firstRecord = $trainingArray [0]['firstRecord'];
            }
            if (isset($trainingArray [0]['nextRecord'])) {
                $nextRecord = $trainingArray [0]['nextRecord'];
            }
            if (isset($trainingArray [0]['previousRecord'])) {
                $previousRecord = $trainingArray [0]['previousRecord'];
            }
            if (isset($trainingArray [0]['lastRecord'])) {
                $lastRecord = $trainingArray [0]['lastRecord'];
                $endRecord = $trainingArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($training->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($trainingArray [0]['total'])) {
                $total = $trainingArray [0]['total'];
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
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $training->getViewPath();
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
                                    echo $training->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $training->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $training->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'html');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Html&nbsp;&nbsp; </a>
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

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block"
                                            onClick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $training->getViewPath();
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
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $training->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $training->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"> <br>

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $training->getViewPath();
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
                                               echo $training->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $training->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $training->getViewPath();
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
                                               echo $training->getViewPath();
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
                                                              echo $training->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $training->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $training->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png"
                                                 alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $training->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $training->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $training->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $training->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $training->getViewPath();
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
                                           echo $training->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                           onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $training->getViewPath();
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
                                        <input type="hidden" name="trainingIdPreview" id="trainingIdPreview">

                                        <div class="form-group" id="trainingTypeIdDiv">
                                            <label for="trainingTypeIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['trainingTypeIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="trainingTypeIdPreview"
                                                       id="trainingTypeIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="trainingTitleDiv">
                                            <label for="trainingTitlePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['trainingTitleLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="trainingTitlePreview"
                                                       id="trainingTitlePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="trainingLocationDiv">
                                            <label for="trainingLocationPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['trainingLocationLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="trainingLocationPreview"
                                                       id="trainingLocationPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="trainingDescriptionDiv">
                                            <label for="trainingDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['trainingDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="trainingDescriptionPreview"
                                                       id="trainingDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="trainingDateDiv">
                                            <label for="trainingDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['trainingDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="trainingDatePreview"
                                                       id="trainingDatePreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onClick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $training->getControllerPath();
                                            ?>', '<?php
                                            echo $training->getViewPath();
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
                                    <th width="100px"><?php echo ucwords($leafTranslation['trainingTypeIdLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['trainingTitleLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['trainingLocationLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['trainingDescriptionLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['trainingDateLabel']); ?></th>
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
                                            if (is_array($trainingArray)) {
                                                $totalRecord = intval(count($trainingArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($trainingArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($trainingArray[$i]['isDraft'] == 1) {
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
                                                                            echo $training->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $training->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $trainingArray [$i]['trainingId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onClick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $trainingArray [$i]['trainingId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $trainingArray [$i]['trainingTypeDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $trainingArray [$i]['trainingTitle']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $trainingArray [$i]['trainingLocation']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $trainingArray [$i]['trainingDescription']
                                                                            );
                                                                            ?>', '<?php echo rawurlencode($trainingArray [$i]['trainingDate']); ?>');">
                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-left">
                                                                    <?php
                                                                    if (isset($trainingArray[$i]['trainingTypeDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $trainingArray[$i]['trainingTypeDescription'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $trainingArray[$i]['trainingTypeDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingTypeDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $trainingArray[$i]['trainingTypeDescription'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $trainingArray[$i]['trainingTypeDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $trainingArray[$i]['trainingTypeDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingTypeDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $trainingArray[$i]['trainingTypeDescription'];
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
                                                                    if (isset($trainingArray[$i]['trainingTitle'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($trainingArray[$i]['trainingTitle']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $trainingArray[$i]['trainingTitle']
                                                                                    );
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingTitle'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($trainingArray[$i]['trainingTitle']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $trainingArray[$i]['trainingTitle']
                                                                                        );
                                                                                    } else {
                                                                                        echo $trainingArray[$i]['trainingTitle'];
                                                                                    }
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingTitle'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $trainingArray[$i]['trainingTitle'];
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
                                                                    if (isset($trainingArray[$i]['trainingLocation'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($trainingArray[$i]['trainingLocation']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $trainingArray[$i]['trainingLocation']
                                                                                    );
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingLocation'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($trainingArray[$i]['trainingLocation']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $trainingArray[$i]['trainingLocation']
                                                                                        );
                                                                                    } else {
                                                                                        echo $trainingArray[$i]['trainingLocation'];
                                                                                    }
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingLocation'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $trainingArray[$i]['trainingLocation'];
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
                                                                    if (isset($trainingArray[$i]['trainingDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($trainingArray[$i]['trainingDescription']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $trainingArray[$i]['trainingDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($trainingArray[$i]['trainingDescription']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $trainingArray[$i]['trainingDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $trainingArray[$i]['trainingDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $trainingArray[$i]['trainingDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $trainingArray[$i]['trainingDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($trainingArray[$i]['trainingDate'])) {
                                                                $valueArray = $trainingArray[$i]['trainingDate'];
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
                                                                <td><?php echo $value; ?></td>
                                                            <?php } else { ?>
                                                                <td>
                                                                    <div class="pull-left">&nbsp;</div>
                                                                </td>
                                                            <?php } ?>
                                                            <td>
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($trainingArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($trainingArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $trainingArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $trainingArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $trainingArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $trainingArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $trainingArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $trainingArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $trainingArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($trainingArray[$i]['executeTime'])) {
                                                                $valueArray = $trainingArray[$i]['executeTime'];
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
                                                            if ($trainingArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <label for="trainingId[]">
                                                                    <input style="display:none;" type="checkbox" name="trainingId[]"
                                                                           value="<?php echo $trainingArray[$i]['trainingId']; ?>">
                                                                </label> <label for="isDelete[]">
                                                                    <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                   value="<?php echo $trainingArray[$i]['isDelete']; ?>">
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" vAlign="top" align="center"><?php
                                                            $training->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" vAlign="top" align="center"><?php
                                                        $training->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" vAlign="top" align="center"><?php
                                                    $training->exceptionMessage(
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
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 pull-left"><?php $navigation->pagenationv4($offset); ?></div>
                            <div class="col-xs-3 col-sm-3 col-md-3 pagination">
                                <div class="pull-right">
                                    <button class="delete btn btn-warning" type="button" 
                                            onClick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                            echo $training->getControllerPath();
                                            ?>', '<?php echo $training->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
        </div>
        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <?php
    $trainingEmployee = new \Core\HumanResource\Training\TrainingEmployee\Controller\TrainingEmployeeClass();
    $trainingEmployee->setServiceOutput('html');
    $trainingEmployee->setLeafId($leafId);
    $trainingEmployee->execute();
    $employeeArray = $trainingEmployee->getEmployee();
    $trainingEmployee->setStart(0);
    $trainingEmployee->setLimit(999999); // normal system don't like paging..
    $trainingEmployee->setPageOutput('html');
    if (isset($_POST['trainingId'])) {
        $trainingEmployeeArray = $trainingEmployee->read();
    }
    ?>
    <?php $trainingEmployee->setService('option'); ?>
    <form class="form-horizontal">
        <input type="hidden" name="trainingId" id="trainingId" value="<?php
        if (isset($_POST['trainingId'])) {
            echo $_POST['trainingId'];
        }
        ?>">

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
                                       echo $training->getControllerPath();
                                       ?>', '<?php echo $training->getViewPath(); ?>', '<?php
                                       echo $trainingEmployee->getControllerPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $training->getControllerPath();
                                       ?>', '<?php
                                       echo $training->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $training->getControllerPath();
                                       ?>', '<?php
                                       echo $training->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $training->getControllerPath();
                                       ?>', '<?php echo $training->getViewPath(); ?>', '<?php
                                       echo $trainingEmployee->getControllerPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="trainingTypeIdForm">
                                        <label for="trainingTypeId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['trainingTypeIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="trainingTypeId" id="trainingTypeId" class="chzn-select form-control"
                                                    onChange="removeMeError('trainingTypeId');">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($trainingTypeArray)) {
                                                    $totalRecord = intval(count($trainingTypeArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($trainingArray[0]['trainingTypeId'])) {
                                                                if ($trainingArray[0]['trainingTypeId'] == $trainingTypeArray[$i]['trainingTypeId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $trainingTypeArray[$i]['trainingTypeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $trainingTypeArray[$i]['trainingTypeDescription']; ?></option>
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
                                            </select> <span class="help-block" id="trainingTypeIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($trainingArray) && is_array($trainingArray)) {
                                        if (isset($trainingArray[0]['trainingDate'])) {
                                            $valueArray = $trainingArray[0]['trainingDate'];
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="trainingDateForm">
                                        <label for="trainingDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['trainingDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="trainingDate" id="trainingDate"
                                                       value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                       id="trainingDateImage"></span>
                                            </div>
                                            <span class="help-block" id="trainingDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="trainingLocationForm">
                                        <label for="trainingLocation" class="control-label col-xs-2 col-sm-2 col-md-2"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['trainingLocationLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-10 col-sm-10 col-md-10">
                                            <textarea class="form-control" name="trainingLocation" id="trainingLocation"><?php
                                                if (isset($trainingArray) && is_array($trainingArray)) {
                                                    if (isset($trainingArray[0]['trainingLocation'])) {
                                                        echo htmlentities($trainingArray[0]['trainingLocation']);
                                                    }
                                                }
                                                ?></textarea> <span class="help-block" id="trainingLocationHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group" id="trainingTitleForm">
                                    <label for="trainingTitle" class="control-label col-xs-2 col-sm-2 col-md-2"><strong><?php
                                            echo ucfirst(
                                                    $leafTranslation['trainingTitleLabel']
                                            );
                                            ?></strong></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <input type="text" class="form-control" class="col-xs-10 col-sm-10 col-md-10" name="trainingTitle"
                                               id="trainingTitle" onKeyUp="removeMeError('trainingTitle', 12)" value="<?php
                                               if (isset($trainingArray) && is_array($trainingArray)) {
                                                   if (isset($trainingArray[0]['trainingTitle'])) {
                                                       echo htmlentities($trainingArray[0]['trainingTitle']);
                                                   }
                                               }
                                               ?>"> <span class="help-block" id="trainingTitleHelpMe"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="trainingDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea class="form-control" name="trainingDescription"
                                                      id="trainingDescription"><?php
                                                          if (isset($trainingArray[0]['trainingDescription'])) {
                                                              echo htmlentities($trainingArray[0]['trainingDescription']);
                                                          }
                                                          ?></textarea> <span class="help-block" id="trainingDescriptionHelpMe"></span>
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
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                       ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                       ?></a></li>-->
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
                                   echo $training->getControllerPath();
                                   ?>', '<?php
                                   echo $training->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $training->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?>
                                </a>
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
                    <div class="modal hide" id="deleteDetailPreview" tabindex="-1">
                        <div class="modal-header">
                            <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="trainingEmployeeIdPreview" id="trainingEmployeeIdPreview">

                            <div class="form-group" id="employeeIdDiv">
                                <label for="employeeIdPreview"
                                       class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['employeeIdLabel']; ?></label>

                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input type="text" class="form-control" name="employeeIdPreview" id="employeeIdPreview">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-danger" onClick="deleteGridRecordDetail(<?php echo $leafId; ?>, '<?php
                            echo $trainingEmployee->getControllerPath();
                            ?>', '<?php
                            echo $trainingEmployee->getViewPath();
                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                            <button type="button"  onClick="showMeModal('deleteDetailPreview', 0);" class="btn btn-default"
                                    data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                <thead>
                                    <tr>
                                        <th width="25" align="center">
                                <div align="center">#</div>
                                </th>
                                <th><?php echo ucfirst($leafTranslation['employeeIdLabel']); ?></th>
                                <th width="50px">
                                <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                </th>
                                </tr>
                                <tr>
                                    <?php
                                    $disabledDetail = null;
                                    if (isset($_POST['trainingId']) && (strlen($_POST['trainingId']) > 0)) {
                                        $disabledDetail = null;
                                    } else {
                                        $disabledDetail = "disabled";
                                    }
                                    ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td vAlign="top">
                                        <div class="form-group" id="employeeId9999Detail">
                                            <select name="employeeId[]" id="employeeId9999"
                                                    class="chzn-select form-control"
                                                    onChange="removeMeErrorDetail('employeeId9999');"
                                                    <?php echo $disabledDetail; ?>>
                                                <option value=""></option>
                                                <?php
                                                if (is_array($employeeArray)) {
                                                    $totalRecord = intval(count($employeeArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            ?>
                                                            <option
                                                                value="<?php echo $employeeArray[$i]['employeeId']; ?>"><?php echo $employeeArray[$i]['employeeName']; ?></option>
                                                                <?php
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
                                            </select><span class="help-block" id="employeeId9999HelpMe"></span></div>
                                    </td>
                                    <td vAlign="top">
                                        <div align="center">
                                            <button class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onClick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $trainingEmployee->getControllerPath(); ?>', '<?php echo $securityToken; ?>');"><i
                                                    class="glyphicon glyphicon-plus  glyphicon-white"></i></button>

                                            <div id="miniInfoPanel9999"></div>
                                        </div>
                                    </td>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    if (isset($_POST['trainingId'])) {
                                        if (is_array($trainingEmployeeArray)) {
                                            $totalRecordDetail = intval(count($trainingEmployeeArray));
                                            if ($totalRecordDetail > 0) {
                                                $counter = 0;
                                                for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                    $counter++;
                                                    ?>
                                                    <tr id="<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>">
                                                        <td align="center">
                                                            <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group"
                                                                 id="employeeId<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>Detail">
                                                                <select name="employeeId[]"
                                                                        id="employeeId<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>"
                                                                        class="chzn-select form-control"
                                                                        onChange="removeMeErrorDetail('employeeId<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>');">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($employeeArray)) {
                                                                        $totalRecord = intval(count($employeeArray));
                                                                        if ($totalRecord > 0) {
                                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                                if ($trainingEmployeeArray[$j]['employeeId'] == $employeeArray[$i]['employeeId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                                ?>
                                                                                <option
                                                                                    value="<?php echo $employeeArray[$i]['employeeId']; ?>" <?php echo $selected; ?>><?php echo $employeeArray[$i]['employeeName']; ?></option>
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                            <option
                                                                                value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                        <option
                                                                            value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                        <?php } ?>
                                                                </select></div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <input type="hidden" name="trainingEmployeeId[]"
                                                                       id="trainingEmployeeId<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>"
                                                                       value="<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>">
                                                                <input type="hidden" name="trainingId[]"
                                                                       id="trainingId<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>"
                                                                       value="<?php echo $trainingEmployeeArray[$j]['trainingId']; ?>">
                                                                <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                        onClick="showFormUpdateDetail(<?php echo $leafId; ?>, '<?php
                                                                        echo $trainingEmployee->getControllerPath();
                                                                        ?>', '<?php echo $securityToken; ?>', '<?php
                                                                        echo intval(
                                                                                $trainingEmployeeArray [$j]['trainingEmployeeId']
                                                                        );
                                                                        ?>');">
                                                                    <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                        onClick="showModalDeleteDetail('<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>');">
                                                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                <div
                                                                    id="miniInfoPanel<?php echo $trainingEmployeeArray[$j]['trainingEmployeeId']; ?>"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6"><?php
                                                        $trainingEmployee->exceptionMessage(
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
                                                    $trainingEmployee->exceptionMessage(
                                                            $t['recordNotFoundLabel']
                                                    );
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(document).keypress(function(e) {

                        // shift+n new record event
                        if (e.which === 78 && e.which === 18  && e.shiftKey) {
                            

    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                newRecord(<?php echo $leafId; ?>, '<?php echo $training->getControllerPath(); ?>', '<?php echo $training->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                            return false;
                        }
                        // shift+s save event
                        if (e.which === 83 && e.which === 18  && e.shiftKey) {
                            

    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                updateRecord(<?php echo $leafId; ?>, '<?php echo $training->getControllerPath(); ?>', '<?php echo $training->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                            return false;
                        }
                        // shift+d delete event
                        if (e.which === 88 && e.which === 18 && e.shiftKey) {
                            

    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                deleteRecord(<?php echo $leafId; ?>, '<?php echo $training->getControllerPath(); ?>', '<?php echo $training->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

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
                                previousRecord(<?php echo $leafId; ?>, '<?php echo $training->getControllerPath(); ?>', '<?php echo $training->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                                
                                return false;
                                break;
                            case 39:
                                nextRecord(<?php echo $leafId; ?>, '<?php echo $training->getControllerPath(); ?>', '<?php echo $training->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                                
                                return false;
                                break;
                        }
                        

                    });
                    $(document).ready(function() {
                        window.scrollTo(0, 0);
                        $(".chzn-select").chosen();
                        $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                        validateMeNumeric('trainingId');
                        validateMeNumeric('trainingTypeId');
                        validateMeAlphaNumeric('trainingTitle');
                        validateMeAlphaNumeric('trainingLocation');
                        var a = $('#trainingDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        $("#trainingDateImage").on('click', function() {
                            a.datepicker('show');
                        });
                        validateMeNumericRange('trainingEmployeeId');
                        validateMeNumericRange('trainingId');
                        validateMeNumericRange('employeeId');
    <?php if ($_POST['method'] == "new") { ?>
                            $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                                $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                                $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                                $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                                $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['trainingId']) {
            ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                $('#newRecordButton3').attr('onClick', '');
                                $('#newRecordButton4').attr('onClick', '');
                                $('#newRecordButton5').attr('onClick', '');
                                $('#newRecordButton6').attr('onClick', '');
                                $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                    $('#updateRecordButton3').attr('onClick', '');
                                    $('#updateRecordButton4').attr('onClick', '');
                                    $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $training->getControllerPath(); ?>','<?php echo $training->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                    });
                </script>
            </div></div></form>
<?php } ?>
<script type="text/javascript" src="./v3/humanResource/training/javascript/training.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>