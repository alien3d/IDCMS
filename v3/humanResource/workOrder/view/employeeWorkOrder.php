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
require_once($newFakeDocumentRoot . "v3/humanResource/workOrder/controller/employeeWorkOrderController.php");
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
$translator->setCurrentDatabase('icore');
$translator->setCurrentTable('employeeWorkOrder');
if (isset($_POST['leafId'])) {
    $leafId = intval($_POST['leafId'] * 1);
} else if (isset($_GET['leafId'])) {
    $leafId = intval($_GET['leafId'] * 1);
} else {
    // redirect to main page if no id

    header("index.php");
    exit();
}
if ($leafId == 0) {
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
$employeeWorkOrderArray = array();
$employeeArray = array();
$shiftArray = array();
$invoiceProjectArray = array();
$milestoneArray = array();
$_POST['from'] = 'employeeWorkOrder.php';
$_GET['from'] = 'employeeWorkOrder.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $employeeWorkOrder = new \Core\HumanResource\WorkOrder\EmployeeWorkOrder\Controller\EmployeeWorkOrderClass();
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
            $employeeWorkOrder->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $employeeWorkOrder->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $employeeWorkOrder->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $employeeWorkOrder->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $employeeWorkOrder->setStartDay($start[2]);
            $employeeWorkOrder->setStartMonth($start[1]);
            $employeeWorkOrder->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $employeeWorkOrder->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $employeeWorkOrder->setEndDay($start[2]);
            $employeeWorkOrder->setEndMonth($start[1]);
            $employeeWorkOrder->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $employeeWorkOrder->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $employeeWorkOrder->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $employeeWorkOrder->setServiceOutput('html');
        echo "sini takde ka " . $leafId;
        $employeeWorkOrder->setLeafId($leafId);
        $employeeWorkOrder->execute();
        $employeeArray = $employeeWorkOrder->getEmployee();
        $shiftArray = $employeeWorkOrder->getShift();
        $invoiceProjectArray = $employeeWorkOrder->getInvoiceProject();
        $milestoneArray = $employeeWorkOrder->getMilestone();
        if ($_POST['method'] == 'read') {
            $employeeWorkOrder->setStart($offset);
            $employeeWorkOrder->setLimit($limit); // normal system don't like paging..  
            $employeeWorkOrder->setPageOutput('html');
            $employeeWorkOrderArray = $employeeWorkOrder->read();
            if (isset($employeeWorkOrderArray [0]['firstRecord'])) {
                $firstRecord = $employeeWorkOrderArray [0]['firstRecord'];
            }
            if (isset($employeeWorkOrderArray [0]['nextRecord'])) {
                $nextRecord = $employeeWorkOrderArray [0]['nextRecord'];
            }
            if (isset($employeeWorkOrderArray [0]['previousRecord'])) {
                $previousRecord = $employeeWorkOrderArray [0]['previousRecord'];
            }
            if (isset($employeeWorkOrderArray [0]['lastRecord'])) {
                $lastRecord = $employeeWorkOrderArray [0]['lastRecord'];
                $endRecord = $employeeWorkOrderArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($employeeWorkOrder->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($employeeWorkOrderArray [0]['total'])) {
                $total = $employeeWorkOrderArray [0]['total'];
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
    var leafTranslation =<?php echo json_encode($translator->getLeafTranslation()); ?>;</script><?php
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
                <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form class="form-forizontal">
                                <div id="btnList">

                                    <button type="button"  name="newRecordbutton"  id="newRecordbutton" 
                                        class="btn btn-info btn-block"
                                            onClick="showForm('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['newButtonLabel']; ?></button>
                                </div>
                                <br>
                                <label for="queryWidget"></label><input type="text" name="queryWidget" id="queryWidget"
                                                                        class="form-control" value="<?php
                                                                        if (isset($_POST['query'])) {
                                                                            echo $_POST['query'];
                                                                        }
                                                                        ?>">
                                <br>
                                <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block"
                                        onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php
                                        echo $employeeWorkOrder->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                                <button type="button"  name="clearSearchString" id="clearSearchString"
                                        class="btn btn-info btn-block"
                                        onClick="showGrid('<?php echo $leafId; ?>', '<?php
                                        echo $employeeWorkOrder->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1);"><?php echo $t['clearButtonLabel']; ?></button>
                                <br>

                                <table class="table table-striped table-condensed table-hover">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="center"><img src="./images/icons/calendar-select-days-span.png"
                                                                alt="<?php echo $t['allDay'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" rel="tooltip"
                                                              onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                              echo $employeeWorkOrder->getViewPath(
                                                              );
                                                              ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                                              echo date(
                                                                      'd-m-Y'
                                                              );
                                                              ?>', 'between', '');"><?php
                                                                  echo strtoupper(
                                                                          $t['anyTimeTextLabel']
                                                                  );
                                                                  ?></a></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Day <?php echo $previousDay; ?>"
                                                             onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                             echo $employeeWorkOrder->getViewPath(
                                                             );
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                                alt="<?php echo $t['dayTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                              echo $employeeWorkOrder->getViewPath(
                                                              );
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                        </td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Day <?php echo $nextDay; ?>"
                                                            onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                            echo $employeeWorkOrder->getViewPath(
                                                            );
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Week<?php
                                                             echo $dateConvert->getCurrentWeekInfo(
                                                                     $dateRangeStart, 'previous'
                                                             );
                                                             ?>"
                                                             onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                             echo $employeeWorkOrder->getViewPath(
                                                             );
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-week.png"
                                                                alt="<?php echo $t['weekTextLabel'] ?>"></td>
                                        <td align="center"><a href="javascript:void(0)" rel="tooltip"
                                                              title="<?php
                                                              echo $dateConvert->getCurrentWeekInfo(
                                                                      $dateRangeStart, 'current'
                                                              );
                                                              ?>"
                                                              onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                              echo $employeeWorkOrder->getViewPath(
                                                              );
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                        </td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Week <?php
                                                            echo $dateConvert->getCurrentWeekInfo(
                                                                    $dateRangeStart, 'next'
                                                            );
                                                            ?>"
                                                            onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                            echo $employeeWorkOrder->getViewPath(
                                                            );
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Month <?php echo $previousMonth; ?>"
                                                             onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                             echo $employeeWorkOrder->getViewPath(
                                                             );
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar-select-month.png"
                                                                alt="<?php echo $t['monthTextLabel']; ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                              echo $employeeWorkOrder->getViewPath(
                                                              );
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                        </td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Month <?php echo $nextMonth; ?>"
                                                            onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                            echo $employeeWorkOrder->getViewPath(
                                                            );
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                             title="Previous Year <?php echo $previousYear; ?>"
                                                             onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                             echo $employeeWorkOrder->getViewPath(
                                                             );
                                                             ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                        </td>
                                        <td align="center"><img src="./images/icons/calendar.png"
                                                                alt="<?php echo $t['yearTextLabel']; ?>"></td>
                                        <td align="center"><a href="javascript:void(0)"
                                                              onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                              echo $employeeWorkOrder->getViewPath(
                                                              );
                                                              ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                        </td>
                                        <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                            title="Next Year <?php echo $nextYear; ?>"
                                                            onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                            echo $employeeWorkOrder->getViewPath(
                                                            );
                                                            ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                        </td>
                                    </tr>
                                </table>


                                <div>
                                    <label for="dateRangeStart"></label><input type="text" name="dateRangeStart"
                                                                               id="dateRangeStart" class="form-control"
                                                                               value="<?php
                                                                               if (isset($_POST['dateRangeStart'])) {
                                                                                   echo $_POST['dateRangeStart'];
                                                                               }
                                                                               ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>">
                                    <label for="dateRangeEnd"></label><input type="text" name="dateRangeEnd" id="dateRangeEnd"
                                                                             class="form-control" value="<?php
                                                                             if (isset($_POST['dateRangeEnd'])) {
                                                                                 echo $_POST['dateRangeEnd'];
                                                                             }
                                                                             ?>" onClick="topPage(175);"><br>
                                    <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block"
                                            onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['searchButtonLabel']; ?></button>
                                    <button type="button"  name="clearSearchDate" id="clearSearchDate"
                                            class="btn btn-info btn-block"
                                            onClick="showGrid('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><?php echo $t['clearButtonLabel']; ?></button>
                                </div>
                            </form>
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
                                        <button type="button"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <input type="hidden" name="employeeWorkOrderIdPreview" id="employeeWorkOrderIdPreview">

                                        <div class="form-group" id="employeeIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeIdPreview"><?php echo $leafTranslation['employeeIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="employeeIdPreview"
                                                       id="employeeIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="shiftIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="shiftIdPreview"><?php echo $leafTranslation['shiftIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="shiftIdPreview"
                                                       id="shiftIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="invoiceProjectIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="invoiceProjectIdPreview"><?php echo $leafTranslation['invoiceProjectIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="invoiceProjectIdPreview"
                                                       id="invoiceProjectIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="milestoneIdDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="milestoneIdPreview"><?php echo $leafTranslation['milestoneIdLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="milestoneIdPreview"
                                                       id="milestoneIdPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="documentNumberDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="documentNumberPreview"><?php echo $leafTranslation['documentNumberLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="documentNumberPreview"
                                                       id="documentNumberPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeWorkOrderDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeWorkOrderDatePreview"><?php echo $leafTranslation['employeeWorkOrderDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="employeeWorkOrderDatePreview"
                                                       id="employeeWorkOrderDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeWorkOrderStartDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeWorkOrderStartDatePreview"><?php echo $leafTranslation['employeeWorkOrderStartDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text"
                                                       name="employeeWorkOrderStartDatePreview"
                                                       id="employeeWorkOrderStartDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeWorkOrderEndDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeWorkOrderEndDatePreview"><?php echo $leafTranslation['employeeWorkOrderEndDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text"
                                                       name="employeeWorkOrderEndDatePreview" id="employeeWorkOrderEndDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeWorkOrderDueDateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeWorkOrderDueDatePreview"><?php echo $leafTranslation['employeeWorkOrderDueDateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text"
                                                       name="employeeWorkOrderDueDatePreview" id="employeeWorkOrderDueDatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeWorkOrderRateDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeWorkOrderRatePreview"><?php echo $leafTranslation['employeeWorkOrderRateLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="employeeWorkOrderRatePreview"
                                                       id="employeeWorkOrderRatePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="employeeWorkOrderDescriptionDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="employeeWorkOrderDescriptionPreview"><?php echo $leafTranslation['employeeWorkOrderDescriptionLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text"
                                                       name="employeeWorkOrderDescriptionPreview"
                                                       id="employeeWorkOrderDescriptionPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isClientViewableDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="isClientViewablePreview"><?php echo $leafTranslation['isClientViewableLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="isClientViewablePreview"
                                                       id="isClientViewablePreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isAllDayEventDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="isAllDayEventPreview"><?php echo $leafTranslation['isAllDayEventLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="isAllDayEventPreview"
                                                       id="isAllDayEventPreview">
                                            </div>
                                        </div>
                                        <div class="form-group" id="isCompleteDiv">
                                            <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                                   for="isCompletePreview"><?php echo $leafTranslation['isCompleteLabel']; ?></label>

                                            <div class="col-xs-8 col-sm-8 col-md-8">
                                                <input class="form-control input-sm" type="text" name="isCompletePreview"
                                                       id="isCompletePreview">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-danger"
                                            onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>');"><?php echo $t['deleteButtonLabel']; ?></button>
                                    <button type="button"  class="btn btn-default"
                                            onClick="showMeModal('deletePreview', 0);"><?php echo $t['closeButtonLabel']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div align="left" class="btn-group col-md-10 pull-left">
                            <button title="A" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'A');">A
                            </button>
                            <button title="B" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'B');">B
                            </button>
                            <button title="C" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'C');">C
                            </button>
                            <button title="D" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'D');">D
                            </button>
                            <button title="E" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'E');">E
                            </button>
                            <button title="F" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'F');">F
                            </button>
                            <button title="G" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'G');">G
                            </button>
                            <button title="H" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'H');">H
                            </button>
                            <button title="I" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'I');">I
                            </button>
                            <button title="J" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'J');">J
                            </button>
                            <button title="K" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'K');">K
                            </button>
                            <button title="L" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'L');">L
                            </button>
                            <button title="M" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'M');">M
                            </button>
                            <button title="N" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'N');">N
                            </button>
                            <button title="O" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'O');">O
                            </button>
                            <button title="P" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'P');">P
                            </button>
                            <button title="Q" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'Q');">Q
                            </button>
                            <button title="R" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'R');">R
                            </button>
                            <button title="S" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'S');">S
                            </button>
                            <button title="T" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'T');">T
                            </button>
                            <button title="U" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'U');">U
                            </button>
                            <button title="V" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'V');">V
                            </button>
                            <button title="W" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'W');">W
                            </button>
                            <button title="X" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'X');">X
                            </button>
                            <button title="Y" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'Y');">Y
                            </button>
                            <button title="Z" class="btn btn-success btn-sm" type="button" 
                                    onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 'Z');">Z
                            </button>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div align="right" class="pull-right">
                                <div class="btn-group">
                                    <button class="btn btn-warning">
                                        <i class="glyphicon glyphicon-print glyphicon-white"></i>
                                    </button>
                                    <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle btn-sm" type="button" >
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" style="text-align:left">
                                        <li>
                                            <a href="javascript:void(0)"
                                               onClick="reportRequest('<?php echo $leafId; ?>', '<?php
                                               echo $employeeWorkOrder->getControllerPath(
                                               );
                                               ?>', '<?php echo $securityToken; ?>', 'excel');">
                                                <i class="pull-right glyphicon glyphicon-download"></i>&nbsp;&nbsp;Excel 2007&nbsp;&nbsp;
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)"
                                               onClick="reportRequest('<?php echo $leafId; ?>', '<?php
                                               echo $employeeWorkOrder->getControllerPath(
                                               );
                                               ?>', '<?php echo $securityToken; ?>', 'csv');">
                                                <i class="pull-right glyphicon glyphicon-download"></i>CSV
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)"
                                               onClick="reportRequest('<?php echo $leafId; ?>', '<?php
                                               echo $employeeWorkOrder->getControllerPath(
                                               );
                                               ?>', '<?php echo $securityToken; ?>', 'html');">
                                                <i class="pull-right glyphicon glyphicon-download"></i>Html
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
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                <thead>
                                    <tr>
                                        <th width="25px" align="center">
                                <div align="center">#</div>
                                </th>
                                <th width="75px">
                                <div align="center"><?php echo ucfirst($t['actionTextLabel']); ?></div>
                                </th>
                                <th width="125px"><?php echo ucwords($leafTranslation['employeeIdLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['shiftIdLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['invoiceProjectIdLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['milestoneIdLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['documentNumberLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['employeeWorkOrderDateLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['employeeWorkOrderStartDateLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['employeeWorkOrderEndDateLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['employeeWorkOrderDueDateLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['employeeWorkOrderRateLabel']); ?></th>
                                <th><?php echo ucwords($leafTranslation['employeeWorkOrderDescriptionLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['isClientViewableLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['isAllDayEventLabel']); ?></th>
                                <th width="125px"><?php echo ucwords($leafTranslation['isCompleteLabel']); ?></th>
                                <th width="100px">
                                <div align="center"><?php echo ucwords($leafTranslation['executeByLabel']); ?></div>
                                </th>
                                <th width="150px"><?php echo ucwords($leafTranslation['executeTimeLabel']); ?></th>
                                <th width="25px" align="center"><input type="checkbox" name="check_all"
                                                                                       id="check_all" alt="Check Record"
                                                                                       onChange="toggleChecked(this.checked);"></th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($employeeWorkOrderArray)) {
                                            $totalRecord = intval(count($employeeWorkOrderArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    ?>
                                                    <tr>
                                                        <td vAlign="top" align="center">
                                                            <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                        </td>
                                                        <td vAlign="top" align="center">
                                                            <div class="btn-group" align="center">
                                                                <button type="button"  class="btn btn-info btn-sm" title="Edit"
                                                                        onClick="showFormUpdate('<?php echo $leafId; ?>', '<?php
                                                                        echo $employeeWorkOrder->getControllerPath(
                                                                        );
                                                                        ?>', '<?php
                                                                        echo $employeeWorkOrder->getViewPath(
                                                                        );
                                                                        ?>', '<?php echo $securityToken; ?>', '<?php
                                                                        echo intval(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderId']
                                                                        );
                                                                        ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                                                    <i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                                <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                        onClick="showModalDelete('<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderId']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeFirstName']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['shiftDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['invoiceProjectDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['milestoneDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['documentNumber']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderDate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderStartDate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderEndDate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderDueDate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderRate']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['employeeWorkOrderDescription']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['isClientViewable']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['isAllDayEvent']
                                                                        );
                                                                        ?>', '<?php
                                                                        echo rawurlencode(
                                                                                $employeeWorkOrderArray [$i]['isComplete']
                                                                        );
                                                                        ?>');"><i class="glyphicon glyphicon-trash glyphicon-white"></i></button>
                                                            </div>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['employeeFirstName'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['employeeFirstName'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['employeeFirstName']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeFirstName'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['employeeFirstName'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['employeeFirstName']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeFirstName'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['employeeFirstName'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['employeeFirstName'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['shiftDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['shiftDescription'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['shiftDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['shiftDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['shiftDescription'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['shiftDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['shiftDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['shiftDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['shiftDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['invoiceProjectDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['invoiceProjectDescription'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['invoiceProjectDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['invoiceProjectDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['invoiceProjectDescription'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['invoiceProjectDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['invoiceProjectDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['invoiceProjectDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['invoiceProjectDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['milestoneDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['milestoneDescription'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['milestoneDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['milestoneDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['milestoneDescription'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['milestoneDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['milestoneDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['milestoneDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['milestoneDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['documentNumber'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['documentNumber']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['documentNumber']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['documentNumber'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['documentNumber']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['documentNumber']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['documentNumber'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['documentNumber'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['documentNumber'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        if (isset($employeeWorkOrderArray[$i]['employeeWorkOrderDate'])) {
                                                            $valueArray = $employeeWorkOrderArray[$i]['employeeWorkOrderDate'];
                                                            if ($dateConvert->checkDate($valueArray)) {
                                                                $valueData = explode('-', $valueArray);
                                                                $year = $valueData[0];
                                                                $month = $valueData[1];
                                                                $day = $valueData[2];
                                                                $value = date(
                                                                        $systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year)
                                                                );
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td vAlign="top"><?php echo $value; ?></td>
                                                        <?php } else { ?>
                                                            <td vAlign="top">
                                                                <div align="left">&nbsp;</div>
                                                            </td>
                                                        <?php } ?>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['employeeWorkOrderStartDate'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['employeeWorkOrderStartDate']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['employeeWorkOrderStartDate']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeWorkOrderStartDate'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['employeeWorkOrderStartDate']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['employeeWorkOrderStartDate']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeWorkOrderStartDate'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['employeeWorkOrderStartDate'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['employeeWorkOrderStartDate'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['employeeWorkOrderEndDate'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['employeeWorkOrderEndDate']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['employeeWorkOrderEndDate']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeWorkOrderEndDate'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['employeeWorkOrderEndDate']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['employeeWorkOrderEndDate']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeWorkOrderEndDate'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['employeeWorkOrderEndDate'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['employeeWorkOrderEndDate'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        if (isset($employeeWorkOrderArray[$i]['employeeWorkOrderDueDate'])) {
                                                            $valueArray = $employeeWorkOrderArray[$i]['employeeWorkOrderDueDate'];
                                                            if ($dateConvert->checkDate($valueArray)) {
                                                                $valueData = explode('-', $valueArray);
                                                                $year = $valueData[0];
                                                                $month = $valueData[1];
                                                                $day = $valueData[2];
                                                                $value = date(
                                                                        $systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year)
                                                                );
                                                            } else {
                                                                $value = null;
                                                            }
                                                            ?>
                                                            <td vAlign="top"><?php echo $value; ?></td>
                                                        <?php } else { ?>
                                                            <td vAlign="top">
                                                                <div align="left">&nbsp;</div>
                                                            </td>
                                                        <?php } ?>
                                                        <?php
                                                        $d = $employeeWorkOrderArray[$i]['employeeWorkOrderRate'];
                                                        if (class_exists('NumberFormatter')) {
                                                            if (is_array($systemFormat) && $systemFormat['languageCode'] != '') {
                                                                $a = new \NumberFormatter($systemFormat['languageCode'], \NumberFormatter::CURRENCY);
                                                                $d = $a->format($employeeWorkOrderArray[$i]['employeeWorkOrderRate']);
                                                            } else {
                                                                $d = number_format($d) . " You can assign Currency Format ";
                                                            }
                                                        } else {
                                                            $d = number_format($d);
                                                        }
                                                        ?>
                                                        <td vAlign="top">
                                                            <div align="right"><?php echo $d; ?></div>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['employeeWorkOrderDescription'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['employeeWorkOrderDescription']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['employeeWorkOrderDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeWorkOrderDescription'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['employeeWorkOrderDescription']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['employeeWorkOrderDescription']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['employeeWorkOrderDescription'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['employeeWorkOrderDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['employeeWorkOrderDescription'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['isClientViewable'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['isClientViewable']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['isClientViewable']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['isClientViewable'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['isClientViewable']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['isClientViewable']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['isClientViewable'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['isClientViewable'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['isClientViewable'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['isAllDayEvent'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['isAllDayEvent']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['isAllDayEvent']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['isAllDayEvent'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['isAllDayEvent']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['isAllDayEvent']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['isAllDayEvent'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['isAllDayEvent'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['isAllDayEvent'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top">
                                                            <div align="left">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['isComplete'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['isComplete']), strtolower($_POST['query'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['isComplete']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['isComplete'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            strtolower($employeeWorkOrderArray[$i]['isComplete']), strtolower($_POST['character'])
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['isComplete']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['isComplete'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['isComplete'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['isComplete'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <td vAlign="top" align="center">
                                                            <div align="center">
                                                                <?php
                                                                if (isset($employeeWorkOrderArray[$i]['executeBy'])) {
                                                                    if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                        if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['staffName'], $_POST['query']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $employeeWorkOrderArray[$i]['staffName']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['staffName'];
                                                                            }
                                                                        } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                            if (strpos(
                                                                                            $employeeWorkOrderArray[$i]['staffName'], $_POST['character']
                                                                                    ) !== false
                                                                            ) {
                                                                                echo str_replace(
                                                                                        $_POST['query'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $employeeWorkOrderArray[$i]['staffName']
                                                                                );
                                                                            } else {
                                                                                echo $employeeWorkOrderArray[$i]['staffName'];
                                                                            }
                                                                        } else {
                                                                            echo $employeeWorkOrderArray[$i]['staffName'];
                                                                        }
                                                                    } else {
                                                                        echo $employeeWorkOrderArray[$i]['staffName'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                &nbsp;
                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        if (isset($employeeWorkOrderArray[$i]['executeTime'])) {
                                                            $valueArray = $employeeWorkOrderArray[$i]['executeTime'];
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
                                                            ?>
                                                            <td vAlign="top"><?php echo $value; ?></td>
                                                        <?php } else { ?>
                                                            <td>&nbsp;</td>
                                                        <?php } ?>
                                                        <?php
                                                        if ($employeeWorkOrderArray[$i]['isDelete']) {
                                                            $checked = "checked";
                                                        } else {
                                                            $checked = null;
                                                        }
                                                        ?>
                                                        <td vAlign="top">
                                                            <input class="form-control" style="display:none;" type="checkbox"
                                                                   name="employeeWorkOrderId[]"
                                                                   value="<?php echo $employeeWorkOrderArray[$i]['employeeWorkOrderId']; ?>">
                                                            <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                                   value="<?php echo $employeeWorkOrderArray[$i]['isDelete']; ?>">

                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="7" vAlign="top" align="center"><?php
                                                        $employeeWorkOrder->exceptionMessage(
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
                                                    $employeeWorkOrder->exceptionMessage(
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
                                                $employeeWorkOrder->exceptionMessage(
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
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9 pull-left" align="left">
                            <?php $navigation->pagenationv4($offset); ?>
                        </div>
                        <div class="col-md-3 pull-right pagination" align="right">
                            <button type="button"  class="delete btn btn-warning"
                                    onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php
                                    echo $employeeWorkOrder->getControllerPath(
                                    );
                                    ?>', '<?php echo $employeeWorkOrder->getViewPath(); ?>', '<?php echo $securityToken; ?>');">
                                <i class="glyphicon glyphicon-white glyphicon-trash"></i>
                            </button>
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
                </div>
            </div>
        </div>
        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <form class="form-horizontal"><input type="hidden" name="employeeWorkOrderId" id="employeeWorkOrderId" value="<?php
        if (isset($_POST['employeeWorkOrderId'])) {
            echo $_POST['employeeWorkOrderId'];
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
                        <div class="panel-heading">
                            <div align="right">

                                <div class="btn-group">
                                    <button type="button"  id="firstRecordbutton"  class="btn btn-default"
                                            onClick="firstRecord('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                        <i class="glyphicon glyphicon-fast-backward glyphicon-white"></i> <?php echo $t['firstButtonLabel']; ?>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled"
                                            onClick="previousRecord('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                        <i class="glyphicon glyphicon-backward glyphicon-white"></i> <?php echo $t['previousButtonLabel']; ?>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled"
                                            onClick="nextRecord('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                        <i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button"  id="endRecordbutton"  class="btn btn-default"
                                            onClick="endRecord('<?php echo $leafId; ?>', '<?php
                                            echo $employeeWorkOrder->getControllerPath(
                                            );
                                            ?>', '<?php
                                            echo $employeeWorkOrder->getViewPath(
                                            );
                                            ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                        <i class="glyphicon glyphicon-fast-forward glyphicon-white"></i> <?php echo $t['endButtonLabel']; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="employeeIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="employeeId"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['employeeIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="employeeId" id="employeeId" class="form-control input-sm chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($employeeArray)) {
                                                    $totalRecord = intval(count($employeeArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($employeeWorkOrderArray[0]['employeeId'])) {
                                                                if ($employeeWorkOrderArray[0]['employeeId'] == $employeeArray[$i]['employeeId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $employeeArray[$i]['employeeId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $employeeArray[$i]['employeeFirstName']; ?></option>
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
                                            <span class="help-block" id="employeeIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="shiftIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="shiftId"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['shiftIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="shiftId" id="shiftId" class="form-control input-sm chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($shiftArray)) {
                                                    $totalRecord = intval(count($shiftArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($employeeWorkOrderArray[0]['shiftId'])) {
                                                                if ($employeeWorkOrderArray[0]['shiftId'] == $shiftArray[$i]['shiftId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $shiftArray[$i]['shiftId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $shiftArray[$i]['shiftDescription']; ?></option>
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
                                            <span class="help-block" id="shiftIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="invoiceProjectIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="invoiceProjectId"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['invoiceProjectIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="invoiceProjectId" id="invoiceProjectId" class="form-control input-sm chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($invoiceProjectArray)) {
                                                    $totalRecord = intval(count($invoiceProjectArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($employeeWorkOrderArray[0]['invoiceProjectId'])) {
                                                                if ($employeeWorkOrderArray[0]['invoiceProjectId'] == $invoiceProjectArray[$i]['invoiceProjectId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $invoiceProjectArray[$i]['invoiceProjectId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $invoiceProjectArray[$i]['invoiceProjectDescription']; ?></option>
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
                                            <span class="help-block" id="invoiceProjectIdHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="milestoneIdForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="milestoneId"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['milestoneIdLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <select name="milestoneId" id="milestoneId" class="form-control input-sm chzn-select">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($milestoneArray)) {
                                                    $totalRecord = intval(count($milestoneArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            if (isset($employeeWorkOrderArray[0]['milestoneId'])) {
                                                                if ($employeeWorkOrderArray[0]['milestoneId'] == $milestoneArray[$i]['milestoneId']) {
                                                                    $selected = "selected";
                                                                } else {
                                                                    $selected = null;
                                                                }
                                                            } else {
                                                                $selected = null;
                                                            }
                                                            ?>
                                                            <option
                                                                value="<?php echo $milestoneArray[$i]['milestoneId']; ?>" <?php echo $selected; ?>><?php echo $d; ?>
                                                                . <?php echo $milestoneArray[$i]['milestoneDescription']; ?></option>
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
                                            <span class="help-block" id="milestoneIdHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="documentNumberForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="documentNumber"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['documentNumberLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input type="text" name="documentNumber" id="documentNumber"
                                                <?php
                                                if (!isset($_POST['employeeWorkOrderId'])) {
                                                    echo "disabled";
                                                }
                                                ?>
                                                       class=" form-control input-sm <?php
                                                       if (!isset($_POST['employeeWorkOrderId'])) {
                                                           echo "disabled";
                                                       }
                                                       ?>"
                                                       value="<?php
                                                       if (isset($documentNumberArray) && is_array($employeeWorkOrderArray)) {
                                                           if (isset($employeeWorkOrderArray[0]['documentNumber'])) {
                                                               echo htmlentities($employeeWorkOrderArray[0]['documentNumber']);
                                                           }
                                                       }
                                                       ?>"><span class="input-group-addon"><img src="./images/icons/document-number.png"></span>
                                            </div>
                                            <span class="help-block" id="documentNumberHelpMe"></span>
                                        </div>
                                    </div>
                                    <?php
                                    if (isset($employeeWorkOrderArray) && is_array($employeeWorkOrderArray)) {

                                        if (isset($employeeWorkOrderArray[0]['employeeWorkOrderDate'])) {
                                            $valueArray = $employeeWorkOrderArray[0]['employeeWorkOrderDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        }
                                    } else {
                                        $value = null;
                                    }
                                    ?>
                                    <div class="form-group" id="employeeWorkOrderDateForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="employeeWorkOrderDate"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['employeeWorkOrderDateLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="employeeWorkOrderDate"
                                                       id="employeeWorkOrderDate" value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                     id="employeeWorkOrderDateImage"></span></div>
                                            <span class="help-block" id="employeeWorkOrderDateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    Cannot Resolve Type :[ string] Cannot Resolve Type :[ string]
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <?php
                                    if (isset($employeeWorkOrderArray) && is_array($employeeWorkOrderArray)) {

                                        if (isset($employeeWorkOrderArray[0]['employeeWorkOrderDueDate'])) {
                                            $valueArray = $employeeWorkOrderArray[0]['employeeWorkOrderDueDate'];
                                            if ($dateConvert->checkDate($valueArray)) {
                                                $valueData = explode('-', $valueArray);
                                                $year = $valueData[0];
                                                $month = $valueData[1];
                                                $day = $valueData[2];
                                                $value = date($systemFormat['systemSettingDateFormat'], mktime(0, 0, 0, $month, $day, $year));
                                            } else {
                                                $value = null;
                                            }
                                        }
                                    } else {
                                        $value = null;
                                    }
                                    ?>
                                    <div class="form-group" id="employeeWorkOrderDueDateForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="employeeWorkOrderDueDate"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['employeeWorkOrderDueDateLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="employeeWorkOrderDueDate"
                                                       id="employeeWorkOrderDueDate" value="<?php
                                                       if (isset($value)) {
                                                           echo $value;
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/calendar.png"
                                                                                     id="employeeWorkOrderDueDateImage"></span></div>
                                            <span class="help-block" id="employeeWorkOrderDueDateHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="employeeWorkOrderRateForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="employeeWorkOrderRate"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['employeeWorkOrderRateLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <div class="input-group">
                                                <input class="form-control input-sm" type="text" name="employeeWorkOrderRate"
                                                       id="employeeWorkOrderRate" onKeyUp="removeMeError('employeeWorkOrderRate');" value="<?php
                                                       if (isset($employeeWorkOrderArray) && is_array($employeeWorkOrderArray)) {
                                                           if (isset($employeeWorkOrderArray[0]['employeeWorkOrderRate'])) {
                                                               echo htmlentities($employeeWorkOrderArray[0]['employeeWorkOrderRate']);
                                                           }
                                                       }
                                                       ?>">
                                                <span class="input-group-addon"><img src="./images/icons/currency.png"></span></div>
                                            <span class="help-block" id="employeeWorkOrderRateHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="employeeWorkOrderDescriptionForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2"
                                               for="employeeWorkOrderDescription"><strong><?php
                                                       echo ucfirst(
                                                               $leafTranslation['employeeWorkOrderDescriptionLabel']
                                                       );
                                                       ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <textarea class="form-control input-sm" name="employeeWorkOrderDescription"
                                                      id="employeeWorkOrderDescription" onKeyUp="removeMeError('employeeWorkOrderDescription');">
                                                          <?php
                                                          if (isset($employeeWorkOrderArray[0]['employeeWorkOrderDescription'])) {
                                                              echo htmlentities($employeeWorkOrderArray[0]['employeeWorkOrderDescription']);
                                                          }
                                                          ?></textarea>
                                            <span class="help-block" id="employeeWorkOrderDescriptionHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="isClientViewableForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="isClientViewable"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isClientViewableLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <input class="form-control input-sm" type="checkbox" name="isClientViewable" id="isClientViewable"
                                                   value="<?php
                                                   if (isset($employeeWorkOrderArray) && is_array($employeeWorkOrderArray)) {
                                                       if (isset($employeeWorkOrderArray[0]['isClientViewable'])) {
                                                           echo $employeeWorkOrderArray[0]['isClientViewable'];
                                                       }
                                                   }
                                                   ?>">
                                            <span class="help-block" id="isClientViewableHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group" id="isAllDayEventForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="isAllDayEvent"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isAllDayEventLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <input class="form-control input-sm" type="checkbox" name="isAllDayEvent" id="isAllDayEvent"
                                                   value="<?php
                                                   if (isset($employeeWorkOrderArray) && is_array($employeeWorkOrderArray)) {
                                                       if (isset($employeeWorkOrderArray[0]['isAllDayEvent'])) {
                                                           echo $employeeWorkOrderArray[0]['isAllDayEvent'];
                                                       }
                                                   }
                                                   ?>">
                                            <span class="help-block" id="isAllDayEventHelpMe"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="isCompleteForm">
                                        <label class="control-label col-xs-2 col-sm-2 col-md-2" for="isComplete"><strong><?php
                                                echo ucfirst(
                                                        $leafTranslation['isCompleteLabel']
                                                );
                                                ?></strong></label>

                                        <div class="control-label col-xs-4 col-sm-4 col-md-4">
                                            <input class="form-control input-sm" type="checkbox" name="isComplete" id="isComplete"
                                                   value="<?php
                                                   if (isset($employeeWorkOrderArray) && is_array($employeeWorkOrderArray)) {
                                                       if (isset($employeeWorkOrderArray[0]['isComplete'])) {
                                                           echo $employeeWorkOrderArray[0]['isComplete'];
                                                       }
                                                   }
                                                   ?>">
                                            <span class="help-block" id="isCompleteHelpMe"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer" align="center">
                            <div class="btn-group" align="left">
                                <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                        class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?> </a>
                                <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled"
                                   data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu" style="text-align:left">
                                    <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?></a></li>
                                    <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a></li>
                                    <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];            ?></a> </li>-->
                                    <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];            ?></a> </li>-->
                                    <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i
                                                class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?> </a></li>
                                </ul>
                            </div>
                            <div class="btn-group" align="left">
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
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i
                                        class="glyphicon glyphicon-trash glyphicon-white"></i> <?php echo $t['deleteButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="resetRecordbutton"  class="btn btn-info"
                                        onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                        echo $employeeWorkOrder->getControllerPath(
                                        );
                                        ?>', '<?php
                                        echo $employeeWorkOrder->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                    <i class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?> </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="listRecordbutton"  class="btn btn-info"
                                        onClick="showGrid('<?php echo $leafId; ?>', '<?php
                                        echo $employeeWorkOrder->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);"><i
                                        class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?> </button>
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
            <script type="text/javascript">
                $(document).keypress(function(e) {
    <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                        // shift+n new record event
                        if (e.which === 78 && e.which === 18  && e.shiftKey) {
                            


                            newRecord(<?php echo $leafId; ?>, '<?php echo $employeeWorkOrder->getControllerPath(); ?>', '<?php echo $employeeWorkOrder->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1);

                            return false;
                        }
    <?php } ?>
                    // shift+s save event
                    if (e.which === 83 && e.which === 18  && e.shiftKey) {
                        

    <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                            updateRecord(<?php echo $leafId; ?>, '<?php echo $employeeWorkOrder->getControllerPath(); ?>', '<?php echo $employeeWorkOrder->getViewPath(); ?>', '<?php echo $securityToken; ?>', 1, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
    <?php } ?>
                        return false;
                    }
                    // shift+d delete event
                    if (e.which === 88 && e.which === 18 && e.shiftKey) {
                        

    <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                            deleteRecord(<?php echo $leafId; ?>, '<?php echo $employeeWorkOrder->getControllerPath(); ?>', '<?php echo $employeeWorkOrder->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessDeleteValue']; ?>);

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
                            previousRecord(<?php echo $leafId; ?>, '<?php echo $employeeWorkOrder->getControllerPath(); ?>', '<?php echo $employeeWorkOrder->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                            
                            return false;
                            break;
                        case 39:
                            nextRecord(<?php echo $leafId; ?>, '<?php echo $employeeWorkOrder->getControllerPath(); ?>', '<?php echo $employeeWorkOrder->getViewPath(); ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);
                            
                            return false;
                            break;
                    }
                    

                });
                $(document).ready(function() {
                    window.scrollTo(0, 0);
                    $(".chzn-select").chosen({search_contains: true});
                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                    validateMeNumeric('employeeWorkOrderId');
                    validateMeNumeric('employeeId');
                    validateMeNumeric('shiftId');
                    validateMeNumeric('invoiceProjectId');
                    validateMeNumeric('milestoneId');
                    
                    $('#employeeWorkOrderDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    $('#employeeWorkOrderDueDate').datepicker({
                        format: "<?php echo $systemFormat['systemSettingDateFormat']; ?>"
                    }).on('changeDate', function() {
                        $(this).datepicker('hide');
                    });
                    validateMeCurrency('employeeWorkOrderRate');
                    validateMeNumeric('isClientViewable');
                    validateMeNumeric('isAllDayEvent');
                    validateMeNumeric('isComplete');
    <?php if ($_POST['method'] == "new") { ?>
                        $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            ;
                            $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                            $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                            $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                            $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                            $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                            $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
        <?php } else { ?>
                            $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                            $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        <?php } ?>
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
    <?php } else if ($_POST['employeeWorkOrderId']) { ?>
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $employeeWorkOrder->getControllerPath(); ?>','<?php echo $employeeWorkOrder->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>);");
        <?php } else { ?>
                            $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
                });
            </script>
        </div></form>
<?php } ?>
<script type="text/javascript" src="./v3/humanResource/workOrder/javascript/employeeWorkOrder.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>