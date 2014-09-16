  

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
require_once($newFakeDocumentRoot . "v3/financial/cashbook/controller/bankController.php");
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
$translator->setCurrentTable('bank');
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
$bankArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $bank = new \Core\Financial\Cashbook\Bank\Controller\BankClass();
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
            $bank->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $bank->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $bank->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $bank->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $bank->setStartDay($start[2]);
            $bank->setStartMonth($start[1]);
            $bank->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $bank->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $bank->setEndDay($start[2]);
            $bank->setEndMonth($start[1]);
            $bank->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $bank->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $bank->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $bank->setServiceOutput('html');
        $bank->setLeafId($leafId);
        $bank->execute();
        if ($_POST['method'] == 'read') {
            $bank->setStart($offset);
            $bank->setLimit($limit); // normal system don't like paging..  
            $bank->setPageOutput('html');
            $bankArray = $bank->read();
            if (isset($bankArray [0]['firstRecord'])) {
                $firstRecord = $bankArray [0]['firstRecord'];
            }
            if (isset($bankArray [0]['nextRecord'])) {
                $nextRecord = $bankArray [0]['nextRecord'];
            }
            if (isset($bankArray [0]['previousRecord'])) {
                $previousRecord = $bankArray [0]['previousRecord'];
            }
            if (isset($bankArray [0]['lastRecord'])) {
                $lastRecord = $bankArray [0]['lastRecord'];
                $endRecord = $bankArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($bank->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($bankArray [0]['total'])) {
                $total = $bankArray [0]['total'];
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
                    <button title="A" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button> 
                    <button title="B" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button> 
                    <button title="C" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button> 
                    <button title="D" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button> 
                    <button title="E" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button> 
                    <button title="F" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button> 
                    <button title="G" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button> 
                    <button title="H" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button> 
                    <button title="I" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button> 
                    <button title="J" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button> 
                    <button title="K" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button> 
                    <button title="L" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button> 
                    <button title="M" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button> 
                    <button title="N" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button> 
                    <button title="O" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button> 
                    <button title="P" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button> 
                    <button title="Q" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button> 
                    <button title="R" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button> 
                    <button title="S" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button> 
                    <button title="T" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button> 
                    <button title="U" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button> 
                    <button title="V" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button> 
                    <button title="W" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button> 
                    <button title="X" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button> 
                    <button title="Y" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button> 
                    <button title="Z" class="btn btn-success" type="button"  onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button> 
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
                                    <a href="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel')">
                                        <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv')">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onClick="reportRequest('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html')">
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
                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button> 
                            </div>
                            <label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                                                                             if (isset($_POST['query'])) {
                                                                                                 echo $_POST['query'];
                                                                                             }
                                                                                             ?>"><span class="input-group-addon">
                                    <img id="searchTextImage" src="./images/icons/magnifier.png">
                                </span>
                            </div>
                            <br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                            <table class="table table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>         				</tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             				<td align="center"><a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a></td>
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
                            <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
                                        <input type="hidden" name="bankIdPreview" id="bankIdPreview">
                                        <div class="form-group" id="chartOfAccountIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="chartOfAccountIdPreview"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="chartOfAccountIdPreview" id="chartOfAccountIdPreview">
                                            </div>					</div>					<div class="form-group" id="bankCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankCodePreview"><?php echo $leafTranslation['bankCodeLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankCodePreview" id="bankCodePreview">
                                            </div>					</div>					<div class="form-group" id="bankAccountDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankAccountPreview"><?php echo $leafTranslation['bankAccountLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankAccountPreview" id="bankAccountPreview">
                                            </div>					</div>					<div class="form-group" id="bankMinimumValueDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankMinimumValuePreview"><?php echo $leafTranslation['bankMinimumValueLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankMinimumValuePreview" id="bankMinimumValuePreview">
                                            </div>					</div>					<div class="form-group" id="bankOverDraftDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankOverDraftPreview"><?php echo $leafTranslation['bankOverDraftLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankOverDraftPreview" id="bankOverDraftPreview">
                                            </div>					</div>					<div class="form-group" id="bankContactPersonDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankContactPersonPreview"><?php echo $leafTranslation['bankContactPersonLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankContactPersonPreview" id="bankContactPersonPreview">
                                            </div>					</div>					<div class="form-group" id="bankAddressDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankAddressPreview"><?php echo $leafTranslation['bankAddressLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankAddressPreview" id="bankAddressPreview">
                                            </div>					</div>					<div class="form-group" id="bankAddress1Div">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankAddress1Preview"><?php echo $leafTranslation['bankAddress1Label']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankAddress1Preview" id="bankAddress1Preview">
                                            </div>					</div>					<div class="form-group" id="bankAddress2Div">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankAddress2Preview"><?php echo $leafTranslation['bankAddress2Label']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankAddress2Preview" id="bankAddress2Preview">
                                            </div>					</div>					<div class="form-group" id="bankAddress3Div">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankAddress3Preview"><?php echo $leafTranslation['bankAddress3Label']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankAddress3Preview" id="bankAddress3Preview">
                                            </div>					</div>					<div class="form-group" id="bankPostCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankPostCodePreview"><?php echo $leafTranslation['bankPostCodeLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankPostCodePreview" id="bankPostCodePreview">
                                            </div>					</div>					<div class="form-group" id="bankOfficeNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankOfficeNumberPreview"><?php echo $leafTranslation['bankOfficeNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankOfficeNumberPreview" id="bankOfficeNumberPreview">
                                            </div>					</div>					<div class="form-group" id="bankFaxNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankFaxNumberPreview"><?php echo $leafTranslation['bankFaxNumberLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankFaxNumberPreview" id="bankFaxNumberPreview">
                                            </div>					</div>					<div class="form-group" id="bankDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankDescriptionPreview"><?php echo $leafTranslation['bankDescriptionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="bankDescriptionPreview" id="bankDescriptionPreview">
                                            </div>					</div>					<div class="form-group" id="isPettyCashDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isPettyCashPreview"><?php echo $leafTranslation['isPettyCashLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isPettyCashPreview" id="isPettyCashPreview">
                                            </div>					</div>					<div class="form-group" id="isCollectionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isCollectionPreview"><?php echo $leafTranslation['isCollectionLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isCollectionPreview" id="isCollectionPreview">
                                            </div>					</div>					<div class="form-group" id="isPaymentVoucherDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="isPaymentVoucherPreview"><?php echo $leafTranslation['isPaymentVoucherLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="isPaymentVoucherPreview" id="isPaymentVoucherPreview">
                                            </div>					</div>     		</div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview', 0)" value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <table class ="table table-striped table-condensed table-hover" id="tableData"> 
                                    <thead> 
                                        <tr> 
                                            <th width="25px" align="center"><div align="center">#</div></th>
                                    <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="75px"><div align="center"><?php echo ucwords($leafTranslation['bankCodeLabel']); ?></div></th> 
                                    <th><div align="center"><?php echo ucwords($leafTranslation['bankNameLabel']); ?></div></th> 

                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                                    <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                                    <th width="25px"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onClick="toggleChecked(this.checked)"></th>
                                    </tr> 
                                    </thead> 
                                    <tbody id="tableBody"> 
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($bankArray)) {
                                                $totalRecord = intval(count($bankArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                            if ($bankArray[$i]['isDelete'] == 1) {
                                                                echo "class=\"danger\"";
                                                            } else {
                                                                if ($bankArray[$i]['isDraft'] == 1) {
                                                                    echo "class=\"warning\"";
                                                                }
                                                            }
                                                            ?>>
                                                            <td vAlign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>                       	
                                                            <td vAlign="top" align="center"><div class="btn-group" align="center">
                                                                    <?php if ($bankArray[$i]['bankCode'] != 'UNBL') { ?>
                                                                        <button type="button"  class="btn btn-warning btn-sm" title="Edit" onClick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($bankArray [$i]['bankId']); ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="Edit"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalDelete('<?php echo rawurlencode($bankArray [$i]['bankId']); ?>', '<?php echo rawurlencode($bankArray [$i]['chartOfAccountTitle']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankCode']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankAccount']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankMinimumValue']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankOverDraft']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankContactPerson']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankAddress']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankAddress1']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankAddress2']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankAddress3']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankPostCode']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankOfficeNumber']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankFaxNumber']); ?>', '<?php echo rawurlencode($bankArray [$i]['bankDescription']); ?>', '<?php echo rawurlencode($bankArray [$i]['isPettyCash']); ?>', '<?php echo rawurlencode($bankArray [$i]['isCollection']); ?>', '<?php echo rawurlencode($bankArray [$i]['isPaymentVoucher']); ?>')" value="Delete"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button></div><?php } ?></td> 
                                                            <td vAlign="top"><div align="center">
                                                                    <?php
                                                                    if (isset($bankArray[$i]['bankCode'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($bankArray[$i]['bankCode']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $bankArray[$i]['bankCode']);
                                                                                } else {
                                                                                    echo $bankArray[$i]['bankCode'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($bankArray[$i]['bankCode']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $bankArray[$i]['bankCode']);
                                                                                } else {
                                                                                    echo $bankArray[$i]['bankCode'];
                                                                                }
                                                                            } else {
                                                                                echo $bankArray[$i]['bankCode'];
                                                                            }
                                                                        } else {
                                                                            echo $bankArray[$i]['bankCode'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    &nbsp;
                                                                    <?php } ?>
                                                            </td><td vAlign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($bankArray[$i]['bankName'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($bankArray[$i]['bankName']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $bankArray[$i]['bankName']);
                                                                                } else {
                                                                                    echo $bankArray[$i]['bankName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($bankArray[$i]['bankName']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $bankArray[$i]['bankName']);
                                                                                } else {
                                                                                    echo $bankArray[$i]['bankName'];
                                                                                }
                                                                            } else {
                                                                                echo $bankArray[$i]['bankName'];
                                                                            }
                                                                        } else {
                                                                            echo $bankArray[$i]['bankName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    &nbsp;
                                                                    <?php } ?>
                                                            </td>


                                                            <td vAlign="top" align="center"><div align="center">
                                                                    <?php
                                                                    if (isset($bankArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($bankArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $bankArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $bankArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($bankArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $bankArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $bankArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $bankArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $bankArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                            <?php } else { ?>
                                                                        &nbsp;
                                                            <?php } ?>
                                                                </div></td>
                                                            <?php
                                                            if (isset($bankArray[$i]['executeTime'])) {
                                                                $valueArray = $bankArray[$i]['executeTime'];
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
                                                                if ($bankArray[$i]['isDelete']) {
                                                                    $checked = "checked";
                                                                } else {
                                                                    $checked = NULL;
                                                                }
                                                                ?>
                                                            <td vAlign="top">
                                                        <?php if ($bankArray[$i]['bankCode'] != 'UNBL') { ?>
                                                                    <input class="form-control" style="display:none;" type="checkbox" name="bankId[]"  value="<?php echo $bankArray[$i]['bankId']; ?>">
                                                                    <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $bankArray[$i]['isDelete']; ?>">
                        <?php } ?>
                                                            </td>
                                                        </tr> 
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr> 
                                                        <td colspan="7" vAlign="top" align="center"><?php $bank->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                    </tr> 
                                                <?php
                                                }
                                            } else {
                                                ?> 
                                                <tr> 
                                                    <td colspan="7" vAlign="top" align="center"><?php $bank->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" vAlign="top" align="center"><?php $bank->exceptionMessage($t['loadFailureLabel']); ?></td> 
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
                            <button type="button"  class="delete btn btn-warning" onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>')"> 
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
    <form class="form-horizontal">		<input type="hidden" name="bankId" id="bankId" value="<?php
            if (isset($_POST['bankId'])) {
                echo $_POST['bankId'];
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
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onClick="firstRecord('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onClick="previousRecord('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onClick="nextRecord('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onClick="endRecord('<?php echo $leafId; ?>', '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <fieldset>
                                <legend><?php echo $t['informationTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeAll(0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeAll(1);">

                                </legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group" id="bankCodeForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankName"><strong><?php echo ucfirst($leafTranslation['bankLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="bankName" id="bankName"  
                                                           onKeyUp="removeMeError('bankName')" 
                                                           value="<?php
    if (isset($bankArray) && is_array($bankArray)) {
        if (isset($bankArray[0]['bankName'])) {
            echo htmlentities($bankArray[0]['bankName']);
        }
    }
    ?>" maxlength="16">
                                                    <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                                <span class="help-block" id="bankNameHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankCodeForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankCode"><strong><?php echo ucfirst($leafTranslation['bankCodeLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="bankCode" id="bankCode"  
                                                           onKeyUp="removeMeError('bankCode')" 
                                                           value="<?php
    if (isset($bankArray) && is_array($bankArray)) {
        if (isset($bankArray[0]['bankCode'])) {
            echo htmlentities($bankArray[0]['bankCode']);
        }
    }
    ?>" maxlength="16">
                                                    <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                                <span class="help-block" id="bankCodeHelpMe"></span>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankAccountForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankAccount"><strong><?php echo ucfirst($leafTranslation['bankAccountLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="bankAccount" id="bankAccount" onKeyUp="removeMeError('bankAccount')"  value="<?php
                                                   if (isset($bankArray) && is_array($bankArray)) {
                                                       if (isset($bankArray[0]['bankAccount'])) {
                                                           echo htmlentities($bankArray[0]['bankAccount']);
                                                       }
                                                   }
    ?>">
                                                <span class="help-block" id="bankAccountHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankMinimumValueForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankMinimumValue"><strong><?php echo ucfirst($leafTranslation['bankMinimumValueLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="bankMinimumValue" id="bankMinimumValue" onKeyUp="removeMeError('bankMinimumValue')"  value="<?php
                                                    if (isset($bankArray) && is_array($bankArray)) {
                                                        if (isset($bankArray[0]['bankMinimumValue'])) {
                                                            echo htmlentities($bankArray[0]['bankMinimumValue']);
                                                        }
                                                    }
                                                    ?>">
                                                    <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                <span class="help-block" id="bankMinimumValueHelpMe"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankOverDraftForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankOverDraft"><strong><?php echo ucfirst($leafTranslation['bankOverDraftLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="bankOverDraft" id="bankOverDraft" onKeyUp="removeMeError('bankOverDraft')"  value="<?php
                                                    if (isset($bankArray) && is_array($bankArray)) {
                                                        if (isset($bankArray[0]['bankOverDraft'])) {
                                                            echo htmlentities($bankArray[0]['bankOverDraft']);
                                                        }
                                                    }
                                                    ?>">
                                                    <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                <span class="help-block" id="bankOverDraftHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="isPettyCashForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isPettyCash"><strong><?php echo ucfirst($leafTranslation['isPettyCashLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <div id="isCloseSwitch" class="switch" data-on-label="<?php echo $t['openTextLabel']; ?>"
                                                     data-off-label="<?php echo $t['closeTextLabel']; ?>" data-on="success" data-off="danger">
                                                    <input class="form-control" type="checkbox" name="isPettyCash" id="isPettyCash" 
                                                           value="<?php
                                                           if (isset($bankArray) && is_array($bankArray)) {
                                                               if (isset($bankArray[0]['isPettyCash'])) {
                                                                   echo $bankArray[0]['isPettyCash'];
                                                               }
                                                           }
                                                           ?>"></div>
                                                <span class="help-block" id="isPettyCashHelpMe"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="isCollectionForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isCollection"><strong><?php echo ucfirst($leafTranslation['isCollectionLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <div id="isCloseSwitch" class="switch" data-on-label="<?php echo $t['openTextLabel']; ?>"
                                                     data-off-label="<?php echo $t['closeTextLabel']; ?>" data-on="success" data-off="danger">
                                                    <input class="form-control" type="checkbox" name="isCollection" id="isCollection" 
                                                           value="<?php
                                                           if (isset($bankArray) && is_array($bankArray)) {
                                                               if (isset($bankArray[0]['isCollection'])) {
                                                                   echo $bankArray[0]['isCollection'];
                                                               }
                                                           }
                                                           ?>"></div>
                                                <span class="help-block" id="isCollectionHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="isPaymentVoucherForm">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="isPaymentVoucher"><strong><?php echo ucfirst($leafTranslation['isPaymentVoucherLabel']); ?></strong></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <div id="isCloseSwitch" class="switch" data-on-label="<?php echo $t['openTextLabel']; ?>"
                                                     data-off-label="<?php echo $t['closeTextLabel']; ?>" data-on="success" data-off="danger">
                                                    <input class="form-control" type="checkbox" name="isPaymentVoucher" id="isPaymentVoucher" 
                                                           value="<?php
                                                       if (isset($bankArray) && is_array($bankArray)) {
                                                           if (isset($bankArray[0]['isPaymentVoucher'])) {
                                                               echo $bankArray[0]['isPaymentVoucher'];
                                                           }
                                                       }
                                                       ?>">
                                                </div>
                                                <span class="help-block" id="isPaymentVoucherHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="addressLegend"><?php echo $t['addressTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('address', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('address', 1);">
                                </legend>
                                <div id="address">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankAddress1Form">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankAddress1"><strong><?php echo ucfirst($leafTranslation['bankAddress1Label']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input class="form-control" type="text" name="bankAddress1" id="bankAddress1" onKeyUp="removeMeError('bankAddress1')"  value="<?php
                                                               if (isset($bankArray) && is_array($bankArray)) {
                                                                   if (isset($bankArray[0]['bankAddress1'])) {
                                                                       echo htmlentities($bankArray[0]['bankAddress1']);
                                                                   }
                                                               }
                                                               ?>">
                                                    <span class="help-block" id="bankAddress1HelpMe"></span>
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankPostCodeForm">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="bankPostCode"><strong><?php echo ucfirst($leafTranslation['bankPostCodeLabel']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" name="bankPostCode" id="bankPostCode"  
                                                               onKeyUp="removeMeError('bankPostCode')" 
                                                               value="<?php
                                                               if (isset($bankArray) && is_array($bankArray)) {
                                                                   if (isset($bankArray[0]['bankPostCode'])) {
                                                                       echo htmlentities($bankArray[0]['bankPostCode']);
                                                                   }
                                                               }
                                                               ?>" maxlength="16">
                                                        <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                                    <span class="help-block" id="bankPostCodeHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankAddress2Form">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankAddress2"><strong><?php echo ucfirst($leafTranslation['bankAddress2Label']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input class="form-control" type="text" name="bankAddress2" id="bankAddress2" onKeyUp="removeMeError('bankAddress2')"  value="<?php
                                                               if (isset($bankArray) && is_array($bankArray)) {
                                                                   if (isset($bankArray[0]['bankAddress2'])) {
                                                                       echo htmlentities($bankArray[0]['bankAddress2']);
                                                                   }
                                                               }
                                                               ?>">
                                                    <span class="help-block" id="bankAddress2HelpMe"></span>
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankOfficeNumberForm">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankOfficeNumber"><strong><?php echo ucfirst($leafTranslation['bankOfficeNumberLabel']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input class="form-control" type="text" name="bankOfficeNumber" id="bankOfficeNumber" onKeyUp="removeMeError('bankOfficeNumber')"  value="<?php
                                                               if (isset($bankArray) && is_array($bankArray)) {
                                                                   if (isset($bankArray[0]['bankOfficeNumber'])) {
                                                                       echo htmlentities($bankArray[0]['bankOfficeNumber']);
                                                                   }
                                                               }
                                                               ?>">
                                                    <span class="help-block" id="bankOfficeNumberHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankAddress3Form">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankAddress3"><strong><?php echo ucfirst($leafTranslation['bankAddress3Label']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input class="form-control" type="text" name="bankAddress3" id="bankAddress3" onKeyUp="removeMeError('bankAddress3')"  value="<?php
                                                if (isset($bankArray) && is_array($bankArray)) {
                                                    if (isset($bankArray[0]['bankAddress3'])) {
                                                        echo htmlentities($bankArray[0]['bankAddress3']);
                                                    }
                                                }
                                                ?>">
                                                    <span class="help-block" id="bankAddress3HelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankFaxNumberForm">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankFaxNumber"><strong><?php echo ucfirst($leafTranslation['bankFaxNumberLabel']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input class="form-control" type="text" name="bankFaxNumber" id="bankFaxNumber" onKeyUp="removeMeError('bankFaxNumber')"  value="<?php
                                                           if (isset($bankArray) && is_array($bankArray)) {
                                                               if (isset($bankArray[0]['bankFaxNumber'])) {
                                                                   echo htmlentities($bankArray[0]['bankFaxNumber']);
                                                               }
                                                           }
                                                           ?>">
                                                    <span class="help-block" id="bankFaxNumberHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="contactLegend"><?php echo $t['contactTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('contact', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('contact', 1);">
                                </legend>
                                <div id="contact">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankContactPersonForm">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankContactPerson"><strong><?php echo ucfirst($leafTranslation['bankContactPersonLabel']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input class="form-control" type="text" name="bankContactPerson" id="bankContactPerson" onKeyUp="removeMeError('bankContactPerson')"  value="<?php
                                                        if (isset($bankArray) && is_array($bankArray)) {
                                                            if (isset($bankArray[0]['bankContactPerson'])) {
                                                                echo htmlentities($bankArray[0]['bankContactPerson']);
                                                            }
                                                        }
                                                        ?>">
                                                    <span class="help-block" id="bankContactPersonHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="notesLegend"><?php echo $t['noteTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('notes', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('notes', 1);">
                                </legend>
                                <div class="row" id="notes">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">



                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="bankDescriptionForm">
                                                <label class="control-label col-xs-4 col-sm-4 col-md-4" for="bankDescription"><strong><?php echo ucfirst($leafTranslation['bankDescriptionLabel']); ?></strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <textarea class="form-control" name="bankDescription" id="bankDescription" onKeyUp="removeMeError('bankDescription')"><?php
                                                        if (isset($bankArray[0]['bankDescription'])) {

                                                            echo htmlentities($bankArray[0]['bankDescription']);
                                                        }
                                                        ?></textarea>
                                                    <span class="help-block" id="bankDescriptionHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                </div>
                            </div>

                        </div><div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
                                <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class=caret></span></a> 
                                <ul class="dropdown-menu"> 
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li> 
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li> 
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];  ?></a> </li>--> 
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
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onClick="resetRecord(<?php echo $leafId; ?>, '<?php echo $bank->getControllerPath(); ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)" value="<?php echo $t['resetButtonLabel']; ?>"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="postRecordbutton"  class="btn btn-warning disabled"><i class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $bank->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
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
                function showMeAll(toggle) {
                    showMeDiv('address', toggle);
                    showMeDiv('office', toggle);
                    showMeDiv('contact', toggle);
                    showMeDiv('web', toggle);
                    showMeDiv('notes', toggle);
                }
                $(document).ready(function() {
                    $('.switch')['bootstrapSwitch']();
                    showMeDiv('address', 0);
                    showMeDiv('office', 0);
                    showMeDiv('contact', 0);
                    showMeDiv('notes', 0);
                    $("#addressLegend").on('click', function() {
                        toggle("address");
                    });
                    $("#officeLegend").on('click', function() {
                        toggle("office");
                    });
                    $("#contactLegend").on('click', function() {
                        toggle("contact");
                    });
                    $("#notesLegend").on('click', function() {
                        toggle("notes");
                    });

                    $(document).scrollTop(0);
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('bankId');
                    validateMeNumeric('chartOfAccountId');
                    validateMeAlphaNumeric('bankCode');
                    validateMeAlphaNumeric('bankAccount');
                    validateMeCurrency('bankMinimumValue');
                    validateMeCurrency('bankOverDraft');
                    validateMeAlphaNumeric('bankContactPerson');
                    validateMeAlphaNumeric('bankAddress');
                    validateMeAlphaNumeric('bankAddress1');
                    validateMeAlphaNumeric('bankAddress2');
                    validateMeAlphaNumeric('bankAddress3');
                    validateMeAlphaNumeric('bankPostCode');
                    validateMeAlphaNumeric('bankOfficeNumber');
                    validateMeAlphaNumeric('bankFaxNumber');
                    validateMeAlphaNumeric('bankDescription');
                    validateMeNumeric('isPettyCash');
                    validateMeNumeric('isCollection');
                    validateMeNumeric('isPaymentVoucher');
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-info');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['bankId']) { ?>
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)")
                                    ;
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $bank->getControllerPath(); ?>','<?php echo $bank->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
                });
            </script> 
<?php } ?> 
    </div></div>
</form>
<script type="text/javascript" src="./v3/financial/cashbook/javascript/bank.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>