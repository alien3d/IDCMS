<?php

namespace Core\Financial\GeneralLedger\ChartOfAccount\Model;

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
 * Class ChartOfAccount
 * This is Chart Of Account model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\GeneralLedger\ChartOfAccount\Model;
 * @subpackage GeneralLedger
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ChartOfAccountModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $chartOfAccountId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Category
     * @var int
     */
    private $chartOfAccountCategoryId;

    /**
     * Type
     * @var int
     */
    private $chartOfAccountTypeId;

    /**
     * Number
     * @var string
     */
    private $chartOfAccountNumber;

    /**
     * Title
     * @var string
     */
    private $chartOfAccountTitle;

    /**
     * Description
     * @var string
     */
    private $chartOfAccountDescription;

    /**
     * Is Consolidation
     * @var bool
     */
    private $isConsolidation;

    /**
     * Is Slice
     * @var bool
     */
    private $isSlice;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('chartOfAccount');
        $this->setPrimaryKeyName('chartOfAccountId');
        $this->setMasterForeignKeyName('chartOfAccountId');
        $this->setFilterCharacter('chartOfAccountDescription');
        //$this->setFilterCharacter('chartOfAccountNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_POST ['chartOfAccountId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_POST ['chartOfAccountCategoryId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountTypeId'])) {
            $this->setChartOfAccountTypeId($this->strict($_POST ['chartOfAccountTypeId'], 'integer'));
        }
        if (isset($_POST ['chartOfAccountNumber'])) {
            $this->setChartOfAccountNumber($this->strict($_POST ['chartOfAccountNumber'], 'string'));
        }
        if (isset($_POST ['chartOfAccountTitle'])) {
            $this->setChartOfAccountTitle($this->strict($_POST ['chartOfAccountTitle'], 'string'));
        }
        if (isset($_POST ['chartOfAccountDescription'])) {
            $this->setChartOfAccountDescription($this->strict($_POST ['chartOfAccountDescription'], 'string'));
        }
        if (isset($_POST ['isConsolidation'])) {
            $this->setIsConsolidation($this->strict($_POST ['isConsolidation'], 'bool'));
        }
        if (isset($_POST ['isSlice'])) {
            $this->setIsSlice($this->strict($_POST ['isSlice'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountCategoryId'])) {
            $this->setChartOfAccountCategoryId($this->strict($_GET ['chartOfAccountCategoryId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountTypeId'])) {
            $this->setChartOfAccountTypeId($this->strict($_GET ['chartOfAccountTypeId'], 'integer'));
        }
        if (isset($_GET ['chartOfAccountNumber'])) {
            $this->setChartOfAccountNumber($this->strict($_GET ['chartOfAccountNumber'], 'string'));
        }
        if (isset($_GET ['chartOfAccountTitle'])) {
            $this->setChartOfAccountTitle($this->strict($_GET ['chartOfAccountTitle'], 'string'));
        }
        if (isset($_GET ['chartOfAccountDescription'])) {
            $this->setChartOfAccountDescription($this->strict($_GET ['chartOfAccountDescription'], 'string'));
        }
        if (isset($_GET ['isConsolidation'])) {
            $this->setIsConsolidation($this->strict($_GET ['isConsolidation'], 'bool'));
        }
        if (isset($_GET ['isSlice'])) {
            $this->setIsSlice($this->strict($_GET ['isSlice'], 'bool'));
        }
        if (isset($_GET ['chartOfAccountId'])) {
            $this->setTotal(count($_GET ['chartOfAccountId']));
            if (is_array($_GET ['chartOfAccountId'])) {
                $this->chartOfAccountId = array();
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
            if (isset($_GET ['chartOfAccountId'])) {
                $this->setChartOfAccountId($this->strict($_GET ['chartOfAccountId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getChartOfAccountId($i, 'array') . ",";
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
    public function getChartOfAccountId($key, $type) {
        if ($type == 'single') {
            return $this->chartOfAccountId;
        } else {
            if ($type == 'array') {
                return $this->chartOfAccountId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getChartOfAccountId ?")
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
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setChartOfAccountId($value, $key, $type) {
        if ($type == 'single') {
            $this->chartOfAccountId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->chartOfAccountId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setChartOfAccountId?")
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
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Category
     * @return int $chartOfAccountCategoryId
     */
    public function getChartOfAccountCategoryId() {
        return $this->chartOfAccountCategoryId;
    }

    /**
     * To Set Category
     * @param int $chartOfAccountCategoryId Category
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setChartOfAccountCategoryId($chartOfAccountCategoryId) {
        $this->chartOfAccountCategoryId = $chartOfAccountCategoryId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $chartOfAccountTypeId
     */
    public function getChartOfAccountTypeId() {
        return $this->chartOfAccountTypeId;
    }

    /**
     * To Set Type
     * @param int $chartOfAccountTypeId Type
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setChartOfAccountTypeId($chartOfAccountTypeId) {
        $this->chartOfAccountTypeId = $chartOfAccountTypeId;
        return $this;
    }

    /**
     * To Return  Chart Of Account Number
     * @return string $chartOfAccountNumber
     */
    public function getChartOfAccountNumber() {
        return $this->chartOfAccountNumber;
    }

    /**
     * To Set Chart Of AccountNumber
     * @param string $chartOfAccountNumber Number
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setChartOfAccountNumber($chartOfAccountNumber) {
        $this->chartOfAccountNumber = $chartOfAccountNumber;
        return $this;
    }

    /**
     * To Return Title
     * @return string $chartOfAccountTitle
     */
    public function getChartOfAccountTitle() {
        return $this->chartOfAccountTitle;
    }

    /**
     * To Set Title
     * @param string $chartOfAccountTitle Title
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setChartOfAccountTitle($chartOfAccountTitle) {
        $this->chartOfAccountTitle = $chartOfAccountTitle;
        return $this;
    }

    /**
     * To Return Description
     * @return string $chartOfAccountDescription
     */
    public function getChartOfAccountDescription() {
        return $this->chartOfAccountDescription;
    }

    /**
     * To Set Description
     * @param string $chartOfAccountDescription Description
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setChartOfAccountDescription($chartOfAccountDescription) {
        $this->chartOfAccountDescription = $chartOfAccountDescription;
        return $this;
    }

    /**
     * To Return Is Consolidation/Merge
     * @return bool $isConsolidation
     */
    public function getIsConsolidation() {
        return $this->isConsolidation;
    }

    /**
     * To Set Is Consolidation/Merge
     * @param bool $isConsolidation Is Consolidation
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setIsConsolidation($isConsolidation) {
        $this->isConsolidation = $isConsolidation;
        return $this;
    }

    /**
     * To Return Is Slice
     * @return bool $isSlice
     */
    public function getIsSlice() {
        return $this->isSlice;
    }

    /**
     * To Set Is Slice
     * @param bool $isSlice Is Slice
     * @return \Core\Financial\GeneralLedger\ChartOfAccount\Model\ChartOfAccountModel
     */
    public function setIsSlice($isSlice) {
        $this->isSlice = $isSlice;
        return $this;
    }

}

?>