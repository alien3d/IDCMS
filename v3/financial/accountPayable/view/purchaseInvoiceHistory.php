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
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseInvoiceController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseInvoiceDetailController.php");
require_once ($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");
require_once ($newFakeDocumentRoot . "library/class/classDate.php");
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

$translator->setCurrentTable(array('purchaseInvoice', 'purchaseInvoiceDetail'));
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
$purchaseInvoiceArray = array();
$businessPartnerArray = array();
$purchaseInvoiceProjectArray = array();
$_POST['from'] = 'purchaseInvoiceHistory.php';
$_GET['from'] = 'purchaseInvoiceHistory.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $purchaseInvoice = new \Core\Financial\AccountPayable\PurchaseInvoice\Controller\PurchaseInvoiceClass();
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
            $purchaseInvoice->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $purchaseInvoice->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $purchaseInvoice->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $purchaseInvoice->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $purchaseInvoice->setStartDay($start[2]);
            $purchaseInvoice->setStartMonth($start[1]);
            $purchaseInvoice->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $purchaseInvoice->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $purchaseInvoice->setEndDay($start[2]);
            $purchaseInvoice->setEndMonth($start[1]);
            $purchaseInvoice->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $purchaseInvoice->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $purchaseInvoice->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $purchaseInvoice->setServiceOutput('html');
        $purchaseInvoice->setLeafId($leafId);
        $purchaseInvoice->execute();
        $businessPartnerArray = $purchaseInvoice->getBusinessPartner();
        $purchaseInvoiceProjectArray = $purchaseInvoice->getPurchaseInvoiceProject();
        if ($_POST['method'] == 'read') {
            $purchaseInvoice->setStart($offset);
            $purchaseInvoice->setLimit($limit); // normal system don't like paging..  
            $purchaseInvoice->setPageOutput('html');
            $purchaseInvoiceArray = $purchaseInvoice->read();
            if (isset($purchaseInvoiceArray [0]['firstRecord'])) {
                $firstRecord = $purchaseInvoiceArray [0]['firstRecord'];
            }
            if (isset($purchaseInvoiceArray [0]['nextRecord'])) {
                $nextRecord = $purchaseInvoiceArray [0]['nextRecord'];
            }
            if (isset($purchaseInvoiceArray [0]['previousRecord'])) {
                $previousRecord = $purchaseInvoiceArray [0]['previousRecord'];
            }
            if (isset($purchaseInvoiceArray [0]['lastRecord'])) {
                $lastRecord = $purchaseInvoiceArray [0]['lastRecord'];
                $endRecord = $purchaseInvoiceArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($purchaseInvoice->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($purchaseInvoiceArray [0]['total'])) {
                $total = $purchaseInvoiceArray [0]['total'];
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
                echo $template->breadcrumb($applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId);
                ?>
            </div>
        </div>
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div id="content" style="opacity: 1;">
            <div class="row">
                <div align="left" class="btn-group col-xs-10 col-sm-10 col-md-10 pull-left">
                    <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A')">A</button>
                    <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B')">B</button>
                    <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C')">C</button>
                    <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D')">D</button>
                    <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E')">E</button>
                    <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F')">F</button>
                    <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G')">G</button>
                    <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H')">H</button>
                    <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I')">I</button>
                    <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J')">J</button>
                    <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K')">K</button>
                    <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L')">L</button>
                    <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M')">M</button>
                    <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N')">N</button>
                    <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O')">O</button>
                    <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P')">P</button>
                    <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q')">Q</button>
                    <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R')">R</button>
                    <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S')">S</button>
                    <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T')">T</button>
                    <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U')">U</button>
                    <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V')">V</button>
                    <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W')">W</button>
                    <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X')">X</button>
                    <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y')">Y</button>
                    <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z')">Z</button>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="right" class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button"> <span class="caret"></span> </button>
                            <ul class="dropdown-menu">
                                <li> <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')"> <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007 </a> </li>
                                <li> <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')"> <i class ="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
                                <li> <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')"> <i class ="pull-right glyphicon glyphicon-download"></i>Html </a> </li>
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

                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button>
                            </div>
                            <label for="queryWidget"></label>
                            <input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                            if (isset($_POST['query'])) {
                                echo $_POST['query'];
                            }
                            ?>">
                            <br>
                            <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            <br>

                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
                                </tr>
                            </table>

                            <div>
                                <label for="dateRangeStart"></label>
                                <input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                if (isset($_POST['dateRangeStart'])) {
                                    echo $_POST['dateRangeStart'];
                                }
                                ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                <br>
                                <label for="dateRangeEnd"></label>
                                <input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                                if (isset($_POST['dateRangeEnd'])) {
                                    echo $_POST['dateRangeEnd'];
                                }
                                ?>" onClick="topPage(150)" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>">
                                <br>
                                <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="purchaseInvoiceIdPreview" id="purchaseInvoiceIdPreview">
                                        <div class="form-group" id="businessPartnerIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceProjectIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceProjectIdPreview"><?php echo $leafTranslation['purchaseInvoiceProjectIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceProjectIdPreview" id="purchaseInvoiceProjectIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="documentNumberPreview" id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="referenceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="referenceNumberPreview"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview" id="referenceNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceAmountPreview"><?php echo $leafTranslation['purchaseInvoiceAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceAmountPreview" id="purchaseInvoiceAmountPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceDatePreview"><?php echo $leafTranslation['purchaseInvoiceDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceDatePreview" id="purchaseInvoiceDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceCreditTermDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceCreditTermPreview"><?php echo $leafTranslation['purchaseInvoiceCreditTermLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceCreditTermPreview" id="purchaseInvoiceCreditTermPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceReminderDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceReminderDatePreview"><?php echo $leafTranslation['purchaseInvoiceReminderDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceReminderDatePreview" id="purchaseInvoiceReminderDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseInvoiceDescriptionPreview"><?php echo $leafTranslation['purchaseInvoiceDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceDescriptionPreview" id="purchaseInvoiceDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isAllocatedDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isAllocatedPreview"><?php echo $leafTranslation['isAllocatedLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isAllocatedPreview" id="isAllocatedPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                    <thead>
                                        <tr>
                                            <th width="25px" align="center"><div align="center">#</div></th>
                                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['purchaseInvoiceAmountLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['purchaseInvoiceDateLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['purchaseInvoiceDescriptionLabel']); ?></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record" onClick="toggleChecked(this.checked)"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($purchaseInvoiceArray)) {
                                                $totalRecord = intval(count($purchaseInvoiceArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($purchaseInvoiceArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($purchaseInvoiceArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td vAlign="top" align="center"><div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceArray [$i]['purchaseInvoiceId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete('<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceId']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['businessPartnerCompany']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceProjectDescription']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['documentNumber']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['referenceNumber']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceAmount']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceDate']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceCreditTerm']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceReminderDate']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['purchaseInvoiceDescription']); ?>', '<?php echo rawurlencode($purchaseInvoiceArray [$i]['isAllocated']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div></td>
                                                            <td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseInvoiceArray[$i]['businessPartnerCompany'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseInvoiceArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseInvoiceArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceArray[$i]['businessPartnerCompany']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceArray[$i]['businessPartnerCompany'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            $d = $purchaseInvoiceArray[$i]['purchaseInvoiceAmount'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($purchaseInvoiceArray[$i]['purchaseInvoiceAmount']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                            <?php
                                                            if (isset($purchaseInvoiceArray[$i]['purchaseInvoiceDate'])) {
                                                                $valueArray = $purchaseInvoiceArray[$i]['purchaseInvoiceDate'];
                                                                if ($dateConvert->checkDate($valueArray)) {
                                                                    $valueData = explode('-', $valueArray);
                                                                    $year = $valueData[0];
                                                                    $month = $valueData[1];
                                                                    $day = $valueData[2];
                                                                    $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                                                } else {
                                                                    $value = null;
                                                                }
                                                                ?>
                                                                <td vAlign="top"><?php echo $value; ?></td>
                                                            <?php } else { ?>
                                                                <td vAlign="top"><div align="left">&nbsp;</div></td>
                                                            <?php } ?>
                                                            <td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseInvoiceArray[$i]['purchaseInvoiceDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceArray[$i]['purchaseInvoiceDescription']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseInvoiceArray[$i]['purchaseInvoiceDescription']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceArray[$i]['purchaseInvoiceDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($purchaseInvoiceArray[$i]['purchaseInvoiceDescription']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseInvoiceArray[$i]['purchaseInvoiceDescription']);
                                                                                } else {
                                                                                    echo $purchaseInvoiceArray[$i]['purchaseInvoiceDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseInvoiceArray[$i]['purchaseInvoiceDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseInvoiceArray[$i]['purchaseInvoiceDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?></td>
                                                            <?php
                                                            if ($purchaseInvoiceArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td vAlign="top"><input class="form-control" style="display:none;" type="checkbox" name="purchaseInvoiceId[]"  value="<?php echo $purchaseInvoiceArray[$i]['purchaseInvoiceId']; ?>">
                                                                <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $purchaseInvoiceArray[$i]['isDelete']; ?>"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="10" vAlign="top" align="center"><?php $purchaseInvoice->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="10" vAlign="top" align="center"><?php $purchaseInvoice->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" vAlign="top" align="center"><?php $purchaseInvoice->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
                            <?php $navigation->pagenationv4($offset); ?>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
                            <button type="button"  class="delete btn btn-warning" onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            tableHeightSize();
                            $(window).resize(function() {
                                tableHeightSize();
                            });
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
    $purchaseInvoiceDetail = new \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Controller\PurchaseInvoiceDetailClass();
    $purchaseInvoiceDetail->setServiceOutput('html');
    $purchaseInvoiceDetail->setLeafId($leafId);
    $purchaseInvoiceDetail->execute();
    $purchaseInvoiceProjectArray = $purchaseInvoiceDetail->getPurchaseInvoiceProject();
    $countryArray = $purchaseInvoiceDetail->getCountry();
    $businessPartnerArray = $purchaseInvoiceDetail->getBusinessPartner();
    $chartOfAccountArray = $purchaseInvoiceDetail->getChartOfAccount();
    $purchaseInvoiceDetail->setStart(0);
    $purchaseInvoiceDetail->setLimit(999999); // normal system don't like paging..  
    $purchaseInvoiceDetail->setPageOutput('html');
    if ($_POST['purchaseInvoiceId']) {
        $purchaseInvoiceDetailArray = $purchaseInvoiceDetail->read();
    }
    ?>
    <form class="form-horizontal">
        <input type="hidden" name="purchaseInvoiceId" id="purchaseInvoiceId" value="<?php
        if (isset($_POST['purchaseInvoiceId'])) {
            echo $_POST['purchaseInvoiceId'];
        }
        ?>">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?php
                $template->setLayout(2);
                echo $template->breadcrumb($applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId);
                ?>
            </div>
        </div>
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div id="content" style="opacity: 1;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-info" id="masterForm">
                        <div class="panel-heading">
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $purchaseInvoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $purchaseInvoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="businessPartnerIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="businessPartnerId"><strong><?php echo ucfirst($leafTranslation['businessPartnerIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="businessPartnerId" id="businessPartnerId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($businessPartnerArray)) {
                                                    $totalRecord = intval(count($businessPartnerArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($purchaseInvoiceArray[0]['businessPartnerId'])) {
                                                                if ($purchaseInvoiceArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $businessPartnerArray[$i]['businessPartnerCompany']; ?></option>
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
                                            <span class="help-block" id="businessPartnerIdHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumber"><strong><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                       disabled class="form-control"
                                                       value="<?php
                                                       if (isset($purchaseInvoiceArray) && is_array($purchaseInvoiceArray)) {
                                                           if (isset($purchaseInvoiceArray[0]['documentNumber'])) {
                                                               echo htmlentities($purchaseInvoiceArray[0]['documentNumber']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/document-number.png"></span></div>
                                            <span class="help-block" id="documentNumberHelpMe"></span> </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="referenceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="referenceNumber"><strong><?php echo ucfirst($leafTranslation['referenceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber" value="<?php
                                            if (isset($purchaseInvoiceArray) && is_array($purchaseInvoiceArray)) {
                                                if (isset($purchaseInvoiceArray[0]['referenceNumber'])) {
                                                    echo htmlentities($purchaseInvoiceArray[0]['referenceNumber']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="referenceNumberHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceAmount"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceAmount" id="purchaseInvoiceAmount" value="<?php
                                                if (isset($purchaseInvoiceArray) && is_array($purchaseInvoiceArray)) {
                                                    if (isset($purchaseInvoiceArray[0]['purchaseInvoiceAmount'])) {
                                                        echo htmlentities($purchaseInvoiceArray[0]['purchaseInvoiceAmount']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="purchaseInvoiceAmountHelpMe"></span> </div>
                                    </div>
                                    <?php
                                    if (isset($purchaseInvoiceArray) && is_array($purchaseInvoiceArray)) {

                                        if (isset($purchaseInvoiceArray[0]['purchaseInvoiceDate'])) {
                                            $valueArray = $purchaseInvoiceArray[0]['purchaseInvoiceDate'];
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDate"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceDate" id="purchaseInvoiceDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="purchaseInvoiceDateImage"></span></div>
                                            <span class="help-block" id="purchaseInvoiceDateHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceCreditTermForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceCreditTerm"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceCreditTermLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceCreditTerm" id="purchaseInvoiceCreditTerm" value="<?php
                                                if (isset($purchaseInvoiceArray) && is_array($purchaseInvoiceArray)) {
                                                    if (isset($purchaseInvoiceArray[0]['purchaseInvoiceCreditTerm'])) {
                                                        echo htmlentities($purchaseInvoiceArray[0]['purchaseInvoiceCreditTerm']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="purchaseInvoiceCreditTermHelpMe"></span> </div>
                                    </div>
                                    <?php
                                    if (isset($purchaseInvoiceArray) && is_array($purchaseInvoiceArray)) {

                                        if (isset($purchaseInvoiceArray[0]['purchaseInvoiceReminderDate'])) {
                                            $valueArray = $purchaseInvoiceArray[0]['purchaseInvoiceReminderDate'];
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="purchaseInvoiceReminderDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceReminderDate"><strong><?php echo ucfirst($leafTranslation['purchaseInvoiceReminderDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="purchaseInvoiceReminderDate" id="purchaseInvoiceReminderDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="purchaseInvoiceReminderDateImage"></span></div>
                                            <span class="help-block" id="purchaseInvoiceReminderDateHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="purchaseInvoiceDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea class="form-control" name="purchaseInvoiceDescription" id="purchaseInvoiceDescription" ><?php
                                                if (isset($purchaseInvoiceArray[0]['purchaseInvoiceDescription'])) {
                                                    echo htmlentities($purchaseInvoiceArray[0]['purchaseInvoiceDescription']);
                                                }
                                                ?>
                                            </textarea>
                                            <span class="help-block" id="purchaseInvoiceDescriptionHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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
            <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="purchaseInvoiceDetailIdPreview" id="purchaseInvoiceDetailIdPreview">
                            <div class="form-group" id="purchaseInvoiceProjectIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceProjectIdPreview"><?php echo $leafTranslation['purchaseInvoiceProjectIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="purchaseInvoiceProjectIdPreview" id="purchaseInvoiceProjectIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="countryIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="countryIdPreview" id="countryIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="businessPartnerIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="chartOfAccountIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="documentNumberDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="documentNumberPreview" id="documentNumberPreview">
                                </div>
                            </div>
                            <div class="form-group" id="journalNumberDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="journalNumberPreview"><?php echo $leafTranslation['journalNumberLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="journalNumberPreview" id="journalNumberPreview">
                                </div>
                            </div>
                            <div class="form-group" id="purchaseInvoiceDetailPrincipalAmountDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDetailPrincipalAmountPreview"><?php echo $leafTranslation['purchaseInvoiceDetailPrincipalAmountLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="purchaseInvoiceDetailPrincipalAmountPreview" id="purchaseInvoiceDetailPrincipalAmountPreview">
                                </div>
                            </div>
                            <div class="form-group" id="purchaseInvoiceDetailInterestAmountDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDetailInterestAmountPreview"><?php echo $leafTranslation['purchaseInvoiceDetailInterestAmountLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="purchaseInvoiceDetailInterestAmountPreview" id="purchaseInvoiceDetailInterestAmountPreview">
                                </div>
                            </div>
                            <div class="form-group" id="purchaseInvoiceDetailAmountDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseInvoiceDetailAmountPreview"><?php echo $leafTranslation['purchaseInvoiceDetailAmountLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="purchaseInvoiceDetailAmountPreview" id="purchaseInvoiceDetailAmountPreview">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-danger" onClick="deleteGridRecordDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDetail->getControllerPath(); ?>', '<?php echo $purchaseInvoiceDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                            <button type="button"  class="btn btn-primary" onClick="showMeModal('deleteDetailPreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6" align="left">
                    <button type="button"  name="hideMaster" id="hideMaster" onClick="toggle('masterForm');"
                            class="btn btn-info"> <?php echo $t['hideUnHideTextLabel']; ?> </button>
                    <button type="button"  name="trialBalance" id="trialBalance"
                            class="btn btn-info"><?php echo $t['trialBalanceTextLabel']; ?></button>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6" align="right">
                    <button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onClick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>');" value="<?php echo $t['newButtonLabel']; ?>">
                        <i class="glyphicon glyphicon-plus  glyphicon-white"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                        <thead>
                            <tr>
                                <th width="25px" align="center"><div align="center">#</div></th>
                        <th width="125px" align="center"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                        <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                        <th><?php echo ucfirst($leafTranslation['purchaseInvoiceDetailAmountLabel']); ?></th>
                        <th width="200px"><div align="center"><?php echo $t['debitTextLabel']; ?></div></th>
                        <th width="200px"><div align="center"><?php echo $t['creditTextLabel']; ?></div></th>
                        </tr>
                        <tr>
                            <?php
                            $disabledDetail = null;
                            if (isset($_POST['purchaseInvoiceId']) && (strlen($_POST['purchaseInvoiceId']) > 0)) {
                                $disabledDetail = null;
                            } else {
                                $disabledDetail = "disabled";
                            }
                            ?>
                        <tr>
                            <td vAlign="top">&nbsp;</td>
                            <td vAlign="top" align="center"><div align="center">
                                    <button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onClick="showFormCreateDetail('<?php
                                    echo $leafId;
                                    ;
                                    ?>', '<?php echo $purchaseInvoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>')">
                                        <i class="glyphicon glyphicon-plus  glyphicon-white"></i>
                                    </button>
                                    <div id="miniInfoPanel9999"></div>
                                </div></td>
                            <td vAlign="top" class="form-group" id="chartOfAccountId9999Detail"><select name="chartOfAccountId[]" id="chartOfAccountId9999" class="chzn-select form-control ">
                                    <option value=""></option>
                                    <?php
                                    if (is_array($chartOfAccountArray)) {
                                        $totalRecord = intval(count($chartOfAccountArray));
                                        if ($totalRecord > 0) {
                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                ?>
                                                <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>"><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                <span class="help-block" id="chartOfAccountId9999HelpMe"></span>


                            </td>

                            <td vAlign="top" class="form-group" id="purchaseInvoiceDetailAmount9999Detail">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="purchaseInvoiceDetailAmount[]" id="purchaseInvoiceDetailAmount9999" >
                                </div>
                                <span class="help-block" id="purchaseInvoiceDetailAmount9999HelpMe"></span></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        </thead>

                        <tbody id="tableBody">
                            <?php
                            $totalDebit = 0;
                            $totalCredit = 0;
                            if ($_POST['purchaseInvoiceId']) {
                                if (is_array($purchaseInvoiceDetailArray)) {
                                    $totalRecordDetail = intval(count($purchaseInvoiceDetailArray));
                                    if ($totalRecordDetail > 0) {
                                        $counter = 0;
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                                            $counter++;
                                            ?>
                                            <tr id="<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>">
                                                <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                <td vAlign="top" align="center"><div class="btn-group" align="center">
                                                        <input type="hidden" name="purchaseInvoiceDetailId[]" id="purchaseInvoiceDetailId<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>" value="<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>">
                                                        <button type="button"  class="btn btn-warning btn-mini" title="Edit" onClick="showFormUpdateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseInvoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseInvoiceDetailArray [$j]['purchaseInvoiceDetailId']); ?>')"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                        <button type="button"  class="btn btn-danger btn-mini" title="Delete" onClick="showModalDeleteDetail('<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>')"><i class="glyphicon glyphicon-trash  glyphicon-white"></i></button>
                                                        <div id="miniInfoPanel<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>"></div>
                                                    </div></td>
                                                <td vAlign="top" class="form-group" id="chartOfAccountIdAmount<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>Detail">
                                                    <select name="chartOfAccountId[]" id="chartOfAccountId<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>" class="form-control chzn-select">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($chartOfAccountArray)) {
                                                            $totalRecord = intval(count($chartOfAccountArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($purchaseInvoiceDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = NULL;
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                                    </select></td>
                                                <td vAlign="top" class="form-group" id="purchaseInvoiceDetailAmount<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>Detail" ><input class="form-control" style="text-align:right" type="text" name="purchaseInvoiceDetailAmount[]" id="purchaseInvoiceDetailAmount<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailId']; ?>"   value="<?php
                                                    if (isset($purchaseInvoiceDetailArray) && is_array($purchaseInvoiceDetailArray)) {
                                                        echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceDetailAmount'];
                                                    }
                                                    ?>"></td>
                                                    <?php
                                                    $debit = 0;
                                                    $credit = 0;
                                                    $x = 0;
                                                    $y = 0;
                                                    $d = $purchaseInvoiceDetailArray[$j]['purchaseInvoiceCreditNoteDetailAmount'];
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
                                                <td vAlign="middle"><div id="debit_<?php echo $purchaseInvoiceDetailArray[$j]['purchaseInvoiceCreditNoteDetailId']; ?>"
                                                                         class="pull-right"><?php echo $debit; ?></div></td>
                                                <td vAlign="middle"><div id="credit_<?php echo $purchaseInvoiceDetailArray[$j]['jpurchaseInvoiceCreditNoteDetailId']; ?>"
                                                                         class="pull-right"><?php echo $credit; ?></div></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" vAlign="top" align="center"><?php $purchaseInvoiceDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" vAlign="top" align="center"><?php $purchaseInvoiceDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            if ($totalDebit == abs($totalCredit)) {
                                $balanceColor = 'success';
                            } else {
                                $balanceColor = 'warning';
                            }
                            ?>
                            <tr id="totalDetail" class="<?php echo $balanceColor; ?>">
                                <td colspan="4">&nbsp;</td>
                                <td><div class="pull-right" id="totalDebit">
                                        <?php
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
                                        ?>
                                    </div></td>
                                <td><div class="pull-right" id="totalCredit"><?php echo $totalCredit; ?></div></td>
                            </tr>
                        </tbody>
                        <tfoot>
                    </table>
                </div>
            </div>

        </div>
    </form>
    <script type="text/javascript">
        $(document).keypress(function(e) {

            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);


                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $purchaseInvoice->getControllerPath(); ?>', '<?php echo $purchaseInvoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);


                    return false;
                    break;
            }

        });
        $(document).ready(function() {
            tableHeightSize();
            $(window).resize(function() {
                tableHeightSize();
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('purchaseInvoiceId');
            validateMeNumeric('businessPartnerId');
            validateMeNumeric('purchaseInvoiceProjectId');

            validateMeAlphaNumeric('referenceNumber');
            validateMeCurrency('purchaseInvoiceAmount');
            $('#purchaseInvoiceDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeCurrency('purchaseInvoiceCreditTerm');
            $('#purchaseInvoiceReminderDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('purchaseInvoiceDescription');
            validateMeNumeric('isAllocated');
            validateMeNumericRange('purchaseInvoiceDetailId');
            validateMeNumericRange('purchaseInvoiceProjectId');
            validateMeNumericRange('purchaseInvoiceId');
            validateMeNumericRange('countryId');
            validateMeNumericRange('businessPartnerId');
            validateMeNumericRange('chartOfAccountId');
            validateMeAlphaNumericRange('documentNumber');
            validateMeAlphaNumericRange('journalNumber');
            validateMeCurrencyRange('purchaseInvoiceDetailPrincipalAmount');
            validateMeCurrencyRange('purchaseInvoiceDetailInterestAmount');
            validateMeCurrencyRange('purchaseInvoiceDetailAmount');


        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/accountPayable/javascript/purchaseInvoiceHistory.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>