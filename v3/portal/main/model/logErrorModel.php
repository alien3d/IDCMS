<?php

namespace Core\Portal\Main\LogError\Model;

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
 * Class LogError
 * This is logError model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\LogError\Model;
 * @subpackage Main
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LogErrorModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $logErrorId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Application
     * @var int
     */
    private $applicationId;

    /**
     * Module
     * @var int
     */
    private $moduleId;

    /**
     * Folder
     * @var int
     */
    private $folderId;

    /**
     * Leaf
     * @var int
     */
    private $leafId;

    /**
     * Role
     * @var int
     */
    private $roleId;

    /**
     * Staff
     * @var int
     */
    private $staffId;

    /**
     * Operation
     * @var string
     */
    private $logErrorOperation;

    /**
     * Sql
     * @var string
     */
    private $logErrorsql;

    /**
     * Date
     * @var time
     */
    private $logErrordate;

    /**
     * Access
     * @var string
     */
    private $logErrorAccess;

    /**
     *
     * @var string
     */
    private $logError;

    /**
     * Guid
     * @var string
     */
    private $logErrorguid;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('logError');
        $this->setPrimaryKeyName('logErrorId');
        $this->setMasterForeignKeyName('logErrorId');
        $this->setFilterCharacter('logErrorDescription');
        //$this->setFilterCharacter('logErrorNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['logErrorId'])) {
            $this->setLogErrorId($this->strict($_POST ['logErrorId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['applicationId'])) {
            $this->setApplicationId($this->strict($_POST ['applicationId'], 'int'));
        }
        if (isset($_POST ['moduleId'])) {
            $this->setModuleId($this->strict($_POST ['moduleId'], 'int'));
        }
        if (isset($_POST ['folderId'])) {
            $this->setFolderId($this->strict($_POST ['folderId'], 'int'));
        }
        if (isset($_POST ['leafId'])) {
            $this->setLeafId($this->strict($_POST ['leafId'], 'int'));
        }
        if (isset($_POST ['roleId'])) {
            $this->setRoleId($this->strict($_POST ['roleId'], 'int'));
        }
        if (isset($_POST ['staffId'])) {
            $this->setStaffId($this->strict($_POST ['staffId'], 'int'));
        }
        if (isset($_POST ['logErrorOperation'])) {
            $this->setLogErrorOperation($this->strict($_POST ['logErrorOperation'], 'string'));
        }
        if (isset($_POST ['logErrorsql'])) {
            $this->setLogErrorsql($this->strict($_POST ['logErrorsql'], 'string'));
        }
        if (isset($_POST ['logErrordate'])) {
            $this->setLogErrordate($this->strict($_POST ['logErrordate'], 'time'));
        }
        if (isset($_POST ['logErrorAccess'])) {
            $this->setLogErrorAccess($this->strict($_POST ['logErrorAccess'], 'string'));
        }
        if (isset($_POST ['logError'])) {
            $this->setLogError($this->strict($_POST ['logError'], 'string'));
        }
        if (isset($_POST ['logErrorguid'])) {
            $this->setLogErrorguid($this->strict($_POST ['logErrorguid'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['logErrorId'])) {
            $this->setLogErrorId($this->strict($_GET ['logErrorId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['applicationId'])) {
            $this->setApplicationId($this->strict($_GET ['applicationId'], 'int'));
        }
        if (isset($_GET ['moduleId'])) {
            $this->setModuleId($this->strict($_GET ['moduleId'], 'int'));
        }
        if (isset($_GET ['folderId'])) {
            $this->setFolderId($this->strict($_GET ['folderId'], 'int'));
        }
        if (isset($_GET ['leafId'])) {
            $this->setLeafId($this->strict($_GET ['leafId'], 'int'));
        }
        if (isset($_GET ['roleId'])) {
            $this->setRoleId($this->strict($_GET ['roleId'], 'int'));
        }
        if (isset($_GET ['staffId'])) {
            $this->setStaffId($this->strict($_GET ['staffId'], 'int'));
        }
        if (isset($_GET ['logErrorOperation'])) {
            $this->setLogErrorOperation($this->strict($_GET ['logErrorOperation'], 'string'));
        }
        if (isset($_GET ['logErrorsql'])) {
            $this->setLogErrorsql($this->strict($_GET ['logErrorsql'], 'string'));
        }
        if (isset($_GET ['logErrordate'])) {
            $this->setLogErrordate($this->strict($_GET ['logErrordate'], 'time'));
        }
        if (isset($_GET ['logErrorAccess'])) {
            $this->setLogErrorAccess($this->strict($_GET ['logErrorAccess'], 'string'));
        }
        if (isset($_GET ['logError'])) {
            $this->setLogError($this->strict($_GET ['logError'], 'string'));
        }
        if (isset($_GET ['logErrorguid'])) {
            $this->setLogErrorguid($this->strict($_GET ['logErrorguid'], 'string'));
        }
        if (isset($_GET ['logErrorId'])) {
            $this->setTotal(count($_GET ['logErrorId']));
            if (is_array($_GET ['logErrorId'])) {
                $this->logErrorId = array();
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
            if (isset($_GET ['logErrorId'])) {
                $this->setLogErrorId($this->strict($_GET ['logErrorId'] [$i], 'numeric'), $i, 'array');
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
                }
                if ($_GET ['isUpdate'] [$i] == 'false') {
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
            $primaryKeyAll .= $this->getLogErrorId($i, 'array') . ",";
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
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')");
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array|string
     */
    public function getLogErrorId($key, $type) {
        if ($type == 'single') {
            return $this->logErrorId;
        } else if ($type == 'array') {
            return $this->logErrorId [$key];
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:getlogErrorId ?")
            );
            exit();
        }
    }

    /**
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogErrorId($value, $key, $type) {
        if ($type == 'single') {
            $this->logErrorId = $value;
            return $this;
        } else if ($type == 'array') {
            $this->logErrorId[$key] = $value;
            return $this;
        } else {
            echo json_encode(
                    array("success" => false, "message" => "Cannot Identify Type String Or Array:setlogErrorId?")
            );
            exit();
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
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Application
     * @return int $applicationId
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * To Set Application
     * @param int $applicationId Application
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setApplicationId($applicationId) {
        $this->applicationId = $applicationId;
        return $this;
    }

    /**
     * To Return Module
     * @return int $moduleId
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * To Set Module
     * @param int $moduleId Module
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setModuleId($moduleId) {
        $this->moduleId = $moduleId;
        return $this;
    }

    /**
     * To Return Folder
     * @return int $folderId
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * To Set Folder
     * @param int $folderId Folder
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setFolderId($folderId) {
        $this->folderId = $folderId;
        return $this;
    }

    /**
     * To Return Leaf
     * @return int $leafId
     */
    public function getLeafId() {
        return $this->leafId;
    }

    /**
     * To Set Leaf
     * @param int $leafId Leaf
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLeafId($leafId) {
        $this->leafId = $leafId;
        return $this;
    }

    /**
     * To Return Role
     * @return int $roleId
     */
    public function getRoleId() {
        return $this->roleId;
    }

    /**
     * To Set Role
     * @param int $roleId Role
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setRoleId($roleId) {
        $this->roleId = $roleId;
        return $this;
    }

    /**
     * To Return Staff
     * @return int $staffId
     */
    public function getStaffId() {
        return $this->staffId;
    }

    /**
     * To Set Staff
     * @param int $staffId Staff
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setStaffId($staffId) {
        $this->staffId = $staffId;
        return $this;
    }

    /**
     * To Return Operation
     * @return string $logErrorOperation
     */
    public function getLogErrorOperation() {
        return $this->logErrorOperation;
    }

    /**
     * To Set Operation
     * @param string $logErrorOperation Operation
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogErrorOperation($logErrorOperation) {
        $this->logErrorOperation = $logErrorOperation;
        return $this;
    }

    /**
     * To Return Sql
     * @return string $logErrorsql
     */
    public function getLogErrorsql() {
        return $this->logErrorsql;
    }

    /**
     * To Set Sql
     * @param string $logErrorsql Sql
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogErrorsql($logErrorsql) {
        $this->logErrorsql = $logErrorsql;
        return $this;
    }

    /**
     * To Return Date
     * @return time $logErrordate
     */
    public function getLogErrordate() {
        return $this->logErrordate;
    }

    /**
     * To Set Date
     * @param time $logErrordate Date
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogErrordate($logErrordate) {
        $this->logErrordate = $logErrordate;
        return $this;
    }

    /**
     * To Return Access
     * @return string $logErrorAccess
     */
    public function getLogErrorAccess() {
        return $this->logErrorAccess;
    }

    /**
     * To Set Access
     * @param string $logErrorAccess Access
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogErrorAccess($logErrorAccess) {
        $this->logErrorAccess = $logErrorAccess;
        return $this;
    }

    /**
     * To Return
     * @return string $logError
     */
    public function getLogError() {
        return $this->logError;
    }

    /**
     * To Set
     * @param string $logError
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogError($logError) {
        $this->logError = $logError;
        return $this;
    }

    /**
     * To Return Guid
     * @return string $logErrorguid
     */
    public function getLogErrorguid() {
        return $this->logErrorguid;
    }

    /**
     * To Set Guid
     * @param string $logErrorguid Guid
     * @return \Core\Portal\Main\LogError\Model\LogErrorModel
     */
    public function setLogErrorguid($logErrorguid) {
        $this->logErrorguid = $logErrorguid;
        return $this;
    }

}

?>