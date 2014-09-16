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
require_once($newFakeDocumentRoot . "v3/financial/cashbook/controller/paymentVoucherController.php");
require_once($newFakeDocumentRoot . "v3/financial/cashbook/controller/paymentVoucherDetailController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
$dateConvert = new \Core\Date\DateClass();
$dateRangeStart = null;
if (isset($_POST['dateRangeStart'])) {
    if ($dateConvert->checkDate($_POST ['dateRangeStart'])) {
        $dateRangeStart = $_POST['dateRangeStart'];
    } else {
        $dateRangeStart = date('d-m-Y');
    }
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

$translator->setCurrentTable(array('paymentVoucher', 'paymentVoucherAllocation'));

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
$paymentVoucherArray = array();
$bankArray = array();
$businessPartnerCategoryArray = array();
$businessPartnerArray = array();
$_POST['from'] = 'paymentVoucherPosting.php';
$_GET['from'] = 'paymentVoucherPosting.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $paymentVoucher = new \Core\Financial\Cashbook\PaymentVoucher\Controller\PaymentVoucherClass();
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
            $paymentVoucher->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $paymentVoucher->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $paymentVoucher->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            // only checked valid date
            //if($dateConvert->checkDate($_POST ['dateRangeStart'])) {
            $paymentVoucher->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $paymentVoucher->setStartDay($start[2]);
            $paymentVoucher->setStartMonth($start[1]);
            $paymentVoucher->setStartYear($start[0]);
            //}
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            if ($dateConvert->checkDate($_POST ['dateRangeEnd'])) {
                $paymentVoucher->setDateRangeEndQuery($_POST['dateRangeEnd']);
                //explode the data to get day,month,year
                $start = explode('-', $_POST ['dateRangeEnd']);
                $paymentVoucher->setEndDay($start[2]);
                $paymentVoucher->setEndMonth($start[1]);
                $paymentVoucher->setEndYear($start[0]);
            }
        }
        if (isset($_POST ['dateRangeType'])) {
            $paymentVoucher->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $paymentVoucher->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $paymentVoucher->setServiceOutput('html');
        $paymentVoucher->setLeafId($leafId);
        $paymentVoucher->execute();
        $bankArray = $paymentVoucher->getBank();
        $businessPartnerCategoryArray = $paymentVoucher->getBusinessPartnerCategory();
        $businessPartnerArray = $paymentVoucher->getBusinessPartner();
        if ($_POST['method'] == 'read') {
            $paymentVoucher->setStart($offset);
            $paymentVoucher->setLimit($limit); // normal system don't like paging..
            $paymentVoucher->setPageOutput('html');
            $paymentVoucherArray = $paymentVoucher->read();
            if (isset($paymentVoucherArray [0]['firstRecord'])) {
                $firstRecord = $paymentVoucherArray [0]['firstRecord'];
            }
            if (isset($paymentVoucherArray [0]['nextRecord'])) {
                $nextRecord = $paymentVoucherArray [0]['nextRecord'];
            }
            if (isset($paymentVoucherArray [0]['previousRecord'])) {
                $previousRecord = $paymentVoucherArray [0]['previousRecord'];
            }
            if (isset($paymentVoucherArray [0]['lastRecord'])) {
                $lastRecord = $paymentVoucherArray [0]['lastRecord'];
                $endRecord = $paymentVoucherArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($paymentVoucher->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($paymentVoucherArray [0]['total'])) {
                $total = $paymentVoucherArray [0]['total'];
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
                    <button title="A" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $paymentVoucher->getViewPath();
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
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $paymentVoucher->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp; </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $paymentVoucher->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $paymentVoucher->getControllerPath();
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
                            
                                <label for="queryWidget"></label><div class="input-group"><input type="text" class="form-control" name="queryWidget"  id="queryWidget" value="<?php
                                    if (isset($_POST['query'])) {
                                        echo $_POST['query'];
                                    }
                                    ?>"><span class="input-group-addon"><img src="./images/icons/magnifier.png" id="searchTextDateImage"></span></div><br>
                                <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $paymentVoucher->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $paymentVoucher->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-days-span.png" alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $paymentVoucher->getViewPath();
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
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png" alt="<?php echo $t['dayTextLabel'] ?>">
                                        </td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $paymentVoucher->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Day <?php echo $nextDay; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Week<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'previous'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-week.png" alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" title="<?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'current'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                              echo $paymentVoucher->getViewPath();
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Week <?php
                                            echo $dateConvert->getCurrentWeekInfo(
                                                    $dateRangeStart, 'next'
                                            );
                                            ?>" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Month <?php echo $previousMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center">
                                            <img src="./images/icons/calendar-select-month.png" alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $paymentVoucher->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Month <?php echo $nextMonth; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">
                                            <a href="javascript:void(0)" rel="tooltip" title="Previous Year <?php echo $previousYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center">
                                            <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                            echo $paymentVoucher->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left">
                                            <a href="javascript:void(0)" rel="tooltip" title="Next Year <?php echo $nextYear; ?>"
                                               onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
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
                                       echo $paymentVoucher->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchDate" id="clearSearchDate"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $paymentVoucher->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                                </div>
                                </div>
                                </div>
                                <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">
                                    <div class="modal fade" id="postPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                                    <h3><?php echo ucwords($t['postRecordMessageLabel']); ?></h3>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal">
                                                        <input type="hidden" name="paymentVoucherIdPreview" id="paymentVoucherIdPreview">
                                                        <div class="form-group" id="bankIdDiv">
                                                            <label for="bankIdPreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['bankIdLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8"><input type="text" class="form-control"
                                                                                                           name="bankIdPreview" id="bankIdPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="businessPartnerCategoryIdDiv">
                                                            <label for="businessPartnerCategoryIdPreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerCategoryIdLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8"><input type="text" class="form-control"
                                                                                                           name="businessPartnerCategoryIdPreview"
                                                                                                           id="businessPartnerCategoryIdPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="businessPartnerIdDiv">
                                                            <label for="businessPartnerIdPreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerIdLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8"><input type="text" class="form-control"
                                                                                                           name="businessPartnerIdPreview"
                                                                                                           id="businessPartnerIdPreview">
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
                                                        <div class="form-group" id="paymentVoucherDescriptionDiv">
                                                            <label for="paymentVoucherDescriptionPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherDescriptionLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="paymentVoucherDescriptionPreview"
                                                                       id="paymentVoucherDescriptionPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="paymentVoucherDateDiv">
                                                            <label for="paymentVoucherDatePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherDateLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="paymentVoucherDatePreview"
                                                                       id="paymentVoucherDatePreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="paymentVoucherChequeDateDiv">
                                                            <label for="paymentVoucherChequeDatePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherChequeDateLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="paymentVoucherChequeDatePreview" id="paymentVoucherChequeDatePreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="paymentVoucherAmountDiv">
                                                            <label for="paymentVoucherAmountPreview" class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherAmountLabel']; ?></label>
                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="paymentVoucherAmountPreview"
                                                                       id="paymentVoucherAmountPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="paymentVoucherChequeNumberDiv">
                                                            <label for="paymentVoucherChequeNumberPreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherChequeNumberLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control"
                                                                       name="paymentVoucherChequeNumberPreview"
                                                                       id="paymentVoucherChequeNumberPreview">
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="paymentVoucherPayeeDiv">
                                                            <label for="paymentVoucherPayeePreview"
                                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherPayeeLabel']; ?></label>

                                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                                <input type="text" class="form-control" name="paymentVoucherPayeePreview"
                                                                       id="paymentVoucherPayeePreview">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"  class="btn btn-danger"
                                                            onClick="postGridRecord(<?php echo $leafId; ?>, '<?php
                                                            echo $paymentVoucher->getControllerPath();
                                                            ?>', '<?php
                                                            echo $paymentVoucher->getViewPath();
                                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['postButtonLabel']; ?></button>
                                                    <button type="button"  class="btn btn-default" onClick="showMeModal('postPreview', 0);"
                                                            data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?>
                                                    </button>
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
                                                    <th width="100px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                                    <th width="75px"><?php echo ucwords($leafTranslation['paymentVoucherDateLabel']); ?></th>
                                                    <th width="100px"><?php echo ucwords($leafTranslation['bankIdLabel']); ?></th>
                                                    <th width="100px"><?php echo ucwords($leafTranslation['businessPartnerIdLabel']); ?></th>
                                                    <th><?php echo ucwords($leafTranslation['paymentVoucherDescriptionLabel']); ?></th>
                                                    <th width="100px">
                                                    <div align="center"><?php echo ucwords($leafTranslation['paymentVoucherAmountLabel']); ?></div>
                                                    </th>
                                                    <th width="100px">
                                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                                    </th>
                                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                                    <th width="25px" align="center">
                                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                                               onChange="toggleChecked(this.checked);">
                                                    </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tableBody">
                                                        <?php
                                                        $totalPaymentVoucher = 0;
                                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                            if (is_array($paymentVoucherArray)) {
                                                                $totalRecord = intval(count($paymentVoucherArray));
                                                                if ($totalRecord > 0) {
                                                                    $counter = 0;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        $counter++;
                                                                        ?>
                                                                        <tr <?php
                                                                        if ($paymentVoucherArray[$i]['isDelete'] == 1) {
                                                                            echo "class=\"danger\"";
                                                                        } else {
                                                                            if ($paymentVoucherArray[$i]['isDraft'] == 1) {
                                                                                echo "class=\"warning\"";
                                                                            }
                                                                        }
                                                                        ?>>
                                                                            <td>
                                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                                            </td>
                                                                            <td align="center">
                                                                                <div class="btn-group" align="center">
                                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                            onClick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                            echo $paymentVoucher->getControllerPath();
                                                                                            ?>', '<?php
                                                                                            echo $paymentVoucher->getViewPath();
                                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                            echo intval(
                                                                                                    $paymentVoucherArray [$i]['paymentVoucherId']
                                                                                            );
                                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete" onClick="showModalPost('<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherId']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['bankDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['bankCategoryDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['businessPartnerCompany']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentTypeDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['documentNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['referenceNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherDate']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherDate']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherAmount']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherChequeNumber']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $paymentVoucherArray [$i]['paymentVoucherPayee']
                                                                                    );
                                                                                    ?>');">
                                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="pull-left">
                                                                                    <?php
                                                                                    if (isset($paymentVoucherArray[$i]['documentNumber'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query'])) {
                                                                                                if (@strpos(
                                                                                                                strtolower($paymentVoucherArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $paymentVoucherArray[$i]['documentNumber']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['documentNumber'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character'])) {
                                                                                                    if (@strpos(
                                                                                                                    strtolower($paymentVoucherArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $paymentVoucherArray[$i]['documentNumber']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $paymentVoucherArray[$i]['documentNumber'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['documentNumber'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $paymentVoucherArray[$i]['documentNumber'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php
                                                                            if (isset($paymentVoucherArray[$i]['paymentVoucherDate'])) {
                                                                                $valueArray = $paymentVoucherArray[$i]['paymentVoucherDate'];
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
                                                                                    if (isset($paymentVoucherArray[$i]['bankDescription'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query'])) {
                                                                                                if (@strpos(
                                                                                                                $paymentVoucherArray[$i]['bankDescription'], $_POST['query']
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $paymentVoucherArray[$i]['bankDescription']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['bankDescription'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character'])) {
                                                                                                    if (@strpos(
                                                                                                                    $paymentVoucherArray[$i]['bankDescription'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $paymentVoucherArray[$i]['bankDescription']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $paymentVoucherArray[$i]['bankDescription'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['bankDescription'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $paymentVoucherArray[$i]['bankDescription'];
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
                                                                                    if (isset($paymentVoucherArray[$i]['businessPartnerCompany'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query'])) {
                                                                                                if (@strpos(
                                                                                                                $paymentVoucherArray[$i]['businessPartnerCompany'], $_POST['query']
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $paymentVoucherArray[$i]['businessPartnerCompany']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['businessPartnerCompany'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character'])) {
                                                                                                    if (@strpos(
                                                                                                                    $paymentVoucherArray[$i]['businessPartnerCompany'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $paymentVoucherArray[$i]['businessPartnerCompany']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $paymentVoucherArray[$i]['businessPartnerCompany'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['businessPartnerCompany'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $paymentVoucherArray[$i]['businessPartnerCompany'];
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
                                                                                    if (isset($paymentVoucherArray[$i]['paymentVoucherDescription'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if (isset($_POST['query'])) {
                                                                                                if (@strpos(
                                                                                                                strtolower($paymentVoucherArray[$i]['paymentVoucherDescription']), strtolower($_POST['query'])
                                                                                                        ) !== false
                                                                                                ) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $paymentVoucherArray[$i]['paymentVoucherDescription']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['paymentVoucherDescription'];
                                                                                                }
                                                                                            } else {
                                                                                                if (isset($_POST['character'])) {
                                                                                                    if (@strpos(
                                                                                                                    strtolower($paymentVoucherArray[$i]['paymentVoucherDescription']), strtolower($_POST['character'])
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $paymentVoucherArray[$i]['paymentVoucherDescription']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $paymentVoucherArray[$i]['paymentVoucherDescription'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['paymentVoucherDescription'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $paymentVoucherArray[$i]['paymentVoucherDescription'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?>
                                                                            </td>


                                                                            <?php
                                                                            $d = $paymentVoucherArray[$i]['paymentVoucherAmount'];
                                                                            $totalPaymentVoucher += $d;
                                                                            if (class_exists('NumberFormatter')) {
                                                                                if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                                    $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                                    $d = $a->format($paymentVoucherArray[$i]['paymentVoucherAmount']);
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
                                                                                <div align="center">
                                                                                    <?php
                                                                                    if (isset($paymentVoucherArray[$i]['executeBy'])) {
                                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                            if ($_POST['query']) {
                                                                                                if (strpos($paymentVoucherArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                                    echo str_replace(
                                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $paymentVoucherArray[$i]['staffName']
                                                                                                    );
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['staffName'];
                                                                                                }
                                                                                            } else {
                                                                                                if ($_POST['character']) {
                                                                                                    if (strpos(
                                                                                                                    $paymentVoucherArray[$i]['staffName'], $_POST['character']
                                                                                                            ) !== false
                                                                                                    ) {
                                                                                                        echo str_replace(
                                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $paymentVoucherArray[$i]['staffName']
                                                                                                        );
                                                                                                    } else {
                                                                                                        echo $paymentVoucherArray[$i]['staffName'];
                                                                                                    }
                                                                                                } else {
                                                                                                    echo $paymentVoucherArray[$i]['staffName'];
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            echo $paymentVoucherArray[$i]['staffName'];
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    &nbsp;
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php
                                                                            if (isset($paymentVoucherArray[$i]['executeTime'])) {
                                                                                $valueArray = $paymentVoucherArray[$i]['executeTime'];
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
                                                                            <td>
                                                                                <input style="display:none;" type="checkbox" name="paymentVoucherId[]"
                                                                                       value="<?php echo $paymentVoucherArray[$i]['paymentVoucherId']; ?>">
                                                                                <input type="checkbox" name="isPost[]">
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="12" vAlign="top" align="center"><?php
                                                                            $paymentVoucher->exceptionMessage(
                                                                                    $t['recordNotFoundLabel']
                                                                            );
                                                                            ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="12" vAlign="top" align="center"><?php
                                                                        $paymentVoucher->exceptionMessage(
                                                                                $t['recordNotFoundLabel']
                                                                        );
                                                                        ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="12" vAlign="top" align="center"><?php
                                                                    $paymentVoucher->exceptionMessage(
                                                                            $t['loadFailureLabel']
                                                                    );
                                                                    ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="pull-right"><?php echo $t['totalTextLabel']; ?></div>
                                                        </td>
                                                        <td class="total">
                                                            <div class="pull-right"><strong><?php
                                                                    if (class_exists('NumberFormatter')) {
                                                                        $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                        $d = $a->format($totalPaymentVoucher);
                                                                    } else {
                                                                        $d = number_format($totalPaymentVoucher) . " You can assign Currency Format ";
                                                                    }
                                                                    echo $d;
                                                                    ?></strong>
                                                            </div>
                                                        </td>
                                                        <td colspan="3">&nbsp;</td>
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
                                                        onClick="postGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                                        echo $paymentVoucher->getControllerPath();
                                                        ?>', '<?php echo $paymentVoucher->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                                    <i class="glyphicon glyphicon-white glyphicon-wrench"></i>
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

                $paymentVoucherDetailArray = array();
                $paymentVoucherDetail = new \Core\Financial\Cashbook\PaymentVoucherDetail\Controller\PaymentVoucherDetailClass();
                $paymentVoucherDetail->setServiceOutput('html');
                $paymentVoucherDetail->setLeafId($leafId);
                $paymentVoucherDetail->execute();
                $chartOfAccountArray = $paymentVoucherDetail->getChartOfAccount();
                $paymentVoucherDetail->setStart(0);
                $paymentVoucherDetail->setLimit(999999); // normal system don't like paging..
                $paymentVoucherDetail->setPageOutput('html');
                if ($_POST['paymentVoucherId']) {
                    $paymentVoucherDetailArray = $paymentVoucherDetail->read();
                }
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
                                        <div class="btn-group">
                                            <a id="firstRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                               onClick="firstRecord(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getControllerPath();
                                               ?>', '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php
                                               echo $paymentVoucherDetail->getControllerPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                               onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getControllerPath();
                                               ?>', '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                               onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getControllerPath();
                                               ?>', '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                               onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getControllerPath();
                                               ?>', '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php
                                               echo $paymentVoucherDetail->getControllerPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <input type="hidden" name="paymentVoucherId" id="paymentVoucherId" value="<?php
                                        if (isset($_POST['paymentVoucherId'])) {
                                            echo $_POST['paymentVoucherId'];
                                        }
                                        ?>">

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-md-4 form-group" id="businessPartnerIdForm">
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

                                                                        if (isset($paymentVoucherArray[0]['businessPartnerId'])) {
                                                                            if ($paymentVoucherArray[0]['businessPartnerId'] == $businessPartnerArray[$i]['businessPartnerId']) {
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
                                                        </select><span class="help-block" id="businessPartnerIdHelpMe"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 form-group" id="paymentVoucherPayeeForm">
                                                    <label for="paymentVoucherPayee" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['paymentVoucherPayeeLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="paymentVoucherPayee"
                                                                   id="paymentVoucherPayee" value="<?php
                                                                   if (isset($paymentVoucherArray) && is_array($paymentVoucherArray)) {
                                                                       if (isset($paymentVoucherArray[0]['paymentVoucherPayee'])) {
                                                                           echo htmlentities($paymentVoucherArray[0]['paymentVoucherPayee']);
                                                                       }
                                                                   }
                                                                   ?>"><span class="input-group-addon"><img src="./images/icons/t-shirt.png"></span></div>
                                                        <span class="help-block" id="paymentVoucherPayeeHelpMe"></span>
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
                                                        <select name="bankId" id="bankId" class="chzn-select form-control">
                                                            <option value=""></option>
                                                            <?php
                                                            if (is_array($bankArray)) {
                                                                $totalRecord = intval(count($bankArray));
                                                                if ($totalRecord > 0) {
                                                                    $d = 1;
                                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                                        if (isset($paymentVoucherArray[0]['bankId'])) {
                                                                            if ($paymentVoucherArray[0]['bankId'] == $bankArray[$i]['bankId']) {
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
                                                <?php
                                                if (isset($paymentVoucherArray) && is_array($paymentVoucherArray)) {
                                                    if (isset($paymentVoucherArray[0]['paymentVoucherDate'])) {
                                                        $valueArray = $paymentVoucherArray[0]['paymentVoucherDate'];
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
                                                <div class="col-md-4 form-group" id="paymentVoucherDateForm">
                                                    <label for="paymentVoucherDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['paymentVoucherDateLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="paymentVoucherDate"
                                                                   id="paymentVoucherDate" value="<?php
                                                                   if (isset($value)) {
                                                                       echo $value;
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                 id="paymentVoucherDateImage"></span>
                                                        </div>
                                                        <span class="help-block" id="paymentVoucherDateHelpMe"></span>
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
                                                            <input type="text" class="form-control" name="documentNumber" id="documentNumber"
                                                            <?php
                                                            if (!isset($_POST['paymentVoucherId'])) {
                                                                echo "disabled";
                                                            }
                                                            ?>
                                                                   class="<?php
                                                                   if (!isset($_POST['paymentVoucherId'])) {
                                                                       echo "disabled";
                                                                   }
                                                                   ?>" value="<?php
                                                                   if (isset($paymentVoucherArray) && is_array($paymentVoucherArray)) {
                                                                       echo htmlentities($paymentVoucherArray[0]['documentNumber']);
                                                                   }
                                                                   ?>"><span class="input-group-addon"><img src="./images/icons/document-number.png"></span>
                                                        </div>
                                                        <span class="help-block" id="documentNumberHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="col-md-4 form-group" id="paymentVoucherAmountForm">
                                                    <label for="paymentVoucherAmount" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['paymentVoucherAmountLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="paymentVoucherAmount"
                                                                   id="paymentVoucherAmount" value="<?php
                                                                   if (isset($paymentVoucherArray) && is_array($paymentVoucherArray)) {
                                                                       if (isset($paymentVoucherArray[0]['paymentVoucherAmount'])) {
                                                                           echo htmlentities($paymentVoucherArray[0]['paymentVoucherAmount']);
                                                                       }
                                                                   }
                                                                   ?>"> <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                        <span class="help-block" id="paymentVoucherAmountHelpMe"></span>
                                                    </div>
                                                </div>
                                                <?php
                                                if (isset($paymentVoucherArray) && is_array($paymentVoucherArray)) {
                                                    if (isset($paymentVoucherArray[0]['paymentVoucherChequeDate'])) {
                                                        $valueArray = $paymentVoucherArray[0]['paymentVoucherChequeDate'];
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
                                                <div class="col-md-4 form-group" id="paymentVoucherChequeForm">
                                                    <label for="paymentVoucherChequeDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['paymentVoucherChequeDateLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="paymentVoucherChequeDate"
                                                                   id="paymentVoucherChequeDate" value="<?php
                                                                   if (isset($value)) {
                                                                       echo $value;
                                                                   }
                                                                   ?>">
                                                            <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                 id="paymentVoucherChequeDateImage"></span>
                                                        </div>
                                                        <span class="help-block" id="paymentVoucherChequeDateHelpMe"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 form-group" id="paymentVoucherChequeNumberForm">
                                                    <label for="paymentVoucherChequeNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                            echo ucfirst(
                                                                    $leafTranslation['paymentVoucherChequeNumberLabel']
                                                            );
                                                            ?></strong></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="paymentVoucherChequeNumber"
                                                                   id="paymentVoucherChequeNumber" value="<?php
                                                                   if (isset($paymentVoucherArray[0]['paymentVoucherChequeNumber'])) {
                                                                       echo htmlentities($paymentVoucherArray[0]['paymentVoucherChequeNumber']);
                                                                   }
                                                                   ?>"><span class="input-group-addon"><img src="./images/icons/cheque.png"></span></div>
                                                        <span class="help-block" id="paymentVoucherChequeNumberHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-group" id="paymentVoucherDescriptionForm">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <textarea class="form-control" name="paymentVoucherDescription"
                                                                  id="paymentVoucherDescription"><?php
                                                                      if (isset($paymentVoucherArray[0]['paymentVoucherDescription'])) {
                                                                          echo htmlentities($paymentVoucherArray[0]['paymentVoucherDescription']);
                                                                      }
                                                                      ?></textarea> <span class="help-block" id="paymentVoucherDescriptionHelpMe"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer" align="center">
                                        <div class="btn-group">
                                            <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                               onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getControllerPath();
                                               ?>', '<?php
                                               echo $paymentVoucher->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="postRecordbutton"  href="javascript:void(0)" class="btn btn-warning disabled"><i
                                                    class="glyphicon glyphicon-cog glyphicon-white"></i> <?php echo $t['postButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                               onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                               echo $paymentVoucher->getViewPath();
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
                        <div class="modal fade" id="postDetailPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                                        <h4 class="modal-title"><?php echo $t['postRecordMessageLabel']; ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="paymentVoucherDetailIdPreview" id="paymentVoucherDetailIdPreview">

                                        <div class="form-group" id="chartOfAccountIdDiv">
                                            <label for="chartOfAccountIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control" name="chartOfAccountIdPreview"
                                                       id="chartOfAccountIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="journalNumberDiv">
                                            <label for="journalNumberPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['journalNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control" name="journalNumberPreview"
                                                       id="journalNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="paymentVoucherDetailAmountDiv">
                                            <label for="paymentVoucherDetailAmountPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['paymentVoucherDetailAmountLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control"
                                                       name="paymentVoucherDetailAmountPreview" id="paymentVoucherDetailAmountPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button"  href="javascript:void(0)" onClick="showMeModal('postDetailPreview', 0);" class="btn btn-default"
                                                data-dismiss="modal"><?php echo $t['closeButtonLabel']; ?></button>
                                    </div>
                                </div>
                            </div></div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                    <thead>
                                        <tr>
                                            <th width="25px" align="center">
                                    <div align="center">#</div>
                                    </th>
                                    <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                    <th width="150px"><?php
                                        echo ucfirst(
                                                $leafTranslation['paymentVoucherDetailAmountLabel']
                                        );
                                        ?></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['paymentVoucherId']) {
                                            if (is_array($paymentVoucherDetailArray)) {
                                                $totalRecordDetail = intval(count($paymentVoucherDetailArray));
                                                if ($totalRecordDetail > 0) {
                                                    $counter = 0;
                                                    for ($j = 0; $j < $totalRecordDetail; $j++) {
                                                        $counter++;
                                                        ?>
                                                        <tr id="<?php echo $paymentVoucherDetailArray[$j]['paymentVoucherDetailId']; ?>">
                                                            <td>
                                                                <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group input-sm"
                                                                     id="chartOfAccountId<?php echo $paymentVoucherDetailArray[$j]['paymentVoucherDetailId']; ?>Detail">
                                                                    <select name="chartOfAccountId[]"
                                                                            id="chartOfAccountId<?php echo $paymentVoucherDetailArray[$j]['paymentVoucherDetailId']; ?>"
                                                                            class="chzn-select form-control"
                                                                            style="width:400px">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        if (is_array($chartOfAccountArray)) {
                                                                            $totalRecord = intval(count($chartOfAccountArray));
                                                                            if ($totalRecord > 0) {
                                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                                    if ($paymentVoucherDetailArray[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                                                        $selected = "selected";
                                                                                    } else {
                                                                                        $selected = null;
                                                                                    }
                                                                                    ?>
                                                                                    <option
                                                                                        value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?> - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
                                                                                        <?php
                                                                                    }
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
                                                                    </select></div>
                                                            </td>
                                                            <td>
                                                                <input class="form-control" style="text-align:right"
                                                                       type="text" name="paymentVoucherDetailAmount[]"
                                                                       id="paymentVoucherDetailAmount<?php echo $paymentVoucherDetailArray[$j]['paymentVoucherDetailId']; ?>"
                                                                       value="<?php
                                                                       if (isset($paymentVoucherDetailArray) && is_array(
                                                                                       $paymentVoucherDetailArray
                                                                               )
                                                                       ) {
                                                                           echo $paymentVoucherDetailArray[$j]['paymentVoucherDetailAmount'];
                                                                       }
                                                                       ?>"></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="6" vAlign="top" align="center"><?php
                                                            $paymentVoucherDetail->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" vAlign="top" align="center"><?php
                                                        $paymentVoucherDetail->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div></form>
                <script type="text/javascript">
                    $(document).keypress(function(e) {
                        switch (e.keyCode) {
                            case 37:
                                previousRecord(<?php echo $leafId; ?>, '<?php echo $paymentVoucher->getControllerPath(); ?>', '<?php echo $paymentVoucher->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                                return false;
                                break;
                            case 39:
                                nextRecord(<?php echo $leafId; ?>, '<?php echo $paymentVoucher->getControllerPath(); ?>', '<?php echo $paymentVoucher->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                                return false;
                                break;
                        }


                    });
                    $(document).ready(function() {
                        window.scrollTo(0, 0);
                        $(".chzn-select").chosen({search_contains: true});
                        $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                        $('#paymentVoucherDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });
                        $('#paymentVoucherChequeDate').datepicker({
                            format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                        }).on('changeDate', function() {
                            $(this).datepicker('hide');
                        });

                    });
                </script>

            <?php } ?>
            <script type="text/javascript" src="./v3/financial/cashbook/javascript/paymentVoucherPosting.js"></script>
            <hr>
            <footer><p>IDCMS 2012/2013</p></footer>