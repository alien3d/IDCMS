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
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once($newFakeDocumentRoot . "v3/financial/inventory/controller/productController.php");
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

$translator->setCurrentTable('product');
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
$productArray = array();
$itemCategoryArray = array();
$itemTypeArray = array();
$countryArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $product = new \Core\Financial\Inventory\Product\Controller\ProductClass();
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
            $product->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $product->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $product->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $product->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $product->setStartDay($start[2]);
            $product->setStartMonth($start[1]);
            $product->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $product->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $product->setEndDay($start[2]);
            $product->setEndMonth($start[1]);
            $product->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $product->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $product->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $product->setServiceOutput('html');
        $product->setLeafId($leafId);
        $product->execute();
        $itemCategoryArray = $product->getItemCategory();
        $itemTypeArray = $product->getItemType();
        $countryArray = $product->getCountry();
        if ($_POST['method'] == 'read') {
            $product->setStart($offset);
            $product->setLimit($limit); // normal system don't like paging..  
            $product->setPageOutput('html');
            $productArray = $product->read();
            if (isset($productArray [0]['firstRecord'])) {
                $firstRecord = $productArray [0]['firstRecord'];
            }
            if (isset($productArray [0]['nextRecord'])) {
                $nextRecord = $productArray [0]['nextRecord'];
            }
            if (isset($productArray [0]['previousRecord'])) {
                $previousRecord = $productArray [0]['previousRecord'];
            }
            if (isset($productArray [0]['lastRecord'])) {
                $lastRecord = $productArray [0]['lastRecord'];
                $endRecord = $productArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($product->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($productArray [0]['total'])) {
                $total = $productArray [0]['total'];
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
                    <button title="A" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'A');">A</button> 
                    <button title="B" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'B');">B</button> 
                    <button title="C" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'C');">C</button> 
                    <button title="D" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'D');">D</button> 
                    <button title="E" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'E');">E</button> 
                    <button title="F" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'F');">F</button> 
                    <button title="G" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'G');">G</button> 
                    <button title="H" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'H');">H</button> 
                    <button title="I" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'I');">I</button> 
                    <button title="J" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'J');">J</button> 
                    <button title="K" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'K');">K</button> 
                    <button title="L" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'L');">L</button> 
                    <button title="M" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'M');">M</button> 
                    <button title="N" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'N');">N</button> 
                    <button title="O" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'O');">O</button> 
                    <button title="P" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'P');">P</button> 
                    <button title="Q" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Q');">Q</button> 
                    <button title="R" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'R');">R</button> 
                    <button title="S" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'S');">S</button> 
                    <button title="T" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'T');">T</button> 
                    <button title="U" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'U');">U</button> 
                    <button title="V" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'V');">V</button> 
                    <button title="W" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'W');">W</button> 
                    <button title="X" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'X');">X</button> 
                    <button title="Y" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Y');">Y</button> 
                    <button title="Z" class="btn btn-success btn-sm" type="button"  onclick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 'Z');">Z</button> 
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2">
                    <div align="left" class="pull-left">
                        <div class="btn-group">
                            <button class="btn btn-warning">
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href ="javascript:void(0)" onclick="reportRequest('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class ="pull-right glyphicon glyphicon-download"></i>CSV
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
                <div  id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3"> 
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="btnList">

                                <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onclick="showForm('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['newButtonLabel']; ?></button> 
                            </div>
                            <label for="queryWidget"></label><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                            if (isset($_POST['query'])) {
                                echo $_POST['query'];
                            }
                            ?>">
                            <br>     <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)"><?php echo $t['clearButtonLabel']; ?></button>

                            <table class="table table-striped table-condensed table-hover">
                                <tr>
                                    <td>&nbsp;</td>             
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>             
                                    <td align="center"><a href="javascript:void(0)" rel="tooltip" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php echo date('d-m-Y'); ?>', 'between', '');"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                    <td>&nbsp;</td>         
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a></td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['day'] ?>"></td>             <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'previous'); ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a> </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['week'] ?>"></td>             <td align="center"><a href="javascript:void(0)" rel="tooltip" title="<?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'current'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Week <?php echo $dateConvert->getCurrentWeekInfo($dateRangeStart, 'next'); ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['month'] ?>"></td>             <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a></td>
                                </tr>
                                <tr>
                                    <td align="right"><a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"  onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a></td> 
                                    <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>"></td>             <td align="center"><a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a></td>
                                    <td align="left"><a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>" onclick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a></td>
                                </tr>
                            </table>

                            <div>
                                <label for="dateRangeStart"></label><input type="text" name="dateRangeStart" id="dateRangeStart" class="form-control" value="<?php
                                if (isset($_POST['dateRangeStart'])) {
                                    echo $_POST['dateRangeStart'];
                                }
                                ?>" onclick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><br>
                                <label for="dateRangeEnd"></label><input type="text" name="dateRangeEnd" id="dateRangeEnd" class="form-control" value="<?php
                                if (isset($_POST['dateRangeEnd'])) {
                                    echo $_POST['dateRangeEnd'];
                                }
                                ?>" onclick="topPage(175);"><br>
                                <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block" onclick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><?php echo $t['clearButtonLabel']; ?></button>
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
                                        <input type="hidden" name="productIdPreview" id="productIdPreview">
                                        <div class="form-group" id="branchIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="branchIdPreview"><?php echo $leafTranslation['branchIdLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="branchIdPreview" id="branchIdPreview">
                                            </div>					</div>					<div class="form-group" id="departmentIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="departmentIdPreview"><?php echo $leafTranslation['departmentIdLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="departmentIdPreview" id="departmentIdPreview">
                                            </div>					</div>					<div class="form-group" id="warehouseIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="warehouseIdPreview"><?php echo $leafTranslation['warehouseIdLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="warehouseIdPreview" id="warehouseIdPreview">
                                            </div>					</div>					<div class="form-group" id="itemCategoryIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="itemCategoryIdPreview"><?php echo $leafTranslation['itemCategoryIdLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="itemCategoryIdPreview" id="itemCategoryIdPreview">
                                            </div>					</div>					<div class="form-group" id="itemTypeIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="itemTypeIdPreview"><?php echo $leafTranslation['itemTypeIdLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="itemTypeIdPreview" id="itemTypeIdPreview">
                                            </div>					</div>					<div class="form-group" id="countryIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="countryIdPreview" id="countryIdPreview">
                                            </div>					</div>					<div class="form-group" id="productCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productCodePreview"><?php echo $leafTranslation['productCodeLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productCodePreview" id="productCodePreview">
                                            </div>					</div>					<div class="form-group" id="productBarcodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productBarcodePreview"><?php echo $leafTranslation['productBarcodeLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productBarcodePreview" id="productBarcodePreview">
                                            </div>					</div>					<div class="form-group" id="productSerialNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSerialNumberPreview"><?php echo $leafTranslation['productSerialNumberLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productSerialNumberPreview" id="productSerialNumberPreview">
                                            </div>					</div>					<div class="form-group" id="productModelDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productModelPreview"><?php echo $leafTranslation['productModelLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productModelPreview" id="productModelPreview">
                                            </div>					</div>					<div class="form-group" id="productSellingPriceDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSellingPricePreview"><?php echo $leafTranslation['productSellingPriceLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productSellingPricePreview" id="productSellingPricePreview">
                                            </div>					</div>					<div class="form-group" id="productCostPriceDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productCostPricePreview"><?php echo $leafTranslation['productCostPriceLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productCostPricePreview" id="productCostPricePreview">
                                            </div>					</div>					<div class="form-group" id="productPictureDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productPicturePreview"><?php echo $leafTranslation['productPictureLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productPicturePreview" id="productPicturePreview">
                                            </div>					</div>					<div class="form-group" id="productDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productDescriptionPreview"><?php echo $leafTranslation['productDescriptionLabel']; ?></label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="productDescriptionPreview" id="productDescriptionPreview">
                                            </div>					</div>					</form>
                                </div> 
                                <div class="modal-footer"> 
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecord('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default" onclick="showMeModal('deletePreview', 0)"><?php echo $t['closeButtonLabel']; ?></button> 
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class ="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData"> 
                                <thead> 
                                    <tr> 
                                        <th width="25px" align="center"><div align="center">#</div></th>
                                <th width="125px"><div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['itemCategoryIdLabel']); ?></th> 
                                <th width="125px"><?php echo ucwords($leafTranslation['itemTypeIdLabel']); ?></th> 
                                <th width="75px"><div align="center"><?php echo ucwords($leafTranslation['productCodeLabel']); ?></div></th> 
                                <th><?php echo ucwords($leafTranslation['productDescriptionLabel']); ?></th> 
                                <th width="100px"><div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th> 
                                <th width="175px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> 
                                <th width="25px" align="center"><input type="checkbox" name="check_all" id="check_all" alt="Check Record" onChange="toggleChecked(this.checked);"></th>
                                </tr> 
                                </thead> 
                                <tbody id="tableBody"> 
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($productArray)) {
                                            $totalRecord = intval(count($productArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
                                                    <tr <?php
                                                    if ($productArray[$i]['isDelete'] == 1) {
                                                        echo "class=\"danger\"";
                                                    } else {
                                                        if ($productArray[$i]['isDraft'] == 1) {
                                                            echo "class=\"warning\"";
                                                        }
                                                    }
                                                    ?>>
                                                        <td valign="top" align="center"><div align="center"><?php echo ($counter + $offset); ?>.</div></td>                       	<td valign="top" align="center"><div class="btn-group" align="center">
                                                                <button type="button"  class="btn btn-warning btn-xs" title="Edit" onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo intval($productArray [$i]['productId']); ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                <button type="button"  class="btn btn-danger btn-xs" title="Delete" onclick="showModalDelete('<?php echo rawurlencode($productArray [$i]['productId']); ?>', '<?php echo rawurlencode($productArray [$i]['branchName']); ?>', '<?php echo rawurlencode($productArray [$i]['departmentDescription']); ?>', '<?php echo rawurlencode($productArray [$i]['warehouseDescription']); ?>', '<?php echo rawurlencode($productArray [$i]['itemCategoryDescription']); ?>', '<?php echo rawurlencode($productArray [$i]['itemTypeDescription']); ?>', '<?php echo rawurlencode($productArray [$i]['countryDescription']); ?>', '<?php echo rawurlencode($productArray [$i]['productCode']); ?>', '<?php echo rawurlencode($productArray [$i]['productBarcode']); ?>', '<?php echo rawurlencode($productArray [$i]['productSerialNumber']); ?>', '<?php echo rawurlencode($productArray [$i]['productModel']); ?>', '<?php echo rawurlencode($productArray [$i]['productSellingPrice']); ?>', '<?php echo rawurlencode($productArray [$i]['productCostPrice']); ?>', '<?php echo rawurlencode($productArray [$i]['productPicture']); ?>', '<?php echo rawurlencode($productArray [$i]['productDescription']); ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button></div></td> 
                                                  
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($productArray[$i]['itemCategoryDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($productArray[$i]['itemCategoryDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productArray[$i]['itemCategoryDescription']);
                                                                            } else {
                                                                                echo $productArray[$i]['itemCategoryDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($productArray[$i]['itemCategoryDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productArray[$i]['itemCategoryDescription']);
                                                                            } else {
                                                                                echo $productArray[$i]['itemCategoryDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $productArray[$i]['itemCategoryDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $productArray[$i]['itemCategoryDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($productArray[$i]['itemTypeDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($productArray[$i]['itemTypeDescription'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productArray[$i]['itemTypeDescription']);
                                                                            } else {
                                                                                echo $productArray[$i]['itemTypeDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($productArray[$i]['itemTypeDescription'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productArray[$i]['itemTypeDescription']);
                                                                            } else {
                                                                                echo $productArray[$i]['itemTypeDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $productArray[$i]['itemTypeDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $productArray[$i]['itemTypeDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td valign="top"><div align="center">
                                                                <?php
                                                                if (isset($productArray[$i]['productCode'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($productArray[$i]['productCode']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productArray[$i]['productCode']);
                                                                            } else {
                                                                                echo $productArray[$i]['productCode'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($productArray[$i]['productCode']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productArray[$i]['productCode']);
                                                                            } else {
                                                                                echo $productArray[$i]['productCode'];
                                                                            }
                                                                        } else {
                                                                            echo $productArray[$i]['productCode'];
                                                                        }
                                                                    } else {
                                                                        echo $productArray[$i]['productCode'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td valign="top"><div align="left">
                                                                <?php
                                                                if (isset($productArray[$i]['productDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(strtolower($productArray[$i]['productDescription']), strtolower($_POST['query'])) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productArray[$i]['productDescription']);
                                                                            } else {
                                                                                echo $productArray[$i]['productDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(strtolower($productArray[$i]['productDescription']), strtolower($_POST['character'])) !== false) {
                                                                                echo str_replace($_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productArray[$i]['productDescription']);
                                                                            } else {
                                                                                echo $productArray[$i]['productDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $productArray[$i]['productDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $productArray[$i]['productDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td valign="top" align="center"><div align="center">
                                                                <?php
                                                                if (isset($productArray[$i]['executeBy'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos($productArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $productArray[$i]['staffName']);
                                                                            } else {
                                                                                echo $productArray[$i]['staffName'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos($productArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                echo str_replace($_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $productArray[$i]['staffName']);
                                                                            } else {
                                                                                echo $productArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $productArray[$i]['staffName'];
                                                                        }
                                                                    } else {
                                                                        echo $productArray[$i]['staffName'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        if (isset($productArray[$i]['executeTime'])) {
                                                            $valueArray = $productArray[$i]['executeTime'];
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
                                                        if ($productArray[$i]['isDelete']) {
                                                            $checked = "checked";
                                                        } else {
                                                            $checked = NULL;
                                                        }
                                                        ?>
                                                        <td valign="top">
                                                            <input class="form-control" style="display:none;" type="checkbox" name="productId[]"  value="<?php echo $productArray[$i]['productId']; ?>">
                                                            <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]" value="<?php echo $productArray[$i]['isDelete']; ?>">

                                                        </td>
                                                    </tr> 
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr> 
                                                    <td colspan="7" valign="top" align="center"><?php $product->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                                </tr> 
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <tr> 
                                                <td colspan="7" valign="top" align="center"><?php $product->exceptionMessage($t['recordNotFoundLabel']); ?></td> 
                                            </tr> 
                                            <?php
                                        }
                                    } else {
                                        ?> 
                                        <tr> 
                                            <td colspan="7" valign="top" align="center"><?php $product->exceptionMessage($t['loadFailureLabel']); ?></td> 
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
                            <button type="button"  class="delete btn btn-warning" onclick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>');"> 
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
    <form class="form-horizontal">		<input type="hidden" name="productId" id="productId" value="<?php
        if (isset($_POST['productId'])) {
            echo $_POST['productId'];
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
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div align="right">

                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default" onclick="firstRecord('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled" onclick="previousRecord('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled" onclick="nextRecord('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?> </button> 
                                </div> 
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default" onclick="endRecord('<?php echo $leafId; ?>', '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?> </button> 
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <!-- information -->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-9 col-sm-9 col-md-9">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- name -->							
                                                    <div class="form-group" id="productNameForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productName"><strong><?php echo ucfirst($leafTranslation['productNameLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input class="form-control" type="text" name="productName" id="productName" onkeyup="removeMeError('productName');"  value="<?php
                                                            if (isset($productArray) && is_array($productArray)) {
                                                                if (isset($productArray[0]['productName'])) {
                                                                    echo htmlentities($productArray[0]['productName']);
                                                                }
                                                            }
                                                            ?>">
                                                            <span class="help-block" id="productNameHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end name -->
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- code -->
                                                    <div class="form-group" id="productCodeForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productCode"><strong><?php echo ucfirst($leafTranslation['productCodeLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" type="text" name="productCode" id="productCode"  
                                                                       onkeyup="removeMeError('productCode');" 
                                                                       value="<?php
                                                                       if (isset($productArray) && is_array($productArray)) {
                                                                           if (isset($productArray[0]['productCode'])) {
                                                                               echo htmlentities($productArray[0]['productCode']);
                                                                           }
                                                                       }
                                                                       ?>" maxlength="16">
                                                                <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                                            <span class="help-block" id="productCodeHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end code -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- model -->
                                                    <div class="form-group" id="productModelForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productModel"><strong><?php echo ucfirst($leafTranslation['productModelLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input class="form-control" type="text" name="productModel" id="productModel" onkeyup="removeMeError('productModel');"  value="<?php
                                                            if (isset($productArray) && is_array($productArray)) {
                                                                if (isset($productArray[0]['productModel'])) {
                                                                    echo htmlentities($productArray[0]['productModel']);
                                                                }
                                                            }
                                                            ?>">
                                                            <span class="help-block" id="productModelHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end model -->
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- serial number -->
                                                    <div class="form-group" id="productSerialNumberForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSerialNumber"><strong><?php echo ucfirst($leafTranslation['productSerialNumberLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input class="form-control" type="text" name="productSerialNumber" id="productSerialNumber" onkeyup="removeMeError('productSerialNumber');"  value="<?php
                                                            if (isset($productArray) && is_array($productArray)) {
                                                                if (isset($productArray[0]['productSerialNumber'])) {
                                                                    echo htmlentities($productArray[0]['productSerialNumber']);
                                                                }
                                                            }
                                                            ?>">
                                                            <span class="help-block" id="productSerialNumberHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end serial number -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- Cost Price -->

                                                    <div class="form-group" id="productCostPriceForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productCostPrice"><strong><?php echo ucfirst($leafTranslation['productCostPriceLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" type="text" name="productCostPrice" id="productCostPrice" onkeyup="removeMeError('productCostPrice');"  value="<?php
                                                                if (isset($productArray) && is_array($productArray)) {
                                                                    if (isset($productArray[0]['productCostPrice'])) {
                                                                        echo htmlentities($productArray[0]['productCostPrice']);
                                                                    }
                                                                }
                                                                ?>">
                                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                            <span class="help-block" id="productCostPriceHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end Cost Price  -->
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- Selling Price -->

                                                    <div class="form-group" id="productSellingPriceForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="productSellingPrice"><strong><?php echo ucfirst($leafTranslation['productSellingPriceLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <div class="input-group">
                                                                <input class="form-control" type="text" name="productSellingPrice" id="productSellingPrice" onkeyup="removeMeError('productSellingPrice');"  value="<?php
                                                                if (isset($productArray) && is_array($productArray)) {
                                                                    if (isset($productArray[0]['productSellingPrice'])) {
                                                                        echo htmlentities($productArray[0]['productSellingPrice']);
                                                                    }
                                                                }
                                                                ?>">
                                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                            <span class="help-block" id="productSellingPriceHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end Selling Price -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- category -->
                                                    <div class="form-group" id="itemCategoryIdForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="itemCategoryId"><strong><?php echo ucfirst($leafTranslation['itemCategoryIdLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="itemCategoryId" id="itemCategoryId" class="form-control  chzn-select">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($itemCategoryArray)) {
                                                                    $totalRecord = intval(count($itemCategoryArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($productArray[0]['itemCategoryId'])) {
                                                                                if ($productArray[0]['itemCategoryId'] == $itemCategoryArray[$i]['itemCategoryId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = NULL;
                                                                                }
                                                                            } else {
                                                                                $selected = NULL;
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $itemCategoryArray[$i]['itemCategoryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $itemCategoryArray[$i]['itemCategoryDescription']; ?></option> 
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
                                                            <span class="help-block" id="itemCategoryIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end category -->
                                                </div>
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <!-- type -->
                                                    <div class="form-group" id="itemTypeIdForm">
                                                        <label class="control-label col-xs-4 col-sm-4 col-md-4" for="itemTypeId"><strong><?php echo ucfirst($leafTranslation['itemTypeIdLabel']); ?></strong></label>
                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <select name="itemTypeId" id="itemTypeId" class="form-control  chzn-select">
                                                                <option value=""></option>
                                                                <?php
                                                                if (is_array($itemTypeArray)) {
                                                                    $totalRecord = intval(count($itemTypeArray));
                                                                    if ($totalRecord > 0) {
                                                                        $d = 1;
                                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                                            if (isset($productArray[0]['itemTypeId'])) {
                                                                                if ($productArray[0]['itemTypeId'] == $itemTypeArray[$i]['itemTypeId']) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = NULL;
                                                                                }
                                                                            } else {
                                                                                $selected = NULL;
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $itemTypeArray[$i]['itemTypeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>. <?php echo $itemTypeArray[$i]['itemTypeDescription']; ?></option> 
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
                                                            <span class="help-block" id="itemTypeIdHelpMe"></span>
                                                        </div>
                                                    </div>
                                                    <!-- end type -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <!-- picture -->
                                        <div class="form form-group" align="center">
                                            <label for="productPicture" class="control-label col-xs-4 col-sm-4 col-md-4"><?php
                                                echo ucfirst(
                                                        $leafTranslation['productPictureLabel']
                                                );
                                                ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="hidden" class="form-control" name="productPicture" id="productPicture"
                                                       value="<?php echo $productArray[0]['productPicture']; ?>">

                                                <div id="productPicturePreviewUpload" align="center">
                                                    <ul class="img-thumbnails">
                                                        <li>
                                                            <div class="img-thumbnail" align="center">
                                                                <?php
                                                                if (empty($productArray[0]['productPicture'])) {
                                                                    $productArray[0]['productPicture'] = 'ps3.jpg';
                                                                }
                                                                if (isset($productArray[0]['productPicture'])) {
                                                                    if (strlen($productArray[0]['productPicture']) > 0) {
                                                                        ?>
                                                                        <img id="imagePreview"
                                                                             src="./v3/financial/inventory/images/<?php echo $productArray[0]['productPicture']; ?>"
                                                                             width="80"
                                                                             height="80">
                                                                             <?php
                                                                         }
                                                                     }
                                                                     ?></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" align="center">
                                            <div class="col-xs-12 col-sm-12 col-md-12" align="center">
                                                <div id="productPictureDiv" class="pull-left" style="text-align:center" align="center">
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
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <!-- product description -->

                                    <div class="form-group" id="productDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea class="form-control" name="productDescription" id="productDescription" onkeyup="removeMeError('productDescription');"><?php
                                                if (isset($productArray[0]['productDescription'])) {
                                                    echo htmlentities($productArray[0]['productDescription']);
                                                }
                                                ?></textarea>
                                            <span class="help-block" id="productDescriptionHelpMe"></span>
                                        </div>
                                    </div>
                                    <!-- end product description -->
                                </div>
                            </div>
                            <!-- end information -->
                            
                            <!-- start dimension -->
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- Height -->
                                        <!-- end Height -->
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- width -->
                                        <!-- end width -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- weight-->
                                        <!-- end weight -->
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- depth -->
                                        <!-- end depth -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- cubic total -->
                                        <!-- cubic total -->
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- unit of measurement -->
                                        <!-- end unit of measurement -->
                                    </div>
                                </div>
                            </div>
                            <!-- end dimension -->

                        </div><div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a> 
                                <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled" data-toggle="dropdown"><span class="caret"></span></a> 
                                <ul class="dropdown-menu" style="text-align:left"> 
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a> </li> 
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a> </li> 
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];           ?></a> </li>--> 
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];            ?></a> </li>--> 
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li> 
                                </ul> 
                            </div> 
                            <div class="btn-group" align="left">
                                <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a> 
                                <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled" data-toggle="dropdown"><span class="caret"></span></a> 
                                <ul class="dropdown-menu" style="text-align:left"> 
                                    <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a> </li> 
                                 <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];            ?></a> </li> -->
                                    <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?></a> </li> 
                                </ul> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info" onclick="resetRecord(<?php echo $leafId; ?>, '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button> 
                            </div> 
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info" onclick="showGrid('<?php echo $leafId; ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button> 
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

            // shift+n new record event
            if (e.which === 78 && e.which === 18  && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    newRecord(<?php echo $leafId; ?>, '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                return false;
            }
            // shift+s save event
            if (e.which === 83 && e.which === 18  && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    updateRecord(<?php echo $leafId; ?>, '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                return false;
            }
            // shift+d delete event
            if (e.which === 88 && e.which === 18 && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

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
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $product->getControllerPath(); ?>', '<?php echo $product->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
            }
            

        });
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('productId');
            validateMeNumeric('branchId');
            validateMeNumeric('departmentId');
            validateMeNumeric('warehouseId');
            validateMeNumeric('itemCategoryId');
            validateMeNumeric('itemTypeId');
            validateMeNumeric('countryId');
            validateMeAlphaNumeric('productCode');
            validateMeAlphaNumeric('productBarcode');
            validateMeAlphaNumeric('productSerialNumber');
            validateMeAlphaNumeric('productModel');
            validateMeCurrency('productSellingPrice');
            validateMeCurrency('productCostPrice');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['productId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $product->getControllerPath(); ?>','<?php echo $product->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
    </script> 
<?php } ?> 
<script type="text/javascript" src="./v3/financial/inventory/javascript/product.js"></script> 
<hr><footer><p>IDCMS 2012/2013</p></footer>