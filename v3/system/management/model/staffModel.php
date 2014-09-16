<?php

namespace Core\System\Management\Staff\Model;

// using absolute path instead of relative path..
// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class StaffModel
 * this is staff model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Staff\Model
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $staffId;

    /**
     * Role
     * @var int
     */
    private $roleId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Branch
     * @var int
     */
    private $branchId;

    /**
     * Department
     * @var int
     */
    private $departmentId;

    /**
     * Language
     * @var int
     */
    private $languageId;

    /**
     * Theme
     * @var int
     */
    private $themeId;

    /**
     * Password
     * @var string
     */
    private $staffPassword;

    /**
     * Name
     * @var string
     */
    private $staffName;

    /**
     * First Name
     * @var string
     */
    private $staffFirstName;

    /**
     * Last Name
     * @var string
     */
    private $staffLastName;

    /**
     * Staff Email
     * @var string
     */
    private $staffEmail;

    /**
     * Avatar
     * @var string
     */
    private $staffAvatar;

    //  registration

    /**
     * Verification Code
     * @var string
     */
    private $verificationCode;

    /**
     * Security Token Web
     * @var string
     */
    private $securityTokenWeb;

    // end registration

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('staff');
        $this->setPrimaryKeyName('staffId');
        $this->setMasterForeignKeyName('staffId');
        $this->setFilterCharacter('staffName');
        //$this->setFilterCharacter('staffName');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['staffId'])) {
            $this->setStaffId($this->strict($_POST ['staffId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['roleId'])) {
            $this->setRoleId($this->strict($_POST ['roleId'], 'integer'));
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['branchId'])) {
            $this->setBranchId($this->strict($_POST ['branchId'], 'integer'));
        }
        if (isset($_POST ['departmentId'])) {
            $this->setDepartmentId($this->strict($_POST ['departmentId'], 'integer'));
        }
        if (isset($_POST ['languageId'])) {
            $this->setLanguageId($this->strict($_POST ['languageId'], 'integer'));
        }
        if (isset($_POST ['themeId'])) {
            $this->setThemeId($this->strict($_POST ['themeId'], 'integer'));
        }
        if (isset($_POST ['staffPassword'])) {
            $this->setStaffPassword($this->strict($_POST ['staffPassword'], 'password'));
        }
        if (isset($_POST ['password'])) {
            $this->setStaffPassword($this->strict($_POST ['password'], 'password'));
        }
        if (isset($_POST ['staffName'])) {
            $this->setStaffName($this->strict($_POST ['staffName'], 'string'));
        }
        if (isset($_POST ['staffFirstName'])) {
            $this->setStaffFirstName($this->strict($_POST ['staffFirstName'], 'string'));
        }
        if (isset($_POST ['staffLastName'])) {
            $this->setStaffLastName($this->strict($_POST ['staffLastName'], 'string'));
        }
        if (isset($_POST ['username'])) {
            $this->setStaffName($this->strict($_POST ['username'], 'string'));
        }
        if (isset($_POST ['staffAvatar'])) {
            $this->setStaffAvatar($this->strict($_POST ['staffAvatar'], 'string'));
        }
        if (isset($_POST ['staffEmail'])) {
            $this->setStaffEmail($this->strict($_POST ['staffEmail'], 'string'));
        }
        if (isset($_POST ['from'])) {
            $this->setFrom($this->strict($_POST ['from'], 'string'));
        }
        if (isset($_POST ['verificationCode'])) {
            $this->setVerificationCode($this->strict($_POST ['verificationCode'], 'string'));
        }
        if (isset($_POST ['securityTokenWeb'])) {
            $this->setSecurityTokenWeb($this->strict($_POST ['securityTokenWeb'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['staffId'])) {
            $this->setStaffId($this->strict($_GET ['staffId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['roleId'])) {
            $this->setRoleId($this->strict($_GET ['roleId'], 'integer'));
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['branchId'])) {
            $this->setBranchId($this->strict($_GET ['branchId'], 'integer'));
        }
        if (isset($_GET ['departmentId'])) {
            $this->setDepartmentId($this->strict($_GET ['departmentId'], 'integer'));
        }
        if (isset($_GET ['languageId'])) {
            $this->setLanguageId($this->strict($_GET ['languageId'], 'integer'));
        }
        if (isset($_GET ['themeId'])) {
            $this->setThemeId($this->strict($_GET ['themeId'], 'integer'));
        }
        if (isset($_GET ['staffPassword'])) {
            $this->setStaffPassword($this->strict($_GET ['staffPassword'], 'password'));
        }
        if (isset($_GET ['password'])) {
            $this->setStaffPassword($this->strict($_GET ['password'], 'password'));
        }
        if (isset($_GET ['staffName'])) {
            $this->setStaffName($this->strict($_GET ['staffName'], 'string'));
        }
        if (isset($_GET ['staffFirstName'])) {
            $this->setStaffFirstName($this->strict($_GET ['staffFirstName'], 'string'));
        }
        if (isset($_GET ['staffLastName'])) {
            $this->setStaffLastName($this->strict($_GET ['staffLastName'], 'string'));
        }
        if (isset($_GET ['username'])) {
            $this->setStaffName($this->strict($_GET ['username'], 'string'));
        }
        if (isset($_GET ['staffEmail'])) {
            $this->setStaffEmail($this->strict($_GET ['staffEmail'], 'string'));
        }
        if (isset($_GET ['from'])) {
            $this->setFrom($this->strict($_GET ['from'], 'string'));
        }
        if (isset($_GET ['verificationCode'])) {
            $this->setVerificationCode($this->strict($_GET ['verificationCode'], 'string'));
        }
        if (isset($_GET ['securityTokenWeb'])) {
            $this->setSecurityTokenWeb($this->strict($_GET ['securityTokenWeb'], 'string'));
        }
        if (isset($_GET ['staffAvatar'])) {
            $this->setStaffAvatar($this->strict($_GET ['staffAvatar'], 'string'));
        }
        if (isset($_GET ['staffId'])) {
            $this->setTotal(count($_GET ['staffId']));
            if (is_array($_GET ['staffId'])) {
                $this->staffId = array();
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
            if (isset($_GET ['staffId'])) {
                $this->setStaffId($this->strict($_GET ['staffId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getStaffId($i, 'array') . ",";
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
        } else if ($this->getVendor() == self::MSSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
        } else if ($this->getVendor() == self::ORACLE) {
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
        }
    }

    /**
     * Return staff Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getStaffId($key, $type) {
        if ($type == 'single') {
            return $this->staffId;
        } else {
            if ($type == 'array') {
                return $this->staffId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getStaffId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set staff Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffId($value, $key, $type) {
        if ($type == 'single') {
            $this->staffId = $value;
        } else {
            if ($type == 'array') {
                $this->staffId[$key] = $value;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setStaffId?")
                );
                exit();
            }
        }
    }

    /**
     * Create
     * @see ValidationClass::create()
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
     * To Return Role
     * @return int $roleId Role Primary Key
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * To Set Role
     * @param int $roleId Role Primary Key
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setRoleId($roleId) {
        $this->roleId = $roleId;
        return $this;
    }

    /**
     * To Return Company
     * @return  int $companyId Company Primary Key
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company Primary Key
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Branch
     * @return int $branchId Branch Primary Key
     */
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * To Set Branch
     * @param int $branchId Branch Primary Key
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setBranchId($branchId) {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * To Return Department
     * @return int $departmentId Department Primary Key
     */
    public function getDepartmentId() {
        return $this->departmentId;
    }

    /**
     * To Set Department
     * @param int $departmentId Department Primary Key
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setDepartmentId($departmentId) {
        $this->departmentId = $departmentId;
        return $this;
    }

    /**
     * To Return Language
     * @return int $languageId Language Primary Key
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    /**
     * To Set Language
     * @param int $languageId Language Primary Key
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * To Return Theme
     * @return int $themeId Theme Primary Key
     */
    public function getThemeId() {
        return $this->themeId;
    }

    /**
     * To Set Theme
     * @param int $themeId Theme Primary Key
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setThemeId($themeId) {
        $this->themeId = $themeId;
        return $this;
    }

    /**
     * To Return Password
     * @return string $staffPassword Password
     */
    public function getStaffPassword() {
        return $this->staffPassword;
    }

    /**
     * To Set Password
     * @param string $staffPassword Password
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffPassword($staffPassword) {
        $this->staffPassword = $staffPassword;
        return $this;
    }

    /**
     * To Return Name
     * @return string $staffName Name
     */
    public function getStaffName() {
        return $this->staffName;
    }

    /**
     * To Set Name
     * @param string $staffName Name
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffName($staffName) {
        $this->staffName = $staffName;
        return $this;
    }

    /**
     * To Return FirstName
     * @return string $staffFirstName Name
     */
    public function getStaffFirstName() {
        return $this->staffFirstName;
    }

    /**
     * To Set Name
     * @param string $staffFirstName Name
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffFirstName($staffFirstName) {
        $this->staffFirstName = $staffFirstName;
        return $this;
    }

    /**
     * To Return Last Name
     * @return string $staffLastName Name
     */
    public function getStaffLastName() {
        return $this->staffLastName;
    }

    /**
     * To Set Last Name
     * @param string $staffLastName Name
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffLastName($staffLastName) {
        $this->staffLastName = $staffLastName;
        return $this;
    }

    /**
     * To Return Staff Email
     * @return string $staffNumber Staff Number
     */
    public function getStaffEmail() {
        return $this->staffEmail;
    }

    /**
     * To Set Staff Email
     * @param string $staffEmail Email
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffEmail($staffEmail) {
        $this->staffEmail = $staffEmail;
        return $this;
    }

    /**
     * To Return Avatar
     * @return string $staffAvatar Avatar
     */
    public function getStaffAvatar() {
        return $this->staffAvatar;
    }

    /**
     * To Set Avatar
     * @param string $staffAvatar Avatar
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setStaffAvatar($staffAvatar) {
        $this->staffAvatar = $staffAvatar;
        return $this;
    }

    /**
     * Return Verification Code
     * @return string
     */
    public function getVerificationCode() {
        return $this->verificationCode;
    }

    /**
     * Set Verification Code
     * @param string $verificationCode
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setVerificationCode($verificationCode) {
        $this->verificationCode = $verificationCode;
        return $this;
    }

    /**
     * Return Security Token Web
     * @return string
     */
    public function getSecurityTokenWeb() {
        return $this->securityTokenWeb;
    }

    /**
     * Set Security Token Web
     * @param string $securityTokenWeb
     * @return \Core\System\Management\Staff\Model\StaffModel
     */
    public function setSecurityTokenWeb($securityTokenWeb) {
        $this->securityTokenWeb = $securityTokenWeb;
        return $this;
    }

}

?>