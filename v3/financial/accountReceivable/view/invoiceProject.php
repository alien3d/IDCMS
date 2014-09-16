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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceProjectController.php");
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

$translator->setCurrentTable('invoiceProject');

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
$invoiceProjectArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoiceProject = new \Core\Financial\AccountReceivable\InvoiceProject\Controller\InvoiceProjectClass();
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
            $invoiceProject->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoiceProject->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoiceProject->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoiceProject->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoiceProject->setStartDay($start[2]);
            $invoiceProject->setStartMonth($start[1]);
            $invoiceProject->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoiceProject->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoiceProject->setEndDay($start[2]);
            $invoiceProject->setEndMonth($start[1]);
            $invoiceProject->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoiceProject->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoiceProject->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoiceProject->setServiceOutput('html');
        $invoiceProject->setLeafId($leafId);
        $invoiceProject->execute();
        if ($_POST['method'] == 'read') {
            $invoiceProject->setStart($offset);
            $invoiceProject->setLimit($limit); // normal system don't like paging..
            $invoiceProject->setPageOutput('html');
            $invoiceProjectArray = $invoiceProject->read();
            if (isset($invoiceProjectArray [0]['firstRecord'])) {
                $firstRecord = $invoiceProjectArray [0]['firstRecord'];
            }
            if (isset($invoiceProjectArray [0]['nextRecord'])) {
                $nextRecord = $invoiceProjectArray [0]['nextRecord'];
            }
            if (isset($invoiceProjectArray [0]['previousRecord'])) {
                $previousRecord = $invoiceProjectArray [0]['previousRecord'];
            }
            if (isset($invoiceProjectArray [0]['lastRecord'])) {
                $lastRecord = $invoiceProjectArray [0]['lastRecord'];
                $endRecord = $invoiceProjectArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoiceProject->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceProjectArray [0]['total'])) {
                $total = $invoiceProjectArray [0]['total'];
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
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');"> A </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');"> B </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');"> C </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');"> D </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');"> E </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');"> F </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');"> G </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');"> H </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');"> I </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');"> J </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');"> K </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');"> L </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');"> M </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');"> N </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');"> O </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');"> P </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');"> Q </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');"> R </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');"> S </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');"> T </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');"> U </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');"> V </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');"> W </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');"> X </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');"> Y </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $invoiceProject->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');"> Z </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button"> <span class="caret"></span> </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceProject->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a> </li>
                                <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceProject->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a> </li>
                                <li> <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceProject->getControllerPath();
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
                                            echo $invoiceProject->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"> <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                    if (isset($_POST['query'])) {
                                        echo $_POST['query'];
                                    }
                                    ?>"><span class="input-group-addon"><img src="./images/icons/magnifier.png" id="searchTextDateImage"></span></div><br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $invoiceProject->getViewPath(); ?>', '<?php echo $securityToken; ?>');" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceProject->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                                <br>
                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center"><img src="./images/icons/calendar-select-days-span.png"
                                                                alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceProject->getViewPath();
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
                                                             echo $invoiceProject->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceProject->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                                            onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceProject->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceProject->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-week.png"
                                                                alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceProject->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceProject->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Month <?php echo $previousMonth; ?>"
                                                             onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceProject->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-month.png"
                                                                alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceProject->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Month <?php echo $nextMonth; ?>"
                                                            onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceProject->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Year <?php echo $previousYear; ?>"
                                                             onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                             echo $invoiceProject->getViewPath();
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $invoiceProject->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Year <?php echo $nextYear; ?>"
                                                            onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                            echo $invoiceProject->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
                                    </tr>
                                </table><div class="input-group"><input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="startDateImage"></span></div><br>
                                <div class="input-group"><input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" onClick="topPage(150)" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="endDateImage"></span></div><br>
                                <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $invoiceProject->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceProject->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                        </div>
                    </div>
                </div>
                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                    <div class="modal fade" id="deletePreview">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="invoiceProjectIdPreview" id="invoiceProjectIdPreview">
                                        <div class="form-group" id="invoiceProjectTitleDiv">
                                            <label for="invoiceProjectTitlePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectTitleLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="invoiceProjectTitlePreview"
                                                       id="invoiceProjectTitlePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label for="documentNumberPreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="documentNumberPreview"
                                                       id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="referenceNumberDiv">
                                            <label for="referenceNumberPreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview"
                                                       id="referenceNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectStartDateDiv">
                                            <label for="invoiceProjectStartDatePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectStartDateLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="invoiceProjectStartDatePreview" id="invoiceProjectStartDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectEndDateDiv">
                                            <label for="invoiceProjectEndDatePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectEndDateLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="invoiceProjectEndDatePreview"
                                                       id="invoiceProjectEndDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectCurrentStageDiv">
                                            <label for="invoiceProjectCurrentStagePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectCurrentStageLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="invoiceProjectCurrentStagePreview"
                                                       id="invoiceProjectCurrentStagePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectTotalStageDiv">
                                            <label for="invoiceProjectTotalStagePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectTotalStageLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="invoiceProjectTotalStagePreview" id="invoiceProjectTotalStagePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectValueDiv">
                                            <label for="invoiceProjectValuePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectValueLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="invoiceProjectValuePreview"
                                                       id="invoiceProjectValuePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectRetentionValueDiv">
                                            <label for="invoiceProjectRetentionValuePreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectRetentionValueLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="invoiceProjectRetentionValuePreview"
                                                       id="invoiceProjectRetentionValuePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectRetentionPercentDiv">
                                            <label for="invoiceProjectRetentionPercentPreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectRetentionPercentLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="invoiceProjectRetentionPercentPreview"
                                                       id="invoiceProjectRetentionPercentPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectDescriptionDiv">
                                            <label for="invoiceProjectDescriptionPreview"
                                                   class="col-md-4 "><?php echo $leafTranslation['invoiceProjectDescriptionLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="invoiceProjectDescriptionPreview"
                                                       id="invoiceProjectDescriptionPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                    echo $invoiceProject->getControllerPath();
                                    ?>', '<?php
                                    echo $invoiceProject->getViewPath();
                                    ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button onclick="showMeModal('deletePreview', 0);"
                                            class="btn btn-info"><?php echo $t['closeButtonLabel']; ?></button>
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
                                    <th width="110px"> <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['invoiceProjectTitleLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['invoiceProjectStartDateLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['invoiceProjectEndDateLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['invoiceProjectCurrentStageLabel']); ?></th>
                                    <th width="100px"> <div align="center"><?php echo ucwords($leafTranslation['invoiceProjectValueLabel']); ?></div>
                                    </th>
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
                                        $totalInvoice = 0;
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($invoiceProjectArray)) {
                                                $totalRecord = intval(count($invoiceProjectArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                    <tr <?php
                                                    if ($invoiceProjectArray[$i]['isDelete'] == 1) {
                                                        echo "class=\"danger\"";
                                                    } else {
                                                        if ($invoiceProjectArray[$i]['isDraft'] == 1) {
                                                            echo "class=\"warning\"";
                                                        }
                                                    }
                                                    ?>>
                                                        <td align="center"><div align="center"><?php echo($counter + $offset); ?></div></td>
                                                        <td align="center">
                                                            <?php if ($invoiceProjectArray [$i]['invoiceProjectCode'] != 'UNBL') { ?>
                                                                <div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $invoiceProject->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $invoiceProject->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"> <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onclick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectTitle']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['documentNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['referenceNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectStartDate']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectEndDate']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectCurrentStage']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectTotalStage']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectValue']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectRetentionValue']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectRetentionPercent']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $invoiceProjectArray [$i]['invoiceProjectDescription']
                                                                            );
                                                                            ?>');"> <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div><?php } ?></td>
                                                        <td><div class="pull-left">
                                                                <?php
                                                                if (isset($invoiceProjectArray[$i]['documentNumber'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceProjectArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceProjectArray[$i]['documentNumber']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($invoiceProjectArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceProjectArray[$i]['documentNumber']
                                                                                    );
                                                                                } else {
                                                                                    echo $invoiceProjectArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['documentNumber'];
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo $invoiceProjectArray[$i]['documentNumber'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <td><div class="pull-left">
                                                                <?php
                                                                if (isset($invoiceProjectArray[$i]['invoiceProjectTitle'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceProjectArray[$i]['invoiceProjectTitle']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceProjectArray[$i]['invoiceProjectTitle']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['invoiceProjectTitle'];
                                                                            }
                                                                        } else {
                                                                            if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($invoiceProjectArray[$i]['invoiceProjectTitle']), strtolower($_POST['character'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceProjectArray[$i]['invoiceProjectTitle']
                                                                                    );
                                                                                } else {
                                                                                    echo $invoiceProjectArray[$i]['invoiceProjectTitle'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['invoiceProjectTitle'];
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo $invoiceProjectArray[$i]['invoiceProjectTitle'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <?php
                                                        if (isset($invoiceProjectArray[$i]['invoiceProjectStartDate'])) {
                                                            $valueArray = $invoiceProjectArray[$i]['invoiceProjectStartDate'];
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
                                                        if (isset($invoiceProjectArray[$i]['invoiceProjectEndDate'])) {
                                                            $valueArray = $invoiceProjectArray[$i]['invoiceProjectEndDate'];
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
                                                        <td><?php echo $value; ?></td>
                                                        <td><div align="center">
                                                                <?php
                                                                if (isset($invoiceProjectArray[$i]['invoiceProjectCurrentStage'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceProjectArray[$i]['invoiceProjectCurrentStage']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceProjectArray[$i]['invoiceProjectCurrentStage']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['invoiceProjectCurrentStage'] . "/" . $invoiceProjectArray[$i]['invoiceProjectTotalStage'];
                                                                            }
                                                                        } else {
                                                                            if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($invoiceProjectArray[$i]['invoiceProjectCurrentStage']), strtolower($_POST['character'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceProjectArray[$i]['invoiceProjectCurrentStage']
                                                                                    );
                                                                                } else {
                                                                                    echo $invoiceProjectArray[$i]['invoiceProjectCurrentStage'] . "/" . $invoiceProjectArray[$i]['invoiceProjectTotalStage'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['invoiceProjectCurrentStage'] . "/" . $invoiceProjectArray[$i]['invoiceProjectTotalStage'];
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo $invoiceProjectArray[$i]['invoiceProjectCurrentStage'] . "/" . $invoiceProjectArray[$i]['invoiceProjectTotalStage'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <?php
                                                        $d = $invoiceProjectArray[$i]['invoiceProjectValue'];
                                                        $totalInvoice += $d;
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($invoiceProjectArray[$i]['invoiceProjectValue']);
                                                            } else {
                                                                $d = number_format($d) . " You can assign Currency Format ";
                                                            }
                                                        } else {
                                                            $d = number_format($d);
                                                        }
                                                        ?>
                                                        <td><div class="pull-right"><?php echo $d; ?></div></td>
                                                        <td align="center"><div align="center">
                                                                <?php
                                                                if (isset($invoiceProjectArray[$i]['executeBy'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceProjectArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceProjectArray[$i]['staffName']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(
                                                                                                $invoiceProjectArray[$i]['staffName'], $_POST['character']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceProjectArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $invoiceProjectArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceProjectArray[$i]['staffName'];
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo $invoiceProjectArray[$i]['staffName'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <?php
                                                        if (isset($invoiceProjectArray[$i]['executeTime'])) {
                                                            $valueArray = $invoiceProjectArray[$i]['executeTime'];
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
                                                        if ($invoiceProjectArray[$i]['isDelete']) {
                                                            $checked = "checked";
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        ?>
                                                        <td><?php if ($invoiceProjectArray [$i]['invoiceProjectCode'] != 'UNBL') { ?><input style="display:none;" type="checkbox" name="invoiceProjectId[]"
                                                                                                                                                value="<?php echo $invoiceProjectArray[$i]['invoiceProjectId']; ?>">
                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                               value="<?php echo $invoiceProjectArray[$i]['isDelete']; ?>"><?php } ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="12" valign="top" align="center"><?php
                                                        $invoiceProject->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="12" valign="top" align="center"><?php
                                                    $invoiceProject->exceptionMessage(
                                                            $t['recordNotFoundLabel']
                                                    );
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="12" valign="top" align="center"><?php
                                                $invoiceProject->exceptionMessage(
                                                        $t['loadFailureLabel']
                                                );
                                                ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tr class="success">
                                    <td colspan="7"><div class="pull-right"><?php echo $t['totalTextLabel']; ?></div></td>
                                    <td><div class="pull-right"><strong>
                                                <?php
                                                if (class_exists('NumberFormatter')) {
                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                    $d = $a->format($totalInvoice);
                                                } else {
                                                    $d = number_format($totalInvoice) . " You can assign Currency Format ";
                                                }
                                                echo $d;
                                                ?>
                                            </strong> </div></td>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
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
                                    echo $invoiceProject->getControllerPath();
                                    ?>', '<?php echo $invoiceProject->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
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
                                <div class="btn-group"> <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                                           onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                                           echo $invoiceProject->getControllerPath();
                                                           ?>', '<?php
                                                           echo $invoiceProject->getViewPath();
                                                           ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </a> </div>
                                <div class="btn-group"> <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                                           onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                                           echo $invoiceProject->getControllerPath();
                                                           ?>', '<?php
                                                           echo $invoiceProject->getViewPath();
                                                           ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </a> </div>
                                <div class="btn-group"> <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                                           onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                                           echo $invoiceProject->getControllerPath();
                                                           ?>', '<?php
                                                           echo $invoiceProject->getViewPath();
                                                           ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </a> </div>
                                <div class="btn-group"> <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                                           onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                                           echo $invoiceProject->getControllerPath();
                                                           ?>', '<?php
                                                           echo $invoiceProject->getViewPath();
                                                           ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </a> </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="invoiceProjectId" id="invoiceProjectId" value="<?php
                            if (isset($_POST['invoiceProjectId'])) {
                                echo $_POST['invoiceProjectId'];
                            }
                            ?>">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
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
                                                <input type="text" class="form-control" name="documentNumber" id="documentNumber" disabled class="<?php
                                                if (!isset($_POST['invoiceProjectId'])) {
                                                    echo "disabled";
                                                }
                                                ?>" value="<?php
                                                       if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {

                                                           if (isset($invoiceProjectArray[0]['documentNumber'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['documentNumber']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/document-number.png"></span> </div>
                                            <span class="help-block" id="documentNumberHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="referenceNumberForm">
                                        <label for="referenceNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['referenceNumberLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="referenceNumber" id="referenceNumber"
                                                       value="<?php
                                                       if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {
                                                           if (isset($invoiceProjectArray[0]['referenceNumber'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['referenceNumber']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/document-number.png"></span> </div>
                                            <span class="help-block" id="referenceNumberHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?php
                                    if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {
                                        if (isset($invoiceProjectArray[0]['invoiceProjectStartDate'])) {
                                            $valueArray = $invoiceProjectArray[0]['invoiceProjectStartDate'];
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectStartDateForm">
                                        <label for="invoiceProjectStartDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectStartDateLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectStartDate"
                                                       id="invoiceProjectStartDate" value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                     id="invoiceProjectStartDateImage"></span> </div>
                                            <span class="help-block" id="invoiceProjectStartDateHelpMe"></span> </div>
                                    </div>
                                    <?php
                                    if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {
                                        if (isset($invoiceProjectArray[0]['invoiceProjectEndDate'])) {
                                            $valueArray = $invoiceProjectArray[0]['invoiceProjectEndDate'];
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectEndDateForm">
                                        <label for="invoiceProjectEndDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectEndDateLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectEndDate"
                                                       id="invoiceProjectEndDate" value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                     id="invoiceProjectEndDateImage"></span> </div>
                                            <span class="help-block" id="invoiceProjectEndDateHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectCurrentStageForm">
                                        <label for="invoiceProjectCurrentStage" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectCurrentStageLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectCurrentStage"
                                                       id="invoiceProjectCurrentStage" value="<?php
                                                       if (isset($invoiceProjectArray[0]['invoiceProjectCurrentStage'])) {
                                                           if (isset($invoiceProjectArray[0]['invoiceProjectCurrentStage'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['invoiceProjectCurrentStage']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="invoiceProjectCurrentStageHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectTotalStageForm">
                                        <label for="invoiceProjectTotalStage" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectTotalStageLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectTotalStage"
                                                       id="invoiceProjectTotalStage" value="<?php
                                                       if (isset($invoiceProjectArray[0]['invoiceProjectTotalStage'])) {
                                                           if (isset($invoiceProjectArray[0]['invoiceProjectTotalStage'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['invoiceProjectTotalStage']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="invoiceProjectTotalStageHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectValueForm">
                                        <label for="invoiceProjectValue" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectValueLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectValue" id="invoiceProjectValue"
                                                       value="<?php
                                                       if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {
                                                           if (isset($invoiceProjectArray[0]['invoiceProjectValue'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['invoiceProjectValue']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceProjectValueHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectRetentionPercentForm">
                                        <label for="invoiceProjectRetentionPercent" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectRetentionPercentLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectRetentionPercent"
                                                       id="invoiceProjectRetentionPercent" value="<?php
                                                       if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {
                                                           if (isset($invoiceProjectArray[0]['invoiceProjectRetentionPercent'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['invoiceProjectRetentionPercent']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/edit-percent.png"></span></div>
                                            <span class="help-block" id="invoiceProjectRetentionPercentHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="invoiceProjectRetentionValueForm">
                                        <label for="invoiceProjectRetentionValue" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                <?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectRetentionValueLabel']
                                                );
                                                ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="invoiceProjectRetentionValue"
                                                       id="invoiceProjectRetentionValue" value="<?php
                                                       if (isset($invoiceProjectArray) && is_array($invoiceProjectArray)) {
                                                           if (isset($invoiceProjectArray[0]['invoiceProjectRetentionValue'])) {
                                                               echo htmlentities($invoiceProjectArray[0]['invoiceProjectRetentionValue']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceProjectRetentionValueHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="invoiceProjectDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea class="form-control" rows="5" name="invoiceProjectDescription"
                                                      id="invoiceProjectDescription"><?php
                                                          if (isset($invoiceProjectArray[0]['invoiceProjectDescription'])) {
                                                              echo htmlentities($invoiceProjectArray[0]['invoiceProjectDescription']);
                                                          }
                                                          ?></textarea>
                                            <span class="help-block" id="invoiceProjectDescriptionHelpMe"></span> </div>
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
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?> </a></li>
                                    <li> <a id="newRecordButton4" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?> </a></li>
                <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                          ?></a></li>-->
                <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                          ?></a></li>-->
                                    <li> <a id="newRecordButton7" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li>
                                </ul>
                            </div>
                            <div class="btn-group" align="left"> <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                        class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> <a id="updateRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                                                                                                                     class="btn dropdown-toggle btn-info disabled"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li> <a id="updateRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?> </a></li>
                <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                            ?></a></li> -->
                                    <li> <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?> </a> </li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"> <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group"> <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                                       onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                                       echo $invoiceProject->getControllerPath();
                                                       ?>', '<?php
                                                       echo $invoiceProject->getViewPath();
                                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </a> </div>

                            <div class="btn-group"> <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                                       echo $invoiceProject->getViewPath();
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
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('invoiceProjectId');

            validateMeAlphaNumeric('referenceNumber');
            $('#invoiceProjectStartDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#invoiceProjectEndDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeNumeric('invoiceProjectCurrentStage');
            validateMeNumeric('invoiceProjectTotalStage');
            validateMeCurrency('invoiceProjectValue');
            validateMeCurrency('invoiceProjectRetentionValue');
            validateMeCurrency('invoiceProjectRetentionPercent');
    <?php if ($_POST['method'] == "new") { ?>

                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>


                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');


                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['invoiceProjectId']) {
            ?>


                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');


                    $('#newRecordButton3').attr('onClick', '');
                    $('#newRecordButton4').attr('onClick', '');
                    $('#newRecordButton5').attr('onClick', '');
                    $('#newRecordButton6').attr('onClick', '');
                    $('#newRecordButton7').attr('onClick', '');


            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');


                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>

                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoiceProject->getControllerPath(); ?>','<?php echo $invoiceProject->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
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
<script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoiceProject.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>