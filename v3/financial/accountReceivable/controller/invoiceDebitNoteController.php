<?php

namespace Core\Financial\AccountReceivable\InvoiceDebitNote\Controller;

use Core\ConfigClass;
use Core\Financial\AccountReceivable\InvoiceDebitNote\Model\InvoiceDebitNoteModel;
use Core\Financial\AccountReceivable\InvoiceDebitNote\Service\InvoiceDebitNoteService;
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
require_once ($newFakeDocumentRoot . "v3/financial/accountReceivable/model/invoiceDebitNoteModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountReceivable/service/invoiceDebitNoteService.php");

/**
 * Class InvoiceDebitNote
 * this is invoiceDebitNote controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountReceivable\InvoiceDebitNote\Controller 
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class InvoiceDebitNoteClass extends ConfigClass {

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
     * @var \Core\Financial\AccountReceivable\InvoiceDebitNote\Model\InvoiceDebitNoteModel 
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request 
     * @var \Core\Financial\AccountReceivable\InvoiceDebitNote\Service\InvoiceDebitNoteService 
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
        $this->setViewPath("./v3/financial/accountReceivable/view/invoiceDebitNote.php");
        $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceDebitNoteController.php");
        $this->setServicePath("./v3/financial/accountReceivable/service/invoiceDebitNoteService.php");
    }

    /**
     * Class Loader 
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new InvoiceDebitNoteModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/accountReceivable/view/" . $this->model->getFrom());
        if
        ($this->getVendor() == self::MYSQL) {
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

        $this->service = new InvoiceDebitNoteService();
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

        if ($this->model->getFrom() == 'invoiceDebitNote.php' || $this->model->getFrom() == 'invoiceDebitNoteMaintenance.php'
        ) {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND `invoicedebitnote`.`isActive` = 1 
					AND `invoicedebitnote`.`isPost` = 0 
					AND	`invoicedebitnote`.`invoiceDebitNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND [invoiceDebitNote].[isActive] = 1 
					AND [invoiceDebitNote].[isPost] = 0 
					AND [invoiceDebitNote].[invoiceDebitNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND INVOICEDEBITNOTE.ISACTIVE = 1 
					AND INVOICEDEBITNOTE.ISPOST = 0 
					AND	INVOICEDEBITNOTE.INVOICEDEBITNOTECODE !='UNBL'
				");
            }
        }

        if ($this->model->getFrom() == 'invoiceDebitNotePost.php' || $this->model->getFrom() == 'invoiceDebitNotePosting.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND `invoicedebitnote`.`isActive` = 1  
					AND `invoicedebitnote`.`isBalance`   =    1 
					AND `invoicedebitnote`.`isPost` = 0 
					AND	`invoicedebitnote`.`invoiceDebitNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("
					AND [invoiceDebitNote].[isActive] = 1  
					AND [isBalance]   =    1 AND [invoiceDebitNote].[isPost] = 0 
					AND [invoiceDebitNote].[invoiceDebitNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND INVOICEDEBITNOTE.ISACTIVE = 1 
					AND ISBALANCE =    1 
					AND INVOICEDEBITNOTE.ISPOST = 0 
					AND	INVOICEDEBITNOTE.INVOICEDEBITNOTECODE !='UNBL'
				");
            }
        }

        if ($this->model->getFrom() == 'invoiceDebitNoteHistory.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND `invoicedebitnote`.`isActive` = 1 
					AND `invoicedebitnote`.`isBalance`   =    1 
					AND `invoicedebitnote`.`isPost` = 1 
					AND	`invoicedebitnote`.`invoiceDebitNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [invoiceDebitNote].[isActive] = 1 
					AND [invoiceDebitNote].[isBalance]   =    1  
					AND [invoiceDebitNote].[isPost] = 1 
					AND [invoiceDebitNote].[invoiceDebitNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND INVOICEDEBITNOTE.ISACTIVE = 1 
					AND INVOICEDEBITNOTE.ISBALANCE =    1  
					AND INVOICEDEBITNOTE.ISPOST = 1 
					AND	INVOICEDEBITNOTE.INVOICEDEBITNOTECODE !='UNBL'
				");
            }
        }
        if ($this->model->getFrom() == 'invoiceDebitNoteCancel.php' || $this->model->getFrom() == 'invoiceDebitNoteVoid.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND `invoicedebitnote`.`isActive` = 0  
					AND	`invoicedebitnote`.`invoiceDebitNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [invoiceDebitNote].[isActive] = 0 
					AND [invoiceDebitNote].[invoiceDebitNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND INVOICEDEBITNOTE.ISACTIVE = 0 
					AND	INVOICEDEBITNOTE.INVOICEDEBITNOTECODE !='UNBL'
				");
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
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoicedebitnote` 
            (
                 `companyId`,
                 `businessPartnerId`,
                 `invoiceId`,
                 `invoiceDebitNoteTitle`,
                 `documentNumber`,
                 `invoiceDebitNoteAmount`,
                 `referenceNumber`,
                 `invoiceDebitNoteDate`,
                 `invoiceDebitNoteDescription`,
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
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getInvoiceDebitNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getInvoiceDebitNoteAmount() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceDebitNoteDate() . "',
                 '" . $this->model->getInvoiceDebitNoteDescription() . "',
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
            INSERT INTO [invoiceDebitNote] 
            (
                 [invoiceDebitNoteId],
                 [companyId],
                 [businessPartnerId],
                 [invoiceId],
                 [invoiceDebitNoteTitle],
                 [documentNumber],
                 [invoiceDebitNoteAmount],
                 [referenceNumber],
                 [invoiceDebitNoteDate],
                 [invoiceDebitNoteDescription],
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
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getInvoiceDebitNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getInvoiceDebitNoteAmount() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceDebitNoteDate() . "',
                 '" . $this->model->getInvoiceDebitNoteDescription() . "',
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
            INSERT INTO INVOICEDEBITNOTE 
            (
                 COMPANYID,
                 BUSINESSPARTNERID,
                 INVOICEID,
                 INVOICEDEBITNOTETITLE,
                 DOCUMENTNUMBER,
                 INVOICEDEBITNOTEAMOUNT,
                 REFERENCENUMBER,
                 INVOICEDEBITNOTEDATE,
                 INVOICEDEBITNOTEDESCRIPTION,
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
                 '" . $this->model->getInvoiceId() . "',
                 '" . $this->model->getInvoiceDebitNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getInvoiceDebitNoteAmount() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceDebitNoteDate() . "',
                 '" . $this->model->getInvoiceDebitNoteDescription() . "',
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
        $invoiceDebitNoteId = $this->q->lastInsertId("invoiceDebitNote");
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "invoiceDebitNoteId" => $invoiceDebitNoteId,
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
                    $this->setAuditFilter(" `invoicedebitnote`.`isActive` = 1  AND `invoicedebitnote`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [invoiceDebitNote].[isActive] = 1 AND [invoiceDebitNote].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" INVOICEDEBITNOTE.ISACTIVE = 1  AND INVOICEDEBITNOTE.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `invoicedebitnote`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [invoiceDebitNote].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" INVOICEDEBITNOTE.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `invoicedebitnote`.`invoiceDebitNoteId`,
                    `company`.`companyDescription`,
                    `invoicedebitnote`.`companyId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `invoicedebitnote`.`businessPartnerId`,
                    `invoice`.`invoiceDescription`,
                    `invoicedebitnote`.`invoiceId`,
					`invoicedebitnote`.`invoiceDebitNoteCode`,
                    `invoicedebitnote`.`invoiceDebitNoteTitle`,
                    `invoicedebitnote`.`documentNumber`,
                    `invoicedebitnote`.`invoiceDebitNoteAmount`,
                    `invoicedebitnote`.`referenceNumber`,
                    `invoicedebitnote`.`invoiceDebitNoteDate`,
                    `invoicedebitnote`.`invoiceDebitNoteDescription`,
                    `invoicedebitnote`.`isDefault`,
                    `invoicedebitnote`.`isNew`,
                    `invoicedebitnote`.`isDraft`,
                    `invoicedebitnote`.`isUpdate`,
                    `invoicedebitnote`.`isDelete`,
                    `invoicedebitnote`.`isActive`,
                    `invoicedebitnote`.`isApproved`,
                    `invoicedebitnote`.`isReview`,
                    `invoicedebitnote`.`isPost`,
                    `invoicedebitnote`.`executeBy`,
                    `invoicedebitnote`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicedebitnote`
		  JOIN      `staff`
		  ON        `invoicedebitnote`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoicedebitnote`.`companyId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `invoicedebitnote`.`businessPartnerId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `invoicedebitnote`.`invoiceId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceDebitNoteId(0, 'single')) {
                $sql .= " AND `invoicedebitnote`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceDebitNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `invoicedebitnote`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND `invoicedebitnote`.`invoiceId`='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getFrom() == 'invoiceDebitNote.php' || $this->model->getFrom() == 'invoiceDebitNoteMaintenance.php') {
                $sql .= " AND	`invoicedebitnote`.`isActive`= 1 AND	`invoicedebitnote`.`isPost`= 0 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNotePost.php' || $this->model->getFrom() == 'invoiceDebitNotePosting.php') {
                $sql .= " AND	`invoicedebitnote`.`IsActive`= 1   AND   `invoicedebitnote`.`isBalance` =   1 AND `invoicedebitnote`.`isPost`=0 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNoteHistory.php') {
                $sql .= " AND	`invoicedebitnote`.`isActive`= 1  AND `invoicedebitnote`.`isBalance`=1 AND `invoicedebitnote`.`isPost`=1 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNoteCancel.php' || $this->model->getFrom() == 'invoiceDebitNoteVoid.php') {
                $sql .= " AND	`invoicedebitnote`.`isDelete` = 1   ";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [invoiceDebitNote].[invoiceDebitNoteId],
                    [company].[companyDescription],
                    [invoiceDebitNote].[companyId],
                    [businessPartner].[businessPartnerCompany],
                    [invoiceDebitNote].[businessPartnerId],
                    [invoice].[invoiceDescription],
                    [invoiceDebitNote].[invoiceId],
					 [invoiceDebitNote].[invoiceDebitNoteCode],
                    [invoiceDebitNote].[invoiceDebitNoteTitle],
                    [invoiceDebitNote].[documentNumber],
                    [invoiceDebitNote].[invoiceDebitNoteAmount],
                    [invoiceDebitNote].[referenceNumber],
                    [invoiceDebitNote].[invoiceDebitNoteDate],
                    [invoiceDebitNote].[invoiceDebitNoteDescription],
                    [invoiceDebitNote].[isDefault],
                    [invoiceDebitNote].[isNew],
                    [invoiceDebitNote].[isDraft],
                    [invoiceDebitNote].[isUpdate],
                    [invoiceDebitNote].[isDelete],
                    [invoiceDebitNote].[isActive],
                    [invoiceDebitNote].[isApproved],
                    [invoiceDebitNote].[isReview],
                    [invoiceDebitNote].[isPost],
                    [invoiceDebitNote].[executeBy],
                    [invoiceDebitNote].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceDebitNote]
		  JOIN	[staff]
		  ON	[invoiceDebitNote].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [invoiceDebitNote].[companyId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [invoiceDebitNote].[businessPartnerId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] = [invoiceDebitNote].[invoiceId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceDebitNoteId(0, 'single')) {
                $sql .= " AND [invoiceDebitNote].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceDebitNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND [invoiceDebitNote].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND [invoiceDebitNote].[invoiceId]='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getFrom() == 'invoiceDebitNote.php' || $this->model->getFrom() == 'invoiceDebitNoteMaintenance.php') {
                $sql .= " AND	[invoiceDebitNote].[isActive]= 1 AND	[invoiceDebitNote].[isPost]= 0 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNotePost.php' || $this->model->getFrom() == 'invoiceDebitNotePosting.php') {
                $sql .= " AND	[invoiceDebitNote].[IsActive]= 1   AND   [invoiceDebitNote].[isBalance] =   1 AND [invoiceDebitNote].[isPost]=0 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNoteHistory.php') {
                $sql .= " AND	[invoiceDebitNote].[isActive]= 1  AND [invoiceDebitNote].[isBalance]=1 AND [invoiceDebitNote].[isPost]=1 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNoteCancel.php' || $this->model->getFrom() == 'invoiceDebitNoteVoid.php' ) {
                $sql .= " AND	[invoiceDebitNote].[isDelete] = 1   ";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    INVOICEDEBITNOTE.INVOICEDEBITNOTEID AS \"invoiceDebitNoteId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICEDEBITNOTE.COMPANYID AS \"companyId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    INVOICEDEBITNOTE.BUSINESSPARTNERID AS \"businessPartnerId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICEDEBITNOTE.INVOICEID AS \"invoiceId\",
					INVOICEDEBITNOTE.INVOICEDEBITNOTECODE AS \"invoiceDebitNoteCode\",
                    INVOICEDEBITNOTE.INVOICEDEBITNOTETITLE AS \"invoiceDebitNoteTitle\",
                    INVOICEDEBITNOTE.DOCUMENTNUMBER AS \"documentNumber\",
                    INVOICEDEBITNOTE.INVOICEDEBITNOTEAMOUNT AS \"invoiceDebitNoteAmount\",
                    INVOICEDEBITNOTE.REFERENCENUMBER AS \"referenceNumber\",
                    INVOICEDEBITNOTE.INVOICEDEBITNOTEDATE AS \"invoiceDebitNoteDate\",
                    INVOICEDEBITNOTE.INVOICEDEBITNOTEDESCRIPTION AS \"invoiceDebitNoteDescription\",
                    INVOICEDEBITNOTE.ISDEFAULT AS \"isDefault\",
                    INVOICEDEBITNOTE.ISNEW AS \"isNew\",
                    INVOICEDEBITNOTE.ISDRAFT AS \"isDraft\",
                    INVOICEDEBITNOTE.ISUPDATE AS \"isUpdate\",
                    INVOICEDEBITNOTE.ISDELETE AS \"isDelete\",
                    INVOICEDEBITNOTE.ISACTIVE AS \"isActive\",
                    INVOICEDEBITNOTE.ISAPPROVED AS \"isApproved\",
                    INVOICEDEBITNOTE.ISREVIEW AS \"isReview\",
                    INVOICEDEBITNOTE.ISPOST AS \"isPost\",
                    INVOICEDEBITNOTE.EXECUTEBY AS \"executeBy\",
                    INVOICEDEBITNOTE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICEDEBITNOTE 
		  JOIN	STAFF 
		  ON	INVOICEDEBITNOTE.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICEDEBITNOTE.COMPANYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = INVOICEDEBITNOTE.BUSINESSPARTNERID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = INVOICEDEBITNOTE.INVOICEID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceDebitNoteId(0, 'single')) {
                $sql .= " AND INVOICEDEBITNOTE. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceDebitNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND INVOICEDEBITNOTE.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND INVOICEDEBITNOTE.INVOICEID='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getFrom() == 'invoiceDebitNote.php' || $this->model->getFrom() == 'invoiceDebitNoteMaintenance.php') {
                $sql .= " AND	INVOICEDEBITNOTE.ISACTIVE= 1 AND INVOICEDEBITNOTE.ISPOST= 0 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNotePost.php' || $this->model->getFrom() == 'invoiceDebitNotePosting.php') {
                $sql .= " AND	INVOICEDEBITNOTE.ISACTIVE= 1   AND   INVOICEDEBITNOTE.ISBALANCE =   1 AND [INVOICEDEBITNOTE.ISPOST=0 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNoteHistory.php') {
                $sql .= " AND	INVOICEDEBITNOTE.ISACTIVE= 1  AND INVOICEDEBITNOTE.ISBALANCE=1 AND INVOICEDEBITNOTE.ISPOST=1 ";
            }
            if ($this->model->getFrom() == 'invoiceDebitNoteCancel.php' || $this->model->getFrom() == 'invoiceDebitNoteVoid.php') {
                $sql .= " AND	INVOICEDEBITNOTE.ISDELETE = 1   ";
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
                $sql.=" AND `invoicedebitnote`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [invoiceDebitNote].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(INVOICEDEBITNOTE." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date 
         * Example Day,Week,Month,Year 
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('invoicedebitnote', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('invoiceDebitNote', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('INVOICEDEBITNOTE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
         * E.g  $filterArray=array('`leaf`.`leafId`'); 
         * @variables $filterArray; 
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`invoicedebitnote`.`invoiceDebitNoteId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[invoiceDebitNote].[invoiceDebitNoteId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("INVOICEDEBITNOTE.INVOICEDEBITNOTEID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'invoicedebitnote', 'company', 'businesspartner', 'invoice');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'invoicedebitnote', 'company', 'businesspartner', 'invoice');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'INVOICEDEBITNOTE', 'COMPANY', 'BUSINESSPARTNER', 'INVOICE');
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
                 */
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
        if (!($this->model->getInvoiceDebitNoteId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 21;
            if ($this->model->getInvoiceDebitNoteId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceDebitNoteId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceDebitNoteId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getInvoiceDebitNoteId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceDebitNoteId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceDebitNoteId(0, 'single')),
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
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceDebitNoteId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceDebitNoteId(0, 'single')),
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
        if (!$this->model->getInvoiceId()) {
            $this->model->setInvoiceId($this->service->getInvoiceDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = " 
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`invoicedebitnote`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[invoiceDebitNote] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICEDEBITNOTE 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `invoicedebitnote` SET 
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `invoiceId` = '" . $this->model->getInvoiceId() . "',
                       `invoiceDebitNoteTitle` = '" . $this->model->getInvoiceDebitNoteTitle() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `invoiceDebitNoteAmount` = '" . $this->model->getInvoiceDebitNoteAmount() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `invoiceDebitNoteDate` = '" . $this->model->getInvoiceDebitNoteDate() . "',
                       `invoiceDebitNoteDescription` = '" . $this->model->getInvoiceDebitNoteDescription() . "',
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
               WHERE    `invoiceDebitNoteId`='" . $this->model->getInvoiceDebitNoteId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [invoiceDebitNote] SET 
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [invoiceId] = '" . $this->model->getInvoiceId() . "',
                       [invoiceDebitNoteTitle] = '" . $this->model->getInvoiceDebitNoteTitle() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [invoiceDebitNoteAmount] = '" . $this->model->getInvoiceDebitNoteAmount() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [invoiceDebitNoteDate] = '" . $this->model->getInvoiceDebitNoteDate() . "',
                       [invoiceDebitNoteDescription] = '" . $this->model->getInvoiceDebitNoteDescription() . "',
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
                WHERE   [invoiceDebitNoteId]='" . $this->model->getInvoiceDebitNoteId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE INVOICEDEBITNOTE SET
                        BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       INVOICEID = '" . $this->model->getInvoiceId() . "',
                       INVOICEDEBITNOTETITLE = '" . $this->model->getInvoiceDebitNoteTitle() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       INVOICEDEBITNOTEAMOUNT = '" . $this->model->getInvoiceDebitNoteAmount() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       INVOICEDEBITNOTEDATE = '" . $this->model->getInvoiceDebitNoteDate() . "',
                       INVOICEDEBITNOTEDESCRIPTION = '" . $this->model->getInvoiceDebitNoteDescription() . "',
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
                WHERE  INVOICEDEBITNOTEID='" . $this->model->getInvoiceDebitNoteId('0', 'single') . "'";
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
           FROM 	`invoicedebitnote` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[invoiceDebitNote] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICEDEBITNOTE 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "' ";
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
               UPDATE  `invoicedebitnote` 
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
               WHERE   `invoiceDebitNoteId`   =  '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [invoiceDebitNote] 
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
               WHERE   [invoiceDebitNoteId]	=  '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  INVOICEDEBITNOTE 
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
               WHERE   INVOICEDEBITNOTEID	=  '" . $this->model->getInvoiceDebitNoteId(0, 'single') . "'";
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
           FROM    `invoicedebitnote` 
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT  [documentNumber]
           FROM    [invoiceDebitNote] 
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1 
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
               SELECT  DOCUMENTNUMBER as \"documentNumber\"
               FROM    INVOICEDEBITNOTE 
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
     * Return  BusinessPartner 
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        if ($this->model->getBusinessPartnerCategoryId()) {
            return $this->service->getBusinessPartner($this->model->getBusinessPartnerCategoryId());
        } else {
            return $this->service->getBusinessPartner();
        }
    }

    /**
     * Return Purchase Invoice 
     * @return null|string
     */
    public function getInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        if ($this->model->getBusinessPartnerId()) {
            return $this->service->getInvoice($this->model->getBusinessPartnerId());
        } else {
            return $this->service->getInvoice();
        }
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
         FROM    `invoicedebitnote`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [invoiceDebitNote]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      INVOICEDEBITNOTE
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
                    $this->model->getInvoiceDebitNoteId(0, 'single'), $this->getLeafId(), $this->model->getFrom()
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
                ->setSubject('invoiceDebitNote')
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
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('L2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:L2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['invoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['invoiceDebitNoteTitleLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['invoiceDebitNoteAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['invoiceDebitNoteDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['invoiceDebitNoteDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['executeTimeLabel']);
        // 
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == TRUE) {
            //	echo print_r($row); 
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['invoiceDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['invoiceDebitNoteTitle']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['invoiceDebitNoteAmount']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['invoiceDebitNoteDate']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['invoiceDebitNoteDescription']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'L' . $loopRow;
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
                $filename = "invoiceDebitNote" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceDebitNote" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceDebitNote" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceDebitNote" . rand(0, 10000000) . $extension;
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
        $invoiceDebitNoteObject = new InvoiceDebitNoteClass ();
        if ($_POST['securityToken'] != $invoiceDebitNoteObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $invoiceDebitNoteObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $invoiceDebitNoteObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $invoiceDebitNoteObject->setLimit($_POST ['limit']);
        }
        $invoiceDebitNoteObject->setPageOutput($_POST['output']);
        $invoiceDebitNoteObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $invoiceDebitNoteObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $invoiceDebitNoteObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $invoiceDebitNoteObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $invoiceDebitNoteObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$invoiceDebitNoteObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$invoiceDebitNoteObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $invoiceDebitNoteObject = new InvoiceDebitNoteClass ();
    if ($_GET['securityToken'] != $invoiceDebitNoteObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */

    if (isset($_GET ['leafId'])) {
        $invoiceDebitNoteObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $invoiceDebitNoteObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $invoiceDebitNoteObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $invoiceDebitNoteObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $invoiceDebitNoteObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $invoiceDebitNoteObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $invoiceDebitNoteObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $invoiceDebitNoteObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $invoiceDebitNoteObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $invoiceDebitNoteObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $invoiceDebitNoteObject->setServiceOutput('option');
        if (($_GET['filter'] == 'businessPartner')) {
            $invoiceDebitNoteObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'invoice')) {
            $invoiceDebitNoteObject->getInvoice();
        }
    }
}
?>
