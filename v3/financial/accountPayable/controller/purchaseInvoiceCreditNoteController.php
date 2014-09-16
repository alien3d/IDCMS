<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Controller;

use Core\ConfigClass;
use Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Model\PurchaseInvoiceCreditNoteModel;
use Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Service\PurchaseInvoiceCreditNoteService;
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
require_once ($newFakeDocumentRoot . "v3/financial/accountPayable/model/purchaseInvoiceCreditNoteModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountPayable/service/purchaseInvoiceCreditNoteService.php");

/**
 * Class PurchaseInvoiceCreditNote
 * this is purchaseInvoiceCreditNote controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Controller
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceCreditNoteClass extends ConfigClass {

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
     * @var \Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Model\PurchaseInvoiceCreditNoteModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\AccountPayable\PurchaseInvoiceCreditNote\Service\PurchaseInvoiceCreditNoteService
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
        $this->setViewPath("./v3/financial/accountPayable/view/purchaseInvoiceCreditNote.php");
        $this->setControllerPath("./v3/financial/accountPayable/controller/purchaseInvoiceCreditNoteController.php");
        $this->setServicePath("./v3/financial/accountPayable/service/purchaseInvoiceCreditNoteService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new PurchaseInvoiceCreditNoteModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/accountPayable/view/" . $this->model->getFrom());
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
        $this->setLeafId($arrayInfo['leafId']);
        $this->setReportTitle($applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative);

        $this->service = new PurchaseInvoiceCreditNoteService();
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
		
		// override for Structured Query Language (SQL) statement

        if ($this->model->getFrom() == 'purchaseInvoiceCreditNote.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteMaintenance.php'
        ) {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND `purchaseinvoicecreditnote`.`isActive` = 1 
					AND `purchaseinvoicecreditnote`.`isPost` = 0 
					AND `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND [purchaseInvoiceCreditNote].[isActive] = 1 
					AND [purchaseInvoiceCreditNote].[isPost] = 0 
					AND [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND PURCHASEINVOICECREDITNOTE.ISACTIVE = 1 
					AND PURCHASEINVOICECREDITNOTE.ISPOST = 0 
					AND PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTECODE !='UNBL'
				");
            }
        }

        if ($this->model->getFrom() == 'purchaseInvoiceCreditNotePost.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNotePosting.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND `purchaseinvoicecreditnote`.`isActive` = 1  
					AND `purchaseinvoicecreditnote`.`isBalance`   =    1 
					AND `purchaseinvoicecreditnote`.`isPost` = 0 
					AND `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND [purchaseInvoiceCreditNote].[isActive] = 1  
					AND [isBalance]   =    1 
					AND [purchaseinvoiceCreditNote].[isPost] = 0 
					AND [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND PURCHASEINVOICECREDITNOTE.ISACTIVE = 1 
					AND ISBALANCE =    1 AND PURCHASEINVOICECREDITNOTE.ISPOST = 0 
					AND PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTECODE !='UNBL'
				");
            }
        }

        if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteHistory.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND `purchaseinvoicecreditnote`.`isActive` = 1 
					AND `purchaseinvoicecreditnote`.`isBalance`   =    1 
					AND `purchaseinvoicecreditnote`.`isPost` = 1 
					AND `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [purchaseInvoiceCreditNote].[isActive] = 1 
					AND [purchaseInvoiceCreditNote].[isBalance]   =    1  
					AND [purchaseInvoiceCreditNote].[isPost] = 1 
					AND [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND PURCHASEINVOICECREDITNOTE.ISACTIVE = 1 
					AND PURCHASEINVOICECREDITNOTE.ISBALANCE =    1 
					AND PURCHASEINVOICECREDITNOTE.ISPOST = 1
					AND PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTECODE !='UNBL'
				");
            }
        }
        if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteCancel.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteVoid.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND `purchaseinvoicecreditnote`.`isActive` = 0 
					AND `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [purchaseInvoiceCreditNote].[isActive] = 0 
					AND [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND PURCHASEINVOICECREDITNOTE.ISACTIVE = 0 
					AND PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTECODE !='UNBL'
				");
            }
        }

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
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getPurchaseInvoiceId()) {
            $this->model->setPurchaseInvoiceId($this->service->getPurchaseInvoiceDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `purchaseinvoicecreditnote`
            (
                 `companyId`,
                 `businessPartnerId`,
                 `purchaseInvoiceId`,
                 `purchaseInvoiceCreditNoteTitle`,
                 `documentNumber`,
                 `purchaseInvoiceCreditNoteAmount`,
                 `purchaseInvoiceCreditNoteAmountText`,
                 `referenceNumber`,
                 `purchaseInvoiceCreditNoteDate`,
                 `purchaseInvoiceCreditNoteDescription`,
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
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteAmount() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteAmountText() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteDate() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteDescription() . "',
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
            INSERT INTO [purchaseInvoiceCreditNote]
            (
                 [purchaseInvoiceCreditNoteId],
                 [companyId],
                 [businessPartnerId],
                 [purchaseInvoiceId],
                 [purchaseInvoiceCreditNoteTitle],
                 [documentNumber],
                 [purchaseInvoiceCreditNoteAmount],
                 [purchaseInvoiceCreditNoteAmountText],
                 [referenceNumber],
                 [purchaseInvoiceCreditNoteDate],
                 [purchaseInvoiceCreditNoteDescription],
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
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteAmount() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteAmountText() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteDate() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteDescription() . "',
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
            INSERT INTO PURCHASEINVOICECREDITNOTE
            (
                 COMPANYID,
                 BUSINESSPARTNERID,
                 PURCHASEINVOICEID,
                 PURCHASEINVOICECREDITNOTETITLE,
                 DOCUMENTNUMBER,
                 PURCHASEINVOICECREDITNOTEAMOUNT,
                 PURCHASEINVOICECREDITNOTEAMOUNTTEXT,
                 REFERENCENUMBER,
                 PURCHASEINVOICECREDITNOTEDATE,
                 PURCHASEINVOICECREDITNOTEDESCRIPTION,
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
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteAmount() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteAmountText() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteDate() . "',
                 '" . $this->model->getPurchaseInvoiceCreditNoteDescription() . "',
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
        $purchaseInvoiceCreditNoteId = $this->q->lastInsertId("purchaseInvoiceCreditNote");
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "purchaseInvoiceCreditNoteId" => $purchaseInvoiceCreditNoteId,
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
                    $this->setAuditFilter(" `purchaseinvoicecreditnote`.`isActive` = 1  AND `purchaseinvoicecreditnote`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [purchaseInvoiceCreditNote].[isActive] = 1 AND [purchaseInvoiceCreditNote].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PURCHASEINVOICECREDITNOTE.ISACTIVE = 1  AND PURCHASEINVOICECREDITNOTE.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `purchaseinvoicecreditnote`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [purchaseInvoiceCreditNote].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" PURCHASEINVOICECREDITNOTE.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteId`,
                    `company`.`companyDescription`,
                    `purchaseinvoicecreditnote`.`companyId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `purchaseinvoicecreditnote`.`businessPartnerId`,
                    `purchaseinvoice`.`purchaseInvoiceDescription`,
                    `purchaseinvoicecreditnote`.`purchaseInvoiceId`,
					 `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteCode`,
                    `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteTitle`,
                    `purchaseinvoicecreditnote`.`documentNumber`,
                    `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteAmount`,
                    `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteAmountText`,
                    `purchaseinvoicecreditnote`.`referenceNumber`,
                    `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteDate`,
                    `purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteDescription`,
                    `purchaseinvoicecreditnote`.`isDefault`,
                    `purchaseinvoicecreditnote`.`isNew`,
                    `purchaseinvoicecreditnote`.`isDraft`,
                    `purchaseinvoicecreditnote`.`isUpdate`,
                    `purchaseinvoicecreditnote`.`isDelete`,
                    `purchaseinvoicecreditnote`.`isActive`,
                    `purchaseinvoicecreditnote`.`isApproved`,
                    `purchaseinvoicecreditnote`.`isReview`,
                    `purchaseinvoicecreditnote`.`isPost`,
                    `purchaseinvoicecreditnote`.`executeBy`,
                    `purchaseinvoicecreditnote`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `purchaseinvoicecreditnote`
		  JOIN      `staff`
		  ON        `purchaseinvoicecreditnote`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `purchaseinvoicecreditnote`.`companyId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `purchaseinvoicecreditnote`.`businessPartnerId`
	JOIN	`purchaseinvoice`
	ON		`purchaseinvoice`.`purchaseInvoiceId` = `purchaseinvoicecreditnote`.`purchaseInvoiceId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseInvoiceCreditNoteId(0, 'single')) {
                $sql .= " AND `purchaseinvoicecreditnote`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `purchaseinvoicecreditnote`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getPurchaseInvoiceId()) {
                $sql .= " AND `purchaseinvoicecreditnote`.`purchaseInvoiceId`='" . $this->model->getPurchaseInvoiceId() . "'";
            }
			if ($this->model->getFrom() == 'purchaseInvoiceCreditNote.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteMaintenance.php') {
                $sql .= " AND	`purchaseinvoicecreditnote`.`isActive`= 1 AND	`purchaseinvoicecreditnote`.`isPost`= 0 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNotePost.php') {
                $sql .= " AND	`purchaseinvoicecreditnote`.`IsActive`= 1   AND   `purchaseinvoicecreditnote`.`isBalance` =   1 AND `purchaseinvoicecreditnote`.`isPost`=0 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteHistory.php') {
                $sql .= " AND	`purchaseinvoicecreditnote`.`isActive`= 1  AND `purchaseinvoicecreditnote`.`isBalance`=1 AND `purchaseinvoicecreditnote`.`isPost`=1 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteCancel.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteVoid.php') {
                $sql .= " AND	`purchaseinvoicecreditnote`.`isDelete` = 1  ";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteId],
                    [company].[companyDescription],
                    [purchaseInvoiceCreditNote].[companyId],
                    [businessPartner].[businessPartnerCompany],
                    [purchaseInvoiceCreditNote].[businessPartnerId],
                    [purchaseInvoice].[purchaseInvoiceDescription],
                    [purchaseInvoiceCreditNote].[purchaseInvoiceId],
					 [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteCode],
                    [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteTitle],
                    [purchaseInvoiceCreditNote].[documentNumber],
                    [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteAmount],
                    [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteAmountText],
                    [purchaseInvoiceCreditNote].[referenceNumber],
                    [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteDate],
                    [purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteDescription],
                    [purchaseInvoiceCreditNote].[isDefault],
                    [purchaseInvoiceCreditNote].[isNew],
                    [purchaseInvoiceCreditNote].[isDraft],
                    [purchaseInvoiceCreditNote].[isUpdate],
                    [purchaseInvoiceCreditNote].[isDelete],
                    [purchaseInvoiceCreditNote].[isActive],
                    [purchaseInvoiceCreditNote].[isApproved],
                    [purchaseInvoiceCreditNote].[isReview],
                    [purchaseInvoiceCreditNote].[isPost],
                    [purchaseInvoiceCreditNote].[executeBy],
                    [purchaseInvoiceCreditNote].[executeTime],
                    [staff].[staffName]
		  FROM 	[purchaseInvoiceCreditNote]
		  JOIN	[staff]
		  ON	[purchaseInvoiceCreditNote].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [purchaseInvoiceCreditNote].[companyId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [purchaseInvoiceCreditNote].[businessPartnerId]
	JOIN	[purchaseInvoice]
	ON		[purchaseInvoice].[purchaseInvoiceId] = [purchaseInvoiceCreditNote].[purchaseInvoiceId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseInvoiceCreditNoteId(0, 'single')) {
                $sql .= " AND [purchaseInvoiceCreditNote].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND [purchaseInvoiceCreditNote].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getPurchaseInvoiceId()) {
                $sql .= " AND [purchaseInvoiceCreditNote].[purchaseInvoiceId]='" . $this->model->getPurchaseInvoiceId() . "'";
            }
			if ($this->model->getFrom() == 'purchaseInvoiceCreditNote.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteMaintenance.php') {
                $sql .= " AND	[purchaseInvoiceCreditNote].[isActive]= 1 AND	[purchaseInvoiceCreditNote].[isPost]= 0 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNotePost.php') {
                $sql .= " AND	[purchaseInvoiceCreditNote].[IsActive]= 1   AND   [purchaseInvoiceCreditNote].[isBalance] =   1 AND [purchaseInvoiceCreditNote].[isPost]=0 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteHistory.php') {
                $sql .= " AND	[purchaseInvoiceCreditNote].[isActive]= 1  AND [purchaseInvoiceCreditNote].[isBalance]=1 AND [purchaseInvoiceCreditNote].[isPost]=1 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteCancel.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteCancel.php') {
                $sql .= " AND	[purchaseInvoiceCreditNote].[isDelete] = 1  ";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTEID AS \"purchaseInvoiceCreditNoteId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    PURCHASEINVOICECREDITNOTE.COMPANYID AS \"companyId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    PURCHASEINVOICECREDITNOTE.BUSINESSPARTNERID AS \"businessPartnerId\",
                    PURCHASEINVOICE.PURCHASEINVOICEDESCRIPTION AS  \"purchaseInvoiceDescription\",
                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTETITLE AS \"purchaseInvoiceCreditNoteTitle\",
                    PURCHASEINVOICECREDITNOTE.DOCUMENTNUMBER AS \"documentNumber\",
                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTEAMOUNT AS \"purchaseInvoiceCreditNoteAmount\",
                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTEAMOUNTTEXT AS \"purchaseInvoiceCreditNoteAmountText\",
                    PURCHASEINVOICECREDITNOTE.REFERENCENUMBER AS \"referenceNumber\",
                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTEDATE AS \"purchaseInvoiceCreditNoteDate\",
                    PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTEDESCRIPTION AS \"purchaseInvoiceCreditNoteDescription\",
                    PURCHASEINVOICECREDITNOTE.ISDEFAULT AS \"isDefault\",
                    PURCHASEINVOICECREDITNOTE.ISNEW AS \"isNew\",
                    PURCHASEINVOICECREDITNOTE.ISDRAFT AS \"isDraft\",
                    PURCHASEINVOICECREDITNOTE.ISUPDATE AS \"isUpdate\",
                    PURCHASEINVOICECREDITNOTE.ISDELETE AS \"isDelete\",
                    PURCHASEINVOICECREDITNOTE.ISACTIVE AS \"isActive\",
                    PURCHASEINVOICECREDITNOTE.ISAPPROVED AS \"isApproved\",
                    PURCHASEINVOICECREDITNOTE.ISREVIEW AS \"isReview\",
                    PURCHASEINVOICECREDITNOTE.ISPOST AS \"isPost\",
                    PURCHASEINVOICECREDITNOTE.EXECUTEBY AS \"executeBy\",
                    PURCHASEINVOICECREDITNOTE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	PURCHASEINVOICECREDITNOTE
		  JOIN	STAFF
		  ON	PURCHASEINVOICECREDITNOTE.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = PURCHASEINVOICECREDITNOTE.COMPANYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = PURCHASEINVOICECREDITNOTE.BUSINESSPARTNERID
	JOIN	PURCHASEINVOICE
	ON		PURCHASEINVOICE.PURCHASEINVOICEID = PURCHASEINVOICECREDITNOTE.PURCHASEINVOICEID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseInvoiceCreditNoteId(0, 'single')) {
                $sql .= " AND PURCHASEINVOICECREDITNOTE. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND PURCHASEINVOICECREDITNOTE.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getPurchaseInvoiceId()) {
                $sql .= " AND PURCHASEINVOICECREDITNOTE.PURCHASEINVOICEID='" . $this->model->getPurchaseInvoiceId() . "'";
            }
			if ($this->model->getFrom() == 'purchaseInvoiceCreditNote.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteMaintenance.php') {
                $sql .= " AND	PURCHASEINVOICECREDITNOTE.ISACTIVE= 1 AND	PURCHASEINVOICECREDITNOTE.ISPOST= 0 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNotePost.php') {
                $sql .= " AND	PURCHASEINVOICECREDITNOTE.ISACTIVE= 1   AND   PURCHASEINVOICECREDITNOTE.ISBALANCE =   1 AND PURCHASEINVOICECREDITNOTE.ISPOST=0 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteHistory.php') {
                $sql .= " AND	PURCHASEINVOICECREDITNOTE.ISACTIVE= 1  AND PURCHASEINVOICECREDITNOTE.ISBALANCE=1 AND PURCHASEINVOICECREDITNOTE.ISPOST=1 ";
            }
            if ($this->model->getFrom() == 'purchaseInvoiceCreditNoteCancel.php' || $this->model->getFrom() == 'purchaseInvoiceCreditNoteCancel.php') {
                $sql .= " AND	PURCHASEINVOICECREDITNOTE.ISDELETE = 1  ";
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
                $sql.=" AND `purchaseinvoicecreditnote`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [purchaseInvoiceCreditNote].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(PURCHASEINVOICECREDITNOTE." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('purchaseinvoicecreditnote', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('purchaseInvoiceCreditNote', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('PURCHASEINVOICECREDITNOTE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search.
         * E.g  $filterArray=array('`leaf`.`leafId`');
         * @variables $filterArray;
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`purchaseinvoicecreditnote`.`purchaseInvoiceCreditNoteId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[purchaseInvoiceCreditNote].[purchaseInvoiceCreditNoteId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("PURCHASEINVOICECREDITNOTE.PURCHASEINVOICECREDITNOTEID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'purchaseinvoicecreditnote', 'company', 'businesspartner', 'purchaseinvoice');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'purchaseinvoicecreditnote', 'company', 'businesspartner', 'purchaseinvoice');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'PURCHASEINVOICECREDITNOTE', 'COMPANY', 'BUSINESSPARTNER', 'PURCHASEINVOICE');
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
        if (!($this->model->getPurchaseInvoiceCreditNoteId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 22;
            if ($this->model->getPurchaseInvoiceCreditNoteId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getPurchaseInvoiceCreditNoteId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getPurchaseInvoiceCreditNoteId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getPurchaseInvoiceCreditNoteId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getPurchaseInvoiceCreditNoteId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getPurchaseInvoiceCreditNoteId(0, 'single')),
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
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getPurchaseInvoiceCreditNoteId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getPurchaseInvoiceCreditNoteId(0, 'single')),
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
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getPurchaseInvoiceId()) {
            $this->model->setPurchaseInvoiceId($this->service->getPurchaseInvoiceDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`purchaseinvoicecreditnote`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[purchaseInvoiceCreditNote]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	PURCHASEINVOICECREDITNOTE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `purchaseinvoicecreditnote` SET
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `purchaseInvoiceId` = '" . $this->model->getPurchaseInvoiceId() . "',
                       `purchaseInvoiceCreditNoteTitle` = '" . $this->model->getPurchaseInvoiceCreditNoteTitle() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `purchaseInvoiceCreditNoteAmount` = '" . $this->model->getPurchaseInvoiceCreditNoteAmount() . "',
                       `purchaseInvoiceCreditNoteAmountText` = '" . $this->model->getPurchaseInvoiceCreditNoteAmountText() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `purchaseInvoiceCreditNoteDate` = '" . $this->model->getPurchaseInvoiceCreditNoteDate() . "',
                       `purchaseInvoiceCreditNoteDescription` = '" . $this->model->getPurchaseInvoiceCreditNoteDescription() . "',
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
               WHERE    `purchaseInvoiceCreditNoteId`='" . $this->model->getPurchaseInvoiceCreditNoteId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [purchaseInvoiceCreditNote] SET
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [purchaseInvoiceId] = '" . $this->model->getPurchaseInvoiceId() . "',
                       [purchaseInvoiceCreditNoteTitle] = '" . $this->model->getPurchaseInvoiceCreditNoteTitle() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [purchaseInvoiceCreditNoteAmount] = '" . $this->model->getPurchaseInvoiceCreditNoteAmount() . "',
                       [purchaseInvoiceCreditNoteAmountText] = '" . $this->model->getPurchaseInvoiceCreditNoteAmountText() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [purchaseInvoiceCreditNoteDate] = '" . $this->model->getPurchaseInvoiceCreditNoteDate() . "',
                       [purchaseInvoiceCreditNoteDescription] = '" . $this->model->getPurchaseInvoiceCreditNoteDescription() . "',
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
                WHERE   [purchaseInvoiceCreditNoteId]='" . $this->model->getPurchaseInvoiceCreditNoteId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE PURCHASEINVOICECREDITNOTE SET
                        BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       PURCHASEINVOICEID = '" . $this->model->getPurchaseInvoiceId() . "',
                       PURCHASEINVOICECREDITNOTETITLE = '" . $this->model->getPurchaseInvoiceCreditNoteTitle() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       PURCHASEINVOICECREDITNOTEAMOUNT = '" . $this->model->getPurchaseInvoiceCreditNoteAmount() . "',
                       PURCHASEINVOICECREDITNOTEAMOUNTTEXT = '" . $this->model->getPurchaseInvoiceCreditNoteAmountText() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       PURCHASEINVOICECREDITNOTEDATE = '" . $this->model->getPurchaseInvoiceCreditNoteDate() . "',
                       PURCHASEINVOICECREDITNOTEDESCRIPTION = '" . $this->model->getPurchaseInvoiceCreditNoteDescription() . "',
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
                WHERE  PURCHASEINVOICECREDITNOTEID='" . $this->model->getPurchaseInvoiceCreditNoteId('0', 'single') . "'";
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
           FROM 	`purchaseinvoicecreditnote`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[purchaseInvoiceCreditNote]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	PURCHASEINVOICECREDITNOTE
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "' ";
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
               UPDATE  `purchaseinvoicecreditnote`
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
               WHERE   `purchaseInvoiceCreditNoteId`   =  '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [purchaseInvoiceCreditNote]
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
               WHERE   [purchaseInvoiceCreditNoteId]	=  '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  PURCHASEINVOICECREDITNOTE
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
               WHERE   PURCHASEINVOICECREDITNOTEID	=  '" . $this->model->getPurchaseInvoiceCreditNoteId(0, 'single') . "'";
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
           FROM    `purchaseinvoicecreditnote`
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  [documentNumber]
           FROM    [purchaseInvoiceCreditNote]
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  PURCHASEINVOICECREDITNOTECODE as \"documentNumber\"
               FROM    PURCHASEINVOICECREDITNOTE
               WHERE   PURCHASEINVOICECREDITNOTECODE	= 	'" . $this->model->getDocumentNumber() . "'
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
                    array("success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateMessageLabel'],
                        "documentNumber" => $row ['documentNumber'],
                        "time" => $time));
            exit();
        } else {
            $end = microtime(true);
            $time = $end - $start;
            echo json_encode(
                    array("success" => true,
                        "total" => $total,
                        "message" => $this->t['duplicateNotMessageLabel'],
                        "time" => $time));
            exit();
        }
    }

    /**
     * First Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
     */
    function firstRecord($value) {
        return $this->recordSet->firstRecord($value);
    }

    /**
     * Next Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue Current  Primary Key Value
     * @return int
     * @throws \Exception
     */
    function nextRecord($value, $primaryKeyValue) {
        return $this->recordSet->nextRecord($value, $primaryKeyValue);
    }

    /**
     * Previous Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @param int $primaryKeyValue
     * @return int
     * @throws \Exception
     */
    function previousRecord($value, $primaryKeyValue) {
        return $this->recordSet->previousRecord($value, $primaryKeyValue);
    }

    /**
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
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
     * Return  Business Partner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner();
    }

    /**
     * Return  Purchase Invoice
     * @return null|string
     */
    public function getPurchaseInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPurchaseInvoice();
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
         FROM    `purchaseinvoicecreditnote`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [purchaseInvoiceCreditNote]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      PURCHASEINVOICECREDITNOTE
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
        } return $total;
    }

    /**
     * Posting
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
                    $this->model->getPurchaseInvoiceCreditNoteId(0, 'single'), $this->getLeafId(), $this->model->getFrom()
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
                ->setSubject('purchaseInvoiceCreditNote')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/accountPayable');
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
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('M2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:M2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['purchaseInvoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['purchaseInvoiceCreditNoteTitleLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['purchaseInvoiceCreditNoteAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['purchaseInvoiceCreditNoteAmountTextLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['purchaseInvoiceCreditNoteDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['purchaseInvoiceCreditNoteDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['purchaseInvoiceDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['purchaseInvoiceCreditNoteTitle']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['purchaseInvoiceCreditNoteAmount']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['purchaseInvoiceCreditNoteAmountText']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['purchaseInvoiceCreditNoteDate']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['purchaseInvoiceCreditNoteDescription']));
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'M' . $loopRow;
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
                $filename = "purchaseInvoiceCreditNote" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
                $filename = "purchaseInvoiceCreditNote" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
                $filename = "purchaseInvoiceCreditNote" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
                $filename = "purchaseInvoiceCreditNote" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot() . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
        $purchaseInvoiceCreditNoteObject = new PurchaseInvoiceCreditNoteClass ();
        if ($_POST['securityToken'] != $purchaseInvoiceCreditNoteObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $purchaseInvoiceCreditNoteObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $purchaseInvoiceCreditNoteObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $purchaseInvoiceCreditNoteObject->setLimit($_POST ['limit']);
        }
        $purchaseInvoiceCreditNoteObject->setPageOutput($_POST['output']);
        $purchaseInvoiceCreditNoteObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $purchaseInvoiceCreditNoteObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $purchaseInvoiceCreditNoteObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $purchaseInvoiceCreditNoteObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $purchaseInvoiceCreditNoteObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$purchaseInvoiceCreditNoteObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$purchaseInvoiceCreditNoteObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $purchaseInvoiceCreditNoteObject = new PurchaseInvoiceCreditNoteClass ();
    if ($_GET['securityToken'] != $purchaseInvoiceCreditNoteObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $purchaseInvoiceCreditNoteObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $purchaseInvoiceCreditNoteObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $purchaseInvoiceCreditNoteObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $purchaseInvoiceCreditNoteObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $purchaseInvoiceCreditNoteObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $purchaseInvoiceCreditNoteObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $purchaseInvoiceCreditNoteObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $purchaseInvoiceCreditNoteObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $purchaseInvoiceCreditNoteObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $purchaseInvoiceCreditNoteObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $purchaseInvoiceCreditNoteObject->setServiceOutput('option');
        if (($_GET['filter'] == 'businessPartner')) {
            $purchaseInvoiceCreditNoteObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'purchaseInvoice')) {
            $purchaseInvoiceCreditNoteObject->getPurchaseInvoice();
        }
    }
}
?>
