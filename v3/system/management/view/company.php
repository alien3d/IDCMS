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
require_once($newFakeDocumentRoot . "v3/system/management/controller/companyController.php");
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
$translator->setCurrentTable('company');
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
$companyArray = array();
$cityArray = array();
$stateArray = array();
$countryArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $company = new \Core\System\Management\Company\Controller\CompanyClass();
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
            $company->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $company->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $company->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $company->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $company->setStartDay($start[2]);
            $company->setStartMonth($start[1]);
            $company->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $company->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $company->setEndDay($start[2]);
            $company->setEndMonth($start[1]);
            $company->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $company->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $company->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $company->setServiceOutput('html');
        $company->setLeafId($leafId);
        $company->execute();
        $cityArray = $company->getCity();
        $stateArray = $company->getState();
        $countryArray = $company->getCountry();
        if ($_POST['method'] == 'read') {
            $company->setStart($offset);
            $company->setLimit($limit); // normal system don't like paging..  
            $company->setPageOutput('html');
            $companyArray = $company->read();
            if (isset($companyArray [0]['firstRecord'])) {
                $firstRecord = $companyArray [0]['firstRecord'];
            }
            if (isset($companyArray [0]['nextRecord'])) {
                $nextRecord = $companyArray [0]['nextRecord'];
            }
            if (isset($companyArray [0]['previousRecord'])) {
                $previousRecord = $companyArray [0]['previousRecord'];
            }
            if (isset($companyArray [0]['lastRecord'])) {
                $lastRecord = $companyArray [0]['lastRecord'];
                $endRecord = $companyArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($company->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($companyArray [0]['total'])) {
                $total = $companyArray [0]['total'];
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
    var leafTranslation =<?php echo json_encode($translator->getLeafTranslation()); ?>;</script>
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
                <div align="left" class="btn-group col-md-10 pull-left">
                    <button title="A" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button>
                    <button title="B" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button>
                    <button title="C" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button>
                    <button title="D" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button>
                    <button title="E" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button>
                    <button title="F" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button>
                    <button title="G" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button>
                    <button title="H" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button>
                    <button title="I" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button>
                    <button title="J" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button>
                    <button title="K" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button>
                    <button title="L" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button>
                    <button title="M" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button>
                    <button title="N" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button>
                    <button title="O" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button>
                    <button title="P" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button>
                    <button title="Q" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button>
                    <button title="R" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button>
                    <button title="S" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button>
                    <button title="T" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button>
                    <button title="U" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button>
                    <button title="V" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button>
                    <button title="W" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button>
                    <button title="X" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button>
                    <button title="Y" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button>
                    <button title="Z" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="right" class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning" type="button" > <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" > <span class="caret"></span> </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li> <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a> </li>
                                <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv');"> <i class ="pull-right glyphicon glyphicon-download"></i>CSV </a> </li>
                                <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'html');"> <i class ="pull-right glyphicon glyphicon-download"></i>Html </a> </li>
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
                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onclick="showForm('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['newButtonLabel']; ?></button>
                            </div>
                             <label for="queryWidget"></label><div class="input-group"><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                                                                                 if (isset($_POST['query'])) {
                                                                                                     echo $_POST['query'];
                                                                                                 }
                                                                                                 ?>"><span class="input-group-addon"><img src="./images/icons/magnifier.png" id="searchTextDateImage"></span></div><br>
                                <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $company->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $company->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">
                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>
                                    <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
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
                                        <input type="hidden" name="companyIdPreview" id="companyIdPreview">
                                        <div class="form-group" id="companyCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyCodePreview"><?php echo $leafTranslation['companyCodeLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyCodePreview" id="companyCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyLogoDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyLogoPreview"><?php echo $leafTranslation['companyLogoLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyLogoPreview" id="companyLogoPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyRegistrationNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyRegistrationNumberPreview"><?php echo $leafTranslation['companyRegistrationNumberLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyRegistrationNumberPreview" id="companyRegistrationNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyTaxNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyTaxNumberPreview"><?php echo $leafTranslation['companyTaxNumberLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyTaxNumberPreview" id="companyTaxNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyDescriptionPreview"><?php echo $leafTranslation['companyDescriptionLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyDescriptionPreview" id="companyDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyNameDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyNamePreview"><?php echo $leafTranslation['companyNameLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyNamePreview" id="companyNamePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyEmailDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyEmailPreview"><?php echo $leafTranslation['companyEmailLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyEmailPreview" id="companyEmailPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyMobilePhoneDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyMobilePhonePreview"><?php echo $leafTranslation['companyMobilePhoneLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyMobilePhonePreview" id="companyMobilePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyOfficePhoneDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyOfficePhonePreview"><?php echo $leafTranslation['companyOfficePhoneLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyOfficePhonePreview" id="companyOfficePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyFaxNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyFaxNumberPreview"><?php echo $leafTranslation['companyFaxNumberLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyFaxNumberPreview" id="companyFaxNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyAddressDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyAddressPreview"><?php echo $leafTranslation['companyAddressLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyAddressPreview" id="companyAddressPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyCityDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyCityPreview"><?php echo $leafTranslation['companyCityLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyCityPreview" id="companyCityPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyStateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyStatePreview"><?php echo $leafTranslation['companyStateLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyStatePreview" id="companyStatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyPostcodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyPostcodePreview"><?php echo $leafTranslation['companyPostcodeLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyPostcodePreview" id="companyPostcodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyCountryDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyCountryPreview"><?php echo $leafTranslation['companyCountryLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyCountryPreview" id="companyCountryPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyWebPageDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyWebPagePreview"><?php echo $leafTranslation['companyWebPageLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyWebPagePreview" id="companyWebPagePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyFacebookDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyFacebookPreview"><?php echo $leafTranslation['companyFacebookLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyFacebookPreview" id="companyFacebookPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyTwitterDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyTwitterPreview"><?php echo $leafTranslation['companyTwitterLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyTwitterPreview" id="companyTwitterPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="companyMapsDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyMapsPreview"><?php echo $leafTranslation['companyMapsLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="companyMapsPreview" id="companyMapsPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0);"><?php echo $t['closeButtonLabel']; ?></button>
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
                                    <th width="100px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                    <th width="75px"><div align="center"><?php echo ucwords($leafTranslation['companyCodeLabel']); ?></div></th>
                                    <th><?php echo ucwords($leafTranslation['companyNameLabel']); ?></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record" onChange="toggleChecked(this.checked);"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($companyArray)) {
                                                $totalRecord = intval(count($companyArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($companyArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($companyArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td valign="top" align="center"><div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($companyArray [$i]['companyId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($companyArray [$i]['companyCode']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyLogo']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyRegistrationNumber']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyTaxNumber']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyDescription']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyName']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyEmail']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyMobilePhone']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyOfficePhone']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyFaxNumber']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyAddress']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyCity']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyState']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyPostcode']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyCountry']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyWebPage']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyFacebook']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyTwitter']); ?>', '<?php echo rawurlencode($companyArray [$i]['companyMaps']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div></td>
                                                            <td valign="top"><div align="center">
                                                                    <?php
                                                                    if (isset($companyArray[$i]['companyCode'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($companyArray[$i]['companyCode']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $companyArray[$i]['companyCode']);
                                                                                } else {
                                                                                    echo $companyArray[$i]['companyCode'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($companyArray[$i]['companyCode']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $companyArray[$i]['companyCode']);
                                                                                } else {
                                                                                    echo $companyArray[$i]['companyCode'];
                                                                                }
                                                                            } else {
                                                                                echo $companyArray[$i]['companyCode'];
                                                                            }
                                                                        } else {
                                                                            echo $companyArray[$i]['companyCode'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                        <?php } ?>
                                                            </td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($companyArray[$i]['companyName'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($companyArray[$i]['companyName']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $companyArray[$i]['companyName']);
                                                                                } else {
                                                                                    echo $companyArray[$i]['companyName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($companyArray[$i]['companyName']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $companyArray[$i]['companyName']);
                                                                                } else {
                                                                                    echo $companyArray[$i]['companyName'];
                                                                                }
                                                                            } else {
                                                                                echo $companyArray[$i]['companyName'];
                                                                            }
                                                                        } else {
                                                                            echo $companyArray[$i]['companyName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                        <?php } ?>
                                                            </td>
                                                            <td valign="top" align="center"><div align="center">
                                                                    <?php
                                                                    if (isset($companyArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($companyArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $companyArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $companyArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($companyArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $companyArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $companyArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $companyArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $companyArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($companyArray[$i]['executeTime'])) {
                                                                $valueArray = $companyArray[$i]['executeTime'];
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
																																																												}
                                                                ?>
                                                                <td valign="top"><?php echo $value; ?></td>
                                                            <?php
                                                            if ($companyArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td valign="top"><input class="form-control" style="display:none;" type="checkbox" name="companyId[]"  value="<?php echo $companyArray[$i]['companyId']; ?>">
                                                                <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $companyArray[$i]['isDelete']; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" valign="top" align="center"><?php $company->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" valign="top" align="center"><?php $company->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" valign="top" align="center"><?php $company->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                            <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
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
        <input type="hidden" name="companyId" id="companyId" value="<?php
        if (isset($_POST['companyId'])) {
            echo $_POST['companyId'];
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
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
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
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="companyNameForm">
                                                            <label for="companyName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                    <?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['companyNameLabel']
                                                                    );
                                                                    ?>
                                                                </strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="companyName" id="companyName"
                                                                       onkeyup="removeMeError('companyName',12);" value="<?php
                                                                       if (isset($companyArray) && is_array($companyArray)) {
                                                                           if (isset($companyArray[0]['companyName'])) {
                                                                               echo htmlentities($companyArray[0]['companyName']);
                                                                           }
                                                                       }
                                                                       ?>">
                                                                <span class="help-block" id="companyNameHelpMe"></span> </div>
                                                        </div>
                                                        <!--end name-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--code-->
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="companyCodeForm">
                                                            <label for="companyCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                    <?php
                                                                    echo ucfirst(
                                                                            $leafTranslation['companyCodeLabel']
                                                                    );
                                                                    ?>
                                                                </strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="companyCode" id="companyCode" value="<?php
                                                                       if (isset($companyArray) && is_array($companyArray)) {
                                                                           if (isset($companyArray[0]['companyCode'])) {
                                                                               echo htmlentities($companyArray[0]['companyCode']);
                                                                           }
                                                                       }
                                                                       ?>">
                                                                <span class="help-block" id="companyCodeHelpMe"></span> </div>
                                                        </div>
                                                        <!-- end code-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!--Registration Number-->
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="companyRegistrationNumberForm">
                                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyRegistrationNumber"><strong><?php echo ucfirst($leafTranslation['companyRegistrationNumberLabel']); ?></strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input class="form-control input-sm" type="text" name="companyRegistrationNumber" id="companyRegistrationNumber" value="<?php
                                                                if (isset($companyArray) && is_array($companyArray)) {
                                                                    if (isset($companyArray[0]['companyRegistrationNumber'])) {
                                                                        echo htmlentities($companyArray[0]['companyRegistrationNumber']);
                                                                    }
                                                                }
                                                                ?>">
                                                                <span class="help-block" id="companyRegistrationNumberHelpMe"></span> </div>
                                                        </div>
                                                        <!--end Registration Number-->
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <!-- tax number-->
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-group" id="companyTaxNumberForm">
                                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="companyTaxNumber"><strong><?php echo ucfirst($leafTranslation['companyTaxNumberLabel']); ?></strong></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input class="form-control input-sm" type="text" name="companyTaxNumber" id="companyTaxNumber" value="<?php
                                                                if (isset($companyArray) && is_array($companyArray)) {
                                                                    if (isset($companyArray[0]['companyTaxNumber'])) {
                                                                        echo htmlentities($companyArray[0]['companyTaxNumber']);
                                                                    }
                                                                }
                                                                ?>">
                                                                <span class="help-block" id="companyTaxNumberHelpMe"></span> </div>
                                                        </div>
                                                        <!--end tax number-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!-- description -->
                                                    <div class="form-group" id="companyDescriptionForm">
                                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                                            <textarea class="form-control" name="companyDescription" id="companyDescription"><?php
                                                                if (isset($companyArray[0]['companyAddress'])) {
                                                                    echo htmlentities($companyArray[0]['companyAddress']);
                                                                }
                                                                ?></textarea>
                                                            <span class="help-block" id="companyDescriptionHelpMe"></span> </div>
                                                    </div>
                                                    <!-- end description -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-sm-3 col-md-3">
                                            <!-- picture -->
                                            <div class="form form-group" align="center">
                                                <label for="companyPicture" class="control-label col-xs-4 col-sm-4 col-md-4">
                                                    <?php
                                                    echo ucfirst(
                                                            $leafTranslation['companyPictureLabel']
                                                    );
                                                    ?>
                                                </label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <input type="hidden" class="form-control" name="companyPicture" id="companyPicture" value="<?php echo $companyArray[0]['companyPicture']; ?>">
                                                    <div id="companyPicturePreviewUpload" align="center">
                                                        <ul class="img-thumbnails">
                                                            <li>
                                                                <div class="img-thumbnail" align="center">
                                                                    <?php
                                                                    if (empty($companyArray[0]['companyPicture'])) {
                                                                        $companyArray[0]['companyPicture'] = 'Kathleen_Byrne.jpg';
                                                                    }
                                                                    if (isset($companyArray[0]['companyPicture'])) {
                                                                        if (strlen($companyArray[0]['companyPicture']) > 0) {
                                                                            ?>
                                                                            <img id="imagePreview"
                                                                                 src="./v3/system/management/images/<?php echo $companyArray[0]['companyPicture']; ?>"
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
                                                    <div id="companyPictureDiv" class="pull-left" style="text-align:center" align="center">
                                                        <noscript>
                                                        <p>Please enable JavaScript to use file uploader.</p>
                                                        <!-- or put a simple form for upload here -->
                                                        </noscript>
														sini
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end picture -->
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="addressLegend">
    <?php echo $t['addressTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onclick="showMeDiv('address', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeDiv('address', 1);">
                                </legend>
                                <div id="address">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!-- start address -->
                                                    <div class="form-group" id="companyAddressForm">
                                                        <label for="companyAddress" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                <?php
                                                                echo ucfirst(
                                                                        $leafTranslation['companyAddressLabel']
                                                                );
                                                                ?>
                                                            </strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <textarea name="companyAddress" id="companyAddress" rows="5"
                                                                      class="form-control"><?php
                                                                          if (isset($companyArray[0]['companyAddress'])) {
                                                                              echo htmlentities($companyArray[0]['companyAddress']);
                                                                          }
                                                                          ?>
                                                            </textarea>
                                                            <span class="help-block" id="companyAddressHelpMe"></span> </div>
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
                                                        <label for="cityId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                <?php
                                                                echo ucfirst(
                                                                        $leafTranslation['cityIdLabel']
                                                                );
                                                                ?>
                                                            </strong></label>
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
                                                                            if (isset($companyArray[0]['cityId'])) {
                                                                                if ($companyArray[0]['cityId'] == $cityArray[$i]['cityId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $cityArray[$i]['cityId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $cityArray[$i]['cityDescription']; ?></option>
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
                                                            </select>
                                                            <span class="help-block" id="cityIdHelpMe"></span> </div>
                                                    </div>
                                                    <!-- end city-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--postcode -->
                                                    <div class="form-group" id="companyPostCodeForm">
                                                        <label for="companyPostCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                <?php
                                                                echo ucfirst(
                                                                        $leafTranslation['companyPostCodeLabel']
                                                                );
                                                                ?>
                                                            </strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="companyPostCode"
                                                                       id="companyPostCode"
                                                                       value="<?php
                                                                       if (isset($companyArray) && is_array($companyArray)) {
                                                                           if (isset($companyArray[0]['companyPostCode'])) {
                                                                               echo htmlentities($companyArray[0]['companyPostCode']);
                                                                           }
                                                                       }
                                                                       ?>" maxlength="16">
                                                                <span class="input-group-addon"><img src="./images/icons/postage-stamp.png"></span> </div>
                                                            <span class="help-block" id="companyPostCodeHelpMe"></span> </div>
                                                    </div>
                                                    <!-- end postcode-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--State -->
                                                    <div class="form-group" id="stateIdForm">
                                                        <label for="stateId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                <?php
                                                                echo ucfirst(
                                                                        $leafTranslation['stateIdLabel']
                                                                );
                                                                ?>
                                                            </strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="stateId" id="stateId" class="chzn-select form-control" onChange="getCity('<?php echo $leafId; ?>', '<?php echo $company->getControllerPath(); ?>', '<?php echo $securityToken; ?>');">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($stateArray)) {
                                                                    $totalRecord = intval(count($stateArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($companyArray[0]['stateId'])) {
                                                                                if ($companyArray[0]['stateId'] == $stateArray[$i]['stateId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $stateArray[$i]['stateId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $stateArray[$i]['stateDescription']; ?></option>
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
                                                            <span class="help-block" id="stateIdHelpMe"></span> </div>
                                                    </div>
                                                    <!-- end state-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <!--Country -->
                                                    <div class="form-group" id="countryIdForm">
                                                        <label for="countryId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                                <?php
                                                                echo ucfirst(
                                                                        $leafTranslation['countryIdLabel']
                                                                );
                                                                ?>
                                                            </strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="countryId" id="countryId" class="chzn-select form-control">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($countryArray)) {
                                                                    $totalRecord = intval(count($countryArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($companyArray[0]['countryId'])) {
                                                                                if ($companyArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = null;
                                                                                }
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                            ?>
                                                                            <option
                                                                                value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?> . <?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                                            <span class="help-block" id="countryIdHelpMe"></span> </div>
                                                    </div>
                                                    <!-- end country-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="contactLegend">
    <?php echo $t['contactTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded"
                                         onclick="showMeDiv('contact', 0);">&nbsp;<img
                                         src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeDiv('contact', 1);">
                                </legend>
                                <div id="contact">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyOfficePhoneForm">
                                                <label for="companyOfficePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyOfficePhoneLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyOfficePhone"
                                                               id="companyOfficePhone"
                                                               value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyOfficePhone'])) {
                                                                       echo htmlentities($companyArray[0]['companyOfficePhone']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img
                                                                src="./images/icons/telephone.png"></span> </div>
                                                    <span class="help-block" id="companyOfficePhoneHelpMe"></span> </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyOfficePhoneSecondaryForm">
                                                <label for="OfficePhoneSecondary" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyOfficePhoneSecondaryLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="OfficePhoneSecondary"
                                                               id="OfficePhoneSecondary" value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['OfficePhoneSecondary'])) {
                                                                       echo htmlentities($companyArray[0]['OfficePhoneSecondary']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/telephone.png"></span> </div>
                                                    <span class="help-block" id="OfficePhoneSecondaryHelpMe"></span> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyMobilePhoneForm">
                                                <label for="companyMobilePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyMobilePhoneLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyMobilePhone"
                                                               id="companyMobilePhone" onkeyup="removeMeError('companyMobilePhone');"
                                                               value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyMobilePhone'])) {
                                                                       echo htmlentities($companyArray[0]['companyMobilePhone']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/mobile-phone.png"></span> </div>
                                                    <span class="help-block" id="companyMobilePhoneHelpMe"></span> </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyFaxNumberForm">
                                                <label for="companyFaxNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyFaxNumberLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyFaxNumber"
                                                               id="companyFaxNumber" value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyFaxNumber'])) {
                                                                       echo htmlentities($companyArray[0]['companyFaxNumber']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/telephone-fax.png"></span> </div>
                                                    <span class="help-block" id="companyFaxNumberHelpMe"></span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="webLegend">
    <?php echo $t['webTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onclick="showMeDiv('web', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onclick="showMeDiv('web', 1);">
                                </legend>
                                <div id="web">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyEmailForm">
                                                <label for="companyEmail" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyEmailLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyEmail" id="companyEmail"
                                                               onkeyup="removeMeError('companyEmail');" value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyEmail'])) {
                                                                       echo htmlentities($companyArray[0]['companyEmail']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/email.png"></span></div>
                                                    <span class="help-block" id="companyEmailHelpMe"></span> </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyFacebookForm">
                                                <label for="companyFacebook" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyFacebookLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyFacebook"
                                                               id="companyFacebook" onkeyup="removeMeError('companyFacebook');" value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyFacebook'])) {
                                                                       echo htmlentities($companyArray[0]['companyFacebook']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/facebook.png"></span> </div>
                                                    <span class="help-block" id="companyFacebookHelpMe"></span> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyTwitterForm">
                                                <label for="companyTwitter" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyTwitterLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyTwitter"
                                                               id="companyTwitter"
                                                               value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyTwitter'])) {
                                                                       echo htmlentities($companyArray[0]['companyTwitter']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img
                                                                src="./images/icons/twitter.png"></span> </div>
                                                    <span class="help-block" id="companyTwitterHelpMe"></span> </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="companyLinkedInForm">
                                                <label for="companyLinkedIn" class="control-label col-xs-4 col-sm-4 col-md-4"><strong>
                                                        <?php
                                                        echo ucfirst(
                                                                $leafTranslation['companyLinkedInLabel']
                                                        );
                                                        ?>
                                                    </strong></label>
                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="companyLinkedIn"
                                                               id="companyLinkedIn" value="<?php
                                                               if (isset($companyArray) && is_array($companyArray)) {
                                                                   if (isset($companyArray[0]['companyLinkedIn'])) {
                                                                       echo htmlentities($companyArray[0]['companyLinkedIn']);
                                                                   }
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/linkedin.png"></span> </div>
                                                    <span class="help-block" id="companyLinkedInHelpMe"></span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group" align="left"> <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li>
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];         ?></a> </li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];         ?></a> </li>-->
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li>
                                </ul>
                            </div>
                              <div class="btn-group" align="left">
            <a id="updateRecordButton1"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php  echo $t['updateButtonLabel']; ?> </a> 
            <a id="updateRecordButton2" data-toggle="dropdown"><span class="caret"></span></a> 
            <ul class="dropdown-menu"> 
                <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php  echo $t['updateButtonLabel']; ?></a> </li> 
             <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel']; ?></a> </li> -->
                <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php  echo $t['updateListingButtonLabel']; ?></a> </li> 
            </ul> 
        </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $company->getControllerPath(); ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $company->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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
        </div>
    </form>
    <script type="text/javascript">
        $(document).keypress(function(e) {

        $(document).ready(function() {
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
            validateMeAlphaNumeric('companyCode');
            validateMeAlphaNumeric('companyLogo');
            validateMeAlphaNumeric('companyRegistrationNumber');
            validateMeAlphaNumeric('companyTaxNumber');
            validateMeAlphaNumeric('companyName');
            validateMeAlphaNumeric('companyEmail');
            validateMeAlphaNumeric('companyMobilePhone');
            validateMeAlphaNumeric('companyOfficePhone');
            validateMeAlphaNumeric('companyFaxNumber');
            validateMeAlphaNumeric('companyCity');
            validateMeAlphaNumeric('companyState');
            validateMeAlphaNumeric('companyPostcode');
            validateMeAlphaNumeric('companyCountry');
            validateMeAlphaNumeric('companyWebPage');
            validateMeAlphaNumeric('companyFacebook');
            validateMeAlphaNumeric('companyTwitter');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>     
				alert("l0l salag");
			//	$('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
           //     $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton1').attr('onClick', '');
                $('#updateRecordButton2').attr('onClick', '');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                $('#firstRecordButton').removeClass().addClass('btn btn-default');
                $('#endRecordButton').removeClass().addClass('btn btn-default');
    <?php } else if ($_POST['companyId']) { ?>
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
			$('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info'); 
             $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php  echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php  echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"); 
             $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php  echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
	 <?php } else { ?>
                   $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                   $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $company->getControllerPath(); ?>','<?php echo $company->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
        $('#employeeLogoDiv').fineUploader({
            request: {
                endpoint: './v3/system/management/controller/companyController.php'
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
                $("#companyLogoPreviewUpload")
                        .html("").empty()
                        .html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./v3/system/management/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                $("#companyLogo")
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
        // tooogle play play

        // end upload avatar

        function showMeAll(toggle) {
            showMeDiv('address', toggle);
            showMeDiv('contact', toggle);
            showMeDiv('web', toggle);
        }
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/system/management/javascript/company.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>
