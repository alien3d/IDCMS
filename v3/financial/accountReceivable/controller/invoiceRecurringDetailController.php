<?php

namespace Core\Financial\AccountReceivable\InvoiceRecurringDetail\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\AccountReceivable\InvoiceRecurringDetail\Model\InvoiceRecurringDetailModel;
use Core\Financial\AccountReceivable\InvoiceRecurringDetail\Service\InvoiceRecurringDetailService;
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
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/model/invoiceRecurringDetailModel.php");
require_once($newFakeDocumentRoot . "v3/financial/accountReceivable/service/invoiceRecurringDetailService.php");

/**
 * Class InvoiceRecurringDetail
 * this is invoiceRecurringDetail controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\AccountReceivable\InvoiceRecurringDetail\Controller
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceRecurringDetailClass extends ConfigClass {

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
     * @var \Core\Financial\AccountReceivable\InvoiceRecurringDetail\Model\InvoiceRecurringDetailModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\AccountReceivable\InvoiceRecurringDetail\Service\InvoiceRecurringDetailService
     */
    public $service;

    /**
     * System Format
     * @var string
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
        $this->setViewPath("./v3/financial/accountReceivable/view/invoiceRecurringDetail.php");
        $this->setControllerPath(
                "./v3/financial/accountReceivable/controller/invoiceRecurringDetailController.php"
        );
        $this->setServicePath("./v3/financial/accountReceivable/service/invoiceRecurringDetailService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new InvoiceRecurringDetailModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
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

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new InvoiceRecurringDetailService();
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
        if (!$this->model->getInvoiceRecurringId()) {
            $this->model->setInvoiceRecurringId($this->service->getInvoiceRecurringDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getTransactionTypeId()) {
            $this->model->setTransactionTypeId($this->service->getTransactionTypeDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `invoicerecurringdetail` 
            (
                 `companyId`,
                 `invoiceRecurringId`,
                 `chartOfAccountId`,
                 `countryId`,
                 `transactionTypeId`,
                 `documentNumber`,
                 `journalNumber`,
                 `invoiceRecurringDetailAmount`,
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
                 '" . $this->model->getInvoiceRecurringId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getTransactionTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceRecurringDetailAmount() . "',
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [invoiceRecurringDetail]
            (
                 [invoiceRecurringDetailId],
                 [companyId],
                 [invoiceRecurringId],
                 [chartOfAccountId],
                 [countryId],
                 [transactionTypeId],
                 [documentNumber],
                 [journalNumber],
                 [invoiceRecurringDetailAmount],
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
                 '" . $this->model->getInvoiceRecurringId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getTransactionTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceRecurringDetailAmount() . "',
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
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO INVOICERECURRINGDETAIL
            (
                 COMPANYID,
                 INVOICERECURRINGID,
                 CHARTOFACCOUNTID,
                 COUNTRYID,
                 TRANSACTIONTYPEID,
                 DOCUMENTNUMBER,
                 JOURNALNUMBER,
                 INVOICERECURRINGDETAILAMOUNT,
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
                 '" . $this->model->getInvoiceRecurringId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getTransactionTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getInvoiceRecurringDetailAmount() . "',
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
            }
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $invoiceRecurringDetailId = $this->q->lastInsertId("invoiceRecurringDetail");
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
                    "invoiceRecurringDetailId" => $invoiceRecurringDetailId,
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
         FROM    `invoicerecurringdetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
            $sql .= "AND     `invoiceRecurringId` = " . $this->model->getInvoiceRecurringId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [invoiceRecurringDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            $sql .= "AND     [invoiceRecurringId] = " . $this->model->getInvoiceRecurringId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      INVOICERECURRINGDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
            $sql .= "AND     INVOICERECURRINGID = " . $this->model->getInvoiceRecurringId() . " ";
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
        if ($this->getPageOutput() == 'json' || $this->getPageOutput() == 'table') {
            header('Content-Type:application/json; charset=utf-8');
        }
        $start = microtime(true);
        if (isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] == 0) {
                if ($this->getVendor() == self::MYSQL) {
                    $this->setAuditFilter(
                            " `invoicerecurringdetail`.`isActive` = 1  AND `invoicerecurringdetail`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [invoiceRecurringDetail].[isActive] = 1 AND [invoiceRecurringDetail].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " INVOICERECURRINGDETAIL.ISACTIVE = 1  AND INVOICERECURRINGDETAIL.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `invoicerecurringdetail`.`companyId`='" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [invoiceRecurringDetail].[companyId]='" . $this->getCompanyId() . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " INVOICERECURRINGDETAIL.COMPANYID='" . $this->getCompanyId() . "' "
                                );
                            }
                        }
                    }
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
       SELECT                    `invoicerecurringdetail`.`invoiceRecurringDetailId`,
                    `company`.`companyDescription`,
                    `invoicerecurringdetail`.`companyId`,
                    `invoicerecurring`.`invoiceRecurringDescription`,
                    `invoicerecurringdetail`.`invoiceRecurringId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `invoicerecurringdetail`.`chartOfAccountId`,
                    `country`.`countryDescription`,
                    `invoicerecurringdetail`.`countryId`,
                    `transactiontype`.`transactionTypeDescription`,
                    `invoicerecurringdetail`.`transactionTypeId`,
                    `invoicerecurringdetail`.`documentNumber`,
                    `invoicerecurringdetail`.`journalNumber`,
                    `invoicerecurringdetail`.`invoiceRecurringDetailAmount`,
                    `invoicerecurringdetail`.`isDefault`,
                    `invoicerecurringdetail`.`isNew`,
                    `invoicerecurringdetail`.`isDraft`,
                    `invoicerecurringdetail`.`isUpdate`,
                    `invoicerecurringdetail`.`isDelete`,
                    `invoicerecurringdetail`.`isActive`,
                    `invoicerecurringdetail`.`isApproved`,
                    `invoicerecurringdetail`.`isReview`,
                    `invoicerecurringdetail`.`isPost`,
                    `invoicerecurringdetail`.`executeBy`,
                    `invoicerecurringdetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `invoicerecurringdetail`
		  JOIN      `staff`
		  ON        `invoicerecurringdetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `invoicerecurringdetail`.`companyId`
	JOIN	`invoicerecurring`
	ON		`invoicerecurring`.`invoiceRecurringId` = `invoicerecurringdetail`.`invoiceRecurringId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `invoicerecurringdetail`.`chartOfAccountId`
	JOIN	`country`
	ON		`country`.`countryId` = `invoicerecurringdetail`.`countryId`
	JOIN	`transactiontype`
	ON		`transactiontype`.`transactionTypeId` = `invoicerecurringdetail`.`transactionTypeId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getInvoiceRecurringDetailId(0, 'single')) {
                $sql .= " AND `invoicerecurringdetail`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getInvoiceRecurringDetailId(0, 'single') . "'";
            }
            if ($this->model->getInvoiceRecurringId()) {
                $sql .= " AND `invoicerecurringdetail`.`invoiceRecurringId`='" . $this->model->getInvoiceRecurringId(
                        ) . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `invoicerecurringdetail`.`chartOfAccountId`='" . $this->model->getChartOfAccountId(
                        ) . "'";
            }
            if ($this->model->getCountryId()) {
                $sql .= " AND `invoicerecurringdetail`.`countryId`='" . $this->model->getCountryId() . "'";
            }
            if ($this->model->getTransactionTypeId()) {
                $sql .= " AND `invoicerecurringdetail`.`transactionTypeId`='" . $this->model->getTransactionTypeId(
                        ) . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [invoiceRecurringDetail].[invoiceRecurringDetailId],
                    [company].[companyDescription],
                    [invoiceRecurringDetail].[companyId],
                    [invoiceRecurring].[invoiceRecurringDescription],
                    [invoiceRecurringDetail].[invoiceRecurringId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [invoiceRecurringDetail].[chartOfAccountId],
                    [country].[countryDescription],
                    [invoiceRecurringDetail].[countryId],
                    [transactionType].[transactionTypeDescription],
                    [invoiceRecurringDetail].[transactionTypeId],
                    [invoiceRecurringDetail].[documentNumber],
                    [invoiceRecurringDetail].[journalNumber],
                    [invoiceRecurringDetail].[invoiceRecurringDetailAmount],
                    [invoiceRecurringDetail].[isDefault],
                    [invoiceRecurringDetail].[isNew],
                    [invoiceRecurringDetail].[isDraft],
                    [invoiceRecurringDetail].[isUpdate],
                    [invoiceRecurringDetail].[isDelete],
                    [invoiceRecurringDetail].[isActive],
                    [invoiceRecurringDetail].[isApproved],
                    [invoiceRecurringDetail].[isReview],
                    [invoiceRecurringDetail].[isPost],
                    [invoiceRecurringDetail].[executeBy],
                    [invoiceRecurringDetail].[executeTime],
                    [staff].[staffName]
		  FROM 	[invoiceRecurringDetail]
		  JOIN	[staff]
		  ON	[invoiceRecurringDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [invoiceRecurringDetail].[companyId]
	JOIN	[invoiceRecurring]
	ON		[invoiceRecurring].[invoiceRecurringId] = [invoiceRecurringDetail].[invoiceRecurringId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [invoiceRecurringDetail].[chartOfAccountId]
	JOIN	[country]
	ON		[country].[countryId] = [invoiceRecurringDetail].[countryId]
	JOIN	[transactionType]
	ON		[transactionType].[transactionTypeId] = [invoiceRecurringDetail].[transactionTypeId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getInvoiceRecurringDetailId(0, 'single')) {
                    $sql .= " AND [invoiceRecurringDetail].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getInvoiceRecurringDetailId(0, 'single') . "'";
                }
                if ($this->model->getInvoiceRecurringId()) {
                    $sql .= " AND [invoiceRecurringDetail].[invoiceRecurringId]='" . $this->model->getInvoiceRecurringId(
                            ) . "'";
                }
                if ($this->model->getChartOfAccountId()) {
                    $sql .= " AND [invoiceRecurringDetail].[chartOfAccountId]='" . $this->model->getChartOfAccountId(
                            ) . "'";
                }
                if ($this->model->getCountryId()) {
                    $sql .= " AND [invoiceRecurringDetail].[countryId]='" . $this->model->getCountryId() . "'";
                }
                if ($this->model->getTransactionTypeId()) {
                    $sql .= " AND [invoiceRecurringDetail].[transactionTypeId]='" . $this->model->getTransactionTypeId(
                            ) . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    INVOICERECURRINGDETAIL.INVOICERECURRINGDETAILID AS \"invoiceRecurringDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    INVOICERECURRINGDETAIL.COMPANYID AS \"companyId\",
                    INVOICERECURRING.INVOICERECURRINGDESCRIPTION AS  \"invoiceRecurringDescription\",
                    INVOICERECURRINGDETAIL.INVOICERECURRINGID AS \"invoiceRecurringId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    INVOICERECURRINGDETAIL.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    INVOICERECURRINGDETAIL.COUNTRYID AS \"countryId\",
                    TRANSACTIONTYPE.TRANSACTIONTYPEDESCRIPTION AS  \"transactionTypeDescription\",
                    INVOICERECURRINGDETAIL.TRANSACTIONTYPEID AS \"transactionTypeId\",
                    INVOICERECURRINGDETAIL.DOCUMENTNUMBER AS \"documentNumber\",
                    INVOICERECURRINGDETAIL.JOURNALNUMBER AS \"journalNumber\",
                    INVOICERECURRINGDETAIL.INVOICERECURRINGDETAILAMOUNT AS \"invoiceRecurringDetailAmount\",
                    INVOICERECURRINGDETAIL.ISDEFAULT AS \"isDefault\",
                    INVOICERECURRINGDETAIL.ISNEW AS \"isNew\",
                    INVOICERECURRINGDETAIL.ISDRAFT AS \"isDraft\",
                    INVOICERECURRINGDETAIL.ISUPDATE AS \"isUpdate\",
                    INVOICERECURRINGDETAIL.ISDELETE AS \"isDelete\",
                    INVOICERECURRINGDETAIL.ISACTIVE AS \"isActive\",
                    INVOICERECURRINGDETAIL.ISAPPROVED AS \"isApproved\",
                    INVOICERECURRINGDETAIL.ISREVIEW AS \"isReview\",
                    INVOICERECURRINGDETAIL.ISPOST AS \"isPost\",
                    INVOICERECURRINGDETAIL.EXECUTEBY AS \"executeBy\",
                    INVOICERECURRINGDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	INVOICERECURRINGDETAIL
		  JOIN	STAFF
		  ON	INVOICERECURRINGDETAIL.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = INVOICERECURRINGDETAIL.COMPANYID
	JOIN	INVOICERECURRING
	ON		INVOICERECURRING.INVOICERECURRINGID = INVOICERECURRINGDETAIL.INVOICERECURRINGID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = INVOICERECURRINGDETAIL.CHARTOFACCOUNTID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = INVOICERECURRINGDETAIL.COUNTRYID
	JOIN	TRANSACTIONTYPE
	ON		TRANSACTIONTYPE.TRANSACTIONTYPEID = INVOICERECURRINGDETAIL.TRANSACTIONTYPEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getInvoiceRecurringDetailId(0, 'single')) {
                        $sql .= " AND INVOICERECURRINGDETAIL. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getInvoiceRecurringDetailId(0, 'single') . "'";
                    }
                    if ($this->model->getInvoiceRecurringId()) {
                        $sql .= " AND INVOICERECURRINGDETAIL.INVOICERECURRINGID='" . $this->model->getInvoiceRecurringId(
                                ) . "'";
                    }
                    if ($this->model->getChartOfAccountId()) {
                        $sql .= " AND INVOICERECURRINGDETAIL.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId(
                                ) . "'";
                    }
                    if ($this->model->getCountryId()) {
                        $sql .= " AND INVOICERECURRINGDETAIL.COUNTRYID='" . $this->model->getCountryId() . "'";
                    }
                    if ($this->model->getTransactionTypeId()) {
                        $sql .= " AND INVOICERECURRINGDETAIL.TRANSACTIONTYPEID='" . $this->model->getTransactionTypeId(
                                ) . "'";
                    }
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
            }
        }
        /**
         * filter column based on first character
         */
        if ($this->getCharacterQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " AND `invoicerecurringdetail`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [invoiceRecurringDetail].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(INVOICERECURRINGDETAIL." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%');";
                    }
                }
            }
        }
        /**
         * filter column based on Range Of Date
         * Example Day,Week,Month,Year
         */
        if ($this->getDateRangeStartQuery()) {
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->dateFilter(
                        'invoicerecurringdetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'invoiceRecurringDetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'INVOICERECURRINGDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                        );
                    }
                }
            }
        }
        /**
         * filter column don't want to filter.Example may contain  sensitive information or unwanted to be search.
         * E.g  $filterArray=array('`leaf`.`leafId`');
         * @variables $filterArray;
         */
        $filterArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $filterArray = array(
                "`invoicerecurringdetail`.`invoiceRecurringDetailId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[invoiceRecurringDetail].[invoiceRecurringDetailId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "INVOICERECURRINGDETAIL.INVOICERECURRINGDETAILID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'invoicerecurringdetail',
                'company',
                'invoicerecurring',
                'chartofaccount',
                'country',
                'transactiontype'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'staff',
                    'invoicerecurringdetail',
                    'company',
                    'invoicerecurring',
                    'chartofaccount',
                    'country',
                    'transactiontype'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'STAFF',
                        'INVOICERECURRINGDETAIL',
                        'COMPANY',
                        'INVOICERECURRING',
                        'CHARTOFACCOUNT',
                        'COUNTRY',
                        'TRANSACTIONTYPE'
                    );
                }
            }
        }
        $tempSql = null;
        if ($this->getFieldQuery()) {
            $this->q->setFieldQuery($this->getFieldQuery());
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->quickSearch($tableArray, $filterArray);
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $tempSql = $this->q->quickSearch($tableArray, $filterArray);
                    $sql .= $tempSql;
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $tempSql = $this->q->quickSearch($tableArray, $filterArray);
                        $sql .= $tempSql;
                    }
                }
            }
        }
        $tempSql2 = null;
        if ($this->getGridQuery()) {
            $this->q->setGridQuery($this->getGridQuery());
            if ($this->getVendor() == self::MYSQL) {
                $sql .= $this->q->searching();
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $tempSql2 = $this->q->searching();
                    $sql .= $tempSql2;
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $tempSql2 = $this->q->searching();
                        $sql .= $tempSql2;
                    }
                }
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
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= "	ORDER BY [" . $this->getSortField() . "] " . $this->getOrder() . " ";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= "	ORDER BY " . strtoupper($this->getSortField()) . " " . strtoupper(
                                        $this->getOrder()
                                ) . " ";
                    }
                }
            }
        } else {
            // @note sql server 2012 must order by first then offset ??
            if ($this->getVendor() == self::MSSQL) {
                $sql .= "	ORDER BY [" . $this->model->getTableName() . "].[" . $this->model->getPrimaryKeyName(
                        ) . "] ASC ";
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
                
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    /**
                     * Sql Server  2012 format only.Row Number
                     * Parameter Query We don't support
                     **/
                    $sqlDerived = $sql . " 	OFFSET  	" . $this->getStart() . " ROWS
											FETCH NEXT 	" . $this->getLimit() . " ROWS ONLY ";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
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
                        echo json_encode(
                                array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel'])
                        );
                        exit();
                    }
                }
            }
        }
        /*
         *  Only Execute One Query
         */
        if (!($this->model->getInvoiceRecurringDetailId(0, 'single'))) {
            try {
                $this->q->read($sqlDerived);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 20;
            if ($this->model->getInvoiceRecurringDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getInvoiceRecurringDetailId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getInvoiceRecurringDetailId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else {
            if ($this->getPageOutput() == 'table') {
                $this->setService('html');
                $str = null;
                if (is_array($items)) {
                    $this->setServiceOutput('html');
                    $totalRecordDetail = intval(count($items));
                    if ($totalRecordDetail > 0) {
                        $counter = 0;
                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                            $counter++;
                            $str .= "<tr id='" . $items[$j]['invoiceRecurringDetailId'] . "'>";
                            $str .= "<td vAlign=\"center\"><div align=\"center\">" . ($counter) . "</div>
		</td>";
                            $str .= "<td><div class='btn-group'>";
                            $str .= "<input type=\"hidden\" name='invoiceRecurringDetailId[]'     id='invoiceRecurringDetailId" . $items[$j]['invoiceRecurringDetailId'] . "'  value='" . $items[$j]['invoiceRecurringDetailId'] . "'>";
                            $str .= "<input type=\"hidden\" name='invoiceRecurringId[]'
                    id='invoiceRecurringDetailId" . $items[$j]['invoiceRecurringId'] . "'
                        value='" . $items[$j]['invoiceRecurringId'] . "'>";
                            $str .= "<a class=' btn-warning btn-xs' title='Edit' onClick=showFormUpdateDetail('" . $this->getLeafId(
                                    ) . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken(
                                    ) . "','" . $items[$j]['invoiceRecurringDetailId'] . "')><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>";
                            $str .= "<a class=' btn-danger btn-xs' title='Delete' onClick=showModalDeleteDetail('" . $items[$j]['invoiceRecurringDetailId'] . "')><i class='glyphicontrash  glyphicon-white'></i></a><div id=miniInfoPanel" . $items[$j]['invoiceRecurringDetailId'] . "></div></td>";
                            $str .= "<input type=\"hidden\" name='invoiceRecurringId[]' id='invoiceRecurringId" . $items[$j]['invoiceRecurringDetailId'] . "' value='" . $items[$j]['invoiceRecurringId'] . "'>";
                            $chartOfAccountArray = $this->getChartOfAccount();
                            $str .= "<td><div class='form-group col-md-12' id='chartOfAccountId" . $items[$j]['invoiceRecurringDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='chartOfAccountId[]' id='chartOfAccountId" . $items[$j]['invoiceRecurringDetailId'] . "' class='chzn-select' onChange=removeMeErrorDetail('chartOfAccountId" . $items[$j]['invoiceRecurringDetailId'] . "') >";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($chartOfAccountArray)) {
                                $totalRecord = intval(count($chartOfAccountArray));
                                if ($totalRecord > 0) {
                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        if ($items[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $chartOfAccountArray[$i]['chartOfAccountId'] . "' " . $selected . ">" . $chartOfAccountArray[$i]['chartOfAccountTitle'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $str .= "<input type=\"hidden\" name='countryId[]' id='countryId" . $items[$j]['invoiceRecurringDetailId'] . "' value='" . $items[$j]['countryId'] . "'>";
                            $str .= "<input type=\"hidden\" name='transactionTypeId[]' id='transactionTypeId" . $items[$j]['invoiceRecurringDetailId'] . "' value='" . $items[$j]['transactionTypeId'] . "'>";
                            $str .= "<td><input class='form-control'  type='text' name='documentNumber[]' id='documentNumber" . $items[$j]['invoiceRecurringDetailId'] . "'   value='" . $items[$j]['documentNumber'] . "'></td>";
                            $str .= "<td><input class='form-control'  type='text' name='invoiceRecurringDetailAmount[]' id='invoiceRecurringDetailAmount" . $items[$j]['invoiceRecurringDetailId'] . "'   value='" . $items[$j]['invoiceRecurringDetailAmount'] . "'></td>";
                            $str .= "</tr>";
                        }
                    } else {
                        $str .= "<tr>";
                        $str .= "<td colspan=\"6\"  vAlign=\"top\" align=\"center\">" . $this->exceptionMessageReturn(
                                        $this->t['recordNotFoundLabel']
                                ) . "</td>";
                        $str .= "</tr>";
                    }
                } else {
                    $str .= "<tr>";
                    $str .= "<td colspan=\"6\"  vAlign=\"top\" align=\"center\">" . $this->exceptionMessageReturn(
                                    $this->t['recordNotFoundLabel']
                            ) . "</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array('success' => true, 'tableData' => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getInvoiceRecurringDetailId(0, 'single')) {
                        $end = microtime(true);
                        $time = $end - $start;
                        echo str_replace(
                                array("[", "]"), "", json_encode(
                                        array(
                                            'success' => true,
                                            'total' => $total,
                                            'message' => $this->t['viewRecordMessageLabel'],
                                            'time' => $time,
                                            'firstRecord' => $this->firstRecord('value'),
                                            'previousRecord' => $this->previousRecord(
                                                    'value', $this->model->getInvoiceRecurringDetailId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getInvoiceRecurringDetailId(0, 'single')
                                            ),
                                            'lastRecord' => $this->lastRecord('value'),
                                            'data' => $items
                                        )
                                )
                        );
                        exit();
                    } else {
                        if (count($items) == 0) {
                            $items = '';
                        }
                        $end = microtime(true);
                        $time = $end - $start;
                        echo json_encode(
                                array(
                                    'success' => true,
                                    'total' => $total,
                                    'message' => $this->t['viewRecordMessageLabel'],
                                    'time' => $time,
                                    'firstRecord' => $this->recordSet->firstRecord('value'),
                                    'previousRecord' => $this->recordSet->previousRecord(
                                            'value', $this->model->getInvoiceRecurringDetailId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getInvoiceRecurringDetailId(0, 'single')
                                    ),
                                    'lastRecord' => $this->recordSet->lastRecord('value'),
                                    'data' => $items
                                )
                        );
                        exit();
                    }
                }
            }
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
     * Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
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
        if (!$this->model->getInvoiceRecurringId()) {
            $this->model->setInvoiceRecurringId($this->service->getInvoiceRecurringDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getTransactionTypeId()) {
            $this->model->setTransactionTypeId($this->service->getTransactionTypeDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`invoicerecurringdetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceRecurringDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[invoiceRecurringDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceRecurringDetailId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::ORACLE) {
                $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	INVOICERECURRINGDETAIL
           WHERE  	" . strtoupper(
                                $this->model->getPrimaryKeyName()
                        ) . " = '" . $this->model->getInvoiceRecurringDetailId(0, 'single') . "' ";
            }
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `invoicerecurringdetail` SET
                       `invoiceRecurringId` = '" . $this->model->getInvoiceRecurringId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `transactionTypeId` = '" . $this->model->getTransactionTypeId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `journalNumber` = '" . $this->model->getJournalNumber() . "',
                       `invoiceRecurringDetailAmount` = '" . $this->model->getInvoiceRecurringDetailAmount() . "',
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
               WHERE    `invoiceRecurringDetailId`='" . $this->model->getInvoiceRecurringDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [invoiceRecurringDetail] SET
                       [invoiceRecurringId] = '" . $this->model->getInvoiceRecurringId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [transactionTypeId] = '" . $this->model->getTransactionTypeId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [journalNumber] = '" . $this->model->getJournalNumber() . "',
                       [invoiceRecurringDetailAmount] = '" . $this->model->getInvoiceRecurringDetailAmount() . "',
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
                WHERE   [invoiceRecurringDetailId]='" . $this->model->getInvoiceRecurringDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE INVOICERECURRINGDETAIL SET
                        INVOICERECURRINGID = '" . $this->model->getInvoiceRecurringId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       TRANSACTIONTYPEID = '" . $this->model->getTransactionTypeId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber() . "',
                       INVOICERECURRINGDETAILAMOUNT = '" . $this->model->getInvoiceRecurringDetailAmount() . "',
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
                WHERE  INVOICERECURRINGDETAILID='" . $this->model->getInvoiceRecurringDetailId('0', 'single') . "'";
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
           FROM 	`invoicerecurringdetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getInvoiceRecurringDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[invoiceRecurringDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getInvoiceRecurringDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	INVOICERECURRINGDETAIL
           WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getInvoiceRecurringDetailId(0, 'single') . "' ";
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
               UPDATE  `invoicerecurringdetail`
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
               WHERE   `invoiceRecurringDetailId`   =  '" . $this->model->getInvoiceRecurringDetailId(
                                0, 'single'
                        ) . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [invoiceRecurringDetail]
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
               WHERE   [invoiceRecurringDetailId]	=  '" . $this->model->getInvoiceRecurringDetailId(
                                0, 'single'
                        ) . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  INVOICERECURRINGDETAIL
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
               WHERE   INVOICERECURRINGDETAILID	=  '" . $this->model->getInvoiceRecurringDetailId(0, 'single') . "'";
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
     * Return  InvoiceRecurring
     * @return null|string
     */
    public function getInvoiceRecurring() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getInvoiceRecurring();
    }

    /**
     * Return  Country
     * @return null|string
     */
    public function getCountry() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCountry();
    }

    /**
     * Return  TransactionType
     * @return null|string
     */
    public function getTransactionType() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getTransactionType();
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
                ->setSubject('invoiceRecurringDetail')
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('K2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:K2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['invoiceRecurringIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['transactionTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['journalNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['invoiceRecurringDetailAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['invoiceRecurringDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['chartOfAccountTitle']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['countryDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'F' . $loopRow, strip_tags($row ['transactionTypeDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['journalNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'I' . $loopRow, strip_tags($row ['invoiceRecurringDetailAmount'])
            );
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
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
                $filename = "invoiceRecurringDetail" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceRecurringDetail" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceRecurringDetail" . rand(0, 10000000) . $extension;
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
                $filename = "invoiceRecurringDetail" . rand(0, 10000000) . $extension;
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
        $invoiceRecurringDetailObject = new InvoiceRecurringDetailClass ();
        if ($_POST['securityToken'] != $invoiceRecurringDetailObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $invoiceRecurringDetailObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $invoiceRecurringDetailObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $invoiceRecurringDetailObject->setLimit($_POST ['limit']);
        }
        $invoiceRecurringDetailObject->setPageOutput($_POST['output']);
        $invoiceRecurringDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $invoiceRecurringDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $invoiceRecurringDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $invoiceRecurringDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $invoiceRecurringDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$invoiceRecurringDetailObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$invoiceRecurringDetailObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $invoiceRecurringDetailObject = new InvoiceRecurringDetailClass ();
    if ($_GET['securityToken'] != $invoiceRecurringDetailObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $invoiceRecurringDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $invoiceRecurringDetailObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $invoiceRecurringDetailObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $invoiceRecurringDetailObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $invoiceRecurringDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $invoiceRecurringDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $invoiceRecurringDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $invoiceRecurringDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $invoiceRecurringDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $invoiceRecurringDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $invoiceRecurringDetailObject->setServiceOutput('option');
        if (($_GET['filter'] == 'invoiceRecurring')) {
            $invoiceRecurringDetailObject->getInvoiceRecurring();
        }
        if (($_GET['filter'] == 'chartOfAccount')) {
            $invoiceRecurringDetailObject->getChartOfAccount();
        }
        if (($_GET['filter'] == 'country')) {
            $invoiceRecurringDetailObject->getCountry();
        }
        if (($_GET['filter'] == 'transactionType')) {
            $invoiceRecurringDetailObject->getTransactionType();
        }
    }
}
?>
