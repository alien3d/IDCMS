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
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/controller/financeYearController.php");
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

$translator->setCurrentTable('financeYear');

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
$financeYearArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $financeYear = new \Core\Financial\GeneralLedger\FinanceYear\Controller\FinanceYearClass();
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
            $financeYear->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $financeYear->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $financeYear->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $financeYear->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $financeYear->setStartDay($start[2]);
            $financeYear->setStartMonth($start[1]);
            $financeYear->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $financeYear->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $financeYear->setEndDay($start[2]);
            $financeYear->setEndMonth($start[1]);
            $financeYear->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $financeYear->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $financeYear->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $financeYear->setServiceOutput('html');
        $financeYear->setLeafId($leafId);
        $financeYear->execute();
        if ($_POST['method'] == 'read') {
            $financeYear->setStart($offset);
            $financeYear->setLimit($limit); // normal system don't like paging..
            $financeYear->setPageOutput('html');
            $financeYearArray = $financeYear->read();
            if (isset($financeYearArray [0]['firstRecord'])) {
                $firstRecord = $financeYearArray [0]['firstRecord'];
            }
            if (isset($financeYearArray [0]['nextRecord'])) {
                $nextRecord = $financeYearArray [0]['nextRecord'];
            }
            if (isset($financeYearArray [0]['previousRecord'])) {
                $previousRecord = $financeYearArray [0]['previousRecord'];
            }
            if (isset($financeYearArray [0]['lastRecord'])) {
                $lastRecord = $financeYearArray [0]['lastRecord'];
                $endRecord = $financeYearArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($financeYear->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($financeYearArray [0]['total'])) {
                $total = $financeYearArray [0]['total'];
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
                <div class="pull-left btn-group col-xs-10 col-sm-10 col-md-10"></div>
                <div class="control-label col-xs-2 col-sm-2 col-md-2">
                    <div class="pull-right">
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm" type="button" >
                                <i class="glyphicon glyphicon-print glyphicon-white"></i>
                            </button>
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" >
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $financeYear->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'excel');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $financeYear->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'csv');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;CSV&nbsp;&nbsp; </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" onclick="reportRequest(<?php echo $leafId; ?>, '<?php
                                    echo $financeYear->getControllerPath();
                                    ?>', '<?php echo $securityToken; ?>', 'html');">
                                        <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Html&nbsp;&nbsp; </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    &nbsp;
                </div>
            </div>
            <div class="row">
                <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3"> 
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="btnList">
                                <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton"  class="btn btn-info btn-block" onClick="showForm('<?php echo $leafId; ?>', '<?php echo $financeYear->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['newButtonLabel']; ?>"><?php echo $t['newButtonLabel']; ?></button> 
                                <?php } ?>
                            </div>
                            <label for="queryWidget"></label><div class="input-group"><input type="text" name="queryWidget" id="queryWidget" class="form-control" value="<?php
                                if (isset($_POST['query'])) {
                                    echo $_POST['query'];
                                }
                                ?>"><span class="input-group-addon">
                                    <img id="searchTextImage" src="./images/icons/magnifier.png">
                                </span>
                            </div>
                            <br>					<button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block" onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php echo $financeYear->getViewPath(); ?>', '<?php echo $securityToken; ?>')" value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block" onClick="showGrid('<?php echo $leafId; ?>', '<?php echo $financeYear->getViewPath(); ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)" value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>

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
                                    <input type="hidden" name="financeYearIdPreview" id="financeYearIdPreview">

                                    <div class="form-group" id="financeYearYearDiv">
                                        <label for="financeYearYearPreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['financeYearYearLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="financeYearYearPreview"
                                                   id="financeYearYearPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="isCloseDiv">
                                        <label for="isClosePreview"
                                               class="control-label col-xs-4 col-sm-4 col-md-4"><?php echo $leafTranslation['isCloseLabel']; ?></label>

                                        <div class="col-xs-8 col-sm-8 col-md-8">
                                            <input class="form-control" type="text" name="isClosePreview"
                                                   id="isClosePreview">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                        <button type="button"  class="btn btn-danger"
                                                onclick="deleteGridRecord(<?php echo $leafId; ?>, '<?php
                                                echo $financeYear->getControllerPath();
                                                ?>', '<?php
                                                echo $financeYear->getViewPath();
                                                ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
        <?php } ?>
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
                                    <th>
                                    <div align="center"><?php echo ucwords($leafTranslation['financeYearYearLabel']); ?></div>
                                    </th>
                                    <th width="75px">
                                    <div align="center"><?php echo ucwords($leafTranslation['isCloseLabel']); ?></div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                    </th>
                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                    <th width="25px" align="center">
                                        <input type="checkbox" name="check_all" id="check_all" alt="Check Record"
                                               onChange="toggleChecked(this.checked);">
                                    </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($financeYearArray)) {
                                                $totalRecord = intval(count($financeYearArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr <?php
                                                        if ($financeYearArray[$i]['isDelete'] == 1) {
                                                            echo "class=\"danger\"";
                                                        } else {
                                                            if ($financeYearArray[$i]['isDraft'] == 1) {
                                                                echo "class=\"warning\"";
                                                            }
                                                        }
                                                        ?>>
                                                            <td align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?></div>
                                                            </td>
                                                            <td align="center">
                                                                <div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onclick="showFormUpdate(<?php echo $leafId; ?>, '<?php
                                                                            echo $financeYear->getControllerPath();
                                                                            ?>', '<?php
                                                                            echo $financeYear->getViewPath();
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $financeYearArray [$i]['financeYearId']
                                                                            );
                                                                            ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                                                        <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                                onclick="showModalDelete('<?php
                                                                                echo rawurlencode(
                                                                                        $financeYearArray [$i]['financeYearId']
                                                                                );
                                                                                ?>', '<?php
                                                                                echo rawurlencode(
                                                                                        $financeYearArray [$i]['financeYearYear']
                                                                                );
                                                                                ?>', <?php echo rawurlencode($financeYearArray [$i]['isClose']); ?>);">
                                                                            <i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                        <?php } ?>
                                                                </div>
                                                            </td>
                                                            <td align="center">
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($financeYearArray[$i]['financeYearYear'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($financeYearArray[$i]['financeYearYear']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $financeYearArray[$i]['financeYearYear']
                                                                                    );
                                                                                } else {
                                                                                    echo $financeYearArray[$i]['financeYearYear'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($financeYearArray[$i]['financeYearYear']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $financeYearArray[$i]['financeYearYear']
                                                                                        );
                                                                                    } else {
                                                                                        echo $financeYearArray[$i]['financeYearYear'];
                                                                                    }
                                                                                } else {
                                                                                    echo $financeYearArray[$i]['financeYearYear'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $financeYearArray[$i]['financeYearYear'];
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
                                                                    if (isset($financeYearArray[$i]['isClose'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($financeYearArray[$i]['isClose']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $financeYearArray[$i]['isClose']
                                                                                    );
                                                                                } else {
                                                                                    if ($financeYearArray[$i]['isClose'] == 1) {
                                                                                        echo "<img src=\"./images/icons/tick.png\">";
                                                                                    } else {
                                                                                        echo "<img src=\"./images/icons/cross.png\">";
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($financeYearArray[$i]['isClose']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $financeYearArray[$i]['isClose']
                                                                                        );
                                                                                    } else {
                                                                                        if ($financeYearArray[$i]['isClose'] == 1) {
                                                                                            echo "<img src=\"./images/icons/tick.png\">";
                                                                                        } else {
                                                                                            echo "<img src=\"./images/icons/cross.png\">";
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    if ($financeYearArray[$i]['isClose'] == 1) {
                                                                                        echo "<img src=\"./images/icons/tick.png\">";
                                                                                    } else {
                                                                                        echo "<img src=\"./images/icons/cross.png\">";
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                            if ($financeYearArray[$i]['isClose'] == 1) {
                                                                                echo "<img src=\"./images/icons/tick.png\">";
                                                                            } else {
                                                                                echo "<img src=\"./images/icons/cross.png\">";
                                                                            }
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
                                                                    if (isset($financeYearArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($financeYearArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $financeYearArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $financeYearArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $financeYearArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $financeYearArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $financeYearArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $financeYearArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $financeYearArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                            <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($financeYearArray[$i]['executeTime'])) {
                                                                $valueArray = $financeYearArray[$i]['executeTime'];
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
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td><?php echo $value; ?></td>
                                                            <?php
                                                            if ($financeYearArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                            <td>
                                                                <input style="display:none;" type="checkbox" name="financeYearId[]"
                                                                       value="<?php echo $financeYearArray[$i]['financeYearId']; ?>">
                                                                <input <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                                               value="<?php echo $financeYearArray[$i]['isDelete']; ?>">
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="7" valign="top" align="center"><?php
                                                            $financeYear->exceptionMessage(
                                                                    $t['recordNotFoundLabel']
                                                            );
                                                            ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" valign="top" align="center"><?php
                                                        $financeYear->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" valign="top" align="center"><?php
                                                    $financeYear->exceptionMessage(
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
                                        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                    <button class="delete btn btn-warning" type="button" 
                                            onclick="deleteGridRecordCheckbox(<?php echo $leafId; ?>, '<?php
                                echo $financeYear->getControllerPath();
                                ?>', '<?php echo $financeYear->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                        <i class="glyphicon glyphicon-white glyphicon-trash"></i>
                                    </button>
        <?php } ?>
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
                                           onclick="firstRecord(<?php echo $leafId; ?>, '<?php
                                           echo $financeYear->getControllerPath();
                                           ?>', '<?php
                                       echo $financeYear->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a id="previousRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                           onclick="previousRecord(<?php echo $leafId; ?>, '<?php
                                           echo $financeYear->getControllerPath();
                                           ?>', '<?php
                                       echo $financeYear->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a id="nextRecordbutton"  href="javascript:void(0)" class="btn btn-default disabled"
                                           onclick="nextRecord(<?php echo $leafId; ?>, '<?php
                                           echo $financeYear->getControllerPath();
                                           ?>', '<?php
                                       echo $financeYear->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                class="glyphicon glyphicon-forward glyphicon-white"></i><?php echo $t['nextButtonLabel']; ?>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a id="endRecordbutton"  href="javascript:void(0)" class="btn btn-default"
                                           onclick="endRecord(<?php echo $leafId; ?>, '<?php
                                           echo $financeYear->getControllerPath();
                                           ?>', '<?php
                                       echo $financeYear->getViewPath();
                                       ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                                class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <input type="hidden" name="financeYearId" id="financeYearId" value="<?php
                                if (isset($_POST['financeYearId'])) {
                                    echo $_POST['financeYearId'];
                                }
                                ?>">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="financeYearYearForm">
                                            <label for="financeYearYear" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                    echo ucfirst(
                                                            $leafTranslation['financeYearYearLabel']
                                                    );
                                                    ?></strong></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input type="text" class="form-control" name="financeYearYear" id="financeYearYear"
                                                       value="<?php
                                                       if (isset($financeYearArray[0]['financeYearYear'])) {
                                                           echo htmlentities($financeYearArray[0]['financeYearYear']);
                                                       }
                                                       ?>"> <span class="help-block" id="financeYearYearHelpMe"></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-6 col-md-6 form-group" id="isCloseForm">
                                            <label for="isClose" class="control-label col-xs-4 col-sm-4 col-md-4"><strong><?php
                                                    echo ucfirst(
                                                            $leafTranslation['isCloseLabel']
                                                    );
                                                    ?></strong></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="checkbox" name="isClose" id="isClose" 
                                                       value="<?php
                                                       if (isset($financeYearArray) && is_array($financeYearArray)) {
                                                           if (isset($financeYearArray[0]['isClose'])) {
                                                               echo $financeYearArray[0]['isClose'];
                                                           }
                                                       }
                                                       ?>" <?php
                                                       if (isset($financeYearArray) && is_array($financeYearArray)) {
                                                           if (isset($financeYearArray[0]['isClose'])) {
                                                               if ($financeYearArray[0]['isClose'] == TRUE || $financeYearArray[0]['isClose'] == 1) {
                                                                   echo "checked";
                                                               }
                                                           }
                                                       }
                                                       ?>>
                                                <span class="help-block" id="isCloseHelpMe"></span>
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
                                        <!---<li><a id="newRecordButton5" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newPrintButtonLabel'];                          ?></a></li>-->
                                        <!---<li><a id="newRecordButton6" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php // echo $t['newUpdatePrintButtonLabel'];                            ?></a></li>-->
                                        <li>
                                            <a id="newRecordButton7" href="javascript:void(0)"><i
                                                    class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="btn-group" align="left">
                                    <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                            class="glyphicon glyphicon-edit glyphicon-white"></i><?php echo $t['updateButtonLabel']; ?>
                                    </a>
                                    <a id="updateRecordButton2" href="javascript:void(0)" data-toggle="dropdown"
                                       class="btn dropdown-toggle btn-info disabled"><span class="caret"></span></a>
                                    <ul class="dropdown-menu" style="text-align:left">
                                        <li>
                                            <a id="updateRecordButton3" href="javascript:void(0)"><i
                                                    class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?>
                                            </a>
                                        </li>
                                        <!---<li><a id="updateRecordButton4" href="javascript:void(0)"><i class="glyphicon glyphicon-print"></i><?php //echo $t['updateButtonPrintLabel'];                           ?></a></li> -->
                                        <li>
                                            <a id="updateRecordButton5" href="javascript:void(0)"><i
                                                    class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                            </a></li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="deleteRecordbutton" 
                                        class="btn btn-danger disabled">
                                        <i class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <a id="resetRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                       onclick="resetRecord(<?php echo $leafId; ?>, '<?php
                                       echo $financeYear->getControllerPath();
                                       ?>', '<?php
                                   echo $financeYear->getViewPath();
                                   ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);"><i
                                            class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a id="listRecordbutton"  href="javascript:void(0)" class="btn btn-info"
                                       onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $financeYear->getViewPath();
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
            </div></form><script type="text/javascript">

                $(document).ready(function() {
                    window.scrollTo(0, 0);
                    $('.switch')['bootstrapSwitch']();
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('financeYearId');
                    validateMeNumeric('financeYearYear');
                    $('#isClose').bootstrapSwitch();
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
        if ($_POST['financeYearId']) {
            ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                            $('#newRecordButton3').attr('onClick', '');
                            $('#newRecordButton4').attr('onClick', '');
                            $('#newRecordButton5').attr('onClick', '');
                            $('#newRecordButton6').attr('onClick', '');
                            $('#newRecordButton7').attr('onClick', '');
            <?php if ($financeYearArray[0]['isClose'] == 1) { ?>
                                $('#isClose').bootstrapSwitch('setState', false);
            <?php } else { ?>
                                $('#isClose').bootstrapSwitch('setState', true);
            <?php } ?>
                            // end optional
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                                $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                                $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                                $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                                $('#updateRecordButton1').removeClass().addClass(' btn btn-info disabled').attr('onClick', '');
                                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                                $('#updateRecordButton3').attr('onClick', '');
                                $('#updateRecordButton4').attr('onClick', '');
                                $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                                $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $financeYear->getControllerPath(); ?>','<?php echo $financeYear->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
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
<?php } ?>
<script type="text/javascript" src="./v3/financial/generalLedger/javascript/financeYear.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>