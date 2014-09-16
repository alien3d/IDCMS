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
require_once($newFakeDocumentRoot . "v3/system/management/controller/systemSettingController.php");
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
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable('systemSetting');

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
$systemSettingArray = array();
$countryArray = array();
$languageArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $systemSetting = new \Core\System\Management\SystemSetting\Controller\SystemSettingClass();
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
            $systemSetting->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $systemSetting->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $systemSetting->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $systemSetting->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $systemSetting->setStartDay($start[2]);
            $systemSetting->setStartMonth($start[1]);
            $systemSetting->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $systemSetting->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $systemSetting->setEndDay($start[2]);
            $systemSetting->setEndMonth($start[1]);
            $systemSetting->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $systemSetting->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $systemSetting->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $systemSetting->setServiceOutput('html');
        $systemSetting->setLeafId($leafId);
        $systemSetting->execute();
        $countryArray = $systemSetting->getCountry();
        $languageArray = $systemSetting->getLanguage();
        if ($_POST['method'] == 'read') {
            $systemSetting->setStart($offset);
            $systemSetting->setLimit($limit); // normal system don't like paging..  
            $systemSetting->setPageOutput('html');
            $systemSettingArray = $systemSetting->read();
            if (isset($systemSettingArray [0]['firstRecord'])) {
                $firstRecord = $systemSettingArray [0]['firstRecord'];
            }
            if (isset($systemSettingArray [0]['nextRecord'])) {
                $nextRecord = $systemSettingArray [0]['nextRecord'];
            }
            if (isset($systemSettingArray [0]['previousRecord'])) {
                $previousRecord = $systemSettingArray [0]['previousRecord'];
            }
            if (isset($systemSettingArray [0]['lastRecord'])) {
                $lastRecord = $systemSettingArray [0]['lastRecord'];
                $endRecord = $systemSettingArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($systemSetting->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($systemSettingArray [0]['total'])) {
                $total = $systemSettingArray [0]['total'];
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

                <div id="rightViewport" class="col-xs-12 col-sm-12 col-md-">
                    <div class="modal fade" id="deletePreview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><?php echo $t['deleteRecordMessageLabel']; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal">
                                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <input type="hidden" name="systemSettingIdPreview" id="systemSettingIdPreview">

                                        <div class="form-group" id="countryIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="countryIdPreview"><?php echo $leafTranslation['countryIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="countryIdPreview"
                                                       id="countryIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="languageIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="languageIdPreview"><?php echo $leafTranslation['languageIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="languageIdPreview"
                                                       id="languageIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="languageCodeDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="languageCodePreview"><?php echo $leafTranslation['languageCodeLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="languageCodePreview"
                                                       id="languageCodePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="systemSettingDateFormatDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="systemSettingDateFormatPreview"><?php echo $leafTranslation['systemSettingDateFormatLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="systemSettingDateFormatPreview" id="systemSettingDateFormatPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="systemSettingTimeFormatDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="systemSettingTimeFormatPreview"><?php echo $leafTranslation['systemSettingTimeFormatLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="systemSettingTimeFormatPreview" id="systemSettingTimeFormatPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="systemSettingWeekStartDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="systemSettingWeekStartPreview"><?php echo $leafTranslation['systemSettingWeekStartLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text"
                                                       name="systemSettingWeekStartPreview" id="systemSettingWeekStartPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="systemWebsiteDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="systemWebsitePreview"><?php echo $leafTranslation['systemWebsiteLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control" type="text" name="systemWebsitePreview"
                                                       id="systemWebsitePreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-default"
                                            onclick="showMeModal('deletePreview', 0);"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <table class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                    <thead>
                                        <tr>
                                            <th width="25px" align="center">
                                    <div align="center">#</div>
                                    </th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                    </th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['countryIdLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['languageIdLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['languageCodeLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['systemSettingDateFormatLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['systemSettingTimeFormatLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['systemSettingWeekStartLabel']); ?></th>
                                    <th width="125px"><?php echo ucwords($leafTranslation['systemWebsiteLabel']); ?></th>
                                    <th width="100px">
                                    <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                    </th>
                                    <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th> </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <?php
                                        if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                            if (is_array($systemSettingArray)) {
                                                $totalRecord = intval(count($systemSettingArray));
                                                if ($totalRecord > 0) {
                                                    $counter = 0;
                                                    for ($i = 0; $i < $totalRecord; $i++) {
                                                        $counter++;
                                                        ?>
                                                        <tr>
                                                            <td valign="top" align="center">
                                                                <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                            </td>
                                                            <td valign="top" align="center">
                                                                <div class="btn-group" align="center">
                                                                    <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                            onclick="showFormUpdate('<?php echo $leafId; ?>', '<?php
                                                                            echo $systemSetting->getControllerPath(
                                                                            );
                                                                            ?>', '<?php
                                                                            echo $systemSetting->getViewPath(
                                                                            );
                                                                            ?>', '<?php echo $securityToken; ?>', '<?php
                                                                            echo intval(
                                                                                    $systemSettingArray [$i]['systemSettingId']
                                                                            );
                                                                            ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                        <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                </div></td><td valign="top">
                                                                <div align="left">
                                                                 <?php
                                                                    if (isset($systemSettingArray[$i]['countryDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $systemSettingArray[$i]['countryDescription'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['countryDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['countryDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $systemSettingArray[$i]['countryDescription'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['countryDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['countryDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['countryDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['countryDescription'];
                                                                        }
                                                                        ?>
                                                                </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top">
                                                                <div align="left">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['languageDescription'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                $systemSettingArray[$i]['languageDescription'], $_POST['query']
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['languageDescription']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['languageDescription'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $systemSettingArray[$i]['languageDescription'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['languageDescription']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['languageDescription'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['languageDescription'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['languageDescription'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top">
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['languageCode'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($systemSettingArray[$i]['languageCode']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['languageCode']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['languageCode'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($systemSettingArray[$i]['languageCode']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['languageCode']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['languageCode'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['languageCode'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['languageCode'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top">
                                                                <div align="left">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['systemSettingDateFormat'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($systemSettingArray[$i]['systemSettingDateFormat']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['systemSettingDateFormat']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemSettingDateFormat'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($systemSettingArray[$i]['systemSettingDateFormat']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['systemSettingDateFormat']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['systemSettingDateFormat'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemSettingDateFormat'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['systemSettingDateFormat'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top">
                                                                <div align="left">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['systemSettingTimeFormat'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($systemSettingArray[$i]['systemSettingTimeFormat']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['systemSettingTimeFormat']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemSettingTimeFormat'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($systemSettingArray[$i]['systemSettingTimeFormat']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['systemSettingTimeFormat']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['systemSettingTimeFormat'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemSettingTimeFormat'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['systemSettingTimeFormat'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top">
                                                                <div align="left">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['systemSettingWeekStart'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($systemSettingArray[$i]['systemSettingWeekStart']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['systemSettingWeekStart']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemSettingWeekStart'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($systemSettingArray[$i]['systemSettingWeekStart']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['systemSettingWeekStart']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['systemSettingWeekStart'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemSettingWeekStart'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['systemSettingWeekStart'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top">
                                                                <div align="left">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['systemWebsite'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos(
                                                                                                strtolower($systemSettingArray[$i]['systemWebsite']), strtolower($_POST['query'])
                                                                                        ) !== false
                                                                                ) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['systemWebsite']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemWebsite'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    strtolower($systemSettingArray[$i]['systemWebsite']), strtolower($_POST['character'])
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['systemWebsite']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['systemWebsite'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['systemWebsite'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['systemWebsite'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td valign="top" align="center">
                                                                <div align="center">
                                                                    <?php
                                                                    if (isset($systemSettingArray[$i]['executeBy'])) {
                                                                        if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                            if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                                if (strpos($systemSettingArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                                    echo str_replace(
                                                                                            $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $systemSettingArray[$i]['staffName']
                                                                                    );
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['staffName'];
                                                                                }
                                                                            } else {
                                                                                if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                                    if (strpos(
                                                                                                    $systemSettingArray[$i]['staffName'], $_POST['character']
                                                                                            ) !== false
                                                                                    ) {
                                                                                        echo str_replace(
                                                                                                $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $systemSettingArray[$i]['staffName']
                                                                                        );
                                                                                    } else {
                                                                                        echo $systemSettingArray[$i]['staffName'];
                                                                                    }
                                                                                } else {
                                                                                    echo $systemSettingArray[$i]['staffName'];
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo $systemSettingArray[$i]['staffName'];
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <?php
                                                            if (isset($systemSettingArray[$i]['executeTime'])) {
                                                                $valueArray = $systemSettingArray[$i]['executeTime'];
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
                                                                <td valign="top"><?php echo $value; ?></td>
                                                            <?php
                                                            if ($systemSettingArray[$i]['isDelete']) {
                                                                $checked = "checked";
                                                            } else {
                                                                $checked = null;
                                                            }
                                                            ?>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td colspan="12" valign="top"
                                                            align="center"><?php $systemSetting->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="12" valign="top"
                                                        align="center"><?php $systemSetting->exceptionMessage($t['recordNotFoundLabel']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="12" valign="top"
                                                    align="center"><?php $systemSetting->exceptionMessage($t['loadFailureLabel']); ?></td>
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
            </div>
        </div>
        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <form class="form-horizontal"><input type="hidden" name="systemSettingId" id="systemSettingId" value="<?php
        if (isset($_POST['systemSettingId'])) {
            echo $_POST['systemSettingId'];
        }
        ?>">

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
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="countryIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="countryId"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['countryIdLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="countryId" id="countryId" class="form-control chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($countryArray)) {
                                                    $totalRecord = intval(count($countryArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($systemSettingArray[0]['countryId'])) {
                                                                if ($systemSettingArray[0]['countryId'] == $countryArray[$i]['countryId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $countryArray[$i]['countryId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $countryArray[$i]['countryDescription']; ?></option>
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
                                            <span class="help-block" id="countryIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="languageIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="languageId"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['languageIdLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="languageId" id="languageId" class="form-control chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($languageArray)) {
                                                    $totalRecord = intval(count($languageArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($systemSettingArray[0]['languageId'])) {
                                                                if ($systemSettingArray[0]['languageId'] == $languageArray[$i]['languageId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $languageArray[$i]['languageId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $languageArray[$i]['languageDescription']; ?></option>
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
                                            <span class="help-block" id="languageIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="languageCodeForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="languageCode"><strong><?php echo ucfirst($leafTranslation['languageCodeLabel']); ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="languageCode" id="languageCode"
                                                       onkeyup="removeMeError('languageCode');"
                                                       value="<?php
                                                       if (isset($systemSettingArray) && is_array($systemSettingArray)) {
                                                           if (isset($systemSettingArray[0]['languageCode'])) {
                                                               echo htmlentities($systemSettingArray[0]['languageCode']);
                                                           }
                                                       }
                                                       ?>" maxlength="16">
                                                <span class="input-group-addon"><img src="./images/icons/document-code.png"></span></div>
                                            <span class="help-block" id="languageCodeHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="systemSettingDateFormatForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="systemSettingDateFormat"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['systemSettingDateFormatLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <input class="form-control" type="text" name="systemSettingDateFormat"
                                                   id="systemSettingDateFormat" onkeyup="removeMeError('systemSettingDateFormat');" value="<?php
                                                   if (isset($systemSettingArray) && is_array($systemSettingArray)) {
                                                       if (isset($systemSettingArray[0]['systemSettingDateFormat'])) {
                                                           echo htmlentities($systemSettingArray[0]['systemSettingDateFormat']);
                                                       }
                                                   }
                                                   ?>">
                                            <span class="help-block" id="systemSettingDateFormatHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="systemSettingTimeFormatForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="systemSettingTimeFormat"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['systemSettingTimeFormatLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <input class="form-control" type="text" name="systemSettingTimeFormat"
                                                   id="systemSettingTimeFormat" onkeyup="removeMeError('systemSettingTimeFormat');" value="<?php
                                                   if (isset($systemSettingArray) && is_array($systemSettingArray)) {
                                                       if (isset($systemSettingArray[0]['systemSettingTimeFormat'])) {
                                                           echo htmlentities($systemSettingArray[0]['systemSettingTimeFormat']);
                                                       }
                                                   }
                                                   ?>">
                                            <span class="help-block" id="systemSettingTimeFormatHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="systemSettingWeekStartForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="systemSettingWeekStart"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['systemSettingWeekStartLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="systemSettingWeekStart"
                                                       id="systemSettingWeekStart"
                                                       value="<?php
                                                       if (isset($systemSettingArray[0]['systemSettingWeekStart'])) {
                                                           if (isset($systemSettingArray[0]['systemSettingWeekStart'])) {
                                                               echo htmlentities($systemSettingArray[0]['systemSettingWeekStart']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/sort-number.png"></span></div>
                                            <span class="help-block" id="systemSettingWeekStartHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="systemWebsiteForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="systemWebsite"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['systemWebsiteLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <textarea class="form-control" name="systemWebsite" id="systemWebsite"
                                                      onkeyup="removeMeError('systemWebsite');"><?php
                                                          if (isset($systemSettingArray[0]['systemWebsite'])) {
                                                              echo htmlentities($systemSettingArray[0]['systemWebsite']);
                                                          }
                                                          ?></textarea>
                                            <span class="help-block" id="systemWebsiteHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group">
                                <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                        class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?> </a>
                                <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled"
                                   data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a></li>
                                    <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];            ?></a> </li> -->
                                    <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?></a></li>
                                </ul></div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info"
                                        onclick="showGrid('<?php echo $leafId; ?>', '<?php
                                        echo $systemSetting->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                                    <i class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
                            </div>
                        </div>
                        <input type="hidden" name="firstRecordCounter" id="firstRecordCounter" value="<?php
                        if (isset($firstRecord)) {
                            echo intval($firstRecord);
                        }
                        ?>">
                        <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                        if (isset($nextRecord)) {
                            echo intval($nextRecord);
                        }
                        ?>">
                        <input type="hidden" name="previousRecordCounter" id="previousRecordCounter" value="<?php
                        if (isset($previousRecord)) {
                            echo intval($previousRecord);
                        }
                        ?>">
                        <input type="hidden" name="lastRecordCounter" id="lastRecordCounter" value="<?php
                        if (isset($lastRecord)) {
                            echo intval($lastRecord);
                        }
                        ?>">
                        <input type="hidden" name="endRecordCounter" id="endRecordCounter" value="<?php
                               if (isset($endRecord)) {
                                   echo intval($endRecord);
                               }
                               ?>">
                    </div>
                </div>
            </div>
        </div></form>
    <script type="text/javascript">
        $(document).keypress(function(e) {
    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                // shift+n new record event
                if (e.which === 78 && e.which === 18  && e.shiftKey) {
                    


                    newRecord(<?php echo $leafId; ?>, '<?php echo $systemSetting->getControllerPath(); ?>', '<?php echo $systemSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);

                    return false;
                }
    <?php } ?>
    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                // shift+s save event
                if (e.which === 83 && e.which === 18  && e.shiftKey) {
                    


                    updateRecord(<?php echo $leafId; ?>, '<?php echo $systemSetting->getControllerPath(); ?>', '<?php echo $systemSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
                }
    <?php } ?>
            // shift+d delete event
            if (e.which === 88 && e.which === 18 && e.shiftKey) {
                

    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    deleteRecord(<?php echo $leafId; ?>, '<?php echo $systemSetting->getControllerPath(); ?>', '<?php echo $systemSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

                    return false;
    <?php } ?>
            }
            switch (e.keyCode) {
                case 37:
                    previousRecord(<?php echo $leafId; ?>, '<?php echo $systemSetting->getControllerPath(); ?>', '<?php echo $systemSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
                case 39:
                    nextRecord(<?php echo $leafId; ?>, '<?php echo $systemSetting->getControllerPath(); ?>', '<?php echo $systemSetting->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                    
                    return false;
                    break;
            }
            

        });
        $(document).ready(function() {
            tableHeightSize()
            $(window).resize(function() {
                tableHeightSize()
            });
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('systemSettingId');
            validateMeNumeric('countryId');
            validateMeNumeric('languageId');
            validateMeAlphaNumeric('languageCode');
            validateMeAlphaNumeric('systemSettingDateFormat');
            validateMeAlphaNumeric('systemSettingTimeFormat');
            validateMeNumeric('systemSettingWeekStart');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
                $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                $('#updateRecordButton1').attr('onClick', '');
                $('#updateRecordButton2').attr('onClick', '');
                $('#updateRecordButton3').attr('onClick', '');
                $('#updateRecordButton4').attr('onClick', '');
                $('#updateRecordButton5').attr('onClick', '');
                $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
                $('#firstRecordButton').removeClass().addClass('btn btn-default');
                $('#endRecordButton').removeClass().addClass('btn btn-default');
        <?php
    } else {
        if ($_POST['systemSettingId']) {
            ?>
            <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $systemSetting->getControllerPath(); ?>','<?php echo $systemSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                        $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $systemSetting->getControllerPath(); ?>','<?php echo $systemSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $systemSetting->getControllerPath(); ?>','<?php echo $systemSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                        $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $systemSetting->getControllerPath(); ?>','<?php echo $systemSetting->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
            <?php } else { ?>
                        $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                        $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                        $('#updateRecordButton3').attr('onClick', '');
                        $('#updateRecordButton4').attr('onClick', '');
                        $('#updateRecordButton5').attr('onClick', '');
            <?php } ?>
            <?php
        }
    }
    ?>
        });
    </script>
    
<?php } ?>
<script type="text/javascript" src="./v3/system/management/javascript/systemSetting.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>