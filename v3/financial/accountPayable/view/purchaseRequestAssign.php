  

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

$translator->setCurrentTable('purchaseRequestDetail');
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
$purchaseRequestDetailArray = array();
$purchaseRequestArray = array();
$productArray = array();
$unitOfMeasurementArray = array();
$businessPartnerIdArray = array();
$_POST['from'] = 'purchaseRequestAssign.php';
$_GET['from'] = 'purchaseRequestAssign.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $purchaseRequestDetail = new \Core\Financial\AccountPayable\PurchaseRequestDetail\Controller\PurchaseRequestDetailClass();
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
            $purchaseRequestDetail->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $purchaseRequestDetail->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $purchaseRequestDetail->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $purchaseRequestDetail->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $purchaseRequestDetail->setStartDay($start[2]);
            $purchaseRequestDetail->setStartMonth($start[1]);
            $purchaseRequestDetail->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $purchaseRequestDetail->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $purchaseRequestDetail->setEndDay($start[2]);
            $purchaseRequestDetail->setEndMonth($start[1]);
            $purchaseRequestDetail->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $purchaseRequestDetail->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $purchaseRequestDetail->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $purchaseRequestDetail->setServiceOutput('html');
        $purchaseRequestDetail->setLeafId($leafId);
        $purchaseRequestDetail->execute();
        $purchaseRequestArray = $purchaseRequestDetail->getPurchaseRequest();
        $productArray = $purchaseRequestDetail->getProduct();
        $unitOfMeasurementArray = $purchaseRequestDetail->getUnitOfMeasurement();
        $businessPartnerArray = $purchaseRequestDetail->getBusinessPartner();
        if ($_POST['method'] == 'read') {
            $purchaseRequestDetail->setStart($offset);
            $purchaseRequestDetail->setLimit($limit); // normal system don't like paging..  
            $purchaseRequestDetail->setPageOutput('html');
            $purchaseRequestDetailArray = $purchaseRequestDetail->read();
            if (isset($purchaseRequestDetailArray [0]['firstRecord'])) {
                $firstRecord = $purchaseRequestDetailArray [0]['firstRecord'];
            }
            if (isset($purchaseRequestDetailArray [0]['nextRecord'])) {
                $nextRecord = $purchaseRequestDetailArray [0]['nextRecord'];
            }
            if (isset($purchaseRequestDetailArray [0]['previousRecord'])) {
                $previousRecord = $purchaseRequestDetailArray [0]['previousRecord'];
            }
            if (isset($purchaseRequestDetailArray [0]['lastRecord'])) {
                $lastRecord = $purchaseRequestDetailArray [0]['lastRecord'];
                $endRecord = $purchaseRequestDetailArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($purchaseRequestDetail->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($purchaseRequestDetailArray [0]['total'])) {
                $total = $purchaseRequestDetailArray [0]['total'];
            } else {
                $total = 0;
            }
            $navigation->setTotalRecord($total);
        }
    }
}
?><script type="text/javascript">
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
                    <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button> 
                    <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button> 
                    <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button> 
                    <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button> 
                    <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button> 
                    <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button> 
                    <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button> 
                    <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button> 
                    <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button> 
                    <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button> 
                    <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button> 
                    <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button> 
                    <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button> 
                    <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button> 
                    <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button> 
                    <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button> 
                    <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button> 
                    <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button> 
                    <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button> 
                    <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button> 
                    <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button> 
                    <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button> 
                    <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button> 
                    <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button> 
                    <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button> 
                    <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button> 
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="right" class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning" type="button">
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')">
                                        <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>Html
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

                            <label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                                                                             if (isset($_POST['query'])) {
                                                                                                 echo $_POST['query'];
                                                                                             }
                                                                                             ?>"><span class="input-group-addon">
                                    <img id="searchTextImage" src="./images/icons/magnifier.png">
                                </span>
                            </div>
                            <br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>         				</tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
                                </tr>
                            </table>
                            <div class="input-group"><input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                                                                             if (isset($_POST['dateRangeStart'])) {
                                                                                                 echo $_POST['dateRangeStart'];
                                                                                             }
                                                                                             ?>" onClick="topPage(125)"  placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><span class="input-group-addon">
                                    <img id="startDateImage" src="./images/icons/calendar.png">
                                </span>
                            </div><br>
                            <div class="input-group"><input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                        if (isset($_POST['dateRangeEnd'])) {
                            echo $_POST['dateRangeEnd'];
                        }
                        ?>" onClick="topPage(175)" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>"><span class="input-group-addon">
                                    <img id="endDateImage" src="./images/icons/calendar.png">
                                </span>
                            </div><br>
                            <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
                                        <input type="hidden" name="purchaseRequestDetailIdPreview" id="purchaseRequestDetailIdPreview">
                                        <div class="form-group" id="purchaseRequestIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestIdPreview"><?php echo $leafTranslation['purchaseRequestIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestIdPreview" id="purchaseRequestIdPreview">
                                            </div>					</div>					<div class="form-group" id="productIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="productIdPreview"><?php echo $leafTranslation['productIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="productIdPreview" id="productIdPreview">
                                            </div>					</div>					<div class="form-group" id="unitOfMeasurementIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="unitOfMeasurementIdPreview"><?php echo $leafTranslation['unitOfMeasurementIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="unitOfMeasurementIdPreview" id="unitOfMeasurementIdPreview">
                                            </div>					</div>					<div class="form-group" id="chartOfAccountIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                                            </div>					</div>					<div class="form-group" id="purchaseRequestDetailQuantityDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestDetailQuantityPreview"><?php echo $leafTranslation['purchaseRequestDetailQuantityLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestDetailQuantityPreview" id="purchaseRequestDetailQuantityPreview">
                                            </div>					</div>					<div class="form-group" id="purchaseRequestDetailDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestDetailDescriptionPreview"><?php echo $leafTranslation['purchaseRequestDetailDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestDetailDescriptionPreview" id="purchaseRequestDetailDescriptionPreview">
                                            </div>					</div>					<div class="form-group" id="purchaseRequestDetailEstimatedPriceDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestDetailEstimatedPricePreview"><?php echo $leafTranslation['purchaseRequestDetailEstimatedPriceLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestDetailEstimatedPricePreview" id="purchaseRequestDetailEstimatedPricePreview">
                                            </div>					</div>					<div class="form-group" id="purchaseRequestDetailBudgetDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="purchaseRequestDetailBudgetPreview"><?php echo $leafTranslation['purchaseRequestDetailBudgetLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="purchaseRequestDetailBudgetPreview" id="purchaseRequestDetailBudgetPreview">
                                            </div>					</div>     		</div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $purchaseRequestDetail->getControllerPath(); ?>', '<?php echo $purchaseRequestDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
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
                                    <th width="125px"><?php echo ucwords($leafTranslation['purchaseRequestIdLabel']); ?></th> 
                                    <th width="125px"><?php echo ucwords($leafTranslation['productIdLabel']); ?></th> 
                                    <th width="125px"><?php echo ucwords($leafTranslation['unitOfMeasurementIdLabel']); ?></th> 
                                    <th width="125px"><?php echo ucwords($leafTranslation['purchaseRequestDetailQuantityLabel']); ?></th> 
                                    <th><?php echo ucwords($leafTranslation['purchaseRequestDetailDescriptionLabel']); ?></th> 
                                    <th width="125px"><?php echo ucwords($leafTranslation['purchaseRequestDetailEstimatedPriceLabel']); ?></th> 
                                    <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th> 
                                    </tr> 
                                    </thead> 
                                    <tbody id="tableBody"> 
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($purchaseRequestDetailArray)) {
                                                $totalRecord = intval(count($purchaseRequestDetailArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                            if ($purchaseRequestDetailArray[$i]['isDelete'] == 1) {
                                                                echo "class=\"danger\"";
                                                            } else {
                                                                if ($purchaseRequestDetailArray[$i]['isDraft'] == 1) {
                                                                    echo "class=\"warning\"";
                                                                }
                                                            }
                                                            ?>>
                                                            <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>                       	<td vAlign="top" align="center"><div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="View" onClick="showModalDelete('<?php echo rawurlencode($purchaseRequestDetailArray [$i]['purchaseRequestDetailId']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['purchaseRequestDescription']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['productDescription']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['unitOfMeasurementDescription']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['chartOfAccountId']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['purchaseRequestDetailQuantity']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['purchaseRequestDetailDescription']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['purchaseRequestDetailEstimatedPrice']); ?>', '<?php echo rawurlencode($purchaseRequestDetailArray [$i]['purchaseRequestDetailBudget']); ?>')" value="Zoom
                                                                            "><i class="glyphicon glyphicon-zoom-in glyphicon-white"></i></button></div></td> 
                                                            <td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseRequestDetailArray[$i]['purchaseRequestDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseRequestDetailArray[$i]['purchaseRequestDescription'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestDetailArray[$i]['purchaseRequestDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['purchaseRequestDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseRequestDetailArray[$i]['purchaseRequestDescription'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestDetailArray[$i]['purchaseRequestDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['purchaseRequestDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestDetailArray[$i]['purchaseRequestDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseRequestDetailArray[$i]['purchaseRequestDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    &nbsp;
                                                                    <?php } ?>
                                                            </td>
                                                            <td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseRequestDetailArray[$i]['productDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseRequestDetailArray[$i]['productDescription'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestDetailArray[$i]['productDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['productDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseRequestDetailArray[$i]['productDescription'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestDetailArray[$i]['productDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['productDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestDetailArray[$i]['productDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseRequestDetailArray[$i]['productDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    &nbsp;
                                                                    <?php } ?>
                                                            </td>
                                                            <td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestDetailArray[$i]['unitOfMeasurementDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestDetailArray[$i]['unitOfMeasurementDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseRequestDetailArray[$i]['unitOfMeasurementDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                            <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>
                                                            </td>
                                                            <?php
                                                            $d = $purchaseRequestDetailArray[$i]['purchaseRequestDetailQuantity'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($purchaseRequestDetailArray[$i]['purchaseRequestDetailQuantity']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                            <td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription']);
                                                                                } else {
                                                                                    echo $purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $purchaseRequestDetailArray[$i]['purchaseRequestDetailDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                            <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>
                                                            </td>

                                                            <?php
                                                            $d = $purchaseRequestDetailArray[$i]['purchaseRequestDetailEstimatedPrice'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($purchaseRequestDetailArray[$i]['purchaseRequestDetailEstimatedPrice']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                            <td vAlign="top"  class="form-group" id="businessPartnerId<?php echo $purchaseRequestDetailArray[$i]['purchaseRequestDetailId']; ?>Detail">	<select name="businessPartnerId[]" id="businessPartnerId<?php echo $purchaseRequestDetailArray[$i]['purchaseRequestDetailId']; ?>" class="form-control chzn-select">
                                                                    <option value=""></option>
                                                                    <?php
                                                                    if (is_array($businessPartnerArray)) {
                                                                        $totalRecordPartner = intval(count($businessPartnerArray));
                                                                        if ($totalRecordPartner > 0) {
                                                                            $d = 1;
                                                                            $businessPartnerCategoryDescription = null;
                                                                            for ($b = 0; $b < $totalRecord; $b++) {
                                                                                if ($b != 0) {
                                                                                    if ($businessPartnerCategoryDescription != $businessPartnerArray[$b]['businessPartnerCategoryDescription']) {
                                                                                        echo "</optgroup><optgroup label=\"" . $businessPartnerArray[$b]['businessPartnerCategoryDescription'] . "\">";
                                                                                    }
                                                                                } else {
                                                                                    echo "<optgroup label=\"" . $businessPartnerArray[$b]['businessPartnerCategoryDescription'] . "\">";
                                                                                }
                                                                                $businessPartnerCategoryDescription = $businessPartnerArray[$b]['businessPartnerCategoryDescription'];
                                                                                if (isset($purchaseRequestDetailArray[0]['businessPartnerId'])) {
                                                                                    if ($purchaseRequestDetailArray[0]['businessPartnerId'] == $businessPartnerArray[$b]['businessPartnerId']) {
                                                                                        $selected = "selected";
                                                                                    } else {
                                                                                        $selected = NULL;
                                                                                    }
                                                                                } else {
                                                                                    $selected = NULL;
                                                                                }
                                                                                ?>
                                                                                <option
                                                                                    value="<?php echo $businessPartnerArray[$b]['businessPartnerId']; ?>"
                                    <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $businessPartnerArray[$b]['businessPartnerCompany']; ?></option>
                                                                                    <?php
                                                                                    $d++;
                                                                                }
                                                                                echo "</optgroup>\n";
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
                                                                </select>
                                                            </td>
                                                        </tr> 
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr> 
                                                        <td colspan="7" vAlign="top" align="center"><?php $purchaseRequestDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                    </tr> 
                                                <?php
                                                }
                                            } else {
                                                ?> 
                                                <tr> 
                                                    <td colspan="7" vAlign="top" align="center"><?php $purchaseRequestDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" vAlign="top" align="center"><?php $purchaseRequestDetail->exceptionMessage($t['loadFailureLabel']); ?></td> 
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
                    </div> 
                  
                </div>
            </div>
        </div>
          <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).scrollTop(0);
                            $(".chzn-select").chosen({search_contains: true});
                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
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
                        function toggleChecked(status) {
                            $('input:checkbox').each(function() {
                                $(this).attr('checked', status);
                            });
                        };
                    </script> 
    <?php }
}
?> 
<script type="text/javascript" src="./v3/financial/accountPayable/javascript/purchaseRequestDetail.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>