<?php

namespace Core\Financial\AccountReceivable\InvoiceDetail\Model;

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
 * Class InvoiceDetail
 * This is Invoice Detail model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\InvoiceDetail\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class InvoiceDetailModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $invoiceDetailId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Invoice Project
     * @var int
     */
    private $invoiceProjectId;

    /**
     * Invoice
     * @var int
     */
    private $invoiceId;

    /**
     * Chart Of Account
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Principal Amount
     * @var double
     */
    private $invoiceDetailPrincipalAmount;

    /**
     * Interest Amount
     * @var double
     */
    private $invoiceDetailInterestAmount;

    /**
     * Amount
     * @var double
     */
    private $invoiceDetailAmount;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('invoiceDetail');
        $this->setPrimaryKeyName('invoiceDetailId');
        $this->setMasterForeignKeyName('invoiceDetailId');
        $this->setFilterCharacter('invoiceDetailDescription');
        //$this->setFilterCharacter('invoiceDetailNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['invoiceDetailId'])) {
            $this->setInvoiceDetailId($this->strict($_POST ['invoiceDetailId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_POST ['invoiceProjectId'], 'integer'));
        }
        if (isset($_POST ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_POST ['invoiceId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'));
        }
        if (isset($_POST ['invoiceDetailPrincipalAmount'])) {
            $this->setInvoiceDetailPrincipalAmount($this->strict($_POST ['invoiceDetailPrincipalAmount'], 'double'));
        }
        if (isset($_POST ['invoiceDetailInterestAmount'])) {
            $this->setInvoiceDetailInterestAmount($this->strict($_POST ['invoiceDetailInterestAmount'], 'double'));
        }
        if (isset($_POST ['invoiceDetailAmount'])) {
            $this->setInvoiceDetailAmount($this->strict($_POST ['invoiceDetailAmount'], 'double'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['invoiceDetailId'])) {
            $this->setInvoiceDetailId($this->strict($_GET ['invoiceDetailId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['invoiceProjectId'])) {
            $this->setInvoiceProjectId($this->strict($_GET ['invoiceProjectId'], 'integer'));
        }
        if (isset($_GET ['invoiceId'])) {
            $this->setInvoiceId($this->strict($_GET ['invoiceId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'));
        }
        if (isset($_GET ['invoiceDetailPrincipalAmount'])) {
            $this->setInvoiceDetailPrincipalAmount($this->strict($_GET ['invoiceDetailPrincipalAmount'], 'double'));
        }
        if (isset($_GET ['invoiceDetailInterestAmount'])) {
            $this->setInvoiceDetailInterestAmount($this->strict($_GET ['invoiceDetailInterestAmount'], 'double'));
        }
        if (isset($_GET ['invoiceDetailAmount'])) {
            $this->setInvoiceDetailAmount($this->strict($_GET ['invoiceDetailAmount'], 'double'));
        }
        if (isset($_GET ['invoiceDetailId'])) {
            $this->setTotal(count($_GET ['invoiceDetailId']));
            if (is_array($_GET ['invoiceDetailId'])) {
                $this->invoiceDetailId = array();
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
            if (isset($_GET ['invoiceDetailId'])) {
                $this->setInvoiceDetailId($this->strict($_GET ['invoiceDetailId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getInvoiceDetailId($i, 'array') . ",";
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
    public function getInvoiceDetailId($key, $type) {
        if ($type == 'single') {
            return $this->invoiceDetailId;
        } else {
            if ($type == 'array') {
                return $this->invoiceDetailId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getInvoiceDetailId ?")
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setInvoiceDetailId($value, $key, $type) {
        if ($type == 'single') {
            $this->invoiceDetailId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->invoiceDetailId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setInvoiceDetailId?")
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Invoice Project
     * @return int $invoiceProjectId
     */
    public function getInvoiceProjectId() {
        return $this->invoiceProjectId;
    }

    /**
     * To Set Invoice Project
     * @param int $invoiceProjectId Invoice Project
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setInvoiceProjectId($invoiceProjectId) {
        $this->invoiceProjectId = $invoiceProjectId;
        return $this;
    }

    /**
     * To Return Invoice
     * @return int $invoiceId
     */
    public function getInvoiceId() {
        return $this->invoiceId;
    }

    /**
     * To Set Invoice
     * @param int $invoiceId Invoice
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
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
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setChartOfAccountId($chartOfAccountId) {
        $this->chartOfAccountId = $chartOfAccountId;
        return $this;
    }

    /**
     * To Return Principal Amount
     * @return double $invoiceDetailPrincipalAmount
     */
    public function getInvoiceDetailPrincipalAmount() {
        return $this->invoiceDetailPrincipalAmount;
    }

    /**
     * To Set Principal Amount
     * @param double $invoiceDetailPrincipalAmount Principal Amount
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setInvoiceDetailPrincipalAmount($invoiceDetailPrincipalAmount) {
        $this->invoiceDetailPrincipalAmount = $invoiceDetailPrincipalAmount;
        return $this;
    }

    /**
     * To Return Interest Amount
     * @return double $invoiceDetailInterestAmount
     */
    public function getInvoiceDetailInterestAmount() {
        return $this->invoiceDetailInterestAmount;
    }

    /**
     * To Set Interest Amount
     * @param double $invoiceDetailInterestAmount Interest Amount
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setInvoiceDetailInterestAmount($invoiceDetailInterestAmount) {
        $this->invoiceDetailInterestAmount = $invoiceDetailInterestAmount;
        return $this;
    }

    /**
     * To Return Amount
     * @return double $invoiceDetailAmount
     */
    public function getInvoiceDetailAmount() {
        return $this->invoiceDetailAmount;
    }

    /**
     * To Set Amount
     * @param double $invoiceDetailAmount Amount
     * @return \Core\Financial\AccountReceivable\InvoiceDetail\Model\InvoiceDetailModel
     */
    public function setInvoiceDetailAmount($invoiceDetailAmount) {
        $this->invoiceDetailAmount = $invoiceDetailAmount;
        return $this;
    }

}

?>