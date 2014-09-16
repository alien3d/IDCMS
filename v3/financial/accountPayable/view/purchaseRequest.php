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
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseRequestController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/controller/purchaseRequestDetailController.php");
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

$translator->setCurrentTable(array('purchaseRequest', 'purchaseRequestDetail'));
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
$purchaseRequestArray = array();
$branchArray = array();
$departmentArray = array();
$warehouseArray = array();
$productResourcesArray = array();
$equipmentStatusArray = array();
$employeeArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $purchaseRequest = new \Core\Financial\AccountPayable\PurchaseRequest\Controller\PurchaseRequestClass();
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
            $purchaseRequest->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $purchaseRequest->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $purchaseRequest->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $purchaseRequest->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $purchaseRequest->setStartDay($start[2]);
            $purchaseRequest->setStartMonth($start[1]);
            $purchaseRequest->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $purchaseRequest->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $purchaseRequest->setEndDay($start[2]);
            $purchaseRequest->setEndMonth($start[1]);
            $purchaseRequest->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $purchaseRequest->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $purchaseRequest->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $purchaseRequest->setServiceOutput('html');
        $purchaseRequest->setLeafId($leafId);
        $purchaseRequest->execute();
        $branchArray = $purchaseRequest->getBranch();
        $departmentArray = $purchaseRequest->getDepartment();
        $warehouseArray = $purchaseRequest->getWarehouse();
        $productResourcesArray = $purchaseRequest->getProductResources();
        $equipmentStatusArray = $purchaseRequest->getEquipmentStatus();
        $employeeArray = $purchaseRequest->getEmployee();
        if ($_POST['method'] == 'read') {
            $purchaseRequest->setStart($offset);
            $purchaseRequest->setLimit($limit); // normal system don't like paging..  
            $purchaseRequest->setPageOutput('html');
            $purchaseRequestArray = $purchaseRequest->read();
            if (isset($purchaseRequestArray [0]['firstRecord'])) {
                $firstRecord = $purchaseRequestArray [0]['firstRecord'];
            }
            if (isset($purchaseRequestArray [0]['nextRecord'])) {
                $nextRecord = $purchaseRequestArray [0]['nextRecord'];
            }
            if (isset($purchaseRequestArray [0]['previousRecord'])) {
                $previousRecord = $purchaseRequestArray [0]['previousRecord'];
            }
            if (isset($purchaseRequestArray [0]['lastRecord'])) {
                $lastRecord = $purchaseRequestArray [0]['lastRecord'];
                $endRecord = $purchaseRequestArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($purchaseRequest->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($purchaseRequestArray [0]['total'])) {
                $total = $purchaseRequestArray [0]['total'];
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
        <form class="form-horizontal">
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
                        <button title="A" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button>
                        <button title="B" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button>
                        <button title="C" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button>
                        <button title="D" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button>
                        <button title="E" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button>
                        <button title="F" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button>
                        <button title="G" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button>
                        <button title="H" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button>
                        <button title="I" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button>
                        <button title="J" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button>
                        <button title="K" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button>
                        <button title="L" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button>
                        <button title="M" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button>
                        <button title="N" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button>
                        <button title="O" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button>
                        <button title="P" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button>
                        <button title="Q" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button>
                        <button title="R" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button>
                        <button title="S" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button>
                        <button title="T" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button>
                        <button title="U" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button>
                        <button title="V" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button>
                        <button title="W" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button>
                        <button title="X" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button>
                        <button title="Y" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button>
                        <button title="Z" class="btn btn-success" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div align="right" class="pull-right">
                            <div class="btn-group">
                                <button class="btn btn-warning" type="button"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                                <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button"> <span class="caret"></span> </button>
                                <ul class="dropdown-menu">
                                    <li> <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')"> <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007 </a> </li>
                                    <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')"> <i class ="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
                                    <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')"> <i class ="pull-right glyphicon glyphicon-download"></i>Html </a> </li>
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
                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onclick="showForm('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label>
                                <div class="input-group">
                                    <input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                    if (isset($_POST['query'])) {
                                        echo $_POST['query'];
                                    }
                                    ?>">
                                    <span class="input-group-addon"> <img id="searchTextImage" src="./images/icons/magnifier.png"> </span> </div>
                                <br>
                                <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" rel="tooltip" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')">
                                                <?php echo $t['anyTimeTextLabel']; ?>
                                            </a></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')">
                                                <?php echo $t['todayTextLabel']; ?>
                                            </a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')">
                                                <?php echo $t['weekTextLabel']; ?>
                                            </a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')">
                                                <?php echo $t['monthTextLabel']; ?>
                                            </a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')">
                                                <?php echo $t['yearTextLabel']; ?>
                                            </a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
                                    </tr>
                                </table>
                                <div class="input-group">
                                    <input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" onclick="topPage(125)"  placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                    <span class="input-group-addon"> <img id="startDateImage" src="./images/icons/calendar.png"> </span> </div>
                                <br>
                                <div class="input-group">
                                    <input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" onclick="topPage(175)" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>">
                                    <span class="input-group-addon"> <img id="endDateImage" src="./images/icons/calendar.png"> </span> </div>
                                <br>
                                <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
                                        <input type="hidden" name="purchaseRequestIdPreview" id="purchaseRequestIdPreview">
                                        <div class="form-group" id="departmentIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="departmentIdPreview"><?php echo $leafTranslation['departmentIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="departmentIdPreview" id="departmentIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productResourcesIdPreview"><?php echo $leafTranslation['productResourcesIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="productResourcesIdPreview" id="productResourcesIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="employeeIdPreview"><?php echo $leafTranslation['employeeIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="employeeIdPreview" id="employeeIdPreview">
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
                                        <div class="form-group" id="purchaseRequestDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestDatePreview"><?php echo $leafTranslation['purchaseRequestDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestDatePreview" id="purchaseRequestDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseRequestRequiredDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestRequiredDatePreview"><?php echo $leafTranslation['purchaseRequestRequiredDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestRequiredDatePreview" id="purchaseRequestRequiredDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseRequestDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestDescriptionPreview"><?php echo $leafTranslation['purchaseRequestDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <textarea class="form-control" name="purchaseRequestDescriptionPreview" id="purchaseRequestDescriptionPreview"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                        <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
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
                                        <th width="125px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                        <th width="125px"><?php echo ucwords($leafTranslation['purchaseRequestDateLabel']); ?></th>
                                        <th width="125px"><?php echo ucwords($leafTranslation['purchaseRequestRequiredDateLabel']); ?></th>
                                        <th><?php echo ucwords($leafTranslation['purchaseRequestDescriptionLabel']); ?></th>
                                        <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                        <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                        <th width="25px" align="center"> <input type="checkbox" name="check_all" id="check_all" alt="Check Record" onChange="toggleChecked(this.checked);">
                                        </th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                if (is_array($purchaseRequestArray)) {
                                                    $totalRecord = intval(count($purchaseRequestArray));
                                                    if ($totalRecord > 0) {
                                                        $counter = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $counter++;
                                                            ?>
                                                            <tr <?php
                                                            if ($purchaseRequestArray[$i]['isDelete'] == 1) {
                                                                echo "class=\"danger\"";
                                                            } else {
                                                                if ($purchaseRequestArray[$i]['isDraft'] == 1) {
                                                                    echo "class=\"warning\"";
                                                                }
                                                            }
                                                            ?>>
                                                                <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                                <td valign="top" align="center"><div class="btn-group" align="center">
                                                                        <button type="button"  class="btn btn-warning btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseRequestArray [$i]['purchaseRequestId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($purchaseRequestArray [$i]['purchaseRequestId']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['branchName']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['departmentDescription']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['warehouseDescription']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['productResourcesDescription']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['equipmentStatusDescription']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['employeeFirstName']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['documentNumber']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['referenceNumber']); ?>', '<?php
                                                                        if ($dateConvert->checkDate($purchaseRequestArray[$i]['purchaseRequestDate'])) {
                                                                            $valueData = explode('-', $purchaseRequestArray[$i]['purchaseRequestDate']);
                                                                            $year = $valueData[0];
                                                                            $month = $valueData[1];
                                                                            $day = $valueData[2];
                                                                            $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                                                        }
                                                                        echo rawurlencode($value);
                                                                        ?>', '<?php
                                                                        if ($dateConvert->checkDate($purchaseRequestArray[$i]['purchaseRequestRequiredDate'])) {
                                                                            $valueData = explode('-', $purchaseRequestArray[$i]['purchaseRequestRequiredDate']);
                                                                            $year = $valueData[0];
                                                                            $month = $valueData[1];
                                                                            $day = $valueData[2];
                                                                            $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                                                        } else {
                                                                            echo $value = null;
                                                                        }
                                                                        echo rawurlencode($value);
                                                                        ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['purchaseRequestValidStartDate']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['purchaseRequestValidEndDate']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['purchaseRequestDescription']); ?>', '<?php echo rawurlencode($purchaseRequestArray [$i]['isReject']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                    </div></td>
                                                                <td valign="top"><div align="left">
                                                                        <?php
                                                                        if (isset($purchaseRequestArray[$i]['documentNumber'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos(strtolower($purchaseRequestArray[$i]['documentNumber']), strtolower($_POST['query'])) !== false) {
                                                                                        echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestArray[$i]['documentNumber']);
                                                                                    } else {
                                                                                        echo $purchaseRequestArray[$i]['documentNumber'];
                                                                                    }
                                                                                } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(strtolower($purchaseRequestArray[$i]['documentNumber']), strtolower($_POST['character'])) !== false) {
                                                                                        echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestArray[$i]['documentNumber']);
                                                                                    } else {
                                                                                        echo $purchaseRequestArray[$i]['documentNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $purchaseRequestArray[$i]['documentNumber'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo"&nbsp;";
                                                                        }
                                                                        ?>
                                                                    </div></td>
                                                                <td valign="top"><?php
                                                                    if (isset($purchaseRequestArray[$i]['purchaseRequestDate'])) {
                                                                        $valueArray = $purchaseRequestArray[$i]['purchaseRequestDate'];
                                                                        if ($dateConvert->checkDate($valueArray)) {
                                                                            $valueData = explode('-', $valueArray);
                                                                            $year = $valueData[0];
                                                                            $month = $valueData[1];
                                                                            $day = $valueData[2];
                                                                            $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                                                        } else {
                                                                            echo $value = null;
                                                                        }
                                                                    } else {
                                                                        echo $value = date("d-m-Y");
                                                                    }
                                                                    ?></td>
                                                                <td valign="top"><?php
                                                                    if (isset($purchaseRequestArray[$i]['purchaseRequestRequiredDate'])) {
                                                                        $valueArray = $purchaseRequestArray[$i]['purchaseRequestRequiredDate'];
                                                                        if ($dateConvert->checkDate($valueArray)) {
                                                                            $valueData = explode('-', $valueArray);
                                                                            $year = $valueData[0];
                                                                            $month = $valueData[1];
                                                                            $day = $valueData[2];
                                                                            $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                                                        } else {
                                                                            echo $value = date("d-m-Y");
                                                                        }
                                                                    } else {
                                                                        echo $value = date("d-m-Y");
                                                                    }
                                                                    ?></td>
                                                                <td valign="top"><div align="left">
                                                                        <?php
                                                                        if (isset($purchaseRequestArray[$i]['purchaseRequestDescription'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos(strtolower($purchaseRequestArray[$i]['purchaseRequestDescription']), strtolower($_POST['query'])) !== false) {
                                                                                        echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestArray[$i]['purchaseRequestDescription']);
                                                                                    } else {
                                                                                        echo $purchaseRequestArray[$i]['purchaseRequestDescription'];
                                                                                    }
                                                                                } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(strtolower($purchaseRequestArray[$i]['purchaseRequestDescription']), strtolower($_POST['character'])) !== false) {
                                                                                        echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestArray[$i]['purchaseRequestDescription']);
                                                                                    } else {
                                                                                        echo $purchaseRequestArray[$i]['purchaseRequestDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $purchaseRequestArray[$i]['purchaseRequestDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestArray[$i]['purchaseRequestDescription'];
                                                                            }
                                                                        } else {
                                                                            echo"&nbsp;";
                                                                        }
                                                                        ?>
                                                                    </div></td>
                                                                <td valign="top" align="center"><div align="center">
                                                                        <?php
                                                                        if (isset($purchaseRequestArray[$i]['executeBy'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos($purchaseRequestArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                        echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestArray[$i]['staffName']);
                                                                                    } else {
                                                                                        echo $purchaseRequestArray[$i]['staffName'];
                                                                                    }
                                                                                } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos($purchaseRequestArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                        echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestArray[$i]['staffName']);
                                                                                    } else {
                                                                                        echo $purchaseRequestArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $purchaseRequestArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo"&nbsp;";
                                                                        }
                                                                        ?>
                                                                    </div></td>
                                                                <td><div align="left"><?php
                                                                        if (isset($purchaseRequestArray[$i]['executeTime'])) {
                                                                            $valueArray = $purchaseRequestArray[$i]['executeTime'];
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
                                                                                echo $value = date($systemFormat['systemSettingDateFormat'] . " " . $systemFormat['systemSettingTimeFormat'], mktime($hour, $minute, $second, $month, $day, $year));
                                                                            }
                                                                        }
                                                                        ?></div></td>
                                                                <td valign="top"><?php
                                                                    if ($purchaseRequestArray[$i]['isDelete']) {
                                                                        $checked = "checked";
                                                                    } else {
                                                                        $checked = NULL;
                                                                    }
                                                                    ?><input class="form-control" style="display:none;" type="checkbox" name="purchaseRequestId[]"  value="<?php echo $purchaseRequestArray[$i]['purchaseRequestId']; ?>">
                                                                    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $purchaseRequestArray[$i]['isDelete']; ?>"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr class="danger">
                                                            <td colspan="9" valign="top" align="center"><?php $purchaseRequest->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr class="danger">
                                                        <td colspan="9" valign="top" align="center"><?php $purchaseRequest->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr class="danger">
                                                    <td colspan="9" valign="top" align="center"><?php $purchaseRequest->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                                <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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

        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <?php
    $purchaseRequestDetail = new \Core\Financial\AccountPayable\PurchaseRequestDetail\Controller\PurchaseRequestDetailClass();
    $purchaseRequestDetail->setServiceOutput('html');
    $purchaseRequestDetail->setLeafId($leafId);
    $purchaseRequestDetail->execute();
    $productArray = $purchaseRequestDetail->getProduct();
    $unitOfMeasurementArray = $purchaseRequestDetail->getUnitOfMeasurement();
    $chartOfAccountArray = $purchaseRequestDetail->getChartOfAccount();
    $purchaseRequestDetail->setStart(0);
    $purchaseRequestDetail->setLimit(999999); // normal system don't like paging..  
    $purchaseRequestDetail->setPageOutput('html');
    if ($_POST['purchaseRequestId']) {
        $purchaseRequestDetailArray = $purchaseRequestDetail->read();
    }
    ?>
    <form class="form-horizontal">
        <input type="hidden" name="purchaseRequestId" id="purchaseRequestId" value="<?php
        if (isset($_POST['purchaseRequestId'])) {
            echo $_POST['purchaseRequestId'];
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
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <div class="btn-group" align="left">
                                    <button type="button"  id="newRecordButton1" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <div class="btn-group" align="left"> <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i>
                                        <?php echo $t['updateButtonLabel']; ?>
                                    </a> <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i>
                                                <?php echo $t['updateButtonLabel']; ?>
                                            </a> </li>
                                           <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];    ?></a> </li> -->
                                        <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i>
                                                <?php echo $t['updateListingButtonLabel']; ?>
                                            </a> </li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"> <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
                                </div>
                            </div>
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $purchaseRequest->getViewPath(); ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="documentNumberForm">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumber"><strong><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <div class="input-group">
                                        <input type="text" name="documentNumber" id="documentNumber"
                                               class="form-control" disabled
                                               value="<?php
                                               if (isset($purchaseRequestArray[0]['documentNumber'])) {
                                                   if (isset($purchaseRequestArray[0]['documentNumber'])) {
                                                       echo htmlentities($purchaseRequestArray[0]['documentNumber']);
                                                   }
                                               }
                                               ?>">
                                        <span class="input-group-addon"><img src="./images/icons/document-number.png"></span></div>
                                    <span class="help-block" id="documentNumberHelpMe"></span> </div>
                            </div>
                            <?php
                            if (isset($purchaseRequestArray) && is_array($purchaseRequestArray)) {

                                if (isset($purchaseRequestArray[0]['purchaseRequestDate'])) {
                                    $valueArray = $purchaseRequestArray[0]['purchaseRequestDate'];
                                    if ($dateConvert->checkDate($valueArray)) {
                                        $valueData = explode('-', $valueArray);
                                        $year = $valueData[0];
                                        $month = $valueData[1];
                                        $day = $valueData[2];
                                        $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                    } else {
                                        $value = date("d-m-Y");
                                    }
                                } else {
                                    $value = date("d-m-Y");
                                }
                            } else {
                                $value = date("d-m-Y");
                            }
                            ?>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="purchaseRequestDateForm">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseRequestDate"><strong><?php echo ucfirst($leafTranslation['purchaseRequestDateLabel']); ?></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="purchaseRequestDate" id="purchaseRequestDate" value="<?php
                                        if (isset($value)) {
                                            echo $value;
                                        }
                                        ?>" >
                                        <span class="input-group-addon"><img src="./images/icons/calendar.png" id="purchaseRequestDateImage"></span></div>
                                    <span class="help-block" id="purchaseRequestDateHelpMe"></span> </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="departmentIdForm">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="departmentId"><strong><?php echo ucfirst($leafTranslation['departmentIdLabel']); ?></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <select name="departmentId" id="departmentId" class="form-control  chzn-select" onChange="getEmployee('<?php echo $leafId; ?>', '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $securityToken; ?>')">
                                        <option value=""></option>
                                        <?php
                                        if (is_array($departmentArray)) {
                                            $totalRecord = intval(count($departmentArray));
                                            if ($totalRecord > 0) {
                                                $d = 1;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    if (isset($purchaseRequestArray[0]['departmentId'])) {
                                                        if ($purchaseRequestArray[0]['departmentId'] == $departmentArray[$i]['departmentId']) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = NULL;
                                                        }
                                                    } else {
                                                        $selected = NULL;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $departmentArray[$i]['departmentId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $departmentArray[$i]['departmentDescription']; ?></option>
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
                                    <span class="help-block" id="departmentIdHelpMe"></span> </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="productResourcesIdForm">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesId"><strong><?php echo ucfirst($leafTranslation['productResourcesIdLabel']); ?></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <select name="productResourcesId" id="productResourcesId" class="form-control  chzn-select">
                                        <option value=""></option>
                                        <?php
                                        if (is_array($productResourcesArray)) {
                                            $totalRecord = intval(count($productResourcesArray));
                                            if ($totalRecord > 0) {
                                                $d = 1;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    if (isset($purchaseRequestArray[0]['productResourcesId'])) {
                                                        if ($purchaseRequestArray[0]['productResourcesId'] == $productResourcesArray[$i]['productResourcesId']) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = NULL;
                                                        }
                                                    } else {
                                                        $selected = NULL;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $productResourcesArray[$i]['productResourcesId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $productResourcesArray[$i]['productResourcesDescription']; ?></option>
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
                                    <span class="help-block" id="productResourcesIdHelpMe"></span> </div>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="employeeIdForm">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="employeeId"><strong><?php echo ucfirst($leafTranslation['employeeIdLabel']); ?> <span style="color:red;"><i class="fa fa-exclamation"></i></span></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <select name="employeeId" id="employeeId" class="form-control chzn-select" onChange="removeMeError('employeeId', 4);">
                                        <option value=""></option>
                                        <?php
                                        if (is_array($employeeArray)) {
                                            $totalRecord = intval(count($employeeArray));
                                            if ($totalRecord > 0) {
                                                $d = 1;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    if (isset($purchaseRequestArray[0]['employeeId'])) {
                                                        if ($purchaseRequestArray[0]['employeeId'] == $employeeArray[$i]['employeeId']) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = NULL;
                                                        }
                                                    } else {
                                                        $selected = NULL;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $employeeArray[$i]['employeeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $employeeArray[$i]['employeeFirstName']; ?></option>
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
                                    <span class="help-block" id="employeeIdHelpMe"></span> </div>
                            </div>
                            <?php
                            if (isset($purchaseRequestArray) && is_array($purchaseRequestArray)) {

                                if (isset($purchaseRequestArray[0]['purchaseRequestRequiredDate'])) {
                                    $valueArray = $purchaseRequestArray[0]['purchaseRequestRequiredDate'];
                                    if ($dateConvert->checkDate($valueArray)) {
                                        $valueData = explode('-', $valueArray);
                                        $year = $valueData[0];
                                        $month = $valueData[1];
                                        $day = $valueData[2];
                                        $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                    } else {
                                        $value = date("d-m-Y");
                                    }
                                } else {
                                    $value = date("d-m-Y");
                                }
                            } else {
                                $value = date("d-m-Y");
                            }
                            ?>
                            <div class="ccol-xs-4 col-sm-4 col-md-4 col-lg-4 form-group" id="purchaseRequestRequiredDateForm">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseRequestRequiredDate"><strong><?php echo ucfirst($leafTranslation['purchaseRequestRequiredDateLabel']); ?> <span style="color:red;"><i class="fa fa-exclamation"></i></span></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="purchaseRequestRequiredDate" id="purchaseRequestRequiredDate" value="<?php
                                        if (isset($value)) {
                                            echo $value;
                                        }
                                        ?>"  onblur="removeMeError('purchaseRequestRequiredDate', 4)" >
                                        <span class="input-group-addon"><img src="./images/icons/calendar.png" id="purchaseRequestRequiredDateImage"></span></div>
                                    <span class="help-block" id="purchaseRequestRequiredDateHelpMe"></span> </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <textarea class="form-control" name="purchaseRequestDescription" id="purchaseRequestDescription" rows="2"><?php
                                    if (isset($purchaseRequestArray[0]['purchaseRequestDescription'])) {

                                        echo htmlentities($purchaseRequestArray[0]['purchaseRequestDescription']);
                                    }
                                    ?>
                                </textarea>
                                <span class="help-block" id="purchaseRequestDescriptionHelpMe"></span> </div>
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
            <div class="modal fade" id="deleteDetailPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="purchaseRequestDetailIdPreview" id="purchaseRequestDetailIdPreview">
                            <div class="form-group" id="productIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productIdPreview"><?php echo $leafTranslation['productIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="productIdPreview" id="productIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="purchaseRequestDescriptionDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseRequestDescriptionPreview"><?php echo $leafTranslation['purchaseRequestDescriptionLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="purchaseRequestDescriptionPreview" id="purchaseRequestDescriptionPreview">
                                </div>
                            </div>
                            <div class="form-group" id="purchaseRequestDetailQuantityDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="purchaseRequestDetailQuantityPreview"><?php echo $leafTranslation['purchaseRequestDetailQuantityLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="purchaseRequestDetailQuantityPreview" id="purchaseRequestDetailQuantityPreview">
                                </div>
                            </div>
                            <div class="form-group" id="unitOfMeasurementIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="unitOfMeasurementIdPreview"><?php echo $leafTranslation['unitOfMeasurementIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="unitOfMeasurementIdPreview" id="unitOfMeasurementIdPreview">
                                </div>
                            </div>
                            <div class="form-group" id="chartOfAccountIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                            <button type="button"  class="btn btn-primary" onclick="showMeModal('deleteDetailPreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                <thead>
                                    <tr class="success">
                                        <th><?php echo ucfirst($leafTranslation['productIdLabel']); ?></th>
                                        <th style="width:100px"><?php echo ucfirst($leafTranslation['purchaseRequestDetailQuantityLabel']); ?></th>
                                        <th style="width:125px"><?php echo ucfirst($leafTranslation['unitOfMeasurementIdLabel']); ?></th>
                                        <th style="width:250px"><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                        <th style="width:125px"><?php echo ucfirst($leafTranslation['purchaseRequestDetailBudgetLabel']); ?></th>
                                    </tr>
                                    <tr>
                                </thead>
                                <?php
                                $disabledDetail = null;
                                if (isset($_POST['purchaseRequestId']) && (strlen($_POST['purchaseRequestId']) > 0)) {
                                    $disabledDetail = null;
                                } else {
                                    $disabledDetail = "disabled";
                                }
                                ?>
                                <thead>
                                    <tr>
                                        <td valign="top" class="form-group" id="productId9999Detail"><select name="productId[]" id="productId9999" class="chzn-select form-control" <?php echo $disabledDetail; ?>>
                                                <option value=""></option>
                                                <?php
                                                if (is_array($productArray)) {
                                                    $totalRecord = intval(count($productArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            ?>
                                                            <option value="<?php echo $productArray[$i]['productId']; ?>"><?php echo $productArray[$i]['productDescription']; ?></option>
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
                                        <td valign="top"  class="form-group" id="purchaseRequestDetailQuantityDetail9999" style="width:75px"><input class="form-control" <?php echo $disabledDetail; ?> type="text" name="purchaseRequestDetailQuantity[]" id="purchaseRequestDetailQuantity9999" value=1></td>
                                        <td valign="top"  class="form-group" id="unitOfMeasurementIdDetail9999" style="width:100px"><select name="unitOfMeasurementId[]" id="unitOfMeasurementId9999" class="chzn-select form-control " <?php echo $disabledDetail; ?>  style="width:100px">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($unitOfMeasurementArray)) {
                                                    $totalRecord = intval(count($unitOfMeasurementArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            ?>
                                                            <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>"><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementCode']; ?> - <?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
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
                                        <td valign="top" class="form-group" id="chartOfAccountIdDetail9999"><select name="chartOfAccountId[]" id="chartOfAccountId9999" class="chzn-select form-control" onChange="getBudget(<?php echo $leafId; ?>, '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 9999);">
                                                <option value=""></option>
                                                <?php
                                                $currentChartOfAccountTypeDescription = null;
                                                if (is_array($chartOfAccountArray)) {
                                                    $totalRecord = intval(count($chartOfAccountArray));
                                                    if ($totalRecord > 0) {
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if ($i != 0) {
                                                                if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                    echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                                                }
                                                            } else {
                                                                echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                                            }
                                                            $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                            ?>
                                                            <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>"><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                            <?php
                                                        }
                                                        echo "</optgroup>\n";
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
                                            <span class="help-block" id="chartOfAccountId9999HelpMe"></span></td>
                                        <td valign="top" class="form-group" id="purchaseRequestDetailBudget9999"><div class="col-xs-12 col-sm-12 col-md-12">
                                                <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="purchaseRequestDetailBudget[]" id="purchaseRequestDetailBudget9999">
                                            </div>
                                            <span class="help-block" id="purchaseRequestDetailBudget9999HelpMe"></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><textarea name="purchaseRequestDetailDescription9999" id="purchaseRequestDetailDescription9999" class="form-control"><?php
                                                if (isset($purchaseRequestDetailArray) && is_array($purchaseRequestDetailArray)) {
                                                    echo $purchaseRequestDetailArray[$i]['purchaseRequestDescription'];
                                                }
                                                ?>
                                            </textarea></td>
                                        <td align="center" valign="middle"><button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onclick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>');" value="<?php echo $t['newButtonLabel']; ?>"> <i class="glyphicon glyphicon-plus  glyphicon-white"></i></button></td>
                                    </tr>
                                <tbody id="tableBody">
                                    <?php
                                    if ($_POST['purchaseRequestId']) {
                                        if (is_array($purchaseRequestDetailArray)) {
                                            $totalRecordDetail = intval(count($purchaseRequestDetailArray));
                                            if ($totalRecordDetail > 0) {
                                                $counter = 0;
                                                for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                    $counter++;
                                                    ?>
                                                    <tr id="<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>">
                                                        <td valign="top"  class="form-group" id="productId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>Detail"><select name="productId[]" id="productId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($productArray)) {
                                                                    $totalRecord = intval(count($productArray));
                                                                    if ($totalRecord > 0) {
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if ($purchaseRequestDetailArray[$j]['productId'] == $productArray[$i]['productId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = NULL;
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $productArray[$i]['productId']; ?>" <?php echo $selected; ?>><?php echo $productArray[$i]['productDescription']; ?></option>
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
                                                        <td valign="top"  class="form-group" id="purchaseRequestDetailQuantity<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="purchaseRequestDetailQuantity[]" id="purchaseRequestDetailQuantity<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>"   value="<?php
                                                            if (isset($purchaseRequestDetailArray) && is_array($purchaseRequestDetailArray)) {
                                                                echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailQuantity'];
                                                            }
                                                            ?>"></td>
                                                        <td valign="top"  class="form-group" id="unitOfMeasurementId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>Detail"><select name="unitOfMeasurementId[]" id="unitOfMeasurementId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" class="form-control chzn-select">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($unitOfMeasurementArray)) {
                                                                    $totalRecord = intval(count($unitOfMeasurementArray));
                                                                    if ($totalRecord > 0) {
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if ($purchaseRequestDetailArray[$j]['unitOfMeasurementId'] == $unitOfMeasurementArray[$i]['unitOfMeasurementId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = NULL;
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
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
                                                        <td valign="top"  class="form-group" id="chartOfAccountId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>Detail"><select name="chartOfAccountId[]" id="chartOfAccountId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" class="form-control chzn-select input-sm" onChange="getBudget(<?php echo $leafId; ?>, '<?php echo $purchaseRequest->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>');">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($chartOfAccountArray)) {
                                                                    $totalRecord = intval(count($chartOfAccountArray));
                                                                    if ($totalRecord > 0) {
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if ($i != 0) {
                                                                                if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                                                    echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                                                                }
                                                                            } else {
                                                                                echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">\n";
                                                                            }
                                                                            $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];

                                                                            if ($purchaseRequestDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = NULL;
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                                            <?php
                                                                        }
                                                                        echo "</optgroup>\n";
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
                                                        <td valign="top"  class="form-group" id="purchaseRequestDetailBudget<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="purchaseRequestDetailBudget[]" id="purchaseRequestDetailBudget<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>"   value="<?php
                                                            if (isset($purchaseRequestDetailArray) && is_array($purchaseRequestDetailArray)) {
                                                                echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailBudget'];
                                                            }
                                                            ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"><textarea name="purchaseRequestDetailDescription<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" id="purchaseRequestDetailDescription<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" class="form-control"><?php
                                                                if (isset($purchaseRequestDetailArray) && is_array($purchaseRequestDetailArray)) {
                                                                    echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailDescription'];
                                                                }
                                                                ?>
                                                            </textarea></td>
                                                        <td valign="top" align="center"><div class="btn-group" align="center">
                                                                <input type="hidden" name="purchaseRequestDetailId[]" id="purchaseRequestDetailId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" value="<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>">
                                                                <input type="hidden" name="purchaseRequestId[]" id="purchaseRequestId<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>" value="<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestId']; ?>">
                                                                <button type="button"  class="btn btn-warning btn-mini" title="Edit" onclick="showFormUpdateDetail('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($purchaseRequestDetailArray [$j]['purchaseRequestDetailId']); ?>')"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                <button type="button"  class="btn btn-danger btn-mini" title="Delete" onclick="showModalDeleteDetail('<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>')"><i class="glyphicon glyphicon-trash  glyphicon-white"></i></button>
                                                                <div id="miniInfoPanel<?php echo $purchaseRequestDetailArray[$j]['purchaseRequestDetailId']; ?>"></div>
                                                            </div></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr class="danger">
                                                    <td colspan="8" valign="top" align="center">&nbsp;<?php $purchaseRequestDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr class="danger">
                                                <td colspan="8" valign="top" align="center">&nbsp;<?php $purchaseRequestDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr class="danger">
                                            <td colspan="8" valign="top" align="center">&nbsp;<?php $purchaseRequestDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                            </table>
                        </div>
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
            validateMeNumeric('purchaseRequestId');
            validateMeNumeric('branchId');
            validateMeNumeric('departmentId');
            validateMeNumeric('warehouseId');
            validateMeNumeric('productResourcesId');
            validateMeNumeric('equipmentStatusId');
            validateMeNumeric('employeeId');

            validateMeAlphaNumeric('referenceNumber');
            $('#purchaseRequestDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#purchaseRequestRequiredDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
                if ($(this).val().length > 0) {
                    $('#purchaseRequestRequiredDateForm').removeClass().addClass('col-xs-4 col-sm-4 col-md-4 col-lg-4 form-group');
                }
            });
            $('#purchaseRequestValidStartDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#purchaseRequestValidEndDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('purchaseRequestDescription');
            validateMeNumeric('isReject');
            validateMeNumericRange('purchaseRequestDetailId');
            validateMeNumericRange('purchaseRequestId');
            validateMeNumericRange('productId');
            validateMeAlphaNumericRange('purchaseRequestDescription');
            validateMeCurrencyRange('purchaseRequestDetailQuantity');
            validateMeNumericRange('unitOfMeasurementId');
            validateMeNumericRange('chartOfAccountId');
            validateMeCurrencyRange('purchaseRequestDetailBudget');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
        <?php } ?>             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton1').attr('onClick', '');
                $('#updateRecordButton2').attr('onClick', '');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton4').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#firstRecordButton').removeClass().addClass('btn btn-default');
                $('#endRecordButton').removeClass().addClass('btn btn-default');
    <?php } else if ($_POST['purchaseRequestId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
                            ;
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').attr('onclick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $purchaseRequest->getControllerPath(); ?>','<?php echo $purchaseRequest->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>

        });

    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/accountPayable/javascript/purchaseRequest.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>
