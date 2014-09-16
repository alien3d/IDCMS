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
require_once($newFakeDocumentRoot . "v3/financial/businessPartner/controller/businessPartnerController.php");
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

$translator->setCurrentTable(array('businessPartner', 'invoiceFollowUp'));

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
$businessPartnerArray = array();
$followUpArray = array();
$_POST['from'] = 'businessPartnerAccountReceivableLedger.php';
$_GET['from'] = 'businessPartnerAccountReceivableLedger.php';
if (isset($_POST)) {
    if (isset($_POST['method'])) {
        $businessPartner = new \Core\Financial\BusinessPartner\BusinessPartner\Controller\BusinessPartnerClass();
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
            $businessPartner->setFieldQuery($_POST ['query']);
        }
        if (isset($_POST ['filter'])) {
            $businessPartner->setGridQuery($_POST ['filter']);
        }
        if (isset($_POST ['character'])) {
            $businessPartner->setCharacterQuery($_POST['character']);
        }
        if (isset($_POST ['dateRangeStart'])) {
            $businessPartner->setDateRangeStartQuery($_POST['dateRangeStart']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeStart']);
            $businessPartner->setStartDay($start[2]);
            $businessPartner->setStartMonth($start[1]);
            $businessPartner->setStartYear($start[0]);
        }
        if (isset($_POST ['dateRangeEnd']) && (strlen($_POST['dateRangeEnd']) > 0)) {
            $businessPartner->setDateRangeEndQuery($_POST['dateRangeEnd']);
            //explode the data to get day,month,year
            $start = explode('-', $_POST ['dateRangeEnd']);
            $businessPartner->setEndDay($start[2]);
            $businessPartner->setEndMonth($start[1]);
            $businessPartner->setEndYear($start[0]);
        }
        if (isset($_POST ['dateRangeType'])) {
            $businessPartner->setDateRangeTypeQuery($_POST['dateRangeType']);
        }
        if (isset($_POST ['dateRangeExtraType'])) {
            $businessPartner->setDateRangeExtraTypeQuery($_POST['dateRangeExtraType']);
        }
        $businessPartner->setServiceOutput('html');
        $businessPartner->setLeafId($leafId);
        $businessPartner->execute();
        $followUpArray = $businessPartner->getFollowUp();
        if ($_POST['method'] == 'read') {
            $businessPartner->setStart($offset);
            $businessPartner->setLimit($limit); // normal system don't like paging..
            $businessPartner->setPageOutput('html');
            $businessPartnerArray = $businessPartner->read();
            $navigation = new \Core\Paging\HtmlPaging();
            $navigation->setLeafId($leafId);
            $navigation->setViewPath($businessPartner->getViewPath());
            $navigation->setOffset($offset);
            $navigation->setLimit($limit);
            $navigation->setSecurityToken($securityToken);
            $navigation->setLoadingText($t['loadingTextLabel']);
            $navigation->setLoadingCompleteText($t['loadingCompleteTextLabel']);
            $navigation->setLoadingErrorText($t['loadingErrorTextLabel']);
            if (isset($businessPartnerArray [0]['total'])) {
                $total = $businessPartnerArray [0]['total'];
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
            <!-- starting enquiry style Search -->
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <form class="form-horizontal">

                                <label for="businessPartnerId" class="control-label col-xs-2 col-sm-2 col-md-2"><strong><?php echo ucfirst($leafTranslation['businessPartnerIdLabel']); ?></strong></label>
                                <div class="col-xs-8 col-sm-8 col-md-8">
                                    <select name="businessPartnerId" id="businessPartnerId" class="chzn-select form-control">
                                        <option value=""></option>
                                        <?php
                                        if (is_array($businessPartnerArray)) {
                                            $totalRecord = intval(count($businessPartnerArray));
                                            $businessPartnerCategoryDescription = null;
                                            if ($totalRecord > 0) {
                                                $d = 1;
                                                for ($i = 0; $i < $totalRecord; $i++) {

                                                    if ($i != 0) {
                                                        if ($businessPartnerCategoryDescription != $businessPartnerArray[$i]['businessPartnerCategoryDescription']) {
                                                            echo "</optgroup><optgroup label=\"" . $businessPartnerArray[$i]['businessPartnerCategoryDescription'] . "\">";
                                                        }
                                                    } else {
                                                        echo "<optgroup label=\"" . $businessPartnerArray[$i]['businessPartnerCategoryDescription'] . "\">";
                                                    }
                                                    $businessPartnerCategoryDescription = $businessPartnerArray[$i]['businessPartnerCategoryDescription'];
                                                    ?>
                                                    <option
                                                        value="<?php echo $businessPartnerArray[$i]['businessPartnerId']; ?>"><?php echo $d; ?>
                                                        . <?php echo $businessPartnerArray[$i]['businessPartnerCompany']; ?></option>
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
                                    </select>&nbsp; <span class="help-block" id="businessPartnerIdHelpMe"></span>

                                </div>
                            </form>
                        </div>


                    </div>
                    <div class="panel-footer">
                        <button type="button"  onClick="showMeModal('newBusinessPartner', 1);" class="btn btn-info"><?php echo $t['newButtonLabel']; ?></button>

                        <button type="button"  onClick="showMeModal('infoBusinessPartner', 1);" class="btn btn-info"><?php echo $t['informationTextLabel']; ?></button>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <!-- start list invoices -->
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div style="min-height:600px;">
                                <div class="panel panel-default">
                                    <ul class="nav nav-tabs">
                                        <li class="active" id="quoteInvoiceTab"><a href="#">Quote</a></li>
                                        <li id="dueInvoiceTab"><a href="#">Due</a></li>
                                        <li id="holdInvoiceTab"><a href="#">Hold</a></li>
                                        <li id="scheduleInvoiceTab"><a href="#">Schedule</a></li>
                                        <li id="completeInvoiceTab"><a href="#">Complete</a></li>
                                        <li id="cancelInvoiceTab"><a href="#">Cancel</a></li>
                                    </ul>

                                    <div id='content' class="tab-content">
                                        <div class="tab-pane fade in active" id="quoteInvoice">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    &nbsp;
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12" align="right">
                                                    <form class="form-inline" >&nbsp;&nbsp;&nbsp;
                                                        <input type="text" name="searchQuoteInvoice" id="searchDueInvoice" class="input-sm form-control"  style="width:70%">
                                                        <button type="button"  class="btn btn-sm btn-info" name="searchDueInvoicebutton"  id="searchDueInvoicebutton"  value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                                        <button type="button"  class="btn btn-sm btn-info" name="clearDueInvoicebutton"  id="clearDueInvoicebutton"  value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                                                        &nbsp;</form>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;
                                                    <div  style="padding:5px">
                                                        <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                                            <thead>
                                                                <tr>
                                                                    <th>aa</th>
                                                                    <th>bb</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="quoteInvoiceTable">
                                                                <tr>
                                                                    <td>due</td>
                                                                    <td>bb</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade in active" id="dueInvoice">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    &nbsp;
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12" align="right">
                                                    <form class="form-inline" >&nbsp;&nbsp;&nbsp;
                                                        <input type="text" name="searchDueInvoice" id="searchDueInvoice" class="input-sm form-control"  style="width:70%">
                                                        <button type="button"  class="btn btn-sm btn-info" name="searchDueInvoicebutton"  id="searchDueInvoicebutton"  value="<?php echo $t['searchButtonLabel']; ?>"><?php echo $t['searchButtonLabel']; ?></button>
                                                        <button type="button"  class="btn btn-sm btn-info" name="clearDueInvoicebutton"  id="clearDueInvoicebutton"  value="<?php echo $t['clearButtonLabel']; ?>"><?php echo $t['clearButtonLabel']; ?></button>
                                                        &nbsp;</form>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">&nbsp;
                                                    <div  style="padding:5px">
                                                        <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                                            <thead>
                                                                <tr>
                                                                    <th>aa</th>
                                                                    <th>bb</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="dueInvoiceTable">
                                                                <tr>
                                                                    <td>due</td>
                                                                    <td>bb</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade"  id="holdInvoice">
                                            <table class="table table-condensed table-hover" id="tableData">
                                                <thead>
                                                    <tr>
                                                        <th>aa</th>
                                                        <th>bb</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="holdInvoiceTable">
                                                    <tr>
                                                        <td>hold</td>
                                                        <td>bb</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="completeInvoice">
                                            <div class="panel panel-default">
                                                <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                                    <thead>
                                                        <tr>
                                                            <th>aa</th>
                                                            <th>bb</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="completeInvoiceTable">
                                                        <tr>
                                                            <td>complete</td>
                                                            <td>bb</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="cancelInvoice">
                                            <div class="panel panel-default">
                                                <table class="table table table-bordered table-striped table-condensed table-hover smart-form has-tickbox" id="tableData">
                                                    <thead>
                                                        <tr>
                                                            <th>aa</th>
                                                            <th>bb</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cancelInvoiceTable">
                                                        <tr>
                                                            <td>cancel</td>
                                                            <td>bb</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end list invoices -->
                        <!-- start list follow up-->
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div style="min-height:600px;" class="panel panel-default">
                                <!-- follow up -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12" align="right">
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <form class="form-inline" >&nbsp;&nbsp;&nbsp;
                                            <input type="text" name="followUpDescription" id="followUpDescription" class="form-control" style="width:200px">
                                            <select name="followUpId" id="followUpId" class="form-control  chzn-select" style="width:150px">
                                                <option value=""></option>
                                                <?php
                                                if (is_array($followUpArray)) {
                                                    $totalRecord = intval(count($followUpArray));
                                                    if ($totalRecord > 0) {
                                                        $d = 1;
                                                        for ($i = 0; $i < $totalRecord; $i++) {
                                                            ?>
                                                            <option value="<?php echo $followUpArray[$i]['followUpId']; ?>"><?php echo $d; ?>. <?php echo $followUpArray[$i]['followUpDescription']; ?></option> 
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
                                            <button type="button"  class="btn btn-sm btn-info" name="searchDueInvoicebutton"  id="searchDueInvoicebutton"  value="<?php echo $t['followUpButtonLabel']; ?>"><?php echo $t['followUpButtonLabel']; ?></button>
                                            &nbsp;</form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12" align="right">
                                        <form class="form-inline" >
                                        </form>
                                    </div>
                                </div>

                                <!-- end follow up -->
                                <!-- follow up method -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        &nbsp;
                                    </div>
                                </div>
                                <!-- end follow up method -->
                                <!-- start listing follow up -->
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div style="padding:5px">
                                            <table class="table table table-condensed table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th width="25px" align="center">
                                                <div align="center">#</div>
                                                </th>
                                                <th><?php echo ucwords($leafTranslation['invoiceFollowUpDateLabel']); ?></th>
                                                <th><?php echo ucwords($leafTranslation['invoiceFollowUpDescriptionLabel']); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody id="followUpId">
                                                    <tr>
                                                        <td>1</td>    
                                                        <td>01-01-2014</td>
                                                        <td>Pknp malas bayar</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>    
                                                        <td>01-01-2014</td>
                                                        <td>Pknp malas bayar</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>    
                                                        <td>01-01-2014</td>
                                                        <td>Pknp malas bayar</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end listing follow up -->
                        </div>
                        <!-- end list follow up -->

                    </div>
                </div>
            </div>
        </div>
        <!-- hidden value before follow up -->
        <input type="hidden" name="invoiceId" id="invoiceId">    
        <!-- End Enquiry Style Search -- >
        <!-- start below information -->

        <!-- end below information -->
        <script type="text/javascript">
            $(document).ready(function() {
                window.scrollTo(0, 0);
                $(".chzn-select").chosen({search_contains: true});
                $(".chzn-select-deselect").chosen({allow_single_deselect: true});
                showMeDiv('quoteInvoice', 1);
                showMeDiv('dueInvoice', 0);
                showMeDiv('holdInvoice', 0);
                showMeDiv('completeInvoice', 0);
                showMeDiv('cancelInvoice', 0);
                $("#quoteInvoiceTab").on('click', function() {
                    showMeDiv('quoteInvoice', 1);
                    showMeDiv('dueInvoice', 0);
                    showMeDiv('holdInvoice', 0);
                    showMeDiv('completeInvoice', 0);
                    showMeDiv('cancelInvoice', 0);
                });
                $("#dueInvoiceTab").on('click', function() {
                    showMeDiv('quoteInvoice', 0);
                    showMeDiv('dueInvoice', 1);
                    showMeDiv('holdInvoice', 0);
                    showMeDiv('completeInvoice', 0);
                    showMeDiv('cancelInvoice', 0);
                });
                $("#holdInvoiceTab").on('click', function() {
                    showMeDiv('quoteInvoice', 0);
                    showMeDiv('dueInvoice', 0);
                    showMeDiv('holdInvoice', 1);
                    showMeDiv('completeInvoice', 0);
                    showMeDiv('cancelInvoice', 0);
                });
                $("#completeInvoiceTab").on('click', function() {
                    showMeDiv('quoteInvoice', 0);
                    showMeDiv('dueInvoice', 0);
                    showMeDiv('holdInvoice', 0);
                    showMeDiv('completeInvoice', 1);
                    showMeDiv('cancelInvoice', 0);
                });
                $("#cancelInvoiceTab").on('click', function() {
                    showMeDiv('quoteInvoice', 0);
                    showMeDiv('dueInvoice', 0);
                    showMeDiv('holdInvoice', 0);
                    showMeDiv('completeInvoice', 0);
                    showMeDiv('cancelInvoice', 1);
                });
            });
        </script>
        <?php
    }
}
?>