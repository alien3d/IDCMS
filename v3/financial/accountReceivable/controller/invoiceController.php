<?php

namespace Core\Financial\AccountReceivable\Invoice\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel;
use Core\Financial\AccountReceivable\Invoice\Service\InvoiceService;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
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
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/model/invoiceModel.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/service/invoiceService.php");

/**
 * Class Invoice
 * this is invoice controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\AccountReceivable\Invoice\Controller
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceClass extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Php Word Generate Microsoft Excel 2007 Output.Format : docxs
     * @var \PHPWord
     */
    //private $word; 
    /**
     * Model
     * @var \Core\Financial\AccountReceivable\Invoice\Model\InvoiceModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\AccountReceivable\Invoice\Service\InvoiceService
     */
    public $service;

    /**
     * System Format
     * @var \Core\shared\SharedClass
     */
    public $systemFormat;

    /**
     * Translation Array
     * @var mixed
     */
    public $translate;

    /**
     * Leaf Access
     * @var mixed
     */
    public $leafAccess;

    /**
     * Translate Label
     * @var array
     */
    public $t;

    /**
     * System Format
     * @var array
     */
    public $systemFormatArray;

    /**
     * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf
     * @var \PHPExcel
     */
    private $excel;

    /**
     * Record Pagination
     * @var \Core\RecordSet\RecordSet
     */
    private $recordSet;

    /**
     * Document Trail Audit.
     * @var \Core\Document\Trail\DocumentTrailClass
     */
    private $documentTrail;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/financial/accountReceivable/view/salesOrder.php");
        $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceController.php");
        $this->setServicePath("./v3/financial/accountReceivable/service/invoiceService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new InvoiceModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/accountReceivable/view/" . $this->model->getFrom());
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->q = new \Core\Database\Mssql\Vendor();
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->q = new \Core\Database\Oracle\Vendor();
                }
            }
        }
        $this->setVendor($this->getVendor());
        $this->q->setRequestDatabase($this->q->getCoreDatabase());
        // $this->q->setApplicationId($this->getApplicationId()); 
        // $this->q->setModuleId($this->getModuleId()); 
        // $this->q->setFolderId($this->getFolderId()); 
        $this->q->setLeafId($this->getLeafId());
        $this->q->setLog($this->getLog());
        $this->q->setAudit($this->getAudit());
        $this->q->connect($this->getConnection(), $this->getUsername(), $this->getDatabase(), $this->getPassword());

        $data = $this->q->getLeafLogData();
        if (is_array($data) && count($data) > 0) {
            $this->q->getLog($data['isLog']);
            $this->q->setAudit($data['isAudit']);
        }
        if ($this->getAudit() == 1) {
            $this->q->setAudit($this->getAudit());
            $this->q->setTableName($this->model->getTableName());
            $this->q->setPrimaryKeyName($this->model->getPrimaryKeyName());
        }
        $translator = new SharedClass();
        $translator->setCurrentTable($this->model->getTableName());
        $translator->setLeafId($this->getLeafId());
        $translator->execute();

        $this->translate = $translator->getLeafTranslation(); // short because code too long  
        $this->t = $translator->getDefaultTranslation(); // short because code too long  

        $arrayInfo = $translator->getFileInfo();
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];

        $this->setApplicationId($arrayInfo['applicationId']);
        $this->setModuleId($arrayInfo['moduleId']);
        $this->setFolderId($arrayInfo['folderId']);

        $this->setReportTitle($applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative);

        $this->service = new InvoiceService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());

        $this->service->setApplicationId($this->getApplicationId());
        $this->service->setModuleId($this->getModuleId());
        $this->service->setFolderId($this->getFolderId());
        $this->service->setLeafId($this->getLeafId());

        $this->service->execute();

        $this->recordSet = new RecordSet();
        $this->recordSet->q = $this->q;
        $this->recordSet->setCurrentTable($this->model->getTableName());
        $this->recordSet->setPrimaryKeyName($this->model->getPrimaryKeyName());

        // override for Structured Query Language (SQL) statement

        if ($this->model->getFrom() == 'invoice.php' || $this->model->getFrom() == 'invoiceMaintenance.php'
        ) {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" AND `invoice`.`isActive` = 1 AND `invoice`.`isPost` = 0 ");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" AND [invoice].[isActive] = 1 AND [invoice].[isPost] = 0 ");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" AND INVOICE.ISACTIVE = 1 AND INVOICE.ISPOST = 0 ");
            }
        }

        if ($this->model->getFrom() == 'invoicePost.php' || $this->model->getFrom() == 'invoicePosting.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" AND `invoice`.`isActive` = 1  AND `isBalance`   =    1 AND `invoice`.`isPost` = 0 ");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" AND [invoice].[isActive] = 1  AND [isBalance]   =    1 AND [invoice].[isPost] = 0 ");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" AND INVOICE.ISACTIVE = 1 AND ISBALANCE =    1 AND INVOICE.ISPOST = 0 ");
            }
        }

        if ($this->model->getFrom() == 'invoiceHistory.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  AND `invoice`.`isActive` = 1 AND `invoice`.`isBalance`   =    1 AND `invoice`.`isPost` = 1 ");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  AND [invoice].[isActive] = 1 AND [invoice].[isBalance]   =    1  AND [invoice].[isPost] = 1 ");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  AND INVOICE.ISACTIVE = 1 AND INVOICE.ISBALANCE =    1  AND INVOICE.ISPOST = 1 ");
            }
        }
        if ($this->model->getFrom() == 'invoiceCancel.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  AND `invoice`.`isActive` = 0  ");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  AND [invoice].[isActive] = 0 ");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  AND INVOICE.ISACTIVE = 0 ");
            }
        }

        $this->recordSet->execute();

        $this->documentTrail = new DocumentTrailClass();
        $this->documentTrail->q = $this->q;
        $this->documentTrail->setVendor($this->getVendor());
        $this->documentTrail->setStaffId($this->getStaffId());
        $this->documentTrail->setLanguageId($this->getLanguageId());

        $this->documentTrail->setApplicationId($this->getApplicationId());
        $this->documentTrail->setModuleId($this->getModuleId());
        $this->documentTrail->setFolderId($this->getFolderId());
        $this->documentTrail->setLeafId($this->getLeafId());

        $this->documentTrail->execute();

        $this->systemFormat = new SharedClass();
        $this->systemFormat->q = $this->q;
        $this->systemFormat->setCurrentTable($this->model->getTableName());
        $this->systemFormat->execute();

        $this->systemFormatArray = $this->systemFormat->getSystemFormat();

        $this->excel = new \PHPExcel ();
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->create();
        $sql = null;
        if (!$this->model->getInvoiceCategoryId()) {
            $this->model->setInvoiceCategoryId($this->service->getInvoiceCategoryDefaultValue());
        }
        if (!$this->model->getInvoiceTypeId()) {
            $this->model->setInvoiceTypeId($this->service->getInvoiceTypeDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getBusinessPartnerContactId()) {
            $this->model->setBusinessPartnerContactId($this->service->getBusinessPartnerContactDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getInvoiceProjectId()) {
            $this->model->setInvoiceProjectId($this->service->getInvoiceProjectDefaultValue());
        }
        if (!$this->model->getPaymentTermId()) {
            $this->model->setPaymentTermId($this->service->getPaymentTermDefaultValue());
        }
        if (!$this->model->getWarehouseId()) {
            $this->model->setWarehouseId($this->service->getWarehouseDefaultValue());
        }
        if (!$this->model->getInvoiceProcessId()) {
            $this->model->setInvoiceProcessId($this->service->getInvoiceProcessDefaultValue());
        }
        // cannot depend on leafId because of multi tenant future
        if ($this->model->getFrom() == 'cashSales.php') {
            $this->model->setDocumentNumber($this->getDocumentNumber('SLIV'));
            $this->model->setInvoiceProcessId($this->service->getInvoiceProcessId('CHSL'));
        } else {
            if ($this->model->getFrom() == 'salesQuotation.php') {
                $this->model->setDocumentNumber($this->getDocumentNumber('SLQT'));
                $this->model->setInvoiceProcessId($this->service->getInvoiceProcessId('SLQT'));
            } else {
                // this include the other sales..
                $this->model->setDocumentNumber($this->getDocumentNumber('SLOR'));
                $this->model->setInvoiceProcessId($this->service->getInvoiceProcessId('SLOR'));
            }
        }

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoice` 
            (
                 `companyId`,
                 `invoiceCategoryId`,
                 `invoiceTypeId`,
                 `businessPartnerId`,
                 `businessPartnerContactId`,
                 `countryId`,
                 `invoiceProjectId`,
                 `paymentTermId`,
                 `warehouseId`,
                 `invoiceProcessId`,
                 `businessPartnerAddress`,
                 `documentNumber`,
                 `referenceNumber`,
                 `invoiceQuotationNumber`,
                 `purchaseOrderNumber`,
                 `invoiceTotalAmount`,
                 `invoiceTaxAmount`,
                 `invoiceDiscountAmount`,
                 `invoiceDate`,
                 `invoiceDueDate`,
                 `invoicePromiseDate`,
                 `invoiceDescription`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `executeBy`,
                 `executeTime`
       ) VALUES ( 
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceCategoryId() . "',
                 '" . $this->model->getInvoiceTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getBusinessPartnerContactId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceProjectId() . "',
                 '" . $this->model->getPaymentTermId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getInvoiceProcessId() . "',
                 '" . $this->model->getBusinessPartnerAddress() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceQuotationNumber() . "',
                 '" . $this->model->getPurchaseOrderNumber() . "',
                 '" . $this->model->getInvoiceTotalAmount() . "',
                 '" . $this->model->getInvoiceTaxAmount() . "',
                 '" . $this->model->getInvoiceDiscountAmount() . "',
                 '" . $this->model->getInvoiceDate() . "',
                 '" . $this->model->getInvoiceDueDate() . "',
                 '" . $this->model->getInvoicePromiseDate() . "',
                 '" . $this->model->getInvoiceDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [invoice]
            (
                 [invoiceId],
                 [companyId],
                 [invoiceCategoryId],
                 [invoiceTypeId],
                 [businessPartnerId],
                 [businessPartnerContactId],
                 [countryId],
                 [invoiceProjectId],
                 [paymentTermId],
                 [warehouseId],
                 [invoiceProcessId],
                 [businessPartnerAddress],
                 [documentNumber],
                 [referenceNumber],
                 [invoiceQuotationNumber],
                 [purchaseOrderNumber],
                 [invoiceTotalAmount],
                 [invoiceTaxAmount],
                 [invoiceDiscountAmount],
                 [invoiceDate],
                 [invoiceDueDate],
                 [invoicePromiseDate],
                 [invoiceDescription],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [executeBy],
                 [executeTime]
) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceCategoryId() . "',
                 '" . $this->model->getInvoiceTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getBusinessPartnerContactId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceProjectId() . "',
                 '" . $this->model->getPaymentTermId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getInvoiceProcessId() . "',
                 '" . $this->model->getBusinessPartnerAddress() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceQuotationNumber() . "',
                 '" . $this->model->getPurchaseOrderNumber() . "',
                 '" . $this->model->getInvoiceTotalAmount() . "',
                 '" . $this->model->getInvoiceTaxAmount() . "',
                 '" . $this->model->getInvoiceDiscountAmount() . "',
                 '" . $this->model->getInvoiceDate() . "',
                 '" . $this->model->getInvoiceDueDate() . "',
                  '" . $this->model->getInvoicePromiseDate() . "',
                 '" . $this->model->getInvoiceDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO INVOICE
            (
                 COMPANYID,
                 INVOICECATEGORYID,
                 INVOICETYPEID,
                 BUSINESSPARTNERID,
                 BUSINESSPARTNERCONTACTID,
                 COUNTRYID,
                 INVOICEPROJECTID,
                 PAYMENTTERMID,
                 WAREHOUSEID,
                 INVOICEPROCESSID,
                 BUSINESSPARTNERADDRESS,
                 DOCUMENTNUMBER,
                 REFERENCENUMBER,
                 INVOICEQUOTATIONNUMBER,
                 PURCHASEORDERNUMBER,
                 INVOICETOTALAMOUNT,
                 INVOICETAXAMOUNT,
                 INVOICEDISCOUNTAMOUNT,
                 INVOICEDATE,
                 INVOICEDUEDATE,
                 INVOICEPROMISEDATE,
                 INVOICEDESCRIPTION,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getInvoiceCategoryId() . "',
                 '" . $this->model->getInvoiceTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getBusinessPartnerContactId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getInvoiceProjectId() . "',
                 '" . $this->model->getPaymentTermId() . "',
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getInvoiceProcessId() . "',
                 '" . $this->model->getBusinessPartnerAddress() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceQuotationNumber() . "',
                 '" . $this->model->getPurchaseOrderNumber() . "',
                 '" . $this->model->getInvoiceTotalAmount() . "',
                 '" . $this->model->getInvoiceTaxAmount() . "',
                 '" . $this->model->getInvoiceDiscountAmount() . "',
                 '" . $this->model->getInvoiceDate() . "',
                 '" . $this->model->getInvoiceDueDate() . "',
                  '" . $this->model->getInvoicePromiseDate() . "',
                 '" . $this->model->getInvoiceDescription() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $invoiceId = $this->q->lastInsertId('invoice');
        if ($this->model->getFrom() == 'salesQuotation.php') {
            $this->service->setInvoiceLedger(
                    $this->model->getBusinessPartnerId(), $this->model->getInvoiceProjectId(), $this->model->getInvoiceDate(), $this->model->getInvoiceDueDate(), $this->model->getInvoiceDescription(), $this->model->getInvoiceTotalAmount(), $invoiceId, $this->getLeafId(), $this->model->getFrom()
            );
        }
        if ($this->model->getFrom() == 'salesOrder.php') {
            /*
              if (intval($this->model->getInvoiceQuotationId() + 0) > 0) {
              $this->service->updateInvoiceQuotation(
              $this->model->getInvoiceQuotation(),
              $this->model->getQuotationNumber(),
              4
              );
              }
             */
        }
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "invoiceId" => $invoiceId,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Return Total Record Of The
     * return int Total Record
     */
    private function getTotalRecord() {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total`
         FROM    `invoice`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [invoice]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      INVOICE
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result) > 0) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    /** 
    * Read
     * @see config::read() 
     */ 
    public function read() { 
       if ($this->getPageOutput() == 'json' ||  $this->getPageOutput() =='table') { 
           header('Content-Type:application/json; charset=utf-8'); 
       } 
       $start = microtime(true); 
       if(isset($_SESSION['isAdmin'])) { 
           if ($_SESSION['isAdmin'] == 0) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter(" `invoice`.`isActive` = 1  AND `invoice`.`companyId`='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoice].[isActive] = 1 AND [invoice].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICE.ISACTIVE = 1  AND INVOICE.COMPANYID='".$this->getCompanyId()."'"); 
               } 
           } else if ($_SESSION['isAdmin'] == 1) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $this->setAuditFilter("   `invoice`.`companyId`='".$this->getCompanyId()."'	"); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $this->setAuditFilter(" [invoice].[companyId]='".$this->getCompanyId()."' "); 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $this->setAuditFilter(" INVOICE.COMPANYID='".$this->getCompanyId()."' "); 
               } 
           } 
       } 
       if ($this->getVendor() == self::MYSQL) { 
           $sql = "SET NAMES utf8"; 
     try {
       $this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
       }  
       $sql=null;
       if ($this->getVendor() == self::MYSQL) { 

      $sql = "
       SELECT                    `invoice`.`invoiceId`,
                    `company`.`companyDescription`,
                    `invoice`.`companyId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `invoice`.`businessPartnerId`,

                    `invoiceproject`.`invoiceProjectDescription`,
                    `invoice`.`invoiceProjectId`,
                    `paymentterm`.`paymentTermDescription`,
                    `invoice`.`paymentTermId`,
                    `invoiceprocess`.`invoiceProcessDescription`,
                    `invoice`.`invoiceProcessId`,
                    `invoice`.`documentNumber`,
                    `invoice`.`referenceNumber`,
                    `invoice`.`invoiceCode`,
                    `invoice`.`invoiceTotalAmount`,
                    `invoice`.`invoiceTextAmount`,
                    `invoice`.`invoiceTaxAmount`,
                    `invoice`.`invoiceDiscountAmount`,
                    `invoice`.`invoiceShippingAmount`,
                    `invoice`.`invoiceDate`,
                    `invoice`.`invoiceShippingDate`,
                    `invoice`.`invoiceDescription`,
                    `invoice`.`invoiceRemark`,
                    `invoice`.`isDefault`,
                    `invoice`.`isNew`,
                    `invoice`.`isDraft`,
                    `invoice`.`isUpdate`,
                    `invoice`.`isDelete`,
                    `invoice`.`isActive`,
                    `invoice`.`isApproved`,
                    `invoice`.`isReview`,
                    `invoice`.`isPost`,
                    `invoice`.`executeBy`,
                    `invoice`.`executeTime`,
                    `staff`.`staffName`
          FROM      `invoice`
          JOIN      `staff`
          ON        `invoice`.`executeBy` = `staff`.`staffId`
    JOIN	`company`
    ON		`company`.`companyId` = `invoice`.`companyId`
    JOIN	`businesspartner`
    ON		`businesspartner`.`businessPartnerId` = `invoice`.`businessPartnerId`

    JOIN	`invoiceproject`
    ON		`invoiceproject`.`invoiceProjectId` = `invoice`.`invoiceProjectId`
    JOIN	`paymentterm`
    ON		`paymentterm`.`paymentTermId` = `invoice`.`paymentTermId`
    JOIN	`invoiceprocess`
    ON		`invoiceprocess`.`invoiceProcessId` = `invoice`.`invoiceProcessId`
          WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceId(0, 'single')) { 
           $sql .= " AND `invoice`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceId(0, 'single') . "'";  
       }
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND `invoice`.`businessPartnerId`='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getCountryId()) { 
           $sql .= " AND `invoice`.`countryId`='".$this->model->getCountryId()."'";  
       }
       if ($this->model->getInvoiceProjectId()) { 
           $sql .= " AND `invoice`.`invoiceProjectId`='".$this->model->getInvoiceProjectId()."'";  
       }
       if ($this->model->getPaymentTermId()) { 
           $sql .= " AND `invoice`.`paymentTermId`='".$this->model->getPaymentTermId()."'";  
       }
       if ($this->model->getInvoiceProcessId()) { 
           $sql .= " AND `invoice`.`invoiceProcessId`='".$this->model->getInvoiceProcessId()."'";  
       }
 } else if ($this->getVendor() == self::MSSQL) {  

          $sql = "
          SELECT                    [invoice].[invoiceId],
                    [company].[companyDescription],
                    [invoice].[companyId],
                    [businessPartner].[businessPartnerCompany],
                    [invoice].[businessPartnerId],
                    [country].[countryDescription],
                    [invoice].[countryId],
                    [invoiceProject].[invoiceProjectDescription],
                    [invoice].[invoiceProjectId],
                    [paymentTerm].[paymentTermDescription],
                    [invoice].[paymentTermId],
                    [invoiceProcess].[invoiceProcessDescription],
                    [invoice].[invoiceProcessId],
                    [invoice].[documentNumber],
                    [invoice].[referenceNumber],
                    [invoice].[invoiceCode],
                    [invoice].[invoiceTotalAmount],
                    [invoice].[invoiceTextAmount],
                    [invoice].[invoiceTaxAmount],
                    [invoice].[invoiceDiscountAmount],
                    [invoice].[invoiceShippingAmount],
                    [invoice].[invoiceDate],
                    [invoice].[invoiceShippingDate],
                    [invoice].[invoiceDescription],
                    [invoice].[invoiceRemark],
                    [invoice].[isDefault],
                    [invoice].[isNew],
                    [invoice].[isDraft],
                    [invoice].[isUpdate],
                    [invoice].[isDelete],
                    [invoice].[isActive],
                    [invoice].[isApproved],
                    [invoice].[isReview],
                    [invoice].[isPost],
                    [invoice].[executeBy],
                    [invoice].[executeTime],
                    [staff].[staffName] 
          FROM 	[invoice]
          JOIN	[staff]
          ON	[invoice].[executeBy] = [staff].[staffId]
    JOIN	[company]
    ON		[company].[companyId] = [invoice].[companyId]
    JOIN	[businessPartner]
    ON		[businessPartner].[businessPartnerId] = [invoice].[businessPartnerId]
    JOIN	[country]
    ON		[country].[countryId] = [invoice].[countryId]
    JOIN	[invoiceProject]
    ON		[invoiceProject].[invoiceProjectId] = [invoice].[invoiceProjectId]
    JOIN	[paymentTerm]
    ON		[paymentTerm].[paymentTermId] = [invoice].[paymentTermId]
    JOIN	[invoiceProcess]
    ON		[invoiceProcess].[invoiceProcessId] = [invoice].[invoiceProcessId]
          WHERE     " . $this->getAuditFilter(); 
       if ($this->model->getInvoiceId(0, 'single')) { 
           $sql .= " AND [invoice].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceId(0, 'single') . "'"; 
       } 
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND [invoice].[businessPartnerId]='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getCountryId()) { 
           $sql .= " AND [invoice].[countryId]='".$this->model->getCountryId()."'";  
       }
       if ($this->model->getInvoiceProjectId()) { 
           $sql .= " AND [invoice].[invoiceProjectId]='".$this->model->getInvoiceProjectId()."'";  
       }
       if ($this->model->getPaymentTermId()) { 
           $sql .= " AND [invoice].[paymentTermId]='".$this->model->getPaymentTermId()."'";  
       }
       if ($this->model->getInvoiceProcessId()) { 
           $sql .= " AND [invoice].[invoiceProcessId]='".$this->model->getInvoiceProcessId()."'";  
       }
        } else if ($this->getVendor() == self::ORACLE) {  

          $sql = "
          SELECT                    INVOICE.INVOICEID AS \"invoiceId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICE.COMPANYID AS \"companyId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    INVOICE.BUSINESSPARTNERID AS \"businessPartnerId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    INVOICE.COUNTRYID AS \"countryId\",
                    INVOICEPROJECT.INVOICEPROJECTDESCRIPTION AS  \"invoiceProjectDescription\",
                    INVOICE.INVOICEPROJECTID AS \"invoiceProjectId\",
                    PAYMENTTERM.PAYMENTTERMDESCRIPTION AS  \"paymentTermDescription\",
                    INVOICE.PAYMENTTERMID AS \"paymentTermId\",
                    INVOICEPROCESS.INVOICEPROCESSDESCRIPTION AS  \"invoiceProcessDescription\",
                    INVOICE.INVOICEPROCESSID AS \"invoiceProcessId\",
                    INVOICE.DOCUMENTNUMBER AS \"documentNumber\",
                    INVOICE.REFERENCENUMBER AS \"referenceNumber\",
                    INVOICE.INVOICECODE AS \"invoiceCode\",
                    INVOICE.INVOICETOTALAMOUNT AS \"invoiceTotalAmount\",
                    INVOICE.INVOICETEXTAMOUNT AS \"invoiceTextAmount\",
                    INVOICE.INVOICETAXAMOUNT AS \"invoiceTaxAmount\",
                    INVOICE.INVOICEDISCOUNTAMOUNT AS \"invoiceDiscountAmount\",
                    INVOICE.INVOICESHIPPINGAMOUNT AS \"invoiceShippingAmount\",
                    INVOICE.INVOICEDATE AS \"invoiceDate\",
                    INVOICE.INVOICESHIPPINGDATE AS \"invoiceShippingDate\",
                    INVOICE.INVOICEDESCRIPTION AS \"invoiceDescription\",
                    INVOICE.INVOICEREMARK AS \"invoiceRemark\",
                    INVOICE.ISDEFAULT AS \"isDefault\",
                    INVOICE.ISNEW AS \"isNew\",
                    INVOICE.ISDRAFT AS \"isDraft\",
                    INVOICE.ISUPDATE AS \"isUpdate\",
                    INVOICE.ISDELETE AS \"isDelete\",
                    INVOICE.ISACTIVE AS \"isActive\",
                    INVOICE.ISAPPROVED AS \"isApproved\",
                    INVOICE.ISREVIEW AS \"isReview\",
                    INVOICE.ISPOST AS \"isPost\",
                    INVOICE.EXECUTEBY AS \"executeBy\",
                    INVOICE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
          FROM 	INVOICE 
          JOIN	STAFF 
          ON	INVOICE.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
    ON		COMPANY.COMPANYID = INVOICE.COMPANYID
    JOIN	BUSINESSPARTNER
    ON		BUSINESSPARTNER.BUSINESSPARTNERID = INVOICE.BUSINESSPARTNERID
    JOIN	COUNTRY
    ON		COUNTRY.COUNTRYID = INVOICE.COUNTRYID
    JOIN	INVOICEPROJECT
    ON		INVOICEPROJECT.INVOICEPROJECTID = INVOICE.INVOICEPROJECTID
    JOIN	PAYMENTTERM
    ON		PAYMENTTERM.PAYMENTTERMID = INVOICE.PAYMENTTERMID
    JOIN	INVOICEPROCESS
    ON		INVOICEPROCESS.INVOICEPROCESSID = INVOICE.INVOICEPROCESSID
         WHERE     " . $this->getAuditFilter(); 
           if ($this->model->getInvoiceId(0, 'single'))  {
               $sql .= " AND INVOICE. ".strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceId(0, 'single') . "'"; 
           } 
       if ($this->model->getBusinessPartnerId()) { 
           $sql .= " AND INVOICE.BUSINESSPARTNERID='".$this->model->getBusinessPartnerId()."'";  
       }
       if ($this->model->getCountryId()) { 
           $sql .= " AND INVOICE.COUNTRYID='".$this->model->getCountryId()."'";  
       }
       if ($this->model->getInvoiceProjectId()) { 
           $sql .= " AND INVOICE.INVOICEPROJECTID='".$this->model->getInvoiceProjectId()."'";  
       }
       if ($this->model->getPaymentTermId()) { 
           $sql .= " AND INVOICE.PAYMENTTERMID='".$this->model->getPaymentTermId()."'";  
       }
       if ($this->model->getInvoiceProcessId()) { 
           $sql .= " AND INVOICE.INVOICEPROCESSID='".$this->model->getInvoiceProcessId()."'";  
       }
           }else { 
               header('Content-Type:application/json; charset=utf-8');  
               echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
               exit(); 
        } 
        /** 
         * filter column based on first character 
         */ 
        if($this->getCharacterQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=" AND `invoice`.`".$this->model->getFilterCharacter()."` like '".$this->getCharacterQuery()."%'"; 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=" AND [invoice].[".$this->model->getFilterCharacter()."] like '".$this->getCharacterQuery()."%'"; 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=" AND Initcap(INVOICE.".strtoupper($this->model->getFilterCharacter()).") LIKE Initcap('".$this->getCharacterQuery()."%')"; 
               }
        } 
        /** 
         * filter column based on Range Of Date 
         * Example Day,Week,Month,Year 
         */ 
        if($this->getDateRangeStartQuery()){ 
               if($this->getVendor()==self::MYSQL){ 
                   $sql.=$this->q->dateFilter('invoice',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if($this->getVendor()==self::MSSQL){ 
                   $sql.=$this->q->dateFilter('invoice',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               } else if ($this->getVendor()==self::ORACLE){ 
                   $sql.=$this->q->dateFilter('INVOICE',$this->model->getFilterDate(),$this->getDateRangeStartQuery(),$this->getDateRangeEndQuery(),$this->getDateRangeTypeQuery(),$this->getDateRangeExtraTypeQuery()); 
               }
           } 
        /** 
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
         * E.g  $filterArray=array('`leaf`.`leafId`'); 
         * @variables $filterArray; 
         */  
        $filterArray =null;
        if($this->getVendor() ==self::MYSQL) { 
            $filterArray = array("`invoice`.`invoiceId`",
                                              "`staff`.`staffPassword`"); 
        } else if ($this->getVendor() == self::MSSQL) {
 		    $filterArray = array("[invoice].[invoiceId]",
                                              "[staff].[staffPassword]"); 
        } else if ($this->getVendor() == self::ORACLE) { 
            $filterArray = array("INVOICE.INVOICEID",
                                              "STAFF.STAFFPASSWORD"); 
        }
        $tableArray = null; 
        if($this->getVendor()==self::MYSQL){ 
            $tableArray = array('staff','invoice','businesspartner','country','invoiceproject','paymentterm','invoiceprocess'); 
        } else if($this->getVendor()==self::MSSQL){ 
            $tableArray = array('staff','invoice','businesspartner','country','invoiceproject','paymentterm','invoiceprocess'); 
        } else if ($this->getVendor()==self::ORACLE){ 
            $tableArray = array('STAFF','INVOICE','BUSINESSPARTNER','COUNTRY','INVOICEPROJECT','PAYMENTTERM','INVOICEPROCESS'); 
        }   
       $tempSql=null;
        if ($this->getFieldQuery()) { 
               $this->q->setFieldQuery($this->getFieldQuery()); 
               if ($this->getVendor() == self::MYSQL) { 
                   $sql .= $this->q->quickSearch($tableArray, $filterArray); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $tempSql = $this->q->quickSearch($tableArray, $filterArray); 
                   $sql .= $tempSql; 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $tempSql = $this->q->quickSearch($tableArray, $filterArray); 
                   $sql .= $tempSql; 
               } 
        } 
       $tempSql2=null;
        if ($this->getGridQuery()) { 
               $this->q->setGridQuery($this->getGridQuery()); 
               if ($this->getVendor() == self::MYSQL) { 
                   $sql .= $this->q->searching(); 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $tempSql2 = $this->q->searching(); 
                   $sql .= $tempSql2; 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $tempSql2 = $this->q->searching(); 
                   $sql .= $tempSql2; 
               } 
        } 
       try {
           $this->q->read($sql);
       } catch (\Exception $e) {
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
        $total = intval($this->q->numberRows()); 
        if ( $this->getSortField()) { 
               if ($this->getVendor() == self::MYSQL) { 
                   $sql .= "	ORDER BY `" . $this->getSortField() . "` " . $this->getOrder() . " "; 
               } else if ($this->getVendor() == self::MSSQL) { 
                   $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " "; 
               } else if ($this->getVendor() == self::ORACLE) { 
                   $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper($this->getOrder()) . " "; 
               } 
        } else {
       	// @note sql server 2012 must order by first then offset ??
        if($this->getVendor() == self::MSSQL){
            $sql .= "	ORDER BY [" . $this->model->getTableName() . "].[" . $this->model->getPrimaryKeyName() . "] ASC ";
        }
    }
        $_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf 
        $_SESSION ['start'] = $this->getStart(); 
        $_SESSION ['limit'] = $this->getLimit(); 
       $sqlDerived = null;
        if ( $this->getLimit()) { 
            // only mysql have limit 
            if ($this->getVendor() == self::MYSQL) { 
                $sqlDerived  = $sql." LIMIT  " . $this->getStart() . "," . $this->getLimit() . " "; 
            } else if ($this->getVendor() == self::MSSQL) { 
              $sqlDerived =
              $sql . " OFFSET " . $this->getStart() . " ROWS
              FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY "; 
             } else if ($this->getVendor() == self::ORACLE) { 

                        $sqlDerived = "

                        SELECT *

                        FROM 	(
 	
                                    SELECT	a.*,

                                            rownum r

                                    FROM ( ".$sql."
 
                                ) a

                        WHERE 	rownum <= '" . ($this->getStart() + $this->getLimit()) . "' )
                        WHERE 	r >=  '" . ($this->getStart() + 1) . "'";
             } else { 
                echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                exit(); 
            } 
        } 
        /* 
         *  Only Execute One Query 
         */ 
        if (!($this->model->getInvoiceId(0, 'single'))) { 
           try {
               $this->q->read($sqlDerived);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
        } 
        $items = array(); 
           $i = 1; 
        while (($row = $this->q->fetchAssoc()) == TRUE) { 
               $row['total'] = $total; // small override 
               $row['counter'] = $this->getStart() + 30; 
               if ($this->model->getInvoiceId(0, 'single')) { 
                   $row['firstRecord'] = $this->firstRecord('value'); 
                   $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceId(0, 'single')); 
                   $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceId(0, 'single')); 
                   $row['lastRecord'] = $this->lastRecord('value'); 
               }  
               $items [] = $row; 
               $i++; 
        }  
        if ($this->getPageOutput() == 'html') { 
               return $items; 
           } else if ($this->getPageOutput() == 'json') { 
           if ($this->model->getInvoiceId(0, 'single')) { 
               $end = microtime(true); 
               $time = $end - $start; 
               echo str_replace(array("[","]"),"",json_encode(array( 
                   'success' => true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'],  
                   'time' => $time,  
                   'firstRecord' => $this->firstRecord('value'),  
                   'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceId(0, 'single')),  
                   'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceId(0, 'single')),  
                   'lastRecord' => $this->lastRecord('value'), 
                   'data' => $items))); 
               exit(); 
           } else { 
               if (count($items) == 0) { 
                   $items = ''; 
               } 
               $end = microtime(true); 
               $time = $end - $start; 
               echo json_encode(array( 
                   'success' =>true,  
                   'total' => $total,  
                   'message' => $this->t['viewRecordMessageLabel'], 
                   'time' => $time,  
                   'firstRecord' => $this->recordSet->firstRecord('value'),  
                   'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceId(0, 'single')),  
                   'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceId(0, 'single')),  
                   'lastRecord' => $this->recordSet->lastRecord('value'), 
                   'data' => $items)); 
               exit();  
           } 
       }	 
       return false;
   }

    /**
     * First Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     */
    function firstRecord($value) {
        return $this->recordSet->firstRecord($value);
    }

    /**
     * Previous Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue
     * @return int
     */
    function previousRecord($value, $primaryKeyValue) {
        return $this->recordSet->previousRecord($value, $primaryKeyValue);
    }

    /**
     * Next Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue Current  Primary Key Value
     * @return int
     */
    function nextRecord($value, $primaryKeyValue) {
        return $this->recordSet->nextRecord($value, $primaryKeyValue);
    }

    /**
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     */
    function lastRecord($value) {
        return $this->recordSet->lastRecord($value);
    }

    /**
     * Update
     * @see config::update()
     */
    function update() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if (!$this->model->getInvoiceCategoryId()) {
            $this->model->setInvoiceCategoryId($this->service->getInvoiceCategoryDefaultValue());
        }
        if (!$this->model->getInvoiceTypeId()) {
            $this->model->setInvoiceTypeId($this->service->getInvoiceTypeDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getBusinessPartnerContactId()) {
            $this->model->setBusinessPartnerContactId($this->service->getBusinessPartnerContactDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getInvoiceProjectId()) {
            $this->model->setInvoiceProjectId($this->service->getInvoiceProjectDefaultValue());
        }
        if (!$this->model->getPaymentTermId()) {
            $this->model->setPaymentTermId($this->service->getPaymentTermDefaultValue());
        }
        if (!$this->model->getWarehouseId()) {
            $this->model->setWarehouseId($this->service->getWarehouseDefaultValue());
        }
        if (!$this->model->getInvoiceProcessId()) {
            $this->model->setInvoiceProcessId($this->service->getInvoiceProcessDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`,
					`quotationNumber`
           FROM 	`invoice`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "],
					[quotationNumber]
           FROM 	[invoice]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceId(0, 'single') . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . ",
					QUOTATIONNUMBER
           FROM 	INVOICE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceId(
                                    0, 'single'
                            ) . "' ";
                }
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            $row = $this->q->fetchArray($result);
            $oldQuotationNumber = $row['quotationNumber'];
            if ($oldQuotationNumber != $this->model->getInvoiceQuotationNumber()) {
                $this->service->updateInvoiceQuotation($this->model->getInvoiceQuotationNumber(), 4);
                $this->service->updateInvoiceQuotation(
                        $this->service->getInvoiceIdFromDocumentNumber($oldQuotationNumber), 3
                );
            }
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `invoice` SET
                       `invoiceCategoryId` = '" . $this->model->getInvoiceCategoryId() . "',
                       `invoiceTypeId` = '" . $this->model->getInvoiceTypeId() . "',
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `businessPartnerContactId` = '" . $this->model->getBusinessPartnerContactId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `invoiceProjectId` = '" . $this->model->getInvoiceProjectId() . "',
                       `paymentTermId` = '" . $this->model->getPaymentTermId() . "',
                       `warehouseId` = '" . $this->model->getWarehouseId() . "',
                       `invoiceProcessId` = '" . $this->model->getInvoiceProcessId() . "',
                       `businessPartnerAddress` = '" . $this->model->getBusinessPartnerAddress() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `invoiceQuotationNumber` = '" . $this->model->getInvoiceQuotationNumber() . "',
                       `purchaseOrderNumber` = '" . $this->model->getPurchaseOrderNumber() . "',
                       `invoiceTotalAmount` = '" . $this->model->getInvoiceTotalAmount() . "',
                       `invoiceTaxAmount` = '" . $this->model->getInvoiceTaxAmount() . "',
                       `invoiceDiscountAmount` = '" . $this->model->getInvoiceDiscountAmount() . "',
                       `invoiceDate` = '" . $this->model->getInvoiceDate() . "',
                       `invoiceDueDate` = '" . $this->model->getInvoiceDueDate() . "',
                       `invoicePromiseDate` = '" . $this->model->getInvoicePromiseDate() . "',
                       `invoiceDescription` = '" . $this->model->getInvoiceDescription() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `invoiceId`='" . $this->model->getInvoiceId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [invoice] SET
                       [invoiceCategoryId] = '" . $this->model->getInvoiceCategoryId() . "',
                       [invoiceTypeId] = '" . $this->model->getInvoiceTypeId() . "',
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [businessPartnerContactId] = '" . $this->model->getBusinessPartnerContactId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [invoiceProjectId] = '" . $this->model->getInvoiceProjectId() . "',
                       [paymentTermId] = '" . $this->model->getPaymentTermId() . "',
                       [warehouseId] = '" . $this->model->getWarehouseId() . "',
                       [invoiceProcessId] = '" . $this->model->getInvoiceProcessId() . "',
                       [businessPartnerAddress] = '" . $this->model->getBusinessPartnerAddress() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [invoiceQuotationNumber] = '" . $this->model->getInvoiceQuotationNumber() . "',
                       [purchaseOrderNumber] = '" . $this->model->getPurchaseOrderNumber() . "',
                       [invoiceTotalAmount] = '" . $this->model->getInvoiceTotalAmount() . "',
                       [invoiceTaxAmount] = '" . $this->model->getInvoiceTaxAmount() . "',
                       [invoiceDiscountAmount] = '" . $this->model->getInvoiceDiscountAmount() . "',
                       [invoiceDate] = '" . $this->model->getInvoiceDate() . "',
                       [invoiceDueDate] = '" . $this->model->getInvoiceDueDate() . "',
                       [invoicePromiseDate] = '" . $this->model->getInvoicePromiseDate() . "',
                       [invoiceDescription] = '" . $this->model->getInvoiceDescription() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [invoiceId]='" . $this->model->getInvoiceId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE INVOICE SET
                        INVOICECATEGORYID = '" . $this->model->getInvoiceCategoryId() . "',
                       INVOICETYPEID = '" . $this->model->getInvoiceTypeId() . "',
                       BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       BUSINESSPARTNERCONTACTID = '" . $this->model->getBusinessPartnerContactId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       INVOICEPROJECTID = '" . $this->model->getInvoiceProjectId() . "',
                       PAYMENTTERMID = '" . $this->model->getPaymentTermId() . "',
                       WAREHOUSEID = '" . $this->model->getWarehouseId() . "',
                       INVOICEPROCESSID = '" . $this->model->getInvoiceProcessId() . "',
                       BUSINESSPARTNERADDRESS = '" . $this->model->getBusinessPartnerAddress() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       INVOICEQUOTATIONNUMBER = '" . $this->model->getInvoiceQuotationNumber() . "',
                       PURCHASEORDERNUMBER = '" . $this->model->getPurchaseOrderNumber() . "',
                       INVOICETOTALAMOUNT = '" . $this->model->getInvoiceTotalAmount() . "',
                       INVOICETAXAMOUNT = '" . $this->model->getInvoiceTaxAmount() . "',
                       INVOICEDISCOUNTAMOUNT = '" . $this->model->getInvoiceDiscountAmount() . "',
                       INVOICEDATE = '" . $this->model->getInvoiceDate() . "',
                       INVOICEDUEDATE = '" . $this->model->getInvoiceDueDate() . "',
                       INVOICEPROMISEDATE = '" . $this->model->getInvoicePromiseDate() . "',
                       INVOICEDESCRIPTION = '" . $this->model->getInvoiceDescription() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  INVOICEID='" . $this->model->getInvoiceId('0', 'single') . "'";
                    }
                }
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        if ($this->model->getFrom() == 'salesOrder.php') {
            $this->service->setInvoiceLedger(
                    $this->model->getBusinessPartnerId(), $this->model->getInvoiceProjectId(), $this->model->getInvoiceDate(), $this->model->getInvoiceDueDate(), $this->model->getInvoiceDescription(), $this->model->getInvoiceTotalAmount(), $this->model->getInvoiceId('0', 'single'), $this->getLeafId(), $this->model->getFrom()
            );
        }
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Delete
     * @see config::delete()
     */
    function delete() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $this->model->delete();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`invoice`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceId(0, 'single') . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[invoice]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceId(0, 'single') . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	INVOICE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceId(
                                    0, 'single'
                            ) . "' ";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE  `invoice`
               SET     `isDefault`     =   '" . $this->model->getIsDefault(0, 'single') . "',
                       `isNew`         =   '" . $this->model->getIsNew(0, 'single') . "',
                       `isDraft`       =   '" . $this->model->getIsDraft(0, 'single') . "',
                       `isUpdate`      =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       `isDelete`      =   '" . $this->model->getIsDelete(0, 'single') . "',
                       `isActive`      =   '" . $this->model->getIsActive(0, 'single') . "',
                       `isApproved`    =   '" . $this->model->getIsApproved(0, 'single') . "',
                       `isReview`      =   '" . $this->model->getIsReview(0, 'single') . "',
                       `isPost`        =   '" . $this->model->getIsPost(0, 'single') . "',
                       `executeBy`     =   '" . $this->model->getExecuteBy() . "',
                       `executeTime`   =   " . $this->model->getExecuteTime() . "
               WHERE   `invoiceId`   =  '" . $this->model->getInvoiceId(0, 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [invoice]
               SET     [isDefault]     =   '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew]         =   '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft]       =   '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate]      =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete]      =   '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive]      =   '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved]    =   '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview]      =   '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost]        =   '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy]     =   '" . $this->model->getExecuteBy() . "',
                       [executeTime]   =   " . $this->model->getExecuteTime() . "
               WHERE   [invoiceId]	=  '" . $this->model->getInvoiceId(0, 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  INVOICE
               SET     ISDEFAULT       =   '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW           =   '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT         =   '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE        =   '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE        =   '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE        =   '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED      =   '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW        =   '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST          =   '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY       =   '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME     =   " . $this->model->getExecuteTime() . "
               WHERE   INVOICEID	=  '" . $this->model->getInvoiceId(0, 'single') . "'";
                    }
                }
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['deleteRecordTextLabel'],
                    "time" => $time
                )
        );
        exit();
    }

