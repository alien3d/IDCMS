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
require_once($newFakeDocumentRoot . "v3/system/management/controller/staffController.php");
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
$translator->setCurrentTable('staff');

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
$staffArray = array();
$roleArray = array();
$languageArray = array();
$departmentArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $staff = new \Core\System\Management\Staff\Controller\StaffClass();
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
            $staff->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $staff->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $staff->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $staff->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $staff->setStartDay($start[2]);
            $staff->setStartMonth($start[1]);
            $staff->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $staff->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $staff->setEndDay($start[2]);
            $staff->setEndMonth($start[1]);
            $staff->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $staff->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $staff->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $staff->setServiceOutput('html');
        $staff->setLeafId($leafId);
        $staff->execute();
        $roleArray = $staff->getRole();
        $languageArray = $staff->getLanguage();
        $departmentArray = $staff->getdepartment();
        if ($_POST['method'] == 'read') {
            $staff->setStart($offset);
            $staff->setLimit($limit); // normal system don't like paging..
            $staff->setPageOutput('html');
            $staffArray = $staff->read();
            if (isset($staffArray [0]['firstRecord'])) {
                $firstRecord = $staffArray [0]['firstRecord'];
            }
            if (isset($staffArray [0]['nextRecord'])) {
                $nextRecord = $staffArray [0]['nextRecord'];
            }
            if (isset($staffArray [0]['previousRecord'])) {
                $previousRecord = $staffArray [0]['previousRecord'];
            }
            if (isset($staffArray [0]['lastRecord'])) {
                $lastRecord = $staffArray [0]['lastRecord'];
                $endRecord = $staffArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($staff->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($staffArray [0]['total'])) {
                $total = $staffArray [0]['total'];
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
                echo $template->breadcrumb(
                        $applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId
                );
                ?>
 </div>
</div>
<div id="content" style="opacity: 1;">
 <div id="infoErrorRowFluid" class="row hidden">
  <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
 </div>
 <div id="content" style="opacity: 1;">
  <div class="row">
   <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10">
    <button title="A" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'A');"> A </button>
    <button title="B" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'B');"> B </button>
    <button title="C" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'C');"> C </button>
    <button title="D" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'D');"> D </button>
    <button title="E" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'E');"> E </button>
    <button title="F" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'F');"> F </button>
    <button title="G" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'G');"> G </button>
    <button title="H" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'H');"> H </button>
    <button title="I" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'I');"> I </button>
    <button title="J" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'J');"> J </button>
    <button title="K" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'K');"> K </button>
    <button title="L" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'L');"> L </button>
    <button title="M" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'M');"> M </button>
    <button title="N" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'N');"> N </button>
    <button title="O" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'O');"> O </button>
    <button title="P" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'P');"> P </button>
    <button title="Q" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'Q');"> Q </button>
    <button title="R" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'R');"> R </button>
    <button title="S" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'S');"> S </button>
    <button title="T" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'T');"> T </button>
    <button title="U" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'U');"> U </button>
    <button title="V" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'V');"> V </button>
    <button title="W" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'W');"> W </button>
    <button title="X" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'X');"> X </button>
    <button title="Y" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'Y');"> Y </button>
    <button title="Z" class="btn btn-success btn-sm" type="button" 
                                onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                                echo $staff->getViewPath();
                                ?>', '<?php echo $securityToken; ?>', 'Z');"> Z </button>
   </div>
   <div class="control-label col-xs-2 col-sm-2 col-md-2">
    <div class="pull-right">
     <div class="btn-group">
      <button class="btn btn-warning btn-sm" type="button" > <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
      <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" > <span class="caret"></span> </button>
      <ul class="dropdown-menu" style="text-align:left">
       <li> <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                        echo $staff->getControllerPath();
                                        ?>', '<?php echo $securityToken; ?>', 'excel');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a> </li>
       <li> <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                        echo $staff->getControllerPath();
                                        ?>', '<?php echo $securityToken; ?>', 'csv');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a> </li>
       <li> <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                        echo $staff->getControllerPath();
                                        ?>', '<?php echo $securityToken; ?>', 'html');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Html&nbsp;&nbsp; </a> </li>
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
       <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block"
                                            onClick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $staff->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"> <?php echo $t['newButtonLabel']; ?></button>
      </div>
      <label for="queryWidget"></label>
      <input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            echo $_POST['query'];
                                                                        }
                                                                        ?>">
      <br>
      <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $staff->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
      <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $staff->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">
      <br>
      <table class="table table-striped table-condensed table-hover">
       <tr>
        <td>&nbsp;</td>
        <td align="center"><img src="./images/icons/calendar-select-days-span.png"></td>
        <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $staff->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                            echo date(
                                                    'd-m-Y'
                                            );
                                            ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a></td>
        <td>&nbsp;</td>
       </tr>
       <tr>
        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
        <td align="center"><img src="./images/icons/calendar-select-days.png"></td>
        <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $staff->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
       </tr>
       <tr>
        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a></td>
        <td align="center"><img src="./images/icons/calendar-select-week.png"></td>
        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $staff->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
       </tr>
       <tr>
        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td>
        <td align="center"><img src="./images/icons/calendar-select-month.png"></td>
        <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $staff->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
       </tr>
       <tr>
        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td>
        <td align="center"><img src="./images/icons/calendar.png"></td>
        <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $staff->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $staff->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
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
         <input type="hidden" name="staffIdPreview" id="staffIdPreview">
         <div class="form-group" id="roleIdDiv">
          <label for="roleIdPreview"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['roleIdLabel']; ?></label>
          <div class="col-xs-8 col-sm-8 col-md-8">
           <input class="form-control" type="text" name="roleIdPreview"
                                                           id="roleIdPreview">
          </div>
         </div>
         <div class="form-group" id="languageIdDiv">
          <label for="languageIdPreview"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['languageIdLabel']; ?></label>
          <div class="col-xs-8 col-sm-8 col-md-8">
           <input class="form-control" type="text" name="languageIdPreview"
                                                           id="languageIdPreview">
          </div>
         </div>
         <div class="form-group" id="departmentIdDiv">
          <label for="departmentIdPreview"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['departmentIdLabel']; ?></label>
          <div class="col-xs-8 col-sm-8 col-md-8">
           <input class="form-control" type="text" name="departmentIdPreview"
                                                           id="departmentIdPreview">
          </div>
         </div>
         <div class="form-group" id="staffNameDiv">
          <label for="staffNamePreview"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['staffNameLabel']; ?></label>
          <div class="col-xs-8 col-sm-8 col-md-8">
           <input class="form-control" type="text" name="staffNamePreview"
                                                           id="staffNamePreview">
          </div>
         </div>
        </form>
       </div>
       <div class="modal-footer">
        <button type="button"  class="btn btn-danger"
                                                onClick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                                echo $staff->getControllerPath();
                                                ?>', '<?php
                                                echo $staff->getViewPath();
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
          <th width="25px" align="center"> <div align="center">#</div></th>
          <th width="100px"> <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
          <th width="150px"><?php echo ucwords($leafTranslation['roleIdLabel']); ?></th>
          <th><?php echo ucwords($leafTranslation['staffNameLabel']); ?></th>
          <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
          <th width="25px" align="center"> <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                                   onChange="toggleChecked(this.checked);">
          </th>
         </tr>
        </thead>
        <tbody id="tableBody">
         <?php
                                            if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                if (is_array($staffArray)) {
                                                    $totalRecord = intval(count($staffArray));
                                                    if ($totalRecord > 0) {
                                                        $counter = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $counter++;
                                                            ?>
         <tr <?php
                                                            if ($departmentArray[$i]['isDelete'] == 1) {
                                                                echo "class=\"danger\"";
                                                            } else {
                                                                if ($departmentArray[$i]['isDraft'] == 1) {
                                                                    echo "class=\"warning\"";
                                                                }
                                                            }
                                                            ?>>
          <td align="center"><?php echo($counter + $offset); ?></td>
          <td align="center">
		  <?php if($staffArray[$i]['staffCode']!='UNBL') { ?>
		  <div class="btn-group" align="center">
            <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                onClick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                echo $staff->getControllerPath();
                                                                                ?>', '<?php
                                                                                echo $staff->getViewPath();
                                                                                ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                echo intval(
                                                                                        $staffArray [$i]['staffId']
                                                                                );
                                                                                ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"> <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
            <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete('<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['staffId']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['roleDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['departmentDesc']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['languageDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['departmentDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['staffSequence']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['staffName']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['staffNumber']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['staffIdentificationCard']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['staffAvatar']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isDefault']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isNew']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isDraft']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isUpdate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isDelete']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isActive']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isApproved']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isReview']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['isPost']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['executeBy']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $staffArray [$i]['executeTime']
                                                                        );
                                                                        ?>');"> <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
           </div><?php } else { ?>&nbsp;<?php } ?></td>
          
           <td><div class="pull-left"><?php if (isset($staffArray[$i]['roleDescription'])) { echo $staffArray[$i]['roleDescription']; } ?></div></td>
          
           <td><div class="pull-left"><?php if (isset($staffArray[$i]['staffFirstName'])) { echo $staffArray[$i]['staffName']." ".$staffArray[$i]['staffFirstName'];  } ?></div></td>
          

          <?php
                                                                if (isset($staffArray[$i]['executeTime'])) {
                                                                    $valueArray = $staffArray[$i]['executeTime'];
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
																																																																		$value=null;
																																																																}
                                                                    ?>
          <td><?php echo $value; ?></td>
    
          <?php
                                                                if ($staffArray[$i]['isDelete']) {
                                                                    $checked = "checked";
                                                                } else {
                                                                    $checked = null;
                                                                }
                                                                ?>
          <td align="center"><input style="display:none;" type="checkbox" name="staffId[]"
                                                                           value="<?php echo $staffArray[$i]['staffId']; ?>">
           <label>
            <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                       value="<?php echo $staffArray[$i]['isDelete']; ?>">
           </label></td>
         </tr>
         <?php
                                                        }
                                                    } else {
                                                        ?>
         <tr>
          <td colspan="7" vAlign="top" align="center"><?php
                                                                $staff->exceptionMessage(
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
                                                            $staff->exceptionMessage(
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
                                                        $staff->exceptionMessage(
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
                                            onClick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                            echo $staff->getControllerPath();
                                            ?>', '<?php echo $staff->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
      </div>
     </div>
    </div>
    <script type="text/javascript">
                            $(document).ready(function() {
                                window.scrollTo(0, 0);
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
       <div class="btn-group"> <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="firstRecord(<?php echo $leafId; ?>, '<?php
                                       echo $staff->getControllerPath();
                                       ?>', '<?php
                                       echo $staff->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </a> </div>
       <div class="btn-group"> <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $staff->getControllerPath();
                                       ?>', '<?php
                                       echo $staff->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </a> </div>
       <div class="btn-group"> <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $staff->getControllerPath();
                                       ?>', '<?php
                                       echo $staff->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </a> </div>
       <div class="btn-group"> <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $staff->getControllerPath();
                                       ?>', '<?php
                                       echo $staff->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </a> </div>
      </div>
     </div>
     <div class="panel-body">
      <input type="hidden" name="staffId" id="staffId" value="<?php
                            if (isset($_POST['staffId'])) {
                                echo $_POST['staffId'];
                            }
                            ?>">
      <div class="row">
       <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="roleIdForm">
         <label for="roleId"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['roleIdLabel']; ?></label>
         <div class="col-xs-8 col-sm-8 col-md-8">
          <select name="roleId" id="roleId" class="chzn-select form-control">
           <option value=""></option>
           <?php
                                                if (is_array($roleArray)) {
                                                    $totalRecord = intval(count($roleArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($staffArray[0]['roleId'] == $roleArray[$i]['roleId']) {
                                                                $selected = "selected";
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
           <option
                                                                value="<?php echo $roleArray[$i]['roleId']; ?>" <?php echo $selected; ?>><?php echo $roleArray[$i]['roleDescription']; ?></option>
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
          </select>
         </div>
        </div>
       </div>
      </div>
      <div class="row">
       <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="languageIdForm">
         <label for="languageId"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['languageIdLabel']; ?></label>
         <div class="col-xs-8 col-sm-8 col-md-8">
          <select name="languageId" id="languageId" class="chzn-select form-control">
           <option value=""></option>
           <?php
                                                if (is_array($languageArray)) {
                                                    $totalRecord = intval(count($languageArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($staffArray[0]['languageId'] == $languageArray[$i]['languageId']) {
                                                                $selected = "selected";
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
           <option
                                                                value="<?php echo $languageArray[$i]['languageId']; ?>" <?php echo $selected; ?>><?php echo $languageArray[$i]['languageDescription']; ?></option>
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
          </select>
         </div>
        </div>
       </div>
      </div>
      <div class="row">
       <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="departmentIdForm">
         <label for="departmentId"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['departmentIdLabel']; ?></label>
         <div class="col-xs-8 col-sm-8 col-md-8">
          <select name="departmentId" id="departmentId" class="chzn-select form-control">
           <option value=""></option>
           <?php
                                                if (is_array($departmentArray)) {
                                                    $totalRecord = intval(count($departmentArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($staffArray[0]['departmentId'] == $departmentArray[$i]['departmentId']) {
                                                                $selected = "selected";
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
           <option
                                                                value="<?php echo $departmentArray[$i]['departmentId']; ?>" <?php echo $selected; ?>><?php echo $departmentArray[$i]['departmentDescription']; ?></option>
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
          </select>
         </div>
        </div>
       </div>
      </div>
      <div class="row">
       <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group" id="staffNameForm">
         <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="staffName"><?php echo $leafTranslation['staffNameLabel']; ?></label>
         <div class="col-xs-8 col-sm-8 col-md-8">
          <input type="text" class="form-control" name="staffName" id="staffName"
                                                   onKeyUp="removeMeError('staffName');" value="<?php
                                                   if (isset($staffArray) && is_array(
                                                                   $staffArray
                                                           )
                                                   ) {
                                                       echo $staffArray[0]['staffName'];
                                                   }
                                                   ?>">
         </div>
        </div>
       </div>
      </div>
      <div class="row">
       <div class="col-sm-12">
        <div class="form-group" id="staffAvatarForm">
         <label class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['staffAvatarLabel']; ?></label>
         <div class="col-xs-8 col-sm-8 col-md-8">
          <input type="text" class="form-control" name="staffAvatar" id="staffAvatar"
                                                   onKeyUp="removeMeError('staffAvatar');" value="<?php
                                                   if (isset($staffArray) && is_array(
                                                                   $staffArray
                                                           )
                                                   ) {
                                                       echo $staffArray[0]['staffAvatar'];
                                                   }
                                                   ?>">
         </div>
        </div>
       </div>
      </div>
     </div>
     <div class="panel-footer" align="center">
      <div class="btn-group" align="left"> <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> <a id="newRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                   class="btn dropdown-toggle btn-success disabled"><span class="caret"></span></a>
       <ul class="dropdown-menu" style="text-align:left">
        <li> <a id="newRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?> </a> </li>
        <li> <a id="newRecordButton4" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?> </a></li>
        <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                      ?></a></li>-->
        <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                      ?></a></li>-->
        <li> <a id="newRecordButton7" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a> </li>
       </ul>
      </div>
      <div class="btn-group" align="left"> <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                        class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> <a id="updateRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                   class="btn dropdown-toggle btn-info disabled"><span class="caret"></span></a>
       <ul class="dropdown-menu" style="text-align:left">
        <li> <a id="updateRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?> </a></li>
        <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                      ?></a></li> -->
        <li> <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?> </a></li>
       </ul>
      </div>
      <div class="btn-group">
       <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"> <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
      </div>
      <div class="btn-group"> <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $staff->getControllerPath();
                                   ?>', '<?php
                                   echo $staff->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </a> </div>

      <div class="btn-group"> <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $staff->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </a> </div>
     </div>
    </div>
    <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php
                    if (isset($firstRecord)) {
                        echo $firstRecord;
                    }
                    ?>">
    <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                           if (isset($nextRecord)) {
                               echo $nextRecord;
                           }
                           ?>">
    <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php
                           if (isset($previousRecord)) {
                               echo $previousRecord;
                           }
                           ?>">
    <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php
                           if (isset($lastRecord)) {
                               echo $lastRecord;
                           }
                           ?>">
    <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php
                           if (isset($endRecord)) {
                               echo $endRecord;
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
                    


                    newRecord(<?php echo $leafId; ?>, '<?php echo $staff->getControllerPath(); ?>', '<?php echo $staff->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                // shift+s save event
                if (e.which === 83 && e.which === 18  && e.shiftKey) {
                    


                    updateRecord(<?php echo $leafId; ?>, '<?php echo $staff->getControllerPath(); ?>', '<?php echo $staff->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                // shift+d delete event
                if (e.which === 88 && e.which === 18 && e.shiftKey) {
                    


                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $staff->getControllerPath(); ?>', '<?php echo $staff->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;

                }
    <?php } ?>
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $staff->getControllerPath(); ?>', '<?php echo $staff->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $staff->getControllerPath(); ?>', '<?php echo $staff->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
            }
            

        });
        $(document).ready(function() {
            tableHeightSize()
            $(window).resize(function() {
                tableHeightSize()
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen();
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('staffId');
            validateMeNumeric('roleId');
            validateMeNumeric('languageId');
            validateMeNumeric('departmentId');
            validateMeAlphaNumeric('staffName');
            validateMeAlphaNumeric('staffNumber');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['staffId']) {
            ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                    $('#newRecordButton3').attr('onClick', '');
                    $('#newRecordButton4').attr('onClick', '');
                    $('#newRecordButton5').attr('onClick', '');
                    $('#newRecordButton6').attr('onClick', '');
                    $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
            <?php } else { ?>
                        $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $staff->getControllerPath(); ?>','<?php echo $staff->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
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
<script type="text/javascript" src="./v3/system/management/javascript/staff.js"></script>
<hr>
<footer>
 <p>IDCMS 2012/2013</p>
</footer>
