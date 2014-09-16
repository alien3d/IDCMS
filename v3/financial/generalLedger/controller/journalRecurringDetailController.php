<?php

namespace Core\Financial\GeneralLedger\JournalRecurringDetail\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\GeneralLedger\JournalRecurringDetail\Model\JournalRecurringDetailModel;
use Core\Financial\GeneralLedger\JournalRecurringDetail\Service\JournalRecurringDetailService;
use Core\RecordSet\RecordSet;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/model/journalRecurringDetailModel.php");
require_once($newFakeDocumentRoot . "v3/financial/generalLedger/service/journalRecurringDetailService.php");

/**
 * Class JournalRecurringDetailClass
 * this is journal recurring detail setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\JournalRecurringDetail\Controller
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class JournalRecurringDetailClass extends ConfigClass {

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
     * @var \Core\Financial\GeneralLedger\JournalRecurringDetail\Model\JournalRecurringDetailModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\GeneralLedger\JournalRecurringDetail\Service\JournalRecurringDetailService
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
     * @var mixed
     */
    public $t;

    /**
     * System Format
     * @var mixed
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
     * journalRecurringIdt
     * @var int
     */
    private $journalRecurringId;

    /**
     * chartOfAccountIdt
     * @var int
     */
    private $chartOfAccountId;

    /**
     * countryIdt
     * @var int
     */
    private $countryId;

    /**
     * transactionTypeIdt
     * @var int
     */
    private $transactionTypeId;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->t = array();
        $this->leafAccess = array();
        $this->systemFormat = array();
        $this->setViewPath("./v3/financial/generalLedger/view/journalRecurringDetail.php");
        $this->setControllerPath("./v3/financial/generalLedger/controller/journalRecurringDetailController.php");
        $this->setServicePath("./v3/financial/generalLedger/service/journalRecurringDetailService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new JournalRecurringDetailModel();
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

        $this->service = new JournalRecurringDetailService();
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->create();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `journalrecurringdetail` 
            (
                 `companyId`,
                 `journalRecurringId`,
                 `chartOfAccountId`,
                 `countryId`,
                 `transactionTypeId`,
                 `journalRecurringDetailAmount`,
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
                 '" . $_SESSION['companyId'] . "',
                 '" . $this->model->getJournalRecurringId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getTransactionTypeId() . "',
                 '" . $this->model->getJournalRecurringDetailAmount() . "',
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
            INSERT INTO [journalRecurringDetail]
            (
                 [journalRecurringDetailId],
                 [companyId],
                 [journalRecurringId],
                 [chartOfAccountId],
                 [countryId],
                 [transactionTypeId],
                 [journalRecurringDetailAmount],
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
                 '" . $_SESSION['companyId'] . "',
                 '" . $this->model->getJournalRecurringId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getTransactionTypeId() . "',
                 '" . $this->model->getJournalRecurringDetailAmount() . "',
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
            INSERT INTO JOURNALRECURRINGDETAIL
            (
                 COMPANYID,
                 JOURNALRECURRINGID,
                 CHARTOFACCOUNTID,
                 COUNTRYID,
                 TRANSACTIONTYPEID,
                 JOURNALRecurringDETAILAMOUNT,
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
                 '" . $_SESSION['companyId'] . "',
                 '" . $this->model->getJournalRecurringId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getTransactionTypeId() . "',
                 '" . $this->model->getJournalRecurringDetailAmount() . "',
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $journalRecurringDetailId = $this->q->lastInsertId();

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
                    "journalRecurringDetailId" => $journalRecurringDetailId,
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
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total`
         FROM    `journalrecurringdetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $_SESSION['companyId'] . " ";
            $sql .= "AND     `journalRecurringId` = " . $this->model->getJournalRecurringId() . " ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT  count(*) AS total
         FROM    [journalRecurringDetail]
         WHERE   [isActive]=1
         AND    [companyId] =   " . $_SESSION['companyId'] . " ";
                $sql .= "AND     [journalRecurringId] = " . $this->model->getJournalRecurringId() . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
         SELECT  count(*) AS    total
         FROM    JOURNALRECURRINGDETAIL
         WHERE  ISACTIVE=1
         AND    COMPANYID=" . $_SESSION['companyId'] . " ";
                    $sql .= "AND     JOURNALRecurringID = " . $this->model->getJournalRecurringId() . " ";
                }
            }
        }
        $result = $this->q->fast($sql);
        if ($result) {
            if ($this->q->numberRows($result) > 0) {
                $row = $this->q->fetchArray($result);
                return $row['total'];
            }
        }
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
                            " `journalrecurringdetail`.`isActive` = 1  AND `journalrecurringdetail`.`companyId`='" . $_SESSION['companyId'] . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [journalRecurringDetail].[isActive] = 1 AND [journalRecurringDetail].[companyId]='" . $_SESSION['companyId'] . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " JOURNALRECURRINGDETAIL.ISACTIVE = 1  AND JOURNALRECURRINGDETAIL.COMPANYID='" . $_SESSION['companyId'] . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `journalrecurringdetail`.`companyId`='" . $_SESSION['companyId'] . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [journalRecurringDetail].[companyId]='" . $_SESSION['companyId'] . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " JOURNALRECURRINGDETAIL.COMPANYID='" . $_SESSION['companyId'] . "' "
                                );
                            }
                        }
                    }
                }
            }
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
       SELECT                    `journalrecurringdetail`.`journalRecurringDetailId`,
                    `company`.`companyDescription`,
                    `journalrecurringdetail`.`companyId`,
                    `journalrecurring`.`journalRecurringDesc`,
                    `journalrecurringdetail`.`journalRecurringId`,
                    `chartofaccount`.`chartOfAccountDesc`,
                    `journalrecurringdetail`.`chartOfAccountId`,
                    `country`.`countryDesc`,
                    `journalrecurringdetail`.`countryId`,
                    `transactiontype`.`transactionTypeDesc`,
                    `journalrecurringdetail`.`transactionTypeId`,
                    `journalrecurringdetail`.`journalRecurringDetailAmount`,
                    `journalrecurringdetail`.`isDefault`,
                    `journalrecurringdetail`.`isNew`,
                    `journalrecurringdetail`.`isDraft`,
                    `journalrecurringdetail`.`isUpdate`,
                    `journalrecurringdetail`.`isDelete`,
                    `journalrecurringdetail`.`isActive`,
                    `journalrecurringdetail`.`isApproved`,
                    `journalrecurringdetail`.`isReview`,
                    `journalrecurringdetail`.`isPost`,
                    `journalrecurringdetail`.`executeBy`,
                    `journalrecurringdetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `journalrecurringdetail`
		  JOIN      `staff`
		  ON        `journalrecurringdetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `journalrecurringdetail`.`companyId`
	JOIN	`journalrecurring`
	ON		`journalrecurring`.`journalRecurringId` = `journalrecurringdetail`.`journalRecurringId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `journalrecurringdetail`.`chartOfAccountId`
	JOIN	`country`
	ON		`country`.`countryId` = `journalrecurringdetail`.`countryId`
	JOIN	`transactiontype`
	ON		`transactiontype`.`transactionTypeId` = `journalrecurringdetail`.`transactionTypeId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getJournalRecurringDetailId(0, 'single')) {
                $sql .= " AND `journalrecurringdetail`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getJournalRecurringDetailId(0, 'single') . "'";
            }
            if ($this->model->getJournalrecurringId()) {
                $sql .= " AND  `journalrecurringdetail`.`JournalrecurringId` = " . $this->model->getJournalrecurringId(
                );
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [journalRecurringDetail].[journalRecurringDetailId],
                    [company].[companyDescription],
                    [journalRecurringDetail].[companyId],
                    [journalRecurring].[journalRecurringDesc],
                    [journalRecurringDetail].[journalRecurringId],
                    [chartOfAccount].[chartOfAccountDesc],
                    [journalRecurringDetail].[chartOfAccountId],
                    [country].[countryDesc],
                    [journalRecurringDetail].[countryId],
                    [transactionType].[transactionTypeDesc],
                    [journalRecurringDetail].[transactionTypeId],
                    [journalRecurringDetail].[journalRecurringDetailAmount],
                    [journalRecurringDetail].[isDefault],
                    [journalRecurringDetail].[isNew],
                    [journalRecurringDetail].[isDraft],
                    [journalRecurringDetail].[isUpdate],
                    [journalRecurringDetail].[isDelete],
                    [journalRecurringDetail].[isActive],
                    [journalRecurringDetail].[isApproved],
                    [journalRecurringDetail].[isReview],
                    [journalRecurringDetail].[isPost],
                    [journalRecurringDetail].[executeBy],
                    [journalRecurringDetail].[executeTime],
                    [staff].[staffName]
		  FROM 	[journalRecurringDetail]
		  JOIN	[staff]
		  ON		[journalRecurringDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [journalRecurringDetail].[companyId]
	JOIN	[journalRecurring]
	ON		[journalRecurring].[journalRecurringId] = [journalRecurringDetail].[journalRecurringId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [journalRecurringDetail].[chartOfAccountId]
	JOIN	[country]
	ON		[country].[countryId] = [journalRecurringDetail].[countryId]
	JOIN	[transactionType]
	ON		[transactionType].[transactionTypeId] = [journalRecurringDetail].[transactionTypeId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getJournalRecurringDetailId(0, 'single')) {
                    $sql .= " AND [journalRecurringDetail].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getJournalRecurringDetailId(0, 'single') . "'";
                }
                if ($this->model->getJournalrecurringId()) {
                    $sql .= " AND  [journalRecurringDetail].[JournalrecurringId] = " . $this->model->getJournalrecurringId(
                    );
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    JOURNALRECURRINGDETAIL.JOURNALRecurringDETAILID,
                    COMPANY.COMPANYID,
                    JOURNALRECURRINGDETAIL.COMPANYID,
                    JOURNALRECURRING.JOURNALRECURRINGID,
                    JOURNALRECURRINGDETAIL.JOURNALRECURRINGID,
                    CHARTOFACCOUNT.CHARTOFACCOUNTID,
                    JOURNALRECURRINGDETAIL.CHARTOFACCOUNTID,
                    COUNTRY.COUNTRYID,
                    JOURNALRECURRINGDETAIL.COUNTRYID,
                    TRANSACTIONTYPE.TRANSACTIONTYPEID,
                    JOURNALRECURRINGDETAIL.TRANSACTIONTYPEID,
                    JOURNALRECURRINGDETAIL.JOURNALRecurringDETAILAMOUNT,
                    JOURNALRECURRINGDETAIL.ISDEFAULT,
                    JOURNALRECURRINGDETAIL.ISNEW,
                    JOURNALRECURRINGDETAIL.ISDRAFT,
                    JOURNALRECURRINGDETAIL.ISUPDATE,
                    JOURNALRECURRINGDETAIL.ISDELETE,
                    JOURNALRECURRINGDETAIL.ISACTIVE,
                    JOURNALRECURRINGDETAIL.ISAPPROVED,
                    JOURNALRECURRINGDETAIL.ISREVIEW,
                    JOURNALRECURRINGDETAIL.ISPOST,
                    JOURNALRECURRINGDETAIL.EXECUTEBY,
                    JOURNALRECURRINGDETAIL.EXECUTETIME,
                    STAFF.STAFFNAME
		  FROM 	JOURNALRECURRINGDETAIL
		  JOIN	STAFF
		  ON	JOURNALRECURRINGDETAIL.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = JOURNALRECURRINGDETAIL.COMPANYID
	JOIN	JOURNALRECURRING
	ON		JOURNALRECURRING.JOURNALRECURRINGID = JOURNALRECURRINGDETAIL.JOURNALRECURRINGID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = JOURNALRECURRINGDETAIL.CHARTOFACCOUNTID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = JOURNALRECURRINGDETAIL.COUNTRYID
	JOIN	TRANSACTIONTYPE
	ON		TRANSACTIONTYPE.TRANSACTIONTYPEID = JOURNALRECURRINGDETAIL.TRANSACTIONTYPEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getJournalRecurringDetailId(0, 'single')) {
                        $sql .= " AND JOURNALRECURRINGDETAIL. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getJournalRecurringDetailId(0, 'single') . "'";
                    }
                    if ($this->model->getJournalrecurringId()) {
                        $sql .= " AND  JOURNALRECURRINGDETAIL.JOURNALRECURRINGID = " . $this->model->getJournalrecurringId(
                        );
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
                $sql .= " AND `journalrecurringdetail`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [journalRecurringDetail].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND JOURNALRECURRINGDETAIL." . strtoupper(
                                        $this->model->getFilterCharacter()
                                ) . " = '" . $this->getCharacterQuery() . "'";
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
                        'journalrecurringdetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'journalrecurringdetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'JOURNALRECURRINGDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
        $filterArray = array('journalRecurringDetailId');
        /**
         * filter table
         * @variables $tableArray
         */
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('journalRecurringDetail');
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array('journalRecurringDetail');
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array('JOURNALRECURRINGDETAIL');
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
        // optional debugger.uncomment if wanted to used
        //if ($this->q->getExecute() == 'fail') {
        //	echo json_encode(array(
        //   "success" => false,
        //   "message" => $this->q->realEscapeString($sql)
        //	));
        //	exit();
        //}
        // end of optional debugger
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        $total = $this->q->numberRows();
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
        }
        $_SESSION ['sql'] = $sql; // push to session so can make report via excel and pdf
        $_SESSION ['start'] = $this->getStart();
        $_SESSION ['limit'] = $this->getLimit();
        if ($this->getLimit()) {
            // only mysql have limit
            if ($this->getVendor() == self::MYSQL) {
                $sql .= " LIMIT  " . $this->getStart() . "," . $this->getLimit() . " ";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    /**
                     * Sql Server and Oracle used row_number
                     * Parameterize Query We don't support
                     * **/

                    $sql = "WITH [journalrecurringdetailDerived] AS
							(
								SELECT 										[journalrecurringdetail].[journalRecurringDetailId],
										[company].[companyDescription],
										[journalrecurringdetail].[companyId],
										[journalRecurring].[journalRecurringDesc],
										[journalrecurringdetail].[journalRecurringId],
										[chartOfAccount].[chartOfAccountDesc],
										[journalrecurringdetail].[chartOfAccountId],
										[country].[countryDesc],
										[journalrecurringdetail].[countryId],
										[transactionType].[transactionTypeDesc],
										[journalrecurringdetail].[transactionTypeId],
										[journalrecurringdetail].[journalRecurringDetailAmount],
										[journalrecurringdetail].[isDefault],
										[journalrecurringdetail].[isNew],
										[journalrecurringdetail].[isDraft],
										[journalrecurringdetail].[isUpdate],
										[journalrecurringdetail].[isDelete],
										[journalrecurringdetail].[isActive],
										[journalrecurringdetail].[isApproved],
										[journalrecurringdetail].[isReview],
										[journalrecurringdetail].[isPost],
										[journalrecurringdetail].[executeBy],
										[journalrecurringdetail].[executeTime],
										[staff].[staffName],
										ROW_NUMBER() OVER (ORDER BY [journalRecurringDetail].[journalRecurringDetailId]) AS 'RowNumber'
							     FROM 	[journalrecurringdetail]
							     JOIN	[staff]
							     ON         [journalRecurringDetail].[executeBy] = [staff].[staffId]
							     JOIN   [company]
							     ON     [company].[companyId] = [journalRecurringDetail].[companyId]
							     JOIN   [journalRecurring]
							     ON     [journalRecurring].[journalRecurringId] = [journalRecurringDetail].[journalRecurringId]
							     JOIN   [chartOfAccount]
							     ON     [chartOfAccount].[chartOfAccountId] = [journalRecurringDetail].[chartOfAccountId]
							     JOIN   [country]
							     ON     [country].[countryId] = [journalRecurringDetail].[countryId]
							     JOIN   [transactionType]
							     ON     [transactionType].[transactionTypeId] = [journalRecurringDetail].[transactionTypeId]
							     WHERE 		" . $this->getAuditFilter() . "

							)
							SELECT		*
							FROM 		[journalrecurringdetailDerived]
							WHERE 		[RowNumber]
							BETWEEN	" . ($this->getStart() + 1) . "
							AND 			" . ($this->getStart() + $this->getLimit()) . " ;";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        /**
                         * Oracle using derived table also
                         * */
                        $sql = "
						SELECT *
						FROM ( SELECT	a.*,
												rownum r
						FROM (
SELECT							     JOURNALRECURRINGDETAIL.JOURNALRecurringDETAILID,
							     COMPANY.COMPANYID,
							     JOURNALRECURRINGDETAIL.COMPANYID,
							     JOURNALRECURRING.JOURNALRECURRINGID,
							     JOURNALRECURRINGDETAIL.JOURNALRECURRINGID,
							     CHARTOFACCOUNT.CHARTOFACCOUNTID,
							     JOURNALRECURRINGDETAIL.CHARTOFACCOUNTID,
							     COUNTRY.COUNTRYID,
							     JOURNALRECURRINGDETAIL.COUNTRYID,
							     TRANSACTIONTYPE.TRANSACTIONTYPEID,
							     JOURNALRECURRINGDETAIL.TRANSACTIONTYPEID,
							     JOURNALRECURRINGDETAIL.JOURNALRecurringDETAILAMOUNT,
							     JOURNALRECURRINGDETAIL.ISDEFAULT,
							     JOURNALRECURRINGDETAIL.ISNEW,
							     JOURNALRECURRINGDETAIL.ISDRAFT,
							     JOURNALRECURRINGDETAIL.ISUPDATE,
							     JOURNALRECURRINGDETAIL.ISDELETE,
							     JOURNALRECURRINGDETAIL.ISACTIVE,
							     JOURNALRECURRINGDETAIL.ISAPPROVED,
							     JOURNALRECURRINGDETAIL.ISREVIEW,
							     JOURNALRECURRINGDETAIL.ISPOST,
							     JOURNALRECURRINGDETAIL.EXECUTEBY,
							     JOURNALRECURRINGDETAIL.EXECUTETIME,
                                   STAFF.STAFFNAME
							     FROM 	JOURNALRECURRINGDETAIL
							     JOIN	  STAFF
							     ON		JOURNALRECURRINGDETAIL.EXECUTEBY = STAFF.STAFFID
							     JOIN   COMPANY
							     ON     COMPANY.COMPANYID = JOURNALRECURRINGDETAIL.COMPANYID
							     JOIN   JOURNALRECURRING
							     ON     JOURNALRECURRING.JOURNALRECURRINGID = JOURNALRECURRINGDETAIL.JOURNALRECURRINGID
							     JOIN   CHARTOFACCOUNT
							     ON     CHARTOFACCOUNT.CHARTOFACCOUNTID = JOURNALRECURRINGDETAIL.CHARTOFACCOUNTID
							     JOIN   COUNTRY
							     ON     COUNTRY.COUNTRYID = JOURNALRECURRINGDETAIL.COUNTRYID
							     JOIN   TRANSACTIONTYPE
							     ON     TRANSACTIONTYPE.TRANSACTIONTYPEID = JOURNALRECURRINGDETAIL.TRANSACTIONTYPEID
							     WHERE 		" . $this->getAuditFilter() . $tempSql . $tempSql2 . "
								 ) a
						WHERE rownum <= '" . ($this->getStart() + $this->getLimit()) . "' )
						WHERE r >=  '" . ($this->getStart() + 1) . "'";
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
        if (!($this->model->getJournalRecurringDetailId(0, 'single'))) {
            try {
                $this->q->read($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 18;
            if ($this->model->getJournalRecurringDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getJournalRecurringDetailId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getJournalRecurringDetailId(0, 'single'));
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
                    $totalRecordDetail = intval(count($items));
                    if ($totalRecordDetail > 0) {
                        $counter = 0;

                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                            $counter++;
                            $str .= "<tr id='" . $items[$j]['journalRecurringDetailId'] . "'>";
                            $str .= "<td>" . ($counter) . "</td>";
                            $str .= "<td><div class='btn-group'>";
                            $str .= "<input type=\"hidden\" name='journalRecurringDetailId[]'     id='journalRecurringDetailId" . $items[$j]['journalRecurringDetailId'] . "'  value='" . $items[$j]['journalRecurringDetailId'] . "'>";
                            $str .= "<input type=\"hidden\" name='journalRecurringId[]'
                    id='journalRecurringDetailId" . $items[$j]['journalRecurringId'] . "'
                        value='" . $items[$j]['journalRecurringId'] . "'>";
                            $str .= "<a class=' btn-warning' title='Edit' onClick=\"showFormUpdateDetail('" . $this->getLeafId(
                                    ) . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken(
                                    ) . "','" . $items[$j]['journalRecurringId'] . "')\"><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>";
                            $str .= "<a class=' btn-danger' title='Delete' onClick=\"showModalDeleteDetail('" . $items[$j]['journalRecurringId'] . "')\"><i class='glyphicontrash  glyphicon-white'></i></a><div id=miniInfoPanel" . $items[$j]['journalRecurringId'] . "></div></td>";
                            $journalRecurringArray = $this->getJournalRecurring();
                            $str .= "<td><div class='form-group' id='journalRecurringId" . $items[$j]['journalRecurringDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='journalRecurringId[]' id='journalRecurringId" . $items[$j]['journalRecurringDetailId'] . "' class='combobox' onChange=\"removeMeErrorDetail('journalRecurringId" . $items[$j]['journalRecurringDetailId'] . "')\">";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($journalRecurringArray)) {
                                $totalRecord = intval(count($journalRecurringArray));
                                if ($totalRecord > 0) {
                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        if ($items[$j]['journalRecurringId'] == $journalRecurringArray[$i]['journalRecurringId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $journalRecurringArray[$i]['journalRecurringId'] . "' " . $selected . ">" . $journalRecurringArray[$i]['journalRecurringDesc'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $chartOfAccountArray = $this->getChartOfAccount();
                            $str .= "<td><div class='form-group' id='chartOfAccountId" . $items[$j]['journalRecurringDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='chartOfAccountId[]' id='chartOfAccountId" . $items[$j]['journalRecurringDetailId'] . "' class='combobox' onChange=\"removeMeErrorDetail('chartOfAccountId" . $items[$j]['journalRecurringDetailId'] . "')\">";
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
                                        $str .= "<option value='" . $chartOfAccountArray[$i]['chartOfAccountId'] . "' " . $selected . ">" . $chartOfAccountArray[$i]['chartOfAccountDesc'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $countryArray = $this->getCountry();
                            $str .= "<td><div class='form-group' id='countryId" . $items[$j]['journalRecurringDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='countryId[]' id='countryId" . $items[$j]['journalRecurringDetailId'] . "' class='combobox' onChange=\"removeMeErrorDetail('countryId" . $items[$j]['journalRecurringDetailId'] . "')\">";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($countryArray)) {
                                $totalRecord = intval(count($countryArray));
                                if ($totalRecord > 0) {
                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        if ($items[$j]['countryId'] == $countryArray[$i]['countryId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $countryArray[$i]['countryId'] . "' " . $selected . ">" . $countryArray[$i]['countryDesc'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $transactionTypeArray = $this->getTransactionType();
                            $str .= "<td><div class='form-group' id='transactionTypeId" . $items[$j]['journalRecurringDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='transactionTypeId[]' id='transactionTypeId" . $items[$j]['journalRecurringDetailId'] . "' class='combobox' onChange=\"removeMeErrorDetail('transactionTypeId" . $items[$j]['journalRecurringDetailId'] . "')\">";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($transactionTypeArray)) {
                                $totalRecord = intval(count($transactionTypeArray));
                                if ($totalRecord > 0) {
                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        if ($items[$j]['transactionTypeId'] == $transactionTypeArray[$i]['transactionTypeId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $transactionTypeArray[$i]['transactionTypeId'] . "' " . $selected . ">" . $transactionTypeArray[$i]['transactionTypeDesc'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $str .= "<td><input class=\"col-\" style='text-align:right' type=\"text\" name='journalRecurringDetailAmount[]' id='journalRecurringDetailAmount" . $items[$j]['journalRecurringDetailId'] . "'   value='" . $items[$j]['journalRecurringDetailAmount'] . "'></td>";
                            $str .= "<td><input type=\"text\" name='executeBy[]' id='executeBy" . $items[$j]['journalRecurringDetailId'] . "' value='" . $items[$j]['staffName'] . "' readOnly class='col-md-6'>
                 </td>";
                            $valueArray = $items[$j]['executeTime'];

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
                                    $this->systemFormatArray['systemSettingDateFormat'] . " " . $this->systemFormatArray['systemSettingTimeFormat'], mktime($hour, $minute, $second, $month, $day, $year)
                            );
                            $str .= "<td><input type=\"text\" name='executeTime' id='executeTime" . $items[$j]['journalRecurringDetailId'] . "' value='" . $value . "' readOnly class=\"col-md-10\"></td>";
                            $str .= "</tr>";
                        }
                    } else {
                        $str .= "<tr>";
                        $str .= "<td colspan=\"6\">" . $this->exceptionMessageReturn(
                                        $this->t['recordNotFoundLabel']
                                ) . "</td>";
                        $str .= "</tr>";
                    }
                } else {
                    $str .= "<tr>";
                    $str .= "<td colspan=\"6\">" . $this->exceptionMessageReturn(
                                    $this->t['recordNotFoundLabel']
                            ) . "</td>";
                    $str .= "</tr>";
                }
                echo json_encode(array('success' => true, 'tableData' => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getJournalRecurringDetailId(0, 'single')) {
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
                                                    'value', $this->model->getJournalRecurringDetailId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getJournalRecurringDetailId(0, 'single')
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
                                            'value', $this->model->getJournalRecurringDetailId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getJournalRecurringDetailId(0, 'single')
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
     * @throws \Exception
     */
    function firstRecord($value) {
        return $this->recordSet->firstRecord($value);
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
     * Last Record
     * @param string $value . This return data type. When call by normal read.Value=='value'.When requested by ajax request button Value=='json'
     * @return int
     * @throws \Exception
     */
    function lastRecord($value) {
        return $this->recordSet->lastRecord($value);
    }

    /**  Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return  JournalRecurring
     * @return mixed
     */
    public function getJournalRecurring() {
        return $this->service->getJournalRecurring();
    }

    /**
     * Return  ChartOfAccount
     * @return mixed
     */
    public function getChartOfAccount() {
        return $this->service->getChartOfAccount();
    }

    /**
     * Return  Country
     * @return mixed
     */
    public function getCountry() {
        return $this->service->getCountry();
    }

    /**
     * Return  TransactionType
     * @return mixed
     */
    public function getTransactionType() {
        return $this->service->getTransactionType();
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->update();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`journalrecurringdetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getJournalRecurringDetailId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[journalrecurringdetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getJournalRecurringDetailId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	JOURNALRECURRINGDETAIL
           WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getJournalRecurringDetailId(0, 'single') . "' ";
                }
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
               UPDATE `journalrecurringdetail` SET
                       `companyId` = '" . $this->model->getCompanyId() . "',
                       `journalRecurringId` = '" . $this->model->getJournalRecurringId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `transactionTypeId` = '" . $this->model->getTransactionTypeId() . "',
                       `journalRecurringDetailAmount` = '" . $this->model->getJournalRecurringDetailAmount() . "',
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
               WHERE    `journalRecurringDetailId`='" . $this->model->getJournalRecurringDetailId('0', 'single') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE [journalRecurringDetail] SET
                       [companyId] = '" . $this->model->getCompanyId() . "',
                       [journalRecurringId] = '" . $this->model->getJournalRecurringId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [transactionTypeId] = '" . $this->model->getTransactionTypeId() . "',
                       [journalRecurringDetailAmount] = '" . $this->model->getJournalRecurringDetailAmount() . "',
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
                WHERE   [journalRecurringDetailId]='" . $this->model->getJournalRecurringDetailId('0', 'single') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE JOURNALRECURRINGDETAIL SET
                       JOURNALRECURRINGID = '" . $this->model->getJournalRecurringId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       TRANSACTIONTYPEID = '" . $this->model->getTransactionTypeId() . "',
                       JOURNALRecurringDETAILAMOUNT = '" . $this->model->getJournalRecurringDetailAmount() . "',
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
                WHERE  JOURNALRecurringDETAILID='" . $this->model->getJournalRecurringDetailId('0', 'single') . "'";
                    }
                }
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
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
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->delete();
        // before updating check the id exist or not . if exist continue to update else warning the user
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`journalrecurringdetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getJournalRecurringDetailId(
                            0, 'single'
                    ) . "' ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[journalrecurringdetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getJournalRecurringDetailId(
                                0, 'single'
                        ) . "' ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	JOURNALRECURRINGDETAIL
           WHERE  	" . strtoupper(
                                    $this->model->getPrimaryKeyName()
                            ) . " = '" . $this->model->getJournalRecurringDetailId(0, 'single') . "' ";
                }
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
               UPDATE  `journalrecurringdetail`
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
               WHERE   `journalRecurringDetailId`   =  '" . $this->model->getJournalRecurringDetailId(
                                0, 'single'
                        ) . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
               UPDATE  [journalRecurringDetail]
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
               WHERE   [journalRecurringDetailId]	=  '" . $this->model->getJournalRecurringDetailId(
                                    0, 'single'
                            ) . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
               UPDATE  JOURNALRECURRINGDETAIL
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
               WHERE   JOURNALRecurringDETAILID	=  '" . $this->model->getJournalRecurringDetailId(0, 'single') . "'";
                    }
                }
            }
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
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
     * To check if a key duplicate or not
     */
    function duplicate() {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT  `journalrecurringdetailCode`
           FROM    `journalrecurringdetail`
           WHERE   `journalrecurringdetailCode` 	= 	'" . $this->model->getJournalrecurringdetailCode() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $_SESSION['companyId'] . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
           SELECT  [referenceNo]
           FROM    [journalRecurringDetail]
           WHERE   [journalrecurringdetailCode] = 	'" . $this->model->getJournalrecurringdetailCode() . "'
           AND     [isActive]  =   1
           AND     [companyId] =	'" . $_SESSION['companyId'] . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               SELECT  REFERENCENO
               FROM    JOURNALRECURRINGDETAIL
               WHERE   JOURNALRECURRINGDETAILCODE	= 	'" . $this->model->getJournalrecurringdetailCode() . "'
               AND     ISACTIVE    =   1
               AND     COMPANYID   =   '" . $_SESSION['companyId'] . "'";
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
     * Return JournalRecurring Primary Key
     * @return int
     */
    public function getJournalRecurringId() {
        return $this->journalRecurringId;
    }

    /**
     * Set JournalRecurring Primary Key
     * @param int $value
     */
    public function setJournalRecurringId($value) {
        $this->journalRecurringId = $value;
    }

    /**
     * Return ChartOfAccount Primary Key
     * @return int
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * Set ChartOfAccount Primary Key
     * @param int $value
     */
    public function setChartOfAccountId($value) {
        $this->chartOfAccountId = $value;
    }

    /**
     * Return Country Primary Key
     * @return int
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * Set Country Primary Key
     * @param int $value
     */
    public function setCountryId($value) {
        $this->countryId = $value;
    }

    /**
     * Return TransactionType Primary Key
     * @return int
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * Set TransactionType Primary Key
     * @param int $value
     */
    public function setTransactionTypeId($value) {
        $this->transactionTypeId = $value;
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
            $sql = str_replace("LIMIT", "", $_SESSION ['sql']);
            $sql = str_replace($_SESSION ['start'] . "," . $_SESSION ['limit'], "", $sql);
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
                ->setSubject('journalrecurringdetail')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/generalLedger');
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('J2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:J2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['companyIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['journalRecurringIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['transactionTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['journalRecurringDetailAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('C' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, $row ['companyId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('D' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, $row ['journalRecurringId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('E' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, $row ['chartOfAccountId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('F' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, $row ['countryId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('G' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, $row ['transactionTypeId']);
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, $row ['journalRecurringDetailAmount']);
            $this->excel->getActiveSheet()
                    ->setCellValue('I' . $loopRow, $row ['staffName']);
            $this->excel->getActiveSheet()
                    ->setCellValue('J' . $loopRow, $row ['executeTime']);
            $this->excel->getActiveSheet()
                    ->getStyle()
                    ->getNumberFormat('J' . $loopRow)
                    ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
            $loopRow++;
            $lastRow = 'J' . $loopRow;
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
                break;
            case 'excel5':
                $objWriter = new \PHPExcel_Writer_Excel5($this->excel);
                $extension = '.xls';
                $folder = 'excel';
                break;
            case 'pdf':
                $objWriter = new \PHPExcel_Writer_PDF($this->excel);
                $objWriter->writeAllSheets();
                $extension = '.pdf';
                $folder = 'pdf';
                break;
            case 'html':
                $objWriter = new \PHPExcel_Writer_HTML($this->excel);
                // $objWriter->setUseBOM(true);
                $extension = '.html';
                //$objWriter->setPreCalculateFormulas(false); //calculation off
                $folder = 'html';
                break;
            case 'csv':
                $objWriter = new \PHPExcel_Writer_CSV($this->excel);
                // $objWriter->setUseBOM(true);
                // $objWriter->setPreCalculateFormulas(false); //calculation off
                $extension = '.csv';
                $folder = 'excel';
                break;
        }
        $filename = "journalrecurringdetail" . rand(0, 10000000) . $extension;
        $path = $this->getFakeDocumentRoot() . "v3/financial/generalLedger/document/" . $folder . "/" . $filename;
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
    }

}

if (isset($_POST ['method'])) {
    if (isset($_POST['output'])) {
        $journalRecurringDetailObject = new JournalRecurringDetailClass ();
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $journalRecurringDetailObject->setLeafId($_POST ['leafId']);
        }
        $journalRecurringDetailObject->setPageOutput($_POST['output']);
        $journalRecurringDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $journalRecurringDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $journalRecurringDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $journalRecurringDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $journalRecurringDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$journalRecurringDetailObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$journalRecurringDetailObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $journalRecurringDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     * Admin Only
     */
    if (isset($_GET ['isAdmin'])) {
        $journalRecurringDetailObject->setIsAdmin($_GET ['isAdmin']);
    }
    /**
     * Database Request
     */
    if (isset($_GET ['databaseRequest'])) {
        $journalRecurringDetailObject->setRequestDatabase($_GET ['databaseRequest']);
    }
    if (isset($_GET['companyId'])) {
        $journalRecurringDetailObject->setCompanyId($_GET['companyId']);
    }
    if (isset($_GET['journalRecurringId'])) {
        $journalRecurringDetailObject->setJournalRecurringId($_GET['journalRecurringId']);
    }
    if (isset($_GET['chartOfAccountId'])) {
        $journalRecurringDetailObject->setChartOfAccountId($_GET['chartOfAccountId']);
    }
    if (isset($_GET['countryId'])) {
        $journalRecurringDetailObject->setCountryId($_GET['countryId']);
    }
    if (isset($_GET['transactionTypeId'])) {
        $journalRecurringDetailObject->setTransactionTypeId($_GET['transactionTypeId']);
    }
    /*
     *  Load the dynamic value
     */
    $journalRecurringDetailObject->execute();

    /**
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $journalRecurringDetailObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if (isset($_GET ['journalrecurringdetailCode'])) {
        if (strlen($_GET ['journalrecurringdetailCode']) > 0) {
            $journalRecurringDetailObject->duplicate();
        }
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $journalRecurringDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $journalRecurringDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $journalRecurringDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $journalRecurringDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $journalRecurringDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $journalRecurringDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'journalRecurring')) {
            $journalRecurringDetailObject->getJournalRecurring();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'chartOfAccount')) {
            $journalRecurringDetailObject->getChartOfAccount();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'country')) {
            $journalRecurringDetailObject->getCountry();
        }
    }
    if (isset($_GET ['filter'])) {
        if (($_GET['filter'] == 'transactionType')) {
            $journalRecurringDetailObject->getTransactionType();
        }
    }
}
?>