/** 
     * To Update flag Status 
     */ 
     function updateStatus() { 
           header('Content-Type:application/json; charset=utf-8'); 
        $start = microtime(true); 
           $sqlLooping=null;
        if ($this->getVendor() == self::MYSQL) { 
               $sql = "SET NAMES utf8"; 
           try {
               $this->q->fast($sql);
           } catch (\Exception $e) {
               echo json_encode(array("success" => false, "message" => $e->getMessage()));
               exit();
           }
        } 
        $this->q->start(); 
        $loop = intval($this->model->getTotal()); 
       $sql=null;
        if ($this->getVendor() == self::MYSQL) { 
               $sql = " 
               UPDATE `".strtolower($this->model->getTableName())."` 
               SET	   `executeBy`		=	'".$this->model->getExecuteBy()."',
                       `executeTime`	=	".$this->model->getExecuteTime().",";
        } else if ($this->getVendor() == self::MSSQL) { 
               $sql = " 
               UPDATE 	[".$this->model->getTableName()."] 
               SET	   [executeBy]		=	'".$this->model->getExecuteBy()."',
                       [executeTime]	=	".$this->model->getExecuteTime().",";
        } else if ($this->getVendor() == self::ORACLE) { 
               $sql = " 
               UPDATE ".strtoupper($this->model->getTableName())." 
               SET	   EXECUTEBY		=	'".$this->model->getExecuteBy()."',
                       EXECUTETIME		=	".$this->model->getExecuteTime().",";
        }  else { 
               echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
               exit(); 
        } 
       if($_SESSION) { 
           if($_SESSION['isAdmin']==1) { 
                 if ($this->model->getIsDefaultTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isDefault` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDefault] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDEFAULT = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsDefault($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDefault` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDefault] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDEFAULT END,";
                     }
            } 
                 if ($this->model->getIsDraftTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isDraft` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDraft] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDRAFT = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsDraft($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDraft` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDraft] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDRAFT END,";
                     }
            } 
                 if ($this->model->getIsNewTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isNew` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isNew] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISNEW = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsNew($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isNew` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isNew] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISNEW END,";
                     }
            } 
                 if ($this->model->getIsActiveTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isActive` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsActive($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isActive` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isActive] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISACTIVE END,";
                     }
            } 
                 if ($this->model->getIsUpdateTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isUpdate` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isUpdate] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISUPDATE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsUpdate($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isUpdate` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isUpdate] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISUPDATE END,";
                     }
            } 
                 if ($this->model->getIsDeleteTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isDelete` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsDelete($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDelete` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDelete] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDELETE END,";
                     }
            } 
                 if ($this->model->getIsReviewTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isReview` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isReview] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISREVIEW = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsReview($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isReview` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isReview] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISREVIEW END,";
                     }
            } 
                 if ($this->model->getIsPostTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isPost` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isPost] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISPOST = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsPost($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isPost` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isPost] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISPOST END,";
                     }
            } 
                 if ($this->model->getIsApprovedTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " `isApproved` = CASE `".$this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isApproved] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISAPPROVED = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     } else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsApproved($i, 'array') . "";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isApproved` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isApproved] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISAPPROVED END,";
                     }
            } 
             } else { 
                 if ($this->model->getIsDeleteTotal() > 0) {
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .=" `isDelete` = CASE `" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isDelete] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISDELETE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     }else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $this->model->getIsDelete($i, 'array') . " ";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDelete` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDelete] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDELETE END,";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .=" `isActive` = CASE `" . $this->model->getPrimaryKeyName() . "`"; 
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= "  [isActive] = CASE [" . $this->model->getPrimaryKeyName() . "]"; 
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ISACTIVE = CASE ".strtoupper($this->model->getPrimaryKeyName()) . " "; 
                     }else { 
                         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
                         exit();
                     }
                     for ($i = 0; $i < $loop; $i++) {
                         if($this->model->getIsDelete($i, 'array') ==0 || $this->model->getIsDelete($i, 'array')==false) {
                         	$isActive=1;
                         } else {
                         	$isActive=0;
                         } 
                         $sqlLooping .= "
                         WHEN " . $this->model->getDiscountTypeId($i, 'array') . "
                         THEN " . $isActive . " ";
                     }
                     if ($this->getVendor() == self::MYSQL) {
                         $sqlLooping .= " ELSE `isDelete` END,";
                     } else if ($this->getVendor() == self::MSSQL) {
                         $sqlLooping .= " ELSE [isDelete] END,";
                     } else if ($this->getVendor() == self::ORACLE) {
                         $sqlLooping .= " ELSE ISDELETE END,";
                     }
                } 
               }
           }
           $sql .= substr($sqlLooping, 0, - 1);
        if ($this->getVendor() == self::MYSQL) {
               $sql .= " 
               WHERE `" . $this->model->getPrimaryKeyName() . "` IN (" . $this->model->getPrimaryKeyAll() . ")"; 
        } else if ($this->getVendor() == self::MSSQL) {
               $sql .= " 
               WHERE [" . $this->model->getPrimaryKeyName() . "] IN (" . $this->model->getPrimaryKeyAll() . ")"; 
        } else if ($this->getVendor() == self::ORACLE) {
               $sql .= " 
               WHERE " . strtoupper($this->model->getPrimaryKeyName()) . "  IN (" . $this->model->getPrimaryKeyAll() . ")"; 
        }  else { 
               echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])); 
               exit(); 
        } 
       $this->q->setPrimaryKeyAll($this->model->getPrimaryKeyAll());
       $this->q->setMultiId(1);
       try {
           $this->q->update($sql);
       } catch (\Exception $e) {
           $this->q->rollback();
           echo json_encode(array("success" => false, "message" => $e->getMessage()));
           exit();
       }
        $this->q->commit(); 
        if ($this->getIsAdmin()) { 
               $message = $this->t['updateRecordTextLabel']; 
        } else {
               $message = $this->t['deleteRecordTextLabel']; 
        } 
        $end = microtime(true); 
        $time = $end - $start; 
        echo json_encode( 
               array(  "success" =>  true, 
                       "message" =>  $message, 
                       "time"    =>  $time) 
           ); 
       exit(); 
    }
    /**
     * To check if a key duplicate or not
     */
    function duplicate() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT  `documentNumber`
           FROM    `invoice`
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  documentNumber]
           FROM    [invoice]
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  DOCUMENTNUMBER as \"documentNumber\"
               FROM    INVOICE
               WHERE   DOCUMENTNUMBER	= 	'" . $this->model->getDocumentNumber() . "'
               AND     ISACTIVE    =   1
               AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = intval($this->q->numberRows());
        if ($total > 0) {
            $row = $this->q->fetchArray();
            $end = microtime(true);
            $time = $end - $start;
            echo json_encode(
                    array(
                        "success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateMessageLabel'],
                        "referenceNo" => $row ['referenceNo'],
                        "time" => $time
                    )
            );
            exit();
        } else {
            $end = microtime(true);
            $time = $end - $start;
            echo json_encode(
                    array(
                        "success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateNotMessageLabel'],
                        "time" => $time
                    )
            );
            exit();
        }
    }

    /**
     * Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return Invoice Category
     * @return null|string
     */
    public function getInvoiceCategory() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceCategory();
    }

    /**
     * Return Invoice Type
     * @return null|string
     */
    public function getInvoiceType() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceType();
    }

    /**
     * Return Business Partner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner($this->model->getBusinessPartnerId());
    }

    /**
     * Return Business Partner Contact
     * @return null|string
     */
    public function getBusinessPartnerContact() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartnerContact($this->model->getBusinessPartnerContactId());
    }

    /**
     * Return Country
     * @return null|string
     */
    public function getCountry() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCountry();
    }

    /**
     * Return Invoice Project
     * @return null|string
     */
    public function getInvoiceProject() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceProject();
    }

    /**
     * Return Payment Terms
     * @return null|string
     */
    public function getPaymentTerm() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPaymentTerm();
    }

    /**
     * Return Warehouse
     * @return null|string
     */
    public function getWarehouse() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getWarehouse();
    }

    /**
     * Return Invoice Process
     * @return null|string
     */
    public function getInvoiceProcess() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceProcess();
    }

    /**
     * Return List Invoice Status Quotation
     * @return mixed
     */
    public function getInvoiceQuotation() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceQuotation($this->model->getBusinessPartnerId());
    }

    /**
     * Set New Business Partner
     * return @void
     */
    public function setNewBusinessPartner() {
        $this->service->setNewBusinessPartner(
                $this->model->getBusinessPartnerCompany(), $this->model->getBusinessPartnerAddress()
        );
    }

    /**
     * Set New Business Partner Contact
     * return @void
     */
    public function setNewBusinessPartnerContact() {
        $this->service->setNewBusinessPartnerContact(
                $this->model->getBusinessPartnerId(), $this->model->getBusinessPartnerContactName(), $this->model->getBusinessPartnerContactPhone(), $this->model->getBusinessPartnerContactEmail()
        );
    }

    public function setNewProductSellingPrice() {
        $this->service->setNewProductSellingPrice($this->model->getProductId(), $this->model->getProductPrice, $this->model->getProductSellingPriceStartDate(), $this->model->getProductSellingPriceEndDate());
    }

    /**
     * Return House Unit For Sold
     * return @void
     */
    public function getHouseForSales() {
        $this->service->getHouseForSales();
    }

    /**
     * Return House Unit For Rent
     * return @void
     */
    public function getHouseForRent() {
        $this->service->getHouseForRent();
    }

    /**
     * Return Currency Rate
     */
    public function getCurrencyRateExchange() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $currencyRate = $this->getCurrency(
                $this->service->getCountryCurrencyCode(), $this->model->getCountryCurrencyTo(), 1
        );
        $end = microtime(true);
        $time = $end - $start;
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $currencyRateText = $a->format($currencyRate);
        } else {
            $currencyRateText = number_format($currencyRate);
        }
        echo json_encode(
                array(
                    "success" => true,
                    "currencyRate" => $currencyRate,
                    "currencyRateText" => $currencyRateText,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Posting
     * @return void
     * @throws \Exception
     */
    public function posting() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        $total = intval($this->model->getTotal());
        if ($total == 0) {
            $this->service->setPosting(
                    $this->model->getInvoiceId(0, 'single'), $this->getLeafId(), $this->model->getFrom()
            );
        } else {
            $this->service->setPosting($this->model->getPrimaryKeyAll(), $this->getLeafId(), $this->model->getFrom());
        }
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "time" => $time,
                    "total" => $total
                )
        );
        exit();
    }

    /**
     * Reporting
     * @see config::excel()
     */
    function excel() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        if ($_SESSION ['start'] == 0) {
            $sql = str_replace(
                    $_SESSION ['start'] . "," . $_SESSION ['limit'], "", str_replace("LIMIT", "", $_SESSION ['sql'])
            );
        } else {
            $sql = $_SESSION ['sql'];
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $username = null;
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
        } else {
            $username = 'Who the fuck are you';
        }
        $this->excel->getProperties()
                ->setCreator($username)
                ->setLastModifiedBy($username)
                ->setTitle($this->getReportTitle())
                ->setSubject('invoice')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/accountReceivable');
        $this->excel->setActiveSheetIndex(0);
        // check file exist or not and return response 
        $styleThinBlackBorderOutline = array(
            'borders' => array(
                'inside' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000')
                ),
                'outline' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000'))
            )
        );
        // header all using  3 line  starting b 
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('X2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:X2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['invoiceCategoryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['invoiceTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['businessPartnerContactIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['invoiceProjectIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['paymentTermIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['warehouseIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['invoiceProcessIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['businessPartnerAddressLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['invoiceQuotationNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['purchaseOrderNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['invoiceTotalAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['invoiceTaxAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('S3', $this->translate['invoiceDiscountAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('T3', $this->translate['invoiceDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('U3', $this->translate['invoiceDueDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('V3', $this->translate['invoiceDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('W3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('X3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['invoiceCategoryDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['invoiceTypeDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue(
                    'F' . $loopRow, strip_tags($row ['businessPartnerContactName'])
            );
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['countryDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'H' . $loopRow, strip_tags($row ['invoiceProjectDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['paymentTermDescription']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['warehouseDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'K' . $loopRow, strip_tags($row ['invoiceProcessDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['businessPartnerAddress']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['invoiceQuotationNumber']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['purchaseOrderNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['invoiceTotalAmount']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['invoiceTaxAmount']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('S' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('S' . $loopRow, strip_tags($row ['invoiceDiscountAmount']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('T' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('T' . $loopRow, strip_tags($row ['invoiceDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('U' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('U' . $loopRow, strip_tags($row ['invoiceDueDate']));
            $this->excel->getActiveSheet()->setCellValue('V' . $loopRow, strip_tags($row ['invoiceDescription']));
            $this->excel->getActiveSheet()->setCellValue('W' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('X' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('X' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'X' . $loopRow;
        }
        $from = 'B2';
        $to = $lastRow;
        $formula = $from . ":" . $to;
        $this->excel->getActiveSheet()->getStyle($formula)->applyFromArray($styleThinBlackBorderOutline);
        $extension = null;
        $folder = null;
        switch ($this->getReportMode()) {
            case 'excel':
                //	$objWriter = \PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
                //optional lock.on request only
                // $objPHPExcel->getSecurity()->setLockWindows(true);
                // $objPHPExcel->getSecurity()->setLockStructure(true);
                // $objPHPExcel->getSecurity()->setWorkbookPassword('PHPExcel');
                $objWriter = new \PHPExcel_Writer_Excel2007($this->excel);
                $extension = '.xlsx';
                $folder = 'excel';
                $filename = "invoice" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
            case 'excel5':
                $objWriter = new \PHPExcel_Writer_Excel5($this->excel);
                $extension = '.xls';
                $folder = 'excel';
                $filename = "invoice" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
            case 'pdf':
                break;
            case 'html':
                $objWriter = new \PHPExcel_Writer_HTML($this->excel);
                // $objWriter->setUseBOM(true); 
                $extension = '.html';
                //$objWriter->setPreCalculateFormulas(false); //calculation off 
                $folder = 'html';
                $filename = "invoice" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
            case 'csv':
                $objWriter = new \PHPExcel_Writer_CSV($this->excel);
                // $objWriter->setUseBOM(true); 
                // $objWriter->setPreCalculateFormulas(false); //calculation off 
                $extension = '.csv';
                $folder = 'excel';
                $filename = "invoice" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time
                            )
                    );
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array(
                                "success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time
                            )
                    );
                    exit();
                }
                break;
        }
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $invoiceObject = new InvoiceClass ();
        if ($_POST['securityToken'] != $invoiceObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $invoiceObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $invoiceObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $invoiceObject->setLimit($_POST ['limit']);
        }
        $invoiceObject->setPageOutput($_POST['output']);
        $invoiceObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $invoiceObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $invoiceObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $invoiceObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $invoiceObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$invoiceObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$invoiceObject->delete(); 
        }
        /**
         * Additional Fast Request
         */
        if ($_POST['method'] == 'fastBusinessPartner') {
            $invoiceObject->setNewBusinessPartner();
        }
        if ($_POST['method'] == 'fastBusinessPartnerContact') {
            $invoiceObject->setNewBusinessPartnerContact();
        }
    }
}
if (isset($_GET ['method'])) {
    $invoiceObject = new InvoiceClass ();
    if ($_GET['securityToken'] != $invoiceObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $invoiceObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $invoiceObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $invoiceObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $invoiceObject->duplicate();
    }
    /**
     * Get Currency Rate
     */
    if ($_GET['method'] == 'currencyRate') {

        $invoiceObject->getCurrencyRateExchange();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $invoiceObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $invoiceObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $invoiceObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $invoiceObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $invoiceObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $invoiceObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $invoiceObject->setServiceOutput('option');
        if (($_GET['filter'] == 'invoiceCategory')) {
            $invoiceObject->getInvoiceCategory();
        }
        if (($_GET['filter'] == 'invoiceType')) {
            $invoiceObject->getInvoiceType();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $invoiceObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'businessPartnerContact')) {
            $invoiceObject->getBusinessPartnerContact();
        }
        if (($_GET['filter'] == 'country')) {
            $invoiceObject->getCountry();
        }
        if (($_GET['filter'] == 'invoiceProject')) {
            $invoiceObject->getInvoiceProject();
        }
        if (($_GET['filter'] == 'paymentTerm')) {
            $invoiceObject->getPaymentTerm();
        }
        if (($_GET['filter'] == 'warehouse')) {
            $invoiceObject->getWarehouse();
        }
        if (($_GET['filter'] == 'invoiceProcess')) {
            $invoiceObject->getInvoiceProcess();
        }
        if (($_GET['filter'] == 'invoiceQuotation')) {
            $invoiceObject->getInvoiceQuotation();
        }
        if (($_GET['filter'] == 'houseSales')) {
            $invoiceObject->getHouseForSales();
        }
        if (($_GET['filter'] == 'houseRent')) {
            $invoiceObject->getHouseForRent();
        }
    }
}
?>
