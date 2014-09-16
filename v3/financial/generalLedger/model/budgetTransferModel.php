<?php

namespace Core\Financial\GeneralLedger\BudgetTransfer\Model;

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
 * Class BudgetTransfer
 * This is Budget Transfer model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\BudgetTransfer\Model;
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BudgetTransferModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $budgetTransferId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Chart of Account Number From
     * @var int
     */
    private $budgetTransferFrom;

    /**
     * Chart of Account Number To
     * @var int
     */
    private $budgetTransferTo;

    /**
     * Finance Year
     * @var int
     */
    private $financeYearId;

    /**
     * Finance Range
     * @var int
     */
    private $financePeriodRangeId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Date
     * @var string
     */
    private $budgetTransferDate;

    /**
     * Amount
     * @var double
     */
    private $budgetTransferAmount;

    /**
     * Comment
     * @var string
     */
    private $budgetTransferComment;

    /**
     * Chart Of Account Number
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('budgetTransfer');
        $this->setPrimaryKeyName('budgetTransferId');
        $this->setMasterForeignKeyName('budgetTransferId');
        $this->setFilterCharacter('budgetTransferDescription');
        //$this->setFilterCharacter('budgetTransferNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['budgetTransferId'])) {
            $this->setBudgetTransferId($this->strict($_POST ['budgetTransferId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['budgetTransferFrom'])) {
            $this->setBudgetTransferFrom($this->strict($_POST ['budgetTransferFrom'], 'integer'));
        }
        if (isset($_POST ['budgetTransferTo'])) {
            $this->setBudgetTransferTo($this->strict($_POST ['budgetTransferTo'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'));
        }
        if (isset($_POST ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_POST ['financeYearId'], 'integer'));
        }
        if (isset($_POST ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_POST ['financePeriodRangeId'], 'integer'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['budgetTransferDate'])) {
            $this->setBudgetTransferDate($this->strict($_POST ['budgetTransferDate'], 'date'));
        }
        if (isset($_POST ['budgetTransferAmount'])) {
            $this->setBudgetTransferAmount($this->strict($_POST ['budgetTransferAmount'], 'double'));
        }
        if (isset($_POST ['budgetTransferComment'])) {
            $this->setBudgetTransferComment($this->strict($_POST ['budgetTransferComment'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['budgetTransferId'])) {
            $this->setBudgetTransferId($this->strict($_GET ['budgetTransferId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['budgetTransferFrom'])) {
            $this->setBudgetTransferFrom($this->strict($_GET ['budgetTransferFrom'], 'integer'));
        }
        if (isset($_GET ['budgetTransferTo'])) {
            $this->setBudgetTransferTo($this->strict($_GET ['budgetTransferTo'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'));
        }
        if (isset($_GET ['financeYearId'])) {
            $this->setFinanceYearId($this->strict($_GET ['financeYearId'], 'integer'));
        }
        if (isset($_GET ['financePeriodRangeId'])) {
            $this->setFinancePeriodRangeId($this->strict($_GET ['financePeriodRangeId'], 'integer'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['budgetTransferDate'])) {
            $this->setBudgetTransferDate($this->strict($_GET ['budgetTransferDate'], 'date'));
        }
        if (isset($_GET ['budgetTransferAmount'])) {
            $this->setBudgetTransferAmount($this->strict($_GET ['budgetTransferAmount'], 'double'));
        }
        if (isset($_GET ['budgetTransferComment'])) {
            $this->setBudgetTransferComment($this->strict($_GET ['budgetTransferComment'], 'string'));
        }
        if (isset($_GET ['budgetTransferId'])) {
            $this->setTotal(count($_GET ['budgetTransferId']));
            if (is_array($_GET ['budgetTransferId'])) {
                $this->budgetTransferId = array();
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
            if (isset($_GET ['budgetTransferId'])) {
                $this->setBudgetTransferId($this->strict($_GET ['budgetTransferId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getBudgetTransferId($i, 'array') . ",";
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
        } elseif ($this->getVendor() == self::MSSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
        } else {
            if ($this->getVendor() == self::ORACLE) {
                $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getBudgetTransferId($key, $type) {
        if ($type == 'single') {
            return $this->budgetTransferId;
        } else {
            if ($type == 'array') {
                return $this->budgetTransferId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getBudgetTransferId ?")
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
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setBudgetTransferId($value, $key, $type) {
        if ($type == 'single') {
            $this->budgetTransferId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->budgetTransferId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setBudgetTransferId?")
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
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Chart of Account Number From
     * @return int $budgetTransferFrom
     */
    public function getBudgetTransferFrom() {
        return $this->budgetTransferFrom;
    }

    /**
     * To Set Chart of Account Number From
     * @param int $budgetTransferFrom Chart of Account Number From
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setBudgetTransferFrom($budgetTransferFrom) {
        $this->budgetTransferFrom = $budgetTransferFrom;
        return $this;
    }

    /**
     * To Return Chart of Account Number To
     * @return int $budgetTransferTo
     */
    public function getBudgetTransferTo() {
        return $this->budgetTransferTo;
    }

    /**
     * To Set Chart of Account Number To
     * @param int $budgetTransferTo Chart of Account Number To
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setBudgetTransferTo($budgetTransferTo) {
        $this->budgetTransferTo = $budgetTransferTo;
        return $this;
    }

    /**
     * To Return Chart of Account Number
     * @return int $budgetTransferTo
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set Chart of Account Number
     * @param int $chartOfAccountId Chart of Account Number
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
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
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * To Return Finance Period Range
     * @return int $financePeriodRangeId
     */
    public function getFinancePeriodRangeId() {
        return $this->financePeriodRangeId;
    }

    /**
     * To Set Finance Period Range
     * @param int $financePeriodRangeId Finance Range
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setFinancePeriodRangeId($financePeriodRangeId) {
        $this->financePeriodRangeId = $financePeriodRangeId;
        return $this;
    }

    /**
     * To Return Document Number
     * @return string $documentNumber
     */
    public function getDocumentNumber() {
        return $this->documentNumber;
    }

    /**
     * To Set Document Number
     * @param string $documentNumber Document Number
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Date
     * @return string $budgetTransferDate
     */
    public function getBudgetTransferDate() {
        return $this->budgetTransferDate;
    }

    /**
     * To Set Date
     * @param string $budgetTransferDate Date
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setBudgetTransferDate($budgetTransferDate) {
        $this->budgetTransferDate = $budgetTransferDate;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $budgetTransferAmount
     */
    public function getBudgetTransferAmount() {
        return $this->budgetTransferAmount;
    }

    /**
     * To Set Amount
     * @param double $budgetTransferAmount Amount
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setBudgetTransferAmount($budgetTransferAmount) {
        $this->budgetTransferAmount = $budgetTransferAmount;
        return $this;
    }

    /**
     * To Return Comment
     * @return string $budgetTransferComment
     */
    public function getBudgetTransferComment() {
        return $this->budgetTransferComment;
    }

    /**
     * To Set Comment
     * @param string $budgetTransferComment Comment
     * @return \Core\Financial\GeneralLedger\BudgetTransfer\Model\BudgetTransferModel
     */
    public function setBudgetTransferComment($budgetTransferComment) {
        $this->budgetTransferComment = $budgetTransferComment;
        return $this;
    }

}

?>