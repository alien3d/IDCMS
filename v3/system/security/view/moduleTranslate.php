<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php

use Core\System\Security\ModuleTranslate\Controller\ModuleTranslateClass;

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
require_once($newFakeDocumentRoot . "v3/system/security/controller/moduleTranslateController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable(array('moduleTranslate', 'module'));

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

$moduleTranslateArray = array();
$applicationArray = array();
$moduleArray = array();
$languageArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $moduleTranslate = new ModuleTranslateClass();
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
            $moduleTranslate->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $moduleTranslate->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $moduleTranslate->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $moduleTranslate->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $moduleTranslate->setStartDay($start[2]);
            $moduleTranslate->setStartMonth($start[1]);
            $moduleTranslate->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $moduleTranslate->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $moduleTranslate->setEndDay($start[2]);
            $moduleTranslate->setEndMonth($start[1]);
            $moduleTranslate->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $moduleTranslate->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $moduleTranslate->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $moduleTranslate->setServiceOutput('html');
        $moduleTranslate->setLeafId($leafId);
        $moduleTranslate->execute();
        $applicationArray = $moduleTranslate->getApplication();
        $moduleArray = $moduleTranslate->getModule();
        $languageArray = $moduleTranslate->getLanguage();
        if ($_POST['method'] == 'read') {
            $moduleTranslate->setStart($offset);
            $moduleTranslate->setLimit($limit); // normal system don't like paging..
            $moduleTranslate->setPageOutput('html');
            $moduleTranslateArray = $moduleTranslate->read();
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($moduleTranslate->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($moduleTranslateArray [0]['total'])) {
                $total = $moduleTranslateArray [0]['total'];
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
</script><link rel="stylesheet"  type="text/css" href="./css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="./css/smartadmin-production.css" /><?php
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
                                            <label for="applicationId" class="control-label col-xs-2 col-sm-2 col-md-2"><?php
                                                echo ucfirst(
                                                        $leafTranslation['applicationIdLabel']
                                                );
                                                ?></label>

                                            <div class="col-xs-10 col-sm-10 col-md-10">
                                                <select name='applicationId' id='applicationId' class="chzn-select form-control"
                                                        style="width:400px" onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                        echo $moduleTranslate->getControllerPath();
                                                        ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1);
                                                                filterForm(<?php echo $leafId; ?>, '<?php
                                                        echo $moduleTranslate->getControllerPath();
                                                        ?>', '<?php echo $securityToken; ?>', 0, 9999);">
                                                    <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                                    <?php
                                                    if (is_array($applicationArray)) {
                                                        $totalRecord = intval(count($applicationArray));
                                                        $d = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $d++;
                                                            ?>
                                                            <option value="<?php echo $applicationArray[$i]['applicationId']; ?>"><?php echo $d; ?>
                                                                . <?php echo $applicationArray[$i]['applicationEnglish']; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="moduleId"
                                                   class="control-label col-xs-2 col-sm-2 col-md-2"><?php echo ucfirst($leafTranslation['moduleIdLabel']); ?></label>

                                            <div class="col-xs-10 col-sm-10 col-md-10">
                                                <select name='moduleId' id='moduleId' class="chzn-select form-control" style="width:400px"
                                                        onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                        echo $moduleTranslate->getControllerPath();
                                                        ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1);">
                                                    <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                                    <?php
                                                    if (is_array($moduleArray)) {
                                                        $totalRecord = intval(count($moduleArray));
                                                        $d = 0;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            $d++;
                                                            ?>
                                                            <option value="<?php echo $moduleArray[$i]['moduleId']; ?>"><?php echo $d; ?>
                                                                . <?php echo $moduleArray[$i]['moduleEnglish']; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="languageId" class="control-label col-xs-2 col-sm-2 col-md-2"><?php
                                                echo ucfirst(
                                                        $leafTranslation['languageIdLabel']
                                                );
                                                ?></label>

                                            <div class="col-xs-10 col-sm-10 col-md-10">
                                                <select name='languageId' id='languageId' class="chzn-select form-control"
                                                        style="width:400px" onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                        echo $moduleTranslate->getControllerPath();
                                                        ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1);">
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
                                                </select></div>
                                        </div>
                            </div>
                            <div class="panel-footer">
                                <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info"
                                        onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                        echo $moduleTranslate->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>');" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
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
                                        <th width="25px" align="center">
                                <div align="center">#</div>
                                </th>
                                <th width="150px"><?php echo ucfirst($leafTranslation['applicationIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['moduleIdLabel']); ?></th>
                                <th width="200px"><?php echo ucfirst($leafTranslation['languageIdLabel']); ?></th>
                                <th width="300px"><?php echo ucfirst($leafTranslation['moduleNativeLabel']); ?></th>
                                </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($moduleTranslateArray)) {
                                            $totalRecord = intval(count($moduleTranslateArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
                                                    <tr>
                                                        <td align="center">
                                                            <div align="center"><?php echo($counter + $offset); ?></div>
                                                        </td>
														<td vAlign="top">
                                                        <?php if (isset($moduleTranslateArray[$i]['applicationEnglish'])) { ?>
                                                            <div class="pull-left"><?php echo $moduleTranslateArray[$i]['applicationEnglish']; ?></div>
                                                        <?php } ?></td>
														<td vAlign="top">
                                                        <?php if (isset($moduleTranslateArray[$i]['moduleEnglish'])) { ?>
                                                            <div class="pull-left"><?php echo $moduleTranslateArray[$i]['moduleEnglish']; ?></div>
                                                        <?php } ?></td>
														
														<td vAlign="top"><?php if (isset($moduleTranslateArray[$i]['languageDescription'])) {
                                                                    if (file_exists($newFakeDocumentRoot . "images/country/" . $moduleTranslateArray[$i]['languageIcon']
)) { ?>                                                                <div class="pull-left">

                                                                        <img src="./images/country/<?php echo $moduleTranslateArray[$i]['languageIcon']; ?>"> <?php echo $moduleTranslateArray[$i]['languageDescription']; ?></div>
                                                                    <?php } else { ?>
                                                                        Image Country Not Available
                                                                    <?php } ?>
                                                        <?php } ?></td>
														<td vAlign="top">
														<?php  if (isset($moduleTranslateArray[$i]['moduleNative'])) {  ?>
                                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                               name="moduleNative<?php echo $moduleTranslateArray[$i]['moduleTranslateId']; ?>"
                                                                               id="moduleNative<?php echo $moduleTranslateArray[$i]['moduleTranslateId']; ?>"
                                                                               value="<?php echo $moduleTranslateArray[$i]['moduleNative']; ?>">
                                                                               <?php
                                                                               if ($leafAccess['leafAccessUpdateValue'] == 0) {
                                                                                   $disabled = "disabled";
                                                                               } else {
                                                                                   $disabled = null;
                                                                               }
                                                                               ?>
                                                                        <span class="input-group-btn">
                                                                            <button type="button" 
                                                                                    class="btn btn-warning <?php echo $disabled; ?>"
                                                                                    title="<?php echo $t['saveButtonLabel']; ?>"
                                                                                    <?php echo $disabled; ?>
                                                                                    onClick="updateRecordInline(<?php echo $leafId; ?>, '<?php
                                                                                    echo $moduleTranslate->getControllerPath(
                                                                                    );
                                                                                    ?>', '<?php echo $securityToken; ?>', '<?php echo $moduleTranslateArray[$i]['moduleTranslateId']; ?>');"><?php echo $t['saveButtonLabel']; ?></button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div id="infoPanelMini<?php echo $moduleTranslateArray[$i]['moduleTranslateId']; ?>"></div>
                                                        <?php } else { ?>
                                                            <div class="pull-left"><img src="./images/icons/burn.png"></div>
                                                        <?php } ?>
														</td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" vAlign="top" align="center"><?php
                                                        $moduleTranslate->exceptionMessage(
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
                                                    $moduleTranslate->exceptionMessage(
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
                                                $moduleTranslate->exceptionMessage(
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
    <script type='text/javascript'>
        $(document).ready(function() {
            tableHeightSize();
            $(window).resize(function() {
                tableHeightSize()
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen();
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
        });
    </script>
</form>
<script type='text/javascript' src='./v3/system/security/javascript/moduleTranslate.js'></script>
