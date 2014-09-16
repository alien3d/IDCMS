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
require_once($newFakeDocumentRoot . "v3/financial/cashbook/controller/collectionController.php");
require_once($newFakeDocumentRoot . "v3/financial/cashbook/controller/collectionAllocationController.php");
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

$translator->setCurrentTable(array('collection', 'collectionAllocation'));

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
$collectionArray = array();
$collectionTypeArray = array();
$businessPartnerArray = array();
$countryArray = array();
$bankArray = array();
$paymentTypeArray = array();
$_GET['from'] = 'collection.php';
$_POST['from'] = 'collection.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $collection = new \Core\Financial\Cashbook\Collection\Controller\CollectionClass();
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
            $collection->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $collection->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $collection->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $collection->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $collection->setStartDay($start[2]);
            $collection->setStartMonth($start[1]);
            $collection->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $collection->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $collection->setEndDay($start[2]);
            $collection->setEndMonth($start[1]);
            $collection->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $collection->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $collection->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $collection->setServiceOutput('html');
        $collection->setLeafId($leafId);
        $collection->execute();
        $collectionTypeArray = $collection->getCollectionType();
        $businessPartnerArray = $collection->getBusinessPartner();
        $countryArray = $collection->getCountry();
        $bankArray = $collection->getBank();
        $paymentTypeArray = $collection->getPaymentType();
        if ($_POST['method'] == 'read') {
            $collection->setStart($offset);
            $collection->setLimit($limit); // normal system don't like paging..
            $collection->setPageOutput('html');
            $collectionArray = $collection->read();
            if (isset($collectionArray [0]['firstRecord'])) {
                $firstRecord = $collectionArray [0]['firstRecord'];
            }
            if (isset($collectionArray [0]['nextRecord'])) {
                $nextRecord = $collectionArray [0]['nextRecord'];
            }
            if (isset($collectionArray [0]['previousRecord'])) {
                $previousRecord = $collectionArray [0]['previousRecord'];
            }
            if (isset($collectionArray [0]['lastRecord'])) {
                $lastRecord = $collectionArray [0]['lastRecord'];
                $endRecord = $collectionArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($collection->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($collectionArray [0]['total'])) {
                $total = $collectionArray [0]['total'];
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
                    <button title="A" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">N</button>
                    <button title="O" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" href="javascript:void(0)" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $collection->getViewPath();
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
                                    echo $collection->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $collection->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $collection->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'html');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Html&nbsp;&nbsp; </a>
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

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block"
                                            onclick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $collection->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');">
                                        <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label><div class="input-group"><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                    if (isset($_POST['query'])) {
                                        echo $_POST['query'];
                                    }
                                    ?>"><span class="input-group-addon"><img src="./images/icons/magnifier.png" id="searchTextDateImage"></span></div><br>
                                <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $collection->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $collection->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $collection->getViewPath();
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
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Day <?php echo $previousDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $collection->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
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
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $collection->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $collection->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $collection->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>"
                                               onclick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                </table>
                                <div class="input-group"><input type="text" class="form-control"  name="dateRangeStart" id="dateRangeStart"  value="<?php
                                    if (isset($_POST['dateRangeStart'])) {
                                        echo $_POST['dateRangeStart'];
                                    }
                                    ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="startDateImage"></span></div><br>
                                <div class="input-group"><input type="text" class="form-control" name="dateRangeEnd"  id="dateRangeEnd" value="<?php
                                    if (isset($_POST['dateRangeEnd'])) {
                                        echo $_POST['dateRangeEnd'];
                                    }
                                    ?>" onClick="topPage(175);"  placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png" id="endDateImage"></span></div><br>
                                <input type="button"  name="searchDate" id="searchDate" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                       echo $collection->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $collection->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
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
                                                    <input type="hidden" name="collectionIdPreview" id="collectionIdPreview">

                                                    <div class="form-group" id="collectionTypeIdDiv">
                                                        <label for="collectionTypeIdPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['collectionTypeIdLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="collectionTypeIdPreview"
                                                                   id="collectionTypeIdPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="businessPartnerIdDiv">
                                                        <label for="businessPartnerIdPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="businessPartnerIdPreview"
                                                                   id="businessPartnerIdPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="countryIdDiv">
                                                        <label for="countryIdPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['countryIdLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="countryIdPreview"
                                                                   id="countryIdPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="bankIdDiv">
                                                        <label for="bankIdPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['bankIdLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="bankIdPreview"
                                                                   id="bankIdPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="paymentTypeIdDiv">
                                                        <label for="paymentTypeIdPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentTypeIdLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="paymentTypeIdPreview"
                                                                   id="paymentTypeIdPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="documentNumberDiv">
                                                        <label for="documentNumberPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['documentNumberLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="documentNumberPreview"
                                                                   id="documentNumberPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="referenceNumberDiv">
                                                        <label for="referenceNumberPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['referenceNumberLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="referenceNumberPreview"
                                                                   id="referenceNumberPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="chequeNumberDiv">
                                                        <label for="chequeNumberPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chequeNumberLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="chequeNumberPreview"
                                                                   id="chequeNumberPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="collectionAmountDiv">
                                                        <label for="collectionAmountPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['collectionAmountLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="collectionAmountPreview"
                                                                   id="collectionAmountPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="collectionDateDiv">
                                                        <label for="collectionDatePreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['collectionDateLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="collectionDatePreview"
                                                                   id="collectionDatePreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="collectionBankInSlipNumberDiv">
                                                        <label for="collectionBankInSlipNumberPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['collectionBankInSlipNumberLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control"
                                                                   name="collectionBankInSlipNumberPreview"
                                                                   id="collectionBankInSlipNumberPreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="collectionBankInSlipDateDiv">
                                                        <label for="collectionBankInSlipDatePreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['collectionBankInSlipDateLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control"
                                                                   name="collectionBankInSlipDatePreview" id="collectionBankInSlipDatePreview">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="collectionDescriptionDiv">
                                                        <label for="collectionDescriptionPreview"
                                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['collectionDescriptionLabel']; ?></label>

                                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                                            <input type="text" class="form-control" name="collectionDescriptionPreview"
                                                                   id="collectionDescriptionPreview">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"  class="btn btn-danger"
                                                            onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                                            echo $collection->getControllerPath();
                                                            ?>', '<?php
                                                            echo $collection->getViewPath();
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
                                                    <th width="75px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                                    <th width="100px">
                                                    <div align="center"><?php echo ucwords($leafTranslation['collectionDateLabel']); ?></div>
                                                    </th>
                                                    <th width="100px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                                    <th><?php echo ucwords($leafTranslation['collectionDescriptionLabel']); ?></th>
                                                    <th width="100px">
                                                    <div align="center"><?php echo ucwords($leafTranslation['collectionAmountLabel']); ?></div>
                                                    </th>
                                                    <th width="100px">
                                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                                    </th>
                                                    <th width="125px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?>
                                                    </th>
                                                    <th width="25px" align="center">
                                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                                               onChange="toggleChecked(this.checked);">
                                                    </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tableBody">
                                                        <?php
                                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                            $totalCollection = 0;
                                                            if (is_array($collectionArray)) {
                                                                $totalRecord = intval(count($collectionArray));
                                                                if ($totalRecord > 0) {
                                                                    $counter = 0;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        $counter++;
                                                                        ?>
                                                                        <tr <?php
                                                                        if ($collectionArray[$i]['isDelete'] == 1) {
                                                                            echo "class=\"danger\"";
                                                                        } else {
                                                                            if ($collectionArray[$i]['isDraft'] == 1) {
                                                                                echo "class=\"warning\"";
                                                                            }
                                                                        }
                                                                        ?>>
                                                                            <td>
                                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                                            </td>
                                                                            <td valign="top" align="center">
                                                                                <div class="btn-group" align="center">
                                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                            echo $collection->getControllerPath();
                                                                                            ?>', '<?php
                                                                                            echo $collection->getViewPath();
                                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                            echo intval(
                                                                                                    $collectionArray [$i]['collectionId']
                                                                                            );
                                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete" onclick="showModalDelete('<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionId']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['documentNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['referenceNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['chequeNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionAmount']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDate']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionBankInSlipNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionBankInSlipDate']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $collectionArray [$i]['collectionDescription']
                                                                                    );
                                                                                    ?>');">
                                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($collectionArray[$i]['documentNumber'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                strtolower($collectionArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $collectionArray[$i]['documentNumber']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['documentNumber'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    strtolower($collectionArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $collectionArray[$i]['documentNumber']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $collectionArray[$i]['documentNumber'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['documentNumber'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $collectionArray[$i]['documentNumber'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php
                                                                            if (isset($collectionArray[$i]['collectionDate'])) {
                                                                                $valueArray = $collectionArray[$i]['collectionDate'];
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
                                                                            <td>
                                                                                <div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($collectionArray[$i]['businessPartnerCompany'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                $collectionArray[$i]['businessPartnerCompany'], $_POST['query']
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $collectionArray[$i]['businessPartnerCompany']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['businessPartnerCompany'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    $collectionArray[$i]['businessPartnerCompany'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $collectionArray[$i]['businessPartnerCompany']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $collectionArray[$i]['businessPartnerCompany'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['businessPartnerCompany'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $collectionArray[$i]['businessPartnerCompany'];
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
                                                                                    if (isset($collectionArray[$i]['collectionDescription'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos(
                                                                                                                strtolower($collectionArray[$i]['collectionDescription']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $collectionArray[$i]['collectionDescription']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['collectionDescription'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    strtolower($collectionArray[$i]['collectionDescription']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $collectionArray[$i]['collectionDescription']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $collectionArray[$i]['collectionDescription'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['collectionDescription'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $collectionArray[$i]['collectionDescription'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php
                                                                            $d = $collectionArray[$i]['collectionAmount'];
                                                                            $totalCollection += $d;
                                                                            if (class_exists('NumberFormatter')) {
                                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                                    $d = $a->format($collectionArray[$i]['collectionAmount']);
                                                                                } else {
                                                                                    $d = number_format($d) . " You can assign Currency Format ";
                                                                                }
                                                                            } else {
                                                                                $d = number_format($d);
                                                                            }
                                                                            ?>
                                                                            <td align="right">
                                                                                <div class="pull-right"><?php echo $d; ?></div>
                                                                            </td>
                                                                            <td>
                                                                                <div align="center">
                                                                                    <?php
                                                                                    if (isset($collectionArray[$i]['executeBy'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                                if (strpos($collectionArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $collectionArray[$i]['staffName']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['staffName'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                                    if (strpos(
                                                                                                                    $collectionArray[$i]['staffName'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $collectionArray[$i]['staffName']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $collectionArray[$i]['staffName'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $collectionArray[$i]['staffName'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $collectionArray[$i]['staffName'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php
                                                                            if (isset($collectionArray[$i]['executeTime'])) {
                                                                                $valueArray = $collectionArray[$i]['executeTime'];
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
                                                                            if ($collectionArray[$i]['isDelete']) {
                                                                                $checked = "checked";
                                                                            } else {
                                                                                $checked = null;
                                                                            }
                                                                            ?>
                                                                            <td>
                                                                                <input style="display:none;" type="checkbox" name="collectionId[]"
                                                                                       value="<?php echo $collectionArray[$i]['collectionId']; ?>">
                                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                               value="<?php echo $collectionArray[$i]['isDelete']; ?>">
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="10" valign="top" align="center"><?php
                                                                            $collection->exceptionMessage(
                                                                                    $t['recordNotFoundLabel']
                                                                            );
                                                                            ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="10" valign="top" align="center"><?php
                                                                        $collection->exceptionMessage(
                                                                                $t['recordNotFoundLabel']
                                                                        );
                                                                        ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="10" valign="top" align="center"><?php
                                                                    $collection->exceptionMessage(
                                                                            $t['loadFailureLabel']
                                                                    );
                                                                    ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="10">&nbsp;</td>
                                                        </tr>
                                                        <tr class="success">
                                                            <td colspan="6">
                                                                <div class="pull-right"><?php echo $t['totalTextLabel']; ?></div>
                                                            </td>
                                                            <td>
                                                                <div class="pull-right"><strong><?php
                                                                        if (class_exists('NumberFormatter')) {
                                                                            $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                            $d = $a->format($totalCollection);
                                                                        } else {
                                                                            $d = number_format($totalCollection) . " You can assign Currency Format ";
                                                                        }
                                                                        echo $d;
                                                                        ?></strong>
                                                                </div>
                                                            </td>
                                                            <td colspan="3">&nbsp;</td>
                                                        </tr>
                                                    </tfoot>
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
                                                        echo $collection->getControllerPath();
                                                        ?>', '<?php echo $collection->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
                <?php
                $collectionAllocation = new \Core\Financial\Cashbook\CollectionAllocation\Controller\CollectionAllocationClass();
                $collectionAllocation->setServiceOutput('html');
                $collectionAllocation->setLeafId($leafId);
                $collectionAllocation->execute();
                $countryArray = $collectionAllocation->getCountry();
                $invoiceArray = $collectionAllocation->getInvoice();
                $collectionAllocation->setStart(0);
                $collectionAllocation->setLimit(999999); // normal system don't like paging..
                $collectionAllocation->setPageOutput('html');
                if (isset($_POST['collectionId'])) {
                    $collectionAllocationArray = $collectionAllocation->read();
                }
                ?>
                <form class="form-horizontal">
                    <input type="hidden" name="collectionId" id="collectionId" value="<?php
                    if (isset($_POST['collectionId'])) {
                        echo $_POST['collectionId'];
                    }
                    ?>">

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
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div align="right">

                                        <div class="btn-group">
                                            <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                               onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getControllerPath();
                                               ?>', '<?php echo $collection->getViewPath(); ?>', '<?php
                                               echo $collectionAllocation->getControllerPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                               onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getControllerPath();
                                               ?>', '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                               onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getControllerPath();
                                               ?>', '<?php
                                               echo $collection->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                               onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                               echo $collection->getControllerPath();
                                               ?>', '<?php echo $collection->getViewPath(); ?>', '<?php
                                               echo $collectionAllocation->getControllerPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group" id="collectionTypeIdForm">
                                                <label for="collectionTypeId" class="control-label col-xs-2 col-sm-2 col-md-2"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['collectionTypeIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                                    <select name="collectionTypeId" id="collectionTypeId" class="chzn-select form-control"
                                                            style="width:190px" onChange="">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($collectionTypeArray)) {
                                                            $totalRecord = intval(count($collectionTypeArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if (isset($collectionArray[0]['collectionTypeId'])) {
                                                                        if ($collectionArray[0]['collectionTypeId'] == $collectionTypeArray[$i]['collectionTypeId']) {
                                                                            $selected = "selected";
                                                                        } else {
                                                                            $selected = null;
                                                                        }
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $collectionTypeArray[$i]['collectionTypeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                        . <?php echo $collectionTypeArray[$i]['collectionTypeDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="collectionTypeIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <?php
                                            if (isset($collectionArray) && is_array($collectionArray)) {
                                                if (isset($collectionArray[0]['collectionDate'])) {
                                                    $valueArray = $collectionArray[0]['collectionDate'];
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
                                            <div class="col-md-4 form-group" id="collectionDateForm">
                                                <label for="collectionDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['collectionDateLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input class="form-control" type="text" name="collectionDate" id="collectionDate"
                                                               value="<?php
                                                               if (isset($value)) {
                                                                   echo $value;
                                                               }
                                                               ?>">
                                                        <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                             id="collectionDateImage"></span>
                                                    </div>
                                                    <span class="help-block" id="collectionDateHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group" id="documentNumberForm">
                                                <label for="documentNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['documentNumberLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control disabled" name="documentNumber"
                                                               id="documentNumber"

                                                               <?php
                                                               if (!isset($_POST['collectionId'])) {
                                                                   echo "disabled";
                                                               }
                                                               ?> value="<?php
                                                               if (isset($collectionArray) && is_array($collectionArray)) {
                                                                   if (isset($collectionArray[0]['documentNumber'])) {
                                                                       echo htmlentities($collectionArray[0]['documentNumber']);
                                                                   }
                                                               }
                                                               ?>"><span class="input-group-addon"><img src="./images/icons/document-number.png"></span></div>
                                                </div>
                                                <span class="help-block" id="documentNumberHelpMe"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-md-4 form-group" id="businessPartnerIdForm">
                                                    <label for="businessPartnerId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['businessPartnerIdLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                                        <select name="businessPartnerId" id="businessPartnerId" class="chzn-select form-control"
                                                                style="width:190px">
                                                            <option value=""></option>
                                                            <?php
                                                            if (is_array($businessPartnerArray)) {
                                                                $totalRecord = intval(count($businessPartnerArray));
                                                                $businessPartnerCategoryDescription = null;
                                                                if ($totalRecord > 0) {
                                                                    $d = 1;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        if ($i != 0) {
                                                                            if ($businessPartnerCategoryDescription != $businessPartnerArray[$i]['businessPartnerCategoryDescription']) {
                                                                                echo "</optgroup><optgroup label=\"" . $businessPartnerArray[$i]['businessPartnerCategoryDescription'] . "\">";
                                                                            }
                                                                        } else {
                                                                            echo "<optgroup label=\"" . $businessPartnerArray[$i]['businessPartnerCategoryDescription'] . "\">";
                                                                        }
                                                                        $businessPartnerCategoryDescription = $businessPartnerArray[$i]['businessPartnerCategoryDescription'];
                                                                        if (isset($collectionArray[0]['businessPartnerId'])) {
                                                                            if ($collectionArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
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
                                                                            . <?php echo $businessPartnerArray[$i]['businessPartnerCompany']; ?></option>
                                                                        <?php
                                                                        $d++;
                                                                    }
                                                                    echo "</optgroup>";
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
                                                <div class="col-md-4 form-group" id="paymentTypeIdForm">
                                                    <label for="paymentTypeId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['paymentTypeIdLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <select name="paymentTypeId" id="paymentTypeId" class="chzn-select form-control"
                                                                style="width:190px">
                                                            <option value=""></option>
                                                            <?php
                                                            if (is_array($paymentTypeArray)) {
                                                                $totalRecord = intval(count($paymentTypeArray));
                                                                if ($totalRecord > 0) {
                                                                    $d = 1;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        if (isset($collectionArray[0]['paymentTypeId'])) {
                                                                            if ($collectionArray[0]['paymentTypeId'] == $paymentTypeArray[$i]['paymentTypeId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                        } else {
                                                                            $selected = null;
                                                                        }
                                                                        ?>
                                                                        <option
                                                                            value="<?php echo $paymentTypeArray[$i]['paymentTypeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                            . <?php echo $paymentTypeArray[$i]['paymentTypeDescription']; ?></option>
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
                                                        </select> <span class="help-block" id="paymentTypeIdHelpMe"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 form-group" id="referenceNumberForm">
                                                    <label for="referenceNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['referenceNumberLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input class="form-control" type="text" name="referenceNumber" id="referenceNumber"
                                                                   value="<?php
                                                                   if (isset($collectionArray) && is_array($collectionArray)) {
                                                                       if (isset($collectionArray[0]['referenceNumber'])) {
                                                                           echo htmlentities($collectionArray[0]['referenceNumber']);
                                                                       }
                                                                   }
                                                                   ?>"> <span class="input-group-addon"><img
                                                                    src="./images/icons/document-number.png"></span></div>
                                                        <span class="help-block" id="referenceNumberHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-md-4 form-group" id="bankIdForm">
                                                    <label for="bankId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['bankIdLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <select name="bankId" id="bankId" class="chzn-select form-control" style="width:190px">
                                                            <option value=""></option>
                                                            <?php
                                                            if (is_array($bankArray)) {
                                                                $totalRecord = intval(count($bankArray));
                                                                if ($totalRecord > 0) {
                                                                    $d = 1;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        if (isset($collectionArray[0]['bankId'])) {
                                                                            if ($collectionArray[0]['bankId'] == $bankArray[$i]['bankId']) {
                                                                                $selected = "selected";
                                                                            } else {
                                                                                $selected = null;
                                                                            }
                                                                        } else {
                                                                            $selected = null;
                                                                        }
                                                                        ?>
                                                                        <option
                                                                            value="<?php echo $bankArray[$i]['bankId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                            . <?php echo $bankArray[$i]['bankDescription']; ?></option>
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
                                                        </select> <span class="help-block" id="bankIdHelpMe"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 form-group" id="collectionBankInSlipNumberForm">
                                                    <label for="collectionBankInSlipNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['collectionBankInSlipNumberLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input class="form-control" type="text" name="collectionBankInSlipNumber"
                                                                   id="collectionBankInSlipNumber" value="<?php
                                                                   if (isset($collectionArray) && is_array($collectionArray)) {
                                                                       if (isset($collectionArray[0]['collectionBankInSlipNumber'])) {
                                                                           echo htmlentities($collectionArray[0]['collectionBankInSlipNumber']);
                                                                       }
                                                                   }
                                                                   ?>"> <span class="input-group-addon"><img src="./images/icons/document-number.png"></span></div>
                                                        <span class="help-block" id="collectionBankInSlipNumberHelpMe"></span>
                                                    </div>
                                                </div>
                                                <?php
                                                if (isset($collectionArray) && is_array($collectionArray)) {
                                                    if (isset($collectionArray[0]['collectionBankInSlipDate'])) {
                                                        $valueArray = $collectionArray[0]['collectionBankInSlipDate'];
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
                                                <div class="col-md-4 form-group" id="collectionBankInSlipDateForm">
                                                    <label for="collectionBankInSlipDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['collectionBankInSlipDateLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="collectionBankInSlipDate" id="collectionBankInSlipDate" value="<?php
                                                            if (isset($value)) {
                                                                echo $value;
                                                            }
                                                            ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                 id="collectionBankInSlipDateImage"></span>
                                                        </div>
                                                        <span class="help-block" id="collectionBankInSlipDateHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-md-4 form-group" id="collectionAmountForm">
                                                    <label for="collectionAmount" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['collectionAmountLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="collectionAmount" id="collectionAmount" value="<?php
                                                            if (isset($collectionArray) && is_array($collectionArray)) {
                                                                if (isset($collectionArray[0]['collectionAmount'])) {
                                                                    echo htmlentities($collectionArray[0]['collectionAmount']);
                                                                }
                                                            }
                                                            ?>"> <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                        <span class="help-block" id="collectionAmountHelpMe"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 form-group" id="chequeNumberForm">
                                                    <label for="chequeNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['chequeNumberLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                   name="chequeNumber" id="chequeNumber" value="<?php
                                                                   if (isset($collectionArray) && is_array($collectionArray)) {
                                                                       if (isset($collectionArray[0]['chequeNumber'])) {
                                                                           echo htmlentities($collectionArray[0]['chequeNumber']);
                                                                       }
                                                                   }
                                                                   ?>"> <span class="input-group-addon"><img src="./images/icons/cheque-sign.png"></span></div>
                                                        <span class="help-block" id="chequeNumberHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-group" id="collectionDescriptionForm">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <textarea rows="3" class="form-control" name="collectionDescription"
                                                                  id="collectionDescription"><?php
                                                                      if (isset($collectionArray[0]['collectionDescription'])) {
                                                                          echo htmlentities($collectionArray[0]['collectionDescription']);
                                                                      }
                                                                      ?></textarea> <span class="help-block" id="collectionDescriptionHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                           echo $collection->getControllerPath();
                                           ?>', '<?php
                                           echo $collection->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                        </a>
                                    </div>

                                    <div class="btn-group">
                                        <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                           onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $collection->getViewPath();
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
                        <div class="modal hide" id="deleteDetailPreview" tabindex="-1">
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="collectionAllocationIdPreview" id="collectionAllocationIdPreview">

                                <div class="form-group" id="countryIdDiv">
                                    <label for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>
                                    <input type="text" class="form-control" name="countryIdPreview" id="countryIdPreview">
                                </div>
                                <div class="form-group" id="invoiceIdDiv">
                                    <label for="invoiceIdPreview"><?php echo $leafTranslation['invoiceIdLabel']; ?></label>
                                    <input type="text" class="form-control" name="invoiceIdPreview" id="invoiceIdPreview">
                                </div>
                                <div class="form-group" id="collectionAllocationAmountDiv">
                                    <label
                                        for="collectionAllocationAmountPreview"><?php echo $leafTranslation['collectionAllocationAmountLabel']; ?></label>
                                    <input type="text" class="form-control" name="collectionAllocationAmountPreview"
                                           id="collectionAllocationAmountPreview">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"  class="btn btn-danger" onclick="deleteGridRecordDetail(<?php echo $leafId; ?>, '<?php
                                echo $collectionAllocation->getControllerPath();
                                ?>', '<?php
                                echo $collectionAllocation->getViewPath();
                                ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                <button type="button"  onclick="showMeModal('deleteDetailPreview', 0);" class="btn btn-default"
                                        data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="panel panel-default">
                                    <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                        <thead>
                                            <tr>
                                                <th width="25px" align="center">
                                        <div align="center">#</div>
                                        </th>
                                        <th width="100px"><?php echo ucfirst($t['actionTextLabel']); ?></th>
                                        <th width="100px"><?php echo ucfirst($leafTranslation['countryIdLabel']); ?></th>
                                        <th><?php echo ucfirst($leafTranslation['invoiceIdLabel']); ?></th>
                                        <th width="100px"><?php echo ucfirst($leafTranslation['collectionAllocationAmountLabel']); ?></th>
                                        </tr>
                                        <tr>
                                            <?php
                                            $disabledDetail = null;
                                            if (isset($_POST['collectionId']) && (strlen($_POST['collectionId']) > 0)) {
                                                $disabledDetail = null;
                                            } else {
                                                $disabledDetail = "disabled";
                                            }
                                            ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td valign="middle" align="center">
                                                <div align="center">
                                                    <button class="btn btn-success" title="<?php echo $t['newButtonLabel']; ?>" onclick="showFormCreateDetail('<?php echo $leafId; ?>', '<?php echo $collectionAllocation->getControllerPath();
                                            ?>', '<?php echo $securityToken; ?>');"><i class="glyphicon glyphicon-plus  glyphicon-white"></i></button>

                                                    <div id="miniInfoPanel9999"></div>
                                                </div>
                                            </td>
                                            <td valign="top">
                                                <div class="form-group" id="countryId9999Detail">
                                                    <select name="countryId[]" id="countryId9999" class="chzn-select form-control"
                                                            onChange="removeMeErrorDetail('countryId9999');"  <?php echo $disabledDetail; ?>>
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($countryArray)) {
                                                            $totalRecord = intval(count($countryArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $countryArray[$i]['countryId']; ?>"><?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                                    </select><span class="help-block" id="countryId9999HelpMe"></span></div>
                                            </td>
                                            <td valign="top">
                                                <div class="form-group" id="invoiceId9999Detail">
                                                    <select name="invoiceId[]" id="invoiceId9999" class="chzn-select form-control"
                                                            onChange="removeMeErrorDetail('invoiceId9999');"  <?php echo $disabledDetail; ?>>
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($invoiceArray)) {
                                                            $totalRecord = intval(count($invoiceArray));
                                                            if ($totalRecord > 0) {
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $invoiceArray[$i]['invoiceId']; ?>"><?php echo $invoiceArray[$i]['invoiceDescription']; ?></option>
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
                                                    </select><span class="help-block" id="invoiceId9999HelpMe"></span></div>
                                            </td>
                                            <td valign="top">
                                                <div class="form-group" id="collectionAllocationAmount9999Detail">
                                                    <input class="col-xs-10 col-sm-10 col-md-10"   <?php echo $disabledDetail; ?> type="text"
                                                           name="collectionAllocationAmount[]" id="collectionAllocationAmount9999"
                                                           onblur="removeMeErrorDetail('collectionAllocationAmount9999');"
                                                           onkeyup="removeMeErrorDetail('collectionAllocationAmount9999');"><span class="help-block"
                                                           id="collectionAllocationAmount9999HelpMe"></span>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            if (isset($_POST['collectionId'])) {
                                                if (is_array($collectionAllocationArray)) {
                                                    $totalRecordDetail = intval(count($collectionAllocationArray));
                                                    if ($totalRecordDetail > 0) {
                                                        $counter = 0;
                                                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                            $counter++;
                                                            ?>
                                                            <tr id="<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>">
                                                                <td>
                                                                    <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <input type="hidden" name="collectionAllocationId[]"
                                                                               id="collectionAllocationId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>"
                                                                               value="<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>">
                                                                        <input type="hidden" name="collectionId[]"
                                                                               id="collectionId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>"
                                                                               value="<?php echo $collectionAllocationArray[$j]['collectionId']; ?>">
                                                                        <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                onclick="showFormUpdateDetail(<?php echo $leafId; ?>, '<?php
                                                                                echo $collectionAllocation->getControllerPath();
                                                                                ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                echo intval(
                                                                                        $collectionAllocationArray [$j]['collectionAllocationId']
                                                                                );
                                                                                ?>');">
                                                                            <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                onclick="showModalDeleteDetail('<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>');">
                                                                            <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                        <div
                                                                            id="miniInfoPanel<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>"></div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group"
                                                                         id="countryId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>Detail">
                                                                        <select name="countryId[]"
                                                                                id="countryId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>"
                                                                                class="chzn-select form-control"
                                                                                onChange="removeMeErrorDetail('countryId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>');">
                                                                            <option value=""></option>
                                                                            <?php
                                                                            if (is_array($countryArray)) {
                                                                                $totalRecord = intval(count($countryArray));
                                                                                if ($totalRecord > 0) {
                                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                                        if ($collectionAllocationArray[$j]['countryId'] == $countryArray[$i]['countryId']) {
                                                                                            $selected = "selected";
                                                                                        } else {
                                                                                            $selected = null;
                                                                                        }
                                                                                        ?>
                                                                                        <option
                                                                                            value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                                                        </select></div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group"
                                                                         id="invoiceId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>Detail">
                                                                        <select name="invoiceId[]"
                                                                                id="invoiceId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>"
                                                                                class="chzn-select form-control"
                                                                                onChange="removeMeErrorDetail('invoiceId<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>');">
                                                                            <option value=""></option>
                                                                            <?php
                                                                            if (is_array($invoiceArray)) {
                                                                                $totalRecord = intval(count($invoiceArray));
                                                                                if ($totalRecord > 0) {
                                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                                        if ($collectionAllocationArray[$j]['invoiceId'] == $invoiceArray[$i]['invoiceId']) {
                                                                                            $selected = "selected";
                                                                                        } else {
                                                                                            $selected = null;
                                                                                        }
                                                                                        ?>
                                                                                        <option
                                                                                            value="<?php echo $invoiceArray[$i]['invoiceId']; ?>" <?php echo $selected; ?>><?php echo $invoiceArray[$i]['invoiceDescription']; ?></option>
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
                                                                        </select></div>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" style="text-align:right" type="text"
                                                                           name="collectionAllocationAmount[]"
                                                                           id="collectionAllocationAmount<?php echo $collectionAllocationArray[$j]['collectionAllocationId']; ?>"
                                                                           value="<?php
                                                                           if (isset($collectionAllocationArray) && is_array($collectionAllocationArray)) {
                                                                               echo $collectionAllocationArray[$j]['collectionAllocationAmount'];
                                                                           }
                                                                           ?>"></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="6"><?php $collectionAllocation->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="6"><?php $collectionAllocation->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div></form>
                <script type="text/javascript">
                    $(document).ready(function() {
                        window.scrollTo(0, 0);
                        $(".chzn-select").chosen({search_contains: true});
                        $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                        validateMeNumeric('collectionId');
                        validateMeNumeric('collectionTypeId');
                        validateMeNumeric('businessPartnerId');
                        validateMeNumeric('countryId');
                        validateMeNumeric('bankId');
                        validateMeNumeric('paymentTypeId');

                        validateMeAlphaNumeric('referenceNumber');
                        validateMeAlphaNumeric('chequeNumber');
                        validateMeCurrency('collectionAmount');
                        $('#collectionDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        validateMeAlphaNumeric('collectionBankInSlipNumber');
                        $('#collectionBankInSlipDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        validateMeNumericRange('collectionAllocationId');
                        validateMeNumericRange('countryId');
                        validateMeNumericRange('invoiceId');
                        validateMeNumericRange('collectionId');
                        validateMeCurrencyRange('collectionAllocationAmount');
    <?php if ($_POST['method'] == "new") { ?>
                            $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                                $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                                $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                                $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                                $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>
                            $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                            $('#firstRecordButton').removeClass().addClass('btn btn-default');
                            $('#endRecordButton').removeClass().addClass('btn btn-default');
        <?php
    } else {
        if ($_POST['collectionId']) {
            ?>
                                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                                $('#newRecordButton3').attr('onClick', '');
                                $('#newRecordButton4').attr('onClick', '');
                                $('#newRecordButton5').attr('onClick', '');
                                $('#newRecordButton6').attr('onClick', '');
                                $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                    $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                    $('#updateRecordButton3').attr('onClick', '');
                                    $('#updateRecordButton4').attr('onClick', '');
                                    $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $collection->getControllerPath(); ?>','<?php echo $collection->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                    });
                </script>
            <?php } ?>
            <script type="text/javascript" src="./v3/financial/cashbook/javascript/collection.js"></script>
            <hr>
            <footer><p>IDCMS 2012/2013</p></footer>