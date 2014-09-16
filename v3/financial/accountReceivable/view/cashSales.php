  

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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceController.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/controller/invoiceDetailController.php");
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
$translator->setCurrentTable(array('invoice', 'invoiceDetail'));
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
$invoiceArray = array();
$businessPartnerArray = array();
$businessPartnerContactArray = array();
$countryArray = array();
$invoiceProjectArray = array();
$paymentTermArray = array();
$invoiceProcessArray = array();
$_GET['from'] = 'cashSales.php';
$_POST['from'] = 'cashSales.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $invoice = new \Core\Financial\AccountReceivable\Invoice\Controller\InvoiceClass();
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
            $invoice->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $invoice->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $invoice->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $invoice->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $invoice->setStartDay($start[2]);
            $invoice->setStartMonth($start[1]);
            $invoice->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $invoice->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $invoice->setEndDay($start[2]);
            $invoice->setEndMonth($start[1]);
            $invoice->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $invoice->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $invoice->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $invoice->setServiceOutput('html');
        $invoice->setLeafId($leafId);
        $invoice->execute();
        $businessPartnerArray = $invoice->getBusinessPartner();
        $businessPartnerContactArray = $invoice->getBusinessPartnerContact();
        $countryArray = $invoice->getCountry();
        $invoiceProjectArray = $invoice->getInvoiceProject();
        $paymentTermArray = $invoice->getPaymentTerm();
        $invoiceProcessArray = $invoice->getInvoiceProcess();
        if ($_POST['method'] == 'read') {
            $invoice->setStart($offset);
            $invoice->setLimit($limit); // normal system don't like paging..  
            $invoice->setPageOutput('html');
            $invoiceArray = $invoice->read();
            if (isset($invoiceArray [0]['firstRecord'])) {
                $firstRecord = $invoiceArray [0]['firstRecord'];
            }
            if (isset($invoiceArray [0]['nextRecord'])) {
                $nextRecord = $invoiceArray [0]['nextRecord'];
            }
            if (isset($invoiceArray [0]['previousRecord'])) {
                $previousRecord = $invoiceArray [0]['previousRecord'];
            }
            if (isset($invoiceArray [0]['lastRecord'])) {
                $lastRecord = $invoiceArray [0]['lastRecord'];
                $endRecord = $invoiceArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($invoice->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($invoiceArray [0]['total'])) {
                $total = $invoiceArray [0]['total'];
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
                    <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button> 
                    <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button> 
                    <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button> 
                    <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button> 
                    <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button> 
                    <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button> 
                    <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button> 
                    <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button> 
                    <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button> 
                    <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button> 
                    <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button> 
                    <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button> 
                    <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button> 
                    <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button> 
                    <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button> 
                    <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button> 
                    <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button> 
                    <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button> 
                    <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button> 
                    <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button> 
                    <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button> 
                    <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button> 
                    <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button> 
                    <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button> 
                    <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button> 
                    <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button> 
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
                                    <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')">
                                        <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')">
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
                            <div id="btnList">
                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button> 
                            </div>
                            <label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                                                                             if (isset($_POST['query'])) {
                                                                                                 echo $_POST['query'];
                                                                                             }
                                                                                             ?>"><span class="input-group-addon">
                                    <img id="searchTextImage" src="./images/icons/magnifier.png">
                                </span>
                            </div>
                            <br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            <table class="table table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>         				</tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
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
                            <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
                                    
                                        <input type="hidden" name="invoiceIdPreview" id="invoiceIdPreview">
                                        <div class="form-group" id="businessPartnerIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerIdPreview"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerIdPreview" id="businessPartnerIdPreview">
                                            </div>					</div>					<div class="form-group" id="businessPartnerContactIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerContactIdPreview"><?php echo $leafTranslation['businessPartnerContactIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerContactIdPreview" id="businessPartnerContactIdPreview">
                                            </div>					</div>					<div class="form-group" id="countryIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="countryIdPreview" id="countryIdPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceProjectIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceProjectIdPreview"><?php echo $leafTranslation['invoiceProjectIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceProjectIdPreview" id="invoiceProjectIdPreview">
                                            </div>					</div>					<div class="form-group" id="paymentTermIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="paymentTermIdPreview"><?php echo $leafTranslation['paymentTermIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="paymentTermIdPreview" id="paymentTermIdPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceProcessIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceProcessIdPreview"><?php echo $leafTranslation['invoiceProcessIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceProcessIdPreview" id="invoiceProcessIdPreview">
                                            </div>					</div>					<div class="form-group" id="businessPartnerAddressDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="businessPartnerAddressPreview"><?php echo $leafTranslation['businessPartnerAddressLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="businessPartnerAddressPreview" id="businessPartnerAddressPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceQuotationNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceQuotationNumberPreview"><?php echo $leafTranslation['invoiceQuotationNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceQuotationNumberPreview" id="invoiceQuotationNumberPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceNumberPreview"><?php echo $leafTranslation['invoiceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceNumberPreview" id="invoiceNumberPreview">
                                            </div>					</div>					<div class="form-group" id="referenceNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="referenceNumberPreview"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="referenceNumberPreview" id="referenceNumberPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceCodePreview"><?php echo $leafTranslation['invoiceCodeLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceCodePreview" id="invoiceCodePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceTotalAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceTotalAmountPreview"><?php echo $leafTranslation['invoiceTotalAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceTotalAmountPreview" id="invoiceTotalAmountPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceTextAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceTextAmountPreview"><?php echo $leafTranslation['invoiceTextAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceTextAmountPreview" id="invoiceTextAmountPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceTaxAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceTaxAmountPreview"><?php echo $leafTranslation['invoiceTaxAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceTaxAmountPreview" id="invoiceTaxAmountPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceDiscountAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDiscountAmountPreview"><?php echo $leafTranslation['invoiceDiscountAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDiscountAmountPreview" id="invoiceDiscountAmountPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceShippingAmountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceShippingAmountPreview"><?php echo $leafTranslation['invoiceShippingAmountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceShippingAmountPreview" id="invoiceShippingAmountPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceInterestRateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceInterestRatePreview"><?php echo $leafTranslation['invoiceInterestRateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceInterestRatePreview" id="invoiceInterestRatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDatePreview"><?php echo $leafTranslation['invoiceDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDatePreview" id="invoiceDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceStartDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceStartDatePreview"><?php echo $leafTranslation['invoiceStartDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceStartDatePreview" id="invoiceStartDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceEndDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceEndDatePreview"><?php echo $leafTranslation['invoiceEndDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceEndDatePreview" id="invoiceEndDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceDueDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDueDatePreview"><?php echo $leafTranslation['invoiceDueDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDueDatePreview" id="invoiceDueDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoicePromiseDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoicePromiseDatePreview"><?php echo $leafTranslation['invoicePromiseDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoicePromiseDatePreview" id="invoicePromiseDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoiceShippingDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceShippingDatePreview"><?php echo $leafTranslation['invoiceShippingDateLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceShippingDatePreview" id="invoiceShippingDatePreview">
                                            </div>					</div>					<div class="form-group" id="invoicePeriodDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoicePeriodPreview"><?php echo $leafTranslation['invoicePeriodLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoicePeriodPreview" id="invoicePeriodPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceDescriptionPreview"><?php echo $leafTranslation['invoiceDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceDescriptionPreview" id="invoiceDescriptionPreview">
                                            </div>					</div>					<div class="form-group" id="invoiceRemarkDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceRemarkPreview"><?php echo $leafTranslation['invoiceRemarkLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="invoiceRemarkPreview" id="invoiceRemarkPreview">
                                            </div>					</div>     		</div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class ="table table-striped table-condensed table-hover" id="tableData"> 
                                <thead> 
                                    <tr> 
                                        <th width="25px" align="center"><div align="center">#</div></th>
                                <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerContactIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['countryIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceProjectIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['paymentTermIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceProcessIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['businessPartnerAddressLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceQuotationNumberLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceNumberLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['referenceNumberLabel']); ?></th> 
                                <th width="75px"><div align="center"><?php echo ucwords($leafTranslation['invoiceCodeLabel']); ?></div></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceTotalAmountLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceTextAmountLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceTaxAmountLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceDiscountAmountLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceShippingAmountLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceInterestRateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceDateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceStartDateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceEndDateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceDueDateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoicePromiseDateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceShippingDateLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoicePeriodLabel']); ?></th> 
                                <th><?php echo ucwords($leafTranslation['invoiceDescriptionLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceRemarkLabel']); ?></th> 
                                <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                                <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                                <th width="25px"><label for="check_all"></label><input class="form-control" type="checkbox" name="check_all" id="check_all" alt="Check Record" onClick="toggleChecked(this.checked)"></th>
                                </tr> 
                                </thead> 
                                <tbody id="tableBody"> 
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($invoiceArray)) {
                                            $totalRecord = intval(count($invoiceArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
                                                    <tr <?php
                                                        if ($invoiceArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($invoiceArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                        <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>                       	<td vAlign="top" align="center"><div class="btn-group" align="center">
                                                                <button type="button"  class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($invoiceArray [$i]['invoiceId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="Edit"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete('<?php echo rawurlencode($invoiceArray [$i]['invoiceId']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['businessPartnerCompany']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['businessPartnerContactDescription']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['countryDescription']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceProjectDescription']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['paymentTermDescription']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceProcessDescription']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['businessPartnerAddress']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceQuotationNumber']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceNumber']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['referenceNumber']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceCode']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceTotalAmount']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceTextAmount']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceTaxAmount']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceDiscountAmount']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceShippingAmount']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceInterestRate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceDate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceStartDate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceEndDate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceDueDate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoicePromiseDate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceShippingDate']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoicePeriod']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceDescription']); ?>', '<?php echo rawurlencode($invoiceArray [$i]['invoiceRemark']); ?>')" value="Delete"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button></div></td> 
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['businessPartnerCompany'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['businessPartnerCompany'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['businessPartnerCompany']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['businessPartnerCompany'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['businessPartnerCompany']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['businessPartnerCompany'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['businessPartnerCompany'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['businessPartnerCompany'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['businessPartnerContactDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['businessPartnerContactDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['businessPartnerContactDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['businessPartnerContactDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['businessPartnerContactDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['businessPartnerContactDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['businessPartnerContactDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['businessPartnerContactDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['businessPartnerContactDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['countryDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['countryDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['countryDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['countryDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['countryDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['countryDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['countryDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['countryDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['countryDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceProjectDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['invoiceProjectDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceProjectDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceProjectDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['invoiceProjectDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceProjectDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceProjectDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceProjectDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceProjectDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['paymentTermDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['paymentTermDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['paymentTermDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['paymentTermDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['paymentTermDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['paymentTermDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['paymentTermDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['paymentTermDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['paymentTermDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceProcessDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['invoiceProcessDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceProcessDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceProcessDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['invoiceProcessDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceProcessDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceProcessDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceProcessDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceProcessDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['businessPartnerAddress'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['businessPartnerAddress']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['businessPartnerAddress']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['businessPartnerAddress'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['businessPartnerAddress']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['businessPartnerAddress']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['businessPartnerAddress'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['businessPartnerAddress'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['businessPartnerAddress'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceQuotationNumber'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceQuotationNumber']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceQuotationNumber']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceQuotationNumber'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceQuotationNumber']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceQuotationNumber']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceQuotationNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceQuotationNumber'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceQuotationNumber'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceNumber'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceNumber']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceNumber']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceNumber'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceNumber']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceNumber']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceNumber'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceNumber'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['referenceNumber'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['referenceNumber']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['referenceNumber']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['referenceNumber'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['referenceNumber']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['referenceNumber']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['referenceNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['referenceNumber'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['referenceNumber'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="center">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceCode'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceCode']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceCode']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceCode'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceCode']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceCode']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceCode'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceCode'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceCode'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                        <?php } else { ?>
                                                                &nbsp;
                                                        <?php } ?>
                                                        </td>
                                                        <?php
                                                        $d = $invoiceArray[$i]['invoiceTotalAmount'];
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($invoiceArray[$i]['invoiceTotalAmount']);
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
                                                                if (isset($invoiceArray[$i]['invoiceTextAmount'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceTextAmount']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceTextAmount']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceTextAmount'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceTextAmount']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceTextAmount']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceTextAmount'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceTextAmount'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceTextAmount'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                        <?php } else { ?>
                                                                &nbsp;
                                                        <?php } ?>
                                                        </td>
                                                        <?php
                                                        $d = $invoiceArray[$i]['invoiceTaxAmount'];
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($invoiceArray[$i]['invoiceTaxAmount']);
                                                            } else {
                                                                $d = number_format($d) . " You can assign Currency Format ";
                                                            }
                                                        } else {
                                                            $d = number_format($d);
                                                        }
                                                        ?>
                                                        <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                        <?php
                                                        $d = $invoiceArray[$i]['invoiceDiscountAmount'];
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($invoiceArray[$i]['invoiceDiscountAmount']);
                                                            } else {
                                                                $d = number_format($d) . " You can assign Currency Format ";
                                                            }
                                                        } else {
                                                            $d = number_format($d);
                                                        }
                                                        ?>
                                                        <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                        <?php
                                                        $d = $invoiceArray[$i]['invoiceShippingAmount'];
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($invoiceArray[$i]['invoiceShippingAmount']);
                                                            } else {
                                                                $d = number_format($d) . " You can assign Currency Format ";
                                                            }
                                                        } else {
                                                            $d = number_format($d);
                                                        }
                                                        ?>
                                                        <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                        <?php
                                                        $d = $invoiceArray[$i]['invoiceInterestRate'];
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($invoiceArray[$i]['invoiceInterestRate']);
                                                            } else {
                                                                $d = number_format($d) . " You can assign Currency Format ";
                                                            }
                                                        } else {
                                                            $d = number_format($d);
                                                        }
                                                        ?>
                                                        <td vAlign="top"><div align="right"><?php echo$d; ?></div></td>
                                                        <?php
                                                        if (isset($invoiceArray[$i]['invoiceDate'])) {
                                                            $valueArray = $invoiceArray[$i]['invoiceDate'];
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
                                                        <?php
                                                        if (isset($invoiceArray[$i]['invoiceStartDate'])) {
                                                            $valueArray = $invoiceArray[$i]['invoiceStartDate'];
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
                                                        <?php
                                                        if (isset($invoiceArray[$i]['invoiceEndDate'])) {
                                                            $valueArray = $invoiceArray[$i]['invoiceEndDate'];
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
                                                        <?php
                                                        if (isset($invoiceArray[$i]['invoiceDueDate'])) {
                                                            $valueArray = $invoiceArray[$i]['invoiceDueDate'];
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
                                                        <?php
                                                        if (isset($invoiceArray[$i]['invoicePromiseDate'])) {
                                                            $valueArray = $invoiceArray[$i]['invoicePromiseDate'];
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
                                                        <?php
                                                        if (isset($invoiceArray[$i]['invoiceShippingDate'])) {
                                                            $valueArray = $invoiceArray[$i]['invoiceShippingDate'];
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
                                                                if (isset($invoiceArray[$i]['invoicePeriod'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoicePeriod']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoicePeriod']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoicePeriod'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoicePeriod']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoicePeriod']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoicePeriod'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoicePeriod'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoicePeriod'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceDescription']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceDescription']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceDescription']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top"><div align="left">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['invoiceRemark'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceRemark']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['invoiceRemark']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceRemark'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($invoiceArray[$i]['invoiceRemark']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['invoiceRemark']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['invoiceRemark'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['invoiceRemark'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['invoiceRemark'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                &nbsp;
                                                                <?php } ?>
                                                        </td>
                                                        <td vAlign="top" align="center"><div align="center">
                                                                <?php
                                                                if (isset($invoiceArray[$i]['executeBy'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $invoiceArray[$i]['staffName']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['staffName'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($invoiceArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $invoiceArray[$i]['staffName']);
                                                                            } else {
                                                                                echo $invoiceArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $invoiceArray[$i]['staffName'];
                                                                        }
                                                                    } else {
                                                                        echo $invoiceArray[$i]['staffName'];
                                                                    }
                                                                    ?>
                                                        <?php } else { ?>
                                                                    &nbsp;
                                                        <?php } ?>
                                                            </div></td>
                                                        <?php
                                                        if (isset($invoiceArray[$i]['executeTime'])) {
                                                            $valueArray = $invoiceArray[$i]['executeTime'];
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
                                                                $value = date($systemFormat['systemSettingDateFormat'] . " " . $systemFormat['systemSettingTimeFormat'], mktime($hour, $minute, $second, $month, $day, $year));
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td vAlign="top"><?php echo $value; ?></td> 
                                                        <?php } else { ?>
                                                            <td>&nbsp;</td> 
                                                        <?php } ?>
                        <?php
                        if ($invoiceArray[$i]['isDelete']) {
                            $checked = "checked";
                        } else {
                            $checked = NULL;
                        }
                        ?>
                                                        <td vAlign="top">
                                                            <input class="form-control" style="display:none;" type="checkbox" name="invoiceId[]"  value="<?php echo $invoiceArray[$i]['invoiceId']; ?>">
                                                            <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $invoiceArray[$i]['isDelete']; ?>">

                                                        </td>
                                                    </tr> 
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr> 
                                                    <td colspan="7" vAlign="top" align="center"><?php $invoice->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                            <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" vAlign="top" align="center"><?php $invoice->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                            </tr> 
                                            <?php
                                        }
                                    } else {
                                        ?> 
                                        <tr> 
                                            <td colspan="7" vAlign="top" align="center"><?php $invoice->exceptionMessage($t['loadFailureLabel']); ?></td> 
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
                            <button type="button"  class="delete btn btn-warning" onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> 
                                <i class="glyphicon glyphicon-white glyphicon-trash"></i> 
                            </button> 
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
    <?php
    $invoiceDetail = new \Core\Financial\AccountReceivable\InvoiceDetail\Controller\InvoiceDetailClass();
    $invoiceDetail->setServiceOutput('html');
    $invoiceDetail->setLeafId($leafId);
    $invoiceDetail->execute();
    $productArray = $invoiceDetail->getProduct();
    $unitOfMeasurementArray = $invoiceDetail->getUnitOfMeasurement();
    $discountArray = $invoiceDetail->getDiscount();
    $taxArray = $invoiceDetail->getTax();
    $invoiceDetail->setStart(0);
    $invoiceDetail->setLimit(999999); // normal system don't like paging..  
    $invoiceDetail->setPageOutput('html');
    if ($_POST['invoiceId']) {
        $invoiceDetailArray = $invoiceDetail->read();
    }
    ?>
    <form class="form-horizontal">		<input type="hidden" name="invoiceId" id="invoiceId" value="<?php
            if (isset($_POST['invoiceId'])) {
                echo $_POST['invoiceId'];
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
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $invoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>', '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $invoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
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
                                                            if (isset($invoiceArray[0]['businessPartnerId'])) {
                                                                if ($invoiceArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
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
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="businessPartnerContactIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="businessPartnerContactId"><strong><?php echo ucfirst($leafTranslation['businessPartnerContactIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="businessPartnerContactId" id="businessPartnerContactId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($businessPartnerContactArray)) {
                                                    $totalRecord = intval(count($businessPartnerContactArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceArray[0]['businessPartnerContactId'])) {
                                                                if ($invoiceArray[0]['businessPartnerContactId'] == $businessPartnerContactArray[$i]['businessPartnerContactId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $businessPartnerContactArray[$i]['businessPartnerContactId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $businessPartnerContactArray[$i]['businessPartnerContactDescription']; ?></option> 
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
                                            <span class="help-block" id="businessPartnerContactIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="countryIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="countryId"><strong><?php echo ucfirst($leafTranslation['countryIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="countryId" id="countryId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($countryArray)) {
                                                    $totalRecord = intval(count($countryArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceArray[0]['countryId'])) {
                                                                if ($invoiceArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $countryArray[$i]['countryDescription']; ?></option> 
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
                                            <span class="help-block" id="countryIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceProjectIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceProjectId"><strong><?php echo ucfirst($leafTranslation['invoiceProjectIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="invoiceProjectId" id="invoiceProjectId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($invoiceProjectArray)) {
                                                    $totalRecord = intval(count($invoiceProjectArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceArray[0]['invoiceProjectId'])) {
                                                                if ($invoiceArray[0]['invoiceProjectId'] == $invoiceProjectArray[$i]['invoiceProjectId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $invoiceProjectArray[$i]['invoiceProjectId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $invoiceProjectArray[$i]['invoiceProjectDescription']; ?></option> 
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
                                            <span class="help-block" id="invoiceProjectIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="paymentTermIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="paymentTermId"><strong><?php echo ucfirst($leafTranslation['paymentTermIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="paymentTermId" id="paymentTermId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($paymentTermArray)) {
                                                    $totalRecord = intval(count($paymentTermArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceArray[0]['paymentTermId'])) {
                                                                if ($invoiceArray[0]['paymentTermId'] == $paymentTermArray[$i]['paymentTermId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $paymentTermArray[$i]['paymentTermId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $paymentTermArray[$i]['paymentTermDescription']; ?></option> 
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
                                            <span class="help-block" id="paymentTermIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceProcessIdForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceProcessId"><strong><?php echo ucfirst($leafTranslation['invoiceProcessIdLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="invoiceProcessId" id="invoiceProcessId" class="form-control  chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($invoiceProcessArray)) {
                                                    $totalRecord = intval(count($invoiceProcessArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($invoiceArray[0]['invoiceProcessId'])) {
                                                                if ($invoiceArray[0]['invoiceProcessId'] == $invoiceProcessArray[$i]['invoiceProcessId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $invoiceProcessArray[$i]['invoiceProcessId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $invoiceProcessArray[$i]['invoiceProcessDescription']; ?></option> 
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
                                            <span class="help-block" id="invoiceProcessIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="businessPartnerAddressForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="businessPartnerAddress"><strong><?php echo ucfirst($leafTranslation['businessPartnerAddressLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea class="form-control" name="businessPartnerAddress" id="businessPartnerAddress" onKeyUp="removeMeError('businessPartnerAddress')">
                                                   <?php
                                                   if (isset($invoiceArray[0]['businessPartnerAddress'])) {

                                                       echo htmlentities($invoiceArray[0]['businessPartnerAddress']);
                                                   }
                                                   ?>
                                            </textarea>
                                            <span class="help-block" id="businessPartnerAddressHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceQuotationNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceQuotationNumber"><strong><?php echo ucfirst($leafTranslation['invoiceQuotationNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="invoiceQuotationNumber" id="invoiceQuotationNumber" onKeyUp="removeMeError('invoiceQuotationNumber')"  value="<?php
                                            if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                if (isset($invoiceArray[0]['invoiceQuotationNumber'])) {
                                                    echo htmlentities($invoiceArray[0]['invoiceQuotationNumber']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="invoiceQuotationNumberHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceNumber"><strong><?php echo ucfirst($leafTranslation['invoiceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="invoiceNumber" id="invoiceNumber" onKeyUp="removeMeError('invoiceNumber')"  value="<?php
                                            if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                if (isset($invoiceArray[0]['invoiceNumber'])) {
                                                    echo htmlentities($invoiceArray[0]['invoiceNumber']);
                                                }
                                            }
                                            ?>">
                                            <span class="help-block" id="invoiceNumberHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="referenceNumberForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="referenceNumber"><strong><?php echo ucfirst($leafTranslation['referenceNumberLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber" onKeyUp="removeMeError('referenceNumber')"  value="<?php
                                                       if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                           if (isset($invoiceArray[0]['referenceNumber'])) {
                                                               echo htmlentities($invoiceArray[0]['referenceNumber']);
                                                           }
                                                       }
                                                       ?>">
                                            <span class="help-block" id="referenceNumberHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceCodeForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="invoiceCode"><strong><?php echo ucfirst($leafTranslation['invoiceCodeLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceCode" id="invoiceCode"  
                                                       onKeyUp="removeMeError('invoiceCode')" 
                                                       value="<?php
                                                   if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                       if (isset($invoiceArray[0]['invoiceCode'])) {
                                                           echo htmlentities($invoiceArray[0]['invoiceCode']);
                                                       }
                                                   }
                                                   ?>" maxlength="16">
                                                <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                            <span class="help-block" id="invoiceCodeHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceTotalAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceTotalAmount"><strong><?php echo ucfirst($leafTranslation['invoiceTotalAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceTotalAmount" id="invoiceTotalAmount" onKeyUp="removeMeError('invoiceTotalAmount')"  value="<?php
                                                   if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                       if (isset($invoiceArray[0]['invoiceTotalAmount'])) {
                                                           echo htmlentities($invoiceArray[0]['invoiceTotalAmount']);
                                                       }
                                                   }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceTotalAmountHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceTextAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceTextAmount"><strong><?php echo ucfirst($leafTranslation['invoiceTextAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="invoiceTextAmount" id="invoiceTextAmount" onKeyUp="removeMeError('invoiceTextAmount')"  value="<?php
                                            if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                if (isset($invoiceArray[0]['invoiceTextAmount'])) {
                                                    echo htmlentities($invoiceArray[0]['invoiceTextAmount']);
                                                }
                                            }
                                                       ?>">
                                            <span class="help-block" id="invoiceTextAmountHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceTaxAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceTaxAmount"><strong><?php echo ucfirst($leafTranslation['invoiceTaxAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceTaxAmount" id="invoiceTaxAmount" onKeyUp="removeMeError('invoiceTaxAmount')"  value="<?php
                                            if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                if (isset($invoiceArray[0]['invoiceTaxAmount'])) {
                                                    echo htmlentities($invoiceArray[0]['invoiceTaxAmount']);
                                                }
                                            }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceTaxAmountHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceDiscountAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDiscountAmount"><strong><?php echo ucfirst($leafTranslation['invoiceDiscountAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceDiscountAmount" id="invoiceDiscountAmount" onKeyUp="removeMeError('invoiceDiscountAmount')"  value="<?php
                                                if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                    if (isset($invoiceArray[0]['invoiceDiscountAmount'])) {
                                                        echo htmlentities($invoiceArray[0]['invoiceDiscountAmount']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceDiscountAmountHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceShippingAmountForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceShippingAmount"><strong><?php echo ucfirst($leafTranslation['invoiceShippingAmountLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceShippingAmount" id="invoiceShippingAmount" onKeyUp="removeMeError('invoiceShippingAmount')"  value="<?php
                                    if (isset($invoiceArray) && is_array($invoiceArray)) {
                                        if (isset($invoiceArray[0]['invoiceShippingAmount'])) {
                                            echo htmlentities($invoiceArray[0]['invoiceShippingAmount']);
                                        }
                                    }
                                    ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceShippingAmountHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceInterestRateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceInterestRate"><strong><?php echo ucfirst($leafTranslation['invoiceInterestRateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceInterestRate" id="invoiceInterestRate" onKeyUp="removeMeError('invoiceInterestRate')"  value="<?php
                                                       if (isset($invoiceArray) && is_array($invoiceArray)) {
                                                           if (isset($invoiceArray[0]['invoiceInterestRate'])) {
                                                               echo htmlentities($invoiceArray[0]['invoiceInterestRate']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="invoiceInterestRateHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($invoiceArray) && is_array($invoiceArray)) {

                                        if (isset($invoiceArray[0]['invoiceDate'])) {
                                            $valueArray = $invoiceArray[0]['invoiceDate'];
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
                                    ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDate"><strong><?php echo ucfirst($leafTranslation['invoiceDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceDate" id="invoiceDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceDateImage"></span></div>
                                            <span class="help-block" id="invoiceDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    if (isset($invoiceArray) && is_array($invoiceArray)) {

                                        if (isset($invoiceArray[0]['invoiceStartDate'])) {
                                            $valueArray = $invoiceArray[0]['invoiceStartDate'];
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
                                    ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceStartDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceStartDate"><strong><?php echo ucfirst($leafTranslation['invoiceStartDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceStartDate" id="invoiceStartDate" value="<?php
                                            if (isset($value)) {
                                                echo $value;
                                            }
                                            ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceStartDateImage"></span></div>
                                            <span class="help-block" id="invoiceStartDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($invoiceArray) && is_array($invoiceArray)) {

                                        if (isset($invoiceArray[0]['invoiceEndDate'])) {
                                            $valueArray = $invoiceArray[0]['invoiceEndDate'];
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
                                    ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceEndDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceEndDate"><strong><?php echo ucfirst($leafTranslation['invoiceEndDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceEndDate" id="invoiceEndDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceEndDateImage"></span></div>
                                            <span class="help-block" id="invoiceEndDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    if (isset($invoiceArray) && is_array($invoiceArray)) {

                                        if (isset($invoiceArray[0]['invoiceDueDate'])) {
                                            $valueArray = $invoiceArray[0]['invoiceDueDate'];
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
                                    <?php
                                    if (isset($invoiceArray) && is_array($invoiceArray)) {

                                        if (isset($invoiceArray[0]['invoicePromiseDate'])) {
                                            $valueArray = $invoiceArray[0]['invoicePromiseDate'];
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
                                    ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoicePromiseDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoicePromiseDate"><strong><?php echo ucfirst($leafTranslation['invoicePromiseDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoicePromiseDate" id="invoicePromiseDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoicePromiseDateImage"></span></div>
                                            <span class="help-block" id="invoicePromiseDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                       <?php
                                                       if (isset($invoiceArray) && is_array($invoiceArray)) {

                                                           if (isset($invoiceArray[0]['invoiceShippingDate'])) {
                                                               $valueArray = $invoiceArray[0]['invoiceShippingDate'];
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
                                                       ?>                     <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceShippingDateForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceShippingDate"><strong><?php echo ucfirst($leafTranslation['invoiceShippingDateLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoiceShippingDate" id="invoiceShippingDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="invoiceShippingDateImage"></span></div>
                                            <span class="help-block" id="invoiceShippingDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoicePeriodForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoicePeriod"><strong><?php echo ucfirst($leafTranslation['invoicePeriodLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="invoicePeriod" id="invoicePeriod"
                                                       value="<?php
                                                if (isset($invoiceArray[0]['invoicePeriod'])) {
                                                    if (isset($invoiceArray[0]['invoicePeriod'])) {
                                                        echo htmlentities($invoiceArray[0]['invoicePeriod']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="invoicePeriodHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceDescriptionForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDescription"><strong><?php echo ucfirst($leafTranslation['invoiceDescriptionLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea class="form-control" name="invoiceDescription" id="invoiceDescription" onKeyUp="removeMeError('invoiceDescription')">
    <?php
    if (isset($invoiceArray[0]['invoiceDescription'])) {

        echo htmlentities($invoiceArray[0]['invoiceDescription']);
    }
    ?>
                                            </textarea>
                                            <span class="help-block" id="invoiceDescriptionHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="invoiceRemarkForm">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceRemark"><strong><?php echo ucfirst($leafTranslation['invoiceRemarkLabel']); ?></strong></label>
                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea class="form-control" name="invoiceRemark" id="invoiceRemark" onKeyUp="removeMeError('invoiceRemark')">
    <?php
    if (isset($invoiceArray[0]['invoiceRemark'])) {

        echo htmlentities($invoiceArray[0]['invoiceRemark']);
    }
    ?>
                                            </textarea>
                                            <span class="help-block" id="invoiceRemarkHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                </div>
                            </div>
                        </div><div class="panel-footer" align="center">
                            <div class="btn-group">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
                                <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class=caret></span></a> 
                                <ul class="dropdown-menu"> 
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li> 
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li> 
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];   ?></a> </li>--> 
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];  ?></a> </li>--> 
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li> 
                                </ul> 
                            </div> 
                            <div class="btn-group">
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
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>, '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
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
            <div class="modal fade" id="'deleteDetailPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                        </div>
                        <div class="modal-body"> 
                            <input type="hidden" name="invoiceDetailIdPreview" id="invoiceDetailIdPreview"> 
                            <div class="form-group" id="productIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productIdPreview"><?php echo $leafTranslation['productIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="productIdPreview" id="productIdPreview">
                                </div>						</div>						<div class="form-group" id="unitOfMeasurementIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="unitOfMeasurementIdPreview"><?php echo $leafTranslation['unitOfMeasurementIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="unitOfMeasurementIdPreview" id="unitOfMeasurementIdPreview">
                                </div>						</div>						<div class="form-group" id="discountIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="discountIdPreview"><?php echo $leafTranslation['discountIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="discountIdPreview" id="discountIdPreview">
                                </div>						</div>						<div class="form-group" id="taxIdDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="taxIdPreview"><?php echo $leafTranslation['taxIdLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="taxIdPreview" id="taxIdPreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailLineNumberDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailLineNumberPreview"><?php echo $leafTranslation['invoiceDetailLineNumberLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailLineNumberPreview" id="invoiceDetailLineNumberPreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailQuantityDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailQuantityPreview"><?php echo $leafTranslation['invoiceDetailQuantityLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailQuantityPreview" id="invoiceDetailQuantityPreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailDescriptionDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailDescriptionPreview"><?php echo $leafTranslation['invoiceDetailDescriptionLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailDescriptionPreview" id="invoiceDetailDescriptionPreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailPriceDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailPricePreview"><?php echo $leafTranslation['invoiceDetailPriceLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailPricePreview" id="invoiceDetailPricePreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailDiscountDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailDiscountPreview"><?php echo $leafTranslation['invoiceDetailDiscountLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailDiscountPreview" id="invoiceDetailDiscountPreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailTaxDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailTaxPreview"><?php echo $leafTranslation['invoiceDetailTaxLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailTaxPreview" id="invoiceDetailTaxPreview">
                                </div>						</div>						<div class="form-group" id="invoiceDetailTotalPriceDiv">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceDetailTotalPricePreview"><?php echo $leafTranslation['invoiceDetailTotalPriceLabel']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="invoiceDetailTotalPricePreview" id="invoiceDetailTotalPricePreview">
                                </div>						</div>						<div class="form-group" id="isRule78Div">
                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isRule78Preview"><?php echo $leafTranslation['isRule78Label']; ?></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">								<input class="form-control" type="text" name="isRule78Preview" id="isRule78Preview">
                                </div>						</div>				</div> 
                        <div class="modal-footer"> 
                            <button type="button"  class="btn btn-danger" onClick="deleteGridRecordDetail('<?php echo $leafId; ?>', '<?php echo $invoiceDetail->getControllerPath(); ?>', '<?php echo $invoiceDetail->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button> 
                            <button type="button"  class="btn btn-primary" onClick="showMeModal('deleteDetailPreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            <div class="row"> 
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <table class="table table-bordered table-striped table-condensed table-hover" id="tableData"> 
                        <thead> 
                            <tr> 
                                <th width="25px"><div align="center">#</div></th>
                        <th width="50px" align="center"><div align="center"><?php echo ucfirst($t['actionLabel']); ?></div></th> 
                        <th><?php echo ucfirst($leafTranslation['productIdLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['unitOfMeasurementIdLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['discountIdLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['taxIdLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailLineNumberLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailQuantityLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailDescriptionLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailPriceLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailDiscountLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailTaxLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['invoiceDetailTotalPriceLabel']); ?></th> 
                        <th><?php echo ucfirst($leafTranslation['isRule78Label']); ?></th> 
                        </tr>
                        <tr>
                                        <?php
                                        $disabledDetail = null;
                                        if (isset($_POST['invoiceId']) && (strlen($_POST['invoiceId']) > 0)) {
                                            $disabledDetail = null;
                                        } else {
                                            $disabledDetail = "disabled";
                                        }
                                        ?>
                        <tr>
                            <td vAlign="top">&nbsp;</td> <td vAlign="top" align="center"><div align="center"><button type="button"  class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onClick="showFormCreateDetail('<?php echo $leafId;
                                    ;
                                        ?>', '<?php echo $invoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>')"><i class="glyphicon glyphicon-plus  glyphicon-white"></i></button><div id="miniInfoPanel9999"></div></div></td>
                            <td vAlign="top"><div class="form-group" id="productId9999Detail">                     <select name="productId[]" id="productId9999" class="chzn-select form-control ">
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
                                    </select><span class="help-block" id="productId9999HelpMe"></span></div>
                            </td>
                            <td vAlign="top"><div class="form-group" id="unitOfMeasurementId9999Detail">                     <select name="unitOfMeasurementId[]" id="unitOfMeasurementId9999" class="chzn-select form-control ">
                                        <option value=""></option>
                                        <?php
                                        if (is_array($unitOfMeasurementArray)) {
                                            $totalRecord = intval(count($unitOfMeasurementArray));
                                            if ($totalRecord > 0) {
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    ?>
                                                    <option value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>"><?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option> 
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
                                    </select><span class="help-block" id="unitOfMeasurementId9999HelpMe"></span></div>
                            </td>
                            <td vAlign="top"><div class="form-group" id="discountId9999Detail">                     <select name="discountId[]" id="discountId9999" class="chzn-select form-control ">
                                        <option value=""></option>
    <?php
    if (is_array($discountArray)) {
        $totalRecord = intval(count($discountArray));
        if ($totalRecord > 0) {
            for ($i = 0; $i < $totalRecord; $i++) {
                ?>
                                                    <option value="<?php echo $discountArray[$i]['discountId']; ?>"><?php echo $discountArray[$i]['discountDescription']; ?></option> 
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
                                    </select><span class="help-block" id="discountId9999HelpMe"></span></div>
                            </td>
                            <td vAlign="top"><div class="form-group" id="taxId9999Detail">                     <select name="taxId[]" id="taxId9999" class="chzn-select form-control ">
                                        <option value=""></option>
    <?php
    if (is_array($taxArray)) {
        $totalRecord = intval(count($taxArray));
        if ($totalRecord > 0) {
            for ($i = 0; $i < $totalRecord; $i++) {
                ?>
                                                    <option value="<?php echo $taxArray[$i]['taxId']; ?>"><?php echo $taxArray[$i]['taxDescription']; ?></option> 
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
                                    </select><span class="help-block" id="taxId9999HelpMe"></span></div>
                            </td>
                            <td vAlign="top">
                                <div class="form-group" id="invoiceDetailLineNumber9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" type="text" name="invoiceDetailLineNumber[]" id="invoiceDetailLineNumber9999" onBlur="removeMeErrorDetail('invoiceDetailLineNumber9999')" onKeyUp="removeMeErrorDetail('invoiceDetailLineNumber9999')"   <?php echo $disabledDetail; ?>>
                                    </div>
                                    <span class="help-block" id="invoiceDetailLineNumber9999HelpMe"></span></div></td>
                            <td vAlign="top">
                                <div class="form-group" id="invoiceDetailQuantity9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity9999" onBlur="removeMeErrorDetail('invoiceDetailQuantity9999')" onKeyUp="removeMeErrorDetail('invoiceDetailQuantity9999')"   <?php echo $disabledDetail; ?>>
                                    </div>
                                    <span class="help-block" id="invoiceDetailQuantity9999HelpMe"></span></div></td>
                            <td vAlign="top"><div class="form-group" id="invoiceDetailDescription9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription9999" onBlur="removeMeErrorDetail('invoiceDetailDescription9999')" onKeyUp="removeMeErrorDetail('invoiceDetailDescription9999')">
                                    </div>
                                    <span class="help-block" id="invoiceDetailDescription9999HelpMe"></span></div></td>
                            <td vAlign="top"><div class="form-group" id="invoiceDetailPrice9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice9999" onBlur="removeMeErrorDetail('invoiceDetailPrice9999')" onKeyUp="removeMeErrorDetail('invoiceDetailPrice9999')">
                                    </div>
                                    <span class="help-block" id="invoiceDetailPrice9999HelpMe"></span></div></td>
                            <td vAlign="top"><div class="form-group" id="invoiceDetailDiscount9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount9999" onBlur="removeMeErrorDetail('invoiceDetailDiscount9999')" onKeyUp="removeMeErrorDetail('invoiceDetailDiscount9999')">
                                    </div>
                                    <span class="help-block" id="invoiceDetailDiscount9999HelpMe"></span></div></td>
                            <td vAlign="top"><div class="form-group" id="invoiceDetailTax9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="invoiceDetailTax[]" id="invoiceDetailTax9999" onBlur="removeMeErrorDetail('invoiceDetailTax9999')" onKeyUp="removeMeErrorDetail('invoiceDetailTax9999')">
                                    </div>
                                    <span class="help-block" id="invoiceDetailTax9999HelpMe"></span></div></td>
                            <td vAlign="top"><div class="form-group" id="invoiceDetailTotalPrice9999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" <?php echo $disabledDetail; ?> type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice9999" onBlur="removeMeErrorDetail('invoiceDetailTotalPrice9999')" onKeyUp="removeMeErrorDetail('invoiceDetailTotalPrice9999')">
                                    </div>
                                    <span class="help-block" id="invoiceDetailTotalPrice9999HelpMe"></span></div></td>
                            <td vAlign="top">
                                <div class="form-group" id="isRule789999Detail">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <input class="form-control" type="checkbox" name="isRule78[]" id="isRule78"   <?php echo $disabledDetail; ?>>
                                    </div>
                                    <span class="help-block" id="isRule789999HelpMe"></span></div></td>
                        </tr> 
                        </thead> 
                        <tbody id="tableBody">
                                        <?php
                                        if ($_POST['invoiceId']) {
                                            if (is_array($invoiceDetailArray)) {
                                                $totalRecordDetail = intval(count($invoiceDetailArray));
                                                if ($totalRecordDetail > 0) {
                                                    $counter = 0;
                                                    for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                        $counter++;
                                                        ?>
                                            <tr id="<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>">
                                                <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td> 
                                                <td vAlign="top" align="center"><div class="btn-group" align="center">
                                                        <input type="hidden" name="invoiceDetailId[]" id="invoiceDetailId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" value="<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>">
                                                        <input type="hidden" name="invoiceId[]" id="invoiceId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" value="<?php echo $invoiceDetailArray[$j]['invoiceId']; ?>">
                                                        <button type="button"  class="btn btn-warning btn-mini" title="Edit" onClick="showFormUpdateDetail('<?php echo $leafId; ?>', '<?php echo $invoiceDetail->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($invoiceDetailArray [$j]['invoiceDetailId']); ?>')"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                        <button type="button"  class="btn btn-danger btn-mini" title="Delete" onClick="showModalDeleteDetail('<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>')"><i class="glyphicon glyphicon-trash  glyphicon-white"></i></button><div id="miniInfoPanel<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>"></div></div></td>
                                                <td vAlign="top"  class="form-group" id="productId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail">	<select name="productId[]" id="productId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($productArray)) {
                                                            $totalRecord = intval(count($productArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($invoiceDetailArray[$j]['productId'] == $productArray[$i]['productId']) {
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
                                                    </select>
                                                </td>
                                                <td vAlign="top"  class="form-group" id="unitOfMeasurementId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail">	<select name="unitOfMeasurementId[]" id="unitOfMeasurementId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($unitOfMeasurementArray)) {
                                                            $totalRecord = intval(count($unitOfMeasurementArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($invoiceDetailArray[$j]['unitOfMeasurementId'] == $unitOfMeasurementArray[$i]['unitOfMeasurementId']) {
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
                                                    </select>
                                                </td>
                                                <td vAlign="top"  class="form-group" id="discountId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail">	<select name="discountId[]" id="discountId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                        <option value=""></option>
                    <?php
                    if (is_array($discountArray)) {
                        $totalRecord = intval(count($discountArray));
                        if ($totalRecord > 0) {
                            for ($i = 0; $i < $totalRecord; $i++) {
                                if ($invoiceDetailArray[$j]['discountId'] == $discountArray[$i]['discountId']) {
                                    $selected = "selected";
                                } else {
                                    $selected = NULL;
                                }
                                ?>
                                                                    <option value="<?php echo $discountArray[$i]['discountId']; ?>" <?php echo $selected; ?>><?php echo $discountArray[$i]['discountDescription']; ?></option> 
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
                                                </td>
                                                <td vAlign="top"  class="form-group" id="taxId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail">	<select name="taxId[]" id="taxId<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" class="form-control chzn-select inpu-sm">
                                                        <option value=""></option>
                                            <?php
                                            if (is_array($taxArray)) {
                                                $totalRecord = intval(count($taxArray));
                                                if ($totalRecord > 0) {
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        if ($invoiceDetailArray[$j]['taxId'] == $taxArray[$i]['taxId']) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = NULL;
                                                        }
                                                        ?>
                                                                    <option value="<?php echo $taxArray[$i]['taxId']; ?>" <?php echo $selected; ?>><?php echo $taxArray[$i]['taxDescription']; ?></option> 
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
                                                </td>
                                                <td vAlign="top" class="form-group" id="invoiceDetailLineNumber<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" type="text" name="invoiceDetailLineNumber[]" id="invoiceDetailLineNumber<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailLineNumber'];
                    }
                    ?>">
                                                </td>
                                                <td vAlign="top" class="form-group" id="invoiceDetailQuantity<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" type="text" name="invoiceDetailQuantity[]" id="invoiceDetailQuantity<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailQuantity'];
                    }
                    ?>">
                                                </td>
                                                <td vAlign="top"><input class="form-control" type="text" name="invoiceDetailDescription[]" id="invoiceDetailDescription<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>" value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailDescription'];
                    }
                    ?>"></td>
                                                <td vAlign="top"  class="form-group" id="invoiceDetailPrice<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="invoiceDetailPrice[]" id="invoiceDetailPrice<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>"   value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailPrice'];
                    }
                    ?>"></td>
                                                <td vAlign="top"  class="form-group" id="invoiceDetailDiscount<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="invoiceDetailDiscount[]" id="invoiceDetailDiscount<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>"   value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailDiscount'];
                    }
                    ?>"></td>
                                                <td vAlign="top"  class="form-group" id="invoiceDetailTax<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="invoiceDetailTax[]" id="invoiceDetailTax<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>"   value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailTax'];
                    }
                    ?>"></td>
                                                <td vAlign="top"  class="form-group" id="invoiceDetailTotalPrice<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" style="text-align:right" type="text" name="invoiceDetailTotalPrice[]" id="invoiceDetailTotalPrice<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>"   value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['invoiceDetailTotalPrice'];
                    }
                    ?>"></td>
                                                <td vAlign="top"  class="form-group" id="isRule78<?php echo $invoiceDetailArray[$j]['invoiceDetailId']; ?>Detail"><input class="form-control" type="checkbox" name="isRule78[]" id="isRule78<?php echo $invoiceDetailArray[$j]['invoiceId']; ?>" value="<?php
                    if (isset($invoiceDetailArray) && is_array($invoiceDetailArray)) {
                        echo $invoiceDetailArray[$j]['isRule78'];
                    }
                    ?>"></td>
                                            </tr> 
                <?php
                }
            } else {
                ?>
                                        <tr> 
                                            <td colspan="6" vAlign="top" align="center"><?php $invoiceDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                        </tr> 
            <?php
            }
        } else {
            ?> 
                                    <tr> 
                                        <td colspan="6" vAlign="top" align="center"><?php $invoiceDetail->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                    </tr> 
            <?php
        }
    }
    ?>
                        </tbody> 
                        <tfoot>
                    </table>
                </div>
            </div>
        </div>
    </form>    <script type="text/javascript">
        $(document).keypress(function(e) {
    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                // shift+n new record event
                if (e.which === 78 && e.shiftKey) {
                    e.preventDefault();
                    delete e;
                    newRecord(<?php echo $leafId; ?>, '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                // shift+s save event
                if (e.which === 83 && e.shiftKey) {
                    e.preventDefault();
                    delete e;
                    updateRecord(<?php echo $leafId; ?>, '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                // shift+d delete event
                if (e.which === 88 && e.shiftKey) {
                    e.preventDefault();
                    delete e;
                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    return false;
                }
    <?php } ?>
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    e.preventDefault();
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $invoice->getControllerPath(); ?>', '<?php echo $invoice->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    e.preventDefault();
                    return false;
                    break;
            }
            e.preventDefault();
            delete e;
        });
        $(document).ready(function() {
            $(document).scrollTop(0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('invoiceId');
            validateMeNumeric('businessPartnerId');
            validateMeNumeric('businessPartnerContactId');
            validateMeNumeric('countryId');
            validateMeNumeric('invoiceProjectId');
            validateMeNumeric('paymentTermId');
            validateMeNumeric('invoiceProcessId');
            validateMeAlphaNumeric('businessPartnerAddress');
            validateMeAlphaNumeric('invoiceQuotationNumber');
            validateMeAlphaNumeric('invoiceNumber');
            validateMeAlphaNumeric('referenceNumber');
            validateMeAlphaNumeric('invoiceCode');
            validateMeCurrency('invoiceTotalAmount');
            validateMeAlphaNumeric('invoiceTextAmount');
            validateMeCurrency('invoiceTaxAmount');
            validateMeCurrency('invoiceDiscountAmount');
            validateMeCurrency('invoiceShippingAmount');
            validateMeCurrency('invoiceInterestRate');
            $('#invoiceDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#invoiceStartDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#invoiceEndDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#invoiceDueDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#invoicePromiseDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#invoiceShippingDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeNumeric('invoicePeriod');
            validateMeAlphaNumeric('invoiceDescription');
            validateMeAlphaNumeric('invoiceRemark');
            validateMeNumericRange('invoiceDetailId');
            validateMeNumericRange('invoiceId');
            validateMeNumericRange('productId');
            validateMeNumericRange('unitOfMeasurementId');
            validateMeNumericRange('discountId');
            validateMeNumericRange('taxId');
            validateMeNumericRange('invoiceDetailLineNumber');
            validateMeNumericRange('invoiceDetailQuantity');
            validateMeAlphaNumericRange('invoiceDetailDescription');
            validateMeCurrencyRange('invoiceDetailPrice');
            validateMeCurrencyRange('invoiceDetailDiscount');
            validateMeCurrencyRange('invoiceDetailTax');
            validateMeCurrencyRange('invoiceDetailTotalPrice');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['invoiceId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
                            ;
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $invoice->getControllerPath(); ?>','<?php echo $invoice->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
    </script> 
<?php } ?> 
</div></div>
</form>
<script type="text/javascript" src="./v3/financial/accountReceivable/javascript/invoice.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>