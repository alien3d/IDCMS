<link rel="stylesheet"  type="text/css" href="css/bootstrap.min.css" />
<?php
use Core\System\Security\TableMappingTranslate\Controller\TableMappingTranslateClass;
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
require_once($newFakeDocumentRoot . "v3/system/security/controller/tableMappingTranslateController.php");
require_once($newFakeDocumentRoot . "library/class/classNavigation.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$template = new \Core\shared\SharedTemplate();
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable(array('tableMappingTranslate', 'tableMapping'));

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
$tableMappingTranslateArray = array();
$tableMappingArray = array();
$tableMappingColumnArray = array();
$languageArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $tableMappingTranslate = new TableMappingTranslateClass();
        define('LIMIT', 500);
        if (isset($_POST['offset'])) {
            $offset = $_POST['offset'];
        } else {
            $offset = 0;
        }
        if (isset($_POST['limit'])) {
            $limit = 500;
        } else {
            $limit = LIMIT;
        }
        if (isset($_POST ['query'])) {
            $tableMappingTranslate->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $tableMappingTranslate->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $tableMappingTranslate->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $tableMappingTranslate->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $tableMappingTranslate->setStartDay($start[2]);
            $tableMappingTranslate->setStartMonth($start[1]);
            $tableMappingTranslate->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $tableMappingTranslate->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $tableMappingTranslate->setEndDay($start[2]);
            $tableMappingTranslate->setEndMonth($start[1]);
            $tableMappingTranslate->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $tableMappingTranslate->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $tableMappingTranslate->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $tableMappingTranslate->setServiceOutput('html');
        $tableMappingTranslate->setLeafId($leafId);
        $tableMappingTranslate->execute();
        //@depreciate $tableMappingDatabaseArray = $tableMappingTranslate->getTableMappingDatabase();
        $tableMappingArray = $tableMappingTranslate->getTableMapping();
        $tableMappingColumnArray = $tableMappingTranslate->getTableMappingColumn();
        $languageArray = $tableMappingTranslate->getLanguage();
        if ($_POST['method'] == 'read') {
            $tableMappingTranslate->setStart($offset);
            $tableMappingTranslate->setLimit($limit); // normal system don't like paging..
            $tableMappingTranslate->setPageOutput('html');
            $tableMappingTranslateArray = $tableMappingTranslate->read();
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($tableMappingTranslate->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($tableMappingTranslateArray [0]['total'])) {
                $total = $tableMappingTranslateArray [0]['total'];
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
</script>
<?php
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
              <label for="tableMappingName" class="control-label col-xs-2 col-sm-2 col-md-2">
                <?php
                                        echo ucfirst(
                                                $leafTranslation['tableMappingIdLabel']
                                        );
                                        ?>
              </label>
              <div class="col-xs-10 col-sm-10 col-md-10">
                <select name="tableMappingName" id="tableMappingName" class="chzn-select form-control"
                                                style="width:400px" onChange="filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $tableMappingTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 'tableMappingId');
                                                        filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $tableMappingTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1, 'tableMappingId');
                                                        filterForm(<?php echo $leafId; ?>, '<?php
                                                echo $tableMappingTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1, 'tableMappingId');">
                  <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                  <?php
                                            if (is_array($tableMappingArray)) {
                                                $totalRecord = intval(count($tableMappingArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                  <option value="<?php echo $tableMappingArray[$i]['tableMappingName']; ?>"><?php echo $d; ?> . <?php echo $tableMappingArray[$i]['tableMappingName']; ?></option>
                  <?php
                                                }
                                            }
                                            ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="tableMappingId" class="control-label col-xs-2 col-sm-2 col-md-2">
                <?php
                                        echo ucfirst(
                                                $leafTranslation['tableMappingColumnNameLabel']
                                        );
                                        ?>
              </label>
              <div class="col-xs-10 col-sm-10 col-md-10">
                <select name="tableMappingId" id="tableMappingId" class="chzn-select form-control"
                                                style="width:400px" onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $tableMappingTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1);">
                  <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                  <?php
                                            if (is_array($tableMappingColumnArray)) {
                                                $totalRecord = intval(count($tableMappingColumnArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                  <option value="<?php echo $tableMappingColumnArray[$i]['tableMappingId']; ?>"><?php echo $d; ?> . <?php echo $tableMappingArray[$i]['tableMappingName']; ?> :: <?php echo $tableMappingColumnArray[$i]['tableMappingColumnName']; ?></option>
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
                <select name='languageId' id='languageId' class="chzn-select form-control" style="width:400px"
                                                onChange="filterGrid(<?php echo $leafId; ?>, '<?php
                                                echo $tableMappingTranslate->getControllerPath();
                                                ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1);">
                  <option value=""><?php echo $t['pleaseSelectTextLabel']; ?></option>
                  <?php
                                            if (is_array($languageArray)) {
                                                $totalRecord = intval(count($languageArray));
                                                $d = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $d++;
                                                    ?>
                  <option value="<?php echo $languageArray[$i]['languageId']; ?>"><?php echo $d; ?> . <?php echo $languageArray[$i]['languageDescription']; ?></option>
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
                                        onclick="showGrid(<?php echo $leafId; ?>, '<?php
                                        echo $tableMappingTranslate->getViewPath();
                                        ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>');"> <?php echo $t['clearButtonLabel']; ?> </button>
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
                <th width="25"> <div align="center">#</div>
                </th>
                <th width="150"><?php echo ucfirst($leafTranslation['tableMappingNameLabel']); ?></th>
                <th width="150"><?php echo ucfirst($leafTranslation['tableMappingColumnNameLabel']); ?></th>
                <th><?php echo ucfirst($leafTranslation['tableMappingEnglishLabel']); ?></th>
                <th width="150"><?php echo ucfirst($leafTranslation['languageIdLabel']); ?></th>
                <th width="300"><?php echo ucfirst($leafTranslation['tableMappingNativeLabel']); ?></th>
              </tr>
            </thead>
            <tbody id=tableBody>
              <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($tableMappingTranslateArray)) {
                                            $totalRecord = intval(count($tableMappingTranslateArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
              <tr>
                <td valign="top" align="center"><div align="center"><?php echo($counter + $offset); ?></div></td>
                <td valign="top" align="left"><?php if (isset($tableMappingTranslateArray[$i]['tableMappingName'])) { 
											echo $tableMappingTranslateArray[$i]['tableMappingName']; 
									   } else { ?>
									  <img src="./images/icons/burn.png">
									  <?php } ?></td>
									  
                <td valign="top" ><?php if (isset($tableMappingTranslateArray[$i]['tableMappingColumnName'])) {
                          	echo $tableMappingTranslateArray[$i]['tableMappingColumnName']; 
                   		  } else { ?>
                  			<img src="./images/icons/burn.png">
                  <?php	}	?></td>
				  
                <td valign="top" ><?php	if (isset($tableMappingTranslateArray[$i]['tableMappingEnglish'])) {
                                                            echo $tableMappingTranslateArray[$i]['tableMappingEnglish']; 
                   } else { ?>
                  <img src="./images/icons/burn.png">
                  <?php } ?></td>
				  
                <td valign="top" ><?php	if (isset($tableMappingTranslateArray[$i]['languageDescription'])) {
                            	if (file_exists($newFakeDocumentRoot . "images/country/" . $tableMappingTranslateArray[$i]['languageIcon'])) { ?><img class="img-thumbnail" src="./images/country/<?php echo $tableMappingTranslateArray[$i]['languageIcon']; ?>">&nbsp;<?php echo $tableMappingTranslateArray[$i]['languageDescription'];  
                                                                     } else { ?> Image Country Not Available
                  <?php } ?>
                  <?php } ?></td>
				  
                <td valign="top" ><?php	if (isset($tableMappingTranslateArray[$i]['tableMappingNative'])) {
                                                            ?>
                  <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="input-group">
                      <input type="text" class="form-control" name="tableMappingNative<?php echo $tableMappingTranslateArray[$i]['tableMappingTranslateId']; ?>" id="tableMappingNative<?php echo $tableMappingTranslateArray[$i]['tableMappingTranslateId']; ?>" value="<?php echo $tableMappingTranslateArray[$i]['tableMappingNative']; ?>">
                      <?php
                                                                               if ($leafAccess['leafAccessUpdateValue'] == 0) {
                                                                                   $disabled = "disabled";
                                                                               } else {
                                                                                   $disabled = null;
                                                                               }
                                                                               ?>
                      <span class="input-group-btn">
                      <button type="button"  class="btn btn-warning <?php echo $disabled; ?>" title="<?php echo $t['saveButtonLabel']; ?>" <?php echo $disabled; ?> onclick="updateRecordInline(<?php echo $leafId; ?>, '<?php echo $tableMappingTranslate->getControllerPath(); ?>', '<?php echo $securityToken; ?>', '<?php echo $tableMappingTranslateArray[$i]['tableMappingTranslateId']; ?>');"><?php echo $t['saveButtonLabel']; ?></button>
                      </span> </div>
                  </div>
                  <div id="infoPanelMini<?php echo $tableMappingTranslateArray[$i]['tableMappingTranslateId']; ?>"></div>
                  <?php } else { ?>
                  <img src="./images/icons/burn.png">
                  <?php } ?></td>
              </tr>
              <?php
                                                }
                                            } else {
                                                ?>
              <tr>
                <td colspan="6" valign="top" align="center"><?php
                                                        $tableMappingTranslate->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
              </tr>
              <?php
                                            }
                                        } else {
                                            ?>
              <tr>
                <td colspan="6" valign="top" align="center"><?php
                                                    $tableMappingTranslate->exceptionMessage(
                                                            $t['recordNotFoundLabel']
                                                    );
                                                    ?></td>
              </tr>
              <?php
                                        }
                                    } else {
                                        ?>
              <tr>
                <td colspan="6" valign="top" align="center"><?php
                                                $tableMappingTranslate->exceptionMessage(
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
<script type='text/javascript' src='./v3/system/security/javascript/tableMappingTranslate.js'></script>
