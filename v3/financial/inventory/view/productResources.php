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

require_once($newFakeDocumentRoot . "v3/financial/inventory/controller/productResourcesController.php");

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

$translator->setCurrentTable('productResources');
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
$productResourcesArray = array();
$productBatchArray = array();
$invoiceArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $productResources = new \Core\Financial\Inventory\ProductResources\Controller\ProductResourcesClass();
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
            $productResources->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $productResources->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $productResources->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $productResources->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $productResources->setStartDay($start[2]);
            $productResources->setStartMonth($start[1]);
            $productResources->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $productResources->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $productResources->setEndDay($start[2]);
            $productResources->setEndMonth($start[1]);
            $productResources->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $productResources->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $productResources->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $productResources->setServiceOutput('html');
        $productResources->setLeafId($leafId);
        $productResources->execute();
        $productBatchArray = $productResources->getProductBatch();
        $invoiceArray = $productResources->getInvoice();
        if ($_POST['method'] == 'read') {
            $productResources->setStart($offset);
            $productResources->setLimit($limit); // normal system don't like paging..  
            $productResources->setPageOutput('html');
            $productResourcesArray = $productResources->read();
            if (isset($productResourcesArray [0]['firstRecord'])) {
                $firstRecord = $productResourcesArray [0]['firstRecord'];
            }
            if (isset($productResourcesArray [0]['nextRecord'])) {
                $nextRecord = $productResourcesArray [0]['nextRecord'];
            }
            if (isset($productResourcesArray [0]['previousRecord'])) {
                $previousRecord = $productResourcesArray [0]['previousRecord'];
            }
            if (isset($productResourcesArray [0]['lastRecord'])) {
                $lastRecord = $productResourcesArray [0]['lastRecord'];
                $endRecord = $productResourcesArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($productResources->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($productResourcesArray [0]['total'])) {
                $total = $productResourcesArray [0]['total'];
            } else {
                $total = 0;
            }
            $navigation->setTotalRecord($total);
        }
    }
}
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
                    <button title="A" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button>
                    <button title="B" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button>
                    <button title="C" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button>
                    <button title="D" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button>
                    <button title="E" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button>
                    <button title="F" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button>
                    <button title="G" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button>
                    <button title="H" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button>
                    <button title="I" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button>
                    <button title="J" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button>
                    <button title="K" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button>
                    <button title="L" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button>
                    <button title="M" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button>
                    <button title="N" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button>
                    <button title="O" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button>
                    <button title="P" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button>
                    <button title="Q" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button>
                    <button title="R" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button>
                    <button title="S" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button>
                    <button title="T" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button>
                    <button title="U" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button>
                    <button title="V" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button>
                    <button title="W" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button>
                    <button title="X" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button>
                    <button title="Y" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button>
                    <button title="Z" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="left" class="pull-left">
                        <div class="btn-group">
                            <button class="btn btn-warning"> <i class="glyphicon glyphicon-print glyphicon-white"></i> </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" > <span class="caret"></span> </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li> <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel');"> <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a> </li>
                                <li> <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv');"> <i class ="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;</a> </li>
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
                            <form class="form-forizontal">
                                <div id="btnList">

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onclick="showForm('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label>
                                <input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                if (isset($_POST['query'])) {
                                    echo $_POST['query'];
                                }
                                ?>">
                                <br>
                                <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"><?php echo $t['clearButtonLabel']; ?></button>
                                <br>

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt=" <?php echo $t['allDay'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" rel="tooltip" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png" alt=" <?php echo $t['day'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a> </td>
                                        <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td>
                                        <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
                                    </tr>
                                </table>

                                <div>
                                    <label for="dateRangeStart"></label>
                                    <input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" onclick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                    <br>
                                    <label for="dateRangeEnd"></label>
                                    <input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" onclick="topPage(175);">
                                    <br>
                                    <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                                    <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><?php echo $t['clearButtonLabel']; ?></button>
                                </div>
                            </form>
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
                                        <input type="hidden" name="productResourcesIdPreview" id="productResourcesIdPreview">
                                        <div class="form-group" id="productBatchIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productBatchIdPreview"><?php echo $leafTranslation['productBatchIdLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productBatchIdPreview" id="productBatchIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="invoiceIdPreview"><?php echo $leafTranslation['invoiceIdLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="invoiceIdPreview" id="invoiceIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesTaskDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesTaskPreview"><?php echo $leafTranslation['productResourcesTaskLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesTaskPreview" id="productResourcesTaskPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesEstimatedDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesEstimatedDatePreview"><?php echo $leafTranslation['productResourcesEstimatedDateLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedDatePreview" id="productResourcesEstimatedDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesActualDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesActualDatePreview"><?php echo $leafTranslation['productResourcesActualDateLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualDatePreview" id="productResourcesActualDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesEstimatedEmployeeCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesEstimatedEmployeeCostPreview"><?php echo $leafTranslation['productResourcesEstimatedEmployeeCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedEmployeeCostPreview" id="productResourcesEstimatedEmployeeCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesActualEmployeeCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesActualEmployeeCostPreview"><?php echo $leafTranslation['productResourcesActualEmployeeCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualEmployeeCostPreview" id="productResourcesActualEmployeeCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesEstimatedMachineCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesEstimatedMachineCostPreview"><?php echo $leafTranslation['productResourcesEstimatedMachineCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedMachineCostPreview" id="productResourcesEstimatedMachineCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesActualMachineCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesActualMachineCostPreview"><?php echo $leafTranslation['productResourcesActualMachineCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualMachineCostPreview" id="productResourcesActualMachineCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesEstimatedAdditionalCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesEstimatedAdditionalCostPreview"><?php echo $leafTranslation['productResourcesEstimatedAdditionalCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedAdditionalCostPreview" id="productResourcesEstimatedAdditionalCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesActualAdditionalCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesActualAdditionalCostPreview"><?php echo $leafTranslation['productResourcesActualAdditionalCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualAdditionalCostPreview" id="productResourcesActualAdditionalCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesEstimatedBillOfMaterialCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesEstimatedBillOfMaterialCostPreview"><?php echo $leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedBillOfMaterialCostPreview" id="productResourcesEstimatedBillOfMaterialCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesActualBillOfMaterialCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesActualBillOfMaterialCostPreview"><?php echo $leafTranslation['productResourcesActualBillOfMaterialCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualBillOfMaterialCostPreview" id="productResourcesActualBillOfMaterialCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesEstimatedTotalCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesEstimatedTotalCostPreview"><?php echo $leafTranslation['productResourcesEstimatedTotalCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedTotalCostPreview" id="productResourcesEstimatedTotalCostPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="productResourcesActualTotalCostDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productResourcesActualTotalCostPreview"><?php echo $leafTranslation['productResourcesActualTotalCostLabel']; ?></label>
                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualTotalCostPreview" id="productResourcesActualTotalCostPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
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
                                    <th width="125px"><?php echo ucwords($leafTranslation['productBatchIdLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['invoiceIdLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['productResourcesTaskLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['productResourcesEstimatedDateLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['productResourcesActualDateLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['productResourcesEstimatedTotalCostLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['productResourcesActualTotalCostLabel']); ?></th>
                                    <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record" onChange="toggleChecked(this.checked);"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($productResourcesArray)) {
                                                $totalRecord = intval(count($productResourcesArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($productResourcesArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($productResourcesArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>
                                                            <td valign="top" align="center"><div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($productResourcesArray [$i]['productResourcesId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($productResourcesArray [$i]['productResourcesId']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productBatchDescription']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['invoiceDescription']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesTask']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesEstimatedDate']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesActualDate']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesEstimatedEmployeeCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesActualEmployeeCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesEstimatedMachineCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesActualMachineCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesEstimatedAdditionalCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesActualAdditionalCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesEstimatedBillOfMaterialCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesActualBillOfMaterialCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesEstimatedTotalCost']); ?>', '<?php echo rawurlencode($productResourcesArray [$i]['productResourcesActualTotalCost']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div></td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($productResourcesArray[$i]['productBatchDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($productResourcesArray[$i]['productBatchDescription'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productResourcesArray[$i]['productBatchDescription']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['productBatchDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($productResourcesArray[$i]['productBatchDescription'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productResourcesArray[$i]['productBatchDescription']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['productBatchDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $productResourcesArray[$i]['productBatchDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $productResourcesArray[$i]['productBatchDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($productResourcesArray[$i]['invoiceDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($productResourcesArray[$i]['invoiceDescription'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productResourcesArray[$i]['invoiceDescription']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['invoiceDescription'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($productResourcesArray[$i]['invoiceDescription'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productResourcesArray[$i]['invoiceDescription']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['invoiceDescription'];
                                                                                }
                                                                            } else {
                                                                                echo $productResourcesArray[$i]['invoiceDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $productResourcesArray[$i]['invoiceDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top"><div align="left">
                                                                    <?php
                                                                    if (isset($productResourcesArray[$i]['productResourcesTask'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(strtolower($productResourcesArray[$i]['productResourcesTask']), strtolower($_POST['query'])) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productResourcesArray[$i]['productResourcesTask']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['productResourcesTask'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos(strtolower($productResourcesArray[$i]['productResourcesTask']), strtolower($_POST['character'])) !== false) {
                                                                                    echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productResourcesArray[$i]['productResourcesTask']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['productResourcesTask'];
                                                                                }
                                                                            } else {
                                                                                echo $productResourcesArray[$i]['productResourcesTask'];
                                                                            }
                                                                        } else {
                                                                            echo $productResourcesArray[$i]['productResourcesTask'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($productResourcesArray[$i]['productResourcesEstimatedDate'])) {
                                                                $valueArray = $productResourcesArray[$i]['productResourcesEstimatedDate'];
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
                                                                <td valign="top"><?php echo $value; ?></td>
                                                            <?php } else { ?>
                                                                <td valign="top"><div align="left">&nbsp;</div></td>
                                                            <?php } ?>
                                                            <?php
                                                            if (isset($productResourcesArray[$i]['productResourcesActualDate'])) {
                                                                $valueArray = $productResourcesArray[$i]['productResourcesActualDate'];
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
                                                                <td valign="top"><?php echo $value; ?></td>
                                                            <?php } else { ?>
                                                                <td valign="top"><div align="left">&nbsp;</div></td>
                                                            <?php } ?>
                                                            <?php
                                                            $d = $productResourcesArray[$i]['productResourcesEstimatedTotalCost'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($productResourcesArray[$i]['productResourcesEstimatedTotalCost']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td valign="top"><div align="right"><?php echo$d; ?></div></td>
                                                            <?php
                                                            $d = $productResourcesArray[$i]['productResourcesActualTotalCost'];
                                                            if (class_exists('NumberFormatter')) {
                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                    $d = $a->format($productResourcesArray[$i]['productResourcesActualTotalCost']);
                                                                } else {
                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                }
                                                            } else {
                                                                $d = number_format($d);
                                                            }
                                                            ?>
                                                            <td valign="top"><div align="right"><?php echo$d; ?></div></td>
                                                            <td valign="top" align="center"><div align="center">
                                                                    <?php
                                                                    if (isset($productResourcesArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($productResourcesArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productResourcesArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['staffName'];
                                                                                }
                                                                            } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                if (strpos($productResourcesArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                    echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productResourcesArray[$i]['staffName']);
                                                                                } else {
                                                                                    echo $productResourcesArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                echo $productResourcesArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $productResourcesArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($productResourcesArray[$i]['executeTime'])) {
                                                                $valueArray = $productResourcesArray[$i]['executeTime'];
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
                                                            if ($productResourcesArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = NULL;
                                                            }
                                                            ?>
                                                            <td valign="top"><input class="form-control" style="display:none;" type="checkbox" name="productResourcesId[]"  value="<?php echo $productResourcesArray[$i]['productResourcesId']; ?>">
                                                                <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $productResourcesArray[$i]['isDelete']; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="12" valign="top" align="center"><?php $productResources->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="12" valign="top" align="center"><?php $productResources->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="12" valign="top" align="center"><?php $productResources->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                            <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> <i class="glyphicon glyphicon-white glyphicon-trash"></i> </button>
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
        <input type="hidden" name="productResourcesId" id="productResourcesId" value="<?php
        if (isset($_POST['productResourcesId'])) {
            echo $_POST['productResourcesId'];
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
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"> </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productBatchIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productBatchId"><strong><?php echo ucfirst($leafTranslation['productBatchIdLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="productBatchId" id="productBatchId" class="form-control input-sm chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($productBatchArray)) {
                                                    $totalRecord = intval(count($productBatchArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($productResourcesArray[0]['productBatchId'])) {
                                                                if ($productResourcesArray[0]['productBatchId'] == $productBatchArray[$i]['productBatchId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $productBatchArray[$i]['productBatchId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $productBatchArray[$i]['productBatchDescription']; ?></option>
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
                                            <span class="help-block" id="productBatchIdHelpMe"></span> </div>
                                    </div>
                                    <div class="form-group" id="invoiceIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="invoiceId"><strong><?php echo ucfirst($leafTranslation['invoiceIdLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="invoiceId" id="invoiceId" class="form-control input-sm chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($invoiceArray)) {
                                                    $totalRecord = intval(count($invoiceArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($productResourcesArray[0]['invoiceId'])) {
                                                                if ($productResourcesArray[0]['invoiceId'] == $invoiceArray[$i]['invoiceId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = NULL;
                                                                }
                                                            } else {
                                                                $selected = NULL;
                                                            }
                                                            ?>
                                                            <option value="<?php echo $invoiceArray[$i]['invoiceId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $invoiceArray[$i]['invoiceDescription']; ?></option>
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
                                            <span class="help-block" id="invoiceIdHelpMe"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productResourcesTaskForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesTask"><strong><?php echo ucfirst($leafTranslation['productResourcesTaskLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <textarea class="form-control input-sm" name="productResourcesTask" id="productResourcesTask" onkeyup="removeMeError('productResourcesTask');"><?php
                                                if (isset($productResourcesArray[0]['productResourcesTask'])) {
                                                    echo htmlentities($productResourcesArray[0]['productResourcesTask']);
                                                }
                                                ?></textarea>
                                            <span class="help-block" id="productResourcesTaskHelpMe"></span> </div>
                                    </div>
                                    <?php
                                    if (isset($productResourcesArray) && is_array($productResourcesArray)) {

                                        if (isset($productResourcesArray[0]['productResourcesEstimatedDate'])) {
                                            $valueArray = $productResourcesArray[0]['productResourcesEstimatedDate'];
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
                                    <div class="form-group" id="productResourcesEstimatedDateForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesEstimatedDate"><strong><?php echo ucfirst($leafTranslation['productResourcesEstimatedDateLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedDate" id="productResourcesEstimatedDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="productResourcesEstimatedDateImage"></span></div>
                                            <span class="help-block" id="productResourcesEstimatedDateHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?php
                                    if (isset($productResourcesArray) && is_array($productResourcesArray)) {

                                        if (isset($productResourcesArray[0]['productResourcesActualDate'])) {
                                            $valueArray = $productResourcesArray[0]['productResourcesActualDate'];
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
                                    <div class="form-group" id="productResourcesActualDateForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesActualDate"><strong><?php echo ucfirst($leafTranslation['productResourcesActualDateLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualDate" id="productResourcesActualDate" value="<?php
                                                if (isset($value)) {
                                                    echo $value;
                                                }
                                                ?>" >
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png" id="productResourcesActualDateImage"></span></div>
                                            <span class="help-block" id="productResourcesActualDateHelpMe"></span> </div>
                                    </div>
                                    <div class="form-group" id="productResourcesEstimatedEmployeeCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesEstimatedEmployeeCost"><strong><?php echo ucfirst($leafTranslation['productResourcesEstimatedEmployeeCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedEmployeeCost" id="productResourcesEstimatedEmployeeCost" onkeyup="removeMeError('productResourcesEstimatedEmployeeCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesEstimatedEmployeeCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesEstimatedEmployeeCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesEstimatedEmployeeCostHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productResourcesActualEmployeeCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesActualEmployeeCost"><strong><?php echo ucfirst($leafTranslation['productResourcesActualEmployeeCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualEmployeeCost" id="productResourcesActualEmployeeCost" onkeyup="removeMeError('productResourcesActualEmployeeCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesActualEmployeeCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesActualEmployeeCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesActualEmployeeCostHelpMe"></span> </div>
                                    </div>
                                    <div class="form-group" id="productResourcesEstimatedMachineCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesEstimatedMachineCost"><strong><?php echo ucfirst($leafTranslation['productResourcesEstimatedMachineCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedMachineCost" id="productResourcesEstimatedMachineCost" onkeyup="removeMeError('productResourcesEstimatedMachineCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesEstimatedMachineCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesEstimatedMachineCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesEstimatedMachineCostHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productResourcesActualMachineCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesActualMachineCost"><strong><?php echo ucfirst($leafTranslation['productResourcesActualMachineCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualMachineCost" id="productResourcesActualMachineCost" onkeyup="removeMeError('productResourcesActualMachineCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesActualMachineCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesActualMachineCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesActualMachineCostHelpMe"></span> </div>
                                    </div>
                                    <div class="form-group" id="productResourcesEstimatedAdditionalCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesEstimatedAdditionalCost"><strong><?php echo ucfirst($leafTranslation['productResourcesEstimatedAdditionalCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedAdditionalCost" id="productResourcesEstimatedAdditionalCost" onkeyup="removeMeError('productResourcesEstimatedAdditionalCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesEstimatedAdditionalCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesEstimatedAdditionalCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesEstimatedAdditionalCostHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productResourcesActualAdditionalCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesActualAdditionalCost"><strong><?php echo ucfirst($leafTranslation['productResourcesActualAdditionalCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualAdditionalCost" id="productResourcesActualAdditionalCost" onkeyup="removeMeError('productResourcesActualAdditionalCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesActualAdditionalCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesActualAdditionalCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesActualAdditionalCostHelpMe"></span> </div>
                                    </div>
                                    <div class="form-group" id="productResourcesEstimatedBillOfMaterialCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesEstimatedBillOfMaterialCost"><strong><?php echo ucfirst($leafTranslation['productResourcesEstimatedBillOfMaterialCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedBillOfMaterialCost" id="productResourcesEstimatedBillOfMaterialCost" onkeyup="removeMeError('productResourcesEstimatedBillOfMaterialCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesEstimatedBillOfMaterialCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesEstimatedBillOfMaterialCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="productResourcesEstimatedBillOfMaterialCostHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productResourcesActualBillOfMaterialCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesActualBillOfMaterialCost"><strong><?php echo ucfirst($leafTranslation['productResourcesActualBillOfMaterialCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualBillOfMaterialCost" id="productResourcesActualBillOfMaterialCost" onkeyup="removeMeError('productResourcesActualBillOfMaterialCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesActualBillOfMaterialCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesActualBillOfMaterialCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png" alt=""></span></div>
                                            <span class="help-block" id="productResourcesActualBillOfMaterialCostHelpMe"></span> </div>
                                    </div>
                                    <div class="form-group" id="productResourcesEstimatedTotalCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesEstimatedTotalCost"><strong><?php echo ucfirst($leafTranslation['productResourcesEstimatedTotalCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesEstimatedTotalCost" id="productResourcesEstimatedTotalCost" onkeyup="removeMeError('productResourcesEstimatedTotalCost');"  value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesEstimatedTotalCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesEstimatedTotalCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png" alt=""></span></div>
                                            <span class="help-block" id="productResourcesEstimatedTotalCostHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="productResourcesActualTotalCostForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="productResourcesActualTotalCost"><strong><?php echo ucfirst($leafTranslation['productResourcesActualTotalCostLabel']); ?></strong></label>
                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="productResourcesActualTotalCost" id="productResourcesActualTotalCost" value="<?php
                                                if (isset($productResourcesArray) && is_array($productResourcesArray)) {
                                                    if (isset($productResourcesArray[0]['productResourcesActualTotalCost'])) {
                                                        echo htmlentities($productResourcesArray[0]['productResourcesActualTotalCost']);
                                                    }
                                                }
                                                ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png" alt=""></span></div>
                                            <span class="help-block" id="productResourcesActualTotalCostHelpMe"></span> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group" align="left"> <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li>
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];               ?></a> </li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];               ?></a> </li>-->
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li>
                                </ul>
                            </div>
                            <div class="btn-group" align="left"> <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a> </li>
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];              ?></a> </li> -->
                                    <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?></a> </li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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

            // shift+n new record event
            if (e.which === 78 && e.which === 18  && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    newRecord(<?php echo $leafId; ?>, '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                return false;
            }
            // shift+s save event
            if (e.which === 83 && e.which === 18  && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    updateRecord(<?php echo $leafId; ?>, '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                return false;
            }
            // shift+d delete event
            if (e.which === 88 && e.which === 18 && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
    <?php } ?>
            }
            // shift+f.find event
            if (e.which === 18 && e.shiftKey) {
                findRecord();
                
                return false;
            }
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $productResources->getControllerPath(); ?>', '<?php echo $productResources->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
            }
            

        });
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('productResourcesId');
            validateMeNumeric('productBatchId');
            validateMeNumeric('invoiceId');
            $('#productResourcesEstimatedDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            $('#productResourcesActualDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeCurrency('productResourcesEstimatedEmployeeCost');
            validateMeCurrency('productResourcesActualEmployeeCost');
            validateMeCurrency('productResourcesEstimatedMachineCost');
            validateMeCurrency('productResourcesActualMachineCost');
            validateMeCurrency('productResourcesEstimatedAdditionalCost');
            validateMeCurrency('productResourcesActualAdditionalCost');
            validateMeCurrency('productResourcesEstimatedBillOfMaterialCost');
            validateMeCurrency('productResourcesActualBillOfMaterialCost');
            validateMeCurrency('productResourcesEstimatedTotalCost');
            validateMeCurrency('productResourcesActualTotalCost');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['productResourcesId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $productResources->getControllerPath(); ?>','<?php echo $productResources->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/inventory/javascript/productResources.js"></script>
<hr>
<footer>
    <p>IDCMS 2012/2013</p>
</footer>
