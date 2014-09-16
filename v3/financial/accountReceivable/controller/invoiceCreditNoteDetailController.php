<?php

namespace Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Controller;

use Core\ConfigClass;
use Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Model\InvoiceCreditNoteDetailModel;
use Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Service\InvoiceCreditNoteDetailService;
use Core\Document\Trail\DocumentTrailClass;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z ++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once ($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once ($newFakeDocumentRoot . "library/class/classDate.php");
require_once ($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");
require_once ($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountReceivable/model/invoiceCreditNoteDetailModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountReceivable/service/invoiceCreditNoteDetailService.php");

/**
 * Class InvoiceCreditNoteDetail
 * this is invoiceCreditNoteDetail controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Controller
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceCreditNoteDetailClass extends ConfigClass {

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
     * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf
     * @var \PHPExcel
     */
    private $excel;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
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
     * Model
     * @var \Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Model\InvoiceCreditNoteDetailModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\AccountReceivable\InvoiceCreditNoteDetail\Service\InvoiceCreditNoteDetailService
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
        $this->setViewPath("./v3/financial/accountReceivable/view/invoiceCreditNoteDetail.php");
        $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceCreditNoteDetailController.php");
        $this->setServicePath("./v3/financial/accountReceivable/service/invoiceCreditNoteDetailService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new InvoiceCreditNoteDetailModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } else if ($this->getVendor() == self::MSSQL) {
            $this->q = new \Core\Database\Mssql\Vendor();
        } else if ($this->getVendor() == self::ORACLE) {
            $this->q = new \Core\Database\Oracle\Vendor();
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

        $this->service = new InvoiceCreditNoteDetailService();
        $this->service->q = $this->q;
        $this->service->t = $this->t;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());
        $this->service->execute();

        $this->recordSet = new RecordSet();
        $this->recordSet->q = $this->q;
        $this->recordSet->setCurrentTable($this->model->getTableName());
        $this->recordSet->setPrimaryKeyName($this->model->getPrimaryKeyName());
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
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        if (!$this->model->getInvoiceCreditNoteId()) {
            $this->model->setInvoiceCreditNoteId($this->service->getInvoiceCreditNoteDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoicecreditnotedetail`
            (
                 `companyId`,
                 `invoiceId`,
                 `invoiceCreditNoteId`,
                 `chartOfAccountId`,
                 `businessPartnerId`,
                 `documentNumber`,
                 `journalNumber`,
                 `invoiceCreditNoteDetailAmount`,
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
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getInvoiceCreditNoteId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteDetailAmount() . "',
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [invoiceCreditNoteDetail]
            (
                 [invoiceCreditNoteDetailId],
                 [companyId],
                 [invoiceId],
                 [invoiceCreditNoteId],
                 [chartOfAccountId],
                 [businessPartnerId],
                 [documentNumber],
                 [journalNumber],
                 [invoiceCreditNoteDetailAmount],
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
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getInvoiceCreditNoteId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteDetailAmount() . "',
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
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO INVOICECREDITNOTEDETAIL
            (
                 COMPANYID,
                 INVOICEID,
                 INVOICECREDITNOTEID,
                 CHARTOFACCOUNTID,
                 BUSINESSPARTNERID,
                 DOCUMENTNUMBER,
                 JOURNALNUMBER,
                 INVOICECREDITNOTEDETAILAMOUNT,
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
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getInvoiceCreditNoteId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteDetailAmount() . "',
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
        $invoiceCreditNoteDetailId = $this->q->lastInsertId("invoiceCreditNoteDetail");
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "invoiceCreditNoteDetailId" => $invoiceCreditNoteDetailId,
                    "time" => $time));
        exit();
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        if ($this->getPageOutput() == 'json' || $this->getPageOutput() == 'table') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $start = microtime(true);
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(" `invoicecreditnotedetail`.`isActive` = 1  AND `invoicecreditnotedetail`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [invoiceCreditNoteDetail].[isActive] = 1 AND [invoiceCreditNoteDetail].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" INVOICECREDITNOTEDETAIL.ISACTIVE = 1  AND INVOICECREDITNOTEDETAIL.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `invoicecreditnotedetail`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [invoiceCreditNoteDetail].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" INVOICECREDITNOTEDETAIL.COMPANYID='" . $this->getCompanyId() . "' ");
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
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
       SELECT                    `invoicecreditnotedetail`.`invoiceCreditNoteDetailId`,
                    `company`.`companyDescription`,
                    `invoicecreditnotedetail`.`companyId`,
                    `invoice`.`invoiceDescription`,
                    `invoicecreditnotedetail`.`invoiceId`,
                    `invoicecreditnote`.`invoiceCreditNoteDescription`,
                    `invoicecreditnotedetail`.`invoiceCreditNoteId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `invoicecreditnotedetail`.`chartOfAccountId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `invoicecreditnotedetail`.`businessPartnerId`,
                    `invoicecreditnotedetail`.`documentNumber`,
                    `invoicecreditnotedetail`.`journalNumber`,
                    `invoicecreditnotedetail`.`invoiceCreditNoteDetailAmount`,
                    `invoicecreditnotedetail`.`isDefault`,
                    `invoicecreditnotedetail`.`isNew`,
                    `invoicecreditnotedetail`.`isDraft`,
                    `invoicecreditnotedetail`.`isUpdate`,
                    `invoicecreditnotedetail`.`isDelete`,
                    `invoicecreditnotedetail`.`isActive`,
                    `invoicecreditnotedetail`.`isApproved`,
                    `invoicecreditnotedetail`.`isReview`,
                    `invoicecreditnotedetail`.`isPost`,
                    `invoicecreditnotedetail`.`executeBy`,
                    `invoicecreditnotedetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicecreditnotedetail`
		  JOIN      `staff`
		  ON        `invoicecreditnotedetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoicecreditnotedetail`.`companyId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `invoicecreditnotedetail`.`invoiceId`
	JOIN	`invoicecreditnote`
	ON		`invoicecreditnote`.`invoiceCreditNoteId` = `invoicecreditnotedetail`.`invoiceCreditNoteId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `invoicecreditnotedetail`.`chartOfAccountId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `invoicecreditnotedetail`.`businessPartnerId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceCreditNoteDetailId(0, 'single')) {
                $sql .= " AND `invoicecreditnotedetail`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND `invoicecreditnotedetail`.`invoiceId`='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getInvoiceCreditNoteId()) {
                $sql .= " AND `invoicecreditnotedetail`.`invoiceCreditNoteId`='" . $this->model->getInvoiceCreditNoteId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `invoicecreditnotedetail`.`chartOfAccountId`='" . $this->model->getChartOfAccountId() . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `invoicecreditnotedetail`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [invoiceCreditNoteDetail].[invoiceCreditNoteDetailId],
                    [company].[companyDescription],
                    [invoiceCreditNoteDetail].[companyId],
                    [invoice].[invoiceDescription],
                    [invoiceCreditNoteDetail].[invoiceId],
                    [invoiceCreditNote].[invoiceCreditNoteDescription],
                    [invoiceCreditNoteDetail].[invoiceCreditNoteId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [invoiceCreditNoteDetail].[chartOfAccountId],
                    [businessPartner].[businessPartnerCompany],
                    [invoiceCreditNoteDetail].[businessPartnerId],
                    [invoiceCreditNoteDetail].[documentNumber],
                    [invoiceCreditNoteDetail].[journalNumber],
                    [invoiceCreditNoteDetail].[invoiceCreditNoteDetailAmount],
                    [invoiceCreditNoteDetail].[isDefault],
                    [invoiceCreditNoteDetail].[isNew],
                    [invoiceCreditNoteDetail].[isDraft],
                    [invoiceCreditNoteDetail].[isUpdate],
                    [invoiceCreditNoteDetail].[isDelete],
                    [invoiceCreditNoteDetail].[isActive],
                    [invoiceCreditNoteDetail].[isApproved],
                    [invoiceCreditNoteDetail].[isReview],
                    [invoiceCreditNoteDetail].[isPost],
                    [invoiceCreditNoteDetail].[executeBy],
                    [invoiceCreditNoteDetail].[executeTime],
                    [staff].[staffName]
		  FROM 	[invoiceCreditNoteDetail]
		  JOIN	[staff]
		  ON	[invoiceCreditNoteDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [invoiceCreditNoteDetail].[companyId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] = [invoiceCreditNoteDetail].[invoiceId]
	JOIN	[invoiceCreditNote]
	ON		[invoiceCreditNote].[invoiceCreditNoteId] = [invoiceCreditNoteDetail].[invoiceCreditNoteId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [invoiceCreditNoteDetail].[chartOfAccountId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [invoiceCreditNoteDetail].[businessPartnerId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceCreditNoteDetailId(0, 'single')) {
                $sql .= " AND [invoiceCreditNoteDetail].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND [invoiceCreditNoteDetail].[invoiceId]='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getInvoiceCreditNoteId()) {
                $sql .= " AND [invoiceCreditNoteDetail].[invoiceCreditNoteId]='" . $this->model->getInvoiceCreditNoteId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND [invoiceCreditNoteDetail].[chartOfAccountId]='" . $this->model->getChartOfAccountId() . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND [invoiceCreditNoteDetail].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEDETAILID AS \"invoiceCreditNoteDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICECREDITNOTEDETAIL.COMPANYID AS \"companyId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICECREDITNOTEDETAIL.INVOICEID AS \"invoiceId\",
                    INVOICECREDITNOTE.INVOICECREDITNOTEDESCRIPTION AS  \"invoiceCreditNoteDescription\",
                    INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEID AS \"invoiceCreditNoteId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    INVOICECREDITNOTEDETAIL.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    INVOICECREDITNOTEDETAIL.BUSINESSPARTNERID AS \"businessPartnerId\",
                    INVOICECREDITNOTEDETAIL.DOCUMENTNUMBER AS \"documentNumber\",
                    INVOICECREDITNOTEDETAIL.JOURNALNUMBER AS \"journalNumber\",
                    INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEDETAILAMOUNT AS \"invoiceCreditNoteDetailAmount\",
                    INVOICECREDITNOTEDETAIL.ISDEFAULT AS \"isDefault\",
                    INVOICECREDITNOTEDETAIL.ISNEW AS \"isNew\",
                    INVOICECREDITNOTEDETAIL.ISDRAFT AS \"isDraft\",
                    INVOICECREDITNOTEDETAIL.ISUPDATE AS \"isUpdate\",
                    INVOICECREDITNOTEDETAIL.ISDELETE AS \"isDelete\",
                    INVOICECREDITNOTEDETAIL.ISACTIVE AS \"isActive\",
                    INVOICECREDITNOTEDETAIL.ISAPPROVED AS \"isApproved\",
                    INVOICECREDITNOTEDETAIL.ISREVIEW AS \"isReview\",
                    INVOICECREDITNOTEDETAIL.ISPOST AS \"isPost\",
                    INVOICECREDITNOTEDETAIL.EXECUTEBY AS \"executeBy\",
                    INVOICECREDITNOTEDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	INVOICECREDITNOTEDETAIL
		  JOIN	STAFF
		  ON	INVOICECREDITNOTEDETAIL.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICECREDITNOTEDETAIL.COMPANYID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = INVOICECREDITNOTEDETAIL.INVOICEID
	JOIN	INVOICECREDITNOTE
	ON		INVOICECREDITNOTE.INVOICECREDITNOTEID = INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = INVOICECREDITNOTEDETAIL.CHARTOFACCOUNTID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = INVOICECREDITNOTEDETAIL.BUSINESSPARTNERID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceCreditNoteDetailId(0, 'single')) {
                $sql .= " AND INVOICECREDITNOTEDETAIL. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND INVOICECREDITNOTEDETAIL.INVOICEID='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getInvoiceCreditNoteId()) {
                $sql .= " AND INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEID='" . $this->model->getInvoiceCreditNoteId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND INVOICECREDITNOTEDETAIL.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId() . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND INVOICECREDITNOTEDETAIL.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
            }
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=" AND `invoicecreditnotedetail`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [invoiceCreditNoteDetail].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(INVOICECREDITNOTEDETAIL." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('invoicecreditnotedetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('invoiceCreditNoteDetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('INVOICECREDITNOTEDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search.
         * E.g  $filterArray=array('`leaf`.`leafId`');
         * @variables $filterArray;
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`invoicecreditnotedetail`.`invoiceCreditNoteDetailId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[invoiceCreditNoteDetail].[invoiceCreditNoteDetailId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("INVOICECREDITNOTEDETAIL.INVOICECREDITNOTEDETAILID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'invoicecreditnotedetail', 'company', 'invoice', 'invoicecreditnote', 'chartofaccount', 'businesspartner');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'invoicecreditnotedetail', 'company', 'invoice', 'invoicecreditnote', 'chartofaccount', 'businesspartner');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'INVOICECREDITNOTEDETAIL', 'COMPANY', 'INVOICE', 'INVOICECREDITNOTE', 'CHARTOFACCOUNT', 'BUSINESSPARTNER');
        }
        $tempSql = null;
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
        $tempSql2 = null;
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
        if ($this->getSortField()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= "	ORDER BY `" . $this->getSortField() . "` " . $this->getOrder() . " ";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " ";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper($this->getOrder()) . " ";
            }
        } else {
            // @note sql server 2012 must order by first then offset ??
            if ($this->getVendor() == self::MSSQL) {
                $sql .= "	ORDER BY [" . $this->model->getTableName() . "].[" . $this->model->getPrimaryKeyName() . "] ASC ";
            }
        }
        $_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf
        $_SESSION ['start'] = $this->getStart();
        $_SESSION ['limit'] = $this->getLimit();
        $sqlDerived = null;
        if ($this->getLimit()) {
            // only mysql have limit
            $sqlDerived = $sql . " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
            if ($this->getVendor() == self::MYSQL) {
                
            } else if ($this->getVendor() == self::MSSQL) {
                /**
                 * Sql Server  2012 format only.Row Number
                 * Parameter Query We don't support
                 **/
                $sqlDerived = $sql . " 	OFFSET  	" . $this->getStart() . " ROWS
											FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY ";
            } else if ($this->getVendor() == self::ORACLE) {
                /**
                 * Oracle using derived table also
                 * */
                $sqlDerived = "

						SELECT *

						FROM 	(

									SELECT	a.*,

											rownum r

									FROM ( " . $sql . "

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
        if (!($this->model->getInvoiceCreditNoteDetailId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 20;
            if ($this->model->getInvoiceCreditNoteDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceCreditNoteDetailId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceCreditNoteDetailId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'table') {
            $this->setService('html');
            $str = null;
            if (is_array($items)) {
                $this->setServiceOutput('html');
                $totalRecordDetail = intval(count($items));
                if ($totalRecordDetail > 0) {
                    $counter = 0;
                    $totalDebit = 0;
                    $totalCredit = 0;
                    for ($j = 0; $j < $totalRecordDetail; $j++) {
                        $counter++;
                        $str.="<tr id='" . $items[$j]['invoiceCreditNoteDetailId'] . "'>";
                        $str.="<td align=\"center\"><div class=\"btn-group\" align=\"center\">";
                        $str.="<input type='hidden' name='invoiceCreditNoteDetailId[]'     id='invoiceCreditNoteDetailId" . $items[$j]['invoiceCreditNoteDetailId'] . "'  value='" . $items[$j]['invoiceCreditNoteDetailId'] . "'>";
                        $str.="<input type='hidden' name='invoiceCreditNoteId[]'
                    id='invoiceCreditNoteDetailId" . $items[$j]['invoiceCreditNoteId'] . "'
                        value='" . $items[$j]['invoiceCreditNoteId'] . "'>";
                        $str.="<button type=\"button\" class=\"btn btn-warning btn-mini\" title=\"Edit\" onClick=\"showFormUpdateDetail('" . $this->getLeafId() . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken() . "','" . $items[$j]['invoiceCreditNoteDetailId'] . "')\"><i class=\"glyphicon glyphicon-edit glyphicon-white\"></i></button>";
                        $str.="<button type=\"button\" class=\"btn btn-danger btn-mini\" title=\"Delete\" onClick=\"('" . $items[$j]['invoiceCreditNoteDetailId'] . "')\"><i class=\"glyphicon glyphicon-trash  glyphicon-white\"></i></button><div id=\"miniInfoPanel" . $items[$j]['invoiceCreditNoteDetailId'] . "\"></div></td>";
                        $chartOfAccountArray = $this->getChartOfAccount();
                        $str.="<td id='chartOfAccountId" . $items[$j]['invoiceCreditNoteDetailId'] . "DetailForm'>";
                        $str.="<div class=\"input-append\"><select name=\"chartOfAccountId[]\" id=\"chartOfAccountId" . $items[$j]['invoiceCreditNoteDetailId'] . "\" class=\"chzn-select form-control\" onChange=\"removeMeErrorDetail('chartOfAccountId" . $items[$j]['invoiceCreditNoteDetailId'] . "')\">";
                        $str.="<option value=\"\">" . $this->t['pleaseSelectTextLabel'] . "</option>";
                        if (is_array($chartOfAccountArray)) {
                            $totalRecord = intval(count($chartOfAccountArray));
                            if ($totalRecord > 0) {
                                for ($i = 0; $i < $totalRecord; $i++) {
                                    if ($items[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = NULL;
                                    }
                                    $str.="<option value='" . $chartOfAccountArray[$i]['chartOfAccountId'] . "' " . $selected . ">" . $chartOfAccountArray[$i]['chartOfAccountTitle'] . "</option>";
                                }
                            } else {
                                $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                        } else {
                            $str.="<option value=\"\">" . $this->t['notAvailableTextLabel'] . "</option>";
                        }
                        $str.="</select></div></div>";
                        $str.="</td>";
                        $str.="<td vAlign=\"top\" align=\"center\"><input class=\"form-control\" type=\"text\" name=\"invoiceCreditNoteDetailAmount[]\" id=\"invoiceCreditNoteDetailAmount" . $items[$j]['invoiceCreditNoteDetailId'] . "\" value=\"" . $items[$j]['invoiceCreditNoteDetailAmount'] . "\"></td>";
                        $debit = 0;
                        $credit = 0;
                        $x = 0;
                        $y = 0;
                        $d = $items[$j]['invoiceCreditNoteDetailAmount'];
                        if ($d > 0) {
                            $x = $d;
                        } else {
                            $y = $d;
                        }
                        if (class_exists('NumberFormatter')) {
                            if ($this->systemFormatArray['languageCode'] != '') {
                                $a = new \NumberFormatter(
                                        $this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY
                                );
                                if ($d > 0) {
                                    $debit = $a->format($d);
                                } else {
                                    $credit = $a->format($d);
                                }
                            } else {
                                if ($d > 0) {
                                    $debit = number_format($d) . " You can assign Currency Format ";
                                } else {
                                    $credit = number_format($d) . " You can assign Currency Format ";
                                }
                            }
                        } else {
                            if ($d > 0) {
                                $debit = number_format($d);
                            } else {
                                $credit = number_format($d);
                            }
                        }
                        $totalDebit += $x;
                        $totalCredit += $y;

                        $str .= "<td vAlign=\"middle\" align=\"right\"><div id=\"debit_" . $items[$j]['invoiceCreditNoteDetailId'] . "\" align=\"right\">" . $debit . "</div></td>";
                        $str .= "<td vAlign=\"middle\" align=\"right\"><div id=\"credit_" . $items[$j]['invoiceCreditNoteDetailId'] . "\" align=\"right\">" . $credit . "</div></td>\n";
                        $str.="</tr>";
                    }
                } else {
                    $str .= "<tr>";
                    $str .= "<td colspan=\"6\">" . $this->exceptionMessageReturn(
                                    $this->t['recordNotFoundLabel']
                            ) . "</td>";
                    $str .= "</tr>";
                }
            } else {
                $str.="<tr>";
                $str.="<td colspan=\"6\" align=\"center\">" . $this->exceptionMessageReturn($this->t['recordNotFoundLabel']) . "</td>";
                $str.="</tr>";
            }
            if ($totalDebit == abs($totalCredit)) {
                $balanceColor = 'success';
            } else {
                $balanceColor = 'warning';
            }
            if (class_exists('NumberFormatter')) {
                if ($this->systemFormatArray['languageCode'] != '') {
                    $a = new \NumberFormatter(
                            $this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY
                    );
                    $totalDebit = $a->format($totalDebit);
                    $totalCredit = $a->format($totalCredit);
                } else {
                    $totalDebit = number_format($totalDebit) . " You can assign Currency Format ";
                    $totalCredit = number_format($totalCredit) . " You can assign Currency Format ";
                }
            } else {
                $totalDebit = number_format($totalDebit);
                $totalCredit = number_format($totalCredit);
            }
            $str .= "<tr id=\"totalDetail\" class=\"" . $balanceColor . "\">\n";
            $str .= "<td colspan=\"3\">&nbsp;</td>\n";
            $str .= "<td align=\"right\"><div id=\"totalDebit\" align=\"right\">" . $totalDebit . "</div></td>\n";
            $str .= "<td align=\"right\"><div id=\"totalCredit\" align=\"right\">" . $totalCredit . "</div></td>\n";
            $str .= "</tr>";
            echo json_encode(array('success' => true, 'tableData' => $str));
            exit();
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getInvoiceCreditNoteDetailId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceCreditNoteDetailId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceCreditNoteDetailId(0, 'single')),
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
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->recordSet->firstRecord('value'),
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceCreditNoteDetailId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceCreditNoteDetailId(0, 'single')),
                    'lastRecord' => $this->recordSet->lastRecord('value'),
                    'data' => $items));
                exit();
            }
        }
        return false;
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
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        if (!$this->model->getInvoiceCreditNoteId()) {
            $this->model->setInvoiceCreditNoteId($this->service->getInvoiceCreditNoteDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`invoicecreditnotedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[invoiceCreditNoteDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	INVOICECREDITNOTEDETAIL
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `invoicecreditnotedetail` SET
                       `invoiceId` = '" . $this->model->getInvoiceId() . "',
                       `invoiceCreditNoteId` = '" . $this->model->getInvoiceCreditNoteId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `journalNumber` = '" . $this->model->getJournalNumber() . "',
                       `invoiceCreditNoteDetailAmount` = '" . $this->model->getInvoiceCreditNoteDetailAmount() . "',
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
               WHERE    `invoiceCreditNoteDetailId`='" . $this->model->getInvoiceCreditNoteDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [invoiceCreditNoteDetail] SET
                       [invoiceId] = '" . $this->model->getInvoiceId() . "',
                       [invoiceCreditNoteId] = '" . $this->model->getInvoiceCreditNoteId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [journalNumber] = '" . $this->model->getJournalNumber() . "',
                       [invoiceCreditNoteDetailAmount] = '" . $this->model->getInvoiceCreditNoteDetailAmount() . "',
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
                WHERE   [invoiceCreditNoteDetailId]='" . $this->model->getInvoiceCreditNoteDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE INVOICECREDITNOTEDETAIL SET
                        INVOICEID = '" . $this->model->getInvoiceId() . "',
                       INVOICECREDITNOTEID = '" . $this->model->getInvoiceCreditNoteId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber() . "',
                       INVOICECREDITNOTEDETAILAMOUNT = '" . $this->model->getInvoiceCreditNoteDetailAmount() . "',
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
                WHERE  INVOICECREDITNOTEDETAILID='" . $this->model->getInvoiceCreditNoteDetailId('0', 'single') . "'";
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
                array("success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "time" => $time));
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
           FROM 	`invoicecreditnotedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[invoiceCreditNoteDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	INVOICECREDITNOTEDETAIL
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "' ";
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
               UPDATE  `invoicecreditnotedetail`
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
               WHERE   `invoiceCreditNoteDetailId`   =  '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [invoiceCreditNoteDetail]
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
               WHERE   [invoiceCreditNoteDetailId]	=  '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  INVOICECREDITNOTEDETAIL
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
               WHERE   INVOICECREDITNOTEDETAILID	=  '" . $this->model->getInvoiceCreditNoteDetailId(0, 'single') . "'";
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
                array("success" => true,
                    "message" => $this->t['deleteRecordTextLabel'],
                    "time" => $time));
        exit();
    }

 
    /**
     * To check if a key duplicate or not
     * @return void
     */
    function duplicate() {
        
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
     * Next Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue Current  Primary Key Value
     * @return int
     */
    function nextRecord($value, $primaryKeyValue) {
        return $this->recordSet->nextRecord($value, $primaryKeyValue);
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
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     */
    function lastRecord($value) {
        return $this->recordSet->lastRecord($value);
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
     * Return  Invoice
     * @return null|string
     */
    public function getInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoice();
    }

    /**
     * Return  InvoiceCreditNote
     * @return null|string
     */
    public function getInvoiceCreditNote() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceCreditNote();
    }

    /**
     * Return  ChartOfAccount
     * @return null|string
     */
    public function getChartOfAccount() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccount();
    }

    /**
     * Return  BusinessPartner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner();
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
         FROM    `invoicecreditnotedetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
            $sql.="AND     `invoiceCreditNoteId` = " . $this->model->getInvoiceCreditNoteId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [invoiceCreditNoteDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            $sql.="AND     [invoiceCreditNoteId] = " . $this->model->getInvoiceCreditNoteId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      INVOICECREDITNOTEDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
            $sql.="AND     INVOICECREDITNOTEID = " . $this->model->getInvoiceCreditNoteId() . " ";
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
        } return $total;
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
            $sql = str_replace($_SESSION ['start'] . "," . $_SESSION ['limit'], "", str_replace("LIMIT", "", $_SESSION ['sql']));
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
                ->setSubject('invoiceCreditNoteDetail')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/accountReceivable');
        $this->excel->setActiveSheetIndex(0);
        // check file exist or not and return response
        $styleThinBlackBorderOutline = array('borders' => array('inside' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000')), 'outline' => array('style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '000000'))));
        // header all using  3 line  starting b
        $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('K2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:K2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['invoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['invoiceCreditNoteIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['journalNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['invoiceCreditNoteDetailAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['invoiceDescription']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['invoiceCreditNoteDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['chartOfAccountTitle']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['journalNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['invoiceCreditNoteDetailAmount']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'K' . $loopRow;
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
                $filename = "invoiceCreditNoteDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
            case 'excel5':
                $objWriter = new \PHPExcel_Writer_Excel5($this->excel);
                $extension = '.xls';
                $folder = 'excel';
                $filename = "invoiceCreditNoteDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
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
                $filename = "invoiceCreditNoteDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
            case 'csv':
                $objWriter = new \PHPExcel_Writer_CSV($this->excel);
                // $objWriter->setUseBOM(true);
                // $objWriter->setPreCalculateFormulas(false); //calculation off
                $extension = '.csv';
                $folder = 'excel';
                $filename = "invoiceCreditNoteDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountReceivable/document/" . $folder . "/" . $filename;
                $this->documentTrail->createTrail($this->getLeafId(), $path, $filename);
                $objWriter->save($path);
                $file = fopen($path, 'r');
                if ($file) {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => true,
                                "message" => $this->t['fileGenerateMessageLabel'],
                                "filename" => $filename,
                                "folder" => $folder,
                                "time" => $time));
                    exit();
                } else {
                    $end = microtime(true);
                    $time = $end - $start;
                    echo json_encode(
                            array("success" => false,
                                "message" => $this->t['fileNotGenerateMessageLabel'],
                                "time" => $time));
                    exit();
                }
                break;
        }
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $invoiceCreditNoteDetailObject = new InvoiceCreditNoteDetailClass ();
        if ($_POST['securityToken'] != $invoiceCreditNoteDetailObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $invoiceCreditNoteDetailObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $invoiceCreditNoteDetailObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $invoiceCreditNoteDetailObject->setLimit($_POST ['limit']);
        }
        $invoiceCreditNoteDetailObject->setPageOutput($_POST['output']);
        $invoiceCreditNoteDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $invoiceCreditNoteDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $invoiceCreditNoteDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $invoiceCreditNoteDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $invoiceCreditNoteDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$invoiceCreditNoteDetailObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$invoiceCreditNoteDetailObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $invoiceCreditNoteDetailObject = new InvoiceCreditNoteDetailClass ();
    if ($_GET['securityToken'] != $invoiceCreditNoteDetailObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $invoiceCreditNoteDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $invoiceCreditNoteDetailObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $invoiceCreditNoteDetailObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $invoiceCreditNoteDetailObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $invoiceCreditNoteDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $invoiceCreditNoteDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $invoiceCreditNoteDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $invoiceCreditNoteDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $invoiceCreditNoteDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $invoiceCreditNoteDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $invoiceCreditNoteDetailObject->setServiceOutput('option');
        if (($_GET['filter'] == 'invoice')) {
            $invoiceCreditNoteDetailObject->getInvoice();
        }
        if (($_GET['filter'] == 'invoiceCreditNote')) {
            $invoiceCreditNoteDetailObject->getInvoiceCreditNote();
        }
        if (($_GET['filter'] == 'chartOfAccount')) {
            $invoiceCreditNoteDetailObject->getChartOfAccount();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $invoiceCreditNoteDetailObject->getBusinessPartner();
        }
    }
}
?>
