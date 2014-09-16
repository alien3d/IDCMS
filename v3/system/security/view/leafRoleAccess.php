<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php

use Core\System\Security\LeafRoleAccess\Controller\LeafRoleAccessClass;

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
require_once($newFakeDocumentRoot . "v3/system/security/controller/leafRoleAccessController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable(array('leaf', 'leafRoleAccess'));

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
$leafRoleAccessArray = array();
$applicationArray = array();
$moduleArray = array();
$folderArray = array();
$leafArray = array();
$roleArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $leafRoleAccess = new LeafRoleAccessClass();
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
            $leafRoleAccess->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $leafRoleAccess->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $leafRoleAccess->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $leafRoleAccess->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $leafRoleAccess->setStartDay($start[2]);
            $leafRoleAccess->setStartMonth($start[1]);
            $leafRoleAccess->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $leafRoleAccess->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $leafRoleAccess->setEndDay($start[2]);
            $leafRoleAccess->setEndMonth($start[1]);
            $leafRoleAccess->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $leafRoleAccess->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $leafRoleAccess->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $leafRoleAccess->setServiceOutput('html');
        $leafRoleAccess->setLeafId($leafId);
        $leafRoleAccess->execute();
        $applicationArray = $leafRoleAccess->getApplication();
        $moduleArray = $leafRoleAccess->getModule();
        $folderArray = $leafRoleAccess->getFolder();
        $leafArray = $leafRoleAccess->getLeaf();
        $roleArray = $leafRoleAccess->getRole();
        if ($_POST['method'] == 'read') {
            $leafRoleAccess->setStart($offset);
            $leafRoleAccess->setLimit($limit); // normal system don't like paging..
            $leafRoleAccess->setPageOutput('html');
            $leafRoleAccessArray = $leafRoleAccess->read();

            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($leafRoleAccess->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($leafRoleAccessArray [0]['total'])) {
                $total = $leafRoleAccessArray [0]['total'];
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
                                    <label class="control-label col-xs-2 col-sm-2 col-md-2" for="applicationId"><?php
                                        echo ucfirst(
                                                $leafTranslation['applicationIdLabel']
                                        );
                                        ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name='applicationId' id='applicationId' class="chzn-select form-control"
                                                style="width:400px" onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'moduleId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'folderId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'leafIdTemp');">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($applicationArray)) {
                                                $d = 0;
                                                $totalRecord = intval(count($applicationArray));
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
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'folderId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'leafIdTemp');">
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
                                    <label for="folderId"
                                           class="control-label col-xs-2 col-sm-2 col-md-2"><?php echo ucfirst($leafTranslation['folderIdLabel']); ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name='folderId' id='folderId' class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'leafIdTemp');">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($folderArray)) {
                                                $totalRecord = intval(count($folderArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                                                    <option value="<?php echo $folderArray[$i]['folderId']; ?>"><?php echo $d; ?>
                                                        . <?php echo $folderArray[$i]['folderEnglish']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="leafIdTemp"
                                           class="control-label col-xs-2 col-sm-2 col-md-2"><?php echo ucfirst($leafTranslation['leafIdLabel']); ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name='leafIdTemp' id='leafIdTemp' class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (isset($leafArray)) {
                                                if (is_array($leafArray)) {
                                                    $totalRecord = intval(count($leafArray));
                                                    $currentApplicationEnglish = null;
                                                    $currentModuleEnglish = null;
                                                    $currentFolderEnglish = null;
                                                    $group = 0;
                                                    $d = 1;
                                                    for ($i = 0; $i < $totalRecord; $i++) {

                                                        if ($i != 0) {
                                                            if ($currentFolderEnglish != $leafArray[$i]['folderEnglish']) {
                                                                $group = 1;
                                                                echo "</optgroup><optgroup label=\"" . $leafArray[$i]['folderEnglish'] . "\">";
                                                            }
                                                        } else {
                                                            echo "<optgroup label=\"" . $leafArray[$i]['folderEnglish'] . "\">";
                                                        }
                                                        $currentApplicationEnglish = $leafArray[$i]['applicationEnglish'];
                                                        $currentModuleEnglish = $leafArray[$i]['moduleEnglish'];
                                                        $currentFolderEnglish = $leafArray[$i]['folderEnglish'];
                                                        ?>

                                                        <option value="<?php echo $leafArray[$i]['leafId']; ?>"><?php echo $d; ?>
                                                            . <?php echo $leafArray[$i]['leafEnglish']; ?></option>


                                                        <?php
                                                        $d++;
                                                    }
                                                    echo "</optgroup>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="roleId"
                                           class="control-label col-xs-2 col-sm-2 col-md-2"><?php echo ucfirst($leafTranslation['roleIdLabel']); ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name='roleId' id='roleId' class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 1);">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($roleArray)) {
                                                $totalRecord = intval(count($roleArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                                                    <option value="<?php echo $roleArray[$i]['roleId']; ?>"><?php echo $d; ?>
                                                        . <?php echo $roleArray[$i]['roleDescription']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info"
                                                onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafRoleAccess->getViewPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);">
                                                    <?php echo $t['clearButtonLabel']; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="pull-right">
                            <button class="delete btn btn-warning" type="button" 
                                    onClick="updateGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                    echo $leafRoleAccess->getControllerPath();
                                    ?>', '<?php echo $leafRoleAccess->getViewPath(); ?>', <?php echo $securityToken; ?>);">
                                <i class='glyphicon glyphicon-lock'></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <span class="col-xs-12 col-sm-12 col-md-12">&nbsp;</span>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <table class='table table-bordered table-striped table-condensed table-hover' id='tableData'>
                                <thead>
                                    <tr>
                                        <th align="center" width="25px">
                                <div align="center">#</div>
                                </th>
                                <th><?php echo ucfirst($leafTranslation['roleIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['applicationIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['moduleIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['folderIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessDraftValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessCreateValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessReadValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessUpdateValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessDeleteValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessReviewValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessApprovedValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessPostValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafRoleAccessPrintValueLabel']); ?></th>
                                <th>
                                    <input class='form-control' type="checkbox" name='check_all' id='check_all' alt='Check Record'
                                           onChange="toggleChecked(this.checked);">
                                </th>
                                </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($leafRoleAccessArray)) {
                                            $totalRecord = intval(count($leafRoleAccessArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td align=\"center\"><div align=\"center\">" . ($counter + $offset) . ". </div></td>";
                                                    if (isset($leafRoleAccessArray[$i]['roleDescription'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafRoleAccessArray[$i]['roleDescription'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['applicationEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafRoleAccessArray[$i]['applicationEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafRoleAccessArray[$i]['moduleEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafRoleAccessArray[$i]['moduleEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafRoleAccessArray[$i]['folderEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafRoleAccessArray[$i]['folderEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafRoleAccessArray[$i]['leafEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafRoleAccessArray[$i]['leafEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessDraftValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessDraftValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessDraftValue[]' id='leafRoleAccessDraftValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessDraftValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessCreateValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessCreateValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessCreateValue[]' id='leafRoleAccessCreateValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessCreateValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessReadValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessReadValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessReadValue[]' id='leafRoleAccessReadValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessReadValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessUpdateValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessUpdateValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessUpdateValue[]' id='leafRoleAccessUpdateValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessUpdateValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessDeleteValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessDeleteValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessDeleteValue[]' id='leafRoleAccessDeleteValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessDeleteValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessReviewValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessReviewValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessReviewValue[]' id='leafRoleAccessReviewValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessReviewValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessApprovedValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessApprovedValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessApprovedValue[]' id='leafRoleAccessApprovedValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessApprovedValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessPostValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessPostValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessPostValue[]' id='leafRoleAccessPostValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessPostValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafRoleAccessArray[$i]['leafRoleAccessPrintValue'])) {
                                                        if ($leafRoleAccessArray[$i]['leafRoleAccessPrintValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " type=\"checkbox\" name='leafRoleAccessPrintValue[]' id='leafRoleAccessPrintValue' value='" . $leafRoleAccessArray[$i]['leafRoleAccessPrintValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    echo "<td vAlign=\"top\" align=\"center\">
    <input style='display:none;' type=\"checkbox\" name='leafRoleAccessId[]' id='leafRoleAccessId' value='" . $leafRoleAccessArray[$i]['leafRoleAccessId'] . "'>
    
</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="16" vAlign="top" align="center"><?php
                                                        $leafRoleAccess->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="16" vAlign="top" align="center"><?php
                                                    $leafRoleAccess->exceptionMessage(
                                                            $t['recordNotFoundLabel']
                                                    );
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="16" vAlign="top" align="center"><?php
                                                $leafRoleAccess->exceptionMessage(
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
            </div>
        </form>
        <?php
    }
}
?>

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
    function toggleChecked(status) {
        $('input:checkbox').each(function() {
            $(this).attr('checked', status);
        });
    }
</script>
<script type='text/javascript' src='./v3/system/security/javascript/leafRoleAccess.js'></script>
