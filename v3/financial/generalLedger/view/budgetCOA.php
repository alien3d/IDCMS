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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/budgetController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
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
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();

$translator->setCurrentTable(array('budget', 'chartOfAccount'));

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
$budgetArray = array();
$chartOfAccountArray = array();
$chartOfAccountCategoryArray = array();
$chartOfAccountTypeArray = array();
$financeYearArray = array();
$_POST['from'] = 'budgetCOA.php';
$_GET['from'] = 'budgetCOA.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $budget = new \Core\Financial\GeneralLedger\Budget\Controller\BudgetClass();
        $budget->setServiceOutput('html');
        $budget->setLeafId($leafId);
        $budget->execute();
        $chartOfAccountArray = $budget->getChartOfAccount();
        $chartOfAccountCategoryArray = $budget->getChartOfAccountCategory();
        $chartOfAccountTypeArray = $budget->getChartOfAccountType();
        $financeYearArray = $budget->getFinanceYear();
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
        <div class="modal fade" id="budgetPreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="false">&times;</button>
                        <h4 class="modal-title"><?php echo $t['budgetRecordMessageLabel']; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div id="previewMiniTransaction">
                        </div>
                        <div id="previewSimple">
                            <form class="form-horizontal">
                                <input type="hidden" name="budgetIdPreview" id="budgetIdPreview">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="chartOfAccountIdDiv">
                                            <label for="chartOfAccountIdSimplePreview"
                                                   class="control-label col-sm-6"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="chartOfAccountIdSimplePreview"
                                                       id="chartOfAccountIdSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="financeYearIdDiv">
                                            <label for="financeYearIdSimplePreview"
                                                   class="control-label col-sm-6"><?php echo $leafTranslation['financeYearIdLabel']; ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="financeYearIdSimplePreview"
                                                       id="financeYearIdSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthOneDiv">
                                            <label for="budgetTargetMonthOneSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['januaryTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="budgetTargetMonthOneSimplePreview"
                                                       id="budgetTargetMonthOneSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthTwoDiv">
                                            <label for="budgetTargetMonthTwoSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['februaryTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="budgetTargetMonthTwoSimplePreview"
                                                       id="budgetTargetMonthTwoSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthThreeDiv">
                                            <label for="budgetTargetMonthThreeSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['marchTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthThreeSimplePreview" id="budgetTargetMonthThreeSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthFourthDiv">
                                            <label for="budgetTargetMonthFourthSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['aprilTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFourthSimplePreview" id="budgetTargetMonthFourthSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthFifthDiv">
                                            <label for="budgetTargetMonthFifthSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['mayTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFifthSimplePreview" id="budgetTargetMonthFifthSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthSixDiv">
                                            <label for="budgetTargetMonthSixSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['junTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="budgetTargetMonthSixSimplePreview"
                                                       id="budgetTargetMonthSixSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthSevenDiv">
                                            <label for="budgetTargetMonthSevenSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['julyTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSevenSimplePreview" id="budgetTargetMonthSevenSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthEightDiv">
                                            <label for="budgetTargetMonthEightSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['augustTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthEightSimplePreview" id="budgetTargetMonthEightSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthNineDiv">
                                            <label for="budgetTargetMonthNineSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['septemberTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="budgetTargetMonthNineSimplePreview"
                                                       id="budgetTargetMonthNineSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthTenDiv">
                                            <label for="budgetTargetMonthTenSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['octoberTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" name="budgetTargetMonthTenSimplePreview"
                                                       id="budgetTargetMonthTenSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthElevenDiv">
                                            <label for="budgetTargetMonthElevenSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['novemberTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthElevenSimplePreview" id="budgetTargetMonthElevenSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthTwelveDiv">
                                            <label for="budgetTargetMonthTwelveSimplePreview" class="control-label col-sm-6"><?php
                                                echo ucfirst(
                                                        $t['decemberTextLabel']
                                                );
                                                ?></label>

                                            <div class="col-sm-6">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthTwelveSimplePreview" id="budgetTargetMonthTwelveSimplePreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="previewDetail">
                            <form class="form-horizontal">
                                <input type="hidden" name="budgetIdPreview" id="budgetIdPreview">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="chartOfAccountIdDiv">
                                            <label for="chartOfAccountIdDetailPreview"
                                                   class="control-label col-sm-4"><?php echo $leafTranslation['chartOfAccountIdLabel']; ?></label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="chartOfAccountIdDetailPreview"
                                                       id="chartOfAccountIdDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="financeYearIdDiv">
                                            <label for="financeYearIdDetailPreview"
                                                   class="control-label col-sm-4"><?php echo $leafTranslation['financeYearIdLabel']; ?></label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="financeYearIdDetailPreview"
                                                       id="financeYearIdDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthOneDiv">
                                            <label for="budgetTargetMonthOneDetailPreview"
                                                   class="control-label col-sm-4">1. </label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthOneDetailPreview"
                                                       id="budgetTargetMonthOneDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthTwoDiv">
                                            <label for="budgetTargetMonthTwoDetailPreview"
                                                   class="control-label col-sm-4">2.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthTwoDetailPreview"
                                                       id="budgetTargetMonthTwoDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthThreeDiv">
                                            <label for="budgetTargetMonthThreeDetailPreview"
                                                   class="control-label col-sm-4">3.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthThreeDetailPreview" id="budgetTargetMonthThreeDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthFourthDiv">
                                            <label for="budgetTargetMonthFourthDetailPreview"
                                                   class="control-label col-sm-4">4.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFourthDetailPreview" id="budgetTargetMonthFourthDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthFifthDiv">
                                            <label for="budgetTargetMonthFifthDetailPreview"
                                                   class="control-label col-sm-4">5.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFifthDetailPreview" id="budgetTargetMonthFifthDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthSixDiv">
                                            <label for="budgetTargetMonthSixDetailPreview"
                                                   class="control-label col-sm-4">6.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthSixDetailPreview"
                                                       id="budgetTargetMonthSixDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthSevenDetailDiv">
                                            <label for="budgetTargetMonthSevenDetailPreview"
                                                   class="control-label col-sm-4">7.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSevenDetailPreview" id="budgetTargetMonthSevenDetailPreview">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthEightDiv">
                                            <label for="budgetTargetMonthEightDetailPreview"
                                                   class="control-label col-sm-4">8.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthEightDetailPreview" id="budgetTargetMonthEightDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthNineDiv">
                                            <label for="budgetTargetMonthNineDetailPreview"
                                                   class="control-label col-sm-4">9.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthNineDetailPreview"
                                                       id="budgetTargetMonthNineDetailPreview">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthTenDiv">
                                            <label for="budgetTargetMonthTenDetailPreview"
                                                   class="control-label col-sm-4">10.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="budgetTargetMonthTenDetailPreview"
                                                       id="budgetTargetMonthTenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthElevenDiv">
                                            <label for="budgetTargetMonthElevenDetailPreview"
                                                   class="control-label col-sm-4">11.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthElevenDetailPreview" id="budgetTargetMonthElevenDetailPreview">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthTwelveDiv">
                                            <label for="budgetTargetMonthTwelveDetailPreview"
                                                   class="control-label col-sm-4">12.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthTwelveDetailPreview" id="budgetTargetMonthTwelveDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthThirteenDiv">
                                            <label for="budgetTargetMonthThirteenDetailPreview"
                                                   class="control-label col-sm-4">13.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthThirteenDetailPreview"
                                                       id="budgetTargetMonthThirteenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthFourteenDiv">
                                            <label for="budgetTargetMonthFourteenDetailPreview"
                                                   class="control-label col-sm-4">14.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFourteenDetailPreview"
                                                       id="budgetTargetMonthFourteenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthFifteenDiv">
                                            <label for="budgetTargetMonthFifteenDetailPreview"
                                                   class="control-label col-sm-4">15.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthFifteenDetailPreview" id="budgetTargetMonthFifteenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthSixteenDiv">
                                            <label for="budgetTargetMonthSixteenDetailPreview"
                                                   class="control-label col-sm-4">16.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSixteenDetailPreview" id="budgetTargetMonthSixteenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthSeventeenDiv">
                                            <label for="budgetTargetMonthSeventeenDetailPreview"
                                                   class="control-label col-sm-4">17.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthSeventeenDetailPreview"
                                                       id="budgetTargetMonthSeventeenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" id="budgetTargetMonthEighteenDiv">
                                            <label for="budgetTargetMonthEighteenDetailPreview"
                                                   class="control-label col-sm-4">18.</label>

                                            <div class="col-sm-8">
                                                <input class="form-control" type="text"
                                                       name="budgetTargetMonthEighteenDetailPreview"
                                                       id="budgetTargetMonthEighteenDetailPreview">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"  name="showSimpleButtonHeader" id="showSimpleButtonHeader" class="btn btn-info"
                                onClick="toggleBudget(1);"><?php echo $t['previewSimpleBudgetTextlabel']; ?></button>
                        &nbsp;
                        <button type="button"  name="showDetailButtonHeader" id="showDetailButtonHeader" class="btn btn-info"
                                onClick="toggleBudget(2);"><?php echo $t['previewDetailBudgetTextlabel']; ?></button>
                        &nbsp;
                        <button type="button"  name="showDetailButtonHeader" id="showDetailButtonHeader" class="btn btn-info"
                                onClick="toggleBudget(3);"><?php echo $t['previewMiniTransactionTextlabel']; ?></button>

                    </div>
                </div>
            </div>
        </div>

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
                <div class="panel-heading">
                    <?php echo $t['filterBudgetTextLabel']; ?>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="financeYearForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                            echo ucfirst(
                                                    $leafTranslation['financeYearIdLabel']
                                            );
                                            ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <select name="financeYearId" id="financeYearId" class="chzn-select form-control"
                                                onChange="getFinancePeriodRange(<?php echo $leafId; ?>, '<?php
                                                echo $budget->getControllerPath(
                                                );
                                                ?>', '<?php echo $securityToken; ?>');">
                                            <option value=""></option>
                                            <?php
                                            if (is_array($financeYearArray)) {
                                                $totalRecord = intval(count($financeYearArray));
                                                if ($totalRecord > 0) {
                                                    $d = 1;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        if (isset($budgetArray[0]['financeYearId'])) {
                                                            if ($budgetArray[0]['financeYearId'] == $financeYearArray[$i]['financeYearId']) {
                                                                $selected = "selected";
                                                            } else {
                                                                $selected = null;
                                                            }
                                                        } else {
                                                            $selected = null;
                                                        }
                                                        ?>
                                                        <option
                                                            value="<?php echo $financeYearArray[$i]['financeYearId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                            . <?php echo $financeYearArray[$i]['financeYearYear']; ?></option>
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
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="financePeriodRangeIdForm"><label
                                        class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                echo ucfirst(
                                                        $t['periodTextLabel']
                                                );
                                                ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <select name="financePeriodRangeId" id="financePeriodRangeId"
                                                class="chzn-select form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="chartOfAccountCategoryIdForm">
                                    <label for="chartOfAccountCategoryId"
                                           class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['chartOfAccountCategoryIdLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <select name="chartOfAccountCategoryId" id="chartOfAccountCategoryId"
                                                class="chzn-select form-control"
                                                onChange="getChartOfAccountType(<?php echo $leafId; ?>, '<?php
                                                echo $budget->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>');">
                                            <option value=""></option>
                                            <?php
                                            if (is_array($chartOfAccountCategoryArray)) {
                                                $totalRecord = intval(count($chartOfAccountCategoryArray));
                                                if ($totalRecord > 0) {
                                                    $d = 1;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        ?>
                                                        <option
                                                            value="<?php echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryId']; ?>"><?php echo $d; ?>
                                                            . <?php echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle']; ?></option>
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
                                        </select> <span class="help-block" id="chartOfAccountCategoryIdHelpMe"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="chartOfAccountTypeIdForm">
                                    <label for="chartOfAccountTypeId" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                            echo ucfirst(
                                                    $leafTranslation['chartOfAccountTypeIdLabel']
                                            );
                                            ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <select name="chartOfAccountTypeId" id="chartOfAccountTypeId"
                                                class="chzn-select form-control">
                                            <option value=""></option>
                                            <?php
                                            if (is_array($chartOfAccountTypeArray)) {
                                                $totalRecord = intval(count($chartOfAccountTypeArray));
                                                $chartOfAccountCategoryTitle = null;
                                                if ($totalRecord > 0) {
                                                    $d = 0;
                                                    $c = 1;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        if ($d != 0) {
                                                            if ($chartOfAccountCategoryTitle != $chartOfAccountTypeArray[$i]['chartOfAccountCategoryTitle']) {
                                                                $c = 1;
                                                                echo "</optgroup><optgroup label=\"" . $chartOfAccountTypeArray[$i]['chartOfAccountCategoryTitle'] . "\">";
                                                            }
                                                        } else {
                                                            echo "<optgroup label=\"" . $chartOfAccountTypeArray[$i]['chartOfAccountCategoryTitle'] . "\">";
                                                        }
                                                        $chartOfAccountCategoryTitle = $chartOfAccountTypeArray[$i]['chartOfAccountCategoryTitle'];
                                                        ?>
                                                        <option
                                                            value="<?php echo $chartOfAccountTypeArray[$i]['chartOfAccountTypeId']; ?>"><?php echo $c; ?>
                                                            . <?php echo $chartOfAccountTypeArray[$i]['chartOfAccountTypeDescription']; ?></option>
                                                        <?php
                                                        $d++;
                                                        $c++;
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
                                        </select> <span class="help-block" id="chartOfAccountTypeIdHelpMe"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <button name="filterBudget" id="filterBudget" class="btn btn-info"
                                    onClick="filterBudgetList(<?php echo $leafId; ?>, '<?php
                                    echo $budget->getControllerPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>');"><?php echo $t['filterTextLabel']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;</div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-default">
                        <table id="budgetCOATable" class="table table-bordered table-striped table-condensed table-hover">
                            <th width="25px" align="center"><div align="center">#</div></th>
                            <th width="200px"><div align="center"><?php echo $leafTranslation['chartOfAccountNumberLabel']; ?></div>
                            </th>
                            <th><?php echo $leafTranslation['chartOfAccountTitleLabel']; ?></th>
                            <th width="200px">
                            <div align="center"><?php echo $t['figureTextLabel']; ?></div>
                            </th>
                            <tbody id="tbody">
                                <tr class="warning">
                                    <td colspan="4" align="center"><?php echo $t['chooseFilterTextLabel']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            showMeDiv('previewDetail', 0);
            showMeDiv('previewMiniTransaction', 0);
        });
    </script>
</div>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/budgetCoa.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>