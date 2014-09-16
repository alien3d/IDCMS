  

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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceLedgerController.php");
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
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable('invoiceLedger');
if (isset($_POST['leafId'])) {
    $leafId = @intval($_POST['leafId'] * 1);
} else if (isset($_GET['leafId'])) {
    $leafId = @intval($_GET['leafId'] * 1);
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
$invoiceLedgerArray = array();
$businessPartnerArray = array();
$chartOfAccountArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoiceLedger = new \Core\Financial\AccountReceivable\InvoiceLedger\Controller\InvoiceLedgerClass();
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
            $invoiceLedger->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoiceLedger->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoiceLedger->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoiceLedger->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoiceLedger->setStartDay($start[2]);
            $invoiceLedger->setStartMonth($start[1]);
            $invoiceLedger->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoiceLedger->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoiceLedger->setEndDay($start[2]);
            $invoiceLedger->setEndMonth($start[1]);
            $invoiceLedger->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoiceLedger->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoiceLedger->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoiceLedger->setServiceOutput('html');
        $invoiceLedger->setLeafId($leafId);
        $invoiceLedger->execute();
        $businessPartnerArray = $invoiceLedger->getBusinessPartner();
        $chartOfAccountArray = $invoiceLedger->getChartOfAccount();
        if ($_POST['method'] == 'read') {
            $invoiceLedger->setStart($offset);
            $invoiceLedger->setLimit($limit); // normal system don't like paging..  
            $invoiceLedger->setPageOutput('html');
            $invoiceLedgerArray = $invoiceLedger->read();
            if (isset($invoiceLedgerArray [0]['firstRecord'])) {
                $firstRecord = $invoiceLedgerArray [0]['firstRecord'];
            }
            if (isset($invoiceLedgerArray [0]['nextRecord'])) {
                $nextRecord = $invoiceLedgerArray [0]['nextRecord'];
            }
            if (isset($invoiceLedgerArray [0]['previousRecord'])) {
                $previousRecord = $invoiceLedgerArray [0]['previousRecord'];
            }
            if (isset($invoiceLedgerArray [0]['lastRecord'])) {
                $lastRecord = $invoiceLedgerArray [0]['lastRecord'];
                $endRecord = $invoiceLedgerArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoiceLedger->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceLedgerArray [0]['total'])) {
                $total = $invoiceLedgerArray [0]['total'];
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
                    <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button> 
                    <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button> 
                    <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button> 
                    <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button> 
                    <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button> 
                    <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button> 
                    <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button> 
                    <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button> 
                    <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button> 
                    <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button> 
                    <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button> 
                    <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button> 
                    <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button> 
                    <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button> 
                    <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button> 
                    <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button> 
                    <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button> 
                    <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button> 
                    <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button> 
                    <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button> 
                    <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button> 
                    <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button> 
                    <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button> 
                    <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button> 
                    <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button> 
                    <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button> 
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
                                    <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')">
                                        <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')">
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
                            <br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            <table class="table table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>         				</tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
                                </tr>
                            </table>
                            <div class="input-group"><input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                                                                             if (isset($_POST['dateRangeStart'])) {
                                                                                                 echo $_POST['dateRangeStart'];
                                                                                             }
                                                                                             ?>" onClick="topPage(125)"  placeholder="<?php echo $t['startDateTextLabel']; ?>"><span class="input-group-addon">
                                    <img id="startDateImage" src="./images/icons/calendar.png">
                                </span>
                            </div><br>
                            <div class="input-group"><input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                        if (isset($_POST['dateRangeEnd'])) {
                            echo $_POST['dateRangeEnd'];
                        }
                        ?>" onClick="topPage(175)" placeholder="<?php echo $t['endDateTextLabel']; ?>"><span class="input-group-addon">
                                    <img id="endDateImage" src="./images/icons/calendar.png">
                                </span>
                            </div><br>
                            <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
                                        <input type="hidden" name="invoiceLedgerIdPreview" id="invoiceLedgerIdPreview">
                                        <div class="form-group" id="businessPartnerIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
                                            </div>					</div>					<div class="form-group" id="chartOfAccountIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceProjectIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceProjectIdPreview"><?php echo $leafTranslation['invoiceProjectIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceProjectIdPreview" id="invoiceProjectIdPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceIdPreview"><?php echo $leafTranslation['invoiceIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceIdPreview" id="invoiceIdPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceDebitNoteIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDebitNoteIdPreview"><?php echo $leafTranslation['invoiceDebitNoteIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDebitNoteIdPreview" id="invoiceDebitNoteIdPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceCreditNoteIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceCreditNoteIdPreview"><?php echo $leafTranslation['invoiceCreditNoteIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceCreditNoteIdPreview" id="invoiceCreditNoteIdPreview">
                                            </div>					</div>					<div class="form-group" id="collectionIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="collectionIdPreview"><?php echo $leafTranslation['collectionIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="collectionIdPreview" id="collectionIdPreview">
                                            </div>					</div>					<div class="form-group" id="documentNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="documentNumberPreview" id="documentNumberPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceLedgerDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceLedgerDatePreview"><?php echo $leafTranslation['invoiceLedgerDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceLedgerDatePreview" id="invoiceLedgerDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceDueDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDueDatePreview"><?php echo $leafTranslation['invoiceDueDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDueDatePreview" id="invoiceDueDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceLedgerAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceLedgerAmountPreview"><?php echo $leafTranslation['invoiceLedgerAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceLedgerAmountPreview" id="invoiceLedgerAmountPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceLedgerDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceLedgerDescriptionPreview"><?php echo $leafTranslation['invoiceLedgerDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceLedgerDescriptionPreview" id="invoiceLedgerDescriptionPreview">
                                            </div>					</div>					<div class="form-group" id="leafIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="leafIdPreview"><?php echo $leafTranslation['leafIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="leafIdPreview" id="leafIdPreview">
                                            </div>					</div>					<div class="form-group" id="leafNameDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="leafNamePreview"><?php echo $leafTranslation['leafNameLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="leafNamePreview" id="leafNamePreview">
                                            </div>					</div>     		</div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
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
                                            <th width="25px" align="center"><div align="center">#</div></th>
                                    <th><?php echo ucwords($leafTranslation['invoiceLedgerDescriptionLabel']); ?></th> 
                                    <th width="75px"><?php echo ucwords($t['debitTextLabel']); ?></th> 
                                    <th width="75px"><?php echo ucwords($t['creditTextLabel']); ?></th> 
                                    <th width="75px"><?php echo ucwords($leafTranslation['invoiceLedgerAmountLabel']); ?></th> 

                                    </tr> 
                                    </thead> 
                                    <tbody id="tableBody"> 
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($invoiceLedgerArray)) {
                                                $totalRecord = intval(count($invoiceLedgerArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        $debit = 0;
                                                        $credit = 0;
                                                        ?>
                                                        <tr>
                                                            <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>  
                                                            <td colspan="3"><div align="left"><strong>
                                                                        <?php
                                                                        if (isset($invoiceLedgerArray[$i]['businessPartnerCompany'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos($invoiceLedgerArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                        echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceLedgerArray[$i]['businessPartnerCompany']);
                                                                                    } else {
                                                                                        echo $invoiceLedgerArray[$i]['businessPartnerCompany'];
                                                                                    }
                                                                                } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos($invoiceLedgerArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                        echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceLedgerArray[$i]['businessPartnerCompany']);
                                                                                    } else {
                                                                                        echo $invoiceLedgerArray[$i]['businessPartnerCompany'];
                                                                                    }
                                                                                } else {
                                                                                    echo $invoiceLedgerArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceLedgerArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                            ?></strong>
                                                                    </div>
                                                                <?php } ?> ( 
                                                                <?php
                                                                if (isset($invoiceLedgerArray[$i]['chartOfAccountTitle'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceLedgerArray[$i]['chartOfAccountTitle'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceLedgerArray[$i]['chartOfAccountTitle']);
                                                                            } else {
                                                                                echo $invoiceLedgerArray[$i]['chartOfAccountTitle'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceLedgerArray[$i]['chartOfAccountTitle'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceLedgerArray[$i]['chartOfAccountTitle']);
                                                                            } else {
                                                                                echo $invoiceLedgerArray[$i]['chartOfAccountNumber'] . " - " . $invoiceLedgerArray[$i]['chartOfAccountTitle'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceLedgerArray[$i]['chartOfAccountNumber'] . " - " . $invoiceLedgerArray[$i]['chartOfAccountTitle'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceLedgerArray[$i]['chartOfAccountNumber'] . " - " . $invoiceLedgerArray[$i]['chartOfAccountTitle'];
                                                                    }
                                                                    ?>

                                                            <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>)
                                                            </td>
                                                            <?php
                                                            if (isset($invoiceLedgerArray[$i]['invoiceLedgerDate'])) {
                                                                $valueArray = $invoiceLedgerArray[$i]['invoiceLedgerDate'];
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
                                                        </tr>
                                                        <tr >


                                                            <td colspan="2"><div align="left">
                                                                    <?php
                                                                    if (isset($invoiceLedgerArray[$i]['invoiceLedgerDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($invoiceLedgerArray[$i]['invoiceLedgerDescription']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceLedgerArray[$i]['invoiceLedgerDescription']);
                                                                                } else {
                                                                                    echo $invoiceLedgerArray[$i]['invoiceLedgerDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($invoiceLedgerArray[$i]['invoiceLedgerDescription']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceLedgerArray[$i]['invoiceLedgerDescription']);
                                                                                } else {
                                                                                    echo $invoiceLedgerArray[$i]['invoiceLedgerDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $invoiceLedgerArray[$i]['invoiceLedgerDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceLedgerArray[$i]['invoiceLedgerDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                            <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>
                                                            </td>


                                                            <?php
                                                            $d = $invoiceLedgerArray[$i]['invoiceLedgerAmount'];
                                                            $totalAll+=$d;
                                                            if ($d > 0) {
                                                                $debit = $d;
                                                                $totalDebit+=$d;
                                                            } else {
                                                                $credit = $d;
                                                                $totalCredit+=$d;
                                                            }
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($invoiceLedgerArray[$i]['invoiceLedgerAmount']);
                                                                    $debit = $a->format($debit);
                                                                    $credit = $a->format($credit);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td>&nbsp;<?php echo $debit; ?></td>
                                                            <td>&nbsp;<?php echo $credit; ?></td>
                                                            <td>&nbsp;<?php echo $d; ?></td>
                                                        </tr> 
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr> 
                                                        <td colspan="7" vAlign="top" align="center"><?php $invoiceLedger->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                    </tr> 
                                                <?php
                                                }
                                            } else {
                                                ?> 
                                                <tr> 
                                                    <td colspan="7" vAlign="top" align="center"><?php $invoiceLedger->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" vAlign="top" align="center"><?php $invoiceLedger->exceptionMessage($t['loadFailureLabel']); ?></td> 
                                            </tr> 
                                        <?php
                                    }
                                    ?> 
                                    </tbody> 
                                    <?php
                                    if (class_exists('NumberFormatter')) {
                                        if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                            $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                            $totalAll = $a->format($totalAll);
                                            $totalDebit = $a->format($totalDebit);
                                            $totalCredit = $a->format($totalCredit);
                                            //
                                        } else {
                                            $totalDebit = number_format($totalDebit) . " You can assign Currency Format ";
                                            $totalCredit = number_format($totalCredit) . " You can assign Currency Format ";
                                        }
                                    } else {
                                        $totalDebit = number_format($totalDebit);
                                        $totalCredit = number_format($totalCredit);
                                        //$totalAll= number_format($totalDebit-$totalCredit);
                                    }
                                    ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">&nbsp;</td>
                                        </tr>
                                        <tr class="success">
                                            <td colspan="2"><strong><?php echo $t['totalTextLabel']; ?></strong></td>
                                            <td><?php echo $totalDebit; ?></td>
                                            <td><?php echo $totalCredit; ?></td>
                                            <td><?php echo $totalAll; ?></td>
                                        </tr>
                                    </tfoot>
                                </table> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 pull-left" align="left">
        <?php $navigation->pagenationv4($offset); ?>
                        </div>
                    </div> 
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(document).scrollTop(0);
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
                        }
                    </script> 
                </div>
            </div>
        </div>
    <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?> 
    <form class="form-horizontal">		<input type="hidden" name="invoiceLedgerId" id="invoiceLedgerId" value="<?php
            if (isset($_POST['invoiceLedgerId'])) {
                echo $_POST['invoiceLedgerId'];
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
                            <div align="right">
                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                </div>
                            </div>
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
                                                            if (isset($invoiceLedgerArray[0]['businessPartnerId'])) {
                                                                if ($invoiceLedgerArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
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
                                            <span class="help-block" id="businessPartnerIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="chartOfAccountIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="chartOfAccountId"><strong><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="chartOfAccountId" id="chartOfAccountId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($chartOfAccountArray)) {
                                                    $totalRecord = intval(count($chartOfAccountArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceLedgerArray[0]['chartOfAccountId'])) {
                                                                if ($invoiceLedgerArray[0]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option> 
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
                                            <span class="help-block" id="chartOfAccountIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="documentNumber"><strong><?php echo ucfirst($leafTranslation['documentNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                       <?php
                                                       if (!isset($_POST['invoiceLedgerId'])) {
                                                           echo "disabled";
                                                       }
                                                       ?>
                                                       class=" form-control  <?php
                                                       if (!isset($_POST['invoiceLedgerId'])) {
                                                           echo "disabled";
                                                       }
                                                       ?>"
                                                       value="<?php
                                    if (isset($invoiceLedgerArray[0]['documentNumber'])) {
                                        if (isset($invoiceLedgerArray[0]['documentNumber'])) {
                                            echo htmlentities($invoiceLedgerArray[0]['documentNumber']);
                                        }
                                    }
                                    ?>"><span class="input-group-addon"><img src="./images/icons/document-number.png"></span></div>
                                            <span class="help-block" id="documentNumberHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    if (isset($invoiceLedgerArray) && is_array($invoiceLedgerArray)) {

                                        if (isset($invoiceLedgerArray[0]['invoiceLedgerDate'])) {
                                            $valueArray = $invoiceLedgerArray[0]['invoiceLedgerDate'];
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
                                    ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceLedgerDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceLedgerDate"><strong><?php echo ucfirst($leafTranslation['invoiceLedgerDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceLedgerDate" id="invoiceLedgerDate" value="<?php
                                    if (isset($value)) {
                                        echo $value;
                                    }
                                    ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceLedgerDateImage"></span></div>
                                            <span class="help-block" id="invoiceLedgerDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($invoiceLedgerArray) && is_array($invoiceLedgerArray)) {

                                        if (isset($invoiceLedgerArray[0]['invoiceDueDate'])) {
                                            $valueArray = $invoiceLedgerArray[0]['invoiceDueDate'];
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
                                    ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceDueDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDueDate"><strong><?php echo ucfirst($leafTranslation['invoiceDueDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceDueDate" id="invoiceDueDate" value="<?php
                                    if (isset($value)) {
                                        echo $value;
                                    }
                                    ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceDueDateImage"></span></div>
                                            <span class="help-block" id="invoiceDueDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceLedgerAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceLedgerAmount"><strong><?php echo ucfirst($leafTranslation['invoiceLedgerAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceLedgerAmount" id="invoiceLedgerAmount"
                                                       value="<?php
                                    if (isset($invoiceLedgerArray[0]['invoiceLedgerAmount'])) {
                                        if (isset($invoiceLedgerArray[0]['invoiceLedgerAmount'])) {
                                            echo htmlentities($invoiceLedgerArray[0]['invoiceLedgerAmount']);
                                        }
                                    }
                                    ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="invoiceLedgerAmountHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceLedgerDescriptionForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceLedgerDescription"><strong><?php echo ucfirst($leafTranslation['invoiceLedgerDescriptionLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea class="form-control" name="invoiceLedgerDescription" id="invoiceLedgerDescription" onKeyUp="removeMeError('invoiceLedgerDescription')"><?php
                                            if (isset($invoiceLedgerArray[0]['invoiceLedgerDescription'])) {

                                                echo htmlentities($invoiceLedgerArray[0]['invoiceLedgerDescription']);
                                            }
                                            ?></textarea>
                                            <span class="help-block" id="invoiceLedgerDescriptionHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="leafIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="leafId"><strong><?php echo ucfirst($leafTranslation['leafIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="leafId" id="leafId"
                                                       value="<?php
                                            if (isset($invoiceLedgerArray[0]['leafId'])) {
                                                if (isset($invoiceLedgerArray[0]['leafId'])) {
                                                    echo htmlentities($invoiceLedgerArray[0]['leafId']);
                                                }
                                            }
                                            ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="leafIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="leafNameForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="leafName"><strong><?php echo ucfirst($leafTranslation['leafNameLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="leafName" id="leafName" onKeyUp="removeMeError('leafName')"  value="<?php
                                            if (isset($invoiceLedgerArray) && is_array($invoiceLedgerArray)) {
                                                if (isset($invoiceLedgerArray[0]['leafName'])) {
                                                    echo htmlentities($invoiceLedgerArray[0]['leafName']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="leafNameHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                </div>
                            </div>
                        </div><div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
                                <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class=caret></span></a> 
                                <ul class="dropdown-menu"> 
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li> 
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li> 
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];   ?></a> </li>--> 
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];   ?></a> </li>--> 
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li> 
                                </ul> 
                            </div> 
                            <div class="btn-group" align="left">
                                <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> 
                                <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a> 
                                <ul class="dropdown-menu"> 
                                    <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a> </li> 
                                 <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];   ?></a> </li> -->
                                    <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?></a> </li> 
                                </ul> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>, '<?php echo $invoiceLedger->getControllerPath(); ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoiceLedger->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
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
                    </div></div></div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $(document).scrollTop(0);
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('invoiceLedgerId');
                    validateMeNumeric('businessPartnerId');
                    validateMeNumeric('chartOfAccountId');
                    validateMeNumeric('invoiceProjectId');
                    validateMeNumeric('invoiceId');
                    validateMeNumeric('invoiceDebitNoteId');
                    validateMeNumeric('invoiceCreditNoteId');
                    validateMeNumeric('collectionId');
                    validateMeAlphaNumeric('documentNumber');
                    $('#invoiceLedgerDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    $('#invoiceDueDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    validateMeNumeric('invoiceLedgerAmount');
                    validateMeAlphaNumeric('invoiceLedgerDescription');
                    validateMeNumeric('leafId');
                    validateMeAlphaNumeric('leafName');
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton1').attr('onClick', '');
                        $('#updateRecordButton2').attr('onClick', '');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                        $('#firstRecordButton').removeClass().addClass('btn btn-default');
                        $('#endRecordButton').removeClass().addClass('btn btn-default');
    <?php } else if ($_POST['invoiceLedgerId']) { ?>
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
                                    ;
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoiceLedger->getControllerPath(); ?>','<?php echo $invoiceLedger->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
                });
            </script> 
<?php } ?> 
    </div>
</form>
<script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoiceLedger.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>