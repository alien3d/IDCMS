<?php

namespace Core\Financial\Cashbook\BankTransfer\Model;

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
 * Class BankTransfer
 * This is Bank Transfer Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\BankTransfer\Model;
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class BankTransferModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $bankTransferId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Reference Number
     * @var string
     */
    private $referenceNumber;

    /**
     * Date
     * @var string
     */
    private $bankTransferDate;

    /**
     * Slip Number
     * @var string
     */
    private $bankTransferSlipNumber;

    /**
     * Additional Fee
     * @var double
     */
    private $bankTransferAdditionalFee;

    /**
     * Amount
     * @var double
     */
    private $bankTransferAmount;

    /**
     * Description
     * @var string
     */
    private $bankTransferDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('bankTransfer');
        $this->setPrimaryKeyName('bankTransferId');
        $this->setMasterForeignKeyName('bankTransferId');
        $this->setFilterCharacter('bankTransferDescription');
        //$this->setFilterCharacter('bankTransferNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['bankTransferId'])) {
            $this->setBankTransferId($this->strict($_POST ['bankTransferId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_POST ['referenceNumber'], 'string'));
        }
        if (isset($_POST ['bankTransferDate'])) {
            $this->setBankTransferDate($this->strict($_POST ['bankTransferDate'], 'date'));
        }
        if (isset($_POST ['bankTransferSlipNumber'])) {
            $this->setBankTransferSlipNumber($this->strict($_POST ['bankTransferSlipNumber'], 'string'));
        }
        if (isset($_POST ['bankTransferAdditionalFee'])) {
            $this->setBankTransferAdditionalFee($this->strict($_POST ['bankTransferAdditionalFee'], 'double'));
        }
        if (isset($_POST ['bankTransferAmount'])) {
            $this->setBankTransferAmount($this->strict($_POST ['bankTransferAmount'], 'double'));
        }
        if (isset($_POST ['bankTransferDescription'])) {
            $this->setBankTransferDescription($this->strict($_POST ['bankTransferDescription'], 'string'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['bankTransferId'])) {
            $this->setBankTransferId($this->strict($_GET ['bankTransferId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['referenceNumber'])) {
            $this->setReferenceNumber($this->strict($_GET ['referenceNumber'], 'string'));
        }
        if (isset($_GET ['bankTransferDate'])) {
            $this->setBankTransferDate($this->strict($_GET ['bankTransferDate'], 'date'));
        }
        if (isset($_GET ['bankTransferSlipNumber'])) {
            $this->setBankTransferSlipNumber($this->strict($_GET ['bankTransferSlipNumber'], 'string'));
        }
        if (isset($_GET ['bankTransferAdditionalFee'])) {
            $this->setBankTransferAdditionalFee($this->strict($_GET ['bankTransferAdditionalFee'], 'double'));
        }
        if (isset($_GET ['bankTransferAmount'])) {
            $this->setBankTransferAmount($this->strict($_GET ['bankTransferAmount'], 'double'));
        }
        if (isset($_GET ['bankTransferDescription'])) {
            $this->setBankTransferDescription($this->strict($_GET ['bankTransferDescription'], 'string'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['bankTransferId'])) {
            $this->setTotal(count($_GET ['bankTransferId']));
            if (is_array($_GET ['bankTransferId'])) {
                $this->bankTransferId = array();
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
            if (isset($_GET ['bankTransferId'])) {
                $this->setBankTransferId($this->strict($_GET ['bankTransferId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getBankTransferId($i, 'array') . ",";
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
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setBankTransferId($value, $key, $type) {
        if ($type == 'single') {
            $this->bankTransferId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->bankTransferId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setBankTransferId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getBankTransferId($key, $type) {
        if ($type == 'single') {
            return $this->bankTransferId;
        } else {
            if ($type == 'array') {
                return $this->bankTransferId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getBankTransferId ?")
                );
                exit();
            }
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
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
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
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Reference Number
     * @return string $referenceNumber
     */
    public function getReferenceNumber() {
        return $this->referenceNumber;
    }

    /**
     * To Set Reference Number
     * @param string $referenceNumber Reference Number
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setReferenceNumber($referenceNumber) {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    /**
     * To Return Date
     * @return string $bankTransferDate
     */
    public function getBankTransferDate() {
        return $this->bankTransferDate;
    }

    /**
     * To Set Date
     * @param string $bankTransferDate Date
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setBankTransferDate($bankTransferDate) {
        $this->bankTransferDate = $bankTransferDate;
        return $this;
    }

    /**
     * To Return Slip Number
     * @return string $bankTransferSlipNumber
     */
    public function getBankTransferSlipNumber() {
        return $this->bankTransferSlipNumber;
    }

    /**
     * To Set Slip Number
     * @param string $bankTransferSlipNumber Slip Number
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setBankTransferSlipNumber($bankTransferSlipNumber) {
        $this->bankTransferSlipNumber = $bankTransferSlipNumber;
        return $this;
    }

    /**
     * To Return Additional Fee
     * @return double $bankTransferAdditionalFee
     */
    public function getBankTransferAdditionalFee() {
        return $this->bankTransferAdditionalFee;
    }

    /**
     * To Set Additional Fee
     * @param double $bankTransferAdditionalFee Additional Fee
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setBankTransferAdditionalFee($bankTransferAdditionalFee) {
        $this->bankTransferAdditionalFee = $bankTransferAdditionalFee;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $bankTransferAmount
     */
    public function getBankTransferAmount() {
        return $this->bankTransferAmount;
    }

    /**
     * To Set Amount
     * @param double $bankTransferAmount Amount
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setBankTransferAmount($bankTransferAmount) {
        $this->bankTransferAmount = $bankTransferAmount;
        return $this;
    }

    /**
     * To Return Description
     * @return string $bankTransferDescription
     */
    public function getBankTransferDescription() {
        return $this->bankTransferDescription;
    }

    /**
     * To Set Description
     * @param string $bankTransferDescription Description
     * @return \Core\Financial\Cashbook\BankTransfer\Model\BankTransferModel
     */
    public function setBankTransferDescription($bankTransferDescription) {
        $this->bankTransferDescription = $bankTransferDescription;
        return $this;
    }

}

?>