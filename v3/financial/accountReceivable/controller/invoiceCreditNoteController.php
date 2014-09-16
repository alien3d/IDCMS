<?php

namespace Core\Financial\AccountReceivable\InvoiceCreditNote\Controller;

use Core\ConfigClass;
use Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel;
use Core\Financial\AccountReceivable\InvoiceCreditNote\Service\InvoiceCreditNoteService;
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
require_once ($newFakeDocumentRoot . "v3/financial/accountReceivable/model/invoiceCreditNoteModel.php");
require_once ($newFakeDocumentRoot . "v3/financial/accountReceivable/service/invoiceCreditNoteService.php");

/**
 * Class InvoiceCreditNote
 * this is invoiceCreditNote controller files. 
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package  Core\Financial\AccountReceivable\InvoiceCreditNote\Controller 
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class InvoiceCreditNoteClass extends ConfigClass {

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
     * @var \Core\Financial\AccountReceivable\InvoiceCreditNote\Model\InvoiceCreditNoteModel 
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request 
     * @var \Core\Financial\AccountReceivable\InvoiceCreditNote\Service\InvoiceCreditNoteService 
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
        $this->setViewPath("./v3/financial/accountReceivable/view/invoiceCreditNote.php");
        $this->setControllerPath("./v3/financial/accountReceivable/controller/invoiceCreditNoteController.php");
        $this->setServicePath("./v3/financial/accountReceivable/service/invoiceCreditNoteService.php");
    }

    /**
     * Class Loader 
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new InvoiceCreditNoteModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/accountReceivable/view/" . $this->model->getFrom());
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

        $this->service = new InvoiceCreditNoteService();
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

        if ($this->model->getFrom() == 'invoiceCreditNote.php' || $this->model->getFrom() == 'invoiceCreditNoteMaintenance.php'
        ) {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND `invoicecreditnote`.`isActive` = 1 
					AND `invoicecreditnote`.`isPost` = 0 
					AND	`invoicecreditnote`.`invoiceCreditNoteCode` != 'UNBL' 
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND [invoiceCreditNote].[isActive] = 1 
					AND [invoiceCreditNote].[isPost] = 0 
					AND	[invoiceCreditNote].[invoiceCreditNoteCode] != 'UNBL' 
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND INVOICECREDITNOTE.ISACTIVE = 1 
					AND INVOICECREDITNOTE.ISPOST = 0 
					AND	INVOICECREDITNOTE.INVOICECREDITNOTECODE != 'UNBL' 
				");
            }
        }

        if ($this->model->getFrom() == 'invoiceCreditNotePost.php' || $this->model->getFrom() == 'invoiceCreditNotePosting.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND `invoicecreditnote`.`isActive` = 1  AND `invoicecreditnote`.`isBalance`   =    1 
					AND `invoicecreditnote`.`isPost` = 0 
					AND	`invoicecreditnote`.`invoiceCreditNoteCode` != 'UNBL' 
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND [invoiceCreditNote].[isActive] = 1  
					AND [isBalance]   =    1 AND [invoicCreditNote].[isPost] = 0 
					AND	[invoiceCreditNote].[invoiceCreditNoteCode] != 'UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement(" 
					AND INVOICECREDITNOTE.ISACTIVE = 1 
					AND ISBALANCE =    1 AND INVOICECREDITNOTE.ISPOST = 0
					AND	INVOICECREDITNOTE.INVOICECREDITNOTECODE != 'UNBL' 
				");
            }
        }

        if ($this->model->getFrom() == 'invoiceCreditNoteHistory.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND `invoicecreditnote`.`isActive` = 1 
					AND `invoicecreditnote`.`isBalance`   =    1 
					AND `invoicecreditnote`.`isPost` = 1 
					AND	`invoicecreditnote`.`invoiceCreditNoteCode` != 'UNBL' 
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [invoiceCreditNote].[isActive] = 1 
					AND [invoiceCreditNote].[isBalance]   =    1  
					AND [invoiceCreditNote].[isPost] = 1 
					AND	[invoiceCreditNote].[invoiceCreditNoteCode] != 'UNBL' 
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND INVOICECREDITNOTE.ISACTIVE = 1 
					AND INVOICECREDITNOTE.ISBALANCE =    1  
					AND INVOICECREDITNOTE.ISPOST = 1 
					AND	INVOICECREDITNOTE.INVOICECREDITNOTECODE != 'UNBL' 
				");
            }
        }
		if ($this->model->getFrom() == 'invoiceCreditNoteCancel.php' || $this->model->getFrom() == 'invoiceCreditNoteVoid.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND `invoicecreditnote`.`isActive` = 0  
					AND	`invoicecreditnote`.`invoiceCreditNoteCode` !='UNBL'
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [invoiceCreditNote].[isActive] = 0 
					AND [invoiceCreditNote].[invoiceCreditNoteCode] !='UNBL'
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND INVOICECREDITNOTE.ISACTIVE = 0 
					AND	INVOICECREDITNOTE.INVOICECREDITNOTECODE !='UNBL'
				");
            }
        }
        if ($this->model->getFrom() == 'invoiceCreditNoteCancel.php' || $this->model->getFrom() == 'invoiceCreditNoteVoid.php') {
            if ($this->getVendor() == self::MYSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND	`invoicecreditnote`.`isActive` = 0 
					AND	`invoicecreditnote`.`invoiceCreditNoteCode` != 'UNBL' 
				");
            } else if ($this->getVendor() == self::MSSQL) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND [invoiceCreditNote].[isActive] = 0
					AND	[invoiceCreditNote].[invoiceCreditNoteCode] != 'UNBL' 					
				");
            } else if ($this->getVendor() == self::ORACLE) {
                $this->recordSet->setOverrideMysqlStatement("  
					AND INVOICECREDITNOTE.ISACTIVE = 0
					AND	INVOICECREDITNOTE.INVOICECREDITNOTECODE != 'UNBL' 				
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
            INSERT INTO `invoicecreditnote` 
            (
                 `companyId`,
                 `businessPartnerId`,
                 `invoiceId`,
                 `invoiceCreditNoteTitle`,
                 `documentNumber`,
                 `invoiceCreditNoteAmount`,
                 `referenceNumber`,
                 `invoiceCreditNoteDate`,
                 `invoiceCreditNoteDescription`,
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
                 '" . $this->model->getInvoiceCreditNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteAmount() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteDate() . "',
                 '" . $this->model->getInvoiceCreditNoteDescription() . "',
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
            INSERT INTO [invoiceCreditNote] 
            (
                 [invoiceCreditNoteId],
                 [companyId],
                 [businessPartnerId],
                 [invoiceId],
                 [invoiceCreditNoteTitle],
                 [documentNumber],
                 [invoiceCreditNoteAmount],
                 [referenceNumber],
                 [invoiceCreditNoteDate],
                 [invoiceCreditNoteDescription],
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
                 '" . $this->model->getInvoiceCreditNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteAmount() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteDate() . "',
                 '" . $this->model->getInvoiceCreditNoteDescription() . "',
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
            INSERT INTO INVOICECREDITNOTE 
            (
                 COMPANYID,
                 BUSINESSPARTNERID,
                 INVOICEID,
                 INVOICECREDITNOTETITLE,
                 DOCUMENTNUMBER,
                 INVOICECREDITNOTEAMOUNT,
                 REFERENCENUMBER,
                 INVOICECREDITNOTEDATE,
                 INVOICECREDITNOTEDESCRIPTION,
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
                 '" . $this->model->getInvoiceCreditNoteTitle() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteAmount() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getInvoiceCreditNoteDate() . "',
                 '" . $this->model->getInvoiceCreditNoteDescription() . "',
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
        $invoiceCreditNoteId = $this->q->lastInsertId("invoiceCreditNote");
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array("success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "invoiceCreditNoteId" => $invoiceCreditNoteId,
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
                    $this->setAuditFilter(" `invoicecreditnote`.`isActive` = 1  AND `invoicecreditnote`.`companyId`='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [invoiceCreditNote].[isActive] = 1 AND [invoiceCreditNote].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" INVOICECREDITNOTE.ISACTIVE = 1  AND INVOICECREDITNOTE.COMPANYID='" . $this->getCompanyId() . "'");
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter("   `invoicecreditnote`.`companyId`='" . $this->getCompanyId() . "'	");
                } else if ($this->getVendor() == self::MSSQL) {
                    $this->setAuditFilter(" [invoiceCreditNote].[companyId]='" . $this->getCompanyId() . "' ");
                } else if ($this->getVendor() == self::ORACLE) {
                    $this->setAuditFilter(" INVOICECREDITNOTE.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `invoicecreditnote`.`invoiceCreditNoteId`,
                    `company`.`companyDescription`,
                    `invoicecreditnote`.`companyId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `invoicecreditnote`.`businessPartnerId`,
                    `invoice`.`invoiceDescription`,
                    `invoicecreditnote`.`invoiceId`,
					 `invoicecreditnote`.`invoiceCreditNoteCode`,
                    `invoicecreditnote`.`invoiceCreditNoteTitle`,
                    `invoicecreditnote`.`documentNumber`,
                    `invoicecreditnote`.`invoiceCreditNoteAmount`,
                    `invoicecreditnote`.`referenceNumber`,
                    `invoicecreditnote`.`invoiceCreditNoteDate`,
                    `invoicecreditnote`.`invoiceCreditNoteDescription`,
                    `invoicecreditnote`.`isDefault`,
                    `invoicecreditnote`.`isNew`,
                    `invoicecreditnote`.`isDraft`,
                    `invoicecreditnote`.`isUpdate`,
                    `invoicecreditnote`.`isDelete`,
                    `invoicecreditnote`.`isActive`,
                    `invoicecreditnote`.`isApproved`,
                    `invoicecreditnote`.`isReview`,
                    `invoicecreditnote`.`isPost`,
                    `invoicecreditnote`.`executeBy`,
                    `invoicecreditnote`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicecreditnote`
		  JOIN      `staff`
		  ON        `invoicecreditnote`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoicecreditnote`.`companyId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `invoicecreditnote`.`businessPartnerId`
	JOIN	`invoice`
	ON		`invoice`.`invoiceId` = `invoicecreditnote`.`invoiceId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceCreditNoteId(0, 'single')) {
                $sql .= " AND `invoicecreditnote`.`" . $this->model->getPrimaryKeyName() . "`='" . $this->model->getInvoiceCreditNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `invoicecreditnote`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND `invoicecreditnote`.`invoiceId`='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getFrom() == 'invoiceCreditNote.php' || $this->model->getFrom() == 'invoiceCreditNoteMaintenance.php') {
                $sql .= " AND	`invoicecreditnote`.`isActive`= 1 AND	`invoicecreditnote`.`isPost`= 0 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNotePost.php' || $this->model->getFrom() == 'invoiceCreditNotePosting.php') {
                $sql .= " AND	`invoicecreditnote`.`IsActive`= 1   AND   `invoicecreditnote`.`isBalance` =   1 AND `invoicecreditnote`.`isPost`=0 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNoteHistory.php') {
                $sql .= " AND	`invoicecreditnote`.`isActive`= 1  AND `invoicecreditnote`.`isBalance`=1 AND `invoicecreditnote`.`isPost`=1 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNoteCancel.php' || $this->model->getFrom() == 'invoiceCreditNoteVoid.php' ) {
                $sql .= " AND	`invoicecreditnote`.`isDelete` = 1   ";
            }
        } else if ($this->getVendor() == self::MSSQL) {

            $sql = "
		  SELECT                    [invoiceCreditNote].[invoiceCreditNoteId],
                    [company].[companyDescription],
                    [invoiceCreditNote].[companyId],
                    [businessPartner].[businessPartnerCompany],
                    [invoiceCreditNote].[businessPartnerId],
                    [invoice].[invoiceDescription],
                    [invoiceCreditNote].[invoiceId],
					 [invoiceCreditNote].[invoiceCreditNoteCode],
                    [invoiceCreditNote].[invoiceCreditNoteTitle],
                    [invoiceCreditNote].[documentNumber],
                    [invoiceCreditNote].[invoiceCreditNoteAmount],
                    [invoiceCreditNote].[referenceNumber],
                    [invoiceCreditNote].[invoiceCreditNoteDate],
                    [invoiceCreditNote].[invoiceCreditNoteDescription],
                    [invoiceCreditNote].[isDefault],
                    [invoiceCreditNote].[isNew],
                    [invoiceCreditNote].[isDraft],
                    [invoiceCreditNote].[isUpdate],
                    [invoiceCreditNote].[isDelete],
                    [invoiceCreditNote].[isActive],
                    [invoiceCreditNote].[isApproved],
                    [invoiceCreditNote].[isReview],
                    [invoiceCreditNote].[isPost],
                    [invoiceCreditNote].[executeBy],
                    [invoiceCreditNote].[executeTime],
                    [staff].[staffName] 
		  FROM 	[invoiceCreditNote]
		  JOIN	[staff]
		  ON	[invoiceCreditNote].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] =[invoiceCreditNote].[companyId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] =[invoiceCreditNote].[businessPartnerId]
	JOIN	[invoice]
	ON		[invoice].[invoiceId] =[invoiceCreditNote].[invoiceId]
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceCreditNoteId(0, 'single')) {
                $sql .= " AND [invoiceCreditNote].[" . $this->model->getPrimaryKeyName() . "]		=	'" . $this->model->getInvoiceCreditNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND [invoiceCreditNote].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND [invoiceCreditNote].[invoiceId]='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getFrom() == 'invoiceCreditNote.php' || $this->model->getFrom() == 'invoiceCreditNoteMaintenance.php') {
                $sql .= " AND	[invoiceCreditNote].[isActive]= 1 AND	[invoiceCreditNote].[isPost]= 0 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNotePost.php' || $this->model->getFrom() == 'invoiceCreditNotePosting.php') {
                $sql .= " AND	[invoiceCreditNote].[IsActive]= 1   AND   [invoiceCreditNote].[isBalance] =   1 AND [invoiceCreditNote].[isPost]=0 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNoteHistory.php') {
                $sql .= " AND	[invoiceCreditNote].[isActive]= 1  AND [invoiceCreditNote].[isBalance]=1 AND [invoiceCreditNote].[isPost]=1 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNoteCancel.php' || $this->model->getFrom() == 'invoiceCreditNoteVoid.php') {
                $sql .= " AND	[invoiceCreditNote].[isDelete] = 1   ";
            }
        } else if ($this->getVendor() == self::ORACLE) {

            $sql = "
		  SELECT                    INVOICECREDITNOTE.INVOICECREDITNOTEID AS \"invoiceCreditNoteId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICECREDITNOTE.COMPANYID AS \"companyId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    INVOICECREDITNOTE.BUSINESSPARTNERID AS \"businessPartnerId\",
                    INVOICE.INVOICEDESCRIPTION AS  \"invoiceDescription\",
                    INVOICECREDITNOTE.INVOICEID AS \"invoiceId\",
					INVOICECREDITNOTE.INVOICECREDITNOTECODE AS \"invoiceCreditNoteCode\",
                    INVOICECREDITNOTE.INVOICECREDITNOTETITLE AS \"invoiceCreditNoteTitle\",
                    INVOICECREDITNOTE.DOCUMENTNUMBER AS \"documentNumber\",
                    INVOICECREDITNOTE.INVOICECREDITNOTEAMOUNT AS \"invoiceCreditNoteAmount\",
                    INVOICECREDITNOTE.REFERENCENUMBER AS \"referenceNumber\",
                    INVOICECREDITNOTE.INVOICECREDITNOTEDATE AS \"invoiceCreditNoteDate\",
                    INVOICECREDITNOTE.INVOICECREDITNOTEDESCRIPTION AS \"invoiceCreditNoteDescription\",
                    INVOICECREDITNOTE.ISDEFAULT AS \"isDefault\",
                    INVOICECREDITNOTE.ISNEW AS \"isNew\",
                    INVOICECREDITNOTE.ISDRAFT AS \"isDraft\",
                    INVOICECREDITNOTE.ISUPDATE AS \"isUpdate\",
                    INVOICECREDITNOTE.ISDELETE AS \"isDelete\",
                    INVOICECREDITNOTE.ISACTIVE AS \"isActive\",
                    INVOICECREDITNOTE.ISAPPROVED AS \"isApproved\",
                    INVOICECREDITNOTE.ISREVIEW AS \"isReview\",
                    INVOICECREDITNOTE.ISPOST AS \"isPost\",
                    INVOICECREDITNOTE.EXECUTEBY AS \"executeBy\",
                    INVOICECREDITNOTE.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\" 
		  FROM 	INVOICECREDITNOTE 
		  JOIN	STAFF 
		  ON	INVOICECREDITNOTE.EXECUTEBY = STAFF.STAFFID 
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICECREDITNOTE.COMPANYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = INVOICECREDITNOTE.BUSINESSPARTNERID
	JOIN	INVOICE
	ON		INVOICE.INVOICEID = INVOICECREDITNOTE.INVOICEID
         WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceCreditNoteId(0, 'single')) {
                $sql .= " AND INVOICECREDITNOTE. " . strtoupper($this->model->getPrimaryKeyName()) . "='" . $this->model->getInvoiceCreditNoteId(0, 'single') . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND INVOICECREDITNOTE.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getInvoiceId()) {
                $sql .= " AND INVOICECREDITNOTE.INVOICEID='" . $this->model->getInvoiceId() . "'";
            }
            if ($this->model->getFrom() == 'invoiceCreditNote.php' || $this->model->getFrom() == 'invoiceCreditNoteMaintenance.php') {
                $sql .= " AND	INVOICECREDITNOTE.ISACTIVE= 1 AND	INVOICECREDITNOTE.ISPOST= 0 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNotePost.php' || $this->model->getFrom() == 'invoiceCreditNotePosting.php') {
                $sql .= " AND	INVOICECREDITNOTE.ISACTIVE= 1   AND   INVOICECREDITNOTE.ISBALANCE =   1 AND .ISPOST=0 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNoteHistory.php') {
                $sql .= " AND	INVOICECREDITNOTE.ISACTIVE= 1  AND INVOICECREDITNOTE.ISBALANCE=1 AND INVOICECREDITNOTE.ISPOST=1 ";
            }
            if ($this->model->getFrom() == 'invoiceCreditNoteCancel.php' || $this->model->getFrom() == 'invoiceCreditNoteVoid.php' ) {
                $sql .= " AND	INVOICECREDITNOTE.ISDELETE]= 0   ";
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
                $sql.=" AND `invoicecreditnote`.`" . $this->model->getFilterCharacter() . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=" AND [invoiceCreditNote].[" . $this->model->getFilterCharacter() . "] like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=" AND Initcap(INVOICECREDITNOTE." . strtoupper($this->model->getFilterCharacter()) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%')";
            }
        }
        /**
         * filter column based on Range Of Date 
         * Example Day,Week,Month,Year 
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql.=$this->q->dateFilter('invoicecreditnote', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::MSSQL) {
                $sql.=$this->q->dateFilter('invoiceCreditNote', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            } else if ($this->getVendor() == self::ORACLE) {
                $sql.=$this->q->dateFilter('INVOICECREDITNOTE', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery());
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search. 
         * E.g  $filterArray=array('`leaf`.`leafId`'); 
         * @variables $filterArray; 
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array("`invoicecreditnote`.`invoiceCreditNoteId`",
                "`staff`.`staffPassword`");
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array("[invoicecreditnote].[invoiceCreditNoteId]",
                "[staff].[staffPassword]");
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array("INVOICECREDITNOTE.INVOICECREDITNOTEID",
                "STAFF.STAFFPASSWORD");
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'invoicecreditnote', 'company', 'businesspartner', 'invoice');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'invoicecreditnote', 'company', 'businesspartner', 'invoice');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'INVOICECREDITNOTE', 'COMPANY', 'BUSINESSPARTNER', 'INVOICE');
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
        if (!($this->model->getInvoiceCreditNoteId(0, 'single'))) {
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
            if ($this->model->getInvoiceCreditNoteId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getInvoiceCreditNoteId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceCreditNoteId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getInvoiceCreditNoteId(0, 'single')) {
                $end = microtime(true);
                $time = $end - $start;
                echo str_replace(array("[", "]"), "", json_encode(array(
                    'success' => true,
                    'total' => $total,
                    'message' => $this->t['viewRecordMessageLabel'],
                    'time' => $time,
                    'firstRecord' => $this->firstRecord('value'),
                    'previousRecord' => $this->previousRecord('value', $this->model->getInvoiceCreditNoteId(0, 'single')),
                    'nextRecord' => $this->nextRecord('value', $this->model->getInvoiceCreditNoteId(0, 'single')),
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
                    'previousRecord' => $this->recordSet->previousRecord('value', $this->model->getInvoiceCreditNoteId(0, 'single')),
                    'nextRecord' => $this->recordSet->nextRecord('value', $this->model->getInvoiceCreditNoteId(0, 'single')),
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
           FROM 	`invoicecreditnote`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[invoiceCreditNote] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICECREDITNOTE 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `invoicecreditnote` SET 
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `invoiceId` = '" . $this->model->getInvoiceId() . "',
                       `invoiceCreditNoteTitle` = '" . $this->model->getInvoiceCreditNoteTitle() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `invoiceCreditNoteAmount` = '" . $this->model->getInvoiceCreditNoteAmount() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `invoiceCreditNoteDate` = '" . $this->model->getInvoiceCreditNoteDate() . "',
                       `invoiceCreditNoteDescription` = '" . $this->model->getInvoiceCreditNoteDescription() . "',
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
               WHERE    `invoiceCreditNoteId`='" . $this->model->getInvoiceCreditNoteId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [invoiceCreditNote] SET 
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [invoiceId] = '" . $this->model->getInvoiceId() . "',
                       [invoiceCreditNoteTitle] = '" . $this->model->getInvoiceCreditNoteTitle() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [invoiceCreditNoteAmount] = '" . $this->model->getInvoiceCreditNoteAmount() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [invoiceCreditNoteDate] = '" . $this->model->getInvoiceCreditNoteDate() . "',
                       [invoiceCreditNoteDescription] = '" . $this->model->getInvoiceCreditNoteDescription() . "',
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
                WHERE   [invoiceCreditNoteId]='" . $this->model->getInvoiceCreditNoteId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE INVOICECREDITNOTE SET
                        BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       INVOICEID = '" . $this->model->getInvoiceId() . "',
                       INVOICECREDITNOTETITLE = '" . $this->model->getInvoiceCreditNoteTitle() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       INVOICECREDITNOTEAMOUNT = '" . $this->model->getInvoiceCreditNoteAmount() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       INVOICECREDITNOTEDATE = '" . $this->model->getInvoiceCreditNoteDate() . "',
                       INVOICECREDITNOTEDESCRIPTION = '" . $this->model->getInvoiceCreditNoteDescription() . "',
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
                WHERE  INVOICECREDITNOTEID='" . $this->model->getInvoiceCreditNoteId('0', 'single') . "'";
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
           FROM 	`invoicecreditnote` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[invoiceCreditNote] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	INVOICECREDITNOTE 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "' ";
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
               UPDATE  `invoicecreditnote` 
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
               WHERE   `invoiceCreditNoteId`   =  '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [invoiceCreditNote] 
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
               WHERE   [invoiceCreditNoteId]	=  '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  INVOICECREDITNOTE 
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
               WHERE   INVOICECREDITNOTEID	=  '" . $this->model->getInvoiceCreditNoteId(0, 'single') . "'";
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
           FROM    `invoicecreditnote` 
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
           SELECT  [documentNumber]
           FROM    [invoiceCreditNote] 
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1 
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
               SELECT  DOCUMENTNUMBER as \"documentNumber\"
               FROM    INVOICECREDITNOTE 
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
     * Return Business Partner
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
         FROM    `invoicecreditnote`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [invoiceCreditNote]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      INVOICECREDITNOTE
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
                    $this->model->getInvoiceCreditNoteId(0, 'single'), $this->getLeafId(), $this->model->getFrom()
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
                ->setSubject('invoiceCreditNote')
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
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(TRUE);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('M2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:M2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['invoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['invoiceCreditNoteTitleLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['invoiceCreditNoteAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['invoiceCreditNoteDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['invoiceCreditNoteDescriptionLabel']);
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
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['invoiceDescription']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('E' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['invoiceCreditNoteTitle']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['invoiceCreditNoteAmount']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['invoiceCreditNoteDate']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['invoiceCreditNoteDescription']));
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
                $filename = "invoiceCreditNote" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceCreditNote" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceCreditNote" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceCreditNote" . rand(0, 10000000) . $extension;
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
        $invoiceCreditNoteObject = new InvoiceCreditNoteClass ();
        if ($_POST['securityToken'] != $invoiceCreditNoteObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value 
         */
        if (isset($_POST ['leafId'])) {
            $invoiceCreditNoteObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $invoiceCreditNoteObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $invoiceCreditNoteObject->setLimit($_POST ['limit']);
        }
        $invoiceCreditNoteObject->setPageOutput($_POST['output']);
        $invoiceCreditNoteObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy) 
         */
        if ($_POST ['method'] == 'create') {
            $invoiceCreditNoteObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $invoiceCreditNoteObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $invoiceCreditNoteObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $invoiceCreditNoteObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$invoiceCreditNoteObject->posting(); 
        }
        if ($_POST ['method'] == 'reverse') {
            //	$invoiceCreditNoteObject->delete(); 
        }
    }
}
if (isset($_GET ['method'])) {
    $invoiceCreditNoteObject = new InvoiceCreditNoteClass ();
    if ($_GET['securityToken'] != $invoiceCreditNoteObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $invoiceCreditNoteObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $invoiceCreditNoteObject->execute();
    /*
     * Update Status of The Table. Admin Level Only 
     */
    if ($_GET ['method'] == 'updateStatus') {
        $invoiceCreditNoteObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key 
     */
    if ($_GET['method'] == 'duplicate') {
        $invoiceCreditNoteObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $invoiceCreditNoteObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $invoiceCreditNoteObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $invoiceCreditNoteObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $invoiceCreditNoteObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting  
     */
    if (isset($_GET ['mode'])) {
        $invoiceCreditNoteObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml') {
            $invoiceCreditNoteObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $invoiceCreditNoteObject->setServiceOutput('option');
        if (($_GET['filter'] == 'businessPartner')) {
            $invoiceCreditNoteObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'invoice')) {
            $invoiceCreditNoteObject->getInvoice();
        }
    }
}
?>
