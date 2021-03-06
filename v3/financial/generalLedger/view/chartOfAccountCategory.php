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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/chartOfAccountCategoryController.php");
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

$translator->setCurrentTable('chartOfAccountCategory');

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
$chartOfAccountCategoryArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $chartOfAccountCategory = new \Core\Financial\GeneralLedger\ChartOfAccountCategory\Controller\ChartOfAccountCategoryClass( );
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
            $chartOfAccountCategory->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $chartOfAccountCategory->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $chartOfAccountCategory->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $chartOfAccountCategory->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $chartOfAccountCategory->setStartDay($start[2]);
            $chartOfAccountCategory->setStartMonth($start[1]);
            $chartOfAccountCategory->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $chartOfAccountCategory->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $chartOfAccountCategory->setEndDay($start[2]);
            $chartOfAccountCategory->setEndMonth($start[1]);
            $chartOfAccountCategory->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $chartOfAccountCategory->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $chartOfAccountCategory->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $chartOfAccountCategory->setServiceOutput('html');
        $chartOfAccountCategory->setLeafId($leafId);
        $chartOfAccountCategory->execute();
        if ($_POST['method'] == 'read') {
            $chartOfAccountCategory->setStart($offset);
            $chartOfAccountCategory->setLimit($limit); // normal system don't like paging..
            $chartOfAccountCategory->setPageOutput('html');
            $chartOfAccountCategoryArray = $chartOfAccountCategory->read();
            if (isset($chartOfAccountCategoryArray [0]['firstRecord'])) {
                $firstRecord = $chartOfAccountCategoryArray [0]['firstRecord'];
            }
            if (isset($chartOfAccountCategoryArray [0]['nextRecord'])) {
                $nextRecord = $chartOfAccountCategoryArray [0]['nextRecord'];
            }
            if (isset($chartOfAccountCategoryArray [0]['previousRecord'])) {
                $previousRecord = $chartOfAccountCategoryArray [0]['previousRecord'];
            }
            if (isset($chartOfAccountCategoryArray [0]['lastRecord'])) {
                $lastRecord = $chartOfAccountCategoryArray [0]['lastRecord'];
                $endRecord = $chartOfAccountCategoryArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($chartOfAccountCategory->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($chartOfAccountCategoryArray [0]['total'])) {
                $total = $chartOfAccountCategoryArray [0]['total'];
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
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'A');">
                        A
                    </button>
                    <button title="B" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'B');">
                        B
                    </button>
                    <button title="C" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'C');">
                        C
                    </button>
                    <button title="D" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'D');">
                        D
                    </button>
                    <button title="E" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'E');">
                        E
                    </button>
                    <button title="F" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'F');">
                        F
                    </button>
                    <button title="G" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'G');">
                        G
                    </button>
                    <button title="H" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'H');">
                        H
                    </button>
                    <button title="I" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'I');">
                        I
                    </button>
                    <button title="J" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'J');">
                        J
                    </button>
                    <button title="K" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'K');">
                        K
                    </button>
                    <button title="L" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'L');">
                        L
                    </button>
                    <button title="M" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'M');">
                        M
                    </button>
                    <button title="N" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'N');">
                        N
                    </button>
                    <button title="O" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'O');">
                        O
                    </button>
                    <button title="P" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'P');">
                        P
                    </button>
                    <button title="Q" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Q');">
                        Q
                    </button>
                    <button title="R" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'R');">
                        R
                    </button>
                    <button title="S" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'S');">
                        S
                    </button>
                    <button title="T" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'T');">
                        T
                    </button>
                    <button title="U" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'U');">
                        U
                    </button>
                    <button title="V" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'V');">
                        V
                    </button>
                    <button title="W" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'W');">
                        W
                    </button>
                    <button title="X" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'X');">
                        X
                    </button>
                    <button title="Y" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Y');">
                        Y
                    </button>
                    <button title="Z" class="btn btn-success btn-sm" type="button" 
                            onClick="ajaxQuerySearchAllCharacter(<?php echo $leafId; ?>, '<?php
                            echo $chartOfAccountCategory->getViewPath();
                            ?>', '<?php echo $securityToken; ?>', 'Z');">
                        Z
                    </button>
                </div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-left" align="left">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button" >
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" >
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountCategory->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $chartOfAccountCategory->getControllerPath();
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
                                        echo $chartOfAccountCategory->getViewPath();
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
                                   onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountCategory->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchString" id="clearSearchString"
                                   value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info btn-block"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountCategory->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);">
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
                                        <input type="hidden" name="chartOfAccountCategoryIdPreview"
                                               id="chartOfAccountCategoryIdPreview">
                                        <div class="form-group" id="chartOfAccountReportTypeIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4" for="chartOfAccountReportTypeIdPreview"><?php echo $leafTranslation['chartOfAccountReportTypeIdLabel']; ?></label>
                                            <div class=" col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                                <input class="form-control" type="text" name="chartOfAccountReportTypeIdPreview" id="chartOfAccountReportTypeIdPreview">
                                            </div>					</div>	
                                        <div class="form-group" id="chartOfAccountCategoryCodeDiv">
                                            <label for="chartOfAccountCategoryCodePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountCategoryCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountCategoryCodePreview"
                                                       id="chartOfAccountCategoryCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountCategoryTitleDiv">
                                            <label for="chartOfAccountCategoryTitlePreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountCategoryTitleLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="chartOfAccountCategoryTitlePreview"
                                                       id="chartOfAccountCategoryTitlePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="chartOfAccountCategoryDescriptionDiv">
                                            <label for="chartOfAccountCategoryDescriptionPreview"
                                                   class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['chartOfAccountCategoryDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <textarea rows="5" class="form-control" type="text"
                                                          name="chartOfAccountCategoryDescriptionPreview"
                                                          id="chartOfAccountCategoryDescriptionPreview"></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onClick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                            echo $chartOfAccountCategory->getControllerPath();
                                            ?>', '<?php
                                            echo $chartOfAccountCategory->getViewPath();
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
                                    <th width="100px">
                                    <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php
                                        echo ucwords(
                                                $leafTranslation['chartOfAccountCategoryCodeLabel']
                                        );
                                        ?></div>
                                    </th>
                                    <th width="100px"><?php
                                        echo ucwords(
                                                $leafTranslation['chartOfAccountCategoryTitleLabel']
                                        );
                                        ?></th>
                                    <th><?php echo ucwords($leafTranslation['chartOfAccountCategoryDescriptionLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                               onChange="toggleChecked(this.checked);">
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($chartOfAccountCategoryArray)) {
                                                $totalRecord = intval(count($chartOfAccountCategoryArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($chartOfAccountCategoryArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($chartOfAccountCategoryArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                            </td>
                                                            <td align="center">
                                                                <?php if ($chartOfAccountCategoryArray [$i]['chartOfAccountCategoryCode'] != 'UNBL') { ?>
                                                                    <div class="btn-group" align="center">
                                                                        <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                                onClick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                                echo $chartOfAccountCategory->getControllerPath();
                                                                                ?>', '<?php
                                                                                echo $chartOfAccountCategory->getViewPath();
                                                                                ?>', '<?php echo $securityToken; ?>', '<?php
                                                                                echo intval(
                                                                                        $chartOfAccountCategoryArray [$i]['chartOfAccountCategoryId']
                                                                                );
                                                                                ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                            <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                onClick="showModalDelete('<?php
                                                                                echo rawurlencode(
                                                                                        $chartOfAccountCategoryArray [$i]['chartOfAccountCategoryId']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $chartOfAccountCategoryArray [$i]['chartOfAccountReportTypeDescription']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $chartOfAccountCategoryArray [$i]['chartOfAccountCategoryCode']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $chartOfAccountCategoryArray [$i]['chartOfAccountCategoryTitle']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $chartOfAccountCategoryArray [$i]['chartOfAccountCategoryDescription']
                                                                                );
                                                                                ?>');">
                                                                            <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                                    </div>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode']
                                                                                    );
                                                                                } else {
                                                                                    echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen(
                                                                                                $_POST['character']
                                                                                        ) > 0
                                                                                ) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryCode'];
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
                                                                    if (isset($chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle']
                                                                                    );
                                                                                } else {
                                                                                    echo substr(
                                                                                            $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle'], 0, 100
                                                                                    );
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen(
                                                                                                $_POST['character']
                                                                                        ) > 0
                                                                                ) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo substr(
                                                                                    $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryTitle'], 0, 100
                                                                            );
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
                                                                    if (isset($chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower(
                                                                                                        $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription']
                                                                                                ), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo substr(
                                                                                            $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription'], 0, 50
                                                                                    ) . "..";
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen(
                                                                                                $_POST['character']
                                                                                        ) > 0
                                                                                ) {
                                                                                    if (strpos(
                                                                                                    strtolower(
                                                                                                            $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription']
                                                                                                    ), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo substr(
                                                                                    $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryDescription'], 0, 50
                                                                            ) . "..";
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>

                                                            <?php
                                                            if ($chartOfAccountCategoryArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <?php if ($chartOfAccountCategoryArray [$i]['chartOfAccountCategoryCode'] != 'UNBL') { ?>
                                                                    <input style="display:none;" type="checkbox" name="chartOfAccountCategoryId[]"
                                                                           value="<?php echo $chartOfAccountCategoryArray[$i]['chartOfAccountCategoryId']; ?>">
                                                                    <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                                   value="<?php echo $chartOfAccountCategoryArray[$i]['isDelete']; ?>">
                                                                                               <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" vAlign="top" align="center"><?php
                                                            $chartOfAccountCategory->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" vAlign="top" align="center"><?php
                                                        $chartOfAccountCategory->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" vAlign="top" align="center"><?php
                                                    $chartOfAccountCategory->exceptionMessage(
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
                                        echo $chartOfAccountCategory->getControllerPath();
                                        ?>', '<?php
                                        echo $chartOfAccountCategory->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>');">
                                    <i class="glyphicon glyphicon-white glyphicon-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

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
                                       echo $chartOfAccountCategory->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountCategory->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="previousRecord(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountCategory->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountCategory->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                       onClick="nextRecord(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountCategory->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountCategory->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-forward glyphicon-white"></i><?php echo $t['nextButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                       onClick="endRecord(<?php echo $leafId; ?>, '<?php
                                       echo $chartOfAccountCategory->getControllerPath();
                                       ?>', '<?php
                                       echo $chartOfAccountCategory->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" name="chartOfAccountCategoryId" id="chartOfAccountCategoryId" value="<?php
                            if (isset($_POST['chartOfAccountCategoryId'])) {
                                echo $_POST['chartOfAccountCategoryId'];
                            }
                            ?>">

                            <div class="row">
                                <div class="form-group col-md-6" id="chartOfAccountCategoryCodeForm">
                                    <label for="chartOfAccountCategoryCode" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                            echo ucfirst(
                                                    $leafTranslation['chartOfAccountCategoryCodeLabel']
                                            );
                                            ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="chartOfAccountCategoryCode"
                                                   id="chartOfAccountCategoryCode" value="<?php
                                                   if (isset($chartOfAccountCategoryArray) && is_array($chartOfAccountCategoryArray)) {
                                                       if (isset($chartOfAccountCategoryArray[0]['chartOfAccountCategoryCode'])) {
                                                           echo htmlentities(
                                                                   $chartOfAccountCategoryArray[0]['chartOfAccountCategoryCode']
                                                           );
                                                       }
                                                   }
                                                   ?>" maxlength="16">
                                            <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                        <span class="help-block" id="chartOfAccountCategoryCodeHelpMe"></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6" id="chartOfAccountCategoryTitleForm">
                                    <label for="chartOfAccountCategoryTitle" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                            echo ucfirst(
                                                    $leafTranslation['chartOfAccountCategoryTitleLabel']
                                            );
                                            ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input class="form-control" type="text" name="chartOfAccountCategoryTitle"
                                               id="chartOfAccountCategoryTitle" value="<?php
                                               if (isset($chartOfAccountCategoryArray) && is_array($chartOfAccountCategoryArray)) {
                                                   if (isset($chartOfAccountCategoryArray[0]['chartOfAccountCategoryTitle'])) {
                                                       echo htmlentities(
                                                               $chartOfAccountCategoryArray[0]['chartOfAccountCategoryTitle']
                                                       );
                                                   }
                                               }
                                               ?>"> <span class="help-block" id="chartOfAccountCategoryTitleHelpMe"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="chartOfAccountCategoryDescriptionForm">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <textarea class="form-control" rows="5" name="chartOfAccountCategoryDescription"
                                                      id="chartOfAccountCategoryDescription"><?php
                                                          if (isset($chartOfAccountCategoryArray[0]['chartOfAccountCategoryDescription'])) {
                                                              echo htmlentities(
                                                                      $chartOfAccountCategoryArray[0]['chartOfAccountCategoryDescription']
                                                              );
                                                          }
                                                          ?></textarea>
                                            <span class="help-block" id="chartOfAccountCategoryDescriptionHelpMe"></span>
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
                                        </a>
                                    </li>
                                    <li>
                                        <a id="newRecordButton4" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?>
                                        </a>
                                    </li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                           ?></a></li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                           ?></a></li>-->
                                    <li>
                                        <a id="newRecordButton7" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?>
                                        </a>
                                    </li>
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
                                        </a>
                                    </li>
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                            ?></a></li> -->
                                    <li>
                                        <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                        </a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled">
                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountCategory->getControllerPath();
                                   ?>', '<?php
                                   echo $chartOfAccountCategory->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                        class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                </a>
                            </div>
                            <div class="btn-group">
                                <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $chartOfAccountCategory->getViewPath();
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
            <script type="text/javascript">
                $(document).keypress(function(e) {

                    // shift+n new record event
                    if (e.which === 78 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            newRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountCategory->getControllerPath(); ?>', '<?php echo $chartOfAccountCategory->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);
    <?php } ?>
                        return false;
                    }
                    // shift+s save event
                    if (e.which === 83 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                            updateRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountCategory->getControllerPath(); ?>', '<?php echo $chartOfAccountCategory->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                        return false;
                    }
                    // shift+d delete event
                    if (e.which === 88 && e.which === 18 && e.shiftKey) {


    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                            deleteRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountCategory->getControllerPath(); ?>', '<?php echo $chartOfAccountCategory->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                            return false;
    <?php } ?>
                    }
                    // shift+f.find event
                    if (e.which === 18 && e.shiftKey) {
                        findRecord();

                        return false;
                    }
                    switch (e.keyCode) {
                        case 37:
                            previousRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountCategory->getControllerPath(); ?>', '<?php echo $chartOfAccountCategory->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                            return false;
                            break;
                        case 39:
                            nextRecord(<?php echo $leafId; ?>, '<?php echo $chartOfAccountCategory->getControllerPath(); ?>', '<?php echo $chartOfAccountCategory->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                            return false;
                            break;
                    }


                });
                $(document).ready(function() {
                    window.scrollTo(0, 0);
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('chartOfAccountCategoryId');
                    validateMeAlphaNumeric('chartOfAccountCategoryCode');
                    validateMeAlphaNumeric('chartOfAccountCategoryTitle');
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['chartOfAccountCategoryId']) {
            ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                            $('#newRecordButton3').attr('onClick', '');
                            $('#newRecordButton4').attr('onClick', '');
                            $('#newRecordButton5').attr('onClick', '');
                            $('#newRecordButton6').attr('onClick', '');
                            $('#newRecordButton7').attr('onClick', '');

            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                $('#updateRecordButton3').attr('onClick', '');
                                $('#updateRecordButton4').attr('onClick', '');
                                $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $chartOfAccountCategory->getControllerPath(); ?>','<?php echo $chartOfAccountCategory->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
                });
            </script>
        </div>
    </form><?php } ?>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/chartOfAccountCategory.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>