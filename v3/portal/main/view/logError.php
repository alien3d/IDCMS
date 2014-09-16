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
require_once($newFakeDocumentRoot . "v3/portal/main/controller/logErrorController.php");
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
$translator->setCurrentTable('logError');
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
$logErrorArray = array();
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $logError = new \Core\Portal\Main\LogError\Controller\LogErrorClass();
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
            $logError->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $logError->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $logError->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $logError->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeStart']);
            $logError->setStartDay($start[2]);
            $logError->setStartMonth($start[1]);
            $logError->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $logError->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year 
            $start = explode('-', $_POST ['dateRangeEnd']);
            $logError->setEndDay($start[2]);
            $logError->setEndMonth($start[1]);
            $logError->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $logError->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $logError->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $logError->setServiceOutput('html');
        $logError->setLeafId($leafId);
        $logError->execute();
        if ($_POST['method'] == 'read') {
            $logError->setStart($offset);
            $logError->setLimit($limit); // normal system don't like paging..  
            $logError->setPageOutput('html');
            $logErrorArray = $logError->read();
            if (isset($logErrorArray [0]['firstRecord'])) {
                $firstRecord = $logErrorArray [0]['firstRecord'];
            }
            if (isset($logErrorArray [0]['nextRecord'])) {
                $nextRecord = $logErrorArray [0]['nextRecord'];
            }
            if (isset($logErrorArray [0]['previousRecord'])) {
                $previousRecord = $logErrorArray [0]['previousRecord'];
            }
            if (isset($logErrorArray [0]['lastRecord'])) {
                $lastRecord = $logErrorArray [0]['lastRecord'];
                $endRecord = $logErrorArray [0]['lastRecord'];
            }
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($logError->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($logErrorArray [0]['total'])) {
                $total = $logErrorArray [0]['total'];
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
        <div class="row">
            <div align="left" class="btn-group col-xs-10 col-sm-10 col-md-10 pull-left">
                <button title="A" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'A')">A
                </button>
                <button title="B" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'B')">B
                </button>
                <button title="C" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'C')">C
                </button>
                <button title="D" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'D')">D
                </button>
                <button title="E" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'E')">E
                </button>
                <button title="F" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'F')">F
                </button>
                <button title="G" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'G')">G
                </button>
                <button title="H" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'H')">H
                </button>
                <button title="I" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'I')">I
                </button>
                <button title="J" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'J')">J
                </button>
                <button title="K" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'K')">K
                </button>
                <button title="L" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'L')">L
                </button>
                <button title="M" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'M')">M
                </button>
                <button title="N" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'N')">N
                </button>
                <button title="O" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'O')">O
                </button>
                <button title="P" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'P')">P
                </button>
                <button title="Q" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'Q')">Q
                </button>
                <button title="R" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'R')">R
                </button>
                <button title="S" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'S')">S
                </button>
                <button title="T" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'T')">T
                </button>
                <button title="U" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'U')">U
                </button>
                <button title="V" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'V')">V
                </button>
                <button title="W" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'W')">W
                </button>
                <button title="X" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'X')">X
                </button>
                <button title="Y" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'Y')">Y
                </button>
                <button title="Z" class="btn btn-success" type="button" 
                        onClick="ajaxQuerySearchAllCharacter('<?php echo $leafId; ?>', '<?php
                        echo $logError->getViewPath(
                        );
                        ?>', '<?php echo $securityToken; ?>', 'Z')">Z
                </button>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div align="right" class="pull-right">
                    <div class="btn-group">
                        <button class="btn btn-warning" type="button" >
                            <i class="glyphicon glyphicon-print glyphicon-white"></i>
                        </button>
                        <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle" type="button" >
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="text-align:left">
                            <li>
                                <a href="javascript:void(0)"
                                   onClick="reportRequest('<?php echo $leafId; ?>', '<?php
                                   echo $logError->getControllerPath(
                                   );
                                   ?>', '<?php echo $securityToken; ?>', 'excel')">
                                    <i class="pull-right glyphicon glyphicon-download"></i>Excel 2007
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                   onClick="reportRequest('<?php echo $leafId; ?>', '<?php
                                   echo $logError->getControllerPath(
                                   );
                                   ?>', '<?php echo $securityToken; ?>', 'csv')">
                                    <i class="pull-right glyphicon glyphicon-download"></i>CSV
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                   onClick="reportRequest('<?php echo $leafId; ?>', '<?php
                                   echo $logError->getControllerPath(
                                   );
                                   ?>', '<?php echo $securityToken; ?>', 'html')">
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
            <div id="leftViewportDetail" class="col-xs-3 col-sm-3 col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">
         <input type="text" name="queryWidget" id="queryWidget"
                                                                class="form-control"
                                                                value="<?php
                                                                if (isset($_POST['query'])) {
                                                                    echo $_POST['query'];
                                                                }
                                                                ?>">
                        <br>
                        <button type="button"  name="searchString" id="searchString" class="btn btn-warning btn-block"
                                onClick="ajaxQuerySearchAll('<?php echo $leafId; ?>', '<?php
                                echo $logError->getViewPath(
                                );
                                ?>', '<?php echo $securityToken; ?>')"
                                value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                        <button type="button"  name="clearSearchString" id="clearSearchString" class="btn btn-info btn-block"
                                onClick="showGrid('<?php echo $leafId; ?>', '<?php
                                echo $logError->getViewPath(
                                );
                                ?>', '<?php echo $securityToken; ?>', '0', '<?php echo LIMIT; ?>', 1)"
                                value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                        <br>

                        <table class="table table-striped table-condensed table-hover">
                            <tr>
                                <td>&nbsp;</td>
                                <td align="center"><img src="./images/icons/calendar-select-days-span.png"
                                                        alt="<?php echo $t['allDay'] ?>"></td>
                                <td align="center"><a href="javascript:void(0)" rel="tooltip"
                                                      onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                      echo $logError->getViewPath(
                                                      );
                                                      ?>', '<?php echo $securityToken; ?>', '01-01-1979', '<?php
                                                      echo date(
                                                              'd-m-Y'
                                                      );
                                                      ?>', 'between', '')"><?php echo $t['anyTimeTextLabel']; ?></a></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                     title="Previous Day <?php echo $previousDay; ?>"
                                                     onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                     echo $logError->getViewPath(
                                                     );
                                                     ?>', '<?php echo $securityToken; ?>', '<?php echo $previousDay; ?>', '', 'day', 'next')">&laquo;</a>
                                </td>
                                <td align="center"><img src="./images/icons/calendar-select-days.png"
                                                        alt="<?php echo $t['day'] ?>"></td>
                                <td align="center"><a href="javascript:void(0)"
                                                      onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                      echo $logError->getViewPath(
                                                      );
                                                      ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'day', '')"><?php echo $t['todayTextLabel']; ?></a>
                                </td>
                                <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                    title="Next Day <?php echo $nextDay; ?>"
                                                    onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                    echo $logError->getViewPath(
                                                    );
                                                    ?>', '<?php echo $securityToken; ?>', '<?php echo $nextDay; ?>', '', 'day', 'next')">&raquo;</a>
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
                                                     echo $logError->getViewPath(
                                                     );
                                                     ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartPreviousWeekStartDay; ?>', '<?php echo $dateRangeEndPreviousWeekEndDay; ?>', 'week', 'previous')">&laquo;</a>
                                </td>
                                <td align="center"><img src="./images/icons/calendar-select-week.png"
                                                        alt="<?php echo $t['week'] ?>"></td>
                                <td align="center"><a href="javascript:void(0)" rel="tooltip"
                                                      title="<?php
                                                      echo $dateConvert->getCurrentWeekInfo(
                                                              $dateRangeStart, 'current'
                                                      );
                                                      ?>"
                                                      onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                      echo $logError->getViewPath(
                                                      );
                                                      ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStartDay; ?>', '<?php echo $dateRangeEndDay; ?>', 'week', '')"><?php echo $t['weekTextLabel']; ?></a>
                                </td>
                                <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                    title="Next Week <?php
                                                    echo $dateConvert->getCurrentWeekInfo(
                                                            $dateRangeStart, 'next'
                                                    );
                                                    ?>"
                                                    onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                    echo $logError->getViewPath(
                                                    );
                                                    ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeEndForwardWeekStartDay; ?>', '<?php echo $dateRangeEndForwardWeekEndDay; ?>', 'week', 'next')">&raquo;</a>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                     title="Previous Month <?php echo $previousMonth; ?>"
                                                     onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                     echo $logError->getViewPath(
                                                     );
                                                     ?>', '<?php echo $securityToken; ?>', '<?php echo $previousMonth; ?>', '', 'month', 'previous')">&laquo;</a>
                                </td>
                                <td align="center"><img src="./images/icons/calendar-select-month.png"
                                                        alt="<?php echo $t['month'] ?>"></td>
                                <td align="center"><a href="javascript:void(0)"
                                                      onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                      echo $logError->getViewPath(
                                                      );
                                                      ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'month', '')"><?php echo $t['monthTextLabel']; ?></a>
                                </td>
                                <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                    title="Next Month <?php echo $nextMonth; ?>"
                                                    onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                    echo $logError->getViewPath(
                                                    );
                                                    ?>', '<?php echo $securityToken; ?>', '<?php echo $nextMonth; ?>', '', 'month', 'next')">&raquo;</a>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><a href="javascript:void(0)" rel="tooltip"
                                                     title="Previous Year <?php echo $previousYear; ?>"
                                                     onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                     echo $logError->getViewPath(
                                                     );
                                                     ?>', '<?php echo $securityToken; ?>', '<?php echo $previousYear; ?>', '', 'year', 'previous')">&laquo;</a>
                                </td>
                                <td align="center"><img src="./images/icons/calendar.png" alt="<?php echo $t['year'] ?>">
                                </td>
                                <td align="center"><a href="javascript:void(0)"
                                                      onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                      echo $logError->getViewPath(
                                                      );
                                                      ?>', '<?php echo $securityToken; ?>', '<?php echo $dateRangeStart; ?>', '', 'year', '')"><?php echo $t['yearTextLabel']; ?></a>
                                </td>
                                <td align="left"><a href="javascript:void(0)" rel="tooltip"
                                                    title="Next Year <?php echo $nextYear; ?>"
                                                    onClick="ajaxQuerySearchAllDate('<?php echo $leafId; ?>', '<?php
                                                    echo $logError->getViewPath(
                                                    );
                                                    ?>', '<?php echo $securityToken; ?>', '<?php echo $nextYear; ?>', '', 'year', 'next')">&raquo;</a>
                                </td>
                            </tr>
                        </table>


                        <div>
                            <label for="dateRangeStart"></label><input type="text" name="dateRangeStart" id="dateRangeStart"
                                                                       class="form-control"
                                                                       value="<?php
                                                                       if (isset($_POST['dateRangeStart'])) {
                                                                           echo $_POST['dateRangeStart'];
                                                                       }
                                                                       ?>" onClick="topPage(125)" placeholder="<?php echo $t['dateRangeStartTextLabel']; ?>"><br>
                            <label for="dateRangeEnd"></label><input type="text" name="dateRangeEnd" id="dateRangeEnd"
                                                                     class="form-control"
                                                                     value="<?php
                                                                     if (isset($_POST['dateRangeEnd'])) {
                                                                         echo $_POST['dateRangeEnd'];
                                                                     }
                                                                     ?>" onClick="topPage(150)" placeholder="<?php echo $t['dateRangeEndTextLabel']; ?>"><br>
                            <button type="button"  name="searchDate" id="searchDate" class="btn btn-warning btn-block"
                                    onClick="ajaxQuerySearchAllDateRange('<?php echo $leafId; ?>', '<?php
                                    echo $logError->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>')"
                                    value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                            <button type="button"  name="clearSearchDate" id="clearSearchDate" class="btn btn-info btn-block"
                                    onClick="showGrid('<?php echo $leafId; ?>', '<?php
                                    echo $logError->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"
                                    value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                        </div>
                        
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
                                    <input type="hidden" name="logErrorIdPreview" id="logErrorIdPreview">

                                    <div class="form-group" id="applicationIdDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="applicationIdPreview"><?php echo $leafTranslation['applicationIdLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="applicationIdPreview"
                                                   id="applicationIdPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="moduleIdDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="moduleIdPreview"><?php echo $leafTranslation['moduleIdLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="moduleIdPreview" id="moduleIdPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="folderIdDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="folderIdPreview"><?php echo $leafTranslation['folderIdLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="folderIdPreview" id="folderIdPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="leafIdDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="leafIdPreview"><?php echo $leafTranslation['leafIdLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="leafIdPreview" id="leafIdPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="roleIdDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="roleIdPreview"><?php echo $leafTranslation['roleIdLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="roleIdPreview" id="roleIdPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="staffIdDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="staffIdPreview"><?php echo $leafTranslation['staffIdLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="staffIdPreview" id="staffIdPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="logErrorOperationDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="logErrorOperationPreview"><?php echo $leafTranslation['logErrorOperationLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="logErrorOperationPreview"
                                                   id="logErrorOperationPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="logErrorsqlDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="logErrorsqlPreview"><?php echo $leafTranslation['logErrorsqlLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="logErrorsqlPreview"
                                                   id="logErrorsqlPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="logErrordateDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="logErrordatePreview"><?php echo $leafTranslation['logErrordateLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="logErrordatePreview"
                                                   id="logErrordatePreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="logErrorAccessDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="logErrorAccessPreview"><?php echo $leafTranslation['logErrorAccessLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="logErrorAccessPreview"
                                                   id="logErrorAccessPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="logErrorDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="logErrorPreview"><?php echo $leafTranslation['logErrorLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="logErrorPreview" id="logErrorPreview">
                                        </div>
                                    </div>
                                    <div class="form-group" id="logErrorguidDiv">
                                        <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                               for="logErrorguidPreview"><?php echo $leafTranslation['logErrorguidLabel']; ?></label>

                                        <div class="col-lg-8">
                                            <input class="form-control" type="text" name="logErrorguidPreview"
                                                   id="logErrorguidPreview">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button"  class="btn btn-danger"
                                        onClick="deleteGridRecord('<?php echo $leafId; ?>', '<?php
                                        echo $logError->getControllerPath(
                                        );
                                        ?>', '<?php echo $logError->getViewPath(); ?>', '<?php echo $securityToken; ?>')"
                                        value="<?php echo $t['deleteButtonLabel']; ?>"><?php echo $t['deleteButtonLabel']; ?></button>
                                <button type="button"  class="btn btn-default" onClick="showMeModal('deletePreview', 0)"
                                        value="<?php echo $t['closeButtonLabel']; ?>"><?php echo $t['closeButtonLabel']; ?></button>
                            </div>
                        </div>
                    </div>
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
                            <th width="125px"><?php echo ucwords($leafTranslation['applicationIdLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['moduleIdLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['folderIdLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['leafIdLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['roleIdLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['staffIdLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['logErrorOperationLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['logErrorsqlLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['logErrordateLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['logErrorAccessLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['logErrorLabel']); ?></th>
                            <th width="125px"><?php echo ucwords($leafTranslation['logErrorguidLabel']); ?></th>
                            <th width="25px" align="center"><input type="checkbox" name="check_all"
                                                                                   id="check_all" alt="Check Record"
                                                                                   onClick="toggleChecked(this.checked)"></th>
                            </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                if ($_POST['method'] == 'read' && $_POST['type'] == 'list' && $_POST['detail'] == 'body') {
                                    if (is_array($logErrorArray)) {
                                        $totalRecord = intval(count($logErrorArray));
                                        if ($totalRecord > 0) {
                                            $counter = 0;
                                            for ($i = 0; $i < $totalRecord; $i++) {
                                                $counter++;
                                                ?>
                                                <tr <?php
                                                if ($logErrorArray[$i]['isDelete'] == 1) {
                                                    echo "class=\"danger\"";
                                                } else {
                                                    if ($logErrorArray[$i]['isDraft'] == 1) {
                                                        echo "class=\"warning\"";
                                                    }
                                                }
                                                ?>>
                                                    <td vAlign="top" align="center">
                                                        <div align="center"><?php echo($counter + $offset); ?>.</div>
                                                    </td>
                                                    <td vAlign="top" align="center">
                                                        <div class="btn-group" align="center">
                                                            <button type="button"  class="btn btn-warning btn-sm" title="Edit"
                                                                    onClick="showFormUpdate('<?php echo $leafId; ?>', '<?php
                                                                    echo $logError->getControllerPath(
                                                                    );
                                                                    ?>', '<?php
                                                                    echo $logError->getViewPath(
                                                                    );
                                                                    ?>', '<?php echo $securityToken; ?>', '<?php
                                                                    echo intval(
                                                                            $logErrorArray [$i]['logErrorId']
                                                                    );
                                                                    ?>', '<?php echo $leafAccess['leafAccessUpdateValue']; ?>', '<?php echo $leafAccess['leafAccessDeleteValue']; ?>');"
                                                                    ><i class="glyphicon glyphicon-edit glyphicon-white"></i></button>
                                                            <button type="button"  class="btn btn-danger btn-sm" title="Delete"
                                                                    onClick="showModalDelete('<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['logErrorId']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['applicationDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['moduleDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['folderDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['leafDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['roleDescription']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['staffName']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['logErrorOperation']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['logErrorsql']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['logErrordate']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['logErrorAccess']
                                                                    );
                                                                    ?>', '<?php
                                                                    echo rawurlencode(
                                                                            $logErrorArray [$i]['logError']
                                                                    );
                                                                    ?>', '<?php echo rawurlencode($logErrorArray [$i]['logErrorguid']); ?>')"
                                                                    value="Delete"><i class="glyphicon glyphicon-trash glyphicon-white"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td vAlign="top">
                                                        <div align="left">
                                                            <?php
                                                            if (isset($logErrorArray[$i]['applicationDescription'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['applicationDescription'], $_POST['query']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['applicationDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['applicationDescription'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['applicationDescription'], $_POST['character']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['applicationDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['applicationDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['applicationDescription'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['applicationDescription'];
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
                                                            if (isset($logErrorArray[$i]['moduleDescription'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['moduleDescription'], $_POST['query']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['moduleDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['moduleDescription'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['moduleDescription'], $_POST['character']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['moduleDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['moduleDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['moduleDescription'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['moduleDescription'];
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
                                                            if (isset($logErrorArray[$i]['folderDescription'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['folderDescription'], $_POST['query']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['folderDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['folderDescription'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['folderDescription'], $_POST['character']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['folderDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['folderDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['folderDescription'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['folderDescription'];
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
                                                            if (isset($logErrorArray[$i]['leafDescription'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos($logErrorArray[$i]['leafDescription'], $_POST['query']) !== false) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['leafDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['leafDescription'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['leafDescription'], $_POST['character']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['leafDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['leafDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['leafDescription'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['leafDescription'];
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
                                                            if (isset($logErrorArray[$i]['roleDescription'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos($logErrorArray[$i]['roleDescription'], $_POST['query']) !== false) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['roleDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['roleDescription'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        $logErrorArray[$i]['roleDescription'], $_POST['character']
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['roleDescription']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['roleDescription'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['roleDescription'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['roleDescription'];
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
                                                            if (isset($logErrorArray[$i]['staffName'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos($logErrorArray[$i]['staffName'], $_POST['query']) !== false) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['staffName']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['staffName'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos($logErrorArray[$i]['staffName'], $_POST['character']) !== false) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['staffName']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['staffName'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['staffName'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['staffName'];
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
                                                            if (isset($logErrorArray[$i]['logErrorOperation'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorOperation']), strtolower($_POST['query'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['logErrorOperation']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorOperation'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorOperation']), strtolower($_POST['character'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['logErrorOperation']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorOperation'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['logErrorOperation'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['logErrorOperation'];
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
                                                            if (isset($logErrorArray[$i]['logErrorsql'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorsql']), strtolower($_POST['query'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['logErrorsql']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorsql'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorsql']), strtolower($_POST['character'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['logErrorsql']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorsql'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['logErrorsql'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['logErrorsql'];
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
                                                            if (isset($logErrorArray[$i]['logErrordate'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrordate']), strtolower($_POST['query'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['logErrordate']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrordate'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrordate']), strtolower($_POST['character'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['logErrordate']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrordate'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['logErrordate'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['logErrordate'];
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
                                                            if (isset($logErrorArray[$i]['logErrorAccess'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorAccess']), strtolower($_POST['query'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['logErrorAccess']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorAccess'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorAccess']), strtolower($_POST['character'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['logErrorAccess']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorAccess'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['logErrorAccess'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['logErrorAccess'];
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
                                                            if (isset($logErrorArray[$i]['logError'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logError']), strtolower($_POST['query'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['logError']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logError'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logError']), strtolower($_POST['character'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['logError']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logError'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['logError'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['logError'];
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
                                                            if (isset($logErrorArray[$i]['logErrorguid'])) {
                                                                if (isset($_POST['query']) || isset($_POST['character'])) {
                                                                    if (isset($_POST['query']) && strlen($_POST['query']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorguid']), strtolower($_POST['query'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['query'], "<span class=\"label label-info\">" . $_POST['query'] . "</span>", $logErrorArray[$i]['logErrorguid']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorguid'];
                                                                        }
                                                                    } else if (isset($_POST['character']) && strlen($_POST['character']) > 0) {
                                                                        if (strpos(
                                                                                        strtolower($logErrorArray[$i]['logErrorguid']), strtolower($_POST['character'])
                                                                                ) !== false
                                                                        ) {
                                                                            echo str_replace(
                                                                                    $_POST['character'], "<span class=\"label label-info\">" . $_POST['character'] . "</span>", $logErrorArray[$i]['logErrorguid']
                                                                            );
                                                                        } else {
                                                                            echo $logErrorArray[$i]['logErrorguid'];
                                                                        }
                                                                    } else {
                                                                        echo $logErrorArray[$i]['logErrorguid'];
                                                                    }
                                                                } else {
                                                                    echo $logErrorArray[$i]['logErrorguid'];
                                                                }
                                                                ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            &nbsp;
                                                        <?php } ?>
                                                    </td>
                                                    <?php
                                                    if ($logErrorArray[$i]['isDelete']) {
                                                        $checked = "checked";
                                                    } else {
                                                        $checked = NULL;
                                                    }
                                                    ?>
                                                    <td vAlign="top">
                                                        <input class="form-control" style="display:none;" type="checkbox" name="logErrorId[]"
                                                               value="<?php echo $logErrorArray[$i]['logErrorId']; ?>">
                                                        <input class="form-control" <?php echo $checked; ?> type="checkbox" name="isDelete[]"
                                                               value="<?php echo $logErrorArray[$i]['isDelete']; ?>">

                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="7" vAlign="top" align="center"><?php
                                                    $logError->exceptionMessage(
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
                                                $logError->exceptionMessage(
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
                                            $logError->exceptionMessage(
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
                    <div class="col-xs-3 col-sm-3 col-md-3 pull-right pagination" align="right">
                        <button type="button"  class="delete btn btn-warning"
                                onClick="deleteGridRecordCheckbox('<?php echo $leafId; ?>', '<?php
                                echo $logError->getControllerPath(
                                );
                                ?>', '<?php echo $logError->getViewPath(); ?>', '<?php echo $securityToken; ?>')">
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
        <?php
    }
}
if ((isset($_POST['method']) == 'new' || isset($_POST['method']) == 'read') && $_POST['type'] == 'form') {
    ?>
    <form class="form-horizontal"><input type="hidden" name="logErrorId" id="logErrorId" value="<?php
        if (isset($_POST['logErrorId'])) {
            echo $_POST['logErrorId'];
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
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div align="right">
                            <div class="btn-group">
                                <button type="button"  id="firstRecordbutton"  class="btn btn-default"
                                        onClick="firstRecord('<?php echo $leafId; ?>', '<?php
                                        echo $logError->getControllerPath(
                                        );
                                        ?>', '<?php
                                        echo $logError->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                    <i class="glyphicon glyphicon-fast-backward glyphicon-white"
                                       value="<?php echo $t['firstButtonLabel']; ?>"></i> <?php echo $t['firstButtonLabel']; ?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="previousRecordbutton"  class="btn btn-default disabled"
                                        onClick="previousRecord('<?php echo $leafId; ?>', '<?php
                                        echo $logError->getControllerPath(
                                        );
                                        ?>', '<?php
                                        echo $logError->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)">
                                    <i class="glyphicon glyphicon-backward glyphicon-white"
                                       value="<?php echo $t['previousButtonLabel']; ?>"></i> <?php echo $t['previousButtonLabel']; ?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="nextRecordbutton"  class="btn btn-default disabled"
                                        onClick="nextRecord('<?php echo $leafId; ?>', '<?php
                                        echo $logError->getControllerPath(
                                        );
                                        ?>', '<?php
                                        echo $logError->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)">
                                    <i class="glyphicon glyphicon-forward glyphicon-white"></i> <?php echo $t['nextButtonLabel']; ?>
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="button"  id="endRecordbutton"  class="btn btn-default"
                                        onClick="endRecord('<?php echo $leafId; ?>', '<?php
                                        echo $logError->getControllerPath(
                                        );
                                        ?>', '<?php
                                        echo $logError->getViewPath(
                                        );
                                        ?>', '<?php echo $securityToken; ?>', <?php echo $leafAccess['leafAccessUpdateValue']; ?>, <?php echo $leafAccess['leafAccessDeleteValue']; ?>);">
                                    <i class="glyphicon glyphicon-fast-forward glyphicon-white"
                                       value="<?php echo $t['endButtonLabel']; ?>"></i> <?php echo $t['endButtonLabel']; ?>
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
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="logErrorOperationForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                           for="logErrorOperation"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['logErrorOperationLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input class="form-control" type="text" name="logErrorOperation" id="logErrorOperation"
                                               onKeyUp="removeMeError('logErrorOperation')"
                                               value="<?php
                                               if (isset($logErrorArray) && is_array($logErrorArray)) {
                                                   if (isset($logErrorArray[0]['logErrorOperation'])) {
                                                       echo htmlentities($logErrorArray[0]['logErrorOperation']);
                                                   }
                                               }
                                               ?>">
                                        <span class="help-block" id="logErrorOperationHelpMe"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="logErrorsqlForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                           for="logErrorsql"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['logErrorsqlLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input class="form-control" type="text" name="logErrorsql" id="logErrorsql"
                                               onKeyUp="removeMeError('logErrorsql')"
                                               value="<?php
                                               if (isset($logErrorArray) && is_array($logErrorArray)) {
                                                   if (isset($logErrorArray[0]['logErrorsql'])) {
                                                       echo htmlentities($logErrorArray[0]['logErrorsql']);
                                                   }
                                               }
                                               ?>">
                                        <span class="help-block" id="logErrorsqlHelpMe"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="logErrordateForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                           for="logErrordate"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['logErrordateLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <div class="input-group bootstrap-timepicker">
                                            <input class="form-control" id="logErrordate" name="logErrordate" type="text"
                                                   class="form-control" value="<?php
                                                   if (isset($logErrorArray) && is_array($logErrorArray)) {
                                                       if (isset($logErrorArray[0]['logErrordate'])) {
                                                           echo $logErrorArray[0]['logErrordate'];
                                                       }
                                                   }
                                                   ?>">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                        </div>
                                        <span class="help-block" id="logErrordateHelpMe"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="logErrorAccessForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                           for="logErrorAccess"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['logErrorAccessLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input class="form-control" type="text" name="logErrorAccess" id="logErrorAccess"
                                               onKeyUp="removeMeError('logErrorAccess')"
                                               value="<?php
                                               if (isset($logErrorArray) && is_array($logErrorArray)) {
                                                   if (isset($logErrorArray[0]['logErrorAccess'])) {
                                                       echo htmlentities($logErrorArray[0]['logErrorAccess']);
                                                   }
                                               }
                                               ?>">
                                        <span class="help-block" id="logErrorAccessHelpMe"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="logErrorForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                           for="logError"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['logErrorLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input class="form-control" type="text" name="logError" id="logError"
                                               onKeyUp="removeMeError('logError')"
                                               value="<?php
                                               if (isset($logErrorArray) && is_array($logErrorArray)) {
                                                   if (isset($logErrorArray[0]['logError'])) {
                                                       echo htmlentities($logErrorArray[0]['logError']);
                                                   }
                                               }
                                               ?>">
                                        <span class="help-block" id="logErrorHelpMe"></span>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 form-group" id="logErrorguidForm">
                                    <label class="control-label col-xs-4 col-sm-4 col-md-4"
                                           for="logErrorguid"><strong><?php
                                                   echo ucfirst(
                                                           $leafTranslation['logErrorguidLabel']
                                                   );
                                                   ?></strong></label>

                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input class="form-control" type="text" name="logErrorguid" id="logErrorguid"
                                               onKeyUp="removeMeError('logErrorguid')"
                                               value="<?php
                                               if (isset($logErrorArray) && is_array($logErrorArray)) {
                                                   if (isset($logErrorArray[0]['logErrorguid'])) {
                                                       echo htmlentities($logErrorArray[0]['logErrorguid']);
                                                   }
                                               }
                                               ?>">
                                        <span class="help-block" id="logErrorguidHelpMe"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" align="center">
                        <div class="btn-group">
                            <a id="newRecordButton1" href="javascript:void(0)" class="btn btn-success disabled"><i
                                    class="glyphicon glyphicon-plus glyphicon-white"></i> <?php echo $t['newButtonLabel']; ?>
                            </a>
                            <a id="newRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-success disabled"
                               data-toggle="dropdown"><span class="caret"></span></a>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li><a id="newRecordButton3" href="javascript:void(0)" class="disabled"><i
                                            class="glyphicon glyphicon-plus"></i> <?php echo $t['newContinueButtonLabel']; ?>
                                    </a></li>
                                <li><a id="newRecordButton4" href="javascript:void(0)" class="disabled"><i
                                            class="glyphicon glyphicon-edit"></i> <?php echo $t['newUpdateButtonLabel']; ?></a>
                                </li>
                                <!---<li><a id="newRecordButton5" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newPrintButtonLabel'];       ?></a> </li>-->
                                <!---<li><a id="newRecordButton6" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php // echo $t['newUpdatePrintButtonLabel'];       ?></a> </li>-->
                                <li><a id="newRecordButton7" href="javascript:void(0)" class="disabled"><i
                                            class="glyphicon glyphicon-list"></i> <?php echo $t['newListingButtonLabel']; ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <a id="updateRecordButton1" href="javascript:void(0)" class="btn btn-info disabled"><i
                                    class="glyphicon glyphicon-edit glyphicon-white"></i> <?php echo $t['updateButtonLabel']; ?>
                            </a>
                            <a id="updateRecordButton2" href="javascript:void(0)" class="btn dropdown-toggle btn-info disabled"
                               data-toggle="dropdown"><span class="caret"></span></a>
                            <ul class="dropdown-menu" style="text-align:left">
                                <li><a id="updateRecordButton3" href="javascript:void(0)" class="disabled"><i
                                            class="glyphicon glyphicon-plus"></i> <?php echo $t['updateButtonLabel']; ?></a>
                                </li>
                                <!---<li><a id="updateRecordButton4" href="javascript:void(0)" class="disabled"><i class="glyphicon glyphicon-print"></i> <?php //echo $t['updateButtonPrintLabel'];       ?></a> </li> -->
                                <li><a id="updateRecordButton5" href="javascript:void(0)" class="disabled"><i
                                            class="glyphicon glyphicon-list-alt"></i> <?php echo $t['updateListingButtonLabel']; ?>
                                    </a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button"  id="deleteRecordbutton"  class="btn btn-danger disabled"><i
                                    class="glyphicon glyphicon-trash glyphicon-white"
                                    value="<?php echo $t['deleteButtonLabel']; ?>"></i> <?php echo $t['deleteButtonLabel']; ?>
                            </button>
                        </div>
                        <div class="btn-group">
                            <button type="button"  id="resetRecordbutton"  class="btn btn-info"
                                    onClick="resetRecord(<?php echo $leafId; ?>, '<?php
                                    echo $logError->getControllerPath(
                                    );
                                    ?>', '<?php
                                    echo $logError->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessCreateValue']; ?>,<?php echo $leafAccess['leafAccessUpdateValue']; ?>,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)"
                                    value="<?php echo $t['resetButtonLabel']; ?>"><i
                                    class="glyphicon glyphicon-refresh glyphicon-white"></i> <?php echo $t['resetButtonLabel']; ?>
                            </button>
                        </div>

                        <div class="btn-group">
                            <button type="button"  id="listRecordbutton"  class="btn btn-info"
                                    onClick="showGrid('<?php echo $leafId; ?>', '<?php
                                    echo $logError->getViewPath(
                                    );
                                    ?>', '<?php echo $securityToken; ?>', 0,<?php echo LIMIT; ?>, 1)"><i
                                    class="glyphicon glyphicon-list glyphicon-white"></i> <?php echo $t['gridButtonLabel']; ?>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="firstRecordCounter" id="firstRecordCounter"
                           value="<?php
                           if (isset($firstRecord)) {
                               echo intval($firstRecord);
                           }
                           ?>">
                    <input type="hidden" name="nextRecordCounter" id="nextRecordCounter" value="<?php
                    if (isset($nextRecord)) {
                        echo intval($nextRecord);
                    }
                    ?>">
                    <input type="hidden" name="previousRecordCounter" id="previousRecordCounter"
                           value="<?php
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
        </div></form>
    <script type="text/javascript">
        $(document).ready(function() {
            window.scrollTo(0, 0);
            $(".chzn-select").chosen({search_contains: true});
            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
            validateMeNumeric('logErrorId');
            validateMeNumeric('applicationId');
            validateMeNumeric('moduleId');
            validateMeNumeric('folderId');
            validateMeNumeric('leafId');
            validateMeNumeric('roleId');
            validateMeNumeric('staffId');
            validateMeAlphaNumeric('logErrorOperation');
            validateMeAlphaNumeric('logErrorsql');
            $('#logErrordate').timepicker();
            validateMeAlphaNumeric('logErrorAccess');
            validateMeAlphaNumeric('logError');
            validateMeAlphaNumeric('logErrorguid');
    <?php if ($_POST['method'] == "new") { ?>
                $('#resetRecordButton').removeClass().addClass('btn btn-default');
        <?php if ($leafAccess['leafAccessCreateValue'] == 1) { ?>
                    $('#newRecordButton1').removeClass().addClass('btn btn-success');
                    $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success');
                    $('#newRecordButton3').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',1)");
                    $('#newRecordButton4').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',2)");
                    $('#newRecordButton5').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',3)");
                    $('#newRecordButton6').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',4)");
                    $('#newRecordButton7').attr('onClick', "newRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',5)");
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
    <?php } else if ($_POST['logErrorId']) { ?>
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
        <?php if ($leafAccess['leafAccessUpdateValue'] == 1) { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-default');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',1,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',2,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',3,<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
        <?php } ?>
        <?php if ($leafAccess['leafAccessDeleteValue'] == 1) { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(<?php echo $leafId; ?>,'<?php echo $logError->getControllerPath(); ?>','<?php echo $logError->getViewPath(); ?>','<?php echo $securityToken; ?>',<?php echo $leafAccess['leafAccessDeleteValue']; ?>)");
        <?php } else { ?>
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
        <?php } ?>
    <?php } ?>
        });
    </script>
<?php } ?>
<script type="text/javascript" src="./v3/portal/main/javascript/logError.js"></script>
<hr>
<footer><p>IDCMS 2012/2013</p></footer>