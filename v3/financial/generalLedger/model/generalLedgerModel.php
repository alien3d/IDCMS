<?php

namespace Core\Financial\GeneralLedger\GeneralLedger\Model;

use Core\Validation\ValidationClass;

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
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class GeneralLedger
 * This is generalLedger model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\GeneralLedger\Model;
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class GeneralLedgerModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $generalLedgerId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Finance Year
     * @var int
     */
    private $financeYearId;

    /**
     * Finance Year
     * @var int
     */
    private $financeYearYear;

    /**
     * Finance Range
     * @var int
     */
    private $financePeriodRangeId;

    /**
     * Finance Period Range   Period
     * @var int
     */
    private $financePeriodRangePeriod;

    /**
     * Journal Number
     * @var string
     */
    private $journalNumber;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Document Date
     * @var string
     */
    private $documentDate;

    /**
     * Title
     * @var string
     */
    private $generalLedgerTitle;

    /**
     * Description
     * @var string
     */
    private $generalLedgerDescription;

    /**
     * Date
     * @var string
     */
    private $generalLedgerDate;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Country Code
     * @var string
     */
    private $countryCurrencyCode;

    /**
     * Transaction Type
     * @var int
     */
    private $transactionTypeId;

    /**
     * Transaction Code
     * @var string
     */
    private $transactionTypeCode;

    /**
     * Transaction Description
     * @var string
     */
    private $transactionTypeDescription;

    /**
     * Foreign Amount
     * @var double
     */
    private $foreignAmount;

    /**
     * Local Amount
     * @var double
     */
    private $localAmount;

    /**
     * Chart Of Account Category
     * @var int
     */
    private $chartOfAccountCategoryId;

    /**
     * Chart Of Account Category Code
     * @var string
     */
    private $chartOfAccountCategoryCode;

    /**
     * Chart Of Account Category Description
     * @var string
     */
    private $chartOfAccountCategoryDescription;

    /**
     * Chart Of Account Type
     * @var int
     */
    private $chartOfAccountTypeId;

    /**
     * Chart Of Account Type Code
     * @var string
     */
    private $chartOfAccountTypeCode;

    /**
     * Chart Of Account Type Description
     * @var string
     */
    private $chartOfAccountTypeDescription;

    /**
     * Reference Name
     * @var string
     */
    private $referenceTableNameId;

    /**
     * Chart Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Chart Of Account Number
     * @var string
     */
    private $chartOfAccountNumber;

    /**
     * Chart Of Account Description
     * @var string
     */
    private $chartOfAccountDescription;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Business Description
     * @var string
     */
    private $businessPartnerDescription;

    /**
     * Module
     * @var string
     */
    private $module;

    /**
     * Table Name
     * @var string
     */
    private $tableName;

    /**
     * Table Name
     * @var int
     */
    private $tableNameId;

    /**
     * Table Detail
     * @var string
     */
    private $tableNameDetail;

    /**
     * Table Detail
     * @var int
     */
    private $tableNameDetailId;

    /**
     * Leaf
     * @var int
     */
    private $leafId;

    /**
     * Leaf Name
     * @var string
     */
    private $leafName;

    /**
     * Is Merge
     * @var bool
     */
    private $isMerge;

    /**
     * Is Slice
     * @var bool
     */
    private $isSlice;

    /**
     * Is Authorized
     * @var bool
     */
    private $isAuthorized;

    /**
     * Execute Name
     * @var string
     */
    private $executeName;

    /**
     * Bank
     * @var int
     */
    private $bankId;
    // fast payment voucher
    /**
     * Payment Voucher
     * @var int
     */
    private $paymentVoucherId;

    /**
     * Period
     * @var string
     */
    private $paymentVoucherDate;

    /**
     * Period
     * @var string
     */
    private $paymentVoucherAmount;

    /**
     * Period
     * @var string
     */
    private $paymentVoucherDescription;

    /**
     * Payment Voucher Detail
     * @var string
     */
    private $paymentVoucherDetailId;
    // fast collection
    /**
     * Collection
     * @var string
     */
    private $collectionId;

    /**
     * Period
     * @var string
     */
    private $collectionDate;

    /**
     * Period
     * @var double
     */
    private $collectionAmount;

    /**
     * Period
     * @var string
     */
    private $collectionDescription;

    /**
     * Collection Detail
     * @var int
     */
    private $collectionDetailId;
    // additional
    /**
     * Period
     * @var int
     */
    private $financeYear;

    /**
     * Period
     * @var int
     */
    private $financePeriod;

    /**
     * Budget Amount
     * @var int
     */
    private $budgetId;

    /**
     * Budget Amount
     * @var double
     */
    private $budgetAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('generalLedger');
        $this->setPrimaryKeyName('generalLedgerId');
        $this->setMasterForeignKeyName('generalLedgerId');
        $this->setFilterCharacter('generalLedgerDescription');
        //$this->setFilterCharacter('generalLedgerNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['generalLedgerId'])) {
            $this->setGeneralLedgerId($this->strict($_POST ['generalLedgerId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_POST ['financeYearId'], 'integer'));
        }
        if (isset($_POST ['financeYearYear'])) {
            $this->setFinanceYearYear($this->strict($_POST ['financeYearYear'], 'integer'));
        }
        if (isset($_POST ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_POST ['financePeriodRangeId'], 'integer'));
        }
        if (isset($_POST ['financePeriodRangePeriod'])) {
            $this->setFinancePeriodRangePeriod($this->strict($_POST ['financePeriodRangePeriod'], 'integer'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['documentDate'])) {
            $this->setDocumentDate($this->strict($_POST ['documentDate'], 'date'));
        }
        if (isset($_POST ['generalLedgerTitle'])) {
            $this->setGeneralLedgerTitle($this->strict($_POST ['generalLedgerTitle'], 'string'));
        }
        if (isset($_POST ['generalLedgerDescription'])) {
            $this->setGeneralLedgerDescription($this->strict($_POST ['generalLedgerDescription'], 'string'));
        }
        if (isset($_POST ['generalLedgerDate'])) {
            $this->setGeneralLedgerDate($this->strict($_POST ['generalLedgerDate'], 'date'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'integer'));
        }
        if (isset($_POST ['countryCurrencyCode'])) {
            $this->setCountryCurrencyCode($this->strict($_POST ['countryCurrencyCode'], 'string'));
        }
        if (isset($_POST ['transactionTypeId'])) {
            $this->setTransactionTypeId($this->strict($_POST ['transactionTypeId'], 'integer'));
        }
        if (isset($_POST ['transactionTypeCode'])) {
            $this->setTransactionTypeCode($this->strict($_POST ['transactionTypeCode'], 'string'));
        }
        if (isset($_POST ['transactionTypeDescription'])) {
            $this->setTransactionTypeDescription($this->strict($_POST ['transactionTypeDescription'], 'string'));
        }
        if (isset($_POST ['foreignAmount'])) {
            $this->setForeignAmount($this->strict($_POST ['foreignAmount'], 'double'));
        }
        if (isset($_POST ['localAmount'])) {
            $this->setLocalAmount($this->strict($_POST ['localAmount'], 'double'));
        }
        if (isset($_POST ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_POST ['chartOfAccountCategoryId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountCategoryCode'])) {
            $this->setChartOfAccountCategoryCode($this->strict($_POST ['chartOfAccountCategoryCode'], 'string'));
        }
        if (isset($_POST ['chartOfAccountCategoryDescription'])) {
            $this->setChartOfAccountCategoryDescription(
                    $this->strict($_POST ['chartOfAccountCategoryDescription'], 'string')
            );
        }
        if (isset($_POST ['chartOfAccountTypeId'])) {
            $this->setChartOfAccountTypeId($this->strict($_POST ['chartOfAccountTypeId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountTypeCode'])) {
            $this->setChartOfAccountTypeCode($this->strict($_POST ['chartOfAccountTypeCode'], 'string'));
        }
        if (isset($_POST ['chartOfAccountTypeDescription'])) {
            $this->setChartOfAccountTypeDescription($this->strict($_POST ['chartOfAccountTypeDescription'], 'string'));
        }
        if (isset($_POST ['referenceTableNameId'])) {
            $this->setReferenceTableNameId($this->strict($_POST ['referenceTableNameId'], 'string'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountNumber'])) {
            $this->setChartOfAccountNumber($this->strict($_POST ['chartOfAccountNumber'], 'string'));
        }
        if (isset($_POST ['chartOfAccountDescription'])) {
            $this->setChartOfAccountDescription($this->strict($_POST ['chartOfAccountDescription'], 'string'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'integer'));
        }
        if (isset($_POST ['businessPartnerDescription'])) {
            $this->setBusinessPartnerDescription($this->strict($_POST ['businessPartnerDescription'], 'string'));
        }
        if (isset($_POST ['module'])) {
            $this->setModule($this->strict($_POST ['module'], 'string'));
        }
        if (isset($_POST ['tableName'])) {
            $this->setTableName($this->strict($_POST ['tableName'], 'string'));
        }
        if (isset($_POST ['tableNameId'])) {
            $this->setTableNameId($this->strict($_POST ['tableNameId'], 'integer'));
        }
        if (isset($_POST ['tableNameDetail'])) {
            $this->setTableNameDetail($this->strict($_POST ['tableNameDetail'], 'string'));
        }
        if (isset($_POST ['tableNameDetailId'])) {
            $this->setTableNameDetailId($this->strict($_POST ['tableNameDetailId'], 'integer'));
        }
        if (isset($_POST ['leafId'])) {
            $this->setLeafId($this->strict($_POST ['leafId'], 'integer'));
        }
        if (isset($_POST ['leafName'])) {
            $this->setLeafName($this->strict($_POST ['leafName'], 'string'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        if (isset($_POST ['isMerge'])) {
            $this->setIsMerge($this->strict($_POST ['isMerge'], 'bool'));
        }
        if (isset($_POST ['isSlice'])) {
            $this->setIsSlice($this->strict($_POST ['isSlice'], 'bool'));
        }
        if (isset($_POST ['isAuthorized'])) {
            $this->setIsAuthorized($this->strict($_POST ['isAuthorized'], 'bool'));
        }
        if (isset($_POST ['executeName'])) {
            $this->setExecuteName($this->strict($_POST ['executeName'], 'string'));
        }
        if (isset($_POST ['bankId'])) {
            $this->setBankId($this->strict($_POST ['bankId'], 'integer'));
        }
        if (isset($_POST ['paymentVoucherId'])) {
            $this->setPaymentVoucherId($this->strict($_POST ['paymentVoucherId'], 'integer'));
        }
        if (isset($_POST ['paymentVoucherDate'])) {
            $this->setPaymentVoucherDate($this->strict($_POST ['paymentVoucherDate'], 'date'));
        }
        if (isset($_POST ['paymentVoucherAmount'])) {
            $this->setPaymentVoucherAmount($this->strict($_POST ['paymentVoucherAmount'], 'currency'));
        }
        if (isset($_POST ['paymentVoucherDescription'])) {
            $this->setPaymentVoucherDescription($this->strict($_POST ['paymentVoucherDescription'], 'string'));
        }
        if (isset($_POST ['collectionId'])) {
            $this->setCollectionId($this->strict($_POST ['collectionId'], 'integer'));
        }
        if (isset($_POST ['collectionDate'])) {
            $this->setCollectionDate($this->strict($_POST ['collectionDate'], 'date'));
        }
        if (isset($_POST ['collectionAmount'])) {
            $this->setCollectionAmount($this->strict($_POST ['collectionAmount'], 'currency'));
        }
        if (isset($_POST ['collectionDescription'])) {
            $this->setCollectionDescription($this->strict($_POST ['collectionDescription'], 'string'));
        }
        if (isset($_POST ['financeYear'])) {
            $this->setFinanceYear($this->strict($_POST ['financeYear'], 'integer'));
        }
        if (isset($_POST ['financePeriod'])) {
            $this->setFinancePeriod($this->strict($_POST ['financePeriod'], 'integer'));
        }
        if (isset($_POST ['budgetId'])) {
            $this->setBudgetId($this->strict($_POST ['budgetId'], 'integer'));
        }
        if (isset($_POST ['budgetAmount'])) {
            $this->setBudgetAmount($this->strict($_POST ['budgetAmount'], 'currency'));
        }
        if (isset($_POST ['paymentVoucherDetailId'])) {
            $this->setPaymentVoucherDetailId($this->strict($_POST ['paymentVoucherDetailId'], 'integer'));
        }
        if (isset($_POST['collectionDetailId'])) {
            $this->setCollectionDetailId($this->strict($_POST ['collectionDetailId'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['generalLedgerId'])) {
            $this->setGeneralLedgerId($this->strict($_GET ['generalLedgerId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_GET ['financeYearId'], 'integer'));
        }
        if (isset($_GET ['financeYearYear'])) {
            $this->setFinanceYearYear($this->strict($_GET ['financeYearYear'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_GET ['financePeriodRangeId'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangePeriod'])) {
            $this->setFinancePeriodRangePeriod($this->strict($_GET ['financePeriodRangePeriod'], 'integer'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['documentDate'])) {
            $this->setDocumentDate($this->strict($_GET ['documentDate'], 'date'));
        }
        if (isset($_GET ['generalLedgerTitle'])) {
            $this->setGeneralLedgerTitle($this->strict($_GET ['generalLedgerTitle'], 'string'));
        }
        if (isset($_GET ['generalLedgerDescription'])) {
            $this->setGeneralLedgerDescription($this->strict($_GET ['generalLedgerDescription'], 'string'));
        }
        if (isset($_GET ['generalLedgerDate'])) {
            $this->setGeneralLedgerDate($this->strict($_GET ['generalLedgerDate'], 'date'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'integer'));
        }
        if (isset($_GET ['countryCurrencyCode'])) {
            $this->setCountryCurrencyCode($this->strict($_GET ['countryCurrencyCode'], 'string'));
        }
        if (isset($_GET ['transactionTypeId'])) {
            $this->setTransactionTypeId($this->strict($_GET ['transactionTypeId'], 'integer'));
        }
        if (isset($_GET ['transactionTypeCode'])) {
            $this->setTransactionTypeCode($this->strict($_GET ['transactionTypeCode'], 'string'));
        }
        if (isset($_GET ['transactionTypeDescription'])) {
            $this->setTransactionTypeDescription($this->strict($_GET ['transactionTypeDescription'], 'string'));
        }
        if (isset($_GET ['foreignAmount'])) {
            $this->setForeignAmount($this->strict($_GET ['foreignAmount'], 'double'));
        }
        if (isset($_GET ['localAmount'])) {
            $this->setLocalAmount($this->strict($_GET ['localAmount'], 'double'));
        }
        if (isset($_GET ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_GET ['chartOfAccountCategoryId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountCategoryCode'])) {
            $this->setChartOfAccountCategoryCode($this->strict($_GET ['chartOfAccountCategoryCode'], 'string'));
        }
        if (isset($_GET ['chartOfAccountCategoryDescription'])) {
            $this->setChartOfAccountCategoryDescription(
                    $this->strict($_GET ['chartOfAccountCategoryDescription'], 'string')
            );
        }
        if (isset($_GET ['chartOfAccountTypeId'])) {
            $this->setChartOfAccountTypeId($this->strict($_GET ['chartOfAccountTypeId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountTypeCode'])) {
            $this->setChartOfAccountTypeCode($this->strict($_GET ['chartOfAccountTypeCode'], 'string'));
        }
        if (isset($_GET ['chartOfAccountTypeDescription'])) {
            $this->setChartOfAccountTypeDescription($this->strict($_GET ['chartOfAccountTypeDescription'], 'string'));
        }
        if (isset($_GET ['referenceTableNameId'])) {
            $this->setReferenceTableNameId($this->strict($_GET ['referenceTableNameId'], 'string'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountNumber'])) {
            $this->setChartOfAccountNumber($this->strict($_GET ['chartOfAccountNumber'], 'string'));
        }
        if (isset($_GET ['chartOfAccountDescription'])) {
            $this->setChartOfAccountDescription($this->strict($_GET ['chartOfAccountDescription'], 'string'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'integer'));
        }
        if (isset($_GET ['businessPartnerDescription'])) {
            $this->setBusinessPartnerDescription($this->strict($_GET ['businessPartnerDescription'], 'string'));
        }
        if (isset($_GET ['module'])) {
            $this->setModule($this->strict($_GET ['module'], 'string'));
        }
        if (isset($_GET ['tableName'])) {
            $this->setTableName($this->strict($_GET ['tableName'], 'string'));
        }
        if (isset($_GET ['tableNameId'])) {
            $this->setTableNameId($this->strict($_GET ['tableNameId'], 'integer'));
        }
        if (isset($_GET ['tableNameDetail'])) {
            $this->setTableNameDetail($this->strict($_GET ['tableNameDetail'], 'string'));
        }
        if (isset($_GET ['tableNameDetailId'])) {
            $this->setTableNameDetailId($this->strict($_GET ['tableNameDetailId'], 'integer'));
        }
        if (isset($_GET ['leafId'])) {
            $this->setLeafId($this->strict($_GET ['leafId'], 'integer'));
        }
        if (isset($_GET ['leafName'])) {
            $this->setLeafName($this->strict($_GET ['leafName'], 'string'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['isMerge'])) {
            $this->setIsMerge($this->strict($_GET ['isMerge'], 'bool'));
        }
        if (isset($_GET ['isSlice'])) {
            $this->setIsSlice($this->strict($_GET ['isSlice'], 'bool'));
        }
        if (isset($_GET ['isAuthorized'])) {
            $this->setIsAuthorized($this->strict($_GET ['isAuthorized'], 'bool'));
        }
        if (isset($_GET ['executeName'])) {
            $this->setExecuteName($this->strict($_GET ['executeName'], 'string'));
        }
        if (isset($_GET ['bankId'])) {
            $this->setBankId($this->strict($_GET ['bankId'], 'integer'));
        }
        if (isset($_GET ['paymentVoucherId'])) {
            $this->setPaymentVoucherId($this->strict($_GET ['paymentVoucherId'], 'integer'));
        }
        if (isset($_GET ['paymentVoucherDate'])) {
            $this->setPaymentVoucherDate($this->strict($_GET ['paymentVoucherDate'], 'date'));
        }
        if (isset($_GET ['paymentVoucherAmount'])) {
            $this->setPaymentVoucherAmount($this->strict($_GET ['paymentVoucherAmount'], 'currency'));
        }
        if (isset($_GET ['paymentVoucherDescription'])) {
            $this->setPaymentVoucherDescription($this->strict($_GET ['paymentVoucherDescription'], 'string'));
        }
        if (isset($_GET ['collectionId'])) {
            $this->setCollectionId($this->strict($_GET ['collectionId'], 'integer'));
        }
        if (isset($_GET ['collectionDate'])) {
            $this->setCollectionDate($this->strict($_GET ['collectionDate'], 'date'));
        }
        if (isset($_GET ['collectionAmount'])) {
            $this->setCollectionAmount($this->strict($_GET ['collectionAmount'], 'currency'));
        }
        if (isset($_GET ['collectionDescription'])) {
            $this->setCollectionDescription($this->strict($_GET ['collectionDescription'], 'string'));
        }
        if (isset($_GET ['financeYear'])) {
            $this->setFinanceYear($this->strict($_GET ['financeYear'], 'integer'));
        }
        if (isset($_GET ['financePeriod'])) {
            $this->setFinancePeriod($this->strict($_GET ['financePeriod'], 'integer'));
        }
        if (isset($_GET ['budgetId'])) {
            $this->setBudgetId($this->strict($_GET ['budgetId'], 'integer'));
        }
        if (isset($_GET ['budgetAmount'])) {
            $this->setBudgetAmount($this->strict($_GET ['budgetAmount'], 'currency'));
        }
        if (isset($_GET ['paymentVoucherDetailId'])) {
            $this->setPaymentVoucherDetailId($this->strict($_GET ['paymentVoucherDetailId'], 'integer'));
        }
        if (isset($_GET ['collectionDetailId'])) {
            $this->setCollectionDetailId($this->strict($_GET ['collectionDetailId'], 'integer'));
        }
        if (isset($_GET ['generalLedgerId'])) {
            $this->setTotal(count($_GET ['generalLedgerId']));
            if (is_array($_GET ['generalLedgerId'])) {
                $this->generalLedgerId = array();
            }
        }
        if (isset($_GET ['isDefault'])) {
            $this->setIsDefaultTotal(count($_GET['isDefault']));
            if (is_array($_GET ['isDefault'])) {
                $this->isDefault = array();
            }
        }
        if (isset($_GET ['isNew'])) {
            $this->setIsNewTotal(count($_GET['isNew']));
            if (is_array($_GET ['isNew'])) {
                $this->isNew = array();
            }
        }
        if (isset($_GET ['isDraft'])) {
            $this->setIsDraftTotal(count($_GET['isDraft']));
            if (is_array($_GET ['isDraft'])) {
                $this->isDraft = array();
            }
        }
        if (isset($_GET ['isUpdate'])) {
            $this->setIsUpdateTotal(count($_GET['isUpdate']));
            if (is_array($_GET ['isUpdate'])) {
                $this->isUpdate = array();
            }
        }
        if (isset($_GET ['isDelete'])) {
            $this->setIsDeleteTotal(count($_GET['isDelete']));
            if (is_array($_GET ['isDelete'])) {
                $this->isDelete = array();
            }
        }
        if (isset($_GET ['isActive'])) {
            $this->setIsActiveTotal(count($_GET['isActive']));
            if (is_array($_GET ['isActive'])) {
                $this->isActive = array();
            }
        }
        if (isset($_GET ['isApproved'])) {
            $this->setIsApprovedTotal(count($_GET['isApproved']));
            if (is_array($_GET ['isApproved'])) {
                $this->isApproved = array();
            }
        }
        if (isset($_GET ['isReview'])) {
            $this->setIsReviewTotal(count($_GET['isReview']));
            if (is_array($_GET ['isReview'])) {
                $this->isReview = array();
            }
        }
        if (isset($_GET ['isPost'])) {
            $this->setIsPostTotal(count($_GET['isPost']));
            if (is_array($_GET ['isPost'])) {
                $this->isPost = array();
            }
        }
        $primaryKeyAll = '';
        for ($i = 0; $i < $this->getTotal(); $i++) {
            if (isset($_GET ['generalLedgerId'])) {
                $this->setGeneralLedgerId($this->strict($_GET ['generalLedgerId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else {
                    if ($_GET ['isDefault'] [$i] == 'false') {
                        $this->setIsDefault(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else {
                    if ($_GET ['isNew'] [$i] == 'false') {
                        $this->setIsNew(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else {
                    if ($_GET ['isDraft'] [$i] == 'false') {
                        $this->setIsDraft(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isUpdate'])) {
                if ($_GET ['isUpdate'] [$i] == 'true') {
                    $this->setIsUpdate(1, $i, 'array');
                }
                if ($_GET ['isUpdate'] [$i] == 'false') {
                    $this->setIsUpdate(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDelete'])) {
                if ($_GET ['isDelete'] [$i] == 'true') {
                    $this->setIsDelete(1, $i, 'array');
                } else {
                    if ($_GET ['isDelete'] [$i] == 'false') {
                        $this->setIsDelete(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else {
                    if ($_GET ['isActive'] [$i] == 'false') {
                        $this->setIsActive(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else {
                    if ($_GET ['isApproved'] [$i] == 'false') {
                        $this->setIsApproved(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else {
                    if ($_GET ['isReview'] [$i] == 'false') {
                        $this->setIsReview(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else {
                    if ($_GET ['isPost'] [$i] == 'false') {
                        $this->setIsPost(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getGeneralLedgerId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, -1)));
        /**
         * All the $_SESSION Environment
         */
        if (isset($_SESSION ['staffId'])) {
            $this->setExecuteBy($_SESSION ['staffId']);
        }
        /**
         * TimeStamp Value.
         */
        if ($this->getVendor() == self::MYSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s") . "'");
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                }
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getGeneralLedgerId($key, $type) {
        if ($type == 'single') {
            return $this->generalLedgerId;
        } else {
            if ($type == 'array') {
                return $this->generalLedgerId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getGeneralLedgerId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setGeneralLedgerId($value, $key, $type) {
        if ($type == 'single') {
            $this->generalLedgerId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->generalLedgerId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setGeneralLedgerId?")
                );
                exit();
            }
        }
    }

    /**
     * Create
     * @see ValidationClass::create()
     * @return void
     */
    public function create() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Update
     * @see ValidationClass::update()
     * @return void
     */
    public function update() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(1, '', 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Delete
     * @see ValidationClass::delete()
     * @return void
     */
    public function delete() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, '', 'single');
        $this->setIsDelete(1, '', 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Draft
     * @see ValidationClass::draft()
     * @return void
     */
    public function draft() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(1, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Approved
     * @see ValidationClass::approved()
     * @return void
     */
    public function approved() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Review
     * @see ValidationClass::review()
     * @return void
     */
    public function review() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(1, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Post
     * @see ValidationClass::post()
     * @return void
     */
    public function post() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(1, 0, 'single');
    }

    /**
     * To Return  Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return  FinanceYear
     * @return int $financeYearId
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * To Set FinanceYear
     * @param int $financeYearId Finance Year
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * To Return  financeYearYear
     * @return int $financeYearYear
     */
    public function getFinanceYearYear() {
        return $this->financeYearYear;
    }

    /**
     * To Set financeYearYear
     * @param int $financeYearYear Finance Year
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setFinanceYearYear($financeYearYear) {
        $this->financeYearYear = $financeYearYear;
        return $this;
    }

    /**
     * To Return  FinancePeriodRange
     * @return int $financePeriodRangeId
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * To Set FinancePeriodRange
     * @param int $financePeriodRangeId Finance Range
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * To Return  financePeriodRangePeriod
     * @return int $financePeriodRangePeriod
     */
    public function getFinancePeriodRangePeriod() {
        return $this->financePeriodRangePeriod;
    }

    /**
     * To Set financePeriodRangePeriod
     * @param int $financePeriodRangePeriod Finance Period Range   Period
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setFinancePeriodRangePeriod($financePeriodRangePeriod) {
        $this->financePeriodRangePeriod = $financePeriodRangePeriod;
        return $this;
    }

    /**
     * To Return  journalNumber
     * @return string $journalNumber
     */
    public function getJournalNumber() {
        return $this->journalNumber;
    }

    /**
     * To Set journalNumber
     * @param string $journalNumber Journal Number
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return  documentNumber
     * @return string $documentNumber
     */
    public function getDocumentNumber() {
        return $this->documentNumber;
    }

    /**
     * To Set documentNumber
     * @param string $documentNumber Document Number
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return  documentDate
     * @return string $documentDate
     */
    public function getDocumentDate() {
        return $this->documentDate;
    }

    /**
     * To Set documentDate
     * @param string $documentDate Document Date
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setDocumentDate($documentDate) {
        $this->documentDate = $documentDate;
        return $this;
    }

    /**
     * To Return  Title
     * @return string $generalLedgerTitle
     */
    public function getGeneralLedgerTitle() {
        return $this->generalLedgerTitle;
    }

    /**
     * To Set Title
     * @param string $generalLedgerTitle Title
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setGeneralLedgerTitle($generalLedgerTitle) {
        $this->generalLedgerTitle = $generalLedgerTitle;
        return $this;
    }

    /**
     * To Return  Description
     * @return string $generalLedgerDescription
     */
    public function getGeneralLedgerDescription() {
        return $this->generalLedgerDescription;
    }

    /**
     * To Set Description
     * @param string $generalLedgerDescription Description
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setGeneralLedgerDescription($generalLedgerDescription) {
        $this->generalLedgerDescription = $generalLedgerDescription;
        return $this;
    }

    /**
     * To Return  Date
     * @return string $generalLedgerDate
     */
    public function getGeneralLedgerDate() {
        return $this->generalLedgerDate;
    }

    /**
     * To Set Date
     * @param string $generalLedgerDate Date
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setGeneralLedgerDate($generalLedgerDate) {
        $this->generalLedgerDate = $generalLedgerDate;
        return $this;
    }

    /**
     * To Return  Country
     * @return int $countryId
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * To Set Country
     * @param int $countryId Country
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return  countryCurrencyCode
     * @return string $countryCurrencyCode
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * To Set countryCurrencyCode
     * @param string $countryCurrencyCode Country Code
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setCountryCurrencyCode($countryCurrencyCode) {
        $this->countryCurrencyCode = $countryCurrencyCode;
        return $this;
    }

    /**
     * To Return  TransactionType
     * @return int $transactionTypeId
     */
    public function getTransactionTypeId() {
        return $this->transactionTypeId;
    }

    /**
     * To Set TransactionType
     * @param int $transactionTypeId Transaction Type
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTransactionTypeId($transactionTypeId) {
        $this->transactionTypeId = $transactionTypeId;
        return $this;
    }

    /**
     * To Return  transactionTypeCode
     * @return string $transactionTypeCode
     */
    public function getTransactionTypeCode() {
        return $this->transactionTypeCode;
    }

    /**
     * To Set transactionTypeCode
     * @param string $transactionTypeCode Transaction Code
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTransactionTypeCode($transactionTypeCode) {
        $this->transactionTypeCode = $transactionTypeCode;
        return $this;
    }

    /**
     * To Return  transactionTypeDescription
     * @return string $transactionTypeDescription
     */
    public function getTransactionTypeDescription() {
        return $this->transactionTypeDescription;
    }

    /**
     * To Set transactionTypeDescription
     * @param string $transactionTypeDescription Transaction Description
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTransactionTypeDescription($transactionTypeDescription) {
        $this->transactionTypeDescription = $transactionTypeDescription;
        return $this;
    }

    /**
     * To Return  foreignAmount
     * @return double $foreignAmount
     */
    public function getForeignAmount() {
        return $this->foreignAmount;
    }

    /**
     * To Set foreignAmount
     * @param double $foreignAmount Foreign Amount
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setForeignAmount($foreignAmount) {
        $this->foreignAmount = $foreignAmount;
        return $this;
    }

    /**
     * To Return  localAmount
     * @return double $localAmount
     */
    public function getLocalAmount() {
        return $this->localAmount;
    }

    /**
     * To Set localAmount
     * @param double $localAmount Local Amount
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setLocalAmount($localAmount) {
        $this->localAmount = $localAmount;
        return $this;
    }

    /**
     * To Return  ChartOfAccountCategory
     * @return int $chartOfAccountCategoryId
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * To Set ChartOfAccountCategory
     * @param int $chartOfAccountCategoryId Chart Of Account   Category
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * To Return  chartOfAccountCategoryCode
     * @return string $chartOfAccountCategoryCode
     */
    public function getChartOfAccountCategoryCode() {
        return $this->chartOfAccountCategoryCode;
    }

    /**
     * To Set chartOfAccountCategoryCode
     * @param string $chartOfAccountCategoryCode Chart Of Account Category   Code
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountCategoryCode($chartOfAccountCategoryCode) {
        $this->chartOfAccountCategoryCode = $chartOfAccountCategoryCode;
        return $this;
    }

    /**
     * To Return  chartOfAccountCategoryDescription
     * @return string $chartOfAccountCategoryDescription
     */
    public function getChartOfAccountCategoryDescription() {
        return $this->chartOfAccountCategoryDescription;
    }

    /**
     * To Set chartOfAccountCategoryDescription
     * @param string $chartOfAccountCategoryDescription Chart Of Account Category   Description
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountCategoryDescription($chartOfAccountCategoryDescription) {
        $this->chartOfAccountCategoryDescription = $chartOfAccountCategoryDescription;
        return $this;
    }

    /**
     * To Return  ChartOfAccountType
     * @return int $chartOfAccountTypeId
     */
    public function getChartOfAccountTypeId() {
        return $this->chartOfAccountTypeId;
    }

    /**
     * To Set ChartOfAccountType
     * @param int $chartOfAccountTypeId Chart Of Account   Type
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * To Return  chartOfAccountTypeCode
     * @return string $chartOfAccountTypeCode
     */
    public function getChartOfAccountTypeCode() {
        return $this->chartOfAccountTypeCode;
    }

    /**
     * To Set chartOfAccountTypeCode
     * @param string $chartOfAccountTypeCode Chart Of Account Type   Code
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountTypeCode($chartOfAccountTypeCode) {
        $this->chartOfAccountTypeCode = $chartOfAccountTypeCode;
        return $this;
    }

    /**
     * To Return  chartOfAccountTypeDescription
     * @return string $chartOfAccountTypeDescription
     */
    public function getChartOfAccountTypeDescription() {
        return $this->chartOfAccountTypeDescription;
    }

    /**
     * To Set chartOfAccountTypeDescription
     * @param string $chartOfAccountTypeDescription Chart Of Account Type   Description
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountTypeDescription($chartOfAccountTypeDescription) {
        $this->chartOfAccountTypeDescription = $chartOfAccountTypeDescription;
        return $this;
    }

    /**
     * To Return  ReferenceTableName
     * @return string $referenceTableNameId
     */
    public function getReferenceTableNameId() {
        return $this->referenceTableNameId;
    }

    /**
     * To Set ReferenceTableName
     * @param string $referenceTableNameId Reference Name
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setReferenceTableNameId($referenceTableNameId) {
        $this->referenceTableNameId = $referenceTableNameId;
        return $this;
    }

    /**
     * To Return  ChartOfAccount
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set ChartOfAccount
     * @param int $chartOfAccountId Chart Account
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * To Return  chartOfAccountNumber
     * @return string $chartOfAccountNumber
     */
    public function getChartOfAccountNumber() {
        return $this->chartOfAccountNumber;
    }

    /**
     * To Set chartOfAccountNumber
     * @param string $chartOfAccountNumber Chart Of Account   Number
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountNumber($chartOfAccountNumber) {
        $this->chartOfAccountNumber = $chartOfAccountNumber;
        return $this;
    }

    /**
     * To Return  chartOfAccountDescription
     * @return string $chartOfAccountDescription
     */
    public function getChartOfAccountDescription() {
        return $this->chartOfAccountDescription;
    }

    /**
     * To Set chartOfAccountDescription
     * @param string $chartOfAccountDescription Chart Of Account   Description
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setChartOfAccountDescription($chartOfAccountDescription) {
        $this->chartOfAccountDescription = $chartOfAccountDescription;
        return $this;
    }

    /**
     * To Return  BusinessPartner
     * @return int $businessPartnerId
     */
    public function getBusinessPartnerId() {
        return $this->businessPartnerId;
    }

    /**
     * To Set BusinessPartner
     * @param int $businessPartnerId Business Partner
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return  businessPartnerDescription
     * @return string $businessPartnerDescription
     */
    public function getBusinessPartnerDescription() {
        return $this->businessPartnerDescription;
    }

    /**
     * To Set businessPartnerDescription
     * @param string $businessPartnerDescription Business Description
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setBusinessPartnerDescription($businessPartnerDescription) {
        $this->businessPartnerDescription = $businessPartnerDescription;
        return $this;
    }

    /**
     * To Return  module
     * @return string $module
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * To Set module
     * @param string $module Module
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setModule($module) {
        $this->module = $module;
        return $this;
    }

    /**
     * To Return  tableName
     * @return string $tableName
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * To Set tableName
     * @param string $tableName Table Name
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTableName($tableName) {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * To Return  TableName
     * @return int $tableNameId
     */
    public function getTableNameId() {
        return $this->tableNameId;
    }

    /**
     * To Set TableName
     * @param int $tableNameId Table Name
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTableNameId($tableNameId) {
        $this->tableNameId = $tableNameId;
        return $this;
    }

    /**
     * To Return  tableNameDetail
     * @return string $tableNameDetail
     */
    public function getTableNameDetail() {
        return $this->tableNameDetail;
    }

    /**
     * To Set tableNameDetail
     * @param string $tableNameDetail Table Detail
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTableNameDetail($tableNameDetail) {
        $this->tableNameDetail = $tableNameDetail;
        return $this;
    }

    /**
     * To Return  TableNameDetail
     * @return int $tableNameDetailId
     */
    public function getTableNameDetailId() {
        return $this->tableNameDetailId;
    }

    /**
     * To Set TableNameDetail
     * @param int $tableNameDetailId Table Detail
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setTableNameDetailId($tableNameDetailId) {
        $this->tableNameDetailId = $tableNameDetailId;
        return $this;
    }

    /**
     * To Return  Leaf
     * @return int $leafId
     */
    public function getLeafId() {
        return $this->leafId;
    }

    /**
     * To Set Leaf
     * @param int $leafId Leaf
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setLeafId($leafId) {
        $this->leafId = $leafId;
        return $this;
    }

    /**
     * To Return  leafName
     * @return string $leafName
     */
    public function getLeafName() {
        return $this->leafName;
    }

    /**
     * To Set leafName
     * @param string $leafName Leaf Name
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setLeafName($leafName) {
        $this->leafName = $leafName;
        return $this;
    }

    /**
     * To Return  isMerge
     * @return bool $isMerge
     */
    public function getIsMerge() {
        return $this->isMerge;
    }

    /**
     * To Set isMerge
     * @param bool $isMerge Is Merge
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setIsMerge($isMerge) {
        $this->isMerge = $isMerge;
        return $this;
    }

    /**
     * To Return  isSlice
     * @return bool $isSlice
     */
    public function getIsSlice() {
        return $this->isSlice;
    }

    /**
     * To Set isSlice
     * @param bool $isSlice Is Slice
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setIsSlice($isSlice) {
        $this->isSlice = $isSlice;
        return $this;
    }

    /**
     * To Return  isAuthorized
     * @return bool $isAuthorized
     */
    public function getIsAuthorized() {
        return $this->isAuthorized;
    }

    /**
     * To Set isAuthorized
     * @param bool $isAuthorized Is Authorized
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setIsAuthorized($isAuthorized) {
        $this->isAuthorized = $isAuthorized;
        return $this;
    }

    /**
     * To Return  executeName
     * @return string $executeName
     */
    public function getExecuteName() {
        return $this->executeName;
    }

    /**
     * To Set executeName
     * @param string $executeName Execute Name
     * @return \Core\Financial\GeneralLedger\GeneralLedger\Model\GeneralLedgerModel
     */
    public function setExecuteName($executeName) {
        $this->executeName = $executeName;
        return $this;
    }

    /**
     * @return int
     */
    public function getBankId() {
        return $this->bankId;
    }

    /**
     * @param int $bankId
     */
    public function setBankId($bankId) {
        $this->bankId = $bankId;
    }

    /**
     * @return float
     */
    public function getBudgetAmount() {
        return $this->budgetAmount;
    }

    /**
     * @param float $budgetAmount
     */
    public function setBudgetAmount($budgetAmount) {
        $this->budgetAmount = $budgetAmount;
    }

    /**
     * @return float
     */
    public function getCollectionAmount() {
        return $this->collectionAmount;
    }

    /**
     * @param float $collectionAmount
     */
    public function setCollectionAmount($collectionAmount) {
        $this->collectionAmount = $collectionAmount;
    }

    /**
     * @return string
     */
    public function getCollectionDate() {
        return $this->collectionDate;
    }

    /**
     * @param string $collectionDate
     */
    public function setCollectionDate($collectionDate) {
        $this->collectionDate = $collectionDate;
    }

    /**
     * @return string
     */
    public function getCollectionDescription() {
        return $this->collectionDescription;
    }

    /**
     * @param string $collectionDescription
     */
    public function setCollectionDescription($collectionDescription) {
        $this->collectionDescription = $collectionDescription;
    }

    /**
     * @return string
     */
    public function getCollectionId() {
        return $this->collectionId;
    }

    /**
     * @param string $collectionId
     */
    public function setCollectionId($collectionId) {
        $this->collectionId = $collectionId;
    }

    /**
     * @return int
     */
    public function getFinancePeriod() {
        return $this->financePeriod;
    }

    /**
     * @param int $financePeriod
     */
    public function setFinancePeriod($financePeriod) {
        $this->financePeriod = $financePeriod;
    }

    /**
     * @return int
     */
    public function getFinanceYear() {
        return $this->financeYear;
    }

    /**
     * @param int $financeYear
     */
    public function setFinanceYear($financeYear) {
        $this->financeYear = $financeYear;
    }

    /**
     * @return string
     */
    public function getPaymentVoucherAmount() {
        return $this->paymentVoucherAmount;
    }

    /**
     * @param string $paymentVoucherAmount
     */
    public function setPaymentVoucherAmount($paymentVoucherAmount) {
        $this->paymentVoucherAmount = $paymentVoucherAmount;
    }

    /**
     * @return string
     */
    public function getPaymentVoucherDate() {
        return $this->paymentVoucherDate;
    }

    /**
     * @param string $paymentVoucherDate
     */
    public function setPaymentVoucherDate($paymentVoucherDate) {
        $this->paymentVoucherDate = $paymentVoucherDate;
    }

    /**
     * @return string
     */
    public function getPaymentVoucherDescription() {
        return $this->paymentVoucherDescription;
    }

    /**
     * @param string $paymentVoucherDescription
     */
    public function setPaymentVoucherDescription($paymentVoucherDescription) {
        $this->paymentVoucherDescription = $paymentVoucherDescription;
    }

    /**
     * @return int
     */
    public function getPaymentVoucherId() {
        return $this->paymentVoucherId;
    }

    /**
     * @param int $paymentVoucherId
     */
    public function setPaymentVoucherId($paymentVoucherId) {
        $this->paymentVoucherId = $paymentVoucherId;
    }

    /**
     * @return int
     */
    public function getBudgetId() {
        return $this->budgetId;
    }

    /**
     * @param int $budgetId
     */
    public function setBudgetId($budgetId) {
        $this->budgetId = $budgetId;
    }

    /**
     * @return int
     */
    public function getCollectionDetailId() {
        return $this->collectionDetailId;
    }

    /**
     * @param int $collectionDetailId
     */
    public function setCollectionDetailId($collectionDetailId) {
        $this->collectionDetailId = $collectionDetailId;
    }

    /**
     * @return string
     */
    public function getPaymentVoucherDetailId() {
        return $this->paymentVoucherDetailId;
    }

    /**
     * @param string $paymentVoucherDetailId
     */
    public function setPaymentVoucherDetailId($paymentVoucherDetailId) {
        $this->paymentVoucherDetailId = $paymentVoucherDetailId;
    }

}

?>