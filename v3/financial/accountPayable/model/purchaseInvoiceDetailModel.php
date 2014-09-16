<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model;

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
 * Class PurchaseInvoiceDetail
 * This is Purchase Invoice Detail model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model;
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $purchaseInvoiceDetailId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Purchase Project
     * @var int
     */
    private $purchaseInvoiceProjectId;

    /**
     * Purchase Invoice
     * @var int
     */
    private $purchaseInvoiceId;

    /**
     * Country
     * @var int
     */
    private $countryId;

    /**
     * Business Partner
     * @var int
     */
    private $businessPartnerId;

    /**
     * Chart Of Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Journal Number
     * @var string
     */
    private $journalNumber;

    /**
     * Principal Amount
     * @var double
     */
    private $purchaseInvoiceDetailPrincipalAmount;

    /**
     * Interest Amount
     * @var double
     */
    private $purchaseInvoiceDetailInterestAmount;

    /**
     * Amount
     * @var double
     */
    private $purchaseInvoiceDetailAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('purchaseInvoiceDetail');
        $this->setPrimaryKeyName('purchaseInvoiceDetailId');
        $this->setMasterForeignKeyName('purchaseInvoiceDetailId');
        $this->setFilterCharacter('purchaseInvoiceDetailDescription');
        //$this->setFilterCharacter('purchaseInvoiceDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['purchaseInvoiceDetailId'])) {
            $this->setPurchaseInvoiceDetailId($this->strict($_POST ['purchaseInvoiceDetailId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['purchaseInvoiceProjectId'])) {
            $this->setPurchaseInvoiceProjectId($this->strict($_POST ['purchaseInvoiceProjectId'], 'int'));
        }
        if (isset($_POST ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_POST ['purchaseInvoiceId'], 'int'));
        }
        if (isset($_POST ['countryId'])) {
            $this->setCountryId($this->strict($_POST ['countryId'], 'int'));
        }
        if (isset($_POST ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_POST ['businessPartnerId'], 'int'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'int'));
        }
        if (isset($_POST ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_POST ['journalNumber'], 'string'));
        }
        if (isset($_POST ['purchaseInvoiceDetailPrincipalAmount'])) {
            $this->setPurchaseInvoiceDetailPrincipalAmount(
                    $this->strict($_POST ['purchaseInvoiceDetailPrincipalAmount'], 'double')
            );
        }
        if (isset($_POST ['purchaseInvoiceDetailInterestAmount'])) {
            $this->setPurchaseInvoiceDetailInterestAmount(
                    $this->strict($_POST ['purchaseInvoiceDetailInterestAmount'], 'double')
            );
        }
        if (isset($_POST ['purchaseInvoiceDetailAmount'])) {
            $this->setPurchaseInvoiceDetailAmount($this->strict($_POST ['purchaseInvoiceDetailAmount'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['purchaseInvoiceDetailId'])) {
            $this->setPurchaseInvoiceDetailId($this->strict($_GET ['purchaseInvoiceDetailId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['purchaseInvoiceProjectId'])) {
            $this->setPurchaseInvoiceProjectId($this->strict($_GET ['purchaseInvoiceProjectId'], 'int'));
        }
        if (isset($_GET ['purchaseInvoiceId'])) {
            $this->setPurchaseInvoiceId($this->strict($_GET ['purchaseInvoiceId'], 'int'));
        }
        if (isset($_GET ['countryId'])) {
            $this->setCountryId($this->strict($_GET ['countryId'], 'int'));
        }
        if (isset($_GET ['businessPartnerId'])) {
            $this->setBusinessPartnerId($this->strict($_GET ['businessPartnerId'], 'int'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'int'));
        }
        if (isset($_GET ['journalNumber'])) {
            $this->setJournalNumber($this->strict($_GET ['journalNumber'], 'string'));
        }
        if (isset($_GET ['purchaseInvoiceDetailPrincipalAmount'])) {
            $this->setPurchaseInvoiceDetailPrincipalAmount(
                    $this->strict($_GET ['purchaseInvoiceDetailPrincipalAmount'], 'double')
            );
        }
        if (isset($_GET ['purchaseInvoiceDetailInterestAmount'])) {
            $this->setPurchaseInvoiceDetailInterestAmount(
                    $this->strict($_GET ['purchaseInvoiceDetailInterestAmount'], 'double')
            );
        }
        if (isset($_GET ['purchaseInvoiceDetailAmount'])) {
            $this->setPurchaseInvoiceDetailAmount($this->strict($_GET ['purchaseInvoiceDetailAmount'], 'double'));
        }
        if (isset($_GET ['purchaseInvoiceDetailId'])) {
            $this->setTotal(count($_GET ['purchaseInvoiceDetailId']));
            if (is_array($_GET ['purchaseInvoiceDetailId'])) {
                $this->purchaseInvoiceDetailId = array();
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
            if (isset($_GET ['purchaseInvoiceDetailId'])) {
                $this->setPurchaseInvoiceDetailId(
                        $this->strict($_GET ['purchaseInvoiceDetailId'] [$i], 'numeric'), $i, 'array'
                );
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
            $primaryKeyAll .= $this->getPurchaseInvoiceDetailId($i, 'array') . ",";
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
    public function getPurchaseInvoiceDetailId($key, $type) {
        if ($type == 'single') {
            return $this->purchaseInvoiceDetailId;
        } else {
            if ($type == 'array') {
                return $this->purchaseInvoiceDetailId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getPurchaseInvoiceDetailId ?"
                        )
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setPurchaseInvoiceDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->purchaseInvoiceDetailId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->purchaseInvoiceDetailId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setPurchaseInvoiceDetailId?"
                        )
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Purchase Invoice Project
     * @return int $purchaseInvoiceProjectId
     */
    public function getPurchaseInvoiceProjectId() {
        return $this->purchaseInvoiceProjectId;
    }

    /**
     * To Set Purchase Invoice Project
     * @param int $purchaseInvoiceProjectId Purchase Project
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setPurchaseInvoiceProjectId($purchaseInvoiceProjectId) {
        $this->purchaseInvoiceProjectId = $purchaseInvoiceProjectId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setPurchaseInvoiceId($purchaseInvoiceId) {
        $this->purchaseInvoiceId = $purchaseInvoiceId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setBusinessPartnerId($businessPartnerId) {
        $this->businessPartnerId = $businessPartnerId;
        return $this;
    }

    /**
     * To Return Chart Of Account
     * @return int $chartOfAccountId
     */
    public function getChartOfAccountId() {
        return $this->chartOfAccountId;
    }

    /**
     * To Set Chart Of Account
     * @param int $chartOfAccountId Chart Account
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
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
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setJournalNumber($journalNumber) {
        $this->journalNumber = $journalNumber;
        return $this;
    }

    /**
     * To Return Principal Amount
     * @return double $purchaseInvoiceDetailPrincipalAmount
     */
    public function getPurchaseInvoiceDetailPrincipalAmount() {
        return $this->purchaseInvoiceDetailPrincipalAmount;
    }

    /**
     * To Set Principal Amount
     * @param double $purchaseInvoiceDetailPrincipalAmount Principal Amount
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setPurchaseInvoiceDetailPrincipalAmount($purchaseInvoiceDetailPrincipalAmount) {
        $this->purchaseInvoiceDetailPrincipalAmount = $purchaseInvoiceDetailPrincipalAmount;
        return $this;
    }

    /**
     * To Return Interest Amount
     * @return double $purchaseInvoiceDetailInterestAmount
     */
    public function getPurchaseInvoiceDetailInterestAmount() {
        return $this->purchaseInvoiceDetailInterestAmount;
    }

    /**
     * To Set Interest Amount
     * @param double $purchaseInvoiceDetailInterestAmount Interest Amount
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setPurchaseInvoiceDetailInterestAmount($purchaseInvoiceDetailInterestAmount) {
        $this->purchaseInvoiceDetailInterestAmount = $purchaseInvoiceDetailInterestAmount;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $purchaseInvoiceDetailAmount
     */
    public function getPurchaseInvoiceDetailAmount() {
        return $this->purchaseInvoiceDetailAmount;
    }

    /**
     * To Set Amount
     * @param double $purchaseInvoiceDetailAmount Amount
     * @return \Core\Financial\AccountPayable\PurchaseInvoiceDetail\Model\PurchaseInvoiceDetailModel
     */
    public function setPurchaseInvoiceDetailAmount($purchaseInvoiceDetailAmount) {
        $this->purchaseInvoiceDetailAmount = $purchaseInvoiceDetailAmount;
        return $this;
    }

}

?>