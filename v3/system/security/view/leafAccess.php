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
require_once($newFakeDocumentRoot . "v3/system/security/controller/leafAccessController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable(array('leaf', 'leafAccess'));

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
$leafAccessArray = array();
$applicationArray = array();
$moduleArray = array();
$folderArray = array();
$leafArray = array();
$staffArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $leafAccess = new \Core\System\Security\LeafAccess\Controller\LeafAccessClass();
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
            $leafAccess->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $leafAccess->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $leafAccess->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $leafAccess->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $leafAccess->setStartDay($start[2]);
            $leafAccess->setStartMonth($start[1]);
            $leafAccess->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $leafAccess->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $leafAccess->setEndDay($start[2]);
            $leafAccess->setEndMonth($start[1]);
            $leafAccess->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $leafAccess->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $leafAccess->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $leafAccess->setServiceOutput('html');
        $leafAccess->setLeafId($leafId);
        $leafAccess->execute();
        $applicationArray = $leafAccess->getApplication();
        $moduleArray = $leafAccess->getModule();
        $folderArray = $leafAccess->getFolder();
        $leafArray = $leafAccess->getLeaf();
        $staffArray = $leafAccess->getStaff();
        if ($_POST['method'] == 'read') {
            $leafAccess->setStart($offset);
            $leafAccess->setLimit($limit); // normal system don't like paging..
            $leafAccess->setPageOutput('html');
            $leafAccessArray = $leafAccess->read();

            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($leafAccess->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($leafAccessArray [0]['total'])) {
                $total = $leafAccessArray [0]['total'];
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
                                    <label for="applicationId" class="control-label col-xs-2 col-sm-2 col-md-2"><?php
                                        echo ucfirst(
                                                $leafTranslation['applicationIdLabel']
                                        );
                                        ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name='applicationId' id='applicationId' class="chzn-select form-control"
                                                style="width:400px" onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'moduleId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'folderId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'leafIdTemp');">
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
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'folderId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
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
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 'leafIdTemp');">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            $currentFolderEnglish = null;
                                            if (is_array($folderArray)) {
                                                $totalRecord = intval(count($folderArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    if ($i != 0) {
                                                        if ($currentFolderEnglish != $leafArray[$i]['folderEnglish']) {
                                                            $group = 1;
                                                            echo "</optgroup><optgroup label=\"" . $leafArray[$i]['folderEnglish'] . "\">";
                                                        }
                                                    } else {
                                                        echo "<optgroup label=\"" . $leafArray[$i]['folderEnglish'] . "\">";
                                                    }
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
                                                echo $leafAccess->getControllerPath();
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

                                                        <option
                                                            value="<?php echo $leafArray[$i]['leafId']; ?>"><?php echo $d; ?> <?php echo $leafArray[$i]['leafEnglish']; ?></option>


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
                                    <label for="staffIdTemp"
                                           class="control-label col-xs-2 col-sm-2 col-md-2"><?php echo ucfirst($leafTranslation['staffIdLabel']); ?></label>

                                    <div class="col-xs-10 col-sm-10 col-md-10">
                                        <select name="staffIdTemp" id="staffIdTemp" class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>);">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($staffArray)) {
                                                $totalRecord = intval(count($staffArray));
                                                $d = 0;
                                                $currentRoleDescription = null;
                                                $group = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    if ($i != 0) {
                                                        if ($currentRoleDescription != $staffArray[$i]['roleDescription']) {
                                                            $group = 1;
                                                            echo "</optgroup><optgroup label=\"" . $staffArray[$i]['roleDescription'] . "\">";
                                                        }
                                                    } else {
                                                        echo "<optgroup label=\"" . $staffArray[$i]['roleDescription'] . "\">";
                                                    }
                                                    $currentRoleDescription = $staffArray[$i]['roleDescription'];
                                                    ?>
                                                    <option value="<?php echo $staffArray[$i]['staffId']; ?>"><?php echo $d; ?>
                                                        . <?php echo $staffArray[$i]['staffName']; ?></option>
                                                    <?php
                                                }
                                                echo "</optgroup>";
                                            }
                                            ?>
                                        </select></div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info"
                                                onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                                echo $leafAccess->getViewPath();
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
                                    echo $leafAccess->getControllerPath();
                                    ?>', '<?php echo $leafAccess->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                <i class='glyphicon glyphicon-lock'></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <table class='table table-bordered table-striped table-condensed table-hover'  id='tableData'>
                                <thead>
                                    <tr>
                                        <th align="center">
                                <div align="center">#</div>
                                </th>
                                <th><?php echo ucfirst($leafTranslation['applicationIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['moduleIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['folderIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['staffIdLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessDraftValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessCreateValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessReadValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessUpdateValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessDeleteValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessReviewValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessApprovedValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessPostValueLabel']); ?></th>
                                <th><?php echo ucfirst($leafTranslation['leafAccessPrintValueLabel']); ?></th>
                                <th>
                                    <input class='check_all' type="checkbox" name='check_all' id='check_all' alt='Check Record'
                                           onChange="toggleChecked(this.checked);">
                                </th>
                                </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($leafAccessArray)) {
                                            $totalRecord = intval(count($leafAccessArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td align=\"center\"><div align=\"center\">" . ($counter + $offset) . ". </div></td>";
                                                    if (isset($leafAccessArray[$i]['applicationEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafAccessArray[$i]['applicationEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafAccessArray[$i]['moduleEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafAccessArray[$i]['moduleEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafAccessArray[$i]['folderEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafAccessArray[$i]['folderEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($leafAccessArray[$i]['leafEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafAccessArray[$i]['leafEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['staffName'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $leafAccessArray[$i]['staffName'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessDraftValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessDraftValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessDraftValue[]' id='leafAccessDraftValue' value='" . $leafAccessArray[$i]['leafAccessDraftValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessCreateValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessCreateValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessCreateValue[]' id='leafAccessCreateValue' value='" . $leafAccessArray[$i]['leafAccessCreateValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessReadValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessReadValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessReadValue[]' id='leafAccessReadValue' value='" . $leafAccessArray[$i]['leafAccessReadValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessUpdateValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessUpdateValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessUpdateValue[]' id='leafAccessUpdateValue' value='" . $leafAccessArray[$i]['leafAccessUpdateValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessDeleteValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessDeleteValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessDeleteValue[]' id='leafAccessDeleteValue' value='" . $leafAccessArray[$i]['leafAccessDeleteValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessReviewValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessReviewValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessReviewValue[]' id='leafAccessReviewValue' value='" . $leafAccessArray[$i]['leafAccessReviewValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessApprovedValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessApprovedValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessApprovedValue[]' id='leafAccessApprovedValue' value='" . $leafAccessArray[$i]['leafAccessApprovedValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessPostValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessPostValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessPostValue[]' id='leafAccessPostValue' value='" . $leafAccessArray[$i]['leafAccessPostValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($leafAccessArray[$i]['leafAccessPrintValue'])) {
                                                        if ($leafAccessArray[$i]['leafAccessPrintValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td vAlign=\"top\" align=\"center\"><input " . $checked . " class='form-control' type=\"checkbox\" name='leafAccessPrintValue[]' id='leafAccessPrintValue' value='" . $leafAccessArray[$i]['leafAccessPrintValue'] . "'></td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"center\"><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    echo "<td>
    <input style='display:none;' type=\"checkbox\" name='leafAccessId[]' id='leafAccessId' value='" . $leafAccessArray[$i]['leafAccessId'] . "'>
    
</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="16" vAlign="top" align="center"><?php
                                                        $leafAccess->exceptionMessage(
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
                                                    $leafAccess->exceptionMessage(
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
                                                $leafAccess->exceptionMessage(
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
        tableHeightSize()
        $(window).resize(function() {
            tableHeightSize()
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
<script type='text/javascript' src='./v3/system/security/javascript/leafAccess.js'></script>
