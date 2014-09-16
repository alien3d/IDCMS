<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceDetail\Controller;

use Core\ConfigClass;
use Core\Document\Trail\DocumentTrailClass;
use Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel;
use Core\Financial\AccountPayable\PurchaseInvoiceDetail\Service\PurchaseInvoiceDetailService;
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
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/model/purchaseInvoiceDetailModel.php");
require_once($newFakeDocumentRoot . "v3/financial/accountPayable/service/purchaseInvoiceDetailService.php");

/**
 * Class PurchaseInvoiceDetail
 * this is purchaseInvoiceDetail controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\AccountPayable\PurchaseInvoiceDetail\Controller
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceDetailClass extends ConfigClass {

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
     * @var \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public $model;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint;
    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Service\PurchaseInvoiceDetailService
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
        $this->setViewPath("./v3/financial/accountPayable/view/purchaseInvoiceDetail.php");
        $this->setControllerPath("./v3/financial/accountPayable/controller/purchaseInvoiceDetailController.php");
        $this->setServicePath("./v3/financial/accountPayable/service/purchaseInvoiceDetailService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new PurchaseInvoiceDetailModel();
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

        $this->service = new PurchaseInvoiceDetailService();
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
        if (!$this->model->getPurchaseInvoiceProjectId()) {
            $this->model->setPurchaseInvoiceProjectId($this->service->getPurchaseInvoiceProjectDefaultValue());
        }
        if (!$this->model->getPurchaseInvoiceId()) {
            $this->model->setPurchaseInvoiceId($this->service->getPurchaseInvoiceDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        //$this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `purchaseinvoicedetail` 
            (
                 `companyId`,
                 `purchaseInvoiceProjectId`,
                 `purchaseInvoiceId`,
                 `countryId`,
                 `businessPartnerId`,
                 `chartOfAccountId`,
                 `journalNumber`,
                 `purchaseInvoiceDetailPrincipalAmount`,
                 `purchaseInvoiceDetailInterestAmount`,
                 `purchaseInvoiceDetailAmount`,
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
                 '" . $this->model->getPurchaseInvoiceProjectId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getPurchaseInvoiceDetailPrincipalAmount() . "',
                 '" . $this->model->getPurchaseInvoiceDetailInterestAmount() . "',
                 '" . $this->model->getPurchaseInvoiceDetailAmount() . "',
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
            INSERT INTO [purchaseInvoiceDetail]
            (
                 [purchaseInvoiceDetailId],
                 [companyId],
                 [purchaseInvoiceProjectId],
                 [purchaseInvoiceId],
                 [countryId],
                 [businessPartnerId],
                 [chartOfAccountId],
                 [journalNumber],
                 [purchaseInvoiceDetailPrincipalAmount],
                 [purchaseInvoiceDetailInterestAmount],
                 [purchaseInvoiceDetailAmount],
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
                 '" . $this->model->getPurchaseInvoiceProjectId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getPurchaseInvoiceDetailPrincipalAmount() . "',
                 '" . $this->model->getPurchaseInvoiceDetailInterestAmount() . "',
                 '" . $this->model->getPurchaseInvoiceDetailAmount() . "',
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
            INSERT INTO PURCHASEINVOICEDETAIL
            (
                 COMPANYID,
                 PURCHASEINVOICEPROJECTID,
                 PURCHASEINVOICEID,
                 COUNTRYID,
                 BUSINESSPARTNERID,
                 CHARTOFACCOUNTID,
                 JOURNALNUMBER,
                 PURCHASEINVOICEDETAILPRINCIPALAMOUNT,
                 PURCHASEINVOICEDETAILINTERESTAMOUNT,
                 PURCHASEINVOICEDETAILAMOUNT,
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
                 '" . $this->model->getPurchaseInvoiceProjectId() . "',
                 '" . $this->model->getPurchaseInvoiceId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getChartOfAccountId() . "',
                 '" . $this->model->getJournalNumber() . "',
                 '" . $this->model->getPurchaseInvoiceDetailPrincipalAmount() . "',
                 '" . $this->model->getPurchaseInvoiceDetailInterestAmount() . "',
                 '" . $this->model->getPurchaseInvoiceDetailAmount() . "',
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
        $purchaseInvoiceDetailId = $this->q->lastInsertId('purchaseInvoiceDetail');
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        $extra = $this->service->getTotalPurchaseInvoiceDetail($this->model->getPurchaseInvoiceId());
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $extra['totalDebit'] = $a->format($extra['totalDebit']);
            $extra['totalCredit'] = $a->format($extra['totalCredit']);
        }
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "totalRecord" => $this->getTotalRecord(),
                    "purchaseInvoiceDetailId" => $purchaseInvoiceDetailId,
                    "time" => $time,
                    "totalDebit" => $extra['totalDebit'],
                    "totalCredit" => $extra['totalCredit'],
                    "trialBalance" => $extra['trialBalance']
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
         FROM    `purchaseinvoicedetail`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
            $sql .= "AND     `purchaseInvoiceId` = " . $this->model->getPurchaseInvoiceId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [purchaseInvoiceDetail]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
            $sql .= "AND     [purchaseInvoiceId] = " . $this->model->getPurchaseInvoiceId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      PURCHASEINVOICEDETAIL
         WHERE     ISACTIVE    =   1
         AND       COMPANYID   =   " . $this->getCompanyId() . " ";
            $sql .= "AND     PURCHASEINVOICEID = " . $this->model->getPurchaseInvoiceId() . " ";
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
                            " `purchaseinvoicedetail`.`isActive` = 1  AND `purchaseinvoicedetail`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [purchaseInvoiceDetail].[isActive] = 1 AND [purchaseInvoiceDetail].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " PURCHASEINVOICEDETAIL.ISACTIVE = 1  AND PURCHASEINVOICEDETAIL.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                "   `purchaseinvoicedetail`.`companyId`='" . $this->getCompanyId() . "'	"
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [purchaseInvoiceDetail].[companyId]='" . $this->getCompanyId() . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " PURCHASEINVOICEDETAIL.COMPANYID='" . $this->getCompanyId() . "' "
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
       SELECT                    `purchaseinvoicedetail`.`purchaseInvoiceDetailId`,
                    `company`.`companyDescription`,
                    `purchaseinvoicedetail`.`companyId`,
                    `purchaseinvoiceproject`.`purchaseInvoiceProjectDescription`,
                    `purchaseinvoicedetail`.`purchaseInvoiceProjectId`,
                    `purchaseinvoice`.`purchaseInvoiceDescription`,
                    `purchaseinvoicedetail`.`purchaseInvoiceId`,
                    `country`.`countryDescription`,
                    `purchaseinvoicedetail`.`countryId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `purchaseinvoicedetail`.`businessPartnerId`,
                    `chartofaccount`.`chartOfAccountTitle`,
                    `purchaseinvoicedetail`.`chartOfAccountId`,
                    `purchaseinvoicedetail`.`journalNumber`,
                    `purchaseinvoicedetail`.`purchaseInvoiceDetailPrincipalAmount`,
                    `purchaseinvoicedetail`.`purchaseInvoiceDetailInterestAmount`,
                    `purchaseinvoicedetail`.`purchaseInvoiceDetailAmount`,
                    `purchaseinvoicedetail`.`isDefault`,
                    `purchaseinvoicedetail`.`isNew`,
                    `purchaseinvoicedetail`.`isDraft`,
                    `purchaseinvoicedetail`.`isUpdate`,
                    `purchaseinvoicedetail`.`isDelete`,
                    `purchaseinvoicedetail`.`isActive`,
                    `purchaseinvoicedetail`.`isApproved`,
                    `purchaseinvoicedetail`.`isReview`,
                    `purchaseinvoicedetail`.`isPost`,
                    `purchaseinvoicedetail`.`executeBy`,
                    `purchaseinvoicedetail`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `purchaseinvoicedetail`
		  JOIN      `staff`
		  ON        `purchaseinvoicedetail`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `purchaseinvoicedetail`.`companyId`
	JOIN	`purchaseinvoiceproject`
	ON		`purchaseinvoiceproject`.`purchaseInvoiceProjectId` = `purchaseinvoicedetail`.`purchaseInvoiceProjectId`
	JOIN	`purchaseinvoice`
	ON		`purchaseinvoice`.`purchaseInvoiceId` = `purchaseinvoicedetail`.`purchaseInvoiceId`
	JOIN	`country`
	ON		`country`.`countryId` = `purchaseinvoicedetail`.`countryId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `purchaseinvoicedetail`.`businessPartnerId`
	JOIN	`chartofaccount`
	ON		`chartofaccount`.`chartOfAccountId` = `purchaseinvoicedetail`.`chartOfAccountId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPurchaseInvoiceDetailId(0, 'single')) {
                $sql .= " AND `purchaseinvoicedetail`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "'";
            }
            if ($this->model->getPurchaseInvoiceProjectId()) {
                $sql .= " AND `purchaseinvoicedetail`.`purchaseInvoiceProjectId`='" . $this->model->getPurchaseInvoiceProjectId(
                        ) . "'";
            }
            if ($this->model->getPurchaseInvoiceId()) {
                $sql .= " AND `purchaseinvoicedetail`.`purchaseInvoiceId`='" . $this->model->getPurchaseInvoiceId(
                        ) . "'";
            }
            if ($this->model->getCountryId()) {
                $sql .= " AND `purchaseinvoicedetail`.`countryId`='" . $this->model->getCountryId() . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `purchaseinvoicedetail`.`businessPartnerId`='" . $this->model->getBusinessPartnerId(
                        ) . "'";
            }
            if ($this->model->getChartOfAccountId()) {
                $sql .= " AND `purchaseinvoicedetail`.`chartOfAccountId`='" . $this->model->getChartOfAccountId() . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [purchaseInvoiceDetail].[purchaseInvoiceDetailId],
                    [company].[companyDescription],
                    [purchaseInvoiceDetail].[companyId],
                    [purchaseInvoiceProject].[purchaseInvoiceProjectDescription],
                    [purchaseInvoiceDetail].[purchaseInvoiceProjectId],
                    [purchaseInvoice].[purchaseInvoiceDescription],
                    [purchaseInvoiceDetail].[purchaseInvoiceId],
                    [country].[countryDescription],
                    [purchaseInvoiceDetail].[countryId],
                    [businessPartner].[businessPartnerCompany],
                    [purchaseInvoiceDetail].[businessPartnerId],
                    [chartOfAccount].[chartOfAccountTitle],
                    [purchaseInvoiceDetail].[chartOfAccountId],
                    [purchaseInvoiceDetail].[journalNumber],
                    [purchaseInvoiceDetail].[purchaseInvoiceDetailPrincipalAmount],
                    [purchaseInvoiceDetail].[purchaseInvoiceDetailInterestAmount],
                    [purchaseInvoiceDetail].[purchaseInvoiceDetailAmount],
                    [purchaseInvoiceDetail].[isDefault],
                    [purchaseInvoiceDetail].[isNew],
                    [purchaseInvoiceDetail].[isDraft],
                    [purchaseInvoiceDetail].[isUpdate],
                    [purchaseInvoiceDetail].[isDelete],
                    [purchaseInvoiceDetail].[isActive],
                    [purchaseInvoiceDetail].[isApproved],
                    [purchaseInvoiceDetail].[isReview],
                    [purchaseInvoiceDetail].[isPost],
                    [purchaseInvoiceDetail].[executeBy],
                    [purchaseInvoiceDetail].[executeTime],
                    [staff].[staffName]
		  FROM 	[purchaseInvoiceDetail]
		  JOIN	[staff]
		  ON	[purchaseInvoiceDetail].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [purchaseInvoiceDetail].[companyId]
	JOIN	[purchaseInvoiceProject]
	ON		[purchaseInvoiceProject].[purchaseInvoiceProjectId] = [purchaseInvoiceDetail].[purchaseInvoiceProjectId]
	JOIN	[purchaseInvoice]
	ON		[purchaseInvoice].[purchaseInvoiceId] = [purchaseInvoiceDetail].[purchaseInvoiceId]
	JOIN	[country]
	ON		[country].[countryId] = [purchaseInvoiceDetail].[countryId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [purchaseInvoiceDetail].[businessPartnerId]
	JOIN	[chartOfAccount]
	ON		[chartOfAccount].[chartOfAccountId] = [purchaseInvoiceDetail].[chartOfAccountId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getPurchaseInvoiceDetailId(0, 'single')) {
                    $sql .= " AND [purchaseInvoiceDetail].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "'";
                }
                if ($this->model->getPurchaseInvoiceProjectId()) {
                    $sql .= " AND [purchaseInvoiceDetail].[purchaseInvoiceProjectId]='" . $this->model->getPurchaseInvoiceProjectId(
                            ) . "'";
                }
                if ($this->model->getPurchaseInvoiceId()) {
                    $sql .= " AND [purchaseInvoiceDetail].[purchaseInvoiceId]='" . $this->model->getPurchaseInvoiceId(
                            ) . "'";
                }
                if ($this->model->getCountryId()) {
                    $sql .= " AND [purchaseInvoiceDetail].[countryId]='" . $this->model->getCountryId() . "'";
                }
                if ($this->model->getBusinessPartnerId()) {
                    $sql .= " AND [purchaseInvoiceDetail].[businessPartnerId]='" . $this->model->getBusinessPartnerId(
                            ) . "'";
                }
                if ($this->model->getChartOfAccountId()) {
                    $sql .= " AND [purchaseInvoiceDetail].[chartOfAccountId]='" . $this->model->getChartOfAccountId(
                            ) . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    PURCHASEINVOICEDETAIL.PURCHASEINVOICEDETAILID AS \"purchaseInvoiceDetailId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    PURCHASEINVOICEDETAIL.COMPANYID AS \"companyId\",
                    PURCHASEINVOICEPROJECT.PURCHASEINVOICEPROJECTDESCRIPTION AS  \"purchaseInvoiceProjectDescription\",
                    PURCHASEINVOICEDETAIL.PURCHASEINVOICEPROJECTID AS \"purchaseInvoiceProjectId\",
                    PURCHASEINVOICE.PURCHASEINVOICEDESCRIPTION AS  \"purchaseInvoiceDescription\",
                    PURCHASEINVOICEDETAIL.PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    PURCHASEINVOICEDETAIL.COUNTRYID AS \"countryId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    PURCHASEINVOICEDETAIL.BUSINESSPARTNERID AS \"businessPartnerId\",
                    CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS  \"chartOfAccountTitle\",
                    PURCHASEINVOICEDETAIL.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                    PURCHASEINVOICEDETAIL.JOURNALNUMBER AS \"journalNumber\",
                    PURCHASEINVOICEDETAIL.PURCHASEINVOICEDETAILPRINCIPALAMOUNT AS \"purchaseInvoiceDetailPrincipalAmount\",
                    PURCHASEINVOICEDETAIL.PURCHASEINVOICEDETAILINTERESTAMOUNT AS \"purchaseInvoiceDetailInterestAmount\",
                    PURCHASEINVOICEDETAIL.PURCHASEINVOICEDETAILAMOUNT AS \"purchaseInvoiceDetailAmount\",
                    PURCHASEINVOICEDETAIL.ISDEFAULT AS \"isDefault\",
                    PURCHASEINVOICEDETAIL.ISNEW AS \"isNew\",
                    PURCHASEINVOICEDETAIL.ISDRAFT AS \"isDraft\",
                    PURCHASEINVOICEDETAIL.ISUPDATE AS \"isUpdate\",
                    PURCHASEINVOICEDETAIL.ISDELETE AS \"isDelete\",
                    PURCHASEINVOICEDETAIL.ISACTIVE AS \"isActive\",
                    PURCHASEINVOICEDETAIL.ISAPPROVED AS \"isApproved\",
                    PURCHASEINVOICEDETAIL.ISREVIEW AS \"isReview\",
                    PURCHASEINVOICEDETAIL.ISPOST AS \"isPost\",
                    PURCHASEINVOICEDETAIL.EXECUTEBY AS \"executeBy\",
                    PURCHASEINVOICEDETAIL.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	PURCHASEINVOICEDETAIL
		  JOIN	STAFF
		  ON	PURCHASEINVOICEDETAIL.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = PURCHASEINVOICEDETAIL.COMPANYID
	JOIN	PURCHASEINVOICEPROJECT
	ON		PURCHASEINVOICEPROJECT.PURCHASEINVOICEPROJECTID = PURCHASEINVOICEDETAIL.PURCHASEINVOICEPROJECTID
	JOIN	PURCHASEINVOICE
	ON		PURCHASEINVOICE.PURCHASEINVOICEID = PURCHASEINVOICEDETAIL.PURCHASEINVOICEID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = PURCHASEINVOICEDETAIL.COUNTRYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = PURCHASEINVOICEDETAIL.BUSINESSPARTNERID
	JOIN	CHARTOFACCOUNT
	ON		CHARTOFACCOUNT.CHARTOFACCOUNTID = PURCHASEINVOICEDETAIL.CHARTOFACCOUNTID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getPurchaseInvoiceDetailId(0, 'single')) {
                        $sql .= " AND PURCHASEINVOICEDETAIL. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "'";
                    }
                    if ($this->model->getPurchaseInvoiceProjectId()) {
                        $sql .= " AND PURCHASEINVOICEDETAIL.PURCHASEINVOICEPROJECTID='" . $this->model->getPurchaseInvoiceProjectId(
                                ) . "'";
                    }
                    if ($this->model->getPurchaseInvoiceId()) {
                        $sql .= " AND PURCHASEINVOICEDETAIL.PURCHASEINVOICEID='" . $this->model->getPurchaseInvoiceId(
                                ) . "'";
                    }
                    if ($this->model->getCountryId()) {
                        $sql .= " AND PURCHASEINVOICEDETAIL.COUNTRYID='" . $this->model->getCountryId() . "'";
                    }
                    if ($this->model->getBusinessPartnerId()) {
                        $sql .= " AND PURCHASEINVOICEDETAIL.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId(
                                ) . "'";
                    }
                    if ($this->model->getChartOfAccountId()) {
                        $sql .= " AND PURCHASEINVOICEDETAIL.CHARTOFACCOUNTID='" . $this->model->getChartOfAccountId(
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
                $sql .= " AND `purchaseinvoicedetail`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= " AND [purchaseInvoiceDetail].[" . $this->model->getFilterCharacter(
                            ) . "] like '" . $this->getCharacterQuery() . "%'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= " AND Initcap(PURCHASEINVOICEDETAIL." . strtoupper(
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
                        'purchaseinvoicedetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql .= $this->q->dateFilter(
                            'purchaseInvoiceDetail', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                    );
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql .= $this->q->dateFilter(
                                'PURCHASEINVOICEDETAIL', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`purchaseinvoicedetail`.`purchaseInvoiceDetailId`",
                "`staff`.`staffPassword`"
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $filterArray = array(
                    "[purchaseInvoiceDetail].[purchaseInvoiceDetailId]",
                    "[staff].[staffPassword]"
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $filterArray = array(
                        "PURCHASEINVOICEDETAIL.PURCHASEINVOICEDETAILID",
                        "STAFF.STAFFPASSWORD"
                    );
                }
            }
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'purchaseinvoicedetail',
                'company',
                'purchaseinvoiceproject',
                'purchaseinvoice',
                'country',
                'businesspartner',
                'chartofaccount'
            );
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $tableArray = array(
                    'staff',
                    'purchaseinvoicedetail',
                    'company',
                    'purchaseinvoiceproject',
                    'purchaseinvoice',
                    'country',
                    'businesspartner',
                    'chartofaccount'
                );
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $tableArray = array(
                        'STAFF',
                        'PURCHASEINVOICEDETAIL',
                        'COMPANY',
                        'PURCHASEINVOICEPROJECT',
                        'PURCHASEINVOICE',
                        'COUNTRY',
                        'BUSINESSPARTNER',
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
        if (!($this->model->getPurchaseInvoiceDetailId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 22;
            if ($this->model->getPurchaseInvoiceDetailId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord(
                        'value', $this->model->getPurchaseInvoiceDetailId(0, 'single')
                );
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getPurchaseInvoiceDetailId(0, 'single'));
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
                $totalDebit = 0;
                $totalCredit = 0;
                if (is_array($items)) {
                    $this->setServiceOutput('html');
                    $totalRecordDetail = intval(count($items));
                    if ($totalRecordDetail > 0) {
                        $counter = 0;
                        $totalDebit = 0;
                        $totalCredit = 0;
                        for ($j = 0; $j < $totalRecordDetail; $j++) {
                            $counter++;
                            $str .= "<tr id='" . $items[$j]['purchaseInvoiceDetailId'] . "'>";
                            $str .= "<td vAlign=\"center\"><div align=\"center\">" . ($counter) . "</div>
		</td>";
                            $str .= "<td><div class='btn-group'>";
                            $str .= "<input type=\"hidden\" name='purchaseInvoiceDetailId[]'     id='purchaseInvoiceDetailId" . $items[$j]['purchaseInvoiceDetailId'] . "'  value='" . $items[$j]['purchaseInvoiceDetailId'] . "'>";
                            $str .= "<input type=\"hidden\" name='purchaseInvoiceId[]'
                    id='purchaseInvoiceDetailId" . $items[$j]['purchaseInvoiceId'] . "'
                        value='" . $items[$j]['purchaseInvoiceId'] . "'>";
                            $str .= "<a class=' btn-warning btn-xs' title='Edit' onClick=showFormUpdateDetail('" . $this->getLeafId(
                                    ) . "','" . $this->getControllerPath() . "','" . $this->getSecurityToken(
                                    ) . "','" . $items[$j]['purchaseInvoiceDetailId'] . "')><i class='glyphicon glyphicon-edit glyphicon-white'></i></a>";
                            $str .= "<a class=' btn-danger btn-xs' title='Delete' onClick=showModalDeleteDetail('" . $items[$j]['purchaseInvoiceDetailId'] . "')><i class='glyphicontrash  glyphicon-white'></i></a><div id=miniInfoPanel" . $items[$j]['purchaseInvoiceDetailId'] . "></div></td>";
                            $chartOfAccountArray = $this->getChartOfAccount();
                            $str .= "<td><div class='form-group col-md-12' id='chartOfAccountId" . $items[$j]['purchaseInvoiceDetailId'] . "Detail'>";
                            $str .= "<div class='input-group'><select name='chartOfAccountId[]' id='chartOfAccountId" . $items[$j]['purchaseInvoiceDetailId'] . "' class='chzn-select' onChange=removeMeErrorDetail('chartOfAccountId" . $items[$j]['purchaseInvoiceDetailId'] . "')  style=\"width:400px\">";
                            $str .= "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>";
                            if (is_array($chartOfAccountArray)) {
                                $totalRecord = intval(count($chartOfAccountArray));
                                $currentChartOfAccountTypeDescription = null;
                                if ($totalRecord > 0) {
                                    for ($i = 0; $i < $totalRecord; $i++) {
                                        if ($items[$j]['chartOfAccountId'] == $chartOfAccountArray[$i]['chartOfAccountId']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = null;
                                        }
                                        $str .= "<option value='" . $chartOfAccountArray[$i]['chartOfAccountId'] . "' " . $selected . ">" . $chartOfAccountArray[$i]['chartOfAccountNumber'] . " - " . $chartOfAccountArray[$i]['chartOfAccountTitle'] . "</option>";
                                    }
                                } else {
                                    $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                                }
                            } else {
                                $str .= "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
                            }
                            $str .= "</select></div></div>";
                            $str .= "</td>";
                            $str .= "<td><input class='form-control'  type='text' name='purchaseInvoiceDetailPrincipalAmount[]' id='purchaseInvoiceDetailPrincipalAmount" . $items[$j]['purchaseInvoiceDetailId'] . "'   value='" . $items[$j]['purchaseInvoiceDetailPrincipalAmount'] . "'></td>";
                            $str .= "<td><input class='form-control'  type='text' name='purchaseInvoiceDetailInterestAmount[]' id='purchaseInvoiceDetailInterestAmount" . $items[$j]['purchaseInvoiceDetailId'] . "'   value='" . $items[$j]['purchaseInvoiceDetailInterestAmount'] . "'></td>";
                            $str .= "<td><input class='form-control'  type='text' name='purchaseInvoiceDetailAmount[]' id='purchaseInvoiceDetailAmount" . $items[$j]['purchaseInvoiceDetailId'] . "'   value='" . $items[$j]['purchaseInvoiceDetailAmount'] . "'></td>";
                            $debit = 0;
                            $credit = 0;
                            $x = 0;
                            $y = 0;
                            $d = $items[$j]['purchaseInvoiceDetailAmount'];
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

                            $str .= "<td vAlign=\"middle\" align=\"right\"><div id=\"debit_" . $items[$j]['purchaseInvoiceDetailId'] . "\" align=\"right\">" . $debit . "</div></td>";
                            $str .= "<td vAlign=\"middle\" align=\"right\"><div id=\"credit_" . $items[$j]['purchaseInvoiceDetailId'] . "\" align=\"right\">" . $credit . "</div></td>\n";
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
                $str .= "<td colspan=\"4\">&nbsp;</td>\n";
                $str .= "<td align=\"right\"><div id=\"totalDebit\" align=\"right\">" . $totalDebit . "</div></td>\n";
                $str .= "<td align=\"right\"><div id=\"totalCredit\" align=\"right\">" . $totalCredit . "</div></td>\n";
                $str .= "</tr>";
                echo json_encode(array('success' => true, 'tableData' => $str));
                exit();
            } else {
                if ($this->getPageOutput() == 'json') {
                    if ($this->model->getPurchaseInvoiceDetailId(0, 'single')) {
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
                                                    'value', $this->model->getPurchaseInvoiceDetailId(0, 'single')
                                            ),
                                            'nextRecord' => $this->nextRecord(
                                                    'value', $this->model->getPurchaseInvoiceDetailId(0, 'single')
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
                                            'value', $this->model->getPurchaseInvoiceDetailId(0, 'single')
                                    ),
                                    'nextRecord' => $this->recordSet->nextRecord(
                                            'value', $this->model->getPurchaseInvoiceDetailId(0, 'single')
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
        return $items;
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

    /**
     * Set Service
     * @param string $service . Reset service either option,html,table
     * @return mixed
     */
    function setService($service) {
        return $this->service->setServiceOutput($service);
    }

    /**
     * Return  PurchaseInvoiceProject
     * @return null|string
     */
    public function getPurchaseInvoiceProject() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPurchaseInvoiceProject();
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
        if (!$this->model->getPurchaseInvoiceProjectId()) {
            $this->model->setPurchaseInvoiceProjectId($this->service->getPurchaseInvoiceProjectDefaultValue());
        }
        if (!$this->model->getPurchaseInvoiceId()) {
            $this->model->setPurchaseInvoiceId($this->service->getPurchaseInvoiceDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getChartOfAccountId()) {
            $this->model->setChartOfAccountId($this->service->getChartOfAccountDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`purchaseinvoicedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseInvoiceDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[purchaseInvoiceDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseInvoiceDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	PURCHASEINVOICEDETAIL
           WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `purchaseinvoicedetail` SET
                       `purchaseInvoiceProjectId` = '" . $this->model->getPurchaseInvoiceProjectId() . "',
                       `purchaseInvoiceId` = '" . $this->model->getPurchaseInvoiceId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `chartOfAccountId` = '" . $this->model->getChartOfAccountId() . "',
                       `journalNumber` = '" . $this->model->getJournalNumber() . "',
                       `purchaseInvoiceDetailPrincipalAmount` = '" . $this->model->getPurchaseInvoiceDetailPrincipalAmount(
                        ) . "',
                       `purchaseInvoiceDetailInterestAmount` = '" . $this->model->getPurchaseInvoiceDetailInterestAmount(
                        ) . "',
                       `purchaseInvoiceDetailAmount` = '" . $this->model->getPurchaseInvoiceDetailAmount() . "',
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
               WHERE    `purchaseInvoiceDetailId`='" . $this->model->getPurchaseInvoiceDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [purchaseInvoiceDetail] SET
                       [purchaseInvoiceProjectId] = '" . $this->model->getPurchaseInvoiceProjectId() . "',
                       [purchaseInvoiceId] = '" . $this->model->getPurchaseInvoiceId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [chartOfAccountId] = '" . $this->model->getChartOfAccountId() . "',
                       [journalNumber] = '" . $this->model->getJournalNumber() . "',
                       [purchaseInvoiceDetailPrincipalAmount] = '" . $this->model->getPurchaseInvoiceDetailPrincipalAmount(
                        ) . "',
                       [purchaseInvoiceDetailInterestAmount] = '" . $this->model->getPurchaseInvoiceDetailInterestAmount(
                        ) . "',
                       [purchaseInvoiceDetailAmount] = '" . $this->model->getPurchaseInvoiceDetailAmount() . "',
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
                WHERE   [purchaseInvoiceDetailId]='" . $this->model->getPurchaseInvoiceDetailId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE PURCHASEINVOICEDETAIL SET
                        PURCHASEINVOICEPROJECTID = '" . $this->model->getPurchaseInvoiceProjectId() . "',
                       PURCHASEINVOICEID = '" . $this->model->getPurchaseInvoiceId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       CHARTOFACCOUNTID = '" . $this->model->getChartOfAccountId() . "',
                       JOURNALNUMBER = '" . $this->model->getJournalNumber() . "',
                       PURCHASEINVOICEDETAILPRINCIPALAMOUNT = '" . $this->model->getPurchaseInvoiceDetailPrincipalAmount(
                        ) . "',
                       PURCHASEINVOICEDETAILINTERESTAMOUNT = '" . $this->model->getPurchaseInvoiceDetailInterestAmount(
                        ) . "',
                       PURCHASEINVOICEDETAILAMOUNT = '" . $this->model->getPurchaseInvoiceDetailAmount() . "',
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
                WHERE  PURCHASEINVOICEDETAILID='" . $this->model->getPurchaseInvoiceDetailId('0', 'single') . "'";
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
        $extra = $this->service->getTotalPurchaseInvoiceDetail($this->model->getPurchaseInvoiceId());
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $extra['totalDebit'] = $a->format($extra['totalDebit']);
            $extra['totalCredit'] = $a->format($extra['totalCredit']);
        }
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
           FROM 	`purchaseinvoicedetail`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPurchaseInvoiceDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[purchaseInvoiceDetail]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPurchaseInvoiceDetailId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	PURCHASEINVOICEDETAIL
           WHERE  	" . strtoupper(
                            $this->model->getPrimaryKeyName()
                    ) . " = '" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "' ";
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
               UPDATE  `purchaseinvoicedetail`
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
               WHERE   `purchaseInvoiceDetailId`   =  '" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [purchaseInvoiceDetail]
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
               WHERE   [purchaseInvoiceDetailId]	=  '" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  PURCHASEINVOICEDETAIL
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
               WHERE   PURCHASEINVOICEDETAILID	=  '" . $this->model->getPurchaseInvoiceDetailId(0, 'single') . "'";
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
        $extra = $this->service->getTotalPurchaseInvoiceDetail($this->model->getPurchaseInvoiceId());
        if (class_exists('NumberFormatter')) {
            $a = new \NumberFormatter($this->systemFormatArray['languageCode'], \NumberFormatter::CURRENCY);
            $extra['totalDebit'] = $a->format($extra['totalDebit']);
            $extra['totalCredit'] = $a->format($extra['totalCredit']);
        }
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
        
    }

    /**
     * Return  PurchaseInvoice
     * @return null|string
     */
    public function getPurchaseInvoice() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPurchaseInvoice();
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
     * Return  BusinessPartner
     * @return null|string
     */
    public function getBusinessPartner() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartner();
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
                ->setSubject('purchaseInvoiceDetail')
                ->setDescription('Generated by PhpExcel an Idcms Generator')
                ->setKeywords('office 2007 openxml php')
                ->setCategory('financial/accountPayable');
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
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('M2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:M2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['purchaseInvoiceProjectIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['purchaseInvoiceIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['chartOfAccountIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['journalNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue(
                'I3', $this->translate['purchaseInvoiceDetailPrincipalAmountLabel']
        );
        $this->excel->getActiveSheet()->setCellValue(
                'J3', $this->translate['purchaseInvoiceDetailInterestAmountLabel']
        );
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['purchaseInvoiceDetailAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['purchaseInvoiceProjectDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'D' . $loopRow, strip_tags($row ['purchaseInvoiceDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['countryDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['chartOfAccountTitle']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['journalNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('I' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'I' . $loopRow, strip_tags($row ['purchaseInvoiceDetailPrincipalAmount'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'J' . $loopRow, strip_tags($row ['purchaseInvoiceDetailInterestAmount'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue(
                    'K' . $loopRow, strip_tags($row ['purchaseInvoiceDetailAmount'])
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('M' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
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
                $filename = "purchaseInvoiceDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
                $filename = "purchaseInvoiceDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
                $filename = "purchaseInvoiceDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
                $filename = "purchaseInvoiceDetail" . rand(0, 10000000) . $extension;
                $path = $this->getFakeDocumentRoot(
                        ) . "v3/financial/accountPayable/document/" . $folder . "/" . $filename;
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
        $purchaseInvoiceDetailObject = new PurchaseInvoiceDetailClass ();
        if ($_POST['securityToken'] != $purchaseInvoiceDetailObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $purchaseInvoiceDetailObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $purchaseInvoiceDetailObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $purchaseInvoiceDetailObject->setLimit($_POST ['limit']);
        }
        $purchaseInvoiceDetailObject->setPageOutput($_POST['output']);
        $purchaseInvoiceDetailObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $purchaseInvoiceDetailObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $purchaseInvoiceDetailObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $purchaseInvoiceDetailObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $purchaseInvoiceDetailObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            //	$purchaseInvoiceDetailObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$purchaseInvoiceDetailObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $purchaseInvoiceDetailObject = new PurchaseInvoiceDetailClass ();
    if ($_GET['securityToken'] != $purchaseInvoiceDetailObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $purchaseInvoiceDetailObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $purchaseInvoiceDetailObject->execute();
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $purchaseInvoiceDetailObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $purchaseInvoiceDetailObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $purchaseInvoiceDetailObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $purchaseInvoiceDetailObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $purchaseInvoiceDetailObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $purchaseInvoiceDetailObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $purchaseInvoiceDetailObject->setServiceOutput('option');
        if (($_GET['filter'] == 'purchaseInvoiceProject')) {
            $purchaseInvoiceDetailObject->getPurchaseInvoiceProject();
        }
        if (($_GET['filter'] == 'purchaseInvoice')) {
            $purchaseInvoiceDetailObject->getPurchaseInvoice();
        }
        if (($_GET['filter'] == 'country')) {
            $purchaseInvoiceDetailObject->getCountry();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $purchaseInvoiceDetailObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'chartOfAccount')) {
            $purchaseInvoiceDetailObject->getChartOfAccount();
        }
    }
}
?>
