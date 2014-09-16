<?php

namespace Core\Financial\Cashbook\PaymentVoucher\Controller;

use Core\ConfigClass;
use Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel;
use Core\Financial\Cashbook\PaymentVoucher\Service\PaymentVoucherService;
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
require_once($newFakeDocumentRoot . "v3/financial/cashbook/model/paymentVoucherModel.php");
require_once($newFakeDocumentRoot . "v3/financial/cashbook/service/paymentVoucherService.php");

/**
 * Class PaymentVoucher
 * this is paymentVoucher controller files.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package  Core\Financial\Cashbook\PaymentVoucher\Controller
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PaymentVoucherClass extends ConfigClass {

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
     * @var \Core\Financial\Cashbook\PaymentVoucher\Model\PaymentVoucherModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\Financial\Cashbook\PaymentVoucher\Service\PaymentVoucherService
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

    const PURCHASE_INVOICE_CODE = 'APINV';

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
        $this->setViewPath("./v3/financial/cashbook/view/paymentVoucher.php");
        $this->setControllerPath("./v3/financial/cashbook/controller/paymentVoucherController.php");
        $this->setServicePath("./v3/financial/cashbook/service/paymentVoucherService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(1);
        $this->setLog(1);
        $this->model = new PaymentVoucherModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        $this->setViewPath("./v3/financial/cashbook/view/" . $this->model->getFrom());
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } elseif ($this->getVendor() == self::MSSQL) {
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

        $this->service = new PaymentVoucherService();
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
        if (!$this->model->getBankId()) {
            $this->model->setBankId($this->service->getBankDefaultValue());
        }
        if (!$this->model->getBusinessPartnerCategoryId()) {
            $this->model->setBusinessPartnerCategoryId($this->service->getBusinessPartnerCategoryDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getPaymentTypeId()) {
            $this->model->setPaymentTypeId($this->service->getPaymentTypeDefaultValue());
        }
        if (!$this->model->getDocumentNumber()) {
            $this->model->setDocumentNumber($this->getDocumentNumber());
        }
        $this->model->setDocumentNumber($this->getDocumentNumber());
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `paymentvoucher`
            (
                 `companyId`,
                 `bankId`,
                 `businessPartnerCategoryId`,
                 `businessPartnerId`,
                 `paymentTypeId`,
                 `documentNumber`,
                 `referenceNumber`,
                 `paymentVoucherDescription`,
                 `paymentVoucherDate`,
				 `paymentVoucherChequeDate`,
                 `paymentVoucherAmount`,
                 `paymentVoucherChequeNumber`,
                 `paymentVoucherPayee`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `isPrinted`,
                 `isConform`,
                 `isChequePrinted`,
                 `executeBy`,
                 `executeTime`
       ) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getBusinessPartnerCategoryId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getPaymentTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPaymentVoucherDescription() . "',
                 '" . $this->model->getPaymentVoucherDate() . "',
				 '" . $this->model->getPaymentVoucherChequeDate() . "',
                 '" . $this->model->getPaymentVoucherAmount() . "',
                 '" . $this->model->getPaymentVoucherChequeNumber() . "',
                 '" . $this->model->getPaymentVoucherPayee() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsPrinted() . "',
                 '" . $this->model->getIsConform() . "',
                 '" . $this->model->getIsChequePrinted() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [paymentVoucher]
            (
                 [paymentVoucherId],
                 [companyId],
                 [bankId],
                 [businessPartnerCategoryId],
                 [businessPartnerId],
                 [paymentTypeId],
                 [documentNumber],
                 [referenceNumber],
                 [paymentVoucherDescription],
                 [paymentVoucherChequeDate],
				 [paymentVoucherDate],
                 [paymentVoucherAmount],
                 [paymentVoucherChequeNumber],
                 [paymentVoucherPayee],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [isPrinted],
                 [isConform],
                 [isChequePrinted],
                 [executeBy],
                 [executeTime]
) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getBusinessPartnerCategoryId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getPaymentTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPaymentVoucherDescription() . "',
                 '" . $this->model->getPaymentVoucherDate() . "',
				 '" . $this->model->getPaymentVoucherChequeDate() . "',
                 '" . $this->model->getPaymentVoucherAmount() . "',
                 '" . $this->model->getPaymentVoucherChequeNumber() . "',
                 '" . $this->model->getPaymentVoucherPayee() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsPrinted() . "',
                 '" . $this->model->getIsConform() . "',
                 '" . $this->model->getIsChequePrinted() . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO PAYMENTVOUCHER
            (
                 COMPANYID,
                 BANKID,
                 BUSINESSPARTNERCATEGORYID,
                 BUSINESSPARTNERID,
                 PAYMENTTYPEID,
                 DOCUMENTNUMBER,
                 REFERENCENUMBER,
                 PAYMENTVOUCHERDESCRIPTION,
                 PAYMENTVOUCHERDATE,
				 PAYMENTVOUCHERCHEQUEDATE,
                 PAYMENTVOUCHERAMOUNT,
                 PAYMENTVOUCHERCHEQUENUMBER,
                 PAYMENTVOUCHERPAYEE,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 ISPRINTED,
                 ISCONFORM,
                 ISCHEQUEPRINTED,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES (
                 '" . $this->getCompanyId() . "',
                 '" . $this->model->getBankId() . "',
                 '" . $this->model->getBusinessPartnerCategoryId() . "',
                 '" . $this->model->getBusinessPartnerId() . "',
                 '" . $this->model->getPaymentTypeId() . "',
                 '" . $this->model->getDocumentNumber() . "',
                 '" . $this->model->getReferenceNumber() . "',
                 '" . $this->model->getPaymentVoucherDescription() . "',
                 '" . $this->model->getPaymentVoucherDate() . "',
				 '" . $this->model->getPaymentVoucherChequeDate() . "',
                 '" . $this->model->getPaymentVoucherAmount() . "',
                 '" . $this->model->getPaymentVoucherChequeNumber() . "',
                 '" . $this->model->getPaymentVoucherPayee() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getIsPrinted() . "',
                 '" . $this->model->getIsConform() . "',
                 '" . $this->model->getIsChequePrinted() . "',
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
        $paymentVoucherId = $this->q->lastInsertId('paymentVoucher');

        $journalNumber = $this->getDocumentNumber('GLPT');
        switch ($this->model->getFrom()) {
            case 'paymentVoucher.php':
                break;
            case 'paymentVoucherMobile.php':
                // credit bank/cash account

                $paymentVoucherDetailId = $this->service->setPaymentVoucherDetail(
                        $paymentVoucherId, $this->model->getBusinessPartnerId(), $this->service->getPettyCashDefaultAccount(), $this->model->getPaymentVoucherAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getPettyCashDefaultAccount(), $journalNumber, $this->model->getDocumentNumber(), $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount(), $this->model->getPaymentVoucherDescription(), 'CB', 'paymentVoucher', 'paymentVoucherDetail', 'paymentVoucherId', 'paymentVoucherDetailId', $paymentVoucherDetailId
                );
                // debit creditor control account
                $paymentVoucherDetailId = $this->service->setPaymentVoucherDetail(
                        $paymentVoucherId, $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $this->model->getPaymentVoucherAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $journalNumber, $this->model->getDocumentNumber(), $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount() * -1, $this->model->getPaymentVoucherDescription(), 'CB', 'paymentVoucher', 'paymentVoucherDetail', 'paymentVoucherId', 'paymentVoucherDetailId', $paymentVoucherDetailId
                );

                // create and invoice
                $purchaseInvoiceDocumentNumber = $this->getDocumentNumber(self::PURCHASE_INVOICE_CODE);
                $purchaseInvoiceId = $this->service->setPurchaseInvoice(
                        $this->model->getBusinessPartnerId(), $purchaseInvoiceDocumentNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherDescription(), $this->model->getPaymentVoucherAmount()
                );
                //  credit creditor  control account
                $purchaseInvoiceDetailId = $this->service->setPurchaseInvoiceDetail(
                        $purchaseInvoiceId, $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $this->model->getPaymentVoucherAmount(), $purchaseInvoiceDocumentNumber, $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $purchaseInvoiceDocumentNumber, $journalNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount(), $this->model->getPaymentVoucherDescription(), 'AP', 'purchaseInvoice', 'purchaseInvoiceDetail', $purchaseInvoiceId, $purchaseInvoiceDetailId
                );
                //  debit expenses account
                $purchaseInvoiceDetailId = $this->service->setPurchaseInvoiceDetail(
                        $purchaseInvoiceId, $this->model->getBusinessPartnerId(), $this->service->getExpensesDefaultAccount(), $this->model->getPaymentVoucherAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getExpensesDefaultAccount(), $purchaseInvoiceDocumentNumber, $journalNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount() * -1, $this->model->getPaymentVoucherDescription(), 'AP', 'purchaseInvoice', 'purchaseInvoiceDetail', $purchaseInvoiceId, $purchaseInvoiceDetailId
                );

                //allocation
                $this->service->setPaymentVoucherAllocation(
                        $paymentVoucherId, $purchaseInvoiceId, $this->model->getBusinessPartnerId(), $this->model->getPaymentVoucherAmount()
                );

                break;
            case 'paymentVoucherSimple.php':
                // credit bank/cash account

                $paymentVoucherDetailId = $this->service->setPaymentVoucherDetail(
                        $paymentVoucherId, $this->model->getBusinessPartnerId(), $this->model->getBankId(), $this->model->getPaymentVoucherAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getPettyCashDefaultAccount(), $this->model->getDocumentNumber(), $journalNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount(), $this->model->getPaymentVoucherDescription(), 'CB', 'paymentVoucher', 'paymentVoucherDetail', $paymentVoucherId, $paymentVoucherDetailId
                );
                // debit creditor control account
                $paymentVoucherDetailId = $this->service->setPaymentVoucherDetail(
                        $paymentVoucherId, $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $this->model->getPaymentVoucherAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $this->model->getDocumentNumber(), $journalNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount() * -1, $this->model->getPaymentVoucherDescription(), 'CB', 'paymentVoucher', 'paymentVoucherDetail', $paymentVoucherId, $paymentVoucherDetailId
                );

                // create and invoice
                $purchaseInvoiceDocumentNumber = $this->getDocumentNumber(self::PURCHASE_INVOICE_CODE);

                $purchaseInvoiceId = $this->service->setPurchaseInvoice(
                        $this->model->getBusinessPartnerId(), $purchaseInvoiceDocumentNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherDescription(), $this->model->getPaymentVoucherAmount()
                );
                //  credit creditor  control account
                $purchaseInvoiceDetailId = $this->service->setPurchaseInvoiceDetail(
                        $purchaseInvoiceId, $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $this->model->getPaymentVoucherAmount(), $purchaseInvoiceDocumentNumber, $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getCreditorDefaultAccount(), $purchaseInvoiceDocumentNumber, $journalNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount(), $this->model->getPaymentVoucherDescription(), 'AP', 'purchaseInvoice', 'purchaseInvoiceDetail', $purchaseInvoiceId, $purchaseInvoiceDetailId
                );
                //  debit expenses account
                $purchaseInvoiceDetailId = $this->service->setPurchaseInvoiceDetail(
                        $purchaseInvoiceId, $this->model->getBusinessPartnerId(), $this->service->getExpensesDefaultAccount(), $this->model->getPaymentVoucherAmount(), $this->model->getDocumentNumber(), $journalNumber
                );
                $this->service->setGeneralLedger(
                        $this->getLeafId(), 'paymentVoucherMobile.php', $this->model->getBusinessPartnerId(), $this->service->getExpensesDefaultAccount(), $purchaseInvoiceDocumentNumber, $journalNumber, $this->model->getPaymentVoucherDate(), $this->model->getPaymentVoucherAmount() * -1, $this->model->getPaymentVoucherDescription(), 'AP', 'purchaseInvoice', 'purchaseInvoiceDetail', $purchaseInvoiceId, $purchaseInvoiceDetailId
                );

                //allocation
                $this->service->setPaymentVoucherAllocation(
                        $paymentVoucherId, $purchaseInvoiceId, $this->model->getBusinessPartnerId(), $this->model->getPaymentVoucherAmount()
                );

                break;

            case 'paymetVoucherInvoice.php':
                // credit cash account / bank account
                //$this->service->setPaymentVoucherDetail($paymentVoucherId, $this->service->getBankChartOfaccount($this->model->getBankId()), $this->model->getPaymentVoucherAmount(),$this->model->getDocumentNumber(),$journalNumber);
                // debit expenses
                //$this->service->setPaymentVoucherDetail($paymentVoucherId, $this->service->model->getChartOfAccountId(), $this->model->getPaymentVoucherAmount(),$this->model->getDocumentNumber(),$journalNumber);

                break;
            case 'paymentVoucherDetail.php':
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
                    "paymentVoucherId" => $paymentVoucherId,
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
                            " `paymentvoucher`.`isActive` = 1  AND `paymentvoucher`.`companyId`='" . $this->getCompanyId(
                            ) . "' "
                    );
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $this->setAuditFilter(
                                " [paymentVoucher].[isActive] = 1 AND [paymentVoucher].[companyId]='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $this->setAuditFilter(
                                    " PAYMENTVOUCHER.ISACTIVE = 1  AND PAYMENTVOUCHER.COMPANYID='" . $this->getCompanyId(
                                    ) . "'"
                            );
                        }
                    }
                }
            } else {
                if ($_SESSION['isAdmin'] == 1) {
                    if ($this->getVendor() == self::MYSQL) {
                        $this->setAuditFilter(
                                " `paymentvoucher`.`isActive` = 1  AND `paymentvoucher`.`companyId`='" . $this->getCompanyId(
                                ) . "' "
                        );
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $this->setAuditFilter(
                                    " [paymentVoucher].[isActive] = 1 AND [paymentVoucher].[companyId]='" . $this->getCompanyId(
                                    ) . "' "
                            );
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $this->setAuditFilter(
                                        " PAYMENTVOUCHER.ISACTIVE = 1  AND PAYMENTVOUCHER.COMPANYID='" . $this->getCompanyId(
                                        ) . "'"
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
       SELECT                    `paymentvoucher`.`paymentVoucherId`,
                    `company`.`companyDescription`,
                    `paymentvoucher`.`companyId`,
                    `bank`.`bankDescription`,
                    `paymentvoucher`.`bankId`,
                    `businesspartnercategory`.`businessPartnerCategoryDescription`,
                    `paymentvoucher`.`businessPartnerCategoryId`,
                    `businesspartner`.`businessPartnerCompany`,
                    `paymentvoucher`.`businessPartnerId`,
                    `paymentvoucher`.`paymentTypeId`,
                    `paymentvoucher`.`documentNumber`,
                    `paymentvoucher`.`referenceNumber`,
                    `paymentvoucher`.`paymentVoucherDescription`,
                    `paymentvoucher`.`paymentVoucherDate`,
					 `paymentvoucher`.`paymentVoucherChequeDate`,
                    `paymentvoucher`.`paymentVoucherAmount`,
                    `paymentvoucher`.`paymentVoucherChequeNumber`,
                    `paymentvoucher`.`paymentVoucherPayee`,
                    `paymentvoucher`.`isDefault`,
                    `paymentvoucher`.`isNew`,
                    `paymentvoucher`.`isDraft`,
                    `paymentvoucher`.`isUpdate`,
                    `paymentvoucher`.`isDelete`,
                    `paymentvoucher`.`isActive`,
                    `paymentvoucher`.`isApproved`,
                    `paymentvoucher`.`isReview`,
                    `paymentvoucher`.`isPost`,
                    `paymentvoucher`.`isPrinted`,
                    `paymentvoucher`.`isConform`,
                    `paymentvoucher`.`isChequePrinted`,
                    `paymentvoucher`.`executeBy`,
                    `paymentvoucher`.`executeTime`,
                    `staff`.`staffName`
		  FROM      `paymentvoucher`
		  JOIN      `staff`
		  ON        `paymentvoucher`.`executeBy` = `staff`.`staffId`
	JOIN	`company`
	ON		`company`.`companyId` = `paymentvoucher`.`companyId`
	JOIN	`bank`
	ON		`bank`.`bankId` = `paymentvoucher`.`bankId`
	JOIN	`businesspartnercategory`
	ON		`businesspartnercategory`.`businessPartnerCategoryId` = `paymentvoucher`.`businessPartnerCategoryId`
	JOIN	`businesspartner`
	ON		`businesspartner`.`businessPartnerId` = `paymentvoucher`.`businessPartnerId`
	JOIN	`paymenttype`
	ON		`paymenttype`.`paymentTypeId` = `paymentvoucher`.`paymentTypeId`
		  WHERE     " . $this->getAuditFilter();
            if ($this->model->getPaymentVoucherId(0, 'single')) {
                $sql .= " AND `paymentvoucher`.`" . $this->model->getPrimaryKeyName(
                        ) . "`='" . $this->model->getPaymentVoucherId(0, 'single') . "'";
            }
            if ($this->model->getBankId()) {
                $sql .= " AND `paymentvoucher`.`bankId`='" . $this->model->getBankId() . "'";
            }
            if ($this->model->getBusinessPartnerCategoryId()) {
                $sql .= " AND `paymentvoucher`.`businessPartnerCategoryId`='" . $this->model->getBusinessPartnerCategoryId(
                        ) . "'";
            }
            if ($this->model->getBusinessPartnerId()) {
                $sql .= " AND `paymentvoucher`.`businessPartnerId`='" . $this->model->getBusinessPartnerId() . "'";
            }
            if ($this->model->getFrom() == 'paymentVoucherChequeEntry.php') {
                // have printed payment voucher detail
                $sql .= "    AND   `paymentvoucher`.`isConform` = 1 ";
            } else {
                if ($this->model->getFrom() == 'paymentVoucherPost.php') {
                    $sql .= "  AND   `paymentvoucher`.`isBalance` =   1 AND `paymentvoucher`.`isPost`=0";
                } else {
                    if ($this->model->getFrom() == 'paymentVoucherHistory.php') {
                        $sql .= " AND `paymentvoucher`.`isBalance`=1 AND `paymentvoucher`.`isPost`=1";
                    }
                }
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {

                $sql = "
		  SELECT                    [paymentVoucher].[paymentVoucherId],
                    [company].[companyDescription],
                    [paymentVoucher].[companyId],
                    [bank].[bankDescription],
                    [paymentVoucher].[bankId],
                    [businessPartnerCategory].[businessPartnerCategoryDescription],
                    [paymentVoucher].[businessPartnerCategoryId],
                    [businessPartner].[businessPartnerCompany],
                    [paymentVoucher].[businessPartnerId],
                    [paymentVoucher].[paymentTypeId],
                    [paymentVoucher].[documentNumber],
                    [paymentVoucher].[referenceNumber],
                    [paymentVoucher].[paymentVoucherDescription],
                    [paymentVoucher].[paymentVoucherDate],
					[paymentVoucher].[paymentVoucherChequeDate],
                    [paymentVoucher].[paymentVoucherAmount],
                    [paymentVoucher].[paymentVoucherChequeNumber],
                    [paymentVoucher].[paymentVoucherPayee],
                    [paymentVoucher].[isDefault],
                    [paymentVoucher].[isNew],
                    [paymentVoucher].[isDraft],
                    [paymentVoucher].[isUpdate],
                    [paymentVoucher].[isDelete],
                    [paymentVoucher].[isActive],
                    [paymentVoucher].[isApproved],
                    [paymentVoucher].[isReview],
                    [paymentVoucher].[isPost],
                    [paymentVoucher].[isPrinted],
                    [paymentVoucher].[isConform],
                    [paymentVoucher].[isChequePrinted],
                    [paymentVoucher].[executeBy],
                    [paymentVoucher].[executeTime],
                    [staff].[staffName]
		  FROM 	[paymentVoucher]
		  JOIN	[staff]
		  ON	[paymentVoucher].[executeBy] = [staff].[staffId]
	JOIN	[company]
	ON		[company].[companyId] = [paymentVoucher].[companyId]
	JOIN	[bank]
	ON		[bank].[bankId] = [paymentVoucher].[bankId]
	JOIN	[businessPartnerCategory]
	ON		[businessPartnerCategory].[businessPartnerCategoryId] = [paymentVoucher].[businessPartnerCategoryId]
	JOIN	[businessPartner]
	ON		[businessPartner].[businessPartnerId] = [paymentVoucher].[businessPartnerId]
		  WHERE     " . $this->getAuditFilter();
                if ($this->model->getPaymentVoucherId(0, 'single')) {
                    $sql .= " AND [paymentVoucher].[" . $this->model->getPrimaryKeyName(
                            ) . "]		=	'" . $this->model->getPaymentVoucherId(0, 'single') . "'";
                }
                if ($this->model->getBankId()) {
                    $sql .= " AND [paymentVoucher].[bankId]='" . $this->model->getBankId() . "'";
                }
                if ($this->model->getBusinessPartnerCategoryId()) {
                    $sql .= " AND [paymentVoucher].[businessPartnerCategoryId]='" . $this->model->getBusinessPartnerCategoryId(
                            ) . "'";
                }
                if ($this->model->getBusinessPartnerId()) {
                    $sql .= " AND [paymentVoucher].[businessPartnerId]='" . $this->model->getBusinessPartnerId() . "'";
                }
                if ($this->model->getFrom() == 'paymentVoucherChequeEntry.php') {
                    // have printed payment voucher detail
                    $sql .= "    AND   [paymentVoucher].[isConform] = 1 ";
                } else {
                    if ($this->model->getFrom() == 'paymentVoucherPost.php') {
                        $sql .= "  AND   [paymentVoucher].[isBalance] =   1 AND [paymentVoucher].[isPost]=0";
                    } else {
                        if ($this->model->getFrom() == 'paymentVoucherHistory.php') {
                            $sql .= " AND [paymentVoucher].[isBalance]=1 AND [paymentVoucher].[isPost]=1";
                        }
                    }
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {

                    $sql = "
		  SELECT                    PAYMENTVOUCHER.PAYMENTVOUCHERID AS \"paymentVoucherId\",
                    COMPANY.COMPANYDESCRIPTION AS  \"companyDescription\",
                    PAYMENTVOUCHER.COMPANYID AS \"companyId\",
                    BANK.BANKDESCRIPTION AS  \"bankDescription\",
                    PAYMENTVOUCHER.BANKID AS \"bankId\",
                    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS  \"businessPartnerCategoryDescription\",
                    PAYMENTVOUCHER.BUSINESSPARTNERCATEGORYID AS \"businessPartnerCategoryId\",
                    BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS  \"businessPartnerCompany\",
                    PAYMENTVOUCHER.BUSINESSPARTNERID AS \"businessPartnerId\",
                    PAYMENTVOUCHER.PAYMENTTYPEID AS \"paymentTypeId\",
                    PAYMENTVOUCHER.DOCUMENTNUMBER AS \"documentNumber\",
                    PAYMENTVOUCHER.REFERENCENUMBER AS \"referenceNumber\",
                    PAYMENTVOUCHER.PAYMENTVOUCHERDESCRIPTION AS \"paymentVoucherDescription\",
                    PAYMENTVOUCHER.PAYMENTVOUCHERDATE AS \"paymentVoucherDate\",
					PAYMENTVOUCHER.PAYMENTVOUCHERCHEQUEDATE AS \"paymentVoucherChequeDate\",
                    PAYMENTVOUCHER.PAYMENTVOUCHERAMOUNT AS \"paymentVoucherAmount\",
                    PAYMENTVOUCHER.PAYMENTVOUCHERCHEQUENUMBER AS \"paymentVoucherChequeNumber\",
                    PAYMENTVOUCHER.PAYMENTVOUCHERPAYEE AS \"paymentVoucherPayee\",
                    PAYMENTVOUCHER.ISDEFAULT AS \"isDefault\",
                    PAYMENTVOUCHER.ISNEW AS \"isNew\",
                    PAYMENTVOUCHER.ISDRAFT AS \"isDraft\",
                    PAYMENTVOUCHER.ISUPDATE AS \"isUpdate\",
                    PAYMENTVOUCHER.ISDELETE AS \"isDelete\",
                    PAYMENTVOUCHER.ISACTIVE AS \"isActive\",
                    PAYMENTVOUCHER.ISAPPROVED AS \"isApproved\",
                    PAYMENTVOUCHER.ISREVIEW AS \"isReview\",
                    PAYMENTVOUCHER.ISPOST AS \"isPost\",
                    PAYMENTVOUCHER.ISPRINTED AS \"isPrinted\",
                    PAYMENTVOUCHER.ISCONFORM AS \"isConform\",
                    PAYMENTVOUCHER.ISCHEQUEPRINTED AS \"isChequePrinted\",
                    PAYMENTVOUCHER.EXECUTEBY AS \"executeBy\",
                    PAYMENTVOUCHER.EXECUTETIME AS \"executeTime\",
                    STAFF.STAFFNAME AS \"staffName\"
		  FROM 	PAYMENTVOUCHER
		  JOIN	STAFF
		  ON	PAYMENTVOUCHER.EXECUTEBY = STAFF.STAFFID
 	JOIN	COMPANY
	ON		COMPANY.COMPANYID = PAYMENTVOUCHER.COMPANYID
	JOIN	BANK
	ON		BANK.BANKID = PAYMENTVOUCHER.BANKID
	JOIN	BUSINESSPARTNERCATEGORY
	ON		BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID = PAYMENTVOUCHER.BUSINESSPARTNERCATEGORYID
	JOIN	BUSINESSPARTNER
	ON		BUSINESSPARTNER.BUSINESSPARTNERID = PAYMENTVOUCHER.BUSINESSPARTNERID
         WHERE     " . $this->getAuditFilter();
                    if ($this->model->getPaymentVoucherId(0, 'single')) {
                        $sql .= " AND PAYMENTVOUCHER. " . strtoupper(
                                        $this->model->getPrimaryKeyName()
                                ) . "='" . $this->model->getPaymentVoucherId(0, 'single') . "'";
                    }
                    if ($this->model->getBankId()) {
                        $sql .= " AND PAYMENTVOUCHER.BANKID='" . $this->model->getBankId() . "'";
                    }
                    if ($this->model->getBusinessPartnerCategoryId()) {
                        $sql .= " AND PAYMENTVOUCHER.BUSINESSPARTNERCATEGORYID='" . $this->model->getBusinessPartnerCategoryId(
                                ) . "'";
                    }
                    if ($this->model->getBusinessPartnerId()) {
                        $sql .= " AND PAYMENTVOUCHER.BUSINESSPARTNERID='" . $this->model->getBusinessPartnerId() . "'";
                    }
                    if ($this->model->getFrom() == 'paymentVoucherChequeEntry.php') {
                        // have printed payment voucher detail
                        $sql .= "    AND  PAYMENTVOUCHER.ISCONFORM = 1 ";
                    } else {
                        if ($this->model->getFrom() == 'paymentVoucherPost.php') {
                            $sql .= "  AND  PAYMENTVOUCHER.ISBALANCE =   1 AND PAYMENTVOUCHER.ISPOST	=	0";
                        } else {
                            if ($this->model->getFrom() == 'paymentVoucherHistory.php') {
                                $sql .= " AND	PAYMENTVOUCHER.ISBALANCE=1 AND PAYMENTVOUCHER.ISPOST	=	1";
                            }
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
                $sql .= " AND `paymentvoucher`.`" . $this->model->getFilterCharacter(
                        ) . "` like '" . $this->getCharacterQuery() . "%'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= " AND [paymentVoucher].[" . $this->model->getFilterCharacter(
                        ) . "] like '" . $this->getCharacterQuery() . "%'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql .= " AND Initcap(PAYMENTVOUCHER." . strtoupper(
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
                        'paymentVoucher', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::MSSQL) {
                $sql .= $this->q->dateFilter(
                        'paymentVoucher', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
                );
            } else if ($this->getVendor() == self::ORACLE) {
                $sql .= $this->q->dateFilter(
                        'PAYMENTVOUCHER', $this->model->getFilterDate(), $this->getDateRangeStartQuery(), $this->getDateRangeEndQuery(), $this->getDateRangeTypeQuery(), $this->getDateRangeExtraTypeQuery()
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
                "`paymentvoucher`.`paymentVoucherId`",
                "`staff`.`staffPassword`"
            );
        } else if ($this->getVendor() == self::MSSQL) {
            $filterArray = array(
                "[paymentVoucher].[paymentVoucherId]",
                "[staff].[staffPassword]"
            );
        } else if ($this->getVendor() == self::ORACLE) {
            $filterArray = array(
                "PAYMENTVOUCHER.PAYMENTVOUCHERID",
                "STAFF.STAFFPASSWORD"
            );
        }
        $tableArray = null;
        if ($this->getVendor() == self::MYSQL) {
            $tableArray = array('staff', 'paymentvoucher', 'bank', 'businesspartnercategory', 'businesspartner');
        } else if ($this->getVendor() == self::MSSQL) {
            $tableArray = array('staff', 'paymentvoucher', 'bank', 'businesspartnercategory', 'businesspartner');
        } else if ($this->getVendor() == self::ORACLE) {
            $tableArray = array('STAFF', 'PAYMENTVOUCHER', 'BANK', 'BUSINESSPARTNERCATEGORY', 'BUSINESSPARTNER');
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
        if (!($this->model->getPaymentVoucherId(0, 'single'))) {
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
            $row['counter'] = $this->getStart() + 27;
            if ($this->model->getPaymentVoucherId(0, 'single')) {
                $row['firstRecord'] = $this->firstRecord('value');
                $row['previousRecord'] = $this->previousRecord('value', $this->model->getPaymentVoucherId(0, 'single'));
                $row['nextRecord'] = $this->nextRecord('value', $this->model->getPaymentVoucherId(0, 'single'));
                $row['lastRecord'] = $this->lastRecord('value');
            }
            $items [] = $row;
            $i++;
        }
        if ($this->getPageOutput() == 'html') {
            return $items;
        } else if ($this->getPageOutput() == 'json') {
            if ($this->model->getPaymentVoucherId(0, 'single')) {
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
                                            'value', $this->model->getPaymentVoucherId(0, 'single')
                                    ),
                                    'nextRecord' => $this->nextRecord('value', $this->model->getPaymentVoucherId(0, 'single')),
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
                                    'value', $this->model->getPaymentVoucherId(0, 'single')
                            ),
                            'nextRecord' => $this->recordSet->nextRecord(
                                    'value', $this->model->getPaymentVoucherId(0, 'single')
                            ),
                            'lastRecord' => $this->recordSet->lastRecord('value'),
                            'data' => $items
                        )
                );
                exit();
            }
        }
        //fake return
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
        if (!$this->model->getBankId()) {
            $this->model->setBankId($this->service->getBankDefaultValue());
        }
        if (!$this->model->getBusinessPartnerCategoryId()) {
            $this->model->setBusinessPartnerCategoryId($this->service->getBusinessPartnerCategoryDefaultValue());
        }
        if (!$this->model->getBusinessPartnerId()) {
            $this->model->setBusinessPartnerId($this->service->getBusinessPartnerDefaultValue());
        }
        if (!$this->model->getPaymentTypeId()) {
            $this->model->setPaymentTypeId($this->service->getPaymentTypeDefaultValue());
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
           SELECT	`" . $this->model->getPrimaryKeyName() . "`
           FROM 	`paymentvoucher`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPaymentVoucherId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[paymentVoucher]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPaymentVoucherId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	PAYMENTVOUCHER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPaymentVoucherId(
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
               UPDATE `paymentvoucher` SET
                       `bankId` = '" . $this->model->getBankId() . "',
                       `businessPartnerCategoryId` = '" . $this->model->getBusinessPartnerCategoryId() . "',
                       `businessPartnerId` = '" . $this->model->getBusinessPartnerId() . "',
                       `paymentTypeId` = '" . $this->model->getPaymentTypeId() . "',
                       `documentNumber` = '" . $this->model->getDocumentNumber() . "',
                       `referenceNumber` = '" . $this->model->getReferenceNumber() . "',
                       `paymentVoucherDescription` = '" . $this->model->getPaymentVoucherDescription() . "',
                       `paymentVoucherDate` = '" . $this->model->getPaymentVoucherDate() . "',
					   `paymentVoucherChequeDate` = '" . $this->model->getPaymentVoucherChequeDate() . "',
                       `paymentVoucherAmount` = '" . $this->model->getPaymentVoucherAmount() . "',
                       `paymentVoucherChequeNumber` = '" . $this->model->getPaymentVoucherChequeNumber() . "',
                       `paymentVoucherPayee` = '" . $this->model->getPaymentVoucherPayee() . "',
                       `isDefault` = '" . $this->model->getIsDefault('0', 'single') . "',
                       `isNew` = '" . $this->model->getIsNew('0', 'single') . "',
                       `isDraft` = '" . $this->model->getIsDraft('0', 'single') . "',
                       `isUpdate` = '" . $this->model->getIsUpdate('0', 'single') . "',
                       `isDelete` = '" . $this->model->getIsDelete('0', 'single') . "',
                       `isActive` = '" . $this->model->getIsActive('0', 'single') . "',
                       `isApproved` = '" . $this->model->getIsApproved('0', 'single') . "',
                       `isReview` = '" . $this->model->getIsReview('0', 'single') . "',
                       `isPost` = '" . $this->model->getIsPost('0', 'single') . "',
                       `isPrinted` = '" . $this->model->getIsPrinted() . "',
                       `isConform` = '" . $this->model->getIsConform() . "',
                       `isChequePrinted` = '" . $this->model->getIsChequePrinted() . "',
                       `executeBy` = '" . $this->model->getExecuteBy('0', 'single') . "',
                       `executeTime` = " . $this->model->getExecuteTime() . "
               WHERE    `paymentVoucherId`='" . $this->model->getPaymentVoucherId('0', 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
                UPDATE [paymentVoucher] SET
                       [bankId] = '" . $this->model->getBankId() . "',
                       [businessPartnerCategoryId] = '" . $this->model->getBusinessPartnerCategoryId() . "',
                       [businessPartnerId] = '" . $this->model->getBusinessPartnerId() . "',
                       [paymentTypeId] = '" . $this->model->getPaymentTypeId() . "',
                       [documentNumber] = '" . $this->model->getDocumentNumber() . "',
                       [referenceNumber] = '" . $this->model->getReferenceNumber() . "',
                       [paymentVoucherDescription] = '" . $this->model->getPaymentVoucherDescription() . "',
                       [paymentVoucherDate] = '" . $this->model->getPaymentVoucherDate() . "',
					   [paymentVoucherChequeDate] = '" . $this->model->getPaymentVoucherChequeDate() . "',
                       [paymentVoucherAmount] = '" . $this->model->getPaymentVoucherAmount() . "',
                       [paymentVoucherChequeNumber] = '" . $this->model->getPaymentVoucherChequeNumber() . "',
                       [paymentVoucherPayee] = '" . $this->model->getPaymentVoucherPayee() . "',
                       [isDefault] = '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew] = '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft] = '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate] = '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete] = '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive] = '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved] = '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview] = '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost] = '" . $this->model->getIsPost(0, 'single') . "',
                       [isPrinted] = '" . $this->model->getIsPrinted() . "',
                       [isConform] = '" . $this->model->getIsConform() . "',
                       [isChequePrinted] = '" . $this->model->getIsChequePrinted() . "',
                       [executeBy] = '" . $this->model->getExecuteBy(0, 'single') . "',
                       [executeTime] = " . $this->model->getExecuteTime() . "
                WHERE   [paymentVoucherId]='" . $this->model->getPaymentVoucherId('0', 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
                UPDATE PAYMENTVOUCHER SET
                        BANKID = '" . $this->model->getBankId() . "',
                       BUSINESSPARTNERCATEGORYID = '" . $this->model->getBusinessPartnerCategoryId() . "',
                       BUSINESSPARTNERID = '" . $this->model->getBusinessPartnerId() . "',
                       PAYMENTTYPEID = '" . $this->model->getPaymentTypeId() . "',
                       DOCUMENTNUMBER = '" . $this->model->getDocumentNumber() . "',
                       REFERENCENUMBER = '" . $this->model->getReferenceNumber() . "',
                       PAYMENTVOUCHERDESCRIPTION = '" . $this->model->getPaymentVoucherDescription() . "',
                       PAYMENTVOUCHERDATE = '" . $this->model->getPaymentVoucherDate() . "',
					   PAYMENTVOUCHERCHEQUEDATE = '" . $this->model->getPaymentVoucherChequeDate() . "',
                       PAYMENTVOUCHERAMOUNT = '" . $this->model->getPaymentVoucherAmount() . "',
                       PAYMENTVOUCHERCHEQUENUMBER = '" . $this->model->getPaymentVoucherChequeNumber() . "',
                       PAYMENTVOUCHERPAYEE = '" . $this->model->getPaymentVoucherPayee() . "',
                       ISDEFAULT = '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW = '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT = '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE = '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE = '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE = '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED = '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW = '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST = '" . $this->model->getIsPost(0, 'single') . "',
                       ISPRINTED = '" . $this->model->getIsPrinted() . "',
                       ISCONFORM = '" . $this->model->getIsConform() . "',
                       ISCHEQUEPRINTED = '" . $this->model->getIsChequePrinted() . "',
                       EXECUTEBY = '" . $this->model->getExecuteBy(0, 'single') . "',
                       EXECUTETIME = " . $this->model->getExecuteTime() . "
                WHERE  PAYMENTVOUCHERID='" . $this->model->getPaymentVoucherId('0', 'single') . "'";
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
           FROM 	`paymentvoucher`
           WHERE  	`" . $this->model->getPrimaryKeyName() . "` = '" . $this->model->getPaymentVoucherId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
           SELECT	[" . $this->model->getPrimaryKeyName() . "]
           FROM 	[paymentVoucher]
           WHERE  	[" . $this->model->getPrimaryKeyName() . "] = '" . $this->model->getPaymentVoucherId(
                            0, 'single'
                    ) . "' ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           SELECT	" . strtoupper($this->model->getPrimaryKeyName()) . "
           FROM 	PAYMENTVOUCHER
           WHERE  	" . strtoupper($this->model->getPrimaryKeyName()) . " = '" . $this->model->getPaymentVoucherId(
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
               UPDATE  `paymentvoucher`
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
               WHERE   `paymentVoucherId`   =  '" . $this->model->getPaymentVoucherId(0, 'single') . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [paymentVoucher]
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
               WHERE   [paymentVoucherId]	=  '" . $this->model->getPaymentVoucherId(0, 'single') . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
               UPDATE  PAYMENTVOUCHER
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
               WHERE   PAYMENTVOUCHERID	=  '" . $this->model->getPaymentVoucherId(0, 'single') . "'";
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
     * Return  Bank
     * @return null|string
     */
    public function getBank() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBank();
    }

    /**
     * Return  Business Partner Category
     * @return null|string
     */
    public function getBusinessPartnerCategory() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getBusinessPartnerCategory();
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
     * Return  Chart Of Account
     * @return null|string
     */
    public function getChartOfAccount() {
        $this->service->setServiceOutput($this->getServiceOutput());
        return $this->service->getChartOfAccount();
    }

    /**
     * Set Cheque / Check Number,Date
     * @return void
     * @throws \Exception
     */
    public function setChequeInformation() {
        $this->service->setChequeInformation(
                $this->model->getPaymentVoucherChequeNumber(), $this->model->getPaymentVoucherChequeDate(), $this->model->getPaymentVoucherId(0, 'single')
        );
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
                    $this->model->getPaymentVoucherId(0, 'single'), $this->getLeafId(), $this->model->getFrom()
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
         FROM    `paymentvoucher`
         WHERE   `isActive`=1
         AND     `companyId`=" . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT    COUNT(*) AS total
         FROM      [paymentVoucher]
         WHERE     [isActive]  =   1
         AND       [companyId] =   " . $this->getCompanyId() . " ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT    COUNT(*)    AS  \"total\"
         FROM      PAYMENTVOUCHER
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
                ->setSubject('paymentVoucher')
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
        $this->excel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $this->excel->getActiveSheet()->setCellValue('B2', $this->getReportTitle());
        $this->excel->getActiveSheet()->setCellValue('R2', '');
        $this->excel->getActiveSheet()->mergeCells('B2:R2');
        $this->excel->getActiveSheet()->setCellValue('B3', 'No.');
        $this->excel->getActiveSheet()->setCellValue('C3', $this->translate['bankIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('D3', $this->translate['businessPartnerCategoryIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('E3', $this->translate['businessPartnerIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('F3', $this->translate['paymentTypeIdLabel']);
        $this->excel->getActiveSheet()->setCellValue('G3', $this->translate['documentNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('H3', $this->translate['referenceNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('I3', $this->translate['paymentVoucherDescriptionLabel']);
        $this->excel->getActiveSheet()->setCellValue('J3', $this->translate['paymentVoucherDateLabel']);
        $this->excel->getActiveSheet()->setCellValue('K3', $this->translate['paymentVoucherAmountLabel']);
        $this->excel->getActiveSheet()->setCellValue('L3', $this->translate['paymentVoucherChequeNumberLabel']);
        $this->excel->getActiveSheet()->setCellValue('M3', $this->translate['paymentVoucherPayeeLabel']);
        $this->excel->getActiveSheet()->setCellValue('N3', $this->translate['isPrintedLabel']);
        $this->excel->getActiveSheet()->setCellValue('O3', $this->translate['isConformLabel']);
        $this->excel->getActiveSheet()->setCellValue('P3', $this->translate['isChequePrintedLabel']);
        $this->excel->getActiveSheet()->setCellValue('Q3', $this->translate['executeByLabel']);
        $this->excel->getActiveSheet()->setCellValue('R3', $this->translate['executeTimeLabel']);
        //
        $loopRow = 4;
        $i = 0;
        \PHPExcel_Cell::setValueBinder(new \PHPExcel_Cell_AdvancedValueBinder());
        $lastRow = null;
        while (($row = $this->q->fetchAssoc()) == true) {
            //	echo print_r($row);
            $this->excel->getActiveSheet()->setCellValue('B' . $loopRow, ++$i);
            $this->excel->getActiveSheet()->setCellValue('C' . $loopRow, strip_tags($row ['bankDescription']));
            $this->excel->getActiveSheet()->setCellValue(
                    'D' . $loopRow, strip_tags($row ['businessPartnerCategoryDescription'])
            );
            $this->excel->getActiveSheet()->setCellValue('E' . $loopRow, strip_tags($row ['businessPartnerCompany']));
            $this->excel->getActiveSheet()->setCellValue('F' . $loopRow, strip_tags($row ['paymentTypeDescription']));
            $this->excel->getActiveSheet()->setCellValue('G' . $loopRow, strip_tags($row ['documentNumber']));
            $this->excel->getActiveSheet()->setCellValue('H' . $loopRow, strip_tags($row ['referenceNumber']));
            $this->excel->getActiveSheet()->setCellValue(
                    'I' . $loopRow, strip_tags($row ['paymentVoucherDescription'])
            );
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('J' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $this->excel->getActiveSheet()->setCellValue('J' . $loopRow, strip_tags($row ['paymentVoucherDate']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('K' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
            );
            $this->excel->getActiveSheet()->setCellValue('K' . $loopRow, strip_tags($row ['paymentVoucherAmount']));
            $this->excel->getActiveSheet()->setCellValue(
                    'L' . $loopRow, strip_tags($row ['paymentVoucherChequeNumber'])
            );
            $this->excel->getActiveSheet()->setCellValue('M' . $loopRow, strip_tags($row ['paymentVoucherPayee']));
            $this->excel->getActiveSheet()->setCellValue('N' . $loopRow, strip_tags($row ['isPrinted']));
            $this->excel->getActiveSheet()->setCellValue('O' . $loopRow, strip_tags($row ['isConform']));
            $this->excel->getActiveSheet()->setCellValue('P' . $loopRow, strip_tags($row ['isChequePrinted']));
            $this->excel->getActiveSheet()->setCellValue('Q' . $loopRow, strip_tags($row ['staffName']));
            $this->excel->getActiveSheet()->setCellValue('R' . $loopRow, strip_tags($row ['executeTime']));
            $this->excel->getActiveSheet()->getStyle()->getNumberFormat('R' . $loopRow)->setFormatCode(
                    \PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDDSLASH
            );
            $loopRow++;
            $lastRow = 'R' . $loopRow;
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
                $filename = "paymentVoucher" . rand(0, 10000000) . $extension;
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
                $filename = "paymentVoucher" . rand(0, 10000000) . $extension;
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
                $filename = "paymentVoucher" . rand(0, 10000000) . $extension;
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
                $filename = "paymentVoucher" . rand(0, 10000000) . $extension;
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
        $paymentVoucherObject = new PaymentVoucherClass ();
        if ($_POST['securityToken'] != $paymentVoucherObject->getSecurityToken()) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
            exit();
        }
        /*
         *  Load the dynamic value
         */
        if (isset($_POST ['leafId'])) {
            $paymentVoucherObject->setLeafId($_POST ['leafId']);
        }
        if (isset($_POST ['offset'])) {
            $paymentVoucherObject->setStart($_POST ['offset']);
        }
        if (isset($_POST ['limit'])) {
            $paymentVoucherObject->setLimit($_POST ['limit']);
        }
        $paymentVoucherObject->setPageOutput($_POST['output']);
        $paymentVoucherObject->execute();
        /*
         *  Crud Operation (Create Read Update Delete/Destroy)
         */
        if ($_POST ['method'] == 'create') {
            $paymentVoucherObject->create();
        }
        if ($_POST ['method'] == 'save') {
            $paymentVoucherObject->update();
        }
        if ($_POST ['method'] == 'read') {
            $paymentVoucherObject->read();
        }
        if ($_POST ['method'] == 'delete') {
            $paymentVoucherObject->delete();
        }
        if ($_POST ['method'] == 'posting') {
            $paymentVoucherObject->posting();
        }
        if ($_POST ['method'] == 'reverse') {
            //	$paymentVoucherObject->delete();
        }
        if ($_POST ['method'] == 'updateCheque') {
            $paymentVoucherObject->setChequeInformation();
        }
    }
}
if (isset($_GET ['method'])) {
    $paymentVoucherObject = new PaymentVoucherClass ();
    if ($_GET['securityToken'] != $paymentVoucherObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    /*
     *  initialize Value before load in the loader
     */
    if (isset($_GET ['leafId'])) {
        $paymentVoucherObject->setLeafId($_GET ['leafId']);
    }
    /*
     *  Load the dynamic value
     */
    $paymentVoucherObject->execute();
    /*
     * Update Status of The Table. Admin Level Only
     */
    if ($_GET ['method'] == 'updateStatus') {
        $paymentVoucherObject->updateStatus();
    }
    if ($_GET ['method'] == 'posting') {
        $paymentVoucherObject->posting();
    }
    /*
     *  Checking Any Duplication  Key
     */
    if ($_GET['method'] == 'duplicate') {
        $paymentVoucherObject->duplicate();
    }
    if ($_GET ['method'] == 'dataNavigationRequest') {
        if ($_GET ['dataNavigation'] == 'firstRecord') {
            $paymentVoucherObject->firstRecord('json');
        }
        if ($_GET ['dataNavigation'] == 'previousRecord') {
            $paymentVoucherObject->previousRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'nextRecord') {
            $paymentVoucherObject->nextRecord('json', 0);
        }
        if ($_GET ['dataNavigation'] == 'lastRecord') {
            $paymentVoucherObject->lastRecord('json');
        }
    }
    /*
     * Excel Reporting
     */
    if (isset($_GET ['mode'])) {
        $paymentVoucherObject->setReportMode($_GET['mode']);
        if ($_GET ['mode'] == 'excel' || $_GET ['mode'] == 'pdf' || $_GET['mode'] == 'csv' || $_GET['mode'] == 'html' || $_GET['mode'] == 'excel5' || $_GET['mode'] == 'xml'
        ) {
            $paymentVoucherObject->excel();
        }
    }
    if (isset($_GET ['filter'])) {
        $paymentVoucherObject->setServiceOutput('option');
        if (($_GET['filter'] == 'bank')) {
            $paymentVoucherObject->getBank();
        }
        if (($_GET['filter'] == 'businessPartnerCategory')) {
            $paymentVoucherObject->getBusinessPartnerCategory();
        }
        if (($_GET['filter'] == 'businessPartner')) {
            $paymentVoucherObject->getBusinessPartner();
        }
    }
}
?>
