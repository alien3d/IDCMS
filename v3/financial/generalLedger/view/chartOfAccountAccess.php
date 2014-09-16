<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/chartOfAccountAccessController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();

$translator->setCurrentTable('chartOfAccountAccess');

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
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $chartOfAccountAccess = new \Core\Financial\GeneralLedger\ChartOfAccountAccess\Controller\ChartOfAccountAccessClass( );
        define("LIMIT", 10);
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
        $chartOfAccountAccess->setServiceOutput('html');
        $chartOfAccountAccess->setLeafId($leafId);
        $chartOfAccountAccess->execute();
        $chartOfAccountArray = $chartOfAccountAccess->getChartOfAccount();
        $staffArray = $chartOfAccountAccess->getStaff();
        if ($_POST['method'] == 'read') {
            $chartOfAccountAccess->setStart($offset);
            $chartOfAccountAccess->setLimit($limit); // normal system don't like paging..
            $chartOfAccountAccess->setPageOutput('html');
            $chartOfAccountAccessArray = $chartOfAccountAccess->read();
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
                <div class="panel panel-info">
                    <div class="panel-body">
                        <div class="form-group" id="chartOfAccountIdForm">
                            <label for="chartOfAccountId" class="control-label col-xs-2 col-sm-2 col-md-2"><strong><?php
                                    echo ucfirst(
                                            $leafTranslation['chartOfAccountIdLabel']
                                    );
                                    ?></strong></label>

                            <div class="col-xs-10 col-sm-10 col-md-10">
                                <select name="chartOfAccountId" id="chartOfAccountId" class="chzn-select form-control">
                                    <option value=""></option>
                                    <?php
                                    if (is_array($chartOfAccountArray)) {
                                        $d = 0;
                                        $currentChartOfAccountTypeDescription = null;
                                        $totalRecord = intval(count($chartOfAccountArray));
                                        if ($totalRecord > 0) {

                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                $d++;
                                                if ($i != 0) {
                                                    if ($currentChartOfAccountTypeDescription != $chartOfAccountArray[$i]['chartOfAccountTypeDescription']) {
                                                        echo "</optgroup><optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                    }
                                                } else {
                                                    echo "<optgroup label=\"" . $chartOfAccountArray[$i]['chartOfAccountTypeDescription'] . "\">";
                                                }
                                                $currentChartOfAccountTypeDescription = $chartOfAccountArray[$i]['chartOfAccountTypeDescription'];
                                                if (isset($chartOfAccountAccessArray[0]['chartOfAccountId'])) {
                                                    if ($chartOfAccountAccessArray[0]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                                        $selected = "selected";
                                                    } else {
                                                        $selected = null;
                                                    }
                                                } else {
                                                    $selected = null;
                                                }
                                                ?>
                                                <option
                                                    value="<?php echo $chartOfAccountArray[$i]['chartOfAccountId']; ?>" <?php echo $selected; ?>><?php echo $chartOfAccountArray[$i]['chartOfAccountNumber']; ?>
                                                    - <?php echo $chartOfAccountArray[$i]['chartOfAccountTitle']; ?></option>
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
                                </select> <span class="help-block" id="chartOfAccountIdHelpMe"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="staffIdTemp"
                                   class="control-label col-xs-2 col-sm-2 col-md-2"><?php
                                       echo ucfirst(
                                               $leafTranslation['staffIdLabel']
                                       );
                                       ?></label>

                            <div class="col-xs-10 col-sm-10 col-md-10">
                                <select name="staffIdTemp" id="staffIdTemp" class="chzn-select form-control"
                                        onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                        echo $chartOfAccountAccess->getControllerPath();
                                        ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>);">
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
                                        echo $chartOfAccountAccess->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>);">
                                    <?php echo $t['clearButtonLabel']; ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="pull-right">
                            <button class="delete btn btn-warning" type="button" 
                                    onClick="updateGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountAccess->getControllerPath();
                                    ?>', '<?php echo $chartOfAccountAccess->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
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
                            <table class="table table-bordered table-striped table-condensed table-hover" id="tableData">
                                <thead>
                                    <tr>
                                        <th width="25px" align="center">
                                <div align="center">#</div>
                                </th>
                                <th><?php echo ucfirst($leafTranslation['chartOfAccountIdLabel']); ?></th>
                                <th width="150px"><?php echo ucfirst($leafTranslation['staffIdLabel']); ?></th>
                                <th width="100px"><?php echo ucfirst($leafTranslation['chartOfAccountAccessValueLabel']); ?></th>
                                <th>
                                    <input type="checkbox" name="check_all" id="check_all"
                                           alt="Check Record" onChange="toggleChecked(this.checked);">
                                </th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($chartOfAccountAccessArray)) {
                                            $totalRecord = intval(count($chartOfAccountAccessArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td align=\"center\"><div align=\"center\">" . ($counter + $offset) . ". </div></td>";
                                                    if (isset($chartOfAccountAccessArray[$i]['chartOfAccountTitle'])) {
                                                        echo "<td align=right>" . $chartOfAccountAccessArray[$i]['chartOfAccountTitle'] . "</td>";
                                                    } else {
                                                        echo "<td  align=right><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($chartOfAccountAccessArray[$i]['staffName'])) {
                                                        echo "<td align=right>" . $chartOfAccountAccessArray[$i]['staffName'] . "</td>";
                                                    } else {
                                                        echo "<td  align=right><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    if (isset($chartOfAccountAccessArray[$i]['chartOfAccountAccessValue'])) {
                                                        if ($chartOfAccountAccessArray[$i]['chartOfAccountAccessValue'] == 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        echo "<td><input " . $checked . " type=\"checkbox\" name='chartOfAccountAccessValue[]' id='chartOfAccountAccessValue' value='" . $chartOfAccountAccessArray[$i]['chartOfAccountAccessValue'] . "'></td>";
                                                    } else {
                                                        echo "<td  align=left><img src='./images/icons/burn.png'></td>";
                                                    }
                                                    echo "<td>
    <input style='display:none;' type=\"checkbox\" name='chartOfAccountAccessId[]' id='chartOfAccountAccessId' value='" . $chartOfAccountAccessArray[$i]['chartOfAccountAccessId'] . "'>
    
</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="6" vAlign="top" align="center"><?php
                                                        $chartOfAccountAccess->exceptionMessage(
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
                                                    $chartOfAccountAccess->exceptionMessage(
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
                                                $chartOfAccountAccess->exceptionMessage(
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



            </div><?php
        }
    }
    ?>

</form>
<script type='text/javascript'>
    $(document).ready(function() {
        window.scrollTo(0, 0);
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({allow_single_deselect: true});
    });

</script>

<script type='text/javascript' src='./v3/financial/generalLedger/javascript/chartOfAccountAccess.js'></script>
