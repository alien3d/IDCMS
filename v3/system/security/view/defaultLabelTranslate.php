<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php

use Core\System\Security\DefaultLabelTranslate\Controller\DefaultLabelTranslateClass;

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
require_once($newFakeDocumentRoot . "v3/system/security/controller/defaultLabelTranslateController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable(array('defaultLabelTranslate', 'defaultLabel'));

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
$t = $translator->getDefaultTranslation(); // short because code too long
$leafTranslation = $translator->getLeafTranslation();
$leafAccess = $translator->getLeafAccess();
$defaultLabelTranslateArray = array();
$defaultLabelArray = array();
$languageArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $defaultLabelTranslate = new DefaultLabelTranslateClass();
        define('LIMIT', 1000);
        if (isset($_POST['offset'])) {
            $offset = $_POST['offset'];
        } else {
            $offset = 0;
        }
        if (isset($_POST['limit'])) {
            $limit = 1000;
        } else {
            $limit = LIMIT;
        }
        if (isset($_POST ['query'])) {
            $defaultLabelTranslate->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $defaultLabelTranslate->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $defaultLabelTranslate->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $defaultLabelTranslate->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $defaultLabelTranslate->setStartDay($start[2]);
            $defaultLabelTranslate->setStartMonth($start[1]);
            $defaultLabelTranslate->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $defaultLabelTranslate->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $defaultLabelTranslate->setEndDay($start[2]);
            $defaultLabelTranslate->setEndMonth($start[1]);
            $defaultLabelTranslate->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $defaultLabelTranslate->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $defaultLabelTranslate->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $defaultLabelTranslate->setServiceOutput('html');
        $defaultLabelTranslate->setLeafId($leafId);
        $defaultLabelTranslate->execute();
        $defaultLabelArray = $defaultLabelTranslate->getDefaultLabel();
        $languageArray = $defaultLabelTranslate->getLanguage();
        if ($_POST['method'] == 'read') {
            $defaultLabelTranslate->setStart($offset);
            $defaultLabelTranslate->setLimit($limit); // normal system don't like paging..
            $defaultLabelTranslate->setPageOutput('html');
            $defaultLabelTranslateArray = $defaultLabelTranslate->read();
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($defaultLabelTranslate->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($defaultLabelTranslateArray [0]['total'])) {
                $total = $defaultLabelTranslateArray [0]['total'];
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
        <form class="form-horizontal">
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
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="defaultLabelId" class="control-label col-xs-2 col-sm-2 col-md-2"><?php
                                        echo ucfirst(
                                                $leafTranslation['defaultLabelIdLabel']
                                        );
                                        ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name="defaultLabelId" id="defaultLabelId" class="chzn-select form-control"
                                                style="width:400px" onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $defaultLabelTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>');">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($defaultLabelArray)) {
                                                $totalRecord = intval(count($defaultLabelArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                                                    <option value="<?php echo $defaultLabelArray[$i]['defaultLabelId']; ?>"><?php echo $d; ?>
                                                        . <?php echo $defaultLabelArray[$i]['defaultLabelEnglish']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="languageId"
                                           class="control-label col-xs-2 col-sm-2 col-md-2"><?php echo ucfirst($leafTranslation['languageIdLabel']); ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name="languageId" id="languageId" class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $defaultLabelTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>');">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($languageArray)) {
                                                $totalRecord = intval(count($languageArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                                                    <option value="<?php echo $languageArray[$i]['languageId']; ?>"><?php echo $d; ?>
                                                        . <?php echo $languageArray[$i]['languageDescription']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info"
                                        onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                        echo $defaultLabelTranslate->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>');">
                                            <?php echo $t['clearButtonLabel']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <table class='table table-bordered table-striped table-condensed table-hover' id='tableData'>
                                <thead>
                                    <tr>
                                        <th width="25" align="center">
                                <div align="center">#</div>
                                </th>
                                <th><?php echo ucfirst($leafTranslation['defaultLabelEnglishLabel']); ?></th>
                                <th width="200"><?php echo ucfirst($leafTranslation['languageIdLabel']); ?></th>
                                <th width="400"><?php echo ucfirst($leafTranslation['defaultLabelNativeLabel']); ?></th>
                                </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($defaultLabelTranslateArray)) {
                                            $totalRecord = intval(count($defaultLabelTranslateArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
                                                    <tr>
                                                        <td align="center"><?php echo($counter + $offset); ?>.</td>
                                                        <td align="left">
														<?php if (isset($defaultLabelTranslateArray[$i]['defaultLabelEnglish'])) {
                                                             echo $defaultLabelTranslateArray[$i]['defaultLabelEnglish'];
                                                         } ?></td>
														<td vAlign="top"><div class="pull-left">
														<?php  if (isset($defaultLabelTranslateArray[$i]['languageDescription'])) { 
																 if (file_exists( $newFakeDocumentRoot . "/images/country/" . $defaultLabelTranslateArray[$i]['languageIcon'])) {?>
                                                                        <img class="img-thumbnail"
                                                                             src="./images/country/<?php echo $defaultLabelTranslateArray[$i]['languageIcon']; ?>">&nbsp;<?php echo $defaultLabelTranslateArray[$i]['languageDescription']; ?>
                                                                    <?php } else { ?>
                                                                        Image Country Not Available
                                                                    <?php } ?>
                                                               
                                                        <?php } ?></div></td>
														<td vAlign="top">
														<?php  if (isset($defaultLabelTranslateArray[$i]['defaultLabelNative'])) { ?>

                                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                               name="defaultLabelNative<?php echo $defaultLabelTranslateArray[$i]['defaultLabelTranslateId']; ?>"
                                                                               id="defaultLabelNative<?php echo $defaultLabelTranslateArray[$i]['defaultLabelTranslateId']; ?>"
                                                                               value="<?php echo $defaultLabelTranslateArray[$i]['defaultLabelNative']; ?>">
                                                                               <?php
                                                                               if ($leafAccess['leafAccessUpdateValue'] == 0) {
                                                                                   $disabled = "disabled";
                                                                               } else {
                                                                                   $disabled = null;
                                                                               }
                                                                               ?>
                                                                        <span class="input-group-btn">
                                                                            <button type="button"  class="btn btn-warning <?php echo $disabled; ?>"
                                                                                    title="<?php echo $t['saveButtonLabel']; ?>"
                                                                                    <?php echo $disabled; ?>
                                                                                    onClick="updateRecordInline(<?php echo $leafId; ?>, '<?php
                                                                                    echo $defaultLabelTranslate->getControllerPath(
                                                                                    );
                                                                                    ?>', '<?php echo $securityToken; ?>', '<?php echo $defaultLabelTranslateArray[$i]['defaultLabelTranslateId']; ?>');"><?php echo $t['saveButtonLabel']; ?></button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div id="infoPanelMini<?php echo $defaultLabelTranslateArray[$i]['defaultLabelTranslateId']; ?>"></div>

                                                        <?php } else { ?>
                                                            <div class="pull-right"><img src="./images/icons/burn.png"></div>
                                                        <?php } ?>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" vAlign="top" align="center"><?php
                                                        $defaultLabelTranslate->exceptionMessage(
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
                                                    $defaultLabelTranslate->exceptionMessage(
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
                                                $defaultLabelTranslate->exceptionMessage(
                                                        $t['loadFailureLabel']
                                                );
                                                ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody></table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</form>
<script type='text/javascript'>

    $(document).ready(function() {
        tableHeightSize();
        $(window).resize(function() {
            tableHeightSize();
        });
        window.scrollTo(0, 0);
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({allow_single_deselect: true});
    });
</script>
<script type='text/javascript' src='./v3/system/security/javascript/defaultLabelTranslate.js'></script>
