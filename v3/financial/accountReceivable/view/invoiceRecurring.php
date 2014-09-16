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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceRecurringController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceRecurringDetailController.php");
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

$translator->setCurrentTable(array('invoiceRecurring', 'invoiceRecurringDetail'));

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
$invoiceRecurringArray = array();
$invoiceRecurringTypeArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoiceRecurring = new \Core\Financial\AccountReceivable\InvoiceRecurring\Controller\InvoiceRecurringClass();
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
            $invoiceRecurring->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoiceRecurring->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoiceRecurring->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoiceRecurring->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoiceRecurring->setStartDay($start[2]);
            $invoiceRecurring->setStartMonth($start[1]);
            $invoiceRecurring->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoiceRecurring->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoiceRecurring->setEndDay($start[2]);
            $invoiceRecurring->setEndMonth($start[1]);
            $invoiceRecurring->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoiceRecurring->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoiceRecurring->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoiceRecurring->setServiceOutput('html');
        $invoiceRecurring->setLeafId($leafId);
        $invoiceRecurring->execute();
        $invoiceRecurringTypeArray = $invoiceRecurring->getInvoiceRecurringType();
        if ($_POST['method'] == 'read') {
            $invoiceRecurring->setStart($offset);
            $invoiceRecurring->setLimit($limit); // normal system don't like paging..
            $invoiceRecurring->setPageOutput('html');
            $invoiceRecurringArray = $invoiceRecurring->read();
            if (isset($invoiceRecurringArray [0]['firstRecord'])) {
                $firstRecord = $invoiceRecurringArray [0]['firstRecord'];
            }
            if (isset($invoiceRecurringArray [0]['nextRecord'])) {
                $nextRecord = $invoiceRecurringArray [0]['nextRecord'];
            }
            if (isset($invoiceRecurringArray [0]['previousRecord'])) {
                $previousRecord = $invoiceRecurringArray [0]['previousRecord'];
            }
            if (isset($invoiceRecurringArray [0]['lastRecord'])) {
                $lastRecord = $invoiceRecurringArray [0]['lastRecord'];
                $endRecord = $invoiceRecurringArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoiceRecurring->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceRecurringArray [0]['total'])) {
                $total = $invoiceRecurringArray [0]['total'];
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
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div id="content" style="opacity: 1;">
            <div class="row">
                <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10">
                    <button title="A" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');"> A </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');"> B </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');"> C </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');"> D </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');"> E </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');"> F </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');"> G </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');"> H </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');"> I </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');"> J </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');"> K </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');"> L </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');"> M </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');"> N </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');"> O </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');"> P </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');"> Q </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');"> R </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');"> S </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');"> T </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');"> U </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');"> V </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');"> W </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');"> X </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');"> Y </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceRecurring->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');"> Z </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button"> <span class="caret"></span> </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceRecurring->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a> </li>
                                <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceRecurring->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a> </li>
                                <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceRecurring->getControllerPath();
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

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                            class="btn btn-info btn-block"
                                            onclick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $invoiceRecurring->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"> <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label><div class="input-group"><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                        echo $_POST['query'];
                                    }
                                    ?>"><span class="input-group-addon"><img src="./images/icons/magnifier.png" id="searchTextDateImage"></span></div><br>
                                <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $invoiceRecurring->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString" value="<?php echo $t['clearButtonLabel']; ?>"
                                       class="btn btn-info btn-block" onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $invoiceRecurring->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"><table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center"><img src="./images/icons/calendar-select-days-span.png"
                                                                alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceRecurring->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                                              echo date(
                                                                      'd-m-Y'
                                                              );
                                                              ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Day <?php echo $previousDay; ?>"
                                                             onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceRecurring->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceRecurring->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                                            onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceRecurring->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceRecurring->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-week.png"
                                                                alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceRecurring->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceRecurring->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Month <?php echo $previousMonth; ?>"
                                                             onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceRecurring->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-month.png"
                                                                alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceRecurring->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Month <?php echo $nextMonth; ?>"
                                                            onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceRecurring->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Year <?php echo $previousYear; ?>"
                                                             onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceRecurring->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceRecurring->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Year <?php echo $nextYear; ?>"
                                                            onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceRecurring->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
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
                                       echo $invoiceRecurring->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchDate" id="clearSearchDate" value="<?php echo $t['clearButtonLabel']; ?>"
                                       class="btn btn-info btn-block" onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $invoiceRecurring->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
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
                                                        <input type="hidden" name="invoiceRecurringIdPreview" id="invoiceRecurringIdPreview">
                                                        <div class="form-group" id="invoiceRecurringTypeIdDiv">
                                                            <label for="invoiceRecurringTypeIdPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['invoiceRecurringTypeIdLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="invoiceRecurringTypeIdPreview" id="invoiceRecurringTypeIdPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="documentNumberDiv">
                                                            <label for="documentNumberPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="documentNumberPreview"
                                                                       id="documentNumberPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="referenceNumberDiv">
                                                            <label for="referenceNumberPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="referenceNumberPreview"
                                                                       id="referenceNumberPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="journalRecurringTitleDiv">
                                                            <label for="journalRecurringTitlePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['journalRecurringTitleLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="journalRecurringTitlePreview"
                                                                       id="journalRecurringTitlePreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="invoiceRecurringDescriptionDiv">
                                                            <label for="invoiceRecurringDescriptionPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['invoiceRecurringDescriptionLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="invoiceRecurringDescriptionPreview"
                                                                       id="invoiceRecurringDescriptionPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="invoiceRecurringDateDiv">
                                                            <label for="invoiceRecurringDatePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['invoiceRecurringDateLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="invoiceRecurringDatePreview"
                                                                       id="invoiceRecurringDatePreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="invoiceRecurringStartDateDiv">
                                                            <label for="invoiceRecurringStartDatePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['invoiceRecurringStartDateLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="invoiceRecurringStartDatePreview"
                                                                       id="invoiceRecurringStartDatePreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="invoiceRecurringEndDateDiv">
                                                            <label for="invoiceRecurringEndDatePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['invoiceRecurringEndDateLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="invoiceRecurringEndDatePreview" id="invoiceRecurringEndDatePreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="invoiceRecurringAmountDiv">
                                                            <label for="invoiceRecurringAmountPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['invoiceRecurringAmountLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="invoiceRecurringAmountPreview" id="invoiceRecurringAmountPreview">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"  class="btn btn-danger"
                                                            onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceRecurring->getControllerPath();
                                                            ?>', '<?php
                                                            echo $invoiceRecurring->getViewPath();
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
                                                            <th width="25px" align="center"> <div align="center">#</div>
                                                    </th>
                                                    <th width="100px"> <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                                    </th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['invoiceRecurringTypeIdLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['referenceNumberLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['journalRecurringTitleLabel']); ?></th>
                                                    <th><?php echo ucwords($leafTranslation['invoiceRecurringDescriptionLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['invoiceRecurringDateLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['invoiceRecurringStartDateLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['invoiceRecurringEndDateLabel']); ?></th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['invoiceRecurringAmountLabel']); ?></th>
                                                    <th width="100px"> <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                                    </th>
                                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                                    <th width="25px" align="center"> <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                                                                            onChange="toggleChecked(this.checked);">
                                                    </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tableBody">
                                                        <?php
                                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                            if (is_array($invoiceRecurringArray)) {
                                                                $totalRecord = intval(count($invoiceRecurringArray));
                                                                if ($totalRecord > 0) {
                                                                    $counter = 0;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        $counter++;
                                                                        ?>
                                                                        <tr <?php
                                                                        if ($invoiceRecurringArray[$i]['isDelete'] == 1) {
                                                                            echo "class=\"danger\"";
                                                                        } else {
                                                                            if ($invoiceRecurringArray[$i]['isDraft'] == 1) {
                                                                                echo "class=\"warning\"";
                                                                            }
                                                                        }
                                                                        ?>>
                                                                            <td><div align="center"><?php echo($counter + $offset); ?></div></td>
                                                                            <td><div class="btn-group">
                                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                            echo $invoiceRecurring->getControllerPath();
                                                                                            ?>', '<?php
                                                                                            echo $invoiceRecurring->getViewPath();
                                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                            echo intval(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringId']
                                                                                            );
                                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"> <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                            onclick="showModalDelete('<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringId']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringDescription']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['documentNumber']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['referenceNumber']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['journalRecurringTitle']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringDescription']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringDate']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringStartDate']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringEndDate']
                                                                                            );
                                                                                            ?>', '<?php
                                                                                            echo rawurlencode(
                                                                                                    $invoiceRecurringArray [$i]['invoiceRecurringAmount']
                                                                                            );
                                                                                            ?>');"> <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                                </div></td>
                                                                            <td><div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'], $_POST['query']
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $invoiceRecurringArray[$i]['invoiceRecurringTypeDescription'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?></td>
                                                                            <td><div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($invoiceRecurringArray[$i]['documentNumber'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                strtolower($invoiceRecurringArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceRecurringArray[$i]['documentNumber']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['documentNumber'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    strtolower($invoiceRecurringArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceRecurringArray[$i]['documentNumber']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $invoiceRecurringArray[$i]['documentNumber'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['documentNumber'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $invoiceRecurringArray[$i]['documentNumber'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?></td>
                                                                            <td><div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($invoiceRecurringArray[$i]['referenceNumber'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                strtolower($invoiceRecurringArray[$i]['referenceNumber']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceRecurringArray[$i]['referenceNumber']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['referenceNumber'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    strtolower($invoiceRecurringArray[$i]['referenceNumber']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceRecurringArray[$i]['referenceNumber']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $invoiceRecurringArray[$i]['referenceNumber'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['referenceNumber'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $invoiceRecurringArray[$i]['referenceNumber'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?></td>
                                                                            <td><div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($invoiceRecurringArray[$i]['journalRecurringTitle'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                strtolower($invoiceRecurringArray[$i]['journalRecurringTitle']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceRecurringArray[$i]['journalRecurringTitle']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['journalRecurringTitle'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    strtolower($invoiceRecurringArray[$i]['journalRecurringTitle']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceRecurringArray[$i]['journalRecurringTitle']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $invoiceRecurringArray[$i]['journalRecurringTitle'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['journalRecurringTitle'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $invoiceRecurringArray[$i]['journalRecurringTitle'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?></td>
                                                                            <td><div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($invoiceRecurringArray[$i]['invoiceRecurringDescription'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                strtolower($invoiceRecurringArray[$i]['invoiceRecurringDescription']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceRecurringArray[$i]['invoiceRecurringDescription']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['invoiceRecurringDescription'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    strtolower(
                                                                                                                            $invoiceRecurringArray[$i]['invoiceRecurringDescription']
                                                                                                                    ), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceRecurringArray[$i]['invoiceRecurringDescription']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $invoiceRecurringArray[$i]['invoiceRecurringDescription'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['invoiceRecurringDescription'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $invoiceRecurringArray[$i]['invoiceRecurringDescription'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?></td>
                                                                            <?php
                                                                            if (isset($invoiceRecurringArray[$i]['invoiceRecurringDate'])) {
                                                                                $valueArray = $invoiceRecurringArray[$i]['invoiceRecurringDate'];
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
                                                                                <td><div class="pull-left">&nbsp;</div></td>
                                                                            <?php } ?>
                                                                            <?php
                                                                            if (isset($invoiceRecurringArray[$i]['invoiceRecurringStartDate'])) {
                                                                                $valueArray = $invoiceRecurringArray[$i]['invoiceRecurringStartDate'];
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
                                                                                <td><div class="pull-left">&nbsp;</div></td>
                                                                            <?php } ?>
                                                                            <?php
                                                                            if (isset($invoiceRecurringArray[$i]['invoiceRecurringEndDate'])) {
                                                                                $valueArray = $invoiceRecurringArray[$i]['invoiceRecurringEndDate'];
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
                                                                                <td><div class="pull-left">&nbsp;</div></td>
                                                                            <?php } ?>
                                                                            <?php
                                                                            $d = $invoiceRecurringArray[$i]['invoiceRecurringAmount'];
                                                                            if (class_exists('NumberFormatter')) {
                                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                                    $d = $a->format($invoiceRecurringArray[$i]['invoiceRecurringAmount']);
                                                                                } else {
                                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                                }
                                                                            } else {
                                                                                $d = number_format($d);
                                                                            }
                                                                            ?>
                                                                            <td><div class="pull-right"><?php echo $d; ?></div></td>
                                                                            <td><div align="center">
                                                                                    <?php
                                                                                    if (isset($invoiceRecurringArray[$i]['executeBy'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                $invoiceRecurringArray[$i]['staffName'], $_POST['query']
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceRecurringArray[$i]['staffName']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['staffName'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    $invoiceRecurringArray[$i]['staffName'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceRecurringArray[$i]['staffName']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $invoiceRecurringArray[$i]['staffName'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $invoiceRecurringArray[$i]['staffName'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $invoiceRecurringArray[$i]['staffName'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?></td>
                                                                            <?php
                                                                            if (isset($invoiceRecurringArray[$i]['executeTime'])) {
                                                                                $valueArray = $invoiceRecurringArray[$i]['executeTime'];
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
                                                                            if ($invoiceRecurringArray[$i]['isDelete']) {
                                                                                $checked = "checked";
                                                                            } else {
                                                                                $checked = null;
                                                                            }
                                                                            ?>
                                                                            <td><input style="display:none;" type="checkbox" name="invoiceRecurringId[]"
                                                                                       value="<?php echo $invoiceRecurringArray[$i]['invoiceRecurringId']; ?>">
                                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                               value="<?php echo $invoiceRecurringArray[$i]['isDelete']; ?>"></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="7" valign="top" align="center"><?php
                                                                            $invoiceRecurring->exceptionMessage(
                                                                                    $t['recordNotFoundLabel']
                                                                            );
                                                                            ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="7" valign="top" align="center"><?php
                                                                        $invoiceRecurring->exceptionMessage(
                                                                                $t['recordNotFoundLabel']
                                                                        );
                                                                        ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="7" valign="top" align="center"><?php
                                                                    $invoiceRecurring->exceptionMessage(
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
                                                        echo $invoiceRecurring->getControllerPath();
                                                        ?>', '<?php echo $invoiceRecurring->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
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
                $invoiceRecurringDetail = new \Core\Financial\AccountReceivable\InvoiceRecurringDetail\Controller\InvoiceRecurringDetailClass( );
                $invoiceRecurringDetail->setServiceOutput('html');
                $invoiceRecurringDetail->setLeafId($leafId);
                $invoiceRecurringDetail->execute();
                $chartOfAccountArray = $invoiceRecurringDetail->getChartOfAccount();
                $countryArray = $invoiceRecurringDetail->getCountry();
                $transactionTypeArray = $invoiceRecurringDetail->getTransactionType();
                $invoiceRecurringDetail->setStart(0);
                $invoiceRecurringDetail->setLimit(999999); // normal system don't like paging..
                $invoiceRecurringDetail->setPageOutput('html');
                if ($_POST['invoiceRecurringId']) {
                    $invoiceRecurringDetailArray = $invoiceRecurringDetail->read();
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
                                            <div class="btn-group"> <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                                                       onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $invoiceRecurring->getControllerPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurring->getViewPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurringDetail->getControllerPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </a> </div>
                                            <div class="btn-group"> <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                                                       onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $invoiceRecurring->getControllerPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurring->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </a> </div>
                                            <div class="btn-group"> <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                                                       onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $invoiceRecurring->getControllerPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurring->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </a> </div>
                                            <div class="btn-group"> <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                                                       onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $invoiceRecurring->getControllerPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurring->getViewPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurringDetail->getControllerPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </a> </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <input type="hidden" name="invoiceRecurringId" id="invoiceRecurringId" value="<?php
                                        if (isset($_POST['invoiceRecurringId'])) {
                                            echo $_POST['invoiceRecurringId'];
                                        }
                                        ?>">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceRecurringTypeIdForm">
                                                    <label for="invoiceRecurringTypeId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['invoiceRecurringTypeIdLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <select name="invoiceRecurringTypeId" id="invoiceRecurringTypeId"
                                                                class="chzn-select form-control">
                                                            <option value=""></option>
                                                            <?php
                                                            if (is_array($invoiceRecurringTypeArray)) {
                                                                $totalRecord = intval(count($invoiceRecurringTypeArray));
                                                                if ($totalRecord > 0) {
                                                                    $d = 1;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        if (isset($invoiceRecurringArray[0]['invoiceRecurringTypeId'])) {
                                                                            if ($invoiceRecurringArray[0]['invoiceRecurringTypeId'] == $invoiceRecurringTypeArray[$i]['invoiceRecurringTypeId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                        } else {
                                                                            $selected = null;
                                                                        }
                                                                        ?>
                                                                        <option
                                                                            value="<?php echo $invoiceRecurringTypeArray[$i]['invoiceRecurringTypeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $invoiceRecurringTypeArray[$i]['invoiceRecurringTypeDescription']; ?></option>
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
                                                        </select>
                                                        <span class="help-block" id="invoiceRecurringTypeIdHelpMe"></span> </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="documentNumberForm">
                                                    <label for="documentNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['documentNumberLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="documentNumber" id="documentNumber"
                                                                   disabled class="<?php
                                                                   if (!isset($_POST['invoiceRecurringId'])) {
                                                                       echo "disabled";
                                                                   }
                                                                   ?>" value="<?php
                                                                   if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {
                                                                       if (isset($invoiceRecurringArray[0]['documentNumber'])) {
                                                                           echo htmlentities($invoiceRecurringArray[0]['documentNumber']);
                                                                       }
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/document-number.png"></span> </div></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="referenceNumberForm">
                                                    <label for="referenceNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['referenceNumberLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <input type="text" class="form-control" name="referenceNumber" id="referenceNumber"
                                                               value="<?php
                                                               if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {
                                                                   if (isset($invoiceRecurringArray[0]['referenceNumber'])) {
                                                                       echo htmlentities($invoiceRecurringArray[0]['referenceNumber']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="help-block" id="referenceNumberHelpMe"></span> </div>
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="journalRecurringTitleForm">
                                                    <label for="journalRecurringTitle" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['journalRecurringTitleLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <input type="text" class="form-control" name="journalRecurringTitle"
                                                               id="journalRecurringTitle" value="<?php
                                                               if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {
                                                                   if (isset($invoiceRecurringArray[0]['journalRecurringTitle'])) {
                                                                       echo htmlentities($invoiceRecurringArray[0]['journalRecurringTitle']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="help-block" id="journalRecurringTitleHelpMe"></span> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceRecurringDescriptionForm">
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <textarea name="invoiceRecurringDescription" id="invoiceRecurringDescription"
                                                                  class="form-control"><?php
                                                                      if (isset($invoiceRecurringArray[0]['invoiceRecurringDescription'])) {
                                                                          echo htmlentities($invoiceRecurringArray[0]['invoiceRecurringDescription']);
                                                                      }
                                                                      ?></textarea>
                                                        <span class="help-block" id="invoiceRecurringDescriptionHelpMe"></span> </div>
                                                </div>
                                                <?php
                                                if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {

                                                    if (isset($invoiceRecurringArray[0]['invoiceRecurringDate'])) {
                                                        $valueArray = $invoiceRecurringArray[0]['invoiceRecurringDate'];
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
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceRecurringDateForm">
                                                    <label for="invoiceRecurringDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['invoiceRecurringDateLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="invoiceRecurringDate"
                                                                   id="invoiceRecurringDate" value="<?php
                                                                   if (isset($value)) {
                                                                       echo $value;
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                 id="invoiceRecurringDateImage"></span> </div>
                                                        <span class="help-block" id="invoiceRecurringDateHelpMe"></span> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <?php
                                                if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {

                                                    if (isset($invoiceRecurringArray[0]['invoiceRecurringStartDate'])) {
                                                        $valueArray = $invoiceRecurringArray[0]['invoiceRecurringStartDate'];
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
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceRecurringStartDateForm">
                                                    <label for="invoiceRecurringStartDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['invoiceRecurringStartDateLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="invoiceRecurringStartDate"
                                                                   id="invoiceRecurringStartDate" value="<?php
                                                                   if (isset($value)) {
                                                                       echo $value;
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                 id="invoiceRecurringStartDateImage"></span> </div>
                                                        <span class="help-block" id="invoiceRecurringStartDateHelpMe"></span> </div>
                                                </div>
                                                <?php
                                                if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {

                                                    if (isset($invoiceRecurringArray[0]['invoiceRecurringEndDate'])) {
                                                        $valueArray = $invoiceRecurringArray[0]['invoiceRecurringEndDate'];
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
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceRecurringEndDateForm">
                                                    <label for="invoiceRecurringEndDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['invoiceRecurringEndDateLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="invoiceRecurringEndDate"
                                                                   id="invoiceRecurringEndDate" value="<?php
                                                                   if (isset($value)) {
                                                                       echo $value;
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                 id="invoiceRecurringEndDateImage"></span> </div>
                                                        <span class="help-block" id="invoiceRecurringEndDateHelpMe"></span> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceRecurringAmountForm">
                                                    <label for="invoiceRecurringAmount" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                            <?php
                                                            echo ucfirst(
                                                                    $leafTranslation['invoiceRecurringAmountLabel']
                                                            );
                                                            ?>
                                                        </strong></label>
                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="invoiceRecurringAmount"
                                                                   id="invoiceRecurringAmount" value="<?php
                                                                   if (isset($invoiceRecurringArray) && is_array($invoiceRecurringArray)) {
                                                                       if (isset($invoiceRecurringArray[0]['invoiceRecurringAmount'])) {
                                                                           echo htmlentities($invoiceRecurringArray[0]['invoiceRecurringAmount']);
                                                                       }
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                        <span class="help-block" id="invoiceRecurringAmountHelpMe"></span> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer" align="center">
                                        <div class="btn-group" align="center">
                                            <div class="btn-group" align="left"> <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> <a id="newRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                                                                                                                                  class="btn dropdown-toggle btn-success disabled"><span class="caret"></span></a>
                                                <ul class="dropdown-menu" style="text-align:left">
                                                    <li> <a id="newRecordButton3" href="javascript:void(0)"><i
                                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?> </a> </li>
                                                    <li> <a id="newRecordButton4" href="javascript:void(0)"><i
                                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?> </a></li>
                          <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                              ?></a></li>-->
                          <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                             ?></a></li>-->
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
                          <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                           ?></a></li> -->
                                                    <li> <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?> </a> </li>
                                                </ul>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"> <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                                            </div>
                                            <div class="btn-group"> <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                                                       onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                                                       echo $invoiceRecurring->getControllerPath();
                                                                       ?>', '<?php
                                                                       echo $invoiceRecurring->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </a> </div>

                                            <div class="btn-group"> <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                                                       echo $invoiceRecurring->getViewPath();
                                                                       ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </a> </div>
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
                            <div class="modal hide" id="deleteDetailPreview" tabindex="-1">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="invoiceRecurringDetailIdPreview" id="invoiceRecurringDetailIdPreview">
                                    <div class="form-group" id="chartOfAccountIdDiv">
                                        <label for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                        <input type="text" class="form-control" name="chartOfAccountIdPreview"
                                               id="chartOfAccountIdPreview">
                                    </div>
                                    <div class="form-group" id="countryIdDiv">
                                        <label for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                        <input type="text" class="form-control" name="countryIdPreview" id="countryIdPreview">
                                    </div>
                                    <div class="form-group" id="transactionTypeIdDiv">
                                        <label for="transactionTypeIdPreview"><?php echo $leafTranslation['transactionTypeIdLabel']; ?></label>
                                        <input type="text" class="form-control" name="transactionTypeIdPreview"
                                               id="transactionTypeIdPreview">
                                    </div>
                                    <div class="form-group" id="documentNumberDiv">
                                        <label for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                        <input type="text" class="form-control" name="documentNumberPreview"
                                               id="documentNumberPreview">
                                    </div>
                                    <div class="form-group" id="journalNumberDiv">
                                        <label for="journalNumberPreview"><?php echo $leafTranslation['journalNumberLabel']; ?></label>
                                        <input type="text" class="form-control" name="journalNumberPreview" id="journalNumberPreview">
                                    </div>
                                    <div class="form-group" id="invoiceRecurringDetailAmountDiv">
                                        <label
                                            for="invoiceRecurringDetailAmountPreview"><?php echo $leafTranslation['invoiceRecurringDetailAmountLabel']; ?></label>
                                        <input type="text" class="form-control" name="invoiceRecurringDetailAmountPreview"
                                               id="invoiceRecurringDetailAmountPreview">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceRecurringDetail->getControllerPath();
                                    ?>', '<?php
                                    echo $invoiceRecurringDetail->getViewPath();
                                    ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  onclick="showMeModal('deleteDetailPreview', 0);" class="btn btn-default"
                                            data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                        <thead>
                                            <tr>
                                                <th width="25px" align="center"> <div align="center">#</div>
                                        </th>
                                        <th width="50px"><?php echo ucfirst($t['actionTextLabel']); ?></th>
                                        <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                        <th><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></th>
                                        <th><?php echo ucfirst($leafTranslation['invoiceRecurringDetailAmountLabel']); ?></th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $disabledDetail = null;
                                            if (isset($_POST['invoiceRecurringId']) && (strlen($_POST['invoiceRecurringId']) > 0)) {
                                                $disabledDetail = null;
                                            } else {
                                                $disabledDetail = "disabled";
                                            }
                                            ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td valign="middle" align="center"><div align="center"> <button class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>"
                                                                                                            onclick="showFormCreateDetail('<?php
                                                                                                            echo $leafId;
                                                                                                            ;
                                                                                                            ?>', '<?php
                                                                                                            echo $invoiceRecurringDetail->getControllerPath();
                                                                                                            ?>', '<?php echo $securityToken; ?>');"><i class="glyphicon glyphicon-plus  glyphicon-white"></i></button>
                                                    <div id="miniInfoPanel9999"></div>
                                                </div></td>
                                            <td valign="top"><div class="form-group" id="chartOfAccountId9999Detail">
                                                    <select name="chartOfAccountId[]" id="chartOfAccountId9999" class="chzn-select form-control"
                                                            onChange="removeMeErrorDetail('chartOfAccountId9999');"  <?php echo $disabledDetail; ?>>
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($chartOfAccountArray)) {
                                                            $totalRecord = intval(count($chartOfAccountArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>"><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                                    <span class="help-block" id="chartOfAccountId9999HelpMe"></span></div></td>
                                            <td valign="top"><div class="form-group" id="countryId9999Detail">
                                                    <select name="countryId[]" id="countryId9999" class="chzn-select form-control"
                                                            onChange="removeMeErrorDetail('countryId9999');"  <?php echo $disabledDetail; ?>>
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($countryArray)) {
                                                            $totalRecord = intval(count($countryArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $countryArray[$i]['countryId']; ?>"><?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                                    <span class="help-block" id="countryId9999HelpMe"></span></div></td>
                                            <td valign="top"><div class="form-group" id="transactionTypeId9999Detail">
                                                    <select name="transactionTypeId[]" id="transactionTypeId9999" class="chzn-select form-control"
                                                            onChange="removeMeErrorDetail('transactionTypeId9999');"  <?php echo $disabledDetail; ?>>
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($transactionTypeArray)) {
                                                            $totalRecord = intval(count($transactionTypeArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $transactionTypeArray[$i]['transactionTypeId']; ?>"><?php echo $transactionTypeArray[$i]['transactionTypeDescription']; ?></option>
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
                                                    <span class="help-block" id="transactionTypeId9999HelpMe"></span></div></td>
                                            <td valign="top"><div class="form-group" id="documentNumber9999Detail">
                                                    <input class="col-xs-10 col-sm-10 col-md-10" <?php echo $disabledDetail; ?> type="text"
                                                           name="documentNumber[]"
                                                           id="documentNumber9999" onblur="removeMeErrorDetail('documentNumber9999');"
                                                           onkeyup="removeMeErrorDetail('documentNumber9999');">
                                                    <span class="help-block"
                                                          id="documentNumber9999HelpMe"></span> </div></td>
                                            <td valign="top"><div class="form-group" id="invoiceRecurringDetailAmount9999Detail">
                                                    <input class="col-xs-10 col-sm-10 col-md-10"   <?php echo $disabledDetail; ?> type="text"
                                                           name="invoiceRecurringDetailAmount[]" id="invoiceRecurringDetailAmount9999"
                                                           onblur="removeMeErrorDetail('invoiceRecurringDetailAmount9999');"
                                                           onkeyup="removeMeErrorDetail('invoiceRecurringDetailAmount9999');">
                                                    <span class="help-block"
                                                          id="invoiceRecurringDetailAmount9999HelpMe"></span> </div></td>
                                        </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            if ($_POST['invoiceRecurringId']) {
                                                if (is_array($invoiceRecurringDetailArray)) {
                                                    $totalRecordDetail = intval(count($invoiceRecurringDetailArray));
                                                    if ($totalRecordDetail > 0) {
                                                        $counter = 0;
                                                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                            $counter++;
                                                            ?>
                                                            <tr id="<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>">
                                                                <td><div align="center"><?php echo($counter + $offset); ?>.</div></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <input type="hidden" name="invoiceRecurringDetailId[]"
                                                                               id="invoiceRecurringDetailId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                               value="<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>">
                                                                        <input type="hidden" name="invoiceRecurringId[]"
                                                                               id="invoiceRecurringId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                               value="<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringId']; ?>">
                                                                        <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                onclick="showFormUpdateDetail(<?php echo $leafId; ?>, '<?php
                                                                                echo $invoiceRecurringDetail->getControllerPath();
                                                                                ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                echo intval(
                                                                                        $invoiceRecurringDetailArray [$j]['invoiceRecurringDetailId']
                                                                                );
                                                                                ?>');"> <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                onclick="showModalDeleteDetail('<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>');">
                                                                            <i class="glyphicon glyphicon-trash glyphicon-white"></i>
                                                                        </button>
                                                                        <div
                                                                            id="miniInfoPanel<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"></div>
                                                                    </div>

                                                                    <input type="hidden" name="invoiceRecurringId[]"
                                                                           id="invoiceRecurringId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                           value="<?php
                                                                           if (isset($invoiceRecurringDetailArray) && is_array($invoiceRecurringDetailArray)) {
                                                                               echo $invoiceRecurringDetailArray[$j]['invoiceRecurringId'];
                                                                           }
                                                                           ?>">
                                                                </td>
                                                                <td><div class="form-group"
                                                                         id="chartOfAccountId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>Detail">
                                                                        <select name="chartOfAccountId[]"
                                                                                id="chartOfAccountId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                                class="chzn-select form-control"
                                                                                onChange="removeMeErrorDetail('chartOfAccountId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>');">
                                                                            <option value=""></option>
                                                                            <?php
                                                                            if (is_array($chartOfAccountArray)) {
                                                                                $totalRecord = intval(count($chartOfAccountArray));
                                                                                if ($totalRecord > 0) {
                                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                                        if ($invoiceRecurringDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                                            $selected = "selected";
                                                                                        } else {
                                                                                            $selected = null;
                                                                                        }
                                                                                        ?>
                                                                                        <option
                                                                                            value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                                                    </div><input type="hidden" name="countryId[]"
                                                                                 id="countryId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                                 value="<?php
                                                                                 if (isset($invoiceRecurringDetailArray) && is_array($invoiceRecurringDetailArray)) {
                                                                                     echo $invoiceRecurringDetailArray[$j]['countryId'];
                                                                                 }
                                                                                 ?>">
                                                                    <input type="hidden" name="transactionTypeId[]"
                                                                           id="transactionTypeId<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                           value="<?php
                                                                           if (isset($invoiceRecurringDetailArray) && is_array($invoiceRecurringDetailArray)) {
                                                                               echo $invoiceRecurringDetailArray[$j]['transactionTypeId'];
                                                                           }
                                                                           ?>"></td>

                                                                <td><input class="form-control" type="text" name="documentNumber[]"
                                                                           id="documentNumber<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                           value="<?php
                                                                           if (isset($invoiceRecurringDetailArray) && is_array($invoiceRecurringDetailArray)) {
                                                                               echo $invoiceRecurringDetailArray[$j]['documentNumber'];
                                                                           }
                                                                           ?>"></td>
                                                                <td><input class="form-control" style="text-align:right" type="text"
                                                                           name="invoiceRecurringDetailAmount[]"
                                                                           id="invoiceRecurringDetailAmount<?php echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailId']; ?>"
                                                                           value="<?php
                                                                           if (isset($invoiceRecurringDetailArray) && is_array($invoiceRecurringDetailArray)) {
                                                                               echo $invoiceRecurringDetailArray[$j]['invoiceRecurringDetailAmount'];
                                                                           }
                                                                           ?>"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="6" valign="top" align="center"><?php
                                                                $invoiceRecurringDetail->exceptionMessage(
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
                                                            $invoiceRecurringDetail->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                            </div></div></div>
                </form>
                <script type="text/javascript">
                    $(document).ready(function() {
                        window.scrollTo(0, 0);
                        $(".chzn-select").chosen({search_contains: true});
                        $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                        validateMeNumeric('invoiceRecurringId');
                        validateMeNumeric('invoiceRecurringTypeId');

                        validateMeAlphaNumeric('referenceNumber');
                        validateMeAlphaNumeric('journalRecurringTitle');
                        $('#invoiceRecurringDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        $('#invoiceRecurringStartDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        $('#invoiceRecurringEndDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        validateMeCurrency('invoiceRecurringAmount');
                        validateMeNumericRange('invoiceRecurringDetailId');
                        validateMeNumericRange('invoiceRecurringId');
                        validateMeNumericRange('chartOfAccountId');
                        validateMeNumericRange('countryId');
                        validateMeNumericRange('transactionTypeId');
                        validateMeAlphaNumericRange('documentNumber');
                        validateMeAlphaNumericRange('journalNumber');
                        validateMeCurrencyRange('invoiceRecurringDetailAmount');
    <?php if ($_POST['method'] == "new") { ?>

                            $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>


                                $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');


                                $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                                $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                                $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                                $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['invoiceRecurringId']) {
            ?>


                                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');


                                $('#newRecordButton3').attr('onClick', '');
                                $('#newRecordButton4').attr('onClick', '');
                                $('#newRecordButton5').attr('onClick', '');
                                $('#newRecordButton6').attr('onClick', '');
                                $('#newRecordButton7').attr('onClick', '');


            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');


                                    $('#updateRecordButton3').attr('onClick', '');
                                    $('#updateRecordButton4').attr('onClick', '');
                                    $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>

                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoiceRecurring->getControllerPath(); ?>','<?php echo $invoiceRecurring->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
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
            <script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoiceRecurring.js"></script>
            <hr>
            <footer>
                <p>IDCMS 2012/2013</p>
            </footer>