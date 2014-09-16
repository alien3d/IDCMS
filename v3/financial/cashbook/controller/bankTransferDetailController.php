<?php

namespace Core\Financial\Cashbook\BankTransferDetail\Controller;

use Core\ConfigClass;
use Core\Financial\Cashbook\BankTransferDetail\Model\BankTransferDetailModel;
use Core\Financial\Cashbook\BankTransferDetail\Service\BankTransferDetailService;
use Core\Document\Trail\DocumentTrailClass;
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
require_once($newFakeDocumentRoot . "v3/financial/cashbook/model/bankTransferDetailModel.php");
require_once($newFakeDocumentRoot . "v3/financial/cashbook/service/bankTransferDetailService.php");

/**
 * Class BankTransferDetail
 * this is bankTransferDetail controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\Cashbook\BankTransferDetail\Controller
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BankTransferDetailClass extends ConfigClass {

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
     * @var \Core\Financial\Cashbook\BankTransferDetail\Model\BankTransferDetailModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\Cashbook\BankTransferDetail\Service\BankTransferDetailService
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
        $this->setViewPath("./v3/financial/cashbook/view/bankTransferDetail.php");
        $this->setControllerPath("./v3/financial/cashbook/controller/bankTransferDetailController.php");
        $this->setServicePath("./v3/financial/cashbook/service/bankTransferDetailService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new BankTransferDetailModel();
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

        $this->service = new BankTransferDetailService();
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
        if (!$this->model->getBankTransferId()) {
            $this->model->setBankTransferId($this->service->getBankTransferDefaultValue());
        }
        if (!$this->model->getBankId()) {
            $this->model->setBankId($this->service->getBankDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `banktransferdetail` 
            (
                 `companyId`,
                 `bankTransferId`,
                 `bankId`,
                 `countryId`,
                 `chartOfAccountId`,
                 `journalNumber`,
                 `bankTransferDetailAmount`,
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
                 '" . $this->model->getBankTransferId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getBankTransferDetailAmount() . "',
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
            INSERT INTO [bankTransferDetail]
            (
                 [bankTransferDetailId],
                 [companyId],
                 [bankTransferId],
                 [bankId],
                 [countryId],
                 [chartOfAccountId],
                 [journalNumber],
                 [bankTransferDetailAmount],
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
                 '" . $this->model->getBankTransferId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getBankTransferDetailAmount() . "',
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
            INSERT INTO BANKTRANSFERDETAIL
            (
                 COMPANYID,
                 BANKTRANSFERID,
                 BANKID,
                 COUNTRYID,
                 CHARTOFACCOUNTID,
                 JOURNALNUMBER,
                 BANKTRANSFERDETAILAMOUNT,
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
                 '" . $this->model->getBankTransferId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getBankTransferDetailAmount() . "',
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
        $bankTransferDetailId = $this->q->lastInsertId('bankTransfer');
        $this->q->commit();
        $extra = $this->service->getTotalBankTransferDetail($this->model->getBankTransferId());
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $extra['totalDebit'] = $a->format($extra['totalDebit']);
            $extra['totalCredit'] = $a->format($extra['totalCredit']);
        }
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "bankTransferDetailId" => $bankTransferDetailId,
                    "time" => $time,
                    "totalDebit" => $extra['totalDebit'],
                    "totalCredit" => $extra['totalCredit'],
                    "trialBalance" => $extra['trialBalance']
                )
        );
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
                    $this->setAuditFilter(
                            " `banktransferdetail`.`isActive` = 1  AND `banktransferdetail`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [bankTransferDetail].[isActive] = 1 AND [bankTransferDetail].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " BANKTRANSFERDETAIL.ISACTIVE = 1  AND BANKTRANSFERDETAIL.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `banktransferdetail`.`companyId`='" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [bankTransferDetail].[companyId]='" . $this->getCompanyId() . "' ");
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" BANKTRANSFERDETAIL.COMPANYID='" . $this->getCompanyId() . "' ");
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
        $this->setStart(0);
        $this->setLimit(9999);
        if ($this->getVendor() == self::MYSQL) {

            $sql = "
       SELECT                    `banktransferdetail`.`bankTransferDetailId`,
                    `company`.`companyDescription`,
                    `banktransferdetail`.`companyId`,
                    `banktransfer`.`bankTransferDescription`,
                    `banktransferdetail`.`bankTransferId`,
                    `bank`.`bankDescription`,
                    `banktransferdetail`.`bankId`,
                    `country`.`countryDescription`,
                    `banktransferdetail`.`countryId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `banktransferdetail`.`chartOfAccountId`,
                    `banktransferdetail`.`journalNumber`,
                    `banktransferdetail`.`bankTransferDetailAmount`,
                    `banktransferdetail`.`isDefault`,
                    `banktransferdetail`.`isNew`,
                    `banktransferdetail`.`isDraft`,
                    `banktransferdetail`.`isUpdate`,
                    `banktransferdetail`.`isDelete`,
                    `banktransferdetail`.`isActive`,
                    `banktransferdetail`.`isApproved`,
                    `banktransferdetail`.`isReview`,
                    `banktransferdetail`.`isPost`,
                    `banktransferdetail`.`executeBy`,
                    `banktransferdetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `banktransferdetail`
		  JOIN      `staff`
		  ON        `banktransferdetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `banktransferdetail`.`companyId`
	JOIN	`banktransfer`
	ON		`banktransfer`.`bankTransferId` = `banktransferdetail`.`bankTransferId`
	JOIN	`bank`
	ON		`bank`.`bankId` = `banktransferdetail`.`bankId`
	JOIN	`country`
	ON		`country`.`countryId` = `banktransferdetail`.`countryId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `banktransferdetail`.`chartOfAccountId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getBankTransferDetailId(0, 'single')) {
                $sql .= " AND `banktransferdetail`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getBankTransferDetailId(0, 'single') . "'";
            }
            if ($this->model->getBankTransferId()) {
                $sql .= " AND `banktransferdetail`.`bankTransferId`='" . $this->model->getBankTransferId() . "'";
            }
            if ($this->model->getBankId()) {
                $sql .= " AND `banktransferdetail`.`bankId`='" . $this->model->getBankId() . "'";
            }
            if ($this->model->getCountryId()) {
                $sql .= " AND `banktransferdetail`.`countryId`='" . $this->model->getCountryId() . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `banktransferdetail`.`chartOfAccountId`='" . $this->model->getChartOfAccountId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [bankTransferDetail].[bankTransferDetailId],
                    [company].[companyDescription],
                    [bankTransferDetail].[companyId],
                    [bankTransfer].[bankTransferDescription],
                    [bankTransferDetail].[bankTransferId],
                    [bank].[bankDescription],
                    [bankTransferDetail].[bankId],
                    [country].[countryDescription],
                    [bankTransferDetail].[countryId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [bankTransferDetail].[chartOfAccountId],
                    [bankTransferDetail].[journalNumber],
                    [bankTransferDetail].[bankTransferDetailAmount],
                    [bankTransferDetail].[isDefault],
                    [bankTransferDetail].[isNew],
                    [bankTransferDetail].[isDraft],
                    [bankTransferDetail].[isUpdate],
                    [bankTransferDetail].[isDelete],
                    [bankTransferDetail].[isActive],
                    [bankTransferDetail].[isApproved],
                    [bankTransferDetail].[isReview],
                    [bankTransferDetail].[isPost],
                    [bankTransferDetail].[executeBy],
                    [bankTransferDetail].[executeTime],
                    [staff].[staffName]
		  FROM 	[bankTransferDetail]
		  JOIN	[staff]
		  ON	[bankTransferDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [bankTransferDetail].[companyId]
	JOIN	[bankTransfer]
	ON		[bankTransfer].[bankTransferId] = [bankTransferDetail].[bankTransferId]
	JOIN	[bank]
	ON		[bank].[bankId] = [bankTransferDetail].[bankId]
	JOIN	[country]
	ON		[country].[countryId] = [bankTransferDetail].[countryId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [bankTransferDetail].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getBankTransferDetailId(0, 'single')) {
                    $sql .= " AND [bankTransferDetail].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getBankTransferDetailId(0, 'single') . "'";
                }
                if ($this->model->getBankTransferId()) {
                    $sql .= " AND [bankTransferDetail].[bankTransferId]='" . $this->model->getBankTransferId() . "'";
                }
                if ($this->model->getBankId()) {
                    $sql .= " AND [bankTransferDetail].[bankId]='" . $this->model->getBankId() . "'";
                }
                if ($this->model->getCountryId()) {
                    $sql .= " AND [bankTransferDetail].[countryId]='" . $this->model->getCountryId() . "'";
                }
                if ($this->model->getChartOfAccountId()) {
                    $sql .= " AND [bankTransferDetail].[chartOfAccountId]='" . $this->model->getChartOfAccountId(
                            ) . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    BANKTRANSFERDETAIL.BANKTRANSFERDETAILID AS \"bankTransferDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    BANKTRANSFERDETAIL.COMPANYID AS \"companyId\",
                    BANKTRANSFER.BANKTRANSFERDESCRIPTION AS  \"bankTransferDescription\",
                    BANKTRANSFERDETAIL.BANKTRANSFERID AS \"bankTransferId\",
                    BANK.BANKDESCRIPTION AS  \"bankDescription\",
                    BANKTRANSFERDETAIL.BANKID AS \"bankId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    BANKTRANSFERDETAIL.COUNTRYID AS \"countryId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    BANKTRANSFERDETAIL.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    BANKTRANSFERDETAIL.JOURNALNUMBER AS \"journalNumber\",
                    BANKTRANSFERDETAIL.BANKTRANSFERDETAILAMOUNT AS \"bankTransferDetailAmount\",
                    BANKTRANSFERDETAIL.ISDEFAULT AS \"isDefault\",
                    BANKTRANSFERDETAIL.ISNEW AS \"isNew\",
                    BANKTRANSFERDETAIL.ISDRAFT AS \"isDraft\",
                    BANKTRANSFERDETAIL.ISUPDATE AS \"isUpdate\",
                    BANKTRANSFERDETAIL.ISDELETE AS \"isDelete\",
                    BANKTRANSFERDETAIL.ISACTIVE AS \"isActive\",
                    BANKTRANSFERDETAIL.ISAPPROVED AS \"isApproved\",
                    BANKTRANSFERDETAIL.ISREVIEW AS \"isReview\",
                    BANKTRANSFERDETAIL.ISPOST AS \"isPost\",
                    BANKTRANSFERDETAIL.EXECUTEBY AS \"executeBy\",
                    BANKTRANSFERDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	BANKTRANSFERDETAIL
		  JOIN	STAFF
		  ON	BANKTRANSFERDETAIL.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = BANKTRANSFERDETAIL.COMPANYID
	JOIN	BANKTRANSFER
	ON		BANKTRANSFER.BANKTRANSFERID = BANKTRANSFERDETAIL.BANKTRANSFERID
	JOIN	BANK
	ON		BANK.BANKID = BANKTRANSFERDETAIL.BANKID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = BANKTRANSFERDETAIL.COUNTRYID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = BANKTRANSFERDETAIL.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getBankTransferDetailId(0, 'single')) {
                        $sql .= " AND BANKTRANSFERDETAIL. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getBankTransferDetailId(0, 'single') . "'";
                    }
                    if ($this->model->getBankTransferId()) {
                        $sql .= " AND BANKTRANSFERDETAIL.BANKTRANSFERID='" . $this->model->getBankTransferId() . "'";
                    }
                    if ($this->model->getBankId()) {
                        $sql .= " AND BANKTRANSFERDETAIL.BANKID='" . $this->model->getBankId() . "'";
                    }
                    if ($this->model->getCountryId()) {
                        $sql .= " AND BANKTRANSFERDETAIL.COUNTRYID='" . $this->model->getCountryId() . "'";
                    }
                    if ($this->model->getChartOfAccountId()) {
                        $sql .= " AND BANKTRANSFERDETAIL.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId(
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
                $sql .= " AND `banktransferdetail`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [bankTransferDetail].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(BANKTRANSFERDETAIL." . strtoupper(
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
                        'banktransferdetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'bankTransferDetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'BANKTRANSFERDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`banktransferdetail`.`bankTransferDetailId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[bankTransferDetail].[bankTransferDetailId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "BANKTRANSFERDETAIL.BANKTRANSFERDETAILID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'banktransferdetail',
                'company',
                'banktransfer',
                'bank',
                'country',
                'chartofaccount'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'staff',
                    'banktransferdetail',
                    'company',
                    'banktransfer',
                    'bank',
                    'country',
                    'chartofaccount'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'STAFF',
                        'BANKTRANSFERDETAIL',
                        'COMPANY',
                        'BANKTRANSFER',
                        'BANK',
                        'COUNTRY',
                        'CHARTOFACCOUNT'
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
        if (!($this->model->getBankTransferDetailId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 19;
            if ($this->model->getBankTransferDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getBankTransferDetailId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getBankTransferDetailId(0, 'single'));
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
                            $str .= "<tr id='" . $items[$j]['bankTransferDetailId'] . "'>";
                            $str .= "<td vAlign=\"center\"><div align=\"center\">" . ($counter) . "</div>
		</td>";
                            $str .= "<td><div class='btn-group'>";
                            $str .= "<input type=\"hidden\" name='bankTransferDetailId[]'     id='bankTransferDetailId" . $items[$j]['bankTransferDetailId'] . "'  value='" . $items[$j]['bankTransferDetailId'] . "'>";
                            $str .= "<input type=\"hidden\" name='bankTransferId[]'
                    id='bankTransferDetailId" . $items[$j]['bankTransferId'] . "'
                        value='" . $items[$j]['bankTransferId'] . "'>";
                            $str .= "<a class=' btn-warning btn-xs' title='Edit' onClick=showFormUpdateDetail('" . $this->getLeafId(
                                    ) . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken(
                                    ) . "','" . $items[$j]['bankTransferDetailId'] . "')><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>";
                            $str .= "<a class=' btn-danger btn-xs' title='Delete' onClick=showModalDeleteDetail('" . $items[$j]['bankTransferDetailId'] . "')><i class='glyphicontrash  glyphicon-white'></i></a><div id=miniInfoPanel" . $items[$j]['bankTransferDetailId'] . "></div></td>";
                            $str .= "<input type=\"hidden\" name='bankTransferId[]' id='bankTransferId" . $items[$j]['bankTransferDetailId'] . "' value='" . $items[$j]['bankTransferId'] . "'>";
                            $bankArray = $this->getBank();
                            $str .= "<td><div class='form-group col-md-12' id='bankId" . $items[$j]['bankTransferDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='bankId[]' id='bankId" . $items[$j]['bankTransferDetailId'] . "' class='chzn-select' onChange=removeMeErrorDetail('bankId" . $items[$j]['bankTransferDetailId'] . "') >";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($bankArray)) {
                                $totalRecord = intval(count($bankArray));
                                if ($totalRecord > 0) {
                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        if ($items[$j]['bankId'] == $bankArray[$i]['bankId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $bankArray[$i]['bankId'] . "' " . $selected . ">" . $bankArray[$i]['bankDescription'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $str .= "<input type=\"hidden\" name='countryId[]' id='countryId" . $items[$j]['bankTransferDetailId'] . "' value='" . $items[$j]['countryId'] . "'>";
                            $chartOfAccountArray = $this->getChartOfAccount();
                            $str .= "<td><div class='form-group col-md-12' id='chartOfAccountId" . $items[$j]['bankTransferDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='chartOfAccountId[]' id='chartOfAccountId" . $items[$j]['bankTransferDetailId'] . "' class='chzn-select' onChange=removeMeErrorDetail('chartOfAccountId" . $items[$j]['bankTransferDetailId'] . "') >";
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
                            $str .= "<td><input class='form-control'  type='text' name='bankTransferDetailAmount[]' id='bankTransferDetailAmount" . $items[$j]['bankTransferDetailId'] . "'   value='" . $items[$j]['bankTransferDetailAmount'] . "'></td>";
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
                    if ($this->model->getBankTransferDetailId(0, 'single')) {
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
                                                    'value', $this->model->getBankTransferDetailId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getBankTransferDetailId(0, 'single')
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
                                            'value', $this->model->getBankTransferDetailId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getBankTransferDetailId(0, 'single')
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
        if (!$this->model->getBankTransferId()) {
            $this->model->setBankTransferId($this->service->getBankTransferDefaultValue());
        }
        if (!$this->model->getBankId()) {
            $this->model->setBankId($this->service->getBankDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`bankTransferDetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBankTransferDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[bankTransferDetail] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBankTransferDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	BANKTRANSFERDETAIL 
           WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getBankTransferDetailId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `banktransferdetail` SET 
                       `bankTransferId` = '" . $this->model->getBankTransferId() . "',
                       `bankId` = '" . $this->model->getBankId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `journalNumber` = '" . $this->model->getJournalNumber() . "',
                       `bankTransferDetailAmount` = '" . $this->model->getBankTransferDetailAmount() . "',
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
               WHERE    `bankTransferDetailId`='" . $this->model->getBankTransferDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [bankTransferDetail] SET 
                       [bankTransferId] = '" . $this->model->getBankTransferId() . "',
                       [bankId] = '" . $this->model->getBankId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [journalNumber] = '" . $this->model->getJournalNumber() . "',
                       [bankTransferDetailAmount] = '" . $this->model->getBankTransferDetailAmount() . "',
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
                WHERE   [bankTransferDetailId]='" . $this->model->getBankTransferDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE BANKTRANSFERDETAIL SET
                        BANKTRANSFERID = '" . $this->model->getBankTransferId() . "',
                       BANKID = '" . $this->model->getBankId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber() . "',
                       BANKTRANSFERDETAILAMOUNT = '" . $this->model->getBankTransferDetailAmount() . "',
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
                WHERE  BANKTRANSFERDETAILID='" . $this->model->getBankTransferDetailId('0', 'single') . "'";
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
        $extra = $this->service->getTotalBankTransferDetail($this->model->getBankTransferId());
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $extra['totalDebit'] = $a->format($extra['totalDebit']);
            $extra['totalCredit'] = $a->format($extra['totalCredit']);
        }
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['updateRecordTextLabel'],
                    "time" => $time,
                    "totalDebit" => $extra['totalDebit'],
                    "totalCredit" => $extra['totalCredit'],
                    "trialBalance" => $extra['trialBalance']
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
           FROM 	`banktransferdetail` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getBankTransferDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[bankTransferDetail] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getBankTransferDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	BANKTRANSFERDETAIL 
           WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getBankTransferDetailId(0, 'single') . "' ";
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
               UPDATE  `banktransferdetail` 
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
               WHERE   `bankTransferDetailId`   =  '" . $this->model->getBankTransferDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [bankTransferDetail] 
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
               WHERE   [bankTransferDetailId]	=  '" . $this->model->getBankTransferDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  BANKTRANSFERDETAIL 
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
               WHERE   BANKTRANSFERDETAILID	=  '" . $this->model->getBankTransferDetailId(0, 'single') . "'";
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
        $extra = $this->service->getTotalBankTransferDetail($this->model->getBankTransferId());
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $extra['totalDebit'] = $a->format($extra['totalDebit']);
            $extra['totalCredit'] = $a->format($extra['totalCredit']);
        }
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['deleteRecordTextLabel'],
                    "time" => $time,
                    "totalDebit" => $extra['totalDebit'],
                    "totalCredit" => $extra['totalCredit'],
                    "trialBalance" => $extra['trialBalance']
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
           SELECT  `bankTransferDetailCode` 
           FROM    `banktransferdetail` 
           WHERE   `bankTransferDetailCode` 	= 	'" . $this->model->getBankTransferDetailCode() . "' 
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  [bankTransferDetailCode] 
           FROM    [bankTransferDetail] 
           WHERE   [bankTransferDetailCode] = 	'" . $this->model->getBankTransferDetailCode() . "' 
           AND     [isActive]  =   1 
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  BANKTRANSFERDETAILCODE as \"bankTransferDetailCode\" 
               FROM    BANKTRANSFERDETAIL 
               WHERE   BANKTRANSFERDETAILCODE	= 	'" . $this->model->getBankTransferDetailCode() . "' 
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
     * Return  BankTransfer
     * @return null|string
     */
    public function getBankTransfer() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBankTransfer();
    }

    /**
     * Return  Bank
     * @return null|string
     */
    public function getBank() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBank();
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
     * Return  ChartOfAccount
     * @return null|string
     */
    public function getChartOfAccount() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccount();
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
         FROM    `bankTransferDetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
            $sql .= "AND     `bankTransferId` = " . $this->model->getBankTransferId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [bankTransferDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            $sql .= "AND     [bankTransferId] = " . $this->model->getBankTransferId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      BANKTRANSFERDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
            $sql .= "AND     BANKTRANSFERID = " . $this->model->getBankTransferId() . " ";
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
                ->setSubject('bankTransferDetail')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/cashbook');
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
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['bankTransferIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['bankIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['journalNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['bankTransferDetailAmountLabel']);
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
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['bankTransferDescription']));
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['bankDescription']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['countryDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['chartOfAccountTitle']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['journalNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('H' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['bankTransferDetailAmount']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
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
                $filename = "bankTransferDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/cashbook/document/" . $folder . "/" . $filename;
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
                $filename = "bankTransferDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/cashbook/document/" . $folder . "/" . $filename;
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
                $filename = "bankTransferDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/cashbook/document/" . $folder . "/" . $filename;
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
                $filename = "bankTransferDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/cashbook/document/" . $folder . "/" . $filename;
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
        $bankTransferDetailObject = new BankTransferDetailClass ();
        if ($_POST['securityToken'] != $bankTransferDetailObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $bankTransferDetailObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $bankTransferDetailObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $bankTransferDetailObject->setLimit($_POST ['limit']);
        }
        $bankTransferDetailObject->setPageOutput($_POST['output']);
        $bankTransferDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $bankTransferDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $bankTransferDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $bankTransferDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $bankTransferDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$bankTransferDetailObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$bankTransferDetailObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $bankTransferDetailObject = new BankTransferDetailClass ();
    if ($_GET['securityToken'] != $bankTransferDetailObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $bankTransferDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $bankTransferDetailObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $bankTransferDetailObject->updateStatus();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $bankTransferDetailObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $bankTransferDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $bankTransferDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $bankTransferDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $bankTransferDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $bankTransferDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $bankTransferDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $bankTransferDetailObject->setServiceOutput('option');
        if (($_GET['filter'] == 'bankTransfer')) {
            $bankTransferDetailObject->getBankTransfer();
        }
        if (($_GET['filter'] == 'bank')) {
            $bankTransferDetailObject->getBank();
        }
        if (($_GET['filter'] == 'country')) {
            $bankTransferDetailObject->getCountry();
        }
        if (($_GET['filter'] == 'chartOfAccount')) {
            $bankTransferDetailObject->getChartOfAccount();
        }
    }
}
?>
