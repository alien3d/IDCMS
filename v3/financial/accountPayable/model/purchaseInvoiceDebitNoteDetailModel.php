<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model;

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
 * Class PurchaseInvoiceDebitNoteDetail
 * This is purchaseInvoiceDebitNoteDetail model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model;
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceDebitNoteDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $purchaseInvoiceDebitNoteDetailId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Purchase Invoice
     * @var int
     */
    private $purchaseInvoiceId;

    /**
     * Purchase Invoice Debit   Note
     * @var int
     */
    private $purchaseInvoiceDebitNoteId;

    /**
     * Chart Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Document Number
     * @var string
     */
    private $documentNumber;

    /**
     * Journal Number
     * @var string
     */
    private $journalNumber;

    /**
     * Amount
     * @var double
     */
    private $purchaseInvoiceDebitNoteDetailAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('purchaseInvoiceDebitNoteDetail');
        $this->setPrimaryKeyName('purchaseInvoiceDebitNoteDetailId');
        $this->setMasterForeignKeyName('purchaseInvoiceDebitNoteDetailId');
        $this->setFilterCharacter('purchaseInvoiceDebitNoteDetailDescription');
        //$this->setFilterCharacter('purchaseInvoiceDebitNoteDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['purchaseInvoiceDebitNoteDetailId'])) {
            $this->setPurchaseInvoiceDebitNoteDetailId($this->strict($_POST ['purchaseInvoiceDebitNoteDetailId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_POST ['purchaseInvoiceId'], 'int'));
        }
        if (isset($_POST ['purchaseInvoiceDebitNoteId'])) {
            $this->setPurchaseInvoiceDebitNoteId($this->strict($_POST ['purchaseInvoiceDebitNoteId'], 'int'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int'));
        }
        if (isset($_POST ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_POST ['documentNumber'], 'string'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['purchaseInvoiceDebitNoteDetailAmount'])) {
            $this->setPurchaseInvoiceDebitNoteDetailAmount($this->strict($_POST ['purchaseInvoiceDebitNoteDetailAmount'], 'double'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['purchaseInvoiceDebitNoteDetailId'])) {
            $this->setPurchaseInvoiceDebitNoteDetailId($this->strict($_GET ['purchaseInvoiceDebitNoteDetailId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_GET ['purchaseInvoiceId'], 'int'));
        }
        if (isset($_GET ['purchaseInvoiceDebitNoteId'])) {
            $this->setPurchaseInvoiceDebitNoteId($this->strict($_GET ['purchaseInvoiceDebitNoteId'], 'int'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int'));
        }
        if (isset($_GET ['documentNumber'])) {
            $this->setDocumentNumber($this->strict($_GET ['documentNumber'], 'string'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['purchaseInvoiceDebitNoteDetailAmount'])) {
            $this->setPurchaseInvoiceDebitNoteDetailAmount($this->strict($_GET ['purchaseInvoiceDebitNoteDetailAmount'], 'double'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['purchaseInvoiceDebitNoteDetailId'])) {
            $this->setTotal(count($_GET ['purchaseInvoiceDebitNoteDetailId']));
            if (is_array($_GET ['purchaseInvoiceDebitNoteDetailId'])) {
                $this->purchaseInvoiceDebitNoteDetailId = array();
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
            if (isset($_GET ['purchaseInvoiceDebitNoteDetailId'])) {
                $this->setPurchaseInvoiceDebitNoteDetailId($this->strict($_GET ['purchaseInvoiceDebitNoteDetailId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getPurchaseInvoiceDebitNoteDetailId($i, 'array') . ",";
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setPurchaseInvoiceDebitNoteDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->purchaseInvoiceDebitNoteDetailId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->purchaseInvoiceDebitNoteDetailId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setpurchaseInvoiceDebitNoteDetailId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getPurchaseInvoiceDebitNoteDetailId($key, $type) {
        if ($type == 'single') {
            return $this->purchaseInvoiceDebitNoteDetailId;
        } else if ($type == 'array') {
            return $this->purchaseInvoiceDebitNoteDetailId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getpurchaseInvoiceDebitNoteDetailId ?"));
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Purchase Invoice
     * @return int $purchaseInvoiceId
     */
    public function getPurchaseInvoiceId() {
        return $this->purchaseInvoiceId;
    }

    /**
     * To Set Purchase Invoice
     * @param int $purchaseInvoiceId Purchase Invoice
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setPurchaseInvoiceId($purchaseInvoiceId) {
        $this->purchaseInvoiceId = $purchaseInvoiceId;
        return $this;
    }

    /**
     * To Return Purchase Invoice Debit   Note
     * @return int $purchaseInvoiceDebitNoteId
     */
    public function getPurchaseInvoiceDebitNoteId() {
        return $this->purchaseInvoiceDebitNoteId;
    }

    /**
     * To Set Purchase Invoice Debit   Note
     * @param int $purchaseInvoiceDebitNoteId Purchase Invoice Debit   Note
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setPurchaseInvoiceDebitNoteId($purchaseInvoiceDebitNoteId) {
        $this->purchaseInvoiceDebitNoteId = $purchaseInvoiceDebitNoteId;
        return $this;
    }

    /**
     * To Return Chart Account
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set Chart Account
     * @param int $chartOfAccountId Chart Account
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * To Return Business Partner
     * @return int $businessPartnerId
     */
    public function getBusinessPartnerId() {
        return $this->businessPartnerId;
    }

    /**
     * To Set Business Partner
     * @param int $businessPartnerId Business Partner
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setDocumentNumber($documentNumber) {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * To Return Journal Number
     * @return string $journalNumber
     */
    public function getJournalNumber() {
        return $this->journalNumber;
    }

    /**
     * To Set Journal Number
     * @param string $journalNumber Journal Number
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $purchaseInvoiceDebitNoteDetailAmount
     */
    public function getPurchaseInvoiceDebitNoteDetailAmount() {
        return $this->purchaseInvoiceDebitNoteDetailAmount;
    }

    /**
     * To Set Amount
     * @param double $purchaseInvoiceDebitNoteDetailAmount Amount
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDebitNoteDetail\Model\PurchaseInvoiceDebitNoteDetailModel
     */
    public function setPurchaseInvoiceDebitNoteDetailAmount($purchaseInvoiceDebitNoteDetailAmount) {
        $this->purchaseInvoiceDebitNoteDetailAmount = $purchaseInvoiceDebitNoteDetailAmount;
        return $this;
    }

}

?>