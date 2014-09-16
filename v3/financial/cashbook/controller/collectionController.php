<?php

namespace Core\Financial\Cashbook\Collection\Controller;

use Core\ConfigClass;
use Core\Financial\Cashbook\Collection\Model\CollectionModel;
use Core\Financial\Cashbook\Collection\Service\CollectionService;
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
require_once($newFakeDocumentRoot . "v3/financial/cashbook/model/collectionModel.php");
require_once($newFakeDocumentRoot . "v3/financial/cashbook/service/collectionService.php");

/**
 * Class Collection
 * this is collection controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\Cashbook\Collection\Controller
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CollectionClass extends ConfigClass {

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
     * @var \Core\Financial\Cashbook\Collection\Model\CollectionModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\Cashbook\Collection\Service\CollectionService
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

    const INVOICE_CODE = 'ARINV';

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
        $this->setViewPath("./v3/financial/cashbook/view/collection.php");
        $this->setControllerPath("./v3/financial/cashbook/controller/collectionController.php");
        $this->setServicePath("./v3/financial/cashbook/service/collectionService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new CollectionModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/cashbook/view/" . $this->model->getFrom());
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

        $this->setReportTitle(
                $applicationNative . " :: " . $moduleNative . " :: " . $folderNative . " :: " . $leafNative
        );

        $this->service = new CollectionService();
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
        if (!$this->model->getCollectionTypeId()) {
            $this->model->setCollectionTypeId($this->service->getCollectionTypeDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getBankId()) {
            $this->model->setBankId($this->service->getBankDefaultValue());
        }
        if (!$this->model->getPaymentTypeId()) {
            $this->model->setPaymentTypeId($this->service->getPaymentTypeDefaultValue());
        }
        if (!$this->model->getDocumentNumber()) {
            $this->model->setDocumentNumber($this->getDocumentNumber());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `collection` 
            (
                 `companyId`,
                 `collectionTypeId`,
                 `businessPartnerId`,
                 `countryId`,
                 `bankId`,
                 `paymentTypeId`,
                 `documentNumber`,
                 `referenceNumber`,
                 `chequeNumber`,
                 `collectionAmount`,
                 `collectionDate`,
                 `collectionBankInSlipNumber`,
                 `collectionBankInSlipDate`,
                 `collectionDescription`,
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
                 '" . $this->model->getCollectionTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getPaymentTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getChequeNumber() . "',
                 '" . $this->model->getCollectionAmount() . "',
                 '" . $this->model->getCollectionDate() . "',
                 '" . $this->model->getCollectionBankInSlipNumber() . "',
                 '" . $this->model->getCollectionBankInSlipDate() . "',
                 '" . $this->model->getCollectionDescription() . "',
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
            INSERT INTO [collection]
            (
                 [collectionId],
                 [companyId],
                 [collectionTypeId],
                 [businessPartnerId],
                 [countryId],
                 [bankId],
                 [paymentTypeId],
                 [documentNumber],
                 [referenceNumber],
                 [chequeNumber],
                 [collectionAmount],
                 [collectionDate],
                 [collectionBankInSlipNumber],
                 [collectionBankInSlipDate],
                 [collectionDescription],
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
                 '" . $this->model->getCollectionTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getPaymentTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getChequeNumber() . "',
                 '" . $this->model->getCollectionAmount() . "',
                 '" . $this->model->getCollectionDate() . "',
                 '" . $this->model->getCollectionBankInSlipNumber() . "',
                 '" . $this->model->getCollectionBankInSlipDate() . "',
                 '" . $this->model->getCollectionDescription() . "',
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
            INSERT INTO COLLECTION
            (
                 COMPANYID,
                 COLLECTIONTYPEID,
                 BUSINESSPARTNERID,
                 COUNTRYID,
                 BANKID,
                 PAYMENTTYPEID,
                 DOCUMENTNUMBER,
                 REFERENCENUMBER,
                 CHEQUENUMBER,
                 COLLECTIONAMOUNT,
                 COLLECTIONDATE,
                 COLLECTIONBANKINSLIPNUMBER,
                 COLLECTIONBANKINSLIPDATE,
                 COLLECTIONDESCRIPTION,
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
                 '" . $this->model->getCollectionTypeId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getCountryId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getPaymentTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getChequeNumber() . "',
                 '" . $this->model->getCollectionAmount() . "',
                 '" . $this->model->getCollectionDate() . "',
                 '" . $this->model->getCollectionBankInSlipNumber() . "',
                 '" . $this->model->getCollectionBankInSlipDate() . "',
                 '" . $this->model->getCollectionDescription() . "',
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
        $collectionId = $this->q->lastInsertId('collection');
        $journalNumber = $this->getDocumentNumber('GLPT');
        switch ($this->model->getFrom()) {
            case 'collection.php':
                //this is auto allocation invoice->Reason user don't have time to think
                break;
            case 'collectionSimple.php':
                // user required to pick bank
                break;
            case 'collectionMobile.php':
                // credit bank/cash account
                $collectionDetailId = $this->service->setCollectionDetail(
                        $collectionId, $this->model->getBusinessPartnerId(), $this->service->getPettyCashDefaultAccount(), $this->model->getCollectionAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'collectionMobile.php', $this->model->getBusinessPartnerId(), $this->service->getPettyCashDefaultAccount(), $this->model->getDocumentNumber(), $journalNumber, $this->model->getCollectionDate(), $this->model->getCollectionAmount(), $this->model->getCollectionDescription(), 'CB', 'paymentVoucher', 'paymentVoucherDetail', $collectionId, $collectionDetailId
                );
                // debit creditor control account
                $collectionDetailId = $this->service->setCollectionDetail(
                        $collectionId, $this->model->getBusinessPartnerId(), $this->service->getDebtorDefaultAccount(), $this->model->getCollectionAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'collectionMobile.php', $this->model->getBusinessPartnerId(), $this->service->getDebtorDefaultAccount(), $this->model->getDocumentNumber(), $journalNumber, $this->model->getCollectionDate(), $this->model->getCollectionAmount() * -1, $this->model->getCollectionDescription(), 'CB', 'collection', 'collectionDetail', $collectionId, $collectionDetailId
                );

                // create and invoice
                $invoiceDocumentNumber = $this->getDocumentNumber(self::INVOICE_CODE);
                $invoiceId = $this->service->setInvoice(
                        $this->model->getBusinessPartnerId(), $invoiceDocumentNumber, $this->model->getCollectionDate(), $this->model->getCollectionDescription(), $this->model->getCollectionAmount()
                );
                //  debit creditor  control account
                $invoiceDetailId = $this->service->setInvoiceDetail(
                        $invoiceId, $this->model->getBusinessPartnerId(), $this->service->getDebtorDefaultAccount(), $this->model->getCollectionAmount(), $invoiceDocumentNumber, $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'collectionMobile.php', $this->model->getBusinessPartnerId(), $this->service->getDebtorDefaultAccount(), $invoiceDocumentNumber, $journalNumber, $this->model->getCollectionDate(), $this->model->getCollectionAmount(), $this->model->getCollectionDescription(), 'AR', 'invoice', 'invoiceDetail', $invoiceId, $invoiceDetailId
                );
                //  credit expenses account
                $invoiceDetailId = $this->service->setInvoiceDetail(
                        $invoiceId, $this->model->getBusinessPartnerId(), $this->service->getIncomeDefaultAccount(), $this->model->getCollectionAmount(), $invoiceDocumentNumber, $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'collectionMobile.php', $this->model->getBusinessPartnerId(), $this->service->getIncomeDefaultAccount(), $invoiceDocumentNumber, $journalNumber, $this->model->getCollectionDate(), $this->model->getCollectionAmount() * -1, $this->model->getCollectionDescription(), 'AR', 'invoice', 'invoiceDetail', $invoiceId, $invoiceDetailId
                );

                //allocation
                $this->service->setCollectionAllocation(
                        $collectionId, $invoiceId, $this->model->getBusinessPartnerId(), $this->model->getCollectionAmount()
                );

                break;

            case 'cashInvoice.php':
                // credit cash account / bank account
                //$this->service->setPaymentVoucherDetail($paymentVoucherId, $this->service->getBankChartOfaccount($this->model->getBankId()), $this->model->getCollectionAmount(),$this->model->getDocumentNumber(),$journalNumber);
                // debit expenses
                //$this->service->setPaymentVoucherDetail($paymentVoucherId, $this->service->model->getChartOfAccountId(), $this->model->getCollectionAmount(),$this->model->getDocumentNumber(),$journalNumber);

                break;
            case 'collectionDetail.php':
                break;
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
                    "collectionId" => $collectionId,
                    "time" => $time
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
                            " `collection`.`isActive` = 1  AND `collection`.`companyId`='" . $this->getCompanyId() . "' "
                    );
                } else  if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [collection].[isActive] = 1 AND [collection].[companyId]='" . $this->getCompanyId() . "' "
                        );
                    } else if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " COLLECTION.ISACTIVE = 1  AND COLLECTION.COMPANYID='" . $this->getCompanyId() . "'"
                            );
                }
            } else if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter("   `collection`.`companyId`='" . $this->getCompanyId() . "'	");
                    } else if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(" [collection].[companyId]='" . $this->getCompanyId() . "' ");
                        } else  if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(" COLLECTION.COMPANYID='" . $this->getCompanyId() . "' ");
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
       SELECT                    `collection`.`collectionId`,
                    `company`.`companyDescription`,
                    `collection`.`companyId`,
                    `collectiontype`.`collectionTypeDescription`,
                    `collection`.`collectionTypeId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `collection`.`businessPartnerId`,
                    `country`.`countryDescription`,
                    `collection`.`countryId`,
                    `bank`.`bankDescription`,
                    `collection`.`bankId`,
                    `paymenttype`.`paymentTypeDescription`,
                    `collection`.`paymentTypeId`,
                    `collection`.`documentNumber`,
                    `collection`.`referenceNumber`,
                    `collection`.`chequeNumber`,
                    `collection`.`collectionAmount`,
                    `collection`.`collectionDate`,
                    `collection`.`collectionBankInSlipNumber`,
                    `collection`.`collectionBankInSlipDate`,
                    `collection`.`collectionDescription`,
                    `collection`.`isDefault`,
                    `collection`.`isNew`,
                    `collection`.`isDraft`,
                    `collection`.`isUpdate`,
                    `collection`.`isDelete`,
                    `collection`.`isActive`,
                    `collection`.`isApproved`,
                    `collection`.`isReview`,
                    `collection`.`isPost`,
                    `collection`.`executeBy`,
                    `collection`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `collection`
		  JOIN      `staff`
		  ON        `collection`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `collection`.`companyId`
	JOIN	`collectiontype`
	ON		`collectiontype`.`collectionTypeId` = `collection`.`collectionTypeId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `collection`.`businessPartnerId`
	JOIN	`country`
	ON		`country`.`countryId` = `collection`.`countryId`
	JOIN	`bank`
	ON		`bank`.`bankId` = `collection`.`bankId`
	JOIN	`paymenttype`
	ON		`paymenttype`.`paymentTypeId` = `collection`.`paymentTypeId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getCollectionId(0, 'single')) {
                $sql .= " AND `collection`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getCollectionId(0, 'single') . "'";
            }
            if ($this->model->getCollectionTypeId()) {
                $sql .= " AND `collection`.`collectionTypeId`='" . $this->model->getCollectionTypeId() . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `collection`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getCountryId()) {
                $sql .= " AND `collection`.`countryId`='" . $this->model->getCountryId() . "'";
            }
            if ($this->model->getBankId()) {
                $sql .= " AND `collection`.`bankId`='" . $this->model->getBankId() . "'";
            }
            if ($this->model->getPaymentTypeId()) {
                $sql .= " AND `collection`.`paymentTypeId`='" . $this->model->getPaymentTypeId() . "'";
            }
            if ($this->model->getFrom() == 'collectionPost.php') {
                $sql .= "  AND   `collection`.`isBalance` =   1 AND `collection`.`isPost`=0";
            } else {
                if ($this->model->getFrom() == 'collectionHistory.php') {
                    $sql .= " AND `collection`.`isBalance`=1 AND `collection`.`isPost`=1";
                }
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [collection].[collectionId],
                    [company].[companyDescription],
                    [collection].[companyId],
                    [collectionType].[collectionTypeDescription],
                    [collection].[collectionTypeId],
                    [businessPartner].[businessPartnerCompany],
                    [collection].[businessPartnerId],
                    [country].[countryDescription],
                    [collection].[countryId],
                    [bank].[bankDescription],
                    [collection].[bankId],
                    [paymentType].[paymentTypeDescription],
                    [collection].[paymentTypeId],
                    [collection].[documentNumber],
                    [collection].[referenceNumber],
                    [collection].[chequeNumber],
                    [collection].[collectionAmount],
                    [collection].[collectionDate],
                    [collection].[collectionBankInSlipNumber],
                    [collection].[collectionBankInSlipDate],
                    [collection].[collectionDescription],
                    [collection].[isDefault],
                    [collection].[isNew],
                    [collection].[isDraft],
                    [collection].[isUpdate],
                    [collection].[isDelete],
                    [collection].[isActive],
                    [collection].[isApproved],
                    [collection].[isReview],
                    [collection].[isPost],
                    [collection].[executeBy],
                    [collection].[executeTime],
                    [staff].[staffName]
		  FROM 	[collection]
		  JOIN	[staff]
		  ON	[collection].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [collection].[companyId]
	JOIN	[collectionType]
	ON		[collectionType].[collectionTypeId] = [collection].[collectionTypeId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [collection].[businessPartnerId]
	JOIN	[country]
	ON		[country].[countryId] = [collection].[countryId]
	JOIN	[bank]
	ON		[bank].[bankId] = [collection].[bankId]
	JOIN	[paymentType]
	ON		[paymentType].[paymentTypeId] = [collection].[paymentTypeId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getCollectionId(0, 'single')) {
                    $sql .= " AND [collection].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getCollectionId(0, 'single') . "'";
                }
                if ($this->model->getCollectionTypeId()) {
                    $sql .= " AND [collection].[collectionTypeId]='" . $this->model->getCollectionTypeId() . "'";
                }
                if ($this->model->getBusinessPartnerId()) {
                    $sql .= " AND [collection].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
                }
                if ($this->model->getCountryId()) {
                    $sql .= " AND [collection].[countryId]='" . $this->model->getCountryId() . "'";
                }
                if ($this->model->getBankId()) {
                    $sql .= " AND [collection].[bankId]='" . $this->model->getBankId() . "'";
                }
                if ($this->model->getPaymentTypeId()) {
                    $sql .= " AND [collection].[paymentTypeId]='" . $this->model->getPaymentTypeId() . "'";
                }
                if ($this->model->getFrom() == 'collectionPost.php') {
                    $sql .= "  AND   [collection].[isBalance] =   1 AND [collection].[isPost]=0";
                } else {
                    if ($this->model->getFrom() == 'collectionHistory.php') {
                        $sql .= " AND [collection].[isBalance]=1 AND [collection].[isPost]=1";
                    }
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    COLLECTION.COLLECTIONID AS \"collectionId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    COLLECTION.COMPANYID AS \"companyId\",
                    COLLECTIONTYPE.COLLECTIONTYPEDESCRIPTION AS  \"collectionTypeDescription\",
                    COLLECTION.COLLECTIONTYPEID AS \"collectionTypeId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    COLLECTION.BUSINESSPARTNERID AS \"businessPartnerId\",
                    COUNTRY.COUNTRYDESCRIPTION AS  \"countryDescription\",
                    COLLECTION.COUNTRYID AS \"countryId\",
                    BANK.BANKDESCRIPTION AS  \"bankDescription\",
                    COLLECTION.BANKID AS \"bankId\",
                    PAYMENTTYPE.PAYMENTTYPEDESCRIPTION AS  \"paymentTypeDescription\",
                    COLLECTION.PAYMENTTYPEID AS \"paymentTypeId\",
                    COLLECTION.DOCUMENTNUMBER AS \"documentNumber\",
                    COLLECTION.REFERENCENUMBER AS \"referenceNumber\",
                    COLLECTION.CHEQUENUMBER AS \"chequeNumber\",
                    COLLECTION.COLLECTIONAMOUNT AS \"collectionAmount\",
                    COLLECTION.COLLECTIONDATE AS \"collectionDate\",
                    COLLECTION.COLLECTIONBANKINSLIPNUMBER AS \"collectionBankInSlipNumber\",
                    COLLECTION.COLLECTIONBANKINSLIPDATE AS \"collectionBankInSlipDate\",
                    COLLECTION.COLLECTIONDESCRIPTION AS \"collectionDescription\",
                    COLLECTION.ISDEFAULT AS \"isDefault\",
                    COLLECTION.ISNEW AS \"isNew\",
                    COLLECTION.ISDRAFT AS \"isDraft\",
                    COLLECTION.ISUPDATE AS \"isUpdate\",
                    COLLECTION.ISDELETE AS \"isDelete\",
                    COLLECTION.ISACTIVE AS \"isActive\",
                    COLLECTION.ISAPPROVED AS \"isApproved\",
                    COLLECTION.ISREVIEW AS \"isReview\",
                    COLLECTION.ISPOST AS \"isPost\",
                    COLLECTION.EXECUTEBY AS \"executeBy\",
                    COLLECTION.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	COLLECTION
		  JOIN	STAFF
		  ON	COLLECTION.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = COLLECTION.COMPANYID
	JOIN	COLLECTIONTYPE
	ON		COLLECTIONTYPE.COLLECTIONTYPEID = COLLECTION.COLLECTIONTYPEID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = COLLECTION.BUSINESSPARTNERID
	JOIN	COUNTRY
	ON		COUNTRY.COUNTRYID = COLLECTION.COUNTRYID
	JOIN	BANK
	ON		BANK.BANKID = COLLECTION.BANKID
	JOIN	PAYMENTTYPE
	ON		PAYMENTTYPE.PAYMENTTYPEID = COLLECTION.PAYMENTTYPEID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getCollectionId(0, 'single')) {
                        $sql .= " AND COLLECTION. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getCollectionId(0, 'single') . "'";
                    }
                    if ($this->model->getCollectionTypeId()) {
                        $sql .= " AND COLLECTION.COLLECTIONTYPEID='" . $this->model->getCollectionTypeId() . "'";
                    }
                    if ($this->model->getBusinessPartnerId()) {
                        $sql .= " AND COLLECTION.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
                    }
                    if ($this->model->getCountryId()) {
                        $sql .= " AND COLLECTION.COUNTRYID='" . $this->model->getCountryId() . "'";
                    }
                    if ($this->model->getBankId()) {
                        $sql .= " AND COLLECTION.BANKID='" . $this->model->getBankId() . "'";
                    }
                    if ($this->model->getPaymentTypeId()) {
                        $sql .= " AND COLLECTION.PAYMENTTYPEID='" . $this->model->getPaymentTypeId() . "'";
                    }
                    if ($this->model->getFrom() == 'collectionPost.php') {
                        $sql .= "  AND	COLLECTION.ISBALANCE =   1 AND COLLECTION.ISPOST=0";
                    } else {
                        if ($this->model->getFrom() == 'collectionHistory.php') {
                            $sql .= " AND COLLECTION.ISBALANCE	=	1 AND COLLECTION.ISPOST=1";
                        }
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
                $sql .= " AND `collection`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= " AND [collection].[" . $this->model->getFilterCharacter(
                        ) . "] like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql .= " AND Initcap(COLLECTION." . strtoupper(
                                    $this->model->getFilterCharacter()
                            ) . ") LIKE Initcap('" . $this->getCharacterQuery() . "%');";
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
                        'collection', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= $this->q->dateFilter(
                        'collection', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= $this->q->dateFilter(
                        'COLLECTION', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
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
                "`collection`.`collectionId`",
                "`staff`.`staffPassword`"
            );
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array(
                "[collection].[collectionId]",
                "[staff].[staffPassword]"
            );
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array(
                "COLLECTION.COLLECTIONID",
                "STAFF.STAFFPASSWORD"
            );
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array(
                'staff',
                'collection',
                'company',
                'collectiontype',
                'businesspartner',
                'country',
                'bank',
                'paymenttype'
            );
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array(
                'staff',
                'collection',
                'company',
                'collectiontype',
                'businesspartner',
                'country',
                'bank',
                'paymenttype'
            );
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array(
                'STAFF',
                'COLLECTION',
                'COMPANY',
                'COLLECTIONTYPE',
                'BUSINESSPARTNER',
                'COUNTRY',
                'BANK',
                'PAYMENTTYPE'
            );
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
        if (!($this->model->getCollectionId(0, 'single'))) {
            try {
                $this->q->read($sqlDerived);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        //$this->exceptionMessage($sql);
        $items = array();
        $i = 1;
        while (($row = $this->q->fetchAssoc()) == true) {
            $row['total'] = $total; // small override
            $row['counter'] = $this->getStart() + 26;
            if ($this->model->getCollectionId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getCollectionId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getCollectionId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getCollectionId(0, 'single')) {
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
                                            'value', $this->model->getCollectionId(0, 'single')
                                    ),
                                    'nextRecord' => $this->nextRecord('value', $this->model->getCollectionId(0, 'single')),
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
                                    'value', $this->model->getCollectionId(0, 'single')
                            ),
                            'nextRecord' => $this->recordSet->nextRecord(
                                    'value', $this->model->getCollectionId(0, 'single')
                            ),
                            'lastRecord' => $this->recordSet->lastRecord('value'),
                            'data' => $items
                        )
                );
                exit();
            }
        }
        // fake return
        return $items;
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
        if (!$this->model->getCollectionTypeId()) {
            $this->model->setCollectionTypeId($this->service->getCollectionTypeDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getCountryId()) {
            $this->model->setCountryId($this->service->getCountryDefaultValue());
        }
        if (!$this->model->getBankId()) {
            $this->model->setBankId($this->service->getBankDefaultValue());
        }
        if (!$this->model->getPaymentTypeId()) {
            $this->model->setPaymentTypeId($this->service->getPaymentTypeDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`collection`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getCollectionId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "] 
           FROM 	[collection] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getCollectionId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	COLLECTION 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getCollectionId(
                            0, 'single'
                    ) . "' ";
        }
        $result = $this->q->fast($sql);
        $total = $this->q->numberRows($result, $sql);
        if ($total == 0) {
            echo json_encode(array("success" => false, "message" => $this->t['recordNotFoundMessageLabel']));
            exit();
        } else {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
               UPDATE `collection` SET 
                       `collectionTypeId` = '" . $this->model->getCollectionTypeId() . "',
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `countryId` = '" . $this->model->getCountryId() . "',
                       `bankId` = '" . $this->model->getBankId() . "',
                       `paymentTypeId` = '" . $this->model->getPaymentTypeId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `chequeNumber` = '" . $this->model->getChequeNumber() . "',
                       `collectionAmount` = '" . $this->model->getCollectionAmount() . "',
                       `collectionDate` = '" . $this->model->getCollectionDate() . "',
                       `collectionBankInSlipNumber` = '" . $this->model->getCollectionBankInSlipNumber() . "',
                       `collectionBankInSlipDate` = '" . $this->model->getCollectionBankInSlipDate() . "',
                       `collectionDescription` = '" . $this->model->getCollectionDescription() . "',
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
               WHERE    `collectionId`='" . $this->model->getCollectionId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [collection] SET 
                       [collectionTypeId] = '" . $this->model->getCollectionTypeId() . "',
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [countryId] = '" . $this->model->getCountryId() . "',
                       [bankId] = '" . $this->model->getBankId() . "',
                       [paymentTypeId] = '" . $this->model->getPaymentTypeId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [chequeNumber] = '" . $this->model->getChequeNumber() . "',
                       [collectionAmount] = '" . $this->model->getCollectionAmount() . "',
                       [collectionDate] = '" . $this->model->getCollectionDate() . "',
                       [collectionBankInSlipNumber] = '" . $this->model->getCollectionBankInSlipNumber() . "',
                       [collectionBankInSlipDate] = '" . $this->model->getCollectionBankInSlipDate() . "',
                       [collectionDescription] = '" . $this->model->getCollectionDescription() . "',
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
                WHERE   [collectionId]='" . $this->model->getCollectionId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE COLLECTION SET
                        COLLECTIONTYPEID = '" . $this->model->getCollectionTypeId() . "',
                       BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       COUNTRYID = '" . $this->model->getCountryId() . "',
                       BANKID = '" . $this->model->getBankId() . "',
                       PAYMENTTYPEID = '" . $this->model->getPaymentTypeId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       CHEQUENUMBER = '" . $this->model->getChequeNumber() . "',
                       COLLECTIONAMOUNT = '" . $this->model->getCollectionAmount() . "',
                       COLLECTIONDATE = '" . $this->model->getCollectionDate() . "',
                       COLLECTIONBANKINSLIPNUMBER = '" . $this->model->getCollectionBankInSlipNumber() . "',
                       COLLECTIONBANKINSLIPDATE = '" . $this->model->getCollectionBankInSlipDate() . "',
                       COLLECTIONDESCRIPTION = '" . $this->model->getCollectionDescription() . "',
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
                WHERE  COLLECTIONID='" . $this->model->getCollectionId('0', 'single') . "'";
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
           FROM 	`collection` 
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getCollectionId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]  
           FROM 	[collection] 
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getCollectionId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . " 
           FROM 	COLLECTION 
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getCollectionId(
                            0, 'single'
                    ) . "' ";
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
               UPDATE  `collection` 
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
               WHERE   `collectionId`   =  '" . $this->model->getCollectionId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [collection] 
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
               WHERE   [collectionId]	=  '" . $this->model->getCollectionId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  COLLECTION 
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
               WHERE   COLLECTIONID	=  '" . $this->model->getCollectionId(0, 'single') . "'";
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
           FROM    `collection` 
           WHERE   `documentNumber` 	= 	'" . $this->model->getDocumentNumber() . "'
           AND     `isActive`  =   1
           AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT  [documentNumber]
           FROM    [collection] 
           WHERE   [documentNumber] = 	'" . $this->model->getDocumentNumber() . "'
           AND     [isActive]  =   1 
           AND     [companyId] =	'" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
               SELECT  DOCUMENTNUMBER as \"documentNumber\"
               FROM    COLLECTION 
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
                        "documentNumber" => $row ['documentNumber'],
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
     * Return  Collection Type
     * @return null|string
     */
    public function getCollectionType() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCollectionType();
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
     * Return  Country
     * @return null|string
     */
    public function getCountry() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getCountry();
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
     * Return  Payment Type
     * @return null|string
     */
    public function getPaymentType() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getPaymentType();
    }

    /**
     * Return  Chart Of Account
     * @return null|string
     */
    public function getChartOfAccount() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccount();
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
                    $this->model->getCollectionId(0, 'single'), $this->getLeafId(), $this->model->getFrom()
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
     * Return Total Record Of The
     * return int Total Record
     */
    private function getTotalRecord() {
        $sql = null;
        $total = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT  count(*) AS `total` 
         FROM    `collection`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total 
         FROM      [collection]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\" 
         FROM      COLLECTION
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
                ->setSubject('collection')
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
        $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('Q2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:Q2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['collectionTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['countryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['bankIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['paymentTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['chequeNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['collectionAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['collectionDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['collectionBankInSlipNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['collectionBankInSlipDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['collectionDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue(
                    'C' . $loopRow, strip_tags($row ['collectionTypeDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('D' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['countryDescription']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['bankDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['paymentTypeDescription']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->setCellValue('I' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['chequeNumber']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['collectionAmount']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('L' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('L' . $loopRow, strip_tags($row ['collectionDate']));
            $this->excel->getActiveSheet()->setCellValue(
                    'M' . $loopRow, strip_tags($row ['collectionBankInSlipNumber'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('N' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['collectionBankInSlipDate']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['collectionDescription']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('Q' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'Q' . $loopRow;
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
                $filename = "collection" . rand(0, 10000000) . $extension;
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
                $filename = "collection" . rand(0, 10000000) . $extension;
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
                $filename = "collection" . rand(0, 10000000) . $extension;
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
                $filename = "collection" . rand(0, 10000000) . $extension;
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
        $collectionObject = new CollectionClass ();
        if ($_POST['securityToken'] != $collectionObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $collectionObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $collectionObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $collectionObject->setLimit($_POST ['limit']);
        }
        $collectionObject->setPageOutput($_POST['output']);
        $collectionObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $collectionObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $collectionObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $collectionObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $collectionObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            $collectionObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            $collectionObject->delete();
        }
    }
}
if (isset($_GET ['method'])) {
    $collectionObject = new CollectionClass ();
    if ($_GET['securityToken'] != $collectionObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $collectionObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $collectionObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $collectionObject->updateStatus();
    }
    if ($_GET ['method'] == 'posting') {
        $collectionObject->posting();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $collectionObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $collectionObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $collectionObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $collectionObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $collectionObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $collectionObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $collectionObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $collectionObject->setServiceOutput('option');
        if (($_GET['filter'] == 'collectionType')) {
            $collectionObject->getCollectionType();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $collectionObject->getBusinessPartner();
        }
        if (($_GET['filter'] == 'country')) {
            $collectionObject->getCountry();
        }
        if (($_GET['filter'] == 'bank')) {
            $collectionObject->getBank();
        }
        if (($_GET['filter'] == 'paymentType')) {
            $collectionObject->getPaymentType();
        }
    }
}
?>
