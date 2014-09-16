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
require_once($newFakeDocumentRoot . "v3/portal/main/controller/staffWebAccessController.php");
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
$translator->setCurrentTable('staffWebAccess');
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
$staffWebAccessNative = $arrayInfo['moduleNative'];
$leafNative = $arrayInfo['leafNative'];
$translator->createLeafBookmark('', '', '', $leafId);
$systemFormat = $translator->getSystemFormat();
$t = $translator->getDefaultTranslation(); // short because code too long  
$leafTranslation = $translator->getLeafTranslation();
$leafAccess = $translator->getLeafAccess();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $staffWebAccess = new \Core\Portal\Main\StaffWebAccess\Controller\StaffWebAccessClass();
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
        if (isset($_POST ['query'])) {
            $staffWebAccess->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $staffWebAccess->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $staffWebAccess->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $staffWebAccess->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode("-", $_POST ['dateRangeStart']);
            $staffWebAccess->setStartDay($start[2]);
            $staffWebAccess->setStartMonth($start[1]);
            $staffWebAccess->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $staffWebAccess->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode("-", $_POST ['dateRangeEnd']);
            $staffWebAccess->setEndDay($start[2]);
            $staffWebAccess->setEndMonth($start[1]);
            $staffWebAccess->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $staffWebAccess->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])
        ) {
            $staffWebAccess->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $staffWebAccess->setServiceOutput('html');
        $staffWebAccess->setLeafId($leafId);
        $staffWebAccess->execute();
        if ($_POST['method'] == 'read') {
            $staffWebAccess->setStart($offset);
            $staffWebAccess->setLimit($limit); // normal system don't like paging..  
            $staffWebAccess->setPageOutput('html');
            $staffWebAccessArray = $staffWebAccess->read();
            if (isset($staffWebAccessArray [0]['firstRecord'])) {
                $firstRecord = $staffWebAccessArray [0]['firstRecord'];
            }
            if (isset($staffWebAccessArray [0]['nextRecord'])) {
                $nextRecord = $staffWebAccessArray [0]['nextRecord'];
            }
            if (isset($staffWebAccessArray [0]['previousRecord'])) {
                $previousRecord = $staffWebAccessArray [0]['previousRecord'];
            }
            if (isset($staffWebAccessArray [0]['lastRecord'])) {
                $lastRecord = $staffWebAccessArray [0]['lastRecord'];
                $endRecord = $staffWebAccessArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($staffWebAccess->getBrowserViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($staffWebAccessArray [0]['total'])) {
                $total = $staffWebAccessArray [0]['total'];
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
                        $applicationNative, $staffWebAccessNative, $folderNative, $leafNative, $securityToken, $applicationId, $moduleId, $folderId, $leafId
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal" role="form">
                            <input type="text" class="form-control" name="queryWidget" id="queryWidget" class='form-control'
                                   value="<?php
                                   if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                       echo $_POST['query'];
                                   }
                                   ?>"> <br>
                            <input type="button"  name="searchString" id="searchString" value="<?php echo $t['searchButtonLabel']; ?>"
                                   class=" btn btn-info" onClick="ajaxQuerySearchAll(<?php echo $leafId; ?>, '<?php
                                   echo $staffWebAccess->getBrowserViewPath();
                                   ?>', '<?php echo $securityToken; ?>');">
                            <input type="button"  name="clearSearchString" id="clearSearchString"
                                   value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info"
                                   onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                   echo $staffWebAccess->getBrowserViewPath();
                                   ?>', '<?php echo $securityToken; ?>', 0, '<?php echo LIMIT; ?>', 1);"> &nbsp;<br>&nbsp;
                            <h4><img src="./images/icons/calendar.png"> <?php echo $t['dateTextLabel']; ?></h4>
                            <table>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="center"><img src="./images/icons/calendar-select-days-span.png"></td>
                                    <td align="left">
                                        <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                        echo $staffWebAccess->getBrowserViewPath();
                                        ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                        echo date(
                                                'd-m-Y'
                                        )
                                        ?>', 'between', '');"><?php echo strtoupper($t['anyTimeTextLabel']); ?></a>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <tr>
                                    <td align="right">
                                        <a href="javascript:void(0)" rel="tooltip" title='Previous Day <?php echo $previousDay; ?>'
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next');">&laquo;</a>
                                    </td>
                                    <td align="center"><img src="./images/icons/calendar-select-days.png"></td>
                                    <td align="left">
                                        <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                        echo $staffWebAccess->getBrowserViewPath();
                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '');"><?php echo $t['todayTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip" title='Next Day <?php echo $nextDay; ?>'
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <a href="javascript:void(0)" rel="tooltip" title='Previous Week<?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'previous'
                                        );
                                        ?>' onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous');">&laquo;</a>
                                    </td>
                                    <td align="center"><img src="./images/icons/calendar-select-week.png"></td>
                                    <td align="left"><a href="javascript:void(0)" title='<?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'current'
                                        );
                                        ?>' onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                                        echo $staffWebAccess->getBrowserViewPath();
                                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '');"><?php echo $t['weekTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip" title='Next Week <?php
                                        echo $dateConvert->getCurrentWeekInfo(
                                                $dateRangeStart, 'next'
                                        );
                                        ?>' onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <a href="javascript:void(0)" rel="tooltip" title='Previous Month <?php echo $previousMonth; ?>'
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous');">&laquo;</a>
                                    </td>
                                    <td align="center"><img src="./images/icons/calendar-select-month.png"></td>
                                    <td align="left">
                                        <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                        echo $staffWebAccess->getBrowserViewPath();
                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '');"><?php echo $t['monthTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip" title='Next Month <?php echo $nextMonth; ?>'
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <a href="javascript:void(0)" rel="tooltip" title='Previous Year <?php echo $previousYear; ?>'
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous');">&laquo;</a>
                                    </td>
                                    <td align="center"><img src="./images/icons/calendar.png"></td>
                                    <td align="left">
                                        <a href="javascript:void(0)" onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                        echo $staffWebAccess->getBrowserViewPath();
                                        ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '');"><?php echo $t['yearTextLabel']; ?></a>
                                    </td>
                                    <td align="left">
                                        <a href="javascript:void(0)" rel="tooltip" title='Next Year <?php echo $nextYear; ?>'
                                           onClick="ajaxQuerySearchAllDate(<?php echo $leafId; ?>, '<?php
                                           echo $staffWebAccess->getBrowserViewPath();
                                           ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next');">&raquo;</a>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <h5><img src="./images/icons/calendar-select-days-span.png"> <?php echo $t['rangeTextLabel']; ?></h5><br>

                            <div>
                                <input type="text" class="form-control" name='dateRangeStart' id='dateRangeStart'
                                       class='form-control' value="<?php
                                       if (isset($_POST['dateRangeStart'])) {
                                           echo $_POST['dateRangeStart'];
                                       }
                                       ?>"><br>
                                <input type="text" class="form-control" name='dateRangeEnd' id='dateRangeEnd' class='form-control'
                                       value="<?php
                                       if (isset($_POST['dateRangeEnd'])) {
                                           echo $_POST['dateRangeEnd'];
                                       }
                                       ?>"><br>
                                <input type="button"  name='searchDate' id='searchDate' value="<?php echo $t['searchButtonLabel']; ?>"
                                       class=" btn btn-info" onClick="ajaxQuerySearchAllDateRange(<?php echo $leafId; ?>, '<?php
                                       echo $staffWebAccess->getBrowserViewPath();
                                       ?>', '<?php echo $securityToken; ?>');">
                                <input type="button"  name='clearSearchDate' id='clearSearchDate'
                                       value="<?php echo $t['clearButtonLabel']; ?>" class="btn btn-info"
                                       onClick="showGrid(<?php echo $leafId; ?>, '<?php
                                       echo $staffWebAccess->getBrowserViewPath();
                                       ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1);">
                            </div>
                            <br>
                            <h4><?php echo $t['statisticTextLabel']; ?>Browser</h4>
                            <table>
                                <tr>
                                    <td>Internet Explorer</td>
                                    <td align="left">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getInternetExplorer()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Firefox</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getFirefox()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Google Chrome</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getChrome()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Apple Safari</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getSafari()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Others</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getOtherBrowser()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <h5><?php echo $t['statisticTextLabel']; ?>Type</h5>
                            <table>
                                <tr>
                                    <td>Robot</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getRobot()
                                                );
                                                ?></span></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Browser</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getBrowser()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getMobileBrowser()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email Client</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getEmailClient()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wap Browser</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getWapBrowser()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Others</td>
                                    <td align="right">
                                        <div class="pull-right"><span class="label label-info"><?php
                                                echo intval(
                                                        $staffWebAccess->getOthersBrowserType()
                                                );
                                                ?></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div id="rightViewport" class="col-xs-9 col-sm-9 col-md-9">

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <?php
                        // hide first version
                        /**
                         * <div class="col-xs-10 col-sm-10 col-md-10">
                         * <span class="label label-default">Internet Explorer</span>
                         * <span class="label label-success">Mozzila Firefox</span>
                         * <span class="label label-primary">Google Chrome</span>
                         * <span class="label label-warning">Apple Safari</span>
                         * <span class="label label-danger">Others</span>
                         * </div>
                         * */
                        ?>
                        <div class="col-xs-10 col-sm-10 col-md-10">
                            &nbsp;
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class='pull-right'>
                                <div class='btn-group'>
                                    <button class='btn btn-sm btn-warning'>
                                        <i class='glyphicon glyphicon-print glyphicon-white'></i>
                                    </button>
                                    <button data-toggle="dropdown" class='btn btn-sm btn-warning dropdown-toggle'>
                                        <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu'>
                                        <li>
                                            <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                            echo $staffWebAccess->getBrowserControllerPath();
                                            ?>', '<?php echo $securityToken; ?>', 'excel');">
                                                <i class='pull-right glyphicon glyphicon-download'></i>Excel 2007 </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                            echo $staffWebAccess->getBrowserControllerPath();
                                            ?>', '<?php echo $securityToken; ?>', 'csv');">
                                                <i class='pull-right glyphicon glyphicon-download'></i>&nbsp;&nbsp;CSV&nbsp;&nbsp;
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" onClick="reportRequest(<?php echo $leafId; ?>, '<?php
                                            echo $staffWebAccess->getBrowserControllerPath();
                                            ?>', '<?php echo $securityToken; ?>', 'html');">
                                                <i class='pull-right glyphicon glyphicon-download'></i>&nbsp;&nbsp;Html&nbsp;&nbsp;
                                            </a>
                                        </li>
                                    </ul>
                                </div>
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
                    <?php $containerName = 'container' . rand(1, 100); ?>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div id="<?php echo $containerName; ?>" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        &nbsp;
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id='tableData'>
                                <thead>
                                    <tr>
                                        <th align="center">#</th>
                                        <th align="center"><?php echo ucfirst($leafTranslation['staffIdLabel']); ?></th>
                                        <th align="center"><?php echo ucfirst($leafTranslation['ua_iconLabel']); ?></th>
                                        <th align="center"><?php echo ucfirst($leafTranslation['ua_typeLabel']); ?></th>
                                        <th align="center"><?php echo ucfirst($leafTranslation['ua_familyLabel']); ?></th>
                                        <th align="center"><?php echo ucfirst($leafTranslation['ua_versionLabel']); ?></th>
                                        <th align="center"><?php echo ucfirst($leafTranslation['staffWebAccessLogInLabel']); ?></th>
                                        <th align="center"><?php
                                            echo ucfirst(
                                                    $leafTranslation['staffWebAccessLogOutLabel']
                                            );
                                            ?></th>
                                    </tr>
                                </thead>
                                <tbody id=tableBody>
                                    <?php
                                    if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                        if (is_array($staffWebAccessArray)) {
                                            $totalRecord = intval(count($staffWebAccessArray));
                                            if ($totalRecord > 0) {
                                                $counter = 0;
                                                for ($i = 0; $i < $totalRecord; $i++) {
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td align=\"center\">" . ($counter + $offset) . "</td>";
                                                    if (isset($staffWebAccessArray[$i]['staffName'])) {
                                                        echo "<td align=\"center\">" . $staffWebAccessArray[$i]['staffName'] . "</td>";
                                                    } else {
                                                        echo "<td  align=right>&nbsp;</td>";
                                                    }

                                                    if (isset($staffWebAccessArray[$i]['ua_icon'])) {
                                                        echo "<td align=left><img src=\"./images/browser/" . $staffWebAccessArray[$i]['ua_icon'] . "\"></td>";
                                                    } else {
                                                        echo "<td  align=left>&nbsp;</td>";
                                                    }
                                                    if (isset($staffWebAccessArray[$i]['ua_type'])) {
                                                        echo "<td align=left>" . $staffWebAccessArray[$i]['ua_type'] . "</td>";
                                                    } else {
                                                        echo "<td  align=left>&nbsp;</td>";
                                                    }
                                                    if (isset($staffWebAccessArray[$i]['ua_family'])) {
                                                        echo "<td align=left>" . $staffWebAccessArray[$i]['ua_family'] . "</td>";
                                                    } else {
                                                        echo "<td  align=left>&nbsp;</td>";
                                                    }

                                                    if (isset($staffWebAccessArray[$i]['ua_version'])) {
                                                        echo "<td align=left>" . $staffWebAccessArray[$i]['ua_version'] . "</td>";
                                                    } else {
                                                        echo "<td  align=left>&nbsp;</td>";
                                                    }
                                                    if (isset($staffWebAccessArray[$i]['staffWebAccessLogIn'])) {
                                                        $valueArray = $staffWebAccessArray[$i]['staffWebAccessLogIn'];
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
                                                        echo "<td align=left>" . $value . "</td>";
                                                    } else {
                                                        echo "<td  align=left>&nbsp;</td>";
                                                    }
                                                    if (isset($staffWebAccessArray[$i]['staffWebAccessLogOut'])) {
                                                        $valueArray = $staffWebAccessArray[$i]['staffWebAccessLogOut'];
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
                                                        echo "<td align=left>" . $value . "</td>";
                                                    } else {
                                                        echo "<td  align=left>&nbsp;</td>";
                                                    }

                                                    echo "</tr>";
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan='8'><?php
                                                        $staffWebAccess->exceptionMessage(
                                                                $t['recordNotFoundLabel']
                                                        );
                                                        ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan='8'><?php
                                                    $staffWebAccess->exceptionMessage(
                                                            $t['recordNotFoundLabel']
                                                    );
                                                    ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan='8'><?php $staffWebAccess->exceptionMessage($t['loadFailureLabel']); ?></td>
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
                    <div class="col-xs-12 col-sm-12 col-md-12" class="pull-left">
                        <div class=' pagination' id='pagingHtml'><?php $navigation->pagenationv4($offset); ?></div>
                    </div>
                </div>
                <?php
                $between = array();
                $between['IE'] = 0;
                $between['Firefox'] = 0;
                $between['chrome'] = 0;
                $between['safari'] = 0;
                $between['others'] = 0;
                if (isset($_POST ['dateRangeType'])) {
                    if ($_POST ['dateRangeType'] == 'day') {

                        // 24 hours clock

                        $xAxis = "{
                                   
                                    categories: [";
                        $hour = 0;
                        $xAxisInsideText = null;
                        while ($hour++ < 23) {
                            $xAxisInsideText .= "'" . date('G:i:s', mktime($hour, 0, 0, 1, 1, 2011)) . "',";
                        }
                        $xAxis .= substr($xAxisInsideText, 0, -1);
                        $xAxis .= "]
                            , labels: {
                rotation: -45,
                align: 'right',
                style: {
                    font: 'normal 13px Verdana, sans-serif'
                }
            } }
   ";

                        $series = "{
                                    name: 'Internet Explorer',
                                    color: '#058DC7',
                                    type: 'column',
                                    
                                    data: [";
                        $Time = $staffWebAccess->getCrossTabTimeInternetExplorer();
                        $seriesInside = null;
                        $hour = 0;
                        while ($hour++ < 23) {
                            $seriesInside .= $Time[$hour] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -3,
                    y: 10,
                    formatter: function() {
                        return this.y;
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

                                },{
                                    name: 'Firefox',
                                    color: '#50B432',
                                    type: 'column',
                                    
                                    data: [";
                        $Time = $staffWebAccess->getCrossTabTimeFirefox();
                        $seriesInside = null;
                        $hour = 0;
                        while ($hour++ < 23) {
                            $seriesInside .= $Time[$hour] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "]

                                },{
                                    name: 'Chrome',
                                    color: '#ED561B',
                                    type: 'column',
                                    
                                    data: [";
                        $Time = $staffWebAccess->getCrossTabTimeChrome();
                        $seriesInside = null;
                        $hour = 0;
                        while ($hour++ < 23) {
                            $seriesInside .= $Time[$hour] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -3,
                    y: 10,
                    formatter: function() {
                        return this.y;
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

                                },{
                                    name: 'Safari',
                                    color: '#89A54E',
                                    type: 'column',
                                    
                                    data: [";
                        $Time = $staffWebAccess->getCrossTabTimeSafari();
                        $seriesInside = null;
                        $hour = 0;
                        while ($hour++ < 23) {
                            $seriesInside .= $Time[$hour] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -3,
                    y: 10,
                    formatter: function() {
                        return this.y;
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

                                },{
                                    name: 'Others',
                                    color: '#24CBE5',
                                    type: 'column',
                                    
                                    data: [";
                        $Time = $staffWebAccess->getCrossTabTimeOtherBrowser();
                        $seriesInside = null;
                        $hour = 0;
                        while ($hour++ < 23) {
                            $seriesInside .= $Time[$hour] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -3,
                    y: 10,
                    formatter: function() {
                        return this.y;
                    },
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

                                }";
                    } else if ($_POST['dateRangeType'] == 'week') {
                        $xAxis = "{
                                   
                                    categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',
                                        'Sunday']
                                }";
                        // loops weeks
                        $series = "{
                                    name: 'Internet Explorer',
                                    color: '#058DC7',
                                    type: 'column',
                                    
                                    data: [";
                        $Weekly = $staffWebAccess->getCrossTabWeeklyInternetExplorer();
                        $seriesInside = null;
                        for ($i = 0; $i <= 7; $i++) {
                            $seriesInside .= $Weekly[$i] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "]

                                },{
                                    name: 'Firefox',
                                    color: '#50B432',
                                    type: 'column',
                                    
                                    data: [";
                        $Weekly = $staffWebAccess->getCrossTabWeeklyFirefox();
                        $seriesInside = null;
                        for ($i = 0; $i <= 7; $i++) {
                            $seriesInside .= $Weekly[$i] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "]

                                },{
                                    name: 'Chrome',
                                    color: '#ED561B',
                                    type: 'column',
                                    
                                    data: [";
                        $Weekly = $staffWebAccess->getCrossTabWeeklyChrome();
                        $seriesInside = null;
                        for ($i = 0; $i <= 7; $i++) {
                            $seriesInside .= $Weekly[$i] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "]

                                },{
                                    name: 'Safari',
                                    color: '#DDDF00',
                                    type: 'column',
                                    
                                    data: [";
                        $Weekly = $staffWebAccess->getCrossTabWeeklySafari();
                        $seriesInside = null;
                        for ($i = 0; $i <= 7; $i++) {
                            $seriesInside .= $Weekly[$i] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "]

                                },{
                                    name: 'Others',
                                    color: '#24CBE5',
                                    type: 'column',
                                    
                                    data: [";
                        $Weekly = $staffWebAccess->getCrossTabWeeklyOtherBrowser();
                        $seriesInside = null;
                        for ($i = 0; $i <= 7; $i++) {
                            $seriesInside .= $Weekly[$i] . ",";
                        }
                        $series .= substr($seriesInside, 0, -1);
                        $series .= "]

                                }";
                    } else {
                        if ($_POST['dateRangeType'] == 'month') {
                            // loops month
                            $xAxis = "{

                                    categories: [";
                            $arrayDate = explode("-", $_POST['dateRangeStart']);
                            $day = $arrayDate[0];
                            $month = $arrayDate[1];
                            $year = $arrayDate[2];
                            $totalDayInMonth = date('t', mktime(0, 0, 0, $month, $day, $year));
                            for ($i = 1; $i <= $totalDayInMonth; $i++) {
                                $xAxisInside .= $i . ",";
                            }
                            $xAxis .= substr($xAxisInside, 0, -1);
                            $xAxis .= "]
                               } ";

                            $series = "{
                                    name: 'Internet Explorer',
                                    color: '#058DC7',
                                    type: 'column',

                                    data: [";
                            $daily = $staffWebAccess->getCrossTabDailyInternetExplorer();

                            $seriesInside = null;
                            for ($i = 1; $i <= $totalDayInMonth; $i++) {
                                $seriesInside .= ($daily[$i] + 0) . ",";
                            }
                            $series .= substr($seriesInside, 0, -1);
                            $series .= "]

                                },{
                                    name: 'Firefox',
                                    color: '#50B432',
                                    type: 'column',

                                    data: [";
                            $daily = $staffWebAccess->getCrossTabDailyFirefox();
                            $seriesInside = null;
                            for ($i = 1; $i <= $totalDayInMonth; $i++) {
                                $seriesInside .= ($daily[$i] + 0) . ",";
                            }
                            $series .= substr($seriesInside, 0, -1);
                            $series .= "]

                                },{
                                    name: 'Chrome',
                                    color: '#ED561B',
                                    type: 'column',

                                    data: [";
                            $daily = $staffWebAccess->getCrossTabDailyChrome();
                            $seriesInside = null;
                            for ($i = 1; $i <= $totalDayInMonth; $i++) {
                                $seriesInside .= ($daily[$i] + 0) . ",";
                            }
                            $series .= substr($seriesInside, 0, -1);
                            $series .= "]

                                },{
                                    name: 'Safari',
                                    color: '#DDDF00',
                                    type: 'column',

                                    data: [";
                            $daily = $staffWebAccess->getCrossTabDailySafari();
                            $seriesInside = null;
                            for ($i = 1; $i <= $totalDayInMonth; $i++) {
                                $seriesInside .= ($daily[$i] + 0) . ",";
                            }
                            $series .= substr($seriesInside, 0, -1);
                            $series .= "]

                                },{
                                    name: 'Others',
                                    color: '#24CBE5',
                                    type: 'column',

                                    data: [";
                            $daily = $staffWebAccess->getCrossTabDailyOtherBrowser();
                            $seriesInside = null;
                            for ($i = 1; $i <= $totalDayInMonth; $i++) {
                                $seriesInside .= ($daily[$i] + 0) . ",";
                            }
                            $series .= substr($seriesInside, 0, -1);
                            $series .= "]

                                }";
                        } else {
                            if ($_POST['dateRangeType'] == 'year') {
                                // loops years +-3
                                $xAxis = "{

                                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                }";
                                $series = "{
                                    name: 'Internet Explorer',
                                    color: '#058DC7',
                                    type: 'column',
                                    data: [";
                                $Monthly = $staffWebAccess->getCrossTabMonthlyInternetExplorer();

                                $series .= ($Monthly['jan'] + 0) . "," . ($Monthly['feb'] + 0) . "," . ($Monthly['mac'] + 0) . "," . ($Monthly['apr'] + 0) . "," . ($Monthly['may'] + 0) . "," . ($Monthly['jun'] + 0) . "," . ($Monthly['jul'] + 0) . "," . ($Monthly['aug'] + 0) . "," . ($Monthly['sep'] + 0) . "," . ($Monthly['oct'] + 0) . "," . ($Monthly['nov'] + 0) . "," . ($Monthly['dec'] + 0);
                                $series .= "]

                                },{
                                    name: 'Firefox',
                                    color: '#50B432',
                                    type: 'column',
                                    data: [";
                                $Monthly = $staffWebAccess->getCrossTabMonthlyFirefox();
                                $series .= ($Monthly['jan'] + 0) . "," . ($Monthly['feb'] + 0) . "," . ($Monthly['mac'] + 0) . "," . ($Monthly['apr'] + 0) . "," . ($Monthly['may'] + 0) . "," . ($Monthly['jun'] + 0) . "," . ($Monthly['jul'] + 0) . "," . ($Monthly['aug'] + 0) . "," . ($Monthly['sep'] + 0) . "," . ($Monthly['oct'] + 0) . "," . ($Monthly['nov'] + 0) . "," . ($Monthly['dec'] + 0);

                                $series .= "]

                                },{
                                    name: 'Chrome',
                                    color: '#ED561B',
                                    type: 'column',
                                    data: [";
                                $Monthly = $staffWebAccess->getCrossTabMonthlyChrome();
                                $series .= ($Monthly['jan'] + 0) . "," . ($Monthly['feb'] + 0) . "," . ($Monthly['mac'] + 0) . "," . ($Monthly['apr'] + 0) . "," . ($Monthly['may'] + 0) . "," . ($Monthly['jun'] + 0) . "," . ($Monthly['jul'] + 0) . "," . ($Monthly['aug'] + 0) . "," . ($Monthly['sep'] + 0) . "," . ($Monthly['oct'] + 0) . "," . ($Monthly['nov'] + 0) . "," . ($Monthly['dec'] + 0);

                                $series .= "]

                                },{
                                    name: 'Safari',
                                    color: '#DDDF00',
                                    type: 'column',
                                    data: [ ";
                                $Monthly = $staffWebAccess->getCrossTabMonthlySafari();
                                $series .= ($Monthly['jan'] + 0) . "," . ($Monthly['feb'] + 0) . "," . ($Monthly['mac'] + 0) . "," . ($Monthly['apr'] + 0) . "," . ($Monthly['may'] + 0) . "," . ($Monthly['jun'] + 0) . "," . ($Monthly['jul'] + 0) . "," . ($Monthly['aug'] + 0) . "," . ($Monthly['sep'] . "," . ($Monthly['oct'] + 0) . "," . $Monthly['nov'] + 0) . "," . ($Monthly['dec'] + 0);

                                $series .= "]

                                },{
                                    name: 'Others',
                                    color: '#24CBE5',
                                    type: 'column',
                                    data: [ ";
                                $Monthly = $staffWebAccess->getCrossTabMonthlyOtherBrowser();
                                $series .= $Monthly['jan'] . "," . ($Monthly['feb'] + 0) . "," . ($Monthly['mac'] + 0) . "," . ($Monthly['apr'] + 0) . "," . ($Monthly['may'] + 0) . "," . ($Monthly['jun'] + 0) . "," . $Monthly['jul'] . "," . $Monthly['aug'] . "," . $Monthly['sep'] . "," . $Monthly['oct'] . "," . $Monthly['nov'] . "," . $Monthly['dec'];

                                $series .= "]

                                }";
                            } else {
                                if ($_POST['dateRangeType'] == 'between') {

                                    $between = $staffWebAccess->getCrossTabRangeAllBrowser();
                                    $xAxis = "{

                                    categories: ['Internet Explorer', 'Firefox', 'Chrome', 'Safari', 'Others']
                                }";
                                    $series = "{
                                    name: 'Browser',
                                    color: '#058DC7',
                                    type: 'column',
                                    data: [" . ($between['IE'] + 0) . "," . ($between['Firefox'] + 0) . "," . ($between['chrome'] + 0) . "," . ($between['safari'] + 0) . "," . ($between['others'] + 0) . "]

                                }";
                                }
                            }
                        }
                    }
                } else {

                    // if npt specify.automatic used year
                    $staffWebAccess->setDateRangeStartQuery('01-01-1970'); // optional for first page

                    $staffWebAccess->setDateRangeEndQuery(date('d-m-Y')); // optional for first page
                    $between = array();
                    $between['IE'] = 0;
                    $between['Firefox'] = 0;
                    $between['chrome'] = 0;
                    $between['safari'] = 0;
                    $between['others'] = 0;
                    $between = $staffWebAccess->getCrossTabRangeAllBrowser();
                    $xAxis = "{
                                   
                                    categories: ['Internet Explorer', 'Firefox', 'Chrome', 'Safari', 'Others']
                                }";
                    $series = "{
                                    name: 'Browser',
                                    color: '#058DC7',
                                    type: 'column',
                                    data: [" . intval($between['IE'] + 0) . "," . @intval(
                                    $between['Firefox'] + 0
                            ) . "," . intval($between['chrome'] + 0) . "," . intval($between['safari'] + 0) . "," . intval(
                                    $between['others'] + 0
                            ) . "]

                                }";
                }
                ?>
                <script type='text/javascript'>
                    $(document).ready(function() {
                        $('#dateRangeStart').datepicker({
                            format: 'd-m-yyyy'
                        });
                        $('#dateRangeEnd').datepicker({
                            format: 'd-m-yyyy'
                        });

        <?php
        $yAxis = '';
        echo $staffWebAccess->lineGraphs($containerName, 'Browser Statistic', 'Total Browser', $xAxis, $yAxis, $series)
        ?>
                    });
                </script>
            </div>
        </div>
        <?php
    }
}
?>
<script type='text/javascript' src='./v3/portal/main/javascript/staffWebAccess.js'></script>
