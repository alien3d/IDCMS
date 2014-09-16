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
require_once($newFakeDocumentRoot . "v3/system/security/controller/applicationAccessController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable(array('application', 'applicationAccess'));

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
$applicationAccessArray = array();
$applicationArray = array();
$roleArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $applicationAccess = new \Core\System\Security\ApplicationAccess\Controller\ApplicationAccessClass();
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
            $applicationAccess->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $applicationAccess->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $applicationAccess->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $applicationAccess->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $applicationAccess->setStartDay($start[2]);
            $applicationAccess->setStartMonth($start[1]);
            $applicationAccess->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $applicationAccess->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $applicationAccess->setEndDay($start[2]);
            $applicationAccess->setEndMonth($start[1]);
            $applicationAccess->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $applicationAccess->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $applicationAccess->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $applicationAccess->setServiceOutput('html');
        $applicationAccess->setLeafId($leafId);
        $applicationAccess->execute();
        $applicationArray = $applicationAccess->getApplication();
        $roleArray = $applicationAccess->getRole();
        if ($_POST['method'] == 'read') {
            $applicationAccess->setStart($offset);
            $applicationAccess->setLimit($limit); // normal system don't like paging..
            $applicationAccess->setPageOutput('html');
            $applicationAccessArray = $applicationAccess->read();
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($applicationAccess->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($applicationAccessArray [0]['total'])) {
                $total = $applicationAccessArray [0]['total'];
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
                                                echo $applicationAccess->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, <?php echo LIMIT; ?>, 1);">
                                            <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                                            <?php
                                            if (is_array($applicationArray)) {
                                                $totalRecord = intval(count($applicationArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                                                    <option value="<?php echo $applicationArray[$i]['applicationId']; ?>"><?php echo $d ?>
                                                        . <?php echo $applicationArray[$i]['applicationEnglish']; ?></option>
                                                    <?php
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
                                        <select name="roleId" id="roleId" class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $applicationAccess->getControllerPath();
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
                                        </select></div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info"
                                                onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                                echo $applicationAccess->getViewPath();
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
                                    echo $applicationAccess->getControllerPath();
                                    ?>', '<?php echo $applicationAccess->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                <i class='glyphicon glyphicon-lock'></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" id="infoPanel">
                    <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <table class='table table-bordered table-striped table-condensed table-hover' id='tableData'>
                                <thead>
                                    <tr class="info success">
                                        <th width="25" align="center">
                                <div align="center">#</div>
                                </th>
                                <th width="100"><?php echo $leafTranslation['roleIdLabel']; ?></th>
                                <th width="25"><?php echo $leafTranslation['applicationAccessValueLabel']; ?></th>
                                <th><?php echo ucfirst($leafTranslation['applicationIdLabel']); ?></th>
                                <th width="25">
                                    <input class='form-control' type="checkbox" name='check_all' id='check_all' alt='Check Record'
                                           onChange="toggleChecked(this.checked);">
                                </th>
                                </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($applicationAccessArray)) {
                                            $totalRecord = intval(count($applicationAccessArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                $oldRoleDescription = null;
                                                $roleDescription = null;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    if ($oldRoleDescription != $applicationAccessArray[$i]['roleDescription']) {
                                                        $roleDescription = $applicationAccessArray[$i]['roleDescription'];
                                                    } else {
                                                        $roleDescription = "&nbsp;";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td align=\"center\"><div align=\"center\">" . ($counter + $offset) . "</div></td>";
                                                    if (isset($applicationAccessArray[$i]['roleDescription'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $roleDescription . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"left\">&nbsp;</td>";
                                                    }
                                                    $oldRoleDescription = $applicationAccessArray[$i]['roleDescription'];
                                                    if (isset($applicationAccessArray[$i]['applicationAccessValue'])) {
                                                        if ($applicationAccessArray[$i]['applicationAccessValue'] == 1) {
                                                            echo "<td vAlign=\"top\" align=\"center\"><div align=\"center\"><img src='./images/icons/tick.png'></div></td>";
                                                        } else {
                                                            echo "<td vAlign=\"top\" align=\"center\"><div align=\"center\"><img src='./images/icons/burn.png'></div></td>";
                                                        }
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"left\"><img src='./images/icons/burn.png'></td>";
                                                    }

                                                    if (isset($applicationAccessArray[$i]['applicationEnglish'])) {
                                                        echo "<td vAlign=\"top\" align=\"left\">" . $applicationAccessArray[$i]['applicationEnglish'] . "</td>";
                                                    } else {
                                                        echo "<td vAlign=\"top\" align=\"left\">&nbsp;</td>";
                                                    }

                                                    if ($applicationAccessArray[$i]['applicationAccessValue']) {
                                                        $checked = 'checked';
                                                    } else {
                                                        $checked = null;
                                                    }
                                                    echo "<td>
    <input style='display:none;' type=\"checkbox\" name='applicationAccessId[]' id='applicationAccessId' value='" . $applicationAccessArray[$i]['applicationAccessId'] . "'>
    <input " . $checked . " class='form-control' type=\"checkbox\" name='applicationAccessValue[]' id='applicationAccessValue' value='" . $applicationAccessArray[$i]['applicationAccessValue'] . "'>
    
</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" vAlign="top" align="center"><?php
                                                        $applicationAccess->exceptionMessage(
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
                                                    $applicationAccess->exceptionMessage(
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
                                                $applicationAccess->exceptionMessage(
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

            <?php
        }
    }
    ?>
</form>
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
</script>
<script type='text/javascript' src='./v3/system/security/javascript/applicationAccess.js'></script>
