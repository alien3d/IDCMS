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
require_once($newFakeDocumentRoot . "v3/financial/businessPartner/controller/businessPartnerController.php");
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

$translator->setCurrentTable('businessPartner');

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
$businessPartnerArray = array();
$businessPartnerCategoryArray = array();
$businessPartnerOfficeCountryArray = array();
$businessPartnerOfficeStateArray = array();
$businessPartnerOfficeCityArray = array();
$businessPartnerShippingCountryArray = array();
$businessPartnerShippingStateArray = array();
$businessPartnerShippingCityArray = array();
$_POST['from'] = 'businessPartner.php';
$_GET['from'] = 'businessPartner.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $businessPartner = new \Core\Financial\BusinessPartner\BusinessPartner\Controller\BusinessPartnerClass();
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
            $businessPartner->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $businessPartner->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $businessPartner->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $businessPartner->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $businessPartner->setStartDay($start[2]);
            $businessPartner->setStartMonth($start[1]);
            $businessPartner->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $businessPartner->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $businessPartner->setEndDay($start[2]);
            $businessPartner->setEndMonth($start[1]);
            $businessPartner->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $businessPartner->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $businessPartner->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $businessPartner->setServiceOutput('html');
        $businessPartner->setLeafId($leafId);
        $businessPartner->execute();
        $businessPartnerCategoryArray = $businessPartner->getBusinessPartnerCategory();
        $businessPartnerOfficeCountryArray = $businessPartner->getBusinessPartnerOfficeCountry();
        $businessPartnerOfficeStateArray = $businessPartner->getBusinessPartnerOfficeState();
        $businessPartnerOfficeCityArray = $businessPartner->getBusinessPartnerOfficeCity();
        $businessPartnerShippingCountryArray = $businessPartner->getBusinessPartnerShippingCountry();
        $businessPartnerShippingStateArray = $businessPartner->getBusinessPartnerShippingState();
        $businessPartnerShippingCityArray = $businessPartner->getBusinessPartnerShippingCity();
        if ($_POST['method'] == 'read') {
            $businessPartner->setStart($offset);
            $businessPartner->setLimit($limit); // normal system don't like paging..
            $businessPartner->setPageOutput('html');
            $businessPartnerArray = $businessPartner->read();
            if (isset($businessPartnerArray [0]['firstRecord'])) {
                $firstRecord = $businessPartnerArray [0]['firstRecord'];
            }
            if (isset($businessPartnerArray [0]['nextRecord'])) {
                $nextRecord = $businessPartnerArray [0]['nextRecord'];
            }
            if (isset($businessPartnerArray [0]['previousRecord'])) {
                $previousRecord = $businessPartnerArray [0]['previousRecord'];
            }
            if (isset($businessPartnerArray [0]['lastRecord'])) {
                $lastRecord = $businessPartnerArray [0]['lastRecord'];
                $endRecord = $businessPartnerArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($businessPartner->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($businessPartnerArray [0]['total'])) {
                $total = $businessPartnerArray [0]['total'];
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
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $businessPartner->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');">
                        Z
                    </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-left" align="left">
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
                                    echo $businessPartner->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $businessPartner->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
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

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                        class="btn btn-info btn-block"
                                            onClick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $businessPartner->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');">
                                        <?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php if(isset($_POST['query'])) {  echo $_POST['query']; } ?>"><span class="input-group-addon">
<img id="searchTextImage" src="./images/icons/magnifier.png">
</span>
</div><br>
                                <input type="button"  name="searchString" id="searchString"
                                       value="<?php echo $t['searchButtonLabel']; ?>"
                                       class="btn btn-warning btn-block"
                                       onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                       echo $businessPartner->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $businessPartner->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"> <br>

                               


                             
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
                                        <input type="hidden" name="businessPartnerIdPreview" id="businessPartnerIdPreview">

                                        <div class="form-group" id="businessPartnerCategoryIdDiv">
                                            <label
                                                for="businessPartnerCategoryIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerCategoryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerCategoryIdPreview"
                                                       id="businessPartnerCategoryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerOfficeCountryIdDiv">
                                            <label
                                                for="businessPartnerOfficeCountryIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerOfficeCountryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerOfficeCountryIdPreview"
                                                       id="businessPartnerOfficeCountryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerOfficeStateIdDiv">
                                            <label
                                                for="businessPartnerOfficeStateIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerOfficeStateIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerOfficeStateIdPreview"
                                                       id="businessPartnerOfficeStateIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerOfficeCityIdDiv">
                                            <label
                                                for="businessPartnerOfficeCityIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerOfficeCityIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerOfficeCityIdPreview"
                                                       id="businessPartnerOfficeCityIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerShippingCountryIdDiv">
                                            <label
                                                for="businessPartnerShippingCountryIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerShippingCountryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerShippingCountryIdPreview"
                                                       id="businessPartnerShippingCountryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerShippingStateIdDiv">
                                            <label
                                                for="businessPartnerShippingStateIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerShippingStateIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerShippingStateIdPreview"
                                                       id="businessPartnerShippingStateIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerShippingCityIdDiv">
                                            <label
                                                for="businessPartnerShippingCityIdPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerShippingCityIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerShippingCityIdPreview"
                                                       id="businessPartnerShippingCityIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerCodeDiv">
                                            <label
                                                for="businessPartnerCodePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerCodePreview"
                                                       id="businessPartnerCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerRegistrationNumberDiv">
                                            <label
                                                for="businessPartnerRegistrationNumberPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerRegistrationNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerRegistrationNumberPreview"
                                                       id="businessPartnerRegistrationNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerTaxNumberDiv">
                                            <label
                                                for="businessPartnerTaxNumberPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerTaxNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerTaxNumberPreview"
                                                       id="businessPartnerTaxNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerCompanyDiv">
                                            <label
                                                for="businessPartnerCompanyPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerCompanyLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerCompanyPreview"
                                                       id="businessPartnerCompanyPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerPictureDiv">
                                            <label
                                                for="businessPartnerPicturePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerPictureLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerPicturePreview"
                                                       id="businessPartnerPicturePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerBusinessPhoneDiv">
                                            <label
                                                for="businessPartnerBusinessPhonePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerBusinessPhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerBusinessPhonePreview"
                                                       id="businessPartnerBusinessPhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerMobilePhoneDiv">
                                            <label
                                                for="businessPartnerMobilePhonePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerMobilePhoneLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerMobilePhonePreview"
                                                       id="businessPartnerMobilePhonePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerFaxNumDiv">
                                            <label
                                                for="businessPartnerFaxNumPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerFaxNumLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerFaxNumPreview"
                                                       id="businessPartnerFaxNumPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerOfficeAddressDiv">
                                            <label
                                                for="businessPartnerOfficeAddressPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerOfficeAddressLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerOfficeAddressPreview"
                                                       id="businessPartnerOfficeAddressPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerShippingAddressDiv">
                                            <label
                                                for="businessPartnerShippingAddressPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerShippingAddressLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerShippingAddressPreview"
                                                       id="businessPartnerShippingAddressPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerOfficePostCodeDiv">
                                            <label
                                                for="businessPartnerOfficePostCodePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerOfficePostCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerOfficePostCodePreview"
                                                       id="businessPartnerOfficePostCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerShippingPostCodeDiv">
                                            <label
                                                for="businessPartnerShippingPostCodePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerShippingPostCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="businessPartnerShippingPostCodePreview"
                                                       id="businessPartnerShippingPostCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerEmailDiv">
                                            <label
                                                for="businessPartnerEmailPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerEmailLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerEmailPreview"
                                                       id="businessPartnerEmailPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerWebPageDiv">
                                            <label
                                                for="businessPartnerWebPagePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerWebPageLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerWebPagePreview"
                                                       id="businessPartnerWebPagePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerFacebookDiv">
                                            <label
                                                for="businessPartnerFacebookPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerFacebookLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerFacebookPreview"
                                                       id="businessPartnerFacebookPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerTwitterDiv">
                                            <label
                                                for="businessPartnerTwitterPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerTwitterLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerTwitterPreview"
                                                       id="businessPartnerTwitterPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerNotesDiv">
                                            <label
                                                for="businessPartnerNotesPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerNotesLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerNotesPreview"
                                                       id="businessPartnerNotesPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerDateDiv">
                                            <label
                                                for="businessPartnerDatePreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerDatePreview"
                                                       id="businessPartnerDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerChequePrintingDiv">
                                            <label
                                                for="businessPartnerChequePrintingPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerChequePrintingLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerChequePrintingPreview"
                                                       id="businessPartnerChequePrintingPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerCreditTermDiv">
                                            <label
                                                for="businessPartnerCreditTermPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerCreditTermLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerCreditTermPreview"
                                                       id="businessPartnerCreditTermPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="businessPartnerCreditLimitDiv">
                                            <label
                                                for="businessPartnerCreditLimitPreview"
                                                class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['businessPartnerCreditLimitLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="businessPartnerCreditLimitPreview"
                                                       id="businessPartnerCreditLimitPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onClick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $businessPartner->getControllerPath();
                                            ?>', '<?php
                                            echo $businessPartner->getViewPath();
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  onClick="showMeModal('deletePreview', 0);" class="btn btn-default"
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
                                    <th width="150px">
                                    <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th><?php echo ucwords($leafTranslation['businessPartnerCodeLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['businessPartnerRegistrationNumberLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['businessPartnerCompanyLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['businessPartnerBusinessPhoneLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['businessPartnerFaxNumberLabel']); ?></th>
                                    <th><?php echo ucwords($leafTranslation['businessPartnerEmailLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record" onChange="toggleChecked(this.checked);">
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($businessPartnerArray)) {
                                                $totalRecord = intval(count($businessPartnerArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($businessPartnerArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($businessPartnerArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                            </td>
                                                            <td>
																<?php if($businessPartnerArray [$i]['businessPartnerCode'] !='UNBL') { ?>
                                                                <div class="btn-group">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onClick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $businessPartner->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $businessPartner->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $businessPartnerArray [$i]['businessPartnerId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                    <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                            onClick="showModalDelete('<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerId']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerCategoryDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerOfficeCountryDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerOfficeStateDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerOfficeCityDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerShippingCountryDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerShippingStateDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerShippingCityDescription']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerCode']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerRegistrationNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerTaxNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerCompany']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerPicture']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerBusinessPhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerMobilePhone']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerFaxNumber']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerOfficeAddress']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerShippingAddress']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerOfficePostCode']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerShippingPostCode']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerEmail']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerWebPage']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerFacebook']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerTwitter']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerNotes']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerDate']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerChequePrinting']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerCreditTerm']
                                                                            );
                                                                            ?>', '<?php
                                                                            echo rawurlencode(
                                                                                    $businessPartnerArray [$i]['businessPartnerCreditLimit']
                                                                            );
                                                                            ?>');">
                                                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                </div>
																<?php } ?>
                                                            </td>
                                                            <td>
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($businessPartnerArray[$i]['businessPartnerCode'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($businessPartnerArray[$i]['businessPartnerCode']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            strtolower($_POST['query']), "<span class=\"label label-info\">" . $_POST['query'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerCode'])
                                                                                    );
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerCode'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($businessPartnerArray[$i]['businessPartnerCode']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                strtolower($_POST['character']), "<span class=\"label label-info\">" . $_POST['character'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerCode'])
                                                                                        );
                                                                                    } else {
                                                                                        echo $businessPartnerArray[$i]['businessPartnerCode'];
                                                                                    }
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerCode'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $businessPartnerArray[$i]['businessPartnerCode'];
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
                                                                    if (isset($businessPartnerArray[$i]['businessPartnerRegistrationNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $businessPartnerArray[$i]['businessPartnerRegistrationNumber']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            strtolower($_POST['query']), "<span class=\"label label-info\">" . $_POST['query'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerRegistrationNumber'])
                                                                                    );
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerRegistrationNumber'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $businessPartnerArray[$i]['businessPartnerRegistrationNumber']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                strtolower($_POST['character']), "<span class=\"label label-info\">" . $_POST['character'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerRegistrationNumber'])
                                                                                        );
                                                                                    } else {
                                                                                        echo $businessPartnerArray[$i]['businessPartnerRegistrationNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerRegistrationNumber'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $businessPartnerArray[$i]['businessPartnerRegistrationNumber'];
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
                                                                    if (isset($businessPartnerArray[$i]['businessPartnerCompany'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($businessPartnerArray[$i]['businessPartnerCompany']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            strtolower($_POST['query']), "<span class=\"label label-info\">" . $_POST['query'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerCompany'])
                                                                                    );
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($businessPartnerArray[$i]['businessPartnerCompany']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                strtolower($_POST['character']), "<span class=\"label label-info\">" . $_POST['character'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerCompany'])
                                                                                        );
                                                                                    } else {
                                                                                        echo $businessPartnerArray[$i]['businessPartnerCompany'];
                                                                                    }
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerCompany'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $businessPartnerArray[$i]['businessPartnerCompany'];
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
                                                                    if (isset($businessPartnerArray[$i]['businessPartnerBusinessPhone'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($businessPartnerArray[$i]['businessPartnerBusinessPhone']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $businessPartnerArray[$i]['businessPartnerBusinessPhone']
                                                                                    );
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerBusinessPhone'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $businessPartnerArray[$i]['businessPartnerBusinessPhone']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $businessPartnerArray[$i]['businessPartnerBusinessPhone']
                                                                                        );
                                                                                    } else {
                                                                                        echo $businessPartnerArray[$i]['businessPartnerBusinessPhone'];
                                                                                    }
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerBusinessPhone'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $businessPartnerArray[$i]['businessPartnerBusinessPhone'];
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
                                                                    if (isset($businessPartnerArray[$i]['businessPartnerFaxNumber'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($businessPartnerArray[$i]['businessPartnerFaxNumber']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $businessPartnerArray[$i]['businessPartnerFaxNumber']
                                                                                    );
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerFaxNumber'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($businessPartnerArray[$i]['businessPartnerFaxNumber']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $businessPartnerArray[$i]['businessPartnerFaxNumber']
                                                                                        );
                                                                                    } else {
                                                                                        echo $businessPartnerArray[$i]['businessPartnerFaxNumber'];
                                                                                    }
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerFaxNumber'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $businessPartnerArray[$i]['businessPartnerFaxNumber'];
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
                                                                    if (isset($businessPartnerArray[$i]['businessPartnerEmail'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($businessPartnerArray[$i]['businessPartnerEmail']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            strtolower($_POST['query']), "<span class=\"label label-info\">" . $_POST['query'] . "</span>", strtolower($businessPartnerArray[$i]['businessPartnerEmail'])
                                                                                    );
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerEmail'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($businessPartnerArray[$i]['businessPartnerEmail']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $businessPartnerArray[$i]['businessPartnerEmail']
                                                                                        );
                                                                                    } else {
                                                                                        echo $businessPartnerArray[$i]['businessPartnerEmail'];
                                                                                    }
                                                                                } else {
                                                                                    echo $businessPartnerArray[$i]['businessPartnerEmail'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $businessPartnerArray[$i]['businessPartnerEmail'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                              <?php
                                                            if ($businessPartnerArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
																<?php if($businessPartnerArray [$i]['businessPartnerCode'] !='UNBL') { ?>
                                                                <label>
                                                                    <input style="display:none;" type="checkbox" name="businessPartnerId[]"
                                                                           value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>">
                                                                </label> <label>
                                                                    <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                   value="<?php echo $businessPartnerArray[$i]['isDelete']; ?>">
                                                                </label>
																<?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="11" vAlign="top" align="center"><?php
                                                            $businessPartner->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="11" vAlign="top" align="center"><?php
                                                        $businessPartner->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="11" vAlign="top" align="center"><?php
                                                    $businessPartner->exceptionMessage(
                                                            $t['loadFailureLabel']
                                                    );
                                                    ?></td>
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
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left"><?php $navigation->pagenationv4($offset); ?></div>
                        <div class="col-xs-3 col-sm-3 col-md-3 pagination">
                            <div class="pull-right">
                                <button class="delete btn btn-warning" type="button" 
                                        onClick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                        echo $businessPartner->getControllerPath();
                                        ?>', '<?php echo $businessPartner->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
                                       onClick="firstRecord(<?php echo $leafId; ?>, '<?php
                                       echo $businessPartner->getControllerPath();
                                       ?>', '<?php
                                       echo $businessPartner->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $businessPartner->getControllerPath();
                                       ?>', '<?php
                                       echo $businessPartner->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $businessPartner->getControllerPath();
                                       ?>', '<?php
                                       echo $businessPartner->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $businessPartner->getControllerPath();
                                       ?>', '<?php
                                       echo $businessPartner->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="businessPartnerId" id="businessPartnerId" value="<?php
                            if (isset($_POST['businessPartnerId'])) {
                                echo $_POST['businessPartnerId'];
                            }
                            ?>">
                            <fieldset>
                                <legend><?php echo $t['informationTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeAll(0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeAll(1);">

                                </legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="row">
                                            <div class="col-xs-9 col-sm-9 col-md-9">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <!--name-->
                                                            <div class="form-group" id="businessPartnerCompanyForm">
                                                                <label for="businessPartnerCompany" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                        echo ucfirst(
                                                                                $leafTranslation['businessPartnerCompanyLabel']
                                                                        );
                                                                        ?></strong></label>

                                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                                    <input type="text" class="form-control" name="businessPartnerCompany"
                                                                           id="businessPartnerCompany" value="<?php
                                                                           if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                               echo htmlentities($businessPartnerArray[0]['businessPartnerCompany']);
                                                                           }
                                                                           ?>" onKeyUp="removeMeError('businessPartnerCompany',9999);"> <span class="help-block"
                                                                           id="businessPartnerCompanyHelpMe"></span>
                                                                </div>
                                                            </div>
                                                            <!--end company-->
                                                        </div>
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <!--start business partner code-->
                                                            <div class="form-group" id="businessPartnerCodeForm">
                                                                <label for="businessPartnerCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                        echo ucfirst(
                                                                                $leafTranslation['businessPartnerCodeLabel']
                                                                        );
                                                                        ?></strong></label>

                                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                                    <input type="text" class="form-control" name="businessPartnerCode"
                                                                           id="businessPartnerCode" value="<?php
                                                                           if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                               echo htmlentities($businessPartnerArray[0]['businessPartnerCompany']);
                                                                           }
                                                                           ?>"> <span class="help-block" id="businessPartnerCodeHelpMe"></span>
                                                                </div>
                                                            </div>
                                                            <!-- end business partner code-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <!--start registration number-->
                                                            <div class="form-group" id="businessPartnerRegistrationNumberForm">
                                                                <label for="businessPartnerRegistrationNumber"
                                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                               echo ucfirst(
                                                                                       $leafTranslation['businessPartnerRegistrationNumberLabel']
                                                                               );
                                                                               ?></strong></label>

                                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                                    <input type="text" class="form-control"
                                                                           name="businessPartnerRegistrationNumber"
                                                                           id="businessPartnerRegistrationNumber" value="<?php
                                                                           if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                               echo htmlentities(
                                                                                       $businessPartnerArray[0]['businessPartnerRegistrationNumber']
                                                                               );
                                                                           }
                                                                           ?>" onKeyUp="removeMeError('businessPartnerRegistrationNumber',9999);"> <span class="help-block"
                                                                           id="businessPartnerRegistrationNumberHelpMe"></span>
                                                                </div>
                                                            </div>
                                                            <!--end registration number-->
                                                        </div>
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <!--start tax number-->
                                                            <div class="form-group" id="businessPartnerTaxNumberForm">
                                                                <label for="businessPartnerTaxNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                        echo ucfirst(
                                                                                $leafTranslation['businessPartnerTaxNumberLabel']
                                                                        );
                                                                        ?></strong></label>

                                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                                    <input type="text" class="form-control" name="businessPartnerTaxNumber"
                                                                           id="businessPartnerTaxNumber" value="<?php
                                                                           if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                               echo htmlentities($businessPartnerArray[0]['businessPartnerTaxNumber']);
                                                                           }
                                                                           ?>"> <span class="help-block" id="businessPartnerTaxNumberHelpMe"></span>
                                                                </div>
                                                            </div>
                                                            <!--end tax number-->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <div class="col-xs-6 col-sm-6">
                                                            <!-- start business partner category -->
                                                            <div class="form-group" id="businessPartnerCategoryIdForm">
                                                                <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                                       for="businessPartnerCategoryId"><strong><?php
                                                                               echo ucfirst(
                                                                                       $leafTranslation['businessPartnerCategoryIdLabel']
                                                                               );
                                                                               ?></strong></label>

                                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                                    <select name="businessPartnerCategoryId" id="businessPartnerCategoryId"
                                                                            class="form-control  chzn-select">
                                                                        <option value=""></option>
                                                                        <?php
                                                                        if (is_array($businessPartnerCategoryArray)) {
                                                                            $totalRecord = intval(count($businessPartnerCategoryArray));
                                                                            if ($totalRecord > 0) {
                                                                                $d = 1;
                                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                                    if (isset($businessPartnerArray[0]['businessPartnerCategoryId'])) {
                                                                                        if ($businessPartnerArray[0]['businessPartnerCategoryId'] == $businessPartnerCategoryArray[$i]['businessPartnerCategoryId']) {
                                                                                            $selected = "selected";
                                                                                        } else {
                                                                                            $selected = null;
                                                                                        }
                                                                                    } else {
                                                                                        $selected = null;
                                                                                    }
                                                                                    ?>
                                                                                    <option
                                                                                        value="<?php echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                                        . <?php echo $businessPartnerCategoryArray[$i]['businessPartnerCategoryDescription']; ?></option>
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
                                                                    <span class="help-block" id="businessPartnerCategoryIdHelpMe"></span>
                                                                </div>
                                                            </div>
                                                            <!-- end business partner category -->
                                                        </div>
                                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <!-- start business registration  date -->
                                                            <?php
                                                            if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {

                                                                $valueArray = $businessPartnerArray[0]['businessPartnerDate'];
                                                                if ($dateConvert->checkDateTime($valueArray)) {
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
                                                            }
                                                            ?>
                                                            <div class="form-group" id="businessPartnerDateForm">
                                                                <label for="businessPartnerDate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                                        echo ucfirst(
                                                                                $leafTranslation['businessPartnerDateLabel']
                                                                        );
                                                                        ?></strong></label>

                                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="businessPartnerDate"
                                                                               id="businessPartnerDate" value="<?php
                                                                               if (isset($value)) {
                                                                                   echo $value;
                                                                               }
                                                                               ?>"><span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                                               id="businessPartnerDateImage"></span>
                                                                    </div>
                                                                    <span class="help-block" id="businessPartnerDateHelpMe"></span>
                                                                </div>
                                                            </div>
                                                            <!-- end registration business date -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-3 col-sm-3 col-md-3">
                                                <!-- picture -->
                                                <div class="form form-group" align="center">
                                                    <label for="businessPartnerPicture" class="control-label col-xs-4 col-sm-4 col-md-4"><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerPictureLabel']
                                                        );
                                                        ?></label>

                                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                                        <input type="hidden" name="businessPartnerPicture" id="businessPartnerPicture"
                                                               value="<?php echo $businessPartnerArray[0]['businessPartnerPicture']; ?>">

                                                        <div id="businessPartnerPicturePreviewUpload" align="center">
                                                            <ul class="img-thumbnails">
                                                                <li>
                                                                    <div class="img-thumbnail" align="center">
                                                                        <?php
                                                                        if (empty($businessPartnerArray[0]['businessPartnerPicture'])) {
                                                                            $businessPartnerArray[0]['businessPartnerPicture'] = 'Kathleen_Byrne.jpg';
                                                                        }
                                                                        if ($businessPartnerArray[0]['businessPartnerPicture']) {
                                                                            if (strlen($businessPartnerArray[0]['businessPartnerPicture']) > 0) {
                                                                                ?>
                                                                                <img id="imagePreview"
                                                                                     src="./v3/financial/businessPartner/images/<?php echo $businessPartnerArray[0]['businessPartnerPicture']; ?>"
                                                                                     width="80" height="80">
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
                                                        <div id="businessPartnerPictureDiv" class="pull-left" style="text-align:center" align="center">
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
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="addressLegend"><?php echo $t['addressTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('address', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('address', 1);">
                                </legend>
                                <div id="address">
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start office address -->
                                            <div class="form-group" id="businessPartnerOfficeAddressForm">
                                                <label for="businessPartnerOfficeAddress" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerOfficeAddressLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <textarea name="businessPartnerOfficeAddress" id="businessPartnerOfficeAddress"
                                                              class="form-control"><?php
                                                                  if (isset($businessPartnerArray[0]['businessPartnerOfficeAddress'])) {
                                                                      echo htmlentities($businessPartnerArray[0]['businessPartnerOfficeAddress']);
                                                                  }
                                                                  ?></textarea> <span class="help-block" id="businessPartnerOfficeAddressHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end office address -->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start shipping address -->
                                            <div class="form-group" id="businessPartnerShippingAddressForm">
                                                <label for="businessPartnerShippingAddress" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerShippingAddressLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <textarea name="businessPartnerShippingAddress" id="businessPartnerShippingAddress"
                                                              class="form-control"><?php
                                                                  if (isset($businessPartnerArray[0]['businessPartnerShippingAddress'])) {
                                                                      echo htmlentities($businessPartnerArray[0]['businessPartnerShippingAddress']);
                                                                  }
                                                                  ?></textarea><span class="help-block" id="businessPartnerShippingAddressHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end shipping address -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!--start office country -->
                                            <div class="form-group" id="businessPartnerOfficeCountryIdForm">
                                                <label for="businessPartnerOfficeCountryId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerShippingCountryIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="businessPartnerOfficeCountryId" id="businessPartnerOfficeCountryId"
                                                            class="chzn-select form-control"
                                                            onChange="getBusinessPartnerOfficeState(<?php echo $leafId; ?>, '<?php
                                                            echo $businessPartner->getControllerPath();
                                                            ?>', '<?php echo $securityToken; ?>');
                                                                    getBusinessPartnerOfficeCity(<?php echo $leafId; ?>, '<?php
                                                            echo $businessPartner->getControllerPath();
                                                            ?>', '<?php echo $securityToken; ?>');">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($businessPartnerOfficeCountryArray)) {
                                                            $totalRecord = intval(count($businessPartnerOfficeCountryArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($businessPartnerArray[0]['businessPartnerOfficeCountryId'] == $businessPartnerOfficeCountryArray[$i]['countryId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $businessPartnerOfficeCountryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                        . <?php echo $businessPartnerOfficeCountryArray[$i]['countryDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="businessPartnerOfficeCountryIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end office country-->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start shipping country -->
                                            <div class="form-group" id="businessPartnerShippingCountryIdForm">
                                                <label for="businessPartnerShippingCountryId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerShippingCountryIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="businessPartnerShippingCountryId" id="businessPartnerShippingCountryId"
                                                            class="chzn-select form-control"
                                                            onChange="getBusinessPartnerShippingState(<?php echo $leafId; ?>, '<?php
                                                            echo $businessPartner->getControllerPath();
                                                            ?>', '<?php echo $securityToken; ?>');
                                                                    getBusinessPartnerShippingCity(<?php echo $leafId; ?>, '<?php
                                                            echo $businessPartner->getControllerPath();
                                                            ?>', '<?php echo $securityToken; ?>');">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($businessPartnerShippingCountryArray)) {
                                                            $totalRecord = intval(count($businessPartnerShippingCountryArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($businessPartnerArray[0]['businessPartnerShippingCountryId'] == $businessPartnerShippingCountryArray[$i]['countryId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $businessPartnerShippingCountryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                        . <?php echo $businessPartnerShippingCountryArray[$i]['countryDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="businessPartnerShippingCountryIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end shipping country -->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start office state -->
                                            <div class="form-group" id="businessPartnerOfficeStateIdForm">
                                                <label for="businessPartnerOfficeStateId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerOfficeStateIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="businessPartnerOfficeStateId" id="businessPartnerOfficeStateId"
                                                            class="chzn-select form-control"
                                                            onChange="getBusinessPartnerOfficeCity(<?php echo $leafId; ?>, '<?php
                                                            echo $businessPartner->getControllerPath();
                                                            ?>', '<?php echo $securityToken; ?>');">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($businessPartnerOfficeStateArray)) {
                                                            $totalRecord = intval(count($businessPartnerOfficeStateArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($businessPartnerArray[0]['businessPartnerOfficeStateId'] == $businessPartnerOfficeStateArray[$i]['stateId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $businessPartnerOfficeStateArray[$i]['stateId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                        . <?php echo $businessPartnerOfficeStateArray[$i]['stateDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="businessPartnerOfficeStateIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end office state-->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start shipping state-->
                                            <div class="form-group" id="businessPartnerShippingStateIdForm">
                                                <label for="businessPartnerShippingStateId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerShippingStateIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="businessPartnerShippingStateId" id="businessPartnerShippingStateId"
                                                            class="chzn-select form-control"
                                                            onChange="getBusinessPartnerShippingCity(<?php echo $leafId; ?>, '<?php
                                                            echo $businessPartner->getControllerPath();
                                                            ?>', '<?php echo $securityToken; ?>');">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($businessPartnerOfficeStateArray)) {
                                                            $totalRecord = intval(count($businessPartnerOfficeStateArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    if ($businessPartnerArray[0]['businessPartnerShippingStateId'] == $businessPartnerShippingStateArray[$i]['stateId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $businessPartnerShippingStateArray[$i]['stateId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                        . <?php echo $businessPartnerShippingStateArray[$i]['stateDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="businessPartnerShppingStateIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end shipping state-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start office city -->
                                            <div class="form-group" id="businessPartnerOfficeCityIdForm">
                                                <label for="businessPartnerOfficeCityId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerOfficeCityIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="businessPartnerOfficeCityId" id="businessPartnerOfficeCityId"
                                                            class="chzn-select form-control">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($businessPartnerOfficeCityArray)) {
                                                            $totalRecord = intval(count($businessPartnerOfficeCityArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                $n = 0;
                                                                $currentStateDescription = null;
                                                                $group = 0;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    $d++;
                                                                    $n++;
                                                                    if ($i != 0) {
                                                                        if ($currentStateDescription != $businessPartnerOfficeCityArray[$i]['stateDescription']) {
                                                                            $group = 1;
                                                                            echo "</optgroup><optgroup label=\"" . $businessPartnerOfficeCityArray[$i]['stateDescription'] . "\">";
                                                                        }
                                                                    } else {
                                                                        echo "<optgroup label=\"" . $businessPartnerOfficeCityArray[$i]['stateDescription'] . "\">";
                                                                    }
                                                                    $currentStateDescription = $businessPartnerOfficeCityArray[$i]['stateDescription'];
                                                                    if ($businessPartnerArray[0]['businessPartnerOfficeCityId'] == $businessPartnerOfficeCityArray[$i]['cityId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $businessPartnerOfficeCityArray[$i]['cityId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                        . <?php echo $businessPartnerOfficeCityArray[$i]['cityDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="businessPartnerOfficeCityIdHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end office city -->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start shipping city -->
                                            <div class="form-group" id="businessPartnerShippingCityIdForm">
                                                <label for="businessPartnerShippingCityId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerShippingCityIdLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <select name="businessPartnerShippingCityId" id="businessPartnerShippingCityId"
                                                            class="chzn-select form-control">
                                                        <option value=""></option>
                                                        <?php
                                                        if (is_array($businessPartnerShippingCityArray)) {
                                                            $totalRecord = intval(count($businessPartnerShippingCityArray));
                                                            if ($totalRecord > 0) {
                                                                $d = 1;
                                                                $n = 0;
                                                                $currentStateDescription = null;
                                                                $group = 0;
                                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                                    $d++;
                                                                    $n++;
                                                                    if ($i != 0) {
                                                                        if ($currentStateDescription != $businessPartnerShippingCityArray[$i]['stateDescription']) {
                                                                            $group = 1;
                                                                            echo "</optgroup><optgroup label=\"" . $businessPartnerShippingCityArray[$i]['stateDescription'] . "\">";
                                                                        }
                                                                    } else {
                                                                        echo "<optgroup label=\"" . $businessPartnerShippingCityArray[$i]['stateDescription'] . "\">";
                                                                    }
                                                                    $currentStateDescription = $businessPartnerShippingCityArray[$i]['stateDescription'];
                                                                    if ($businessPartnerArray[0]['businessPartnerShippingCityId'] == $businessPartnerShippingCityArray[$i]['cityId']) {
                                                                        $selected = "selected";
                                                                    } else {
                                                                        $selected = null;
                                                                    }
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $businessPartnerShippingCityArray[$i]['cityId']; ?>" <?php echo $selected; ?>><?php echo $selected; ?>
                                                                        - <?php echo $d; ?>
                                                                        . <?php echo $businessPartnerShippingCityArray[$i]['cityDescription']; ?></option>
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
                                                    </select> <span class="help-block" id="businessPartnerOfficeCityIdHelpMe"></span>
                                                </div>
                                                <!-- end shipping city -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ccol-xs-6 col-sm-6 col-md-6">
                                            <!-- start office postcode-->
                                            <div class="form-group" id="businessPartnerOfficePostCodeForm">
                                                <label for="businessPartnerOfficePostCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerOfficePostCodeLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerOfficePostCode"
                                                               id="businessPartnerOfficePostCode"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerOfficePostCode']);
                                                               }
                                                               ?>" maxlength="16"> <span class="input-group-addon"><img
                                                                src="./images/icons/postage-stamp.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerOfficePostCodeHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end office postcode-->
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- start shipping postcode-->
                                            <div class="form-group" id="businessPartnerShippingPostCodeForm">
                                                <label for="businessPartnerShippingPostCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerShippingPostCodeLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerShippingPostCode"
                                                               id="businessPartnerShippingPostCode"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerShippingPostCode']);
                                                               }
                                                               ?>" maxlength="16"> <span class="input-group-addon"><img
                                                                src="./images/icons/postage-stamp.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerShippingPostCodeHelpMe"></span>
                                                </div>
                                            </div>
                                            <!-- end shipping postcode-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <label for="sameAddress"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $t['sameAddressShippingLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="checkbox" name="sameAddress" id="sameAddress" onClick="copyAddress();">
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
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerBusinessPhoneForm">
                                                <label for="businessPartnerBusinessPhone"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                               echo ucfirst(
                                                                       $leafTranslation['businessPartnerBusinessPhoneLabel']
                                                               );
                                                               ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerBusinessPhone"
                                                               id="businessPartnerBusinessPhone"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerBusinessPhone']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerBusinessPhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerHomePhoneForm">
                                                <label for="businessPartnerHomePhone" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerHomePhoneLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerHomePhone"
                                                               id="businessPartnerHomePhone"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerHomePhone']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/telephone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerHomePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerMobilePhoneForm">
                                                <label for="businessPartnerMobilePhone"
                                                       class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                               echo ucfirst(
                                                                       $leafTranslation['businessPartnerMobilePhoneLabel']
                                                               );
                                                               ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerMobilePhone"
                                                               id="businessPartnerMobilePhone"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerMobilePhone']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/mobile-phone.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerMobilePhoneHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerFaxNumberForm">
                                                <label for="businessPartnerFaxNumber" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerFaxNumberLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerFaxNumber"
                                                               id="businessPartnerFaxNumber"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerFaxNumber']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/telephone-fax.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerFaxNumberHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend id="webLegend"><?php echo $t['webTextLabel']; ?>&nbsp;
                                    <img src="./images/icons/layer--minus.png" class="img-rounded" onClick="showMeDiv('web', 0);">&nbsp;<img
                                        src="./images/icons/layers-stack.png" class="img-rounded" onClick="showMeDiv('web', 1);">
                                </legend>
                                <div id="web">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerEmailForm">
                                                <label for="businessPartnerEmail" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerEmailLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerEmail"
                                                               id="businessPartnerEmail"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerEmail']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/email.png"></span></div>
                                                    <span class="help-block" id="businessPartnerEmailHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerFacebookForm">
                                                <label for="businessPartnerFacebook" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerFacebookLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerFacebook"
                                                               id="businessPartnerFacebook"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerFacebook']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/facebook.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerFacebookHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerTwitterForm">
                                                <label for="businessPartnerTwitter" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerTwitterLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerTwitter"
                                                               id="businessPartnerTwitter"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerTwitter']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/twitter.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerTwitterHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="businessPartnerWebPageForm">
                                                <label for="businessPartnerWebPage" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['businessPartnerWebPageLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="businessPartnerWebPage"
                                                               id="businessPartnerWebPage"
                                                               value="<?php
                                                               if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                                   echo htmlentities($businessPartnerArray[0]['businessPartnerWebPage']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img
                                                                src="./images/icons/website.png"></span>
                                                    </div>
                                                    <span class="help-block" id="businessPartnerWebPageHelpMe"></span>
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
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-xs-12 col-sm-12 col-md-12 form-group" id="businessPartnerNotesForm">

                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <textarea class="form-control" name="businessPartnerNotes"
                                                       id="businessPartnerNotes"  placeholder="<?php
                                                    echo ucfirst(
                                                            $leafTranslation['businessPartnerNotesLabel']
                                                    );
                                                    ?>"><?php
                                                       if (isset($businessPartnerArray) && is_array($businessPartnerArray)) {
                                                           echo htmlentities($businessPartnerArray[0]['businessPartnerNotes']);
                                                       }
                                                       ?></textarea> <span class="help-block" id="businessPartnerNotesHelpMe"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
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
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                            ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                            ?></a></li>-->
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
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                             ?></a></li> -->
                                    <li>
                                        <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled">
                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $businessPartner->getControllerPath();
                                   ?>', '<?php
                                   echo $businessPartner->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>

                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $businessPartner->getViewPath();
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
    </form><script type="text/javascript">

        $(document).ready(function() {
            showMeDiv('address', 0);
            showMeDiv('office', 0);
            showMeDiv('contact', 0);
            showMeDiv('web', 0);
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
            $("#webLegend").on('click', function() {
                toggle("web");
            });
            $("#notesLegend").on('click', function() {
                toggle("notes");
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            $('#businessPartnerDate').datepicker({
                format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
            }).on('changeDate', function() {
                $(this).datepicker('hide');
            });
            validateMeAlphaNumeric('businessPartnerCreditTerm');
            validateMeCurrency('businessPartnerCreditLimit');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['businessPartnerId']) {
            ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                    $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                    $('#newRecordButton3').attr('onClick', '');
                    $('#newRecordButton4').attr('onClick', '');
                    $('#newRecordButton5').attr('onClick', '');
                    $('#newRecordButton6').attr('onClick', '');
                    $('#newRecordButton7').attr('onClick', '');
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $businessPartner->getControllerPath(); ?>','<?php echo $businessPartner->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');

            <?php } ?>
            <?php
        }
    }
    ?>
            $('#businessPartnerPictureDiv').fineUploader({
                request: {
                    endpoint: './v3/financial/businessPartner/controller/businessPartnerController.php'
                },
                validation: {
                    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                    sizeLimit: 20971520,
                    stopOnFirstInvalidFile: true
                },
                method: 'POST',
                params: {
                    securityToken: '<?php echo $securityToken; ?>',
                    leafId: <?php echo $leafId; ?>,
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
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'>" + reason + "  : " + filename + "</span>");
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'>" + reason + "  : " + filename + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }).
                    on('onCancel', function(id, filename) {
                        var message = "<?php echo $t['cancelButtonLabel']; ?>";
                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'>" + message + "  : " + filename + "</span>");
                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                    }).on('complete', function(event, id, filename, responseJSON) {
                if (responseJSON.success === true) {
                    $("#infoPanelForm").html('').empty().html("<div class='alert alert-success'><img src='./images/icons/smiley-roll.png'> <b>Upload complete </b> : " + filename + "</div>");

                    $("#businessPartnerPicturePreviewUpload").html('').empty().html("<ul class=\"img-thumbnails\"><li>&nbsp;<div class=\"img-thumbnail\"><img src='./v3/financial/businessPartner/images/" + filename + "'  width='80' height='80'></div></li></ul>");
                    $("#businessPartnerPicture").val('').val(filename);
                } else {
                    $("#infoPanelForm").html('').empty().html("<div class='alert alert-error'><img src='./images/icons/smiley-roll-sweat.png'> <b>Filename</b>  : " + filename + " \n<br><br><b>Error Message</b> :" + responseJSON.error + "</div>");

                }
            }).on('submit', function(event, id, filename) {
                $(this).fineUploader('setParams', {'securityToken': '<?php echo $securityToken; ?>',
                    'method': 'upload',
                    'leafId': <?php echo $leafId; ?>,
                    'output': 'json'});
                var message = "<?php echo $t['loadigTextLabel']; ?>";
                $("#infoPanelForm").html('').empty().html("<div class='alert alert-info'><img src='./images/icons/smiley-roll.png'> " + message + " Id: " + id + "  : " + filename + "</div>");

            });
        });
        function showMeAll(toggle) {
            showMeDiv('address', toggle);
            showMeDiv('office', toggle);
            showMeDiv('contact', toggle);
            showMeDiv('web', toggle);
            showMeDiv('notes', toggle);
        }
        function copyAddress() {
            $("#businessPartnerShippingAddress").val($("#businessPartnerOfficeAddress").val()).trigger("liszt:updated");
            $("#businessPartnerShippingCountryId").val($("#businessPartnerOfficeCountryId").val()).trigger("liszt:updated");
            $("#businessPartnerShippingStateId").val($("#businessPartnerOfficeStateId").val()).trigger("liszt:updated");
            $("#businessPartnerShippingCityId").val($("#businessPartnerOfficeCityId").val()).trigger("liszt:updated");
            $("#businessPartnerShippingPostCode").val($("#businessPartnerOfficePostCode").val());
        }
        function autoCopy(field) {
            $("#businessPartnerShipping" + field).val($("#businessPartnerOffice" + field).val());
        }
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/financial/businessPartner/javascript/businessPartner.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>