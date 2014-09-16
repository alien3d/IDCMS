<?php

namespace Core\Financial\GeneralLedger\FinanceSetting\Model;

use Core\Validation\ValidationClass;

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
require_once ($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class FinanceSetting
 * This is financeSetting model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\FinanceSetting\Model;
 * @subpackage GeneralLedger 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FinanceSettingModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $financeSettingId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Country
     * @var int 
     */
    private $countryId;

    /**
     * Finance Year
     * @var int 
     */
    private $financeYearId;

    /**
     * Country Locale
     * @var string 
     */
    private $countryCurrencyLocale;

    /**
     * Exchange Day
     * @var int 
     */
    private $financeSettingExchangeGraceDay;

    /**
     * Finance Petty Cash Control   Account
     * @var int 
     */
    private $financePettyCashControlAccount;

    /**
     * Finance Bank Control   Account
     * @var int 
     */
    private $financeBankControlAccount;

    /**
     * Finance Income Control   Account
     * @var int 
     */
    private $financeIncomeControlAccount;

    /**
     * Finance Expenses Control   Account
     * @var int 
     */
    private $financeExpensesControlAccount;

    /**
     * Finance Debtor Control   Account
     * @var int 
     */
    private $financeDebtorControlAccount;

    /**
     * Finance Creditor Control   Account
     * @var int 
     */
    private $financeCreditorControlAccount;

    /**
     * Finance Approval
     * @var int 
     */
    private $financeJobApproval;

    /**
     * Finance Approval
     * @var int 
     */
    private $financeBudgetApproval;

    /**
     * Is Exchange
     * @var bool 
     */
    private $isExchange;

    /**
     * Is Period
     * @var bool 
     */
    private $isOddPeriod;

    /**
     * Is Closing
     * @var bool 
     */
    private $isClosing;

    /**
     * Is Posting
     * @var bool 
     */
    private $isPosting;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('financeSetting');
        $this->setPrimaryKeyName('financeSettingId');
        $this->setMasterForeignKeyName('financeSettingId');
        $this->setFilterCharacter('financeSettingDescription');
        //$this->setFilterCharacter('financeSettingNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['financeSettingId'])) {
            $this->setFinanceSettingId($this->strict($_POST ['financeSettingId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'));
        }
        if (isset($_POST ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_POST ['financeYearId'], 'int'));
        }
        if (isset($_POST ['countryCurrencyLocale'])) {
            $this->setCountryCurrencyLocale($this->strict($_POST ['countryCurrencyLocale'], 'string'));
        }
        if (isset($_POST ['financeSettingExchangeGraceDay'])) {
            $this->setFinanceSettingExchangeGraceDay($this->strict($_POST ['financeSettingExchangeGraceDay'], 'int'));
        }
        if (isset($_POST ['financePettyCashControlAccount'])) {
            $this->setFinancePettyCashControlAccount($this->strict($_POST ['financePettyCashControlAccount'], 'int'));
        }
        if (isset($_POST ['financeBankControlAccount'])) {
            $this->setFinanceBankControlAccount($this->strict($_POST ['financeBankControlAccount'], 'int'));
        }
        if (isset($_POST ['financeIncomeControlAccount'])) {
            $this->setFinanceIncomeControlAccount($this->strict($_POST ['financeIncomeControlAccount'], 'int'));
        }
        if (isset($_POST ['financeExpensesControlAccount'])) {
            $this->setFinanceExpensesControlAccount($this->strict($_POST ['financeExpensesControlAccount'], 'int'));
        }
        if (isset($_POST ['financeDebtorControlAccount'])) {
            $this->setFinanceDebtorControlAccount($this->strict($_POST ['financeDebtorControlAccount'], 'int'));
        }
        if (isset($_POST ['financeCreditorControlAccount'])) {
            $this->setFinanceCreditorControlAccount($this->strict($_POST ['financeCreditorControlAccount'], 'int'));
        }
        if (isset($_POST ['financeJobApproval'])) {
            $this->setFinanceJobApproval($this->strict($_POST ['financeJobApproval'], 'int'));
        }
        if (isset($_POST ['financeBudgetApproval'])) {
            $this->setFinanceBudgetApproval($this->strict($_POST ['financeBudgetApproval'], 'int'));
        }
        if (isset($_POST ['isExchange'])) {
            $this->setIsExchange($this->strict($_POST ['isExchange'], 'bool'));
        }
        if (isset($_POST ['isOddPeriod'])) {
            $this->setIsOddPeriod($this->strict($_POST ['isOddPeriod'], 'bool'));
        }
        if (isset($_POST ['isClosing'])) {
            $this->setIsClosing($this->strict($_POST ['isClosing'], 'bool'));
        }
        if (isset($_POST ['isPosting'])) {
            $this->setIsPosting($this->strict($_POST ['isPosting'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['financeSettingId'])) {
            $this->setFinanceSettingId($this->strict($_GET ['financeSettingId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'));
        }
        if (isset($_GET ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_GET ['financeYearId'], 'int'));
        }
        if (isset($_GET ['countryCurrencyLocale'])) {
            $this->setCountryCurrencyLocale($this->strict($_GET ['countryCurrencyLocale'], 'string'));
        }
        if (isset($_GET ['financeSettingExchangeGraceDay'])) {
            $this->setFinanceSettingExchangeGraceDay($this->strict($_GET ['financeSettingExchangeGraceDay'], 'int'));
        }
        if (isset($_GET ['financePettyCashControlAccount'])) {
            $this->setFinancePettyCashControlAccount($this->strict($_GET ['financePettyCashControlAccount'], 'int'));
        }
        if (isset($_GET ['financeBankControlAccount'])) {
            $this->setFinanceBankControlAccount($this->strict($_GET ['financeBankControlAccount'], 'int'));
        }
        if (isset($_GET ['financeIncomeControlAccount'])) {
            $this->setFinanceIncomeControlAccount($this->strict($_GET ['financeIncomeControlAccount'], 'int'));
        }
        if (isset($_GET ['financeExpensesControlAccount'])) {
            $this->setFinanceExpensesControlAccount($this->strict($_GET ['financeExpensesControlAccount'], 'int'));
        }
        if (isset($_GET ['financeDebtorControlAccount'])) {
            $this->setFinanceDebtorControlAccount($this->strict($_GET ['financeDebtorControlAccount'], 'int'));
        }
        if (isset($_GET ['financeCreditorControlAccount'])) {
            $this->setFinanceCreditorControlAccount($this->strict($_GET ['financeCreditorControlAccount'], 'int'));
        }
        if (isset($_GET ['financeJobApproval'])) {
            $this->setFinanceJobApproval($this->strict($_GET ['financeJobApproval'], 'int'));
        }
        if (isset($_GET ['financeBudgetApproval'])) {
            $this->setFinanceBudgetApproval($this->strict($_GET ['financeBudgetApproval'], 'int'));
        }
        if (isset($_GET ['isExchange'])) {
            $this->setIsExchange($this->strict($_GET ['isExchange'], 'bool'));
        }
        if (isset($_GET ['isOddPeriod'])) {
            $this->setIsOddPeriod($this->strict($_GET ['isOddPeriod'], 'bool'));
        }
        if (isset($_GET ['isClosing'])) {
            $this->setIsClosing($this->strict($_GET ['isClosing'], 'bool'));
        }
        if (isset($_GET ['isPosting'])) {
            $this->setIsPosting($this->strict($_GET ['isPosting'], 'bool'));
        }
        if (isset($_GET ['financeSettingId'])) {
            $this->setTotal(count($_GET ['financeSettingId']));
            if (is_array($_GET ['financeSettingId'])) {
                $this->financeSettingId = array();
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
            if (isset($_GET ['financeSettingId'])) {
                $this->setFinanceSettingId($this->strict($_GET ['financeSettingId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else if ($_GET ['isDefault'] [$i] == 'false') {
                    $this->setIsDefault(0, $i, 'array');
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else if ($_GET ['isNew'] [$i] == 'false') {
                    $this->setIsNew(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else if ($_GET ['isDraft'] [$i] == 'false') {
                    $this->setIsDraft(0, $i, 'array');
                }
            }
            if (isset($_GET ['isUpdate'])) {
                if ($_GET ['isUpdate'] [$i] == 'true') {
                    $this->setIsUpdate(1, $i, 'array');
                } if ($_GET ['isUpdate'] [$i] == 'false') {
                    $this->setIsUpdate(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDelete'])) {
                if ($_GET ['isDelete'] [$i] == 'true') {
                    $this->setIsDelete(1, $i, 'array');
                } else if ($_GET ['isDelete'] [$i] == 'false') {
                    $this->setIsDelete(0, $i, 'array');
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else if ($_GET ['isActive'] [$i] == 'false') {
                    $this->setIsActive(0, $i, 'array');
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else if ($_GET ['isApproved'] [$i] == 'false') {
                    $this->setIsApproved(0, $i, 'array');
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else if ($_GET ['isReview'] [$i] == 'false') {
                    $this->setIsReview(0, $i, 'array');
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else if ($_GET ['isPost'] [$i] == 'false') {
                    $this->setIsPost(0, $i, 'array');
                }
            }
            $primaryKeyAll .= $this->getFinanceSettingId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, - 1)));
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
        } else if ($this->getVendor() == self::MSSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
        } else if ($this->getVendor() == self::ORACLE) {
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')");
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
     * Set Primary Key Value 
     * @param int|array $value 
     * @param array[int]int $key List Of Primary Key. 
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array' 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceSettingId($value, $key, $type) {
        if ($type == 'single') {
            $this->financeSettingId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->financeSettingId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setfinanceSettingId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getFinanceSettingId($key, $type) {
        if ($type == 'single') {
            return $this->financeSettingId;
        } else if ($type == 'array') {
            return $this->financeSettingId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getfinanceSettingId ?"));
            exit();
        }
    }

    /**
     * To Return Company 
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company 
     * @param int $companyId Company 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Country 
     * @return int $countryId
     */
    public function getCountryId() {
        return $this->countryId;
    }

    /**
     * To Set Country 
     * @param int $countryId Country 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * To Return Finance Year 
     * @return int $financeYearId
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * To Set Finance Year 
     * @param int $financeYearId Finance Year 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * To Return Country Locale 
     * @return string $countryCurrencyLocale
     */
    public function getCountryCurrencyLocale() {
        return $this->countryCurrencyLocale;
    }

    /**
     * To Set Country Locale 
     * @param string $countryCurrencyLocale Country Locale 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setCountryCurrencyLocale($countryCurrencyLocale) {
        $this->countryCurrencyLocale = $countryCurrencyLocale;
        return $this;
    }

    /**
     * To Return Exchange Day 
     * @return int $financeSettingExchangeGraceDay
     */
    public function getFinanceSettingExchangeGraceDay() {
        return $this->financeSettingExchangeGraceDay;
    }

    /**
     * To Set Exchange Day 
     * @param int $financeSettingExchangeGraceDay Exchange Day 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceSettingExchangeGraceDay($financeSettingExchangeGraceDay) {
        $this->financeSettingExchangeGraceDay = $financeSettingExchangeGraceDay;
        return $this;
    }

    /**
     * To Return Finance Petty Cash Control   Account 
     * @return int $financePettyCashControlAccount
     */
    public function getFinancePettyCashControlAccount() {
        return $this->financePettyCashControlAccount;
    }

    /**
     * To Set Finance Petty Cash Control   Account 
     * @param int $financePettyCashControlAccount Finance Petty Cash Control   Account 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinancePettyCashControlAccount($financePettyCashControlAccount) {
        $this->financePettyCashControlAccount = $financePettyCashControlAccount;
        return $this;
    }

    /**
     * To Return Finance Bank Control   Account 
     * @return int $financeBankControlAccount
     */
    public function getFinanceBankControlAccount() {
        return $this->financeBankControlAccount;
    }

    /**
     * To Set Finance Bank Control   Account 
     * @param int $financeBankControlAccount Finance Bank Control   Account 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceBankControlAccount($financeBankControlAccount) {
        $this->financeBankControlAccount = $financeBankControlAccount;
        return $this;
    }

    /**
     * To Return Finance Income Control   Account 
     * @return int $financeIncomeControlAccount
     */
    public function getFinanceIncomeControlAccount() {
        return $this->financeIncomeControlAccount;
    }

    /**
     * To Set Finance Income Control   Account 
     * @param int $financeIncomeControlAccount Finance Income Control   Account 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceIncomeControlAccount($financeIncomeControlAccount) {
        $this->financeIncomeControlAccount = $financeIncomeControlAccount;
        return $this;
    }

    /**
     * To Return Finance Expenses Control   Account 
     * @return int $financeExpensesControlAccount
     */
    public function getFinanceExpensesControlAccount() {
        return $this->financeExpensesControlAccount;
    }

    /**
     * To Set Finance Expenses Control   Account 
     * @param int $financeExpensesControlAccount Finance Expenses Control   Account 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceExpensesControlAccount($financeExpensesControlAccount) {
        $this->financeExpensesControlAccount = $financeExpensesControlAccount;
        return $this;
    }

    /**
     * To Return Finance Debtor Control   Account 
     * @return int $financeDebtorControlAccount
     */
    public function getFinanceDebtorControlAccount() {
        return $this->financeDebtorControlAccount;
    }

    /**
     * To Set Finance Debtor Control   Account 
     * @param int $financeDebtorControlAccount Finance Debtor Control   Account 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceDebtorControlAccount($financeDebtorControlAccount) {
        $this->financeDebtorControlAccount = $financeDebtorControlAccount;
        return $this;
    }

    /**
     * To Return Finance Creditor Control   Account 
     * @return int $financeCreditorControlAccount
     */
    public function getFinanceCreditorControlAccount() {
        return $this->financeCreditorControlAccount;
    }

    /**
     * To Set Finance Creditor Control   Account 
     * @param int $financeCreditorControlAccount Finance Creditor Control   Account 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceCreditorControlAccount($financeCreditorControlAccount) {
        $this->financeCreditorControlAccount = $financeCreditorControlAccount;
        return $this;
    }

    /**
     * To Return Finance Approval 
     * @return int $financeJobApproval
     */
    public function getFinanceJobApproval() {
        return $this->financeJobApproval;
    }

    /**
     * To Set Finance Approval 
     * @param int $financeJobApproval Finance Approval 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceJobApproval($financeJobApproval) {
        $this->financeJobApproval = $financeJobApproval;
        return $this;
    }

    /**
     * To Return Finance Approval 
     * @return int $financeBudgetApproval
     */
    public function getFinanceBudgetApproval() {
        return $this->financeBudgetApproval;
    }

    /**
     * To Set Finance Approval 
     * @param int $financeBudgetApproval Finance Approval 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setFinanceBudgetApproval($financeBudgetApproval) {
        $this->financeBudgetApproval = $financeBudgetApproval;
        return $this;
    }

    /**
     * To Return Is Exchange 
     * @return bool $isExchange
     */
    public function getIsExchange() {
        return $this->isExchange;
    }

    /**
     * To Set Is Exchange 
     * @param bool $isExchange Is Exchange 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setIsExchange($isExchange) {
        $this->isExchange = $isExchange;
        return $this;
    }

    /**
     * To Return Is Period 
     * @return bool $isOddPeriod
     */
    public function getIsOddPeriod() {
        return $this->isOddPeriod;
    }

    /**
     * To Set Is Period 
     * @param bool $isOddPeriod Is Period 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setIsOddPeriod($isOddPeriod) {
        $this->isOddPeriod = $isOddPeriod;
        return $this;
    }

    /**
     * To Return Is Closing 
     * @return bool $isClosing
     */
    public function getIsClosing() {
        return $this->isClosing;
    }

    /**
     * To Set Is Closing 
     * @param bool $isClosing Is Closing 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setIsClosing($isClosing) {
        $this->isClosing = $isClosing;
        return $this;
    }

    /**
     * To Return Is Posting 
     * @return bool $isPosting
     */
    public function getIsPosting() {
        return $this->isPosting;
    }

    /**
     * To Set Is Posting 
     * @param bool $isPosting Is Posting 
     * @return \Core\Financial\GeneralLedger\FinanceSetting\Model\FinanceSettingModel
     */
    public function setIsPosting($isPosting) {
        $this->isPosting = $isPosting;
        return $this;
    }

}

?>