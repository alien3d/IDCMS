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
require_once($newFakeDocumentRoot . "v3/financial/fixedAsset/controller/assetController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
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

$translator->setCurrentTable('asset');

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
$assetArray = array();
$branchArray = array();
$departmentArray = array();
$warehouseArray = array();
$locationArray = array();
$itemCategoryArray = array();
$itemTypeArray = array();
$businessPartnerArray = array();
$unitOfMeasurementArray = array();
$purchaseInvoiceArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $asset = new \Core\Financial\FixedAsset\Asset\Controller\AssetClass();
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
            $asset->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $asset->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $asset->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $asset->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $asset->setStartDay($start[2]);
            $asset->setStartMonth($start[1]);
            $asset->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $asset->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $asset->setEndDay($start[2]);
            $asset->setEndMonth($start[1]);
            $asset->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $asset->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $asset->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $asset->setServiceOutput('html');
        $asset->setLeafId($leafId);
        $asset->execute();
        $branchArray = $asset->getBranch();
        $departmentArray = $asset->getDepartment();
        $warehouseArray = $asset->getWarehouse();
        $locationArray = $asset->getLocation();
        $itemCategoryArray = $asset->getItemCategory();
        $itemTypeArray = $asset->getItemType();
        $businessPartnerArray = $asset->getBusinessPartner();
        $unitOfMeasurementArray = $asset->getUnitOfMeasurement();
        $purchaseInvoiceArray = $asset->getPurchaseInvoice();
        if ($_POST['method'] == 'read') {
            $asset->setStart($offset);
            $asset->setLimit($limit); // normal system don't like paging..  
            $asset->setPageOutput('html');
            $assetArray = $asset->read();
            if (isset($assetArray [0]['firstRecord'])) {
                $firstRecord = $assetArray [0]['firstRecord'];
            }
            if (isset($assetArray [0]['nextRecord'])) {
                $nextRecord = $assetArray [0]['nextRecord'];
            }
            if (isset($assetArray [0]['previousRecord'])) {
                $previousRecord = $assetArray [0]['previousRecord'];
            }
            if (isset($assetArray [0]['lastRecord'])) {
                $lastRecord = $assetArray [0]['lastRecord'];
                $endRecord = $assetArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($asset->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($assetArray [0]['total'])) {
                $total = $assetArray [0]['total'];
            } else {
                $total = 0;
            }
            $navigation->setTotalRecord($total);
        }
    }
}
?>
<style>
    .total {
        border-top-width: thin;
        border-bottom-width: thick;
        border-top-style: solid;
        border-bottom-style: double;
        border-top-color: #000000;
        border-bottom-color: #000000;
    }
