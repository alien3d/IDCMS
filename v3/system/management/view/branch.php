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
require_once($newFakeDocumentRoot . "v3/system/management/controller/branchController.php");
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
$translator->setCurrentTable('branch');
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
$branchArray = array();
$cityArray = array();
$stateArray = array();
$countryArray = array();

if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $branch = new \Core\System\Management\Branch\Controller\BranchClass();
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
            $branch->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $branch->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $branch->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $branch->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $branch->setStartDay($start[2]);
            $branch->setStartMonth($start[1]);
            $branch->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $branch->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $branch->setEndDay($start[2]);
            $branch->setEndMonth($start[1]);
            $branch->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $branch->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $branch->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $branch->setServiceOutput('html');
        $branch->setLeafId($leafId);
        $branch->execute();
        $cityArray = $branch->getCity();
        $stateArray = $branch->getState();
        $countryArray = $branch->getCountry();
        if ($_POST['method'] == 'read') {
            $branch->setStart($offset);
            $branch->setLimit($limit); // normal system don't like paging..  
            $branch->setPageOutput('html');
            $branchArray = $branch->read();
            if (isset($branchArray [0]['firstRecord'])) {
                $firstRecord = $branchArray [0]['firstRecord'];
            }
            if (isset($branchArray [0]['nextRecord'])) {
                $nextRecord = $branchArray [0]['nextRecord'];
            }
            if (isset($branchArray [0]['previousRecord'])) {
                $previousRecord = $branchArray [0]['previousRecord'];
            }
            if (isset($branchArray [0]['lastRecord'])) {
                $lastRecord = $branchArray [0]['lastRecord'];
                $endRecord = $branchArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($branch->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($branchArray [0]['total'])) {
                $total = $branchArray [0]['total'];
            } else {
                $total = 0;
            }
            $navigation->setTotalRecord($total);
        }
    }
}
?><script type="text/javascript">
    var t =<?php echo json_encode($translator->getDefaultTranslation()); ?>;
    var leafTranslation =<?php echo json_encode($translator->getLeafTranslation()); ?>;</script><?php
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
                <div align="left" class="btn-group col-md-10 pull-left"> 
                    <button title="A" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button> 
                    <button title="B" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button> 
                    <button title="C" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button> 
                    <button title="D" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button> 
                    <button title="E" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button> 
                    <button title="F" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button> 
                    <button title="G" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button> 
                    <button title="H" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button> 
                    <button title="I" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button> 
                    <button title="J" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button> 
                    <button title="K" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button> 
                    <button title="L" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button> 
                    <button title="M" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button> 
                    <button title="N" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button> 
                    <button title="O" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button> 
                    <button title="P" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button> 
                    <button title="Q" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button> 
                    <button title="R" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button> 
                    <button title="S" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button> 
                    <button title="T" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button> 
                    <button title="U" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button> 
                    <button title="V" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button> 
                    <button title="W" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button> 
                    <button title="X" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button> 
                    <button title="Y" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button> 
                    <button title="Z" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button> 
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="right" class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button" >
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" >
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html');">
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

                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onclick="showForm('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['newButtonLabel']; ?></button> 
                            </div>
                            <label for="queryWidget"></label><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                            if (isset($_POST['query'])) {
                                echo $_POST['query'];
                            }
                            ?>">
                            <br>     <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"><?php echo $t['clearButtonLabel']; ?></button>
                            <br>

                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>             
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>            
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a></td>
                                    <td>&nbsp;</td>         </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
                                </tr>
                            </table>
                            <div class="input-group">
                                <input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                if (isset($_POST['dateRangeStart'])) {
                                    echo $_POST['dateRangeStart'];
                                }
                                ?>" onclick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="startDateImage"></span></div>
                            <br>
                            <div class="input-group">
                                <input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                if (isset($_POST['dateRangeEnd'])) {
                                    echo $_POST['dateRangeEnd'];
                                }
                                ?>" onclick="topPage(175);"  placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>">
                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="endDateImage"></span></div>
                            <br>
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
                                        <input type="hidden" name="branchIdPreview" id="branchIdPreview">
                                        <div class="form-group" id="cityIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="cityIdPreview"><?php echo $leafTranslation['cityIdLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="cityIdPreview" id="cityIdPreview">
                                            </div>					</div>					<div class="form-group" id="stateIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="stateIdPreview"><?php echo $leafTranslation['stateIdLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="stateIdPreview" id="stateIdPreview">
                                            </div>					</div>					<div class="form-group" id="countryIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="countryIdPreview" id="countryIdPreview">
                                            </div>					</div>					<div class="form-group" id="branchCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchCodePreview"><?php echo $leafTranslation['branchCodeLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchCodePreview" id="branchCodePreview">
                                            </div>					</div>					<div class="form-group" id="branchNameDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchNamePreview"><?php echo $leafTranslation['branchNameLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchNamePreview" id="branchNamePreview">
                                            </div>					</div>					<div class="form-group" id="branchContactPersonDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchContactPersonPreview"><?php echo $leafTranslation['branchContactPersonLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchContactPersonPreview" id="branchContactPersonPreview">
                                            </div>					</div>					<div class="form-group" id="branchEmailDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchEmailPreview"><?php echo $leafTranslation['branchEmailLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchEmailPreview" id="branchEmailPreview">
                                            </div>					</div>					<div class="form-group" id="branchFaxDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchFaxPreview"><?php echo $leafTranslation['branchFaxLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchFaxPreview" id="branchFaxPreview">
                                            </div>					</div>					<div class="form-group" id="branchPhoneNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchPhoneNumberPreview"><?php echo $leafTranslation['branchPhoneNumberLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchPhoneNumberPreview" id="branchPhoneNumberPreview">
                                            </div>					</div>					<div class="form-group" id="branchAddressDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchAddressPreview"><?php echo $leafTranslation['branchAddressLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchAddressPreview" id="branchAddressPreview">
                                            </div>					</div>					<div class="form-group" id="branchMapsDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchMapsPreview"><?php echo $leafTranslation['branchMapsLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchMapsPreview" id="branchMapsPreview">
                                            </div>					</div>					<div class="form-group" id="branchNameDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchNamePreview"><?php echo $leafTranslation['branchNameLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="branchNamePreview" id="branchNamePreview">
                                            </div>					</div>					</form>
                                </div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0);"><?php echo $t['closeButtonLabel']; ?></button> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData"> 
                                    <thead> 
                                        <tr> 
                                            <th width="25px" align="center"><div align="center">#</div></th>
                                    <th width="100px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="75px"><div align="center"><?php echo ucwords($leafTranslation['branchCodeLabel']); ?></div></th> 
                                    <th ><?php echo ucwords($leafTranslation['branchNameLabel']); ?></th> 
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                                    <th width="25px" align="center"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onChange="toggleChecked(this.checked);"></th>
                                    </tr> 
                                    </thead> 
                                    <tbody id="tableBody"> 
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($branchArray)) {
                                                $totalRecord = intval(count($branchArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($branchArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($branchArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>                       
															<td valign="top" align="center"><div class="btn-group" align="center">
															<?php if($branchArray [$i]['branchCode'] !='UNBL') { ?>
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($branchArray [$i]['branchId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($branchArray [$i]['branchId']); ?>', '<?php echo rawurlencode($branchArray [$i]['cityDescription']); ?>', '<?php echo rawurlencode($branchArray [$i]['stateDescription']); ?>', '<?php echo rawurlencode($branchArray [$i]['countryDescription']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchCode']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchName']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchContactPerson']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchEmail']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchFax']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchPhoneNumber']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchAddress']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchMaps']); ?>', '<?php echo rawurlencode($branchArray [$i]['branchName']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button></div><?php } else { ?>&nbsp;<?php } ?></td> 

                                                            <td valign="top"><div align="center">
                                                                    <?php
                                                                    if (isset($branchArray[$i]['branchCode'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($branchArray[$i]['branchCode']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $branchArray[$i]['branchCode']);
                                                                                } else {
                                                                                    echo $branchArray[$i]['branchCode'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($branchArray[$i]['branchCode']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $branchArray[$i]['branchCode']);
                                                                                } else {
                                                                                    echo $branchArray[$i]['branchCode'];
                                                                                }
                                                                            } else {
                                                                                echo $branchArray[$i]['branchCode'];
                                                                            }
                                                                        } else {
                                                                            echo $branchArray[$i]['branchCode'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                        <?php } ?>
                                                            </td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($branchArray[$i]['branchName'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($branchArray[$i]['branchName']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $branchArray[$i]['branchName']);
                                                                                } else {
                                                                                    echo $branchArray[$i]['branchName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($branchArray[$i]['branchName']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $branchArray[$i]['branchName']);
                                                                                } else {
                                                                                    echo $branchArray[$i]['branchName'];
                                                                                }
                                                                            } else {
                                                                                echo $branchArray[$i]['branchName'];
                                                                            }
                                                                        } else {
                                                                            echo $branchArray[$i]['branchName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                        <?php } ?>
                                                            </td>
                                                            <td valign="top" align="center"><div align="center">
                                                                    <?php
                                                                    if (isset($branchArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($branchArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $branchArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $branchArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($branchArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $branchArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $branchArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $branchArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $branchArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($branchArray[$i]['executeTime'])) {
                                                                $valueArray = $branchArray[$i]['executeTime'];
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
																																																												} else {
																																																													$value=null;
																																																												}
                                                                ?>
                                                                <td valign="top"><?php echo $value; ?></td> 
                                                            <?php
                                                            if ($branchArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td valign="top">
																	<?php if($branchArray [$i]['branchCode'] !='UNBL') { ?>
                                                                <input class="form-control" style="display:none;" type="checkbox" name="branchId[]"  value="<?php echo $branchArray[$i]['branchId']; ?>">
                                                                <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $branchArray[$i]['isDelete']; ?>">
															 <?php } else { ?>&nbsp;<?php } ?>
                                                            </td>
                                                        </tr> 
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr> 
                                                        <td colspan="7" valign="top" align="center"><?php $branch->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                    </tr> 
                                                    <?php
                                                }
                                            } else {
                                                ?> 
                                                <tr> 
                                                    <td colspan="7" valign="top" align="center"><?php $branch->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" valign="top" align="center"><?php $branch->exceptionMessage($t['loadFailureLabel']); ?></td> 
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
                        <div class="col-md-3 pull-right pagination" align="right">
                            <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> 
                                <i class="glyphicon glyphicon-white glyphicon-trash"></i> 
                            </button> 
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
    <form class="form-horizontal">		<input type="hidden" name="branchId" id="branchId" value="<?php
        if (isset($_POST['branchId'])) {
            echo $_POST['branchId'];
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
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="branchId" id="branchId" value="<?php
                            if (isset($_POST['branchId'])) {
                                echo $_POST['branchId'];
                            }
                            ?>">
                             <fieldset>
                                <legend>
    <?php echo $t['informationTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onclick="showMeAll(0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeAll(1);">
                                </legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-xs-9 col-sm-9 col-md-9">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--name-->
                                                        <div class="form-group" id="branchNameForm">
                                                            <label for="branchName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                    <?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['branchNameLabel']
                                                                    );
                                                                    ?>
                                                                </strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="branchName" id="branchName"
                                                                       onkeyup="removeMeError('branchName');" value="<?php
                                                                       if (isset($branchArray) && is_array($branchArray)) {
                                                                           if (isset($branchArray[0]['branchName'])) {
                                                                               echo htmlentities($branchArray[0]['branchName']);
                                                                           }
                                                                       }
                                                                       ?>">
                                                                <span class="help-block" id="branchNameHelpMe"></span> </div>
                                                        </div>
                                                        <!--end name-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--code-->
                                                        <div class="form-group" id="branchCodeForm">
                                                            <label for="branchCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                    <?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['branchCodeLabel']
                                                                    );
                                                                    ?>
                                                                </strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="branchCode" id="branchCode"
                                                                       onkeyup="removeMeError('branchCode');" value="<?php
                                                                       if (isset($branchArray) && is_array($branchArray)) {
                                                                           if (isset($branchArray[0]['branchCode'])) {
                                                                               echo htmlentities($branchArray[0]['branchCode']);
                                                                           }
                                                                       }
                                                                       ?>">
                                                                <span class="help-block" id="branchCodeHelpMe"></span> </div>
                                                        </div>
                                                        <!-- end code-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--Registration Number-->
                                                        <div class="form-group" id="branchRegistrationNumberForm">
                                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchRegistrationNumber"><strong><?php echo ucfirst($leafTranslation['branchRegistrationNumberLabel']); ?></strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input class="form-control input-sm" type="text" name="branchRegistrationNumber" id="branchRegistrationNumber" onkeyup="removeMeError('branchRegistrationNumber');"  value="<?php
                                                                if (isset($branchArray) && is_array($branchArray)) {
                                                                    if (isset($branchArray[0]['branchRegistrationNumber'])) {
                                                                        echo htmlentities($branchArray[0]['branchRegistrationNumber']);
                                                                    }
                                                                }
                                                                ?>">
                                                                <span class="help-block" id="branchRegistrationNumberHelpMe"></span> </div>
                                                        </div>
                                                        <!--end Registration Number-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!-- tax number-->
                                                        <div class="form-group" id="branchTaxNumberForm">
                                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchTaxNumber"><strong><?php echo ucfirst($leafTranslation['branchTaxNumberLabel']); ?></strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input class="form-control input-sm" type="text" name="branchTaxNumber" id="branchTaxNumber" onkeyup="removeMeError('branchTaxNumber');"  value="<?php
                                                                if (isset($branchArray) && is_array($branchArray)) {
                                                                    if (isset($branchArray[0]['branchTaxNumber'])) {
                                                                        echo htmlentities($branchArray[0]['branchTaxNumber']);
                                                                    }
                                                                }
                                                                ?>">
                                                                <span class="help-block" id="branchTaxNumberHelpMe"></span> </div>
                                                        </div>
                                                        <!--end tax number-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!-- description -->
                                                    <div class="form-group" id="branchNameForm">
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <textarea class="form-control" name="branchName" id="branchName"><?php
                                                                if (isset($branchArray[0]['branchAddress'])) {
                                                                    echo htmlentities($branchArray[0]['branchAddress']);
                                                                }
                                                                ?>
                                                            </textarea>
                                                            <span class="help-block" id="branchNameHelpMe"></span> </div>
                                                    </div>
                                                    <!-- end description -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3">
                                            <!-- picture -->
                                            <div class="form form-group" align="center">
                                                <label for="branchPicture" class="control-label col-xs-4 col-sm-4 col-md-4">
                                                    <?php
                                                    echo ucfirst(
                                                            $leafTranslation['branchPictureLabel']
                                                    );
                                                    ?>
                                                </label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input type="hidden" class="form-control" name="branchPicture" id="branchPicture"
                                                           value="<?php echo $branchArray[0]['branchPicture']; ?>">
                                                    <div id="branchPicturePreviewUpload" align="center">
                                                        <ul class="img-thumbnails">
                                                            <li>
                                                                <div class="img-thumbnail" align="center">
                                                                    <?php
                                                                    if (empty($branchArray[0]['branchPicture'])) {
                                                                        $branchArray[0]['branchPicture'] = 'Kathleen_Byrne.jpg';
                                                                    }
                                                                    if (isset($branchArray[0]['branchPicture'])) {
                                                                        if (strlen($branchArray[0]['branchPicture']) > 0) {
                                                                            ?>
                                                                            <img id="imagePreview"
                                                                                 src="./v3/management/images/<?php echo $branchArray[0]['branchPicture']; ?>"
                                                                                 width="80"
                                                                                 height="80">
                                                                                 <?php
                                                                             }
                                                                         }
                                                                         ?>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" align="center">
                                                <div class="col-xs-12 col-sm-12 col-md-12" align="center">
                                                    <div id="branchPictureDiv" class="pull-left" style="text-align:center" align="center">
                                                        <noscript>
                                                        <p>Please enable JavaScript to use file uploader.</p>
                                                        <!-- or put a simple form for upload here -->
                                                        </noscript>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end picture -->
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="addressLegend"><?php echo $t['addressTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onclick="showMeDiv('address', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeDiv('address', 1);">
                                </legend>
                                <div id="address">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!-- start address -->
                                                    <div class="form-group" id="branchAddressForm">
                                                        <label for="branchAddress" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['branchAddressLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <textarea name="branchAddress" id="branchAddress" rows="5"
                                                                      class="form-control"><?php
                                                                          if (isset($branchArray[0]['branchAddress'])) {
                                                                              echo htmlentities($branchArray[0]['branchAddress']);
                                                                          }
                                                                          ?></textarea> <span class="help-block" id="branchAddressHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end address -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--City -->
                                                    <div class="form-group" id="cityIdForm">
                                                        <label for="cityId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['cityIdLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="cityId" id="cityId" class="chzn-select form-control">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($cityArray)) {
                                                                    $totalRecord = intval(count($cityArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        $n = 0;
                                                                        $currentStateDescription = null;
                                                                        $group = 0;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            $d++;
                                                                            $n++;
                                                                            if ($i != 0) {
                                                                                if ($currentStateDescription != $cityArray[$i]['stateDescription']) {
                                                                                    $group = 1;
                                                                                    echo "</optgroup><optgroup label=\"" . $cityArray[$i]['stateDescription'] . "\">";
                                                                                }
                                                                            } else {
                                                                                echo "<optgroup label=\"" . $cityArray[$i]['stateDescription'] . "\">";
                                                                            }
                                                                            $currentStateDescription = $cityArray[$i]['stateDescription'];
                                                                            if (isset($branchArray[0]['cityId'])) {
                                                                                if ($branchArray[0]['cityId'] == $cityArray[$i]['cityId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $cityArray[$i]['cityId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                . <?php echo $cityArray[$i]['cityDescription']; ?></option>
                                                                            <?php
                                                                            $d++;
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
                                                                        <?php
                                                                    }
                                                                    echo "</optgroup>";
                                                                } else {
                                                                    ?>
                                                                    <option value=""><?php echo $t['notAvailableTextLabel']; ?></option>
    <?php } ?>
                                                            </select> <span class="help-block" id="cityIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end city-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--postcode -->
                                                    <div class="form-group" id="branchPostCodeForm">
                                                        <label for="branchPostCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['branchPostCodeLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="branchPostCode"
                                                                       id="branchPostCode"
                                                                       value="<?php
                                                                       if (isset($branchArray) && is_array($branchArray)) {
                                                                           if (isset($branchArray[0]['branchPostCode'])) {
                                                                               echo htmlentities($branchArray[0]['branchPostCode']);
                                                                           }
                                                                       }
                                                                       ?>" maxlength="16">
                                                                <span class="input-group-addon"><img src="./images/icons/postage-stamp.png"></span>
                                                            </div>
                                                            <span class="help-block" id="branchPostCodeHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end postcode-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--State -->
                                                    <div class="form-group" id="stateIdForm">
                                                        <label for="stateId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['stateIdLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="stateId" id="stateId" class="chzn-select form-control" onChange="getCity('<?php echo $leafId; ?>', '<?php echo $branch->getControllerPath(); ?>', '<?php echo $securityToken; ?>');">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($stateArray)) {
                                                                    $totalRecord = intval(count($stateArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($branchArray[0]['stateId'])) {
                                                                                if ($branchArray[0]['stateId'] == $stateArray[$i]['stateId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $stateArray[$i]['stateId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                . <?php echo $stateArray[$i]['stateDescription']; ?></option>
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
                                                            </select> <span class="help-block" id="stateIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end state-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--Country -->
                                                    <div class="form-group" id="countryIdForm">
                                                        <label for="countryId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                echo ucfirst(
                                                                        $leafTranslation['countryIdLabel']
                                                                );
                                                                ?></strong></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="countryId" id="countryId" class="chzn-select form-control">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($countryArray)) {
                                                                    $totalRecord = intval(count($countryArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($branchArray[0]['countryId'])) {
                                                                                if ($branchArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                . <?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                                            </select> <span class="help-block" id="countryIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end country-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="contactLegend"><?php echo $t['contactTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded"
                                         onclick="showMeDiv('contact', 0);">&nbsp;<img
                                         src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeDiv('contact', 1);">
                                </legend>
                                <div id="contact">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchBusinessPhoneForm">
                                                <label for="branchBusinessPhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchBusinessPhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchBusinessPhone"
                                                               id="branchBusinessPhone" onkeyup="removeMeError('branchBusinessPhone');"
                                                               value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchBusinessPhone'])) {
                                                                       echo htmlentities($branchArray[0]['branchBusinessPhone']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/telephone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchBusinessPhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchHomePhoneForm">
                                                <label for="branchHomePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchHomePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchHomePhone"
                                                               id="branchHomePhone" onkeyup="removeMeError('branchHomePhone');" value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchHomePhone'])) {
                                                                       echo htmlentities($branchArray[0]['branchHomePhone']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchHomePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchMobilePhoneForm">
                                                <label for="branchMobilePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchMobilePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchMobilePhone"
                                                               id="branchMobilePhone" onkeyup="removeMeError('branchMobilePhone');"
                                                               value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchMobilePhone'])) {
                                                                       echo htmlentities($branchArray[0]['branchMobilePhone']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/mobile-phone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchMobilePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchFaxNumberForm">
                                                <label for="branchFaxNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchFaxNumberLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchFaxNumber"
                                                               id="branchFaxNumber" value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchFaxNumber'])) {
                                                                       echo htmlentities($branchArray[0]['branchFaxNumber']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone-fax.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchFaxNumberHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="webLegend"><?php echo $t['webTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onclick="showMeDiv('web', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeDiv('web', 1);">
                                </legend>
                                <div id="web">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchEmailForm">
                                                <label for="branchEmail" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchEmailLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchEmail" id="branchEmail"
                                                               onkeyup="removeMeError('branchEmail');" value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchEmail'])) {
                                                                       echo htmlentities($branchArray[0]['branchEmail']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/email.png"></span></div>
                                                    <span class="help-block" id="branchEmailHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchFacebookForm">
                                                <label for="branchFacebook" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchFacebookLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchFacebook"
                                                               id="branchFacebook" onkeyup="removeMeError('branchFacebook');" value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchFacebook'])) {
                                                                       echo htmlentities($branchArray[0]['branchFacebook']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/facebook.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchFacebookHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchTwitterForm">
                                                <label for="branchTwitter" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchTwitterLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchTwitter"
                                                               id="branchTwitter"
                                                               value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchTwitter'])) {
                                                                       echo htmlentities($branchArray[0]['branchTwitter']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/twitter.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchTwitterHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchLinkedInForm">
                                                <label for="branchLinkedIn" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['branchLinkedInLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="branchLinkedIn"
                                                               id="branchLinkedIn" value="<?php
                                                               if (isset($branchArray) && is_array($branchArray)) {
                                                                   if (isset($branchArray[0]['branchLinkedIn'])) {
                                                                       echo htmlentities($branchArray[0]['branchLinkedIn']);
                                                                   }
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/linkedin.png"></span>
                                                    </div>
                                                    <span class="help-block" id="branchLinkedInHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </div><div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
                                <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class="caret"></span></a> 
                                <ul class="dropdown-menu" style="text-align:left"> 
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li> 
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li> 
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];          ?></a> </li>--> 
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];          ?></a> </li>--> 
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li> 
                                </ul> 
                            </div> 
                            <div class="btn-group" align="left">
                                <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> 
                                <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a> 
                                <ul class="dropdown-menu" style="text-align:left"> 
                                    <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a> </li> 
                                 <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];          ?></a> </li> -->
                                    <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?></a> </li> 
                                </ul> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
                            </div>  
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
                            </div> </div> 
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
                    </div></div></div></div></form>
    <script type="text/javascript">
        $(document).keypress(function(e) {
    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                // shift+n new record event
                if (e.which === 78 && e.which === 18  && e.shiftKey) {
                    


                    newRecord(<?php echo $leafId; ?>, '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                // shift+s save event
                if (e.which === 83 && e.which === 18  && e.shiftKey) {
                    


                    updateRecord(<?php echo $leafId; ?>, '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                // shift+d delete event
                if (e.which === 88 && e.which === 18 && e.shiftKey) {
                    


                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;

                }
    <?php } ?>
            // shift+f.find event
            if (e.which === 18 && e.shiftKey) {
                findRecord();
                
                return false;
            }
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $branch->getControllerPath(); ?>', '<?php echo $branch->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
            }
            

        });
        $(document).ready(function() {
            tableHeightSize();
            $(window).resize(function() {
                tableHeightSize();
            });
            showMeDiv('address', 0);
            showMeDiv('contact', 0);
            showMeDiv('web', 0);
            $("#addressLegend").on('click', function() {
                toggle("address");
            });
            $("#contactLegend").on('click', function() {
                toggle("contact");
            });
            $("#webLegend").on('click', function() {
                toggle("web");
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('branchId');
            validateMeNumeric('cityId');
            validateMeNumeric('stateId');
            validateMeNumeric('countryId');
            validateMeAlphaNumeric('branchCode');
            validateMeAlphaNumeric('branchName');
            validateMeAlphaNumeric('branchContactPerson');
            validateMeAlphaNumeric('branchEmail');
            validateMeAlphaNumeric('branchFax');
            validateMeAlphaNumeric('branchPhoneNumber');
            validateMeAlphaNumeric('branchMaps');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>             $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton1').attr('onClick', '');
                $('#updateRecordButton2').attr('onClick', '');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton4').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                $('#firstRecordButton').removeClass().addClass('btn btn-default');
                $('#endRecordButton').removeClass().addClass('btn btn-default');
    <?php } else if ($_POST['branchId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $branch->getControllerPath(); ?>','<?php echo $branch->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
        $('#branchLogoDiv').fineUploader({
            request: {
                endpoint: './v3/system/management/controller/branchController.php'
            },
            validation: {
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                sizeLimit: 20971520,
                stopOnFirstInvalidFile: true
            },
            method: 'POST',
            params: {
                securityToken: '<?php echo $securityToken; ?>',
                method: 'upload',
                output: 'json'
            },
            text: {
                uploadButton: 'Upload a file',
                cancelButton: 'Cancel',
                retryButton: 'Retry',
                failUpload: 'Upload failed',
                dragZone: 'Drop files here to upload',
                formatProgress: "{percent}% of {total_size}",
                waitingForResponse: "Processing..."
            },
            messages: {
                typeError: "{file} has an invalid extension. Valid extension(s): {extensions}.",
                sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
                minSizeError: "{file} is too small, minimum file size is {minSizeLimit}.",
                emptyError: "{file} is empty, please select files again without it.",
                noFilesError: "No files to upload.",
                onLeave: "The files are being uploaded, if you leave now the upload will be cancelled."
            },
            // validation
            // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            // each file size limit in bytes
            // this option isn't supported in all browsers
            sizeLimit: (2 * 1024 * 1024), // max size
            minSizeLimit: 0, // min size
            classes: {
                success: 'alert alert-success',
                fail: 'alert alert-error'
            },
            debug: true
        }).on('error', function(event, id, filename, reason) {
            //do something
        }).
                on('onCancel', function(id, filename) {
                    var message = "<?php echo $t['cancelButtonLabel']; ?>";
                    $("#infoPanelForm").html('').empty().html("<div class='alert alert-error'><img src='./images/icons/smiley-roll-sweat.png'> " + message + "  : " + filename + "</div>");
                }).on('complete', function(event, id, filename, responseJSON) {
            if (responseJSON.success === true) {
                // view image upload
                $("#infoPanelForm")
                        .html('').empty()
                        .html("<div class='alert alert-success'><img src='./images/icons/smiley-roll.png'> <b>Upload complete </b> : " + filename + "</div>");
                $("#branchLogoPreviewUpload")
                        .html("").empty()
                        .html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./v3/system/management/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                $("#branchLogo")
                        .val('').val(filename);
            } else {
                $("#infoPanelForm")
                        .html('').empty()
                        .html("<div class='alert alert-error'><img src='./images/icons/smiley-roll-sweat.png'> <b>Filename</b>  : " + filename + " \n<br><br><b>Error Message</b> :" + responseJSON.error + "</div>");
            }
        }).on('submit', function(event, id, filename) {
            $(this).fineUploader('setParams', {'securityToken': '<?php echo $securityToken; ?>',
                'method': 'upload',
                'output': 'json'});
            var message = "<?php echo $t['loadingTextLabel']; ?>";
            $("#infoPanelForm")
                    .html('').empty()
                    .html("<div class='alert alert-info'><img src='./images/icons/smiley-roll.png'> " + message + " Id: " + id + "  : " + filename + "</div>");
        });
        function showMeAll(toggle) {
            showMeDiv('address', toggle);
            showMeDiv('contact', toggle);
            showMeDiv('web', toggle);
        }
    </script> 
<?php } ?> 
<script type="text/javascript" src="./v3/system/management/javascript/branch.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>