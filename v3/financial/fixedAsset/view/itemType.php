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
require_once($newFakeDocumentRoot . "v3/financial/fixedAsset/controller/itemTypeController.php");
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

$translator->setCurrentTable('itemType');

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
$itemTypeArray = array();
$itemCategoryArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $itemType = new \Core\Financial\FixedAsset\ItemType\Controller\ItemTypeClass();
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
            $itemType->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $itemType->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $itemType->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $itemType->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $itemType->setStartDay($start[2]);
            $itemType->setStartMonth($start[1]);
            $itemType->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $itemType->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $itemType->setEndDay($start[2]);
            $itemType->setEndMonth($start[1]);
            $itemType->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $itemType->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $itemType->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $itemType->setServiceOutput('html');
        $itemType->setLeafId($leafId);
        $itemType->execute();
        $itemCategoryArray = $itemType->getItemCategory();
        if ($_POST['method'] == 'read') {
            $itemType->setStart($offset);
            $itemType->setLimit($limit); // normal system don't like paging..
            $itemType->setPageOutput('html');
            $itemTypeArray = $itemType->read();
            if (isset($itemTypeArray [0]['firstRecord'])) {
                $firstRecord = $itemTypeArray [0]['firstRecord'];
            }
            if (isset($itemTypeArray [0]['nextRecord'])) {
                $nextRecord = $itemTypeArray [0]['nextRecord'];
            }
            if (isset($itemTypeArray [0]['previousRecord'])) {
                $previousRecord = $itemTypeArray [0]['previousRecord'];
            }
            if (isset($itemTypeArray [0]['lastRecord'])) {
                $lastRecord = $itemTypeArray [0]['lastRecord'];
                $endRecord = $itemTypeArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($itemType->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($itemTypeArray [0]['total'])) {
                $total = $itemTypeArray [0]['total'];
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
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onclick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $itemType->getViewPath();
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
                                    echo $itemType->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $itemType->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $itemType->getControllerPath();
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
                                <div id="btnList">

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                        class="btn btn-info btn-block"
                                            onclick="showForm(<?php echo $leafId; ?>, '<?php
                                            echo $itemType->getViewPath();
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
                                       echo $itemType->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name="clearSearchString" id="clearSearchString"
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $itemType->getViewPath();
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
                                        <input type="hidden" name="itemTypeIdPreview" id="itemTypeIdPreview">

                                        <div class="form-group" id="itemCategoryIdDiv">
                                            <label for="itemCategoryIdPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemCategoryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="itemCategoryIdPreview"
                                                       id="itemCategoryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemTypeCodeDiv">
                                            <label for="itemTypeCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemTypeCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="itemTypeCodePreview"
                                                       id="itemTypeCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemTypeDepreciationRateDiv">
                                            <label for="itemTypeDepreciationRatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemTypeDepreciationRateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="itemTypeDepreciationRatePreview" id="itemTypeDepreciationRatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemTypeLifeDiv">
                                            <label for="itemTypeLifePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemTypeLifeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="itemTypeLifePreview"
                                                       id="itemTypeLifePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemTypeMinimumReOrderDiv">
                                            <label for="itemTypeMinimumReOrderPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemTypeMinimumReOrderLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="itemTypeMinimumReOrderPreview" id="itemTypeMinimumReOrderPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="itemTypeDescriptionDiv">
                                            <label for="itemTypeDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['itemTypeDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="itemTypeDescriptionPreview"
                                                       id="itemTypeDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isDepreciateDiv">
                                            <label for="isDepreciatePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isDepreciateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isDepreciatePreview"
                                                       id="isDepreciatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isFixedAssetDiv">
                                            <label for="isFixedAssetPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isFixedAssetLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isFixedAssetPreview"
                                                       id="isFixedAssetPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isInventoryDiv">
                                            <label for="isInventoryPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isInventoryLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isInventoryPreview"
                                                       id="isInventoryPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isSalesItemDiv">
                                            <label for="isSalesItemPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isSalesItemLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="isSalesItemPreview"
                                                       id="isSalesItemPreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $itemType->getControllerPath();
                                            ?>', '<?php
                                            echo $itemType->getViewPath();
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
                                    <th width="100px"><?php echo ucwords($leafTranslation['itemCategoryIdLabel']); ?></th>
                                    <th width="75px">
                                    <div align="center"><?php echo ucwords($leafTranslation['itemTypeCodeLabel']); ?></div>
                                    </th>
                                    <th><?php echo ucwords($leafTranslation['itemTypeDescriptionLabel']); ?></th>
                                    <th align="center">
                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?>
                                        </div>
                                            </th>
                                            <th><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                            <th width="25px" align="center">
                                                <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                                       onChange="toggleChecked(this.checked);">
                                            </th>
                                            </tr>
                                            </thead>
                                            <tbody id="tableBody">
                                                <?php
                                                if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                                    if (is_array($itemTypeArray)) {
                                                        $totalRecord = intval(count($itemTypeArray));
                                                        if ($totalRecord > 0) {
                                                            $counter = 0;
                                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                                $counter++;
                                                                ?>
                                                                <tr <?php
                                                                if ($itemTypeArray[$i]['isDelete'] == 1) {
                                                                    echo "class=\"danger\"";
                                                                } else {
                                                                    if ($itemTypeArray[$i]['isDraft'] == 1) {
                                                                        echo "class=\"warning\"";
                                                                    }
                                                                }
                                                                ?>>
                                                                    <td align="center">
                                                                        <div align="center"><?php echo($counter + $offset); ?></div>
                                                                    </td>
                                                                    <td align="center">
																		<?php if($itemTypeArray [$i]['itemTypeCode'] !='UNBL') { ?>
                                                                        <div class="btn-group" align="center">
                                                                            <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                    onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                    echo $itemType->getControllerPath();
                                                                                    ?>', '<?php
                                                                                    echo $itemType->getViewPath();
                                                                                    ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                    echo intval(
                                                                                            $itemTypeArray [$i]['itemTypeId']
                                                                                    );
                                                                                    ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                                <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                            <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                    onclick="showModalDelete('<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemTypeId']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemCategoryDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemTypeCode']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemTypeDepreciationRate']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemTypeLife']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemTypeMinimumReOrder']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['itemTypeDescription']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['isDepreciate']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['isFixedAsset']
                                                                                    );
                                                                                    ?>', '<?php
                                                                                    echo rawurlencode(
                                                                                            $itemTypeArray [$i]['isInventory']
                                                                                    );
                                                                                    ?>', '<?php echo rawurlencode($itemTypeArray [$i]['isSalesItem']); ?>');">
                                                                                <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                        </div>
																		<?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="pull-left">
                                                                            <?php
                                                                            if (isset($itemTypeArray[$i]['itemCategoryDescription'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos(
                                                                                                        $itemTypeArray[$i]['itemCategoryDescription'], $_POST['query']
                                                                                                ) !== false
                                                                                        ) {
                                                                                            echo str_replace(
                                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $itemTypeArray[$i]['itemCategoryDescription']
                                                                                            );
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['itemCategoryDescription'];
                                                                                        }
                                                                                    } else {
                                                                                        if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                            if (strpos(
                                                                                                            $itemTypeArray[$i]['itemCategoryDescription'], $_POST['character']
                                                                                                    ) !== false
                                                                                            ) {
                                                                                                echo str_replace(
                                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $itemTypeArray[$i]['itemCategoryDescription']
                                                                                                );
                                                                                            } else {
                                                                                                echo $itemTypeArray[$i]['itemCategoryDescription'];
                                                                                            }
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['itemCategoryDescription'];
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    echo $itemTypeArray[$i]['itemCategoryDescription'];
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            &nbsp;
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <div align="center">
                                                                            <?php
                                                                            if (isset($itemTypeArray[$i]['itemTypeCode'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos(
                                                                                                        strtolower($itemTypeArray[$i]['itemTypeCode']), strtolower($_POST['query'])
                                                                                                ) !== false
                                                                                        ) {
                                                                                            echo str_replace(
                                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $itemTypeArray[$i]['itemTypeCode']
                                                                                            );
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['itemTypeCode'];
                                                                                        }
                                                                                    } else {
                                                                                        if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                            if (strpos(
                                                                                                            strtolower($itemTypeArray[$i]['itemTypeCode']), strtolower($_POST['character'])
                                                                                                    ) !== false
                                                                                            ) {
                                                                                                echo str_replace(
                                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $itemTypeArray[$i]['itemTypeCode']
                                                                                                );
                                                                                            } else {
                                                                                                echo $itemTypeArray[$i]['itemTypeCode'];
                                                                                            }
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['itemTypeCode'];
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    echo $itemTypeArray[$i]['itemTypeCode'];
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
                                                                            if (isset($itemTypeArray[$i]['itemTypeDescription'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos(
                                                                                                        strtolower($itemTypeArray[$i]['itemTypeDescription']), strtolower($_POST['query'])
                                                                                                ) !== false
                                                                                        ) {
                                                                                            echo str_replace(
                                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $itemTypeArray[$i]['itemTypeDescription']
                                                                                            );
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['itemTypeDescription'];
                                                                                        }
                                                                                    } else {
                                                                                        if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                            if (strpos(
                                                                                                            strtolower($itemTypeArray[$i]['itemTypeDescription']), strtolower($_POST['character'])
                                                                                                    ) !== false
                                                                                            ) {
                                                                                                echo str_replace(
                                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $itemTypeArray[$i]['itemTypeDescription']
                                                                                                );
                                                                                            } else {
                                                                                                echo $itemTypeArray[$i]['itemTypeDescription'];
                                                                                            }
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['itemTypeDescription'];
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    echo $itemTypeArray[$i]['itemTypeDescription'];
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            &nbsp;
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <div align="center">
                                                                            <?php
                                                                            if (isset($itemTypeArray[$i]['executeBy'])) {
                                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                        if (strpos($itemTypeArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                            echo str_replace(
                                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $itemTypeArray[$i]['staffName']
                                                                                            );
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['staffName'];
                                                                                        }
                                                                                    } else {
                                                                                        if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                            if (strpos(
                                                                                                            $itemTypeArray[$i]['staffName'], $_POST['character']
                                                                                                    ) !== false
                                                                                            ) {
                                                                                                echo str_replace(
                                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $itemTypeArray[$i]['staffName']
                                                                                                );
                                                                                            } else {
                                                                                                echo $itemTypeArray[$i]['staffName'];
                                                                                            }
                                                                                        } else {
                                                                                            echo $itemTypeArray[$i]['staffName'];
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    echo $itemTypeArray[$i]['staffName'];
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            &nbsp;
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php
                                                                    if (isset($itemTypeArray[$i]['executeTime'])) {
                                                                        $valueArray = $itemTypeArray[$i]['executeTime'];
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
																																																																									} else {
																																																															$value=null;
																																																														}
                                                                        ?>
                                                                        <td><?php echo $value; ?></td>
                                                                    <?php
                                                                    if ($itemTypeArray[$i]['isDelete']) {
                                                                        $checked = "checked";
                                                                    } else {
                                                                        $checked = null;
                                                                    }
                                                                    ?>
                                                                    <td><?php if($itemTypeArray [$i]['itemTypeCode'] !='UNBL') { ?>
                                                                        <input style="display:none;" type="checkbox" name="itemTypeId[]"
                                                                               value="<?php echo $itemTypeArray[$i]['itemTypeId']; ?>">
                                                                        <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                       value="<?php echo $itemTypeArray[$i]['isDelete']; ?>">
																			<?php } ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="11" valign="top" align="center"><?php
                                                                    $itemType->exceptionMessage(
                                                                            $t['recordNotFoundLabel']
                                                                    );
                                                                    ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="11" valign="top" align="center"><?php
                                                                $itemType->exceptionMessage(
                                                                        $t['recordNotFoundLabel']
                                                                );
                                                                ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="11" valign="top" align="center"><?php
                                                            $itemType->exceptionMessage(
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
                                                onclick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                                echo $itemType->getControllerPath();
                                                ?>', '<?php echo $itemType->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
                                               echo $itemType->getControllerPath();
                                               ?>', '<?php
                                               echo $itemType->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                               onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                               echo $itemType->getControllerPath();
                                               ?>', '<?php
                                               echo $itemType->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                               onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                               echo $itemType->getControllerPath();
                                               ?>', '<?php
                                               echo $itemType->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                            </a>
                                        </div>
                                        <div class="btn-group">
                                            <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                               onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                               echo $itemType->getControllerPath();
                                               ?>', '<?php
                                               echo $itemType->getViewPath();
                                               ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                    class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" name="itemTypeId" id="itemTypeId" value="<?php
                                    if (isset($_POST['itemTypeId'])) {
                                        echo $_POST['itemTypeId'];
                                    }
                                    ?>">

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
                                                                    if ($itemTypeArray[0]['itemCategoryId'] == $itemCategoryArray[$i]['itemCategoryId']) {
                                                                        $selected = "selected";
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
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="itemTypeCodeForm">
                                                <label for="itemTypeCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['itemTypeCodeLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="itemTypeCode" id="itemTypeCode" value="<?php
                                                        if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                            echo htmlentities($itemTypeArray[0]['itemTypeCode']);
                                                        }
                                                        ?>" maxlength="16">
                                                        <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                                    <span class="help-block" id="itemTypeCodeHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="itemTypeDepreciationRateForm">
                                                <label for="itemTypeDepreciationRate" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['itemTypeDepreciationRateLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="itemTypeDepreciationRate"
                                                               id="itemTypeDepreciationRate" value="<?php
                                                               if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                                   echo htmlentities($itemTypeArray[0]['itemTypeDepreciationRate']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                    <span class="help-block" id="itemTypeDepreciationRateHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="itemTypeLifeForm">
                                                <label for="itemTypeLife" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['itemTypeLifeLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="itemTypeLife" id="itemTypeLife" value="<?php
                                                        if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                            echo htmlentities($itemTypeArray[0]['itemTypeLife']);
                                                        }
                                                        ?>"> <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                                    <span class="help-block" id="itemTypeLifeHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="itemTypeMinimumReOrderForm">
                                                <label for="itemTypeMinimumReOrder" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['itemTypeMinimumReOrderLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="itemTypeMinimumReOrder"
                                                               id="itemTypeMinimumReOrder" value="<?php
                                                               if (isset($itemTypeArray[0]['itemTypeMinimumReOrder'])) {
                                                                   echo htmlentities($itemTypeArray[0]['itemTypeMinimumReOrder']);
                                                               }
                                                               ?>"> <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                                    <span class="help-block" id="itemTypeMinimumReOrderHelpMe"></span>
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
                                                    <div id="isDepreciateSwitch" class="switch" data-on-label="<?php echo $t['yesTextLabel']; ?>"
                                                         data-off-label="<?php echo $t['noTextLabel']; ?>" data-on="success" data-off="danger">
                                                        <input type="checkbox" name="isDepreciate" id="isDepreciate" value="<?php
                                                        if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                            echo $itemTypeArray[0]['isDepreciate'];
                                                        }
                                                        ?>"></div>
                                                    <span class="help-block" id="isDepreciateHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isFixedAssetForm">
                                                <label for="isFixedAsset" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['isFixedAssetLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div id="isFixedAssetSwitch" class="switch" data-on-label="<?php echo $t['yesTextLabel']; ?>"
                                                         data-off-label="<?php echo $t['noTextLabel']; ?>" data-on="success" data-off="danger">
                                                        <input type="checkbox" name="isFixedAsset" id="isFixedAsset" value="<?php
                                                        if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                            echo $itemTypeArray[0]['isFixedAsset'];
                                                        }
                                                        ?>"></div>
                                                    <span class="help-block" id="isFixedAssetHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isInventoryForm">
                                                <label for="isInventory" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['isInventoryLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div id="isInventorySwitch" class="switch" data-on-label="<?php echo $t['yesTextLabel']; ?>"
                                                         data-off-label="<?php echo $t['noTextLabel']; ?>" data-on="success" data-off="danger">
                                                        <input type="checkbox" name="isInventory" id="isInventory" value="<?php
                                                        if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                            echo $itemTypeArray[0]['isInventory'];
                                                        }
                                                        ?>"></div>
                                                    <span class="help-block" id="isInventoryHelpMe"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isSalesItemForm">
                                                <label for="isSalesItem" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                        echo ucfirst(
                                                                $leafTranslation['isSalesItemLabel']
                                                        );
                                                        ?></strong></label>

                                                <div class="col-xs-8 col-sm-8 col-md-8">
                                                    <div id="isSalesItemSwitch" class="switch" data-on-label="<?php echo $t['yesTextLabel']; ?>"
                                                         data-off-label="<?php echo $t['noTextLabel']; ?>" data-on="success" data-off="danger">
                                                        <input type="checkbox" name="isSalesItem" id="isSalesItem" value="<?php
                                                        if (isset($itemTypeArray) && is_array($itemTypeArray)) {
                                                            echo $itemTypeArray[0]['isSalesItem'];
                                                        }
                                                        ?>"></div>
                                                    <span class="help-block" id="isSalesItemHelpMe"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group" id="itemTypeDescriptionForm">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <textarea rows="5" class="form-control" name="itemTypeDescription" id="itemTypeDescription"><?php
                                                        if (isset($itemTypeArray[0]['itemTypeDescription'])) {
                                                            echo htmlentities($itemTypeArray[0]['itemTypeDescription']);
                                                        }
                                                        ?></textarea> <span class="help-block" id="itemTypeDescriptionHelpMe"></span>
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
                                            <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                           ?></a></li>-->
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
                                            <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                       ?></a></li> -->
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
                                           echo $itemType->getControllerPath();
                                           ?>', '<?php
                                           echo $itemType->getViewPath();
                                           ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                           onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                           echo $itemType->getViewPath();
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
                       
                        $(document).ready(function() {
                            window.scrollTo(0, 0);
                            $('.switch')['bootstrapSwitch']();
                            $(".chzn-select").chosen({search_contains: true});
                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                            validateMeNumeric('itemTypeId');
                            validateMeNumeric('itemCategoryId');
                            validateMeAlphaNumeric('itemTypeCode');
                            validateMeCurrency('itemTypeDepreciationRate');
                            validateMeCurrency('itemTypeLife');
                            validateMeNumeric('itemTypeMinimumReOrder');
                            validateMeNumeric('isDepreciate');
                            validateMeNumeric('isFixedAsset');
                            validateMeNumeric('isInventory');
                            validateMeNumeric('isSalesItem');
    <?php if ($_POST['method'] == "new") { ?>
                                $('#resetRecordButton')
                                        .removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                    $('#newRecordButton1')
                                            .removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                    $('#newRecordButton2')
                                            .removeClass().addClass('btn dropdown-toggle btn-success');
                                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['itemTypeId']) {
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
                                                .removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                        $('#updateRecordButton2')
                                                .removeClass().addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
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
                                                .attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $itemType->getControllerPath(); ?>','<?php echo $itemType->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
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
            <script type="text/javascript" src="./v3/financial/fixedAsset/javascript/itemType.js"></script>
            <hr>
            <footer><p>IDCMS 2012/2013</p></footer>