</style>
<script type="text/javascript">
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
                echo $template->breadcrumb(
                        $applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId
                );
                ?>
            </div>
        </div>
        <div id="infoErrorRowFluid" class="row hidden">
            <div id="infoError" class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
        </div>
        <div id="content" style="opacity: 1;">
            <div class="row">
                <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10">
                    <button title="A" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $asset->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');">
                        Z
                    </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button">
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $asset->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $asset->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $asset->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'html');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Html&nbsp;&nbsp;
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
                            <form class="form-forizontal">
                                <div id="btnList">

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                            class="btn btn-info btn-block"
                                            onclick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $asset->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');">
                                        <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            echo $_POST['query'];
                                                                        }
                                                                        ?>"> <br>
                                <input type="button"  name="searchString" id="searchString"
                                       value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block"
                                       onclick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $asset->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>"
                                       class="btn btn-info btn-block" onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $asset->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"> <br>

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center"><img src="./images/icons/calendar-select-days-span.png"
                                                                alt="<?php echo $t['allDay'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                               echo date(
                                                       'd-m-Y'
                                               );
                                               ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Day <?php echo $previousDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-week.png"
                                                                alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $asset->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Month <?php echo $previousMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-month.png"
                                                                alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Month <?php echo $nextMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Previous Year <?php echo $previousYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip"
                                               title="Next Year <?php echo $nextYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $asset->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                </table>


                                <div>
                                    <label for="dateRangeStart"></label><input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                                                               if (isset($_POST['dateRangeStart'])) {
                                                                                   echo $_POST['dateRangeStart'];
                                                                               }
                                                                               ?>" onclick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                    <label for="dateRangeEnd"></label><input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                                                             if (isset($_POST['dateRangeEnd'])) {
                                                                                 echo $_POST['dateRangeEnd'];
                                                                             }
                                                                             ?>" onclick="topPage(175);"><br>
                                    <input type="button"  name="searchDate" id="searchDate"
                                           value="<?php echo $t['searchButtonLabel']; ?>"
                                           class="btn btn-warning btn-block"
                                           onclick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                           echo $asset->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>');">
                                    <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                           value="<?php echo $t['clearButtonLabel']; ?>"
                                           class="btn btn-info btn-block" onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $asset->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <input type="hidden" name="assetIdPreview" id="assetIdPreview">

                                        <div class="form-group" id="branchIdDiv">
                                            <label for="branchIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['branchIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="branchIdPreview" id="branchIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="departmentIdDiv">
                                            <label for="departmentIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['departmentIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="departmentIdPreview" id="departmentIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="warehouseIdDiv">
                                            <label for="warehouseIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['warehouseIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="warehouseIdPreview" id="warehouseIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="locationIdDiv">
                                            <label for="locationIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['locationIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="locationIdPreview" id="locationIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemCategoryIdDiv">
                                            <label for="itemCategoryIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemCategoryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="itemCategoryIdPreview"
                                                       id="itemCategoryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemTypeIdDiv">
                                            <label for="itemTypeIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemTypeIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="itemTypeIdPreview" id="itemTypeIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerIdDiv">
                                            <label for="businessPartnerIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerIdPreview"
                                                       id="businessPartnerIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="unitOfMeasurementIdDiv">
                                            <label for="unitOfMeasurementIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['unitOfMeasurementIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="unitOfMeasurementIdPreview"
                                                       id="unitOfMeasurementIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="purchaseInvoiceIdDiv">
                                            <label for="purchaseInvoiceIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['purchaseInvoiceIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="purchaseInvoiceIdPreview"
                                                       id="purchaseInvoiceIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetCodeDiv">
                                            <label for="assetCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetCodePreview" id="assetCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetSerialNumberDiv">
                                            <label for="assetSerialNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetSerialNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetSerialNumberPreview"
                                                       id="assetSerialNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetNameDiv">
                                            <label for="assetNamePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetNameLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetNamePreview" id="assetNamePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetDescriptionDiv">
                                            <label for="assetDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetDescriptionPreview"
                                                       id="assetDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetModelDiv">
                                            <label for="assetModelPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetModelLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetModelPreview" id="assetModelPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetPriceDiv">
                                            <label for="assetPricePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetPriceLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetPricePreview" id="assetPricePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetDateDiv">
                                            <label for="assetDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetDatePreview" id="assetDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetWarrantyDiv">
                                            <label for="assetWarrantyPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetWarrantyLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetWarrantyPreview" id="assetWarrantyPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetColorDiv">
                                            <label for="assetColorPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetColorLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetColorPreview" id="assetColorPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetQuantityDiv">
                                            <label for="assetQuantityPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetQuantityLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetQuantityPreview" id="assetQuantityPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetInsuranceBusinessPartnerIdDiv">
                                            <label for="assetInsuranceBusinessPartnerIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetInsuranceBusinessPartnerIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetInsuranceBusinessPartnerIdPreview"
                                                       id="assetInsuranceBusinessPartnerIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetInsuranceStartDateDiv">
                                            <label for="assetInsuranceStartDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetInsuranceStartDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetInsuranceStartDatePreview"
                                                       id="assetInsuranceStartDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetInsuranceExpiredDateDiv">
                                            <label for="assetInsuranceExpiredDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetInsuranceExpiredDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetInsuranceExpiredDatePreview"
                                                       id="assetInsuranceExpiredDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetWarrantyStartDateDiv">
                                            <label for="assetWarrantyStartDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetWarrantyStartDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetWarrantyStartDatePreview"
                                                       id="assetWarrantyStartDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetWarrantyEndDateDiv">
                                            <label for="assetWarrantyEndDatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetWarrantyEndDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetWarrantyEndDatePreview"
                                                       id="assetWarrantyEndDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetDepreciationRateDiv">
                                            <label for="assetDepreciationRatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetDepreciationRateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetDepreciationRatePreview"
                                                       id="assetDepreciationRatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetNetBookValueDiv">
                                            <label for="assetNetBookValuePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetNetBookValueLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetNetBookValuePreview"
                                                       id="assetNetBookValuePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="assetPictureDiv">
                                            <label for="assetPicturePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['assetPictureLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="assetPicturePreview" id="assetPicturePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isTransferAsKitDiv">
                                            <label for="isTransferAsKitPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isTransferAsKitLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isTransferAsKitPreview"
                                                       id="isTransferAsKitPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isDepreciateDiv">
                                            <label for="isDepreciatePreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isDepreciateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isDepreciatePreview" id="isDepreciatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isWriteOffDiv">
                                            <label for="isWriteOffPreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isWriteOffLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isWriteOffPreview" id="isWriteOffPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isDisposeDiv">
                                            <label for="isDisposePreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isDisposeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isDisposePreview" id="isDisposePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isAdjustDiv">
                                            <label for="isAdjustPreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isAdjustLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isAdjustPreview" id="isAdjustPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger" onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                    echo $asset->getControllerPath();
                                    ?>', '<?php
                                    echo $asset->getViewPath();
                                    ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  onclick="showMeModal('deletePreview', 0);" class="btn btn-default"
                                            data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
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
                                            <th width="25px" align="center">
                                    <div align="center">#</div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th width="75px">
                                    <div align="center"><?php echo ucwords($leafTranslation['assetCodeLabel']); ?></div>
                                    </th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['assetSerialNumberLabel']); ?></th>
                                    <th width="100px"><?php echo ucwords($leafTranslation['assetNameLabel']); ?></th>
                                    <th width="75px"><?php echo ucwords($leafTranslation['assetDateLabel']); ?></th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['assetNetBookValueLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['assetPriceLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div></th>
                                        <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                        <th width="25px" align="center">
                                            <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                                   onChange="toggleChecked(this.checked);">
                                        </th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            $totalAssetNetBookValue = 0;
                                            $totalAssetPriceValue = 0;
                                            if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                if (is_array($assetArray)) {
                                                    $totalRecord = intval(count($assetArray));
                                                    if ($totalRecord > 0) {
                                                        $counter = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $counter++;
                                                            ?>
                                                            <tr <?php
                                                            if ($assetArray[$i]['isDelete'] == 1) {
                                                                echo "class=\"danger\"";
                                                            } else {
                                                                if ($assetArray[$i]['isDraft'] == 1) {
                                                                    echo "class=\"warning\"";
                                                                }
                                                            }
                                                            ?>>
                                                                <td align="center">
                                                                    <div align="center"><?php echo($counter + $offset); ?></div>
                                                                </td>
                                                                <td align="center">
                                                                    <div class="btn-group" align="center">
                                                                        <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                echo $asset->getControllerPath();
                                                                                ?>', '<?php
                                                                                echo $asset->getViewPath();
                                                                                ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                echo intval(
                                                                                        $assetArray [$i]['assetId']
                                                                                );
                                                                                ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                            <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                onclick="showModalDelete('<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetId']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['branchName']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['departmentDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['warehouseDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['locationDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['itemCategoryDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['itemTypeDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['businessPartnerDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['unitOfMeasurementDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['purchaseInvoiceDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetCode']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetSerialNumber']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetName']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetModel']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetPrice']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetDate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetWarranty']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetColor']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetQuantity']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetInsuranceBusinessPartnerId']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetInsuranceStartDate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetInsuranceExpiredDate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetWarrantyStartDate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetWarrantyEndDate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetDepreciationRate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetNetBookValue']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['assetPicture']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['isTransferAsKit']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['isDepreciate']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['isWriteOff']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $assetArray [$i]['isDispose']
                                                                                );
                                                                                ?>', '<?php echo rawurlencode($assetArray [$i]['isAdjust']); ?>');">
                                                                            <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="pull-left">
                                                                        <?php
                                                                        if (isset($assetArray[$i]['assetCode'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($assetArray[$i]['assetCode']), strtolower($_POST['query'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $assetArray[$i]['assetCode']
                                                                                        );
                                                                                    } else {
                                                                                        echo $assetArray[$i]['assetCode'];
                                                                                    }
                                                                                } else {
                                                                                    if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos(
                                                                                                        strtolower($assetArray[$i]['assetCode']), strtolower($_POST['character'])
                                                                                                ) !== false
                                                                                        ) {
                                                                                            echo str_replace(
                                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $assetArray[$i]['assetCode']
                                                                                            );
                                                                                        } else {
                                                                                            echo $assetArray[$i]['assetCode'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $assetArray[$i]['assetCode'];
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                echo $assetArray[$i]['assetCode'];
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <div class="pull-left">
                                                                        <?php
                                                                        if (isset($assetArray[$i]['assetSerialNumber'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($assetArray[$i]['assetSerialNumber']), strtolower($_POST['query'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $assetArray[$i]['assetSerialNumber']
                                                                                        );
                                                                                    } else {
                                                                                        echo $assetArray[$i]['assetSerialNumber'];
                                                                                    }
                                                                                } else {
                                                                                    if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos(
                                                                                                        strtolower($assetArray[$i]['assetSerialNumber']), strtolower($_POST['character'])
                                                                                                ) !== false
                                                                                        ) {
                                                                                            echo str_replace(
                                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $assetArray[$i]['assetSerialNumber']
                                                                                            );
                                                                                        } else {
                                                                                            echo $assetArray[$i]['assetSerialNumber'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $assetArray[$i]['assetSerialNumber'];
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                echo $assetArray[$i]['assetSerialNumber'];
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?>
                                                                </td>
                                                                <td>
                                                                    <div class="pull-left">
                                                                        <?php
                                                                        if (isset($assetArray[$i]['assetName'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($assetArray[$i]['assetName']), strtolower($_POST['query'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $assetArray[$i]['assetName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $assetArray[$i]['assetName'];
                                                                                    }
                                                                                } else {
                                                                                    if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos(
                                                                                                        strtolower($assetArray[$i]['assetName']), strtolower($_POST['character'])
                                                                                                ) !== false
                                                                                        ) {
                                                                                            echo str_replace(
                                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $assetArray[$i]['assetName']
                                                                                            );
                                                                                        } else {
                                                                                            echo $assetArray[$i]['assetName'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $assetArray[$i]['assetName'];
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                echo $assetArray[$i]['assetName'];
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?>
                                                                </td>

                                                                <?php
                                                                if (isset($assetArray[$i]['assetDate'])) {
                                                                    $valueArray = $assetArray[$i]['assetDate'];
                                                                    if ($dateConvert->checkDate($valueArray)) {
                                                                        $valueData = explode('-', $valueArray);
                                                                        $year = $valueData[0];
                                                                        $month = $valueData[1];
                                                                        $day = $valueData[2];
                                                                        $value = date(
                                                                                $systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year)
                                                                        );
                                                                    } else {
                                                                        $value = null;
                                                                    }
                                                                    ?>
                                                                    <td><?php echo $value; ?></td>
                                                                <?php } else { ?>
                                                                    <td>
                                                                        <div class="pull-left">&nbsp;</div>
                                                                    </td>
                                                                <?php } ?>
                                                                <?php
                                                                $d = $assetArray[$i]['assetNetBookValue'];
                                                                if (class_exists('NumberFormatter')) {
                                                                    if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                        $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                        $d = $a->format($assetArray[$i]['assetNetBookValue']);
                                                                    } else {
                                                                        $d = number_format($d) . " You can assign Currency Format ";
                                                                    }
                                                                } else {
                                                                    $d = number_format($d);
                                                                }
                                                                ?>
                                                                <td>
                                                                    <div class="pull-right"><?php echo $d; ?></div>
                                                                </td>
                                                                <?php
                                                                $d = $assetArray[$i]['assetPrice'];
                                                                if (class_exists('NumberFormatter')) {
                                                                    if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                        $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                        $d = $a->format($assetArray[$i]['assetPrice']);
                                                                    } else {
                                                                        $d = number_format($d) . " You can assign Currency Format ";
                                                                    }
                                                                } else {
                                                                    $d = number_format($d);
                                                                }
                                                                ?>
                                                                <td>
                                                                    <div class="pull-right"><?php echo $d; ?></div>
                                                                </td>
                                                                <td>
                                                                    <div class="pull-left">
                                                                        <?php
                                                                        if (isset($assetArray[$i]['executeBy'])) {
                                                                            if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                    if (strpos($assetArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                        echo str_replace(
                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $assetArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $assetArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                        if (strpos($assetArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                                            echo str_replace(
                                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $assetArray[$i]['staffName']
                                                                                            );
                                                                                        } else {
                                                                                            echo $assetArray[$i]['staffName'];
                                                                                        }
                                                                                    } else {
                                                                                        echo $assetArray[$i]['staffName'];
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                echo $assetArray[$i]['staffName'];
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        &nbsp;
                                                                    <?php } ?>
                                                                </td>
                                                                <?php
                                                                if (isset($assetArray[$i]['executeTime'])) {
                                                                    $valueArray = $assetArray[$i]['executeTime'];
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
                                                                        $value = date(
                                                                                $systemFormat['systemSettingDateFormat'] . " " . $systemFormat['systemSettingTimeFormat'], mktime($hour, $minute, $second, $month, $day, $year)
                                                                        );
                                                                    } else {
                                                                        $value = null;
                                                                    }
                                                                    ?>
                                                                    <td><?php echo $value; ?></td>
                                                                <?php } else { ?>
                                                                    <td>&nbsp;</td>
                                                                <?php } ?>
                                                                <?php
                                                                if ($assetArray[$i]['isDelete']) {
                                                                    $checked = "checked";
                                                                } else {
                                                                    $checked = null;
                                                                }
                                                                ?>
                                                                <td>
                                                                    <input style="display:none;" type="checkbox" name="assetId[]"
                                                                           value="<?php echo $assetArray[$i]['assetId']; ?>">
                                                                    <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                   value="<?php echo $assetArray[$i]['isDelete']; ?>">
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="12" valign="top" align="center"><?php
                                                                $asset->exceptionMessage(
                                                                        $t['recordNotFoundLabel']
                                                                );
                                                                ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="12" valign="top" align="center"><?php
                                                            $asset->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="12" valign="top" align="center"><?php
                                                        $asset->exceptionMessage(
                                                                $t['loadFailureLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tr class="success">
                                            <td colspan="11">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <div class="pull-right"><?php echo $t['totalTextLabel']; ?></div>
                                            </td>
                                            <td class="total">
                                                <div class="pull-right"><strong><?php
                                                        if (class_exists('NumberFormatter')) {
                                                            $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                            $d = $a->format($totalAssetNetBookValue);
                                                        } else {
                                                            $d = number_format($totalAssetNetBookValue) . " You can assign Currency Format ";
                                                        }
                                                        echo $d;
                                                        ?></strong></div>
                                            </td>
                                            <td class="total">
                                                <div class="pull-right"><strong><?php
                                                    if (class_exists('NumberFormatter')) {
                                                        $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                        $d = $a->format($totalAssetPriceValue);
                                                    } else {
                                                        $d = number_format($totalAssetPriceValue) . " You can assign Currency Format ";
                                                    }
                                                    echo $d;
                                                    ?></strong></div>
                                            </td>
                                            <td coslpan="2">&nbsp;</td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left"><?php $navigation->pagenationv4($offset); ?></div>
                        <div class="col-xs-3 col-sm-3 col-md-3 pagination">
                            <div class="pull-right">
                                <button class="delete btn btn-warning" type="button" 
                                        onclick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                        echo $asset->getControllerPath();
                                        ?>', '<?php echo $asset->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                    <i class="glyphicon glyphicon-white glyphicon-trash"></i>
                                </button>
                            </div>
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
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?php
                $template->setLayout(2);
                echo $template->breadcrumb(
                        $applicationNative, $moduleNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId
                );
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
                                    <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                       echo $asset->getControllerPath();
                                       ?>', '<?php
                                       echo $asset->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $asset->getControllerPath();
                                       ?>', '<?php
                                       echo $asset->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $asset->getControllerPath();
                                       ?>', '<?php
                                       echo $asset->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $asset->getControllerPath();
                                       ?>', '<?php
                                       echo $asset->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="assetId" id="assetId" value="<?php
                            if (isset($_POST['assetId'])) {
                                echo $_POST['assetId'];
                            }
                            ?>">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="branchIdForm">
                                        <label for="branchId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['branchIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="branchId" id="branchId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($branchArray)) {
                                                    $totalRecord = intval(count($branchArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['branchId'])) {
                                                                if ($assetArray[0]['branchId'] == $branchArray[$i]['branchId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $branchArray[$i]['branchId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $branchArray[$i]['branchName']; ?></option>
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
                                            </select> <span class="help-block" id="branchIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="departmentIdForm">
                                        <label for="departmentId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['departmentIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="departmentId" id="departmentId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($departmentArray)) {
                                                    $totalRecord = intval(count($departmentArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['departmentId'])) {
                                                                if ($assetArray[0]['departmentId'] == $departmentArray[$i]['departmentId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $departmentArray[$i]['departmentId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $departmentArray[$i]['departmentDescription']; ?></option>
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
                                            </select> <span class="help-block" id="departmentIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="warehouseIdForm">
                                        <label for="warehouseId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['warehouseIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="warehouseId" id="warehouseId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($warehouseArray)) {
                                                    $totalRecord = intval(count($warehouseArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['warehouseId'])) {
                                                                if ($assetArray[0]['warehouseId'] == $warehouseArray[$i]['warehouseId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $warehouseArray[$i]['warehouseId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $warehouseArray[$i]['warehouseDescription']; ?></option>
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
                                            </select> <span class="help-block" id="warehouseIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="locationIdForm">
                                        <label for="locationId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['locationIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="locationId" id="locationId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($locationArray)) {
                                                    $totalRecord = intval(count($locationArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['locationId'])) {
                                                                if ($assetArray[0]['locationId'] == $locationArray[$i]['locationId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $locationArray[$i]['locationId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $locationArray[$i]['locationDescription']; ?></option>
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
                                            </select> <span class="help-block" id="locationIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="itemCategoryIdForm">
                                        <label for="itemCategoryId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['itemCategoryIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="itemCategoryId" id="itemCategoryId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($itemCategoryArray)) {
                                                    $totalRecord = intval(count($itemCategoryArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['itemCategoryId'])) {
                                                                if ($assetArray[0]['itemCategoryId'] == $itemCategoryArray[$i]['itemCategoryId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $itemCategoryArray[$i]['itemCategoryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $itemCategoryArray[$i]['itemCategoryDescription']; ?></option>
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
                                            </select> <span class="help-block" id="itemCategoryIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="itemTypeIdForm">
                                        <label for="itemTypeId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['itemTypeIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="itemTypeId" id="itemTypeId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($itemTypeArray)) {
                                                    $totalRecord = intval(count($itemTypeArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['itemTypeId'])) {
                                                                if ($assetArray[0]['itemTypeId'] == $itemTypeArray[$i]['itemTypeId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $itemTypeArray[$i]['itemTypeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $itemTypeArray[$i]['itemTypeDescription']; ?></option>
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
                                            </select> <span class="help-block" id="itemTypeIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerIdForm">
                                        <label for="businessPartnerId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['businessPartnerIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="businessPartnerId" id="businessPartnerId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($businessPartnerArray)) {
                                                    $totalRecord = intval(count($businessPartnerArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['businessPartnerId'])) {
                                                                if ($assetArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $businessPartnerArray[$i]['businessPartnerDescription']; ?></option>
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
                                            </select> <span class="help-block" id="businessPartnerIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="unitOfMeasurementIdForm">
                                        <label for="unitOfMeasurementId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['unitOfMeasurementIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="unitOfMeasurementId" id="unitOfMeasurementId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($unitOfMeasurementArray)) {
                                                    $totalRecord = intval(count($unitOfMeasurementArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['unitOfMeasurementId'])) {
                                                                if ($assetArray[0]['unitOfMeasurementId'] == $unitOfMeasurementArray[$i]['unitOfMeasurementId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $unitOfMeasurementArray[$i]['unitOfMeasurementDescription']; ?></option>
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
                                            </select> <span class="help-block" id="unitOfMeasurementIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="purchaseInvoiceIdForm">
                                        <label for="purchaseInvoiceId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['purchaseInvoiceIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <select name="purchaseInvoiceId" id="purchaseInvoiceId" class="chzn-select form-control">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($purchaseInvoiceArray)) {
                                                    $totalRecord = intval(count($purchaseInvoiceArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($assetArray[0]['purchaseInvoiceId'])) {
                                                                if ($assetArray[0]['purchaseInvoiceId'] == $purchaseInvoiceArray[$i]['purchaseInvoiceId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $purchaseInvoiceArray[$i]['purchaseInvoiceId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $purchaseInvoiceArray[$i]['purchaseInvoiceDescription']; ?></option>
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
                                            </select> <span class="help-block" id="purchaseInvoiceIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetCodeForm">
                                        <label for="assetCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetCodeLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetCode" id="assetCode" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetCode'])) {
                                                    echo htmlentities($assetArray[0]['assetCode']);
                                                }
                                            }
                                            ?>" maxlength="16"> <span class="help-block" id="assetCodeHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetSerialNumberForm">
                                        <label for="assetSerialNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetSerialNumberLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetSerialNumber" id="assetSerialNumber"
                                                   value="<?php
                                                   if (isset($assetArray) && is_array($assetArray)) {
                                                       if (isset($assetArray[0]['assetSerialNumber'])) {
                                                           echo htmlentities($assetArray[0]['assetSerialNumber']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="assetSerialNumberHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetNameForm">
                                        <label for="assetName" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetNameLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetName" id="assetName" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetName'])) {
                                                    echo htmlentities($assetArray[0]['assetName']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetNameHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetDescriptionForm">
                                        <label for="assetDescription" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetDescriptionLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <textarea name="assetDescription" id="assetDescription" class="form-control"><?php
                                                if (isset($assetArray[0]['assetDescription'])) {
                                                    if (isset($assetArray[0]['assetDescription'])) {
                                                        echo htmlentities($assetArray[0]['assetDescription']);
                                                    }
                                                }
                                                ?></textarea> <span class="help-block" id="assetDescriptionHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetModelForm">
                                        <label for="assetModel" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetModelLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetModel" id="assetModel" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetModel'])) {
                                                    echo htmlentities($assetArray[0]['assetModel']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetModelHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetPriceForm">
                                        <label for="assetPrice" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetPriceLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetPrice" id="assetPrice" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetPrice'])) {
                                                    echo htmlentities($assetArray[0]['assetPrice']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetPriceHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($assetArray) && is_array($assetArray)) {
                                        if (isset($assetArray[0]['assetDate'])) {
                                            $valueArray = $assetArray[0]['assetDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        } else {
                                            $value = null;
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetDateForm">
                                        <label for="assetDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetDate" id="assetDate" value="<?php
                                            if (isset($value)) {
                                                echo $value;
                                            }
                                            ?>"> <span class="help-block" id="assetDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetWarrantyForm">
                                        <label for="assetWarranty" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetWarrantyLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetWarranty" id="assetWarranty" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetWarranty'])) {
                                                    echo htmlentities($assetArray[0]['assetWarranty']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetWarrantyHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetColorForm">
                                        <label for="assetColor" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetColorLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetColor" id="assetColor" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetColor'])) {
                                                    echo htmlentities($assetArray[0]['assetColor']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetColorHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetQuantityForm">
                                        <label for="assetQuantity" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetQuantityLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetQuantity" id="assetQuantity" value="<?php
                                            if (isset($assetArray[0]['assetQuantity'])) {
                                                if (isset($assetArray[0]['assetQuantity'])) {
                                                    echo htmlentities($assetArray[0]['assetQuantity']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetQuantityHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetInsuranceBusinessPartnerIdForm">
                                        <label for="assetInsuranceBusinessPartnerId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetInsuranceBusinessPartnerIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <span class="help-block" id="assetInsuranceBusinessPartnerIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?php
                                    if (isset($assetArray) && is_array($assetArray)) {
                                        if (isset($assetArray[0]['assetInsuranceStartDate'])) {
                                            $valueArray = $assetArray[0]['assetInsuranceStartDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        } else {
                                            $value = null;
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetInsuranceStartDateForm">
                                        <label for="assetInsuranceStartDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetInsuranceStartDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetInsuranceStartDate"
                                                   id="assetInsuranceStartDate" value="<?php
                                                   if (isset($value)) {
                                                       echo $value;
                                                   }
                                                   ?>"> <span class="help-block" id="assetInsuranceStartDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($assetArray) && is_array($assetArray)) {
                                        if (isset($assetArray[0]['assetInsuranceExpiredDate'])) {
                                            $valueArray = $assetArray[0]['assetInsuranceExpiredDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        } else {
                                            $value = null;
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetInsuranceExpiredDateForm">
                                        <label for="assetInsuranceExpiredDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetInsuranceExpiredDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetInsuranceExpiredDate"
                                                   id="assetInsuranceExpiredDate" value="<?php
                                                   if (isset($value)) {
                                                       echo $value;
                                                   }
                                                   ?>"> <span class="help-block" id="assetInsuranceExpiredDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?php
                                    if (isset($assetArray) && is_array($assetArray)) {
                                        if (isset($assetArray[0]['assetWarrantyStartDate'])) {
                                            $valueArray = $assetArray[0]['assetWarrantyStartDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        } else {
                                            $value = null;
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetWarrantyStartDateForm">
                                        <label for="assetWarrantyStartDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetWarrantyStartDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetWarrantyStartDate"
                                                   id="assetWarrantyStartDate" value="<?php
                                                   if (isset($value)) {
                                                       echo $value;
                                                   }
                                                   ?>"> <span class="help-block" id="assetWarrantyStartDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($assetArray) && is_array($assetArray)) {
                                        if (isset($assetArray[0]['assetWarrantyEndDate'])) {
                                            $valueArray = $assetArray[0]['assetWarrantyEndDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        } else {
                                            $value = null;
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetWarrantyEndDateForm">
                                        <label for="assetWarrantyEndDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetWarrantyEndDateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetWarrantyEndDate" id="assetWarrantyEndDate"
                                                   value="<?php
                                                   if (isset($value)) {
                                                       echo $value;
                                                   }
                                                   ?>"> <span class="help-block" id="assetWarrantyEndDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetDepreciationRateForm">
                                        <label for="assetDepreciationRate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetDepreciationRateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetDepreciationRate" id="assetDepreciationRate"
                                                   value="<?php
                                                   if (isset($assetArray) && is_array($assetArray)) {
                                                       if (isset($assetArray[0]['assetDepreciationRate'])) {
                                                           echo htmlentities($assetArray[0]['assetDepreciationRate']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="assetDepreciationRateHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetNetBookValueForm">
                                        <label for="assetNetBookValue" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetNetBookValueLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetNetBookValue" id="assetNetBookValue"
                                                   value="<?php
                                                   if (isset($assetArray) && is_array($assetArray)) {
                                                       if (isset($assetArray[0]['assetNetBookValue'])) {
                                                           echo htmlentities($assetArray[0]['assetNetBookValue']);
                                                       }
                                                   }
                                                   ?>"> <span class="help-block" id="assetNetBookValueHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="assetPictureForm">
                                        <label for="assetPicture" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['assetPictureLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="text" class="form-control" name="assetPicture" id="assetPicture" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['assetPicture'])) {
                                                    echo htmlentities($assetArray[0]['assetPicture']);
                                                }
                                            }
                                            ?>"> <span class="help-block" id="assetPictureHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isTransferAsKitForm">
                                        <label for="isTransferAsKit" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isTransferAsKitLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="checkbox" name="isTransferAsKit" id="isTransferAsKit" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['isTransferAsKit'])) {
                                                    echo $assetArray[0]['isTransferAsKit'];
                                                }
                                            }
                                            ?>"> <span class="help-block" id="isTransferAsKitHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isDepreciateForm">
                                        <label for="isDepreciate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isDepreciateLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="checkbox" name="isDepreciate" id="isDepreciate" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['isDepreciate'])) {
                                                    echo $assetArray[0]['isDepreciate'];
                                                }
                                            }
                                            ?>"> <span class="help-block" id="isDepreciateHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isWriteOffForm">
                                        <label for="isWriteOff" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isWriteOffLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="checkbox" name="isWriteOff" id="isWriteOff" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['isWriteOff'])) {
                                                    echo $assetArray[0]['isWriteOff'];
                                                }
                                            }
                                            ?>"> <span class="help-block" id="isWriteOffHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isDisposeForm">
                                        <label for="isDispose" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isDisposeLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="checkbox" name="isDispose" id="isDispose" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['isDispose'])) {
                                                    echo $assetArray[0]['isDispose'];
                                                }
                                            }
                                            ?>"> <span class="help-block" id="isDisposeHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isAdjustForm">
                                        <label for="isAdjust" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isAdjustLabel']
                                                );
                                                ?></strong></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input type="checkbox" name="isAdjust" id="isAdjust" value="<?php
                                            if (isset($assetArray) && is_array($assetArray)) {
                                                if (isset($assetArray[0]['isAdjust'])) {
                                                    echo $assetArray[0]['isAdjust'];
                                                }
                                            }
                                            ?>"> <span class="help-block" id="isAdjustHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12"></div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?>
                                </a>
                                <a id="newRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                   class="btn dropdown-toggle btn-success disabled"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li>
                                        <a id="newRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?>
                                        </a></li>
                                    <li>
                                        <a id="newRecordButton4" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?>
                                        </a></li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                          ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                          ?></a></li>-->
                                    <li>
                                        <a id="newRecordButton7" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?>
                                        </a></li>
                                </ul>
                            </div>
                            <div class="btn-group" align="left">
                                <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                        class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?>
                                </a>
                                <a id="updateRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                   class="btn dropdown-toggle btn-info disabled"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li>
                                        <a id="updateRecordButton3" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?>
                                        </a></li>
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                           ?></a></li> -->
                                    <li>
                                        <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled">
                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $asset->getControllerPath();
                                   ?>', '<?php
                                   echo $asset->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $asset->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php
                    if (isset($firstRecord)) {
                        echo intval($firstRecord);
                    }
                    ?>"> <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                           if (isset($nextRecord)) {
                               echo intval($nextRecord);
                           }
                           ?>"> <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php
                           if (isset($previousRecord)) {
                               echo intval($previousRecord);
                           }
                           ?>"> <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php
                           if (isset($lastRecord)) {
                               echo intval($lastRecord);
                           }
                           ?>"> <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php
                           if (isset($endRecord)) {
                               echo intval($endRecord);
                           }
                           ?>">
                </div>
            </div>
            </div>
            </form>
            <script type="text/javascript">
                $(document).keypress(function(e) {
    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                        // shift+n new record event
                        if (e.which === 78 && e.which === 18  && e.shiftKey) {
                            


                            newRecord(<?php echo $leafId; ?>, '<?php echo $asset->getControllerPath(); ?>', '<?php echo $asset->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);

                            return false;
                        }
    <?php } ?>
    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        // shift+s save event
                        if (e.which === 83 && e.which === 18  && e.shiftKey) {
                            


                            updateRecord(<?php echo $leafId; ?>, '<?php echo $asset->getControllerPath(); ?>', '<?php echo $asset->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                            return false;
                        }
    <?php } ?>
    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                        // shift+d delete event
                        if (e.which === 88 && e.which === 18 && e.shiftKey) {
                            


                            deleteRecord(<?php echo $leafId; ?>, '<?php echo $asset->getControllerPath(); ?>', '<?php echo $asset->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                            return false;

                        }
    <?php } ?>
                    switch (e.keyCode) {
                        case 37:
                            previousRecord(<?php echo $leafId; ?>, '<?php echo $asset->getControllerPath(); ?>', '<?php echo $asset->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                            
                            return false;
                            break;
                        case 39:
                            nextRecord(<?php echo $leafId; ?>, '<?php echo $asset->getControllerPath(); ?>', '<?php echo $asset->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                            
                            return false;
                            break;
                    }
                    

                });
                $(document).ready(function() {
                    window.scrollTo(0, 0);
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('assetId');
                    validateMeNumeric('branchId');
                    validateMeNumeric('departmentId');
                    validateMeNumeric('warehouseId');
                    validateMeNumeric('locationId');
                    validateMeNumeric('itemCategoryId');
                    validateMeNumeric('itemTypeId');
                    validateMeNumeric('businessPartnerId');
                    validateMeNumeric('unitOfMeasurementId');
                    validateMeNumeric('purchaseInvoiceId');
                    validateMeAlphaNumeric('assetCode');
                    validateMeAlphaNumeric('assetSerialNumber');
                    validateMeAlphaNumeric('assetName');
                    validateMeAlphaNumeric('assetModel');
                    validateMeCurrency('assetPrice');
                    $('#assetDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    validateMeAlphaNumeric('assetWarranty');
                    validateMeAlphaNumeric('assetColor');
                    validateMeNumeric('assetQuantity');
                    validateMeNumeric('assetInsuranceBusinessPartnerId');
                    $('#assetInsuranceStartDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    $('#assetInsuranceExpiredDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    $('#assetWarrantyStartDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    $('#assetWarrantyEndDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    validateMeCurrency('assetDepreciationRate');
                    validateMeCurrency('assetNetBookValue');
                    validateMeAlphaNumeric('assetPicture');
                    validateMeNumeric('isTransferAsKit');
                    validateMeNumeric('isDepreciate');
                    validateMeNumeric('isWriteOff');
                    validateMeNumeric('isDispose');
                    validateMeNumeric('isAdjust');
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton')
                                .removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1')
                                    .removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                            $('#newRecordButton1')
                                    .removeClass().addClass('btn btn-success disabled');
                            $('#newRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>
                        $('#updateRecordButton1')
                                .removeClass().addClass(' btn btn-info disabled');
                        $('#updateRecordButton2')
                                .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
                        $('#deleteRecordButton')
                                .removeClass().addClass('btn btn-danger disabled')
                                .attr('onClick', '');
                        $('#firstRecordButton')
                                .removeClass().addClass('btn btn-default');
                        $('#endRecordButton')
                                .removeClass().addClass('btn btn-default');
        <?php
    } else {
        if ($_POST['assetId']) {
            ?>
                            $('#newRecordButton1')
                                    .removeClass().addClass('btn btn-success disabled');
                            $('#newRecordButton2')
                                    .removeClass().addClass('btn dropdown-toggle btn-success disabled');
                            $('#newRecordButton3').attr('onClick', '');
                            $('#newRecordButton4').attr('onClick', '');
                            $('#newRecordButton5').attr('onClick', '');
                            $('#newRecordButton6').attr('onClick', '');
                            $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                $('#updateRecordButton1')
                                        .removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton2')
                                        .removeClass().addClass('btn dropdown-toggle btn-info');
                                $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#updateRecordButton1')
                                        .removeClass().addClass(' btn btn-info disabled');
                                $('#updateRecordButton2')
                                        .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                $('#updateRecordButton3').attr('onClick', '');
                                $('#updateRecordButton4').attr('onClick', '');
                                $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                $('#deleteRecordButton')
                                        .removeClass().addClass('btn btn-danger')
                                        .attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $asset->getControllerPath(); ?>','<?php echo $asset->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#deleteRecordButton')
                                        .removeClass().addClass('btn btn-danger disabled')
                                        .attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                });
            </script>
    <?php } ?>
    <script type="text/javascript" src="./v3/financial/fixedAsset/javascript/asset.js"></script>
    <hr>
    <footer><p>IDCMS 2012/2013</p></footer>