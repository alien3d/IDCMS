<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceFollowUpController.php");
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

$translator->setCurrentTable('invoiceFollowUp');
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
$invoiceFollowUpArray = array();
$invoiceArray = array();
$followUpArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoiceFollowUp = new \Core\Financial\AccountReceivable\InvoiceFollowUp\Controller\InvoiceFollowUpClass();
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
            $invoiceFollowUp->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoiceFollowUp->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoiceFollowUp->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoiceFollowUp->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoiceFollowUp->setStartDay($start[2]);
            $invoiceFollowUp->setStartMonth($start[1]);
            $invoiceFollowUp->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoiceFollowUp->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoiceFollowUp->setEndDay($start[2]);
            $invoiceFollowUp->setEndMonth($start[1]);
            $invoiceFollowUp->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoiceFollowUp->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoiceFollowUp->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoiceFollowUp->setServiceOutput('html');
        $invoiceFollowUp->setLeafId($leafId);
        $invoiceFollowUp->execute();
        $invoiceArray = $invoiceFollowUp->getInvoice();
        $followUpArray = $invoiceFollowUp->getFollowUp();
        if ($_POST['method'] == 'read') {
            $invoiceFollowUp->setStart($offset);
            $invoiceFollowUp->setLimit($limit); // normal system don't like paging..
            $invoiceFollowUp->setPageOutput('html');
            $invoiceFollowUpArray = $invoiceFollowUp->read();
            if (isset($invoiceFollowUpArray [0]['firstRecord'])) {
                $firstRecord = $invoiceFollowUpArray [0]['firstRecord'];
            }
            if (isset($invoiceFollowUpArray [0]['nextRecord'])) {
                $nextRecord = $invoiceFollowUpArray [0]['nextRecord'];
            }
            if (isset($invoiceFollowUpArray [0]['previousRecord'])) {
                $previousRecord = $invoiceFollowUpArray [0]['previousRecord'];
            }
            if (isset($invoiceFollowUpArray [0]['lastRecord'])) {
                $lastRecord = $invoiceFollowUpArray [0]['lastRecord'];
                $endRecord = $invoiceFollowUpArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoiceFollowUp->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceFollowUpArray [0]['total'])) {
                $total = $invoiceFollowUpArray [0]['total'];
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
                <div align="left" class="btn-group col-xs-10 col-sm-10 col-md-10 pull-left">
                    <button title="A" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'A')">A </button>
                    <button title="B" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'B')">B </button>
                    <button title="C" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'C')">C </button>
                    <button title="D" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'D')">D </button>
                    <button title="E" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'E')">E </button>
                    <button title="F" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'F')">F </button>
                    <button title="G" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'G')">G </button>
                    <button title="H" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'H')">H </button>
                    <button title="I" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'I')">I </button>
                    <button title="J" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'J')">J </button>
                    <button title="K" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'K')">K </button>
                    <button title="L" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'L')">L </button>
                    <button title="M" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'M')">M </button>
                    <button title="N" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'N')">N </button>
                    <button title="O" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'O')">O </button>
                    <button title="P" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'P')">P </button>
                    <button title="Q" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'Q')">Q </button>
                    <button title="R" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'R')">R </button>
                    <button title="S" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'S')">S </button>
                    <button title="T" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'T')">T </button>
                    <button title="U" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'U')">U </button>
                    <button title="V" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'V')">V </button>
                    <button title="W" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'W')">W </button>
                    <button title="X" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'X')">X </button>
                    <button title="Y" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'Y')">Y </button>
                    <button title="Z" class="btn btn-success" type="button" 
                            onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                            echo $invoiceFollowUp->getViewPath(
                            );
                            ?>', '<?php echo $securityToken; ?>', 'Z')">Z </button>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="right" class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button"> <span class="caret"></span> </button>
                            <ul class="dropdown-menu">
                                <li> <a href="javascript:void(0)"
                                        onclick="reportRequest('<?php echo $leafId; ?>', '<?php
                                        echo $invoiceFollowUp->getControllerPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', 'excel')"> <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007 </a> </li>
                                <li> <a href="javascript:void(0)"
                                        onclick="reportRequest('<?php echo $leafId; ?>', '<?php
                                        echo $invoiceFollowUp->getControllerPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', 'csv')"> <i class="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
                                <li> <a href="javascript:void(0)"
                                        onclick="reportRequest('<?php echo $leafId; ?>', '<?php
                                        echo $invoiceFollowUp->getControllerPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', 'html')"> <i class="pull-right glyphicon glyphicon-download"></i>Html </a> </li>
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
                                        onclick="showForm('<?php echo $leafId; ?>', '<?php
                                        echo $invoiceFollowUp->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>')"
                                        value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button>
                            </div>
                            <label for="queryWidget"></label>
                            <input type="text" name="queryWidget" id="queryWidget"
                                   class="form-control"
                                   value="<?php
                                   if (isset($_POST['query'])) {
                                       echo $_POST['query'];
                                   }
                                   ?>">
                            <br>
                            <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block"
                                    onclick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php
                                    echo $invoiceFollowUp->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>')"
                                    value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block"
                                    onclick="showGrid('<?php echo $leafId; ?>', '<?php
                                    echo $invoiceFollowUp->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)"
                                    value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            <br>

                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png"
                                                            alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip"
                                                          onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                          echo $invoiceFollowUp->getViewPath(
                                                          );
                                                          ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                                          echo date(
                                                                  'd-m-Y'
                                                          );
                                                          ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title="Previous Day <?php echo $previousDay; ?>"
                                                         onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                         echo $invoiceFollowUp->getViewPath(
                                                         );
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                            alt="<?php echo $t['day'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                          echo $invoiceFollowUp->getViewPath(
                                                          );
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                        title="Next Day <?php echo $nextDay; ?>"
                                                        onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                        echo $invoiceFollowUp->getViewPath(
                                                        );
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title="Previous Week<?php
                                                         echo $dateConvert->getCurrentWeekInfo(
                                                                 $dateRangeStart, 'previous'
                                                         );
                                                         ?>"
                                                         onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                         echo $invoiceFollowUp->getViewPath(
                                                         );
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png"
                                                            alt="<?php echo $t['week'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip"
                                                          title="<?php
                                                          echo $dateConvert->getCurrentWeekInfo(
                                                                  $dateRangeStart, 'current'
                                                          );
                                                          ?>"
                                                          onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                          echo $invoiceFollowUp->getViewPath(
                                                          );
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                        title="Next Week <?php
                                                        echo $dateConvert->getCurrentWeekInfo(
                                                                $dateRangeStart, 'next'
                                                        );
                                                        ?>"
                                                        onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                        echo $invoiceFollowUp->getViewPath(
                                                        );
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title="Previous Month <?php echo $previousMonth; ?>"
                                                         onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                         echo $invoiceFollowUp->getViewPath(
                                                         );
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-month.png"
                                                            alt="<?php echo $t['month'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                          echo $invoiceFollowUp->getViewPath(
                                                          );
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                        title="Next Month <?php echo $nextMonth; ?>"
                                                        onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                        echo $invoiceFollowUp->getViewPath(
                                                        );
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                         title="Previous Year <?php echo $previousYear; ?>"
                                                         onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                         echo $invoiceFollowUp->getViewPath(
                                                         );
                                                         ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)"
                                                          onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                          echo $invoiceFollowUp->getViewPath(
                                                          );
                                                          ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                        title="Next Year <?php echo $nextYear; ?>"
                                                        onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                        echo $invoiceFollowUp->getViewPath(
                                                        );
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
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
                                   echo $invoiceFollowUp->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchDate" id="clearSearchDate" value="<?php echo $t['clearButtonLabel']; ?>"
                                   class="btn btn-info btn-block" onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $invoiceFollowUp->getViewPath();
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
                                        <input type="hidden" name="invoiceFollowUpIdPreview" id="invoiceFollowUpIdPreview">
                                        <div class="form-group" id="invoiceIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                   for="invoiceIdPreview"><?php echo $leafTranslation['invoiceIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceIdPreview"
                                                       id="invoiceIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="followUpIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                   for="followUpIdPreview"><?php echo $leafTranslation['followUpIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="followUpIdPreview"
                                                       id="followUpIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                   for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="documentNumberPreview"
                                                       id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceFollowUpDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                   for="invoiceFollowUpDatePreview"><?php echo $leafTranslation['invoiceFollowUpDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceFollowUpDatePreview"
                                                       id="invoiceFollowUpDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceFollowUpDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                   for="invoiceFollowUpDescriptionPreview"><?php echo $leafTranslation['invoiceFollowUpDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceFollowUpDescriptionPreview"
                                                       id="invoiceFollowUpDescriptionPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php
                                            echo $invoiceFollowUp->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $invoiceFollowUp->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>')"
                                            value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0)"
                                            value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                <thead>
                                    <tr>
                                        <th width="25px" align="center"> <div align="center">#</div>
                                </th>
                                <th width="75px"> <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                </th>
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceIdLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['followUpIdLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceFollowUpDateLabel']); ?></th>
                                <th><?php echo ucwords($leafTranslation['invoiceFollowUpDescriptionLabel']); ?></th>
                                <th width="100px"> <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                </th>
                                <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                <th width="25px" align="center">
                                    <input type="checkbox" name="check_all"
                                           id="check_all" alt="Check Record"
                                           onclick="toggleChecked(this.checked)"></th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($invoiceFollowUpArray)) {
                                            $totalRecord = intval(count($invoiceFollowUpArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
                                                    <tr <?php
                                                    if ($invoiceFollowUpArray[$i]['isDelete'] == 1) {
                                                        echo "class=\"danger\"";
                                                    } else {
                                                        if ($invoiceFollowUpArray[$i]['isDraft'] == 1) {
                                                            echo "class=\"warning\"";
                                                        }
                                                    }
                                                    ?>>
                                                        <td valign="top" align="center"><div align="center"><?php echo($counter + $offset); ?>.</div></td>
                                                        <td valign="top" align="center"><div class="btn-group" align="center">
                                                                <button type="button"  class="btn btn-warning btn-sm" title="Edit"
                                                                        onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php
                                                                        echo $invoiceFollowUp->getControllerPath(
                                                                        );
                                                                        ?>', '<?php
                                                                        echo $invoiceFollowUp->getViewPath(
                                                                        );
                                                                        ?>', '<?php echo $securityToken; ?>', '<?php
                                                                        echo intval(
                                                                                $invoiceFollowUpArray [$i]['invoiceFollowUpId']
                                                                        );
                                                                        ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"
                                                                        ><i class="glyphicon glyphicon-edit glyphicon-white"></i> </button>
                                                                <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                        onclick="showModalDelete('<?php
                                                                        echo rawurlencode(
                                                                                $invoiceFollowUpArray [$i]['invoiceFollowUpId']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $invoiceFollowUpArray [$i]['invoiceDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $invoiceFollowUpArray [$i]['followUpDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $invoiceFollowUpArray [$i]['documentNumber']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $invoiceFollowUpArray [$i]['invoiceFollowUpDate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $invoiceFollowUpArray [$i]['invoiceFollowUpDescription']
                                                                        );
                                                                        ?>')" value="Delete"><i
                                                                        class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                            </div></td>
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceFollowUpArray[$i]['invoiceDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $invoiceFollowUpArray[$i]['invoiceDescription'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceFollowUpArray[$i]['invoiceDescription']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['invoiceDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $invoiceFollowUpArray[$i]['invoiceDescription'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceFollowUpArray[$i]['invoiceDescription']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['invoiceDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceFollowUpArray[$i]['invoiceDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceFollowUpArray[$i]['invoiceDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceFollowUpArray[$i]['followUpDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $invoiceFollowUpArray[$i]['followUpDescription'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceFollowUpArray[$i]['followUpDescription']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['followUpDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $invoiceFollowUpArray[$i]['followUpDescription'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceFollowUpArray[$i]['followUpDescription']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['followUpDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceFollowUpArray[$i]['followUpDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceFollowUpArray[$i]['followUpDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceFollowUpArray[$i]['documentNumber'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceFollowUpArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceFollowUpArray[$i]['documentNumber']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['documentNumber'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceFollowUpArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceFollowUpArray[$i]['documentNumber']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceFollowUpArray[$i]['documentNumber'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceFollowUpArray[$i]['documentNumber'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <?php
                                                        if (isset($invoiceFollowUpArray[$i]['invoiceFollowUpDate'])) {
                                                            $valueArray = $invoiceFollowUpArray[$i]['invoiceFollowUpDate'];
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
                                                        <td valign="top"><?php echo $value; ?></td>
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceFollowUpArray[$i]['invoiceFollowUpDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceFollowUpArray[$i]['invoiceFollowUpDescription']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceFollowUpArray[$i]['invoiceFollowUpDescription']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['invoiceFollowUpDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($invoiceFollowUpArray[$i]['invoiceFollowUpDescription']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceFollowUpArray[$i]['invoiceFollowUpDescription']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['invoiceFollowUpDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceFollowUpArray[$i]['invoiceFollowUpDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceFollowUpArray[$i]['invoiceFollowUpDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?></td>
                                                        <td valign="top" align="center"><div align="center">
                                                                <?php
                                                                if (isset($invoiceFollowUpArray[$i]['executeBy'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $invoiceFollowUpArray[$i]['staffName'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceFollowUpArray[$i]['staffName']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['staffName'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $invoiceFollowUpArray[$i]['staffName'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceFollowUpArray[$i]['staffName']
                                                                                );
                                                                            } else {
                                                                                echo $invoiceFollowUpArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceFollowUpArray[$i]['staffName'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceFollowUpArray[$i]['staffName'];
                                                                    }
                                                                    ?>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></div></td>
                                                        <?php
                                                        if (isset($invoiceFollowUpArray[$i]['executeTime'])) {
                                                            $valueArray = $invoiceFollowUpArray[$i]['executeTime'];
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
                                                        <td valign="top"><?php echo $value; ?></td>
                                                        <?php
                                                        if ($invoiceFollowUpArray[$i]['isDelete']) {
                                                            $checked = "checked";
                                                        } else {
                                                            $checked = NULL;
                                                        }
                                                        ?>
                                                        <td valign="top"><input class="form-control" style="display:none;" type="checkbox"
                                                                                name="invoiceFollowUpId[]"
                                                                                value="<?php echo $invoiceFollowUpArray[$i]['invoiceFollowUpId']; ?>">
                                                            <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                   value="<?php echo $invoiceFollowUpArray[$i]['isDelete']; ?>"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" valign="top" align="center"><?php
                                                        $invoiceFollowUp->exceptionMessage(
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
                                                    $invoiceFollowUp->exceptionMessage(
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
                                                $invoiceFollowUp->exceptionMessage(
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
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
                            <?php $navigation->pagenationv4($offset); ?>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
                            <button type="button"  class="delete btn btn-warning"
                                    onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php
                                    echo $invoiceFollowUp->getControllerPath(
                                    );
                                    ?>', '<?php echo $invoiceFollowUp->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
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
        <input type="hidden" name="invoiceFollowUpId" id="invoiceFollowUpId"
               value="<?php
               if (isset($_POST['invoiceFollowUpId'])) {
                   echo $_POST['invoiceFollowUpId'];
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default"
                                            onclick="firstRecord('<?php echo $leafId; ?>', '<?php
                                            echo $invoiceFollowUp->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $invoiceFollowUp->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"> <i class="glyphicon glyphicon-fast-backward glyphicon-white"
                                          value="<?php echo $t['firstButtonLabel']; ?>"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled"
                                            onclick="previousRecord('<?php echo $leafId; ?>', '<?php
                                            echo $invoiceFollowUp->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $invoiceFollowUp->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"> <i class="glyphicon glyphicon-backward glyphicon-white"
                                          value="<?php echo $t['previousButtonLabel']; ?>"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled"
                                            onclick="nextRecord('<?php echo $leafId; ?>', '<?php
                                            echo $invoiceFollowUp->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $invoiceFollowUp->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"> <i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default"
                                            onclick="endRecord('<?php echo $leafId; ?>', '<?php
                                            echo $invoiceFollowUp->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $invoiceFollowUp->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"> <i class="glyphicon glyphicon-fast-forward glyphicon-white"
                                          value="<?php echo $t['endButtonLabel']; ?>"></i> <?php echo $t['endButtonLabel']; ?> </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"> </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="invoiceId"><strong>
                                                       <?php
                                                       echo ucfirst(
                                                               $leafTranslation['invoiceIdLabel']
                                                       );
                                                       ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="invoiceId" id="invoiceId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($invoiceArray)) {
                                                    $totalRecord = intval(count($invoiceArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceFollowUpArray[0]['invoiceId'])) {
                                                                if ($invoiceFollowUpArray[0]['invoiceId'] == $invoiceArray[$i]['invoiceId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $invoiceArray[$i]['invoiceId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $invoiceArray[$i]['invoiceDescription']; ?></option>
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
                                            <span class="help-block" id="invoiceIdHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="followUpIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="followUpId"><strong>
                                                       <?php
                                                       echo ucfirst(
                                                               $leafTranslation['followUpIdLabel']
                                                       );
                                                       ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="followUpId" id="followUpId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($followUpArray)) {
                                                    $totalRecord = intval(count($followUpArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceFollowUpArray[0]['followUpId'])) {
                                                                if ($invoiceFollowUpArray[0]['followUpId'] == $followUpArray[$i]['followUpId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $followUpArray[$i]['followUpId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $followUpArray[$i]['followUpDescription']; ?></option>
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
                                            <span class="help-block" id="followUpIdHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="documentNumber"><strong>
                                                       <?php
                                                       echo ucfirst(
                                                               $leafTranslation['documentNumberLabel']
                                                       );
                                                       ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                       class="form-control" disabled
                                                       value="<?php
                                                       if (isset($invoiceFollowUpArray) && is_array(
                                                                       $invoiceFollowUpArray
                                                               )
                                                       ) {
                                                           if (isset($invoiceFollowUpArray[0]['documentNumber'])) {
                                                               echo htmlentities($invoiceFollowUpArray[0]['documentNumber']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img
                                                        src="./images/icons/document-number.png"></span></div>
                                            <span class="help-block" id="documentNumberHelpMe"></span> </div>
                                    </div>
                                    <?php
                                    if (isset($invoiceFollowUpArray) && is_array($invoiceFollowUpArray)) {

                                        if (isset($invoiceFollowUpArray[0]['invoiceFollowUpDate'])) {
                                            $valueArray = $invoiceFollowUpArray[0]['invoiceFollowUpDate'];
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
                                        }
                                    } else {
                                        $value = null;
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceFollowUpDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="invoiceFollowUpDate"><strong>
                                                       <?php
                                                       echo ucfirst(
                                                               $leafTranslation['invoiceFollowUpDateLabel']
                                                       );
                                                       ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceFollowUpDate"
                                                       id="invoiceFollowUpDate" value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                     id="invoiceFollowUpDateImage"></span></div>
                                            <span class="help-block" id="invoiceFollowUpDateHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group"
                                         id="invoiceFollowUpDescriptionForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="invoiceFollowUpDescription"><strong>
                                                       <?php
                                                       echo ucfirst(
                                                               $leafTranslation['invoiceFollowUpDescriptionLabel']
                                                       );
                                                       ?>
                                            </strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea class="form-control" name="invoiceFollowUpDescription"
                                                      id="invoiceFollowUpDescription"
                                                      onkeyup="removeMeError('invoiceFollowUpDescription')"><?php
                                                          if (isset($invoiceFollowUpArray[0]['invoiceFollowUpDescription'])) {
                                                              echo htmlentities($invoiceFollowUpArray[0]['invoiceFollowUpDescription']);
                                                          }
                                                          ?></textarea>
                                            <span class="help-block" id="invoiceFollowUpDescriptionHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group"> <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> <a id="newRecordButton2" href="javascript:void(0)"
                                                                                                                                  class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span
                                        class=caret></span></a>
                                <ul class="dropdown-menu">
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?> </a></li>
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?> </a></li>
                <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];             ?></a> </li>-->
                <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];             ?></a> </li>-->
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li>
                                </ul>
                            </div>
                            <div class="btn-group"> <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                        class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> <a id="updateRecordButton2" href="javascript:void(0)"
                                                                                                                                     class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span
                                        class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a> </li>
                <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];             ?></a> </li> -->
                                    <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?> </a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i
                                        class="glyphicon glyphicon-trash glyphicon-white"
                                        value="<?php echo $t['deleteButtonLabel']; ?>"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info"
                                        onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                        echo $invoiceFollowUp->getControllerPath(
                                        );
                                        ?>', '<?php
                                        echo $invoiceFollowUp->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"
                                        value="<?php echo $t['resetButtonLabel']; ?>"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info"
                                        onclick="showGrid('<?php echo $leafId; ?>', '<?php
                                        echo $invoiceFollowUp->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
                            </div>
                        </div>
                        <input type="hidden" name="firstRecordCounter" id="firstRecordCounter"
                               value="<?php
                               if (isset($firstRecord)) {
                                   echo intval($firstRecord);
                               }
                               ?>">
                        <input type="hidden" name="nextRecordCounter" id="nextRecordCounter"
                               value="<?php
                               if (isset($nextRecord)) {
                                   echo intval($nextRecord);
                               }
                               ?>">
                        <input type="hidden" name="previousRecordCounter" id="previousRecordCounter"
                               value="<?php
                               if (isset($previousRecord)) {
                                   echo intval($previousRecord);
                               }
                               ?>">
                        <input type="hidden" name="lastRecordCounter" id="lastRecordCounter"
                               value="<?php
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
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('invoiceFollowUpId');
            validateMeNumeric('invoiceId');
            validateMeNumeric('followUpId');

            $('#invoiceFollowUpDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('invoiceFollowUpDescription');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>
                $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton1').attr('onClick', '');
                $('#updateRecordButton2').attr('onClick', '');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton4').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                $('#firstRecordButton').removeClass().addClass('btn btn-default');
                $('#endRecordButton').removeClass().addClass('btn btn-default');
    <?php } else if ($_POST['invoiceFollowUpId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoiceFollowUp->getControllerPath(); ?>','<?php echo $invoiceFollowUp->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
    </script>
<?php } ?>
<script type="text/javascript" src="./package/financial/accountReceivable/javascript/invoiceFollowUp.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>
