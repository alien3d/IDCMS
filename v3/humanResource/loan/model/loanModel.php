<?php

namespace Core\HumanResource\Loan\Loan\Model;

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
 * Class Loan
 * This is Loan Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Loan\Loan\Model;
 * @subpackage Loan
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LoanModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $loanId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Category
     * @var int
     */
    private $loanCategoryId;

    /**
     * Type
     * @var int
     */
    private $loanTypeId;

    /**
     * Employee
     * @var int
     */
    private $employeeId;

    /**
     * Amount
     * @var double
     */
    private $loanAmount;

    /**
     * Start Date
     * @var string
     */
    private $loanStartDate;

    /**
     * End Date
     * @var string
     */
    private $loanEndDate;

    /**
     * Monthly Payment
     * @var double
     */
    private $loanMonthlyPayment;

    /**
     * Final Payment
     * @var double
     */
    private $loanFinalPayment;

    /**
     * Description
     * @var string
     */
    private $loanDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('loan');
        $this->setPrimaryKeyName('loanId');
        $this->setMasterForeignKeyName('loanId');
        $this->setFilterCharacter('loanDescription');
        //$this->setFilterCharacter('loanNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['loanId'])) {
            $this->setLoanId($this->strict($_POST ['loanId'], 'numeric'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'numeric'));
        }
        if (isset($_POST ['loanCategoryId'])) {
            $this->setLoanCategoryId($this->strict($_POST ['loanCategoryId'], 'numeric'));
        }
        if (isset($_POST ['loanTypeId'])) {
            $this->setLoanTypeId($this->strict($_POST ['loanTypeId'], 'numeric'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'numeric'));
        }
        if (isset($_POST ['loanAmount'])) {
            $this->setLoanAmount($this->strict($_POST ['loanAmount'], 'currency'));
        }
        if (isset($_POST ['loanStartDate'])) {
            $this->setLoanStartDate($this->strict($_POST ['loanStartDate'], 'date'));
        }
        if (isset($_POST ['loanEndDate'])) {
            $this->setLoanEndDate($this->strict($_POST ['loanEndDate'], 'date'));
        }
        if (isset($_POST ['loanMonthlyPayment'])) {
            $this->setLoanMonthlyPayment($this->strict($_POST ['loanMonthlyPayment'], 'currency'));
        }
        if (isset($_POST ['loanFinalPayment'])) {
            $this->setLoanFinalPayment($this->strict($_POST ['loanFinalPayment'], 'currency'));
        }
        if (isset($_POST ['loanDescription'])) {
            $this->setLoanDescription($this->strict($_POST ['loanDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['loanId'])) {
            $this->setLoanId($this->strict($_GET ['loanId'], 'numeric'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'numeric'));
        }
        if (isset($_GET ['loanCategoryId'])) {
            $this->setLoanCategoryId($this->strict($_GET ['loanCategoryId'], 'numeric'));
        }
        if (isset($_GET ['loanTypeId'])) {
            $this->setLoanTypeId($this->strict($_GET ['loanTypeId'], 'numeric'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'numeric'));
        }
        if (isset($_GET ['loanAmount'])) {
            $this->setLoanAmount($this->strict($_GET ['loanAmount'], 'currency'));
        }
        if (isset($_GET ['loanStartDate'])) {
            $this->setLoanStartDate($this->strict($_GET ['loanStartDate'], 'date'));
        }
        if (isset($_GET ['loanEndDate'])) {
            $this->setLoanEndDate($this->strict($_GET ['loanEndDate'], 'date'));
        }
        if (isset($_GET ['loanMonthlyPayment'])) {
            $this->setLoanMonthlyPayment($this->strict($_GET ['loanMonthlyPayment'], 'currency'));
        }
        if (isset($_GET ['loanFinalPayment'])) {
            $this->setLoanFinalPayment($this->strict($_GET ['loanFinalPayment'], 'currency'));
        }
        if (isset($_GET ['loanDescription'])) {
            $this->setLoanDescription($this->strict($_GET ['loanDescription'], 'string'));
        }
        if (isset($_GET ['loanId'])) {
            $this->setTotal(count($_GET ['loanId']));
            if (is_array($_GET ['loanId'])) {
                $this->loanId = array();
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
            if (isset($_GET ['loanId'])) {
                $this->setLoanId($this->strict($_GET ['loanId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getLoanId($i, 'array') . ",";
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
    public function getLoanId($key, $type) {
        if ($type == 'single') {
            return $this->loanId;
        } else {
            if ($type == 'array') {
                return $this->loanId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getLoanId ?")
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
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanId($value, $key, $type) {
        if ($type == 'single') {
            $this->loanId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->loanId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setLoanId?")
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
     * To Return Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Category
     * @return int $loanCategoryId
     */
    public function getLoanCategoryId() {
        return $this->loanCategoryId;
    }

    /**
     * To Set Category
     * @param int $loanCategoryId Category
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanCategoryId($loanCategoryId) {
        $this->loanCategoryId = $loanCategoryId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $loanTypeId
     */
    public function getLoanTypeId() {
        return $this->loanTypeId;
    }

    /**
     * To Set Type
     * @param int $loanTypeId Type
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanTypeId($loanTypeId) {
        $this->loanTypeId = $loanTypeId;
        return $this;
    }

    /**
     * To Return Employee
     * @return int $employeeId
     */
    public function getEmployeeId() {
        return $this->employeeId;
    }

    /**
     * To Set Employee
     * @param int $employeeId Employee
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $loanAmount
     */
    public function getLoanAmount() {
        return $this->loanAmount;
    }

    /**
     * To Set Amount
     * @param double $loanAmount Amount
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanAmount($loanAmount) {
        $this->loanAmount = $loanAmount;
        return $this;
    }

    /**
     * To Return Start Date
     * @return string $loanStartDate
     */
    public function getLoanStartDate() {
        return $this->loanStartDate;
    }

    /**
     * To Set Start Date
     * @param string $loanStartDate Start Date
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanStartDate($loanStartDate) {
        $this->loanStartDate = $loanStartDate;
        return $this;
    }

    /**
     * To Return End Date
     * @return string $loanEndDate
     */
    public function getLoanEndDate() {
        return $this->loanEndDate;
    }

    /**
     * To Set End Date
     * @param string $loanEndDate End Date
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanEndDate($loanEndDate) {
        $this->loanEndDate = $loanEndDate;
        return $this;
    }

    /**
     * To Return Monthly Payment
     * @return double $loanMonthlyPayment
     */
    public function getLoanMonthlyPayment() {
        return $this->loanMonthlyPayment;
    }

    /**
     * To Set Monthly Payment
     * @param double $loanMonthlyPayment Monthly Payment
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanMonthlyPayment($loanMonthlyPayment) {
        $this->loanMonthlyPayment = $loanMonthlyPayment;
        return $this;
    }

    /**
     * To Return Final Payment
     * @return double $loanFinalPayment
     */
    public function getLoanFinalPayment() {
        return $this->loanFinalPayment;
    }

    /**
     * To Set Final Payment
     * @param double $loanFinalPayment Final Payment
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanFinalPayment($loanFinalPayment) {
        $this->loanFinalPayment = $loanFinalPayment;
        return $this;
    }

    /**
     * To Return Description
     * @return string $loanDescription
     */
    public function getLoanDescription() {
        return $this->loanDescription;
    }

    /**
     * To Set Description
     * @param string $loanDescription Description
     * @return \Core\HumanResource\Loan\Loan\Model\LoanModel
     */
    public function setLoanDescription($loanDescription) {
        $this->loanDescription = $loanDescription;
        return $this;
    }

}

?>