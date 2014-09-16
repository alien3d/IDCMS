<?php

namespace Core\System\Management\Role\Model;

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
for ($i = 0; $i < count($d); $i ++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
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
 * Class Role
 * This is role model file.This is to ensure strict setting enable for all variable enter to database 
 * 
 * @name IDCMS.
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Role\Model;
 * @subpackage Management 
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class RoleModel extends ValidationClass {

    /**
     * Primary Key
     * @var int 
     */
    private $roleId;

    /**
     * Company
     * @var int 
     */
    private $companyId;

    /**
     * Code
     * @var string 
     */
    private $roleCode;

    /**
     * Description
     * @var string 
     */
    private $roleDescription;

    /**
     * Is Admin
     * @var bool 
     */
    private $isAdmin;

    /**
     * Is Resource
     * @var bool 
     */
    private $isHumanResource;

    /**
     * Is Customer
     * @var bool 
     */
    private $isCustomer;

    /**
     * Is Ledger
     * @var bool 
     */
    private $isGeneralLedger;

    /**
     * Is Receivable
     * @var bool 
     */
    private $isAccountReceivable;

    /**
     * Is Payable
     * @var bool 
     */
    private $isAccountPayable;

    /**
     * Is Branch
     * @var bool 
     */
    private $isBranch;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('role');
        $this->setPrimaryKeyName('roleId');
        $this->setMasterForeignKeyName('roleId');
        $this->setFilterCharacter('roleDescription');
        //$this->setFilterCharacter('roleNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['roleId'])) {
            $this->setRoleId($this->strict($_POST ['roleId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['roleCode'])) {
            $this->setRoleCode($this->strict($_POST ['roleCode'], 'string'));
        }
        if (isset($_POST ['roleDescription'])) {
            $this->setRoleDescription($this->strict($_POST ['roleDescription'], 'string'));
        }
        if (isset($_POST ['isAdmin'])) {
            $this->setIsAdmin($this->strict($_POST ['isAdmin'], 'bool'));
        }
        if (isset($_POST ['isHumanResource'])) {
            $this->setIsHumanResource($this->strict($_POST ['isHumanResource'], 'bool'));
        }
        if (isset($_POST ['isCustomer'])) {
            $this->setIsCustomer($this->strict($_POST ['isCustomer'], 'bool'));
        }
        if (isset($_POST ['isGeneralLedger'])) {
            $this->setIsGeneralLedger($this->strict($_POST ['isGeneralLedger'], 'bool'));
        }
        if (isset($_POST ['isAccountReceivable'])) {
            $this->setIsAccountReceivable($this->strict($_POST ['isAccountReceivable'], 'bool'));
        }
        if (isset($_POST ['isAccountPayable'])) {
            $this->setIsAccountPayable($this->strict($_POST ['isAccountPayable'], 'bool'));
        }
        if (isset($_POST ['isBranch'])) {
            $this->setIsBranch($this->strict($_POST ['isBranch'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['roleId'])) {
            $this->setRoleId($this->strict($_GET ['roleId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['roleCode'])) {
            $this->setRoleCode($this->strict($_GET ['roleCode'], 'string'));
        }
        if (isset($_GET ['roleDescription'])) {
            $this->setRoleDescription($this->strict($_GET ['roleDescription'], 'string'));
        }
        if (isset($_GET ['isAdmin'])) {
            $this->setIsAdmin($this->strict($_GET ['isAdmin'], 'bool'));
        }
        if (isset($_GET ['isHumanResource'])) {
            $this->setIsHumanResource($this->strict($_GET ['isHumanResource'], 'bool'));
        }
        if (isset($_GET ['isCustomer'])) {
            $this->setIsCustomer($this->strict($_GET ['isCustomer'], 'bool'));
        }
        if (isset($_GET ['isGeneralLedger'])) {
            $this->setIsGeneralLedger($this->strict($_GET ['isGeneralLedger'], 'bool'));
        }
        if (isset($_GET ['isAccountReceivable'])) {
            $this->setIsAccountReceivable($this->strict($_GET ['isAccountReceivable'], 'bool'));
        }
        if (isset($_GET ['isAccountPayable'])) {
            $this->setIsAccountPayable($this->strict($_GET ['isAccountPayable'], 'bool'));
        }
        if (isset($_GET ['isBranch'])) {
            $this->setIsBranch($this->strict($_GET ['isBranch'], 'bool'));
        }
        if (isset($_GET ['roleId'])) {
            $this->setTotal(count($_GET ['roleId']));
            if (is_array($_GET ['roleId'])) {
                $this->roleId = array();
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
            if (isset($_GET ['roleId'])) {
                $this->setRoleId($this->strict($_GET ['roleId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getRoleId($i, 'array') . ",";
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
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setRoleId($value, $key, $type) {
        if ($type == 'single') {
            $this->roleId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->roleId[$key] = $value;
            return $this;
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:setroleId?"));
            exit();
        }
    }

    /**
     * Return Primary Key Value
     * @param array[int]int $key List Of Primary Key.
     * @param array[int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getRoleId($key, $type) {
        if ($type == 'single') {
            return $this->roleId;
        } else if ($type == 'array') {
            return $this->roleId [$key];
        } else {
            echo json_encode(array("success" => false, "message" => "Cannot Identify Type String Or Array:getroleId ?"));
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
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Code 
     * @return string $roleCode
     */
    public function getRoleCode() {
        return $this->roleCode;
    }

    /**
     * To Set Code 
     * @param string $roleCode Code 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setRoleCode($roleCode) {
        $this->roleCode = $roleCode;
        return $this;
    }

    /**
     * To Return Description 
     * @return string $roleDescription
     */
    public function getRoleDescription() {
        return $this->roleDescription;
    }

    /**
     * To Set Description 
     * @param string $roleDescription Description 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setRoleDescription($roleDescription) {
        $this->roleDescription = $roleDescription;
        return $this;
    }

    /**
     * To Return Is Admin 
     * @return bool $isAdmin
     */
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    /**
     * To Set Is Admin 
     * @param bool $isAdmin Is Admin 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * To Return Is Resource 
     * @return bool $isHumanResource
     */
    public function getIsHumanResource() {
        return $this->isHumanResource;
    }

    /**
     * To Set Is Resource 
     * @param bool $isHumanResource Is Resource 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsHumanResource($isHumanResource) {
        $this->isHumanResource = $isHumanResource;
        return $this;
    }

    /**
     * To Return Is Customer 
     * @return bool $isCustomer
     */
    public function getIsCustomer() {
        return $this->isCustomer;
    }

    /**
     * To Set Is Customer 
     * @param bool $isCustomer Is Customer 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsCustomer($isCustomer) {
        $this->isCustomer = $isCustomer;
        return $this;
    }

    /**
     * To Return Is Ledger 
     * @return bool $isGeneralLedger
     */
    public function getIsGeneralLedger() {
        return $this->isGeneralLedger;
    }

    /**
     * To Set Is Ledger 
     * @param bool $isGeneralLedger Is Ledger 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsGeneralLedger($isGeneralLedger) {
        $this->isGeneralLedger = $isGeneralLedger;
        return $this;
    }

    /**
     * To Return Is Receivable 
     * @return bool $isAccountReceivable
     */
    public function getIsAccountReceivable() {
        return $this->isAccountReceivable;
    }

    /**
     * To Set Is Receivable 
     * @param bool $isAccountReceivable Is Receivable 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsAccountReceivable($isAccountReceivable) {
        $this->isAccountReceivable = $isAccountReceivable;
        return $this;
    }

    /**
     * To Return Is Payable 
     * @return bool $isAccountPayable
     */
    public function getIsAccountPayable() {
        return $this->isAccountPayable;
    }

    /**
     * To Set Is Payable 
     * @param bool $isAccountPayable Is Payable 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsAccountPayable($isAccountPayable) {
        $this->isAccountPayable = $isAccountPayable;
        return $this;
    }

    /**
     * To Return Is Branch 
     * @return bool $isBranch
     */
    public function getIsBranch() {
        return $this->isBranch;
    }

    /**
     * To Set Is Branch 
     * @param bool $isBranch Is Branch 
     * @return \Core\System\Management\Role\Model\RoleModel
     */
    public function setIsBranch($isBranch) {
        $this->isBranch = $isBranch;
        return $this;
    }

}

?>