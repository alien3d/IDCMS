<?php

namespace Core\Financial\AccountReceivable\LateInterest\Model;

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
 * Class LateInterest
 * This is lateInterest model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountReceivable\LateInterest\Model;
 * @subpackage AccountReceivable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LateInterestModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $lateInterestId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Type
     * @var int
     */
    private $lateInterestTypeId;

    /**
     * Code
     * @var string
     */
    private $lateInterestCode;

    /**
     * Description
     * @var string
     */
    private $lateInterestDescription;

    /**
     * Warning Day
     * @var int
     */
    private $lateInterestWarningDay;

    /**
     * Grace Day
     * @var int
     */
    private $lateInterestGraceDay;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('lateInterest');
        $this->setPrimaryKeyName('lateInterestId');
        $this->setMasterForeignKeyName('lateInterestId');
        $this->setFilterCharacter('lateInterestDescription');
        //$this->setFilterCharacter('lateInterestNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['lateInterestId'])) {
            $this->setLateInterestId($this->strict($_POST ['lateInterestId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['lateInterestTypeId'])) {
            $this->setLateInterestTypeId($this->strict($_POST ['lateInterestTypeId'], 'integer'));
        }
        if (isset($_POST ['lateInterestCode'])) {
            $this->setLateInterestCode($this->strict($_POST ['lateInterestCode'], 'string'));
        }
        if (isset($_POST ['lateInterestDescription'])) {
            $this->setLateInterestDescription($this->strict($_POST ['lateInterestDescription'], 'string'));
        }
        if (isset($_POST ['lateInterestWarningDay'])) {
            $this->setLateInterestWarningDay($this->strict($_POST ['lateInterestWarningDay'], 'integer'));
        }
        if (isset($_POST ['lateInterestGraceDay'])) {
            $this->setLateInterestGraceDay($this->strict($_POST ['lateInterestGraceDay'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['lateInterestId'])) {
            $this->setLateInterestId($this->strict($_GET ['lateInterestId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['lateInterestTypeId'])) {
            $this->setLateInterestTypeId($this->strict($_GET ['lateInterestTypeId'], 'integer'));
        }
        if (isset($_GET ['lateInterestCode'])) {
            $this->setLateInterestCode($this->strict($_GET ['lateInterestCode'], 'string'));
        }
        if (isset($_GET ['lateInterestDescription'])) {
            $this->setLateInterestDescription($this->strict($_GET ['lateInterestDescription'], 'string'));
        }
        if (isset($_GET ['lateInterestWarningDay'])) {
            $this->setLateInterestWarningDay($this->strict($_GET ['lateInterestWarningDay'], 'integer'));
        }
        if (isset($_GET ['lateInterestGraceDay'])) {
            $this->setLateInterestGraceDay($this->strict($_GET ['lateInterestGraceDay'], 'integer'));
        }
        if (isset($_GET ['lateInterestId'])) {
            $this->setTotal(count($_GET ['lateInterestId']));
            if (is_array($_GET ['lateInterestId'])) {
                $this->lateInterestId = array();
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
            if (isset($_GET ['lateInterestId'])) {
                $this->setLateInterestId($this->strict($_GET ['lateInterestId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getLateInterestId($i, 'array') . ",";
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
    public function getLateInterestId($key, $type) {
        if ($type == 'single') {
            return $this->lateInterestId;
        } else {
            if ($type == 'array') {
                return $this->lateInterestId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getlateInterestId ?")
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
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setLateInterestId($value, $key, $type) {
        if ($type == 'single') {
            $this->lateInterestId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->lateInterestId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setlateInterestId?")
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
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Type
     * @return int $lateInterestTypeId
     */
    public function getLateInterestTypeId() {
        return $this->lateInterestTypeId;
    }

    /**
     * To Set Type
     * @param int $lateInterestTypeId Type
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setLateInterestTypeId($lateInterestTypeId) {
        $this->lateInterestTypeId = $lateInterestTypeId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $lateInterestCode
     */
    public function getLateInterestCode() {
        return $this->lateInterestCode;
    }

    /**
     * To Set Code
     * @param string $lateInterestCode Code
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setLateInterestCode($lateInterestCode) {
        $this->lateInterestCode = $lateInterestCode;
        return $this;
    }

    /**
     * To Return Description
     * @return string $lateInterestDescription
     */
    public function getLateInterestDescription() {
        return $this->lateInterestDescription;
    }

    /**
     * To Set Description
     * @param string $lateInterestDescription Description
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setLateInterestDescription($lateInterestDescription) {
        $this->lateInterestDescription = $lateInterestDescription;
        return $this;
    }

    /**
     * To Return Warning Day
     * @return int $lateInterestWarningDay
     */
    public function getLateInterestWarningDay() {
        return $this->lateInterestWarningDay;
    }

    /**
     * To Set Warning Day
     * @param int $lateInterestWarningDay Warning Day
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setLateInterestWarningDay($lateInterestWarningDay) {
        $this->lateInterestWarningDay = $lateInterestWarningDay;
        return $this;
    }

    /**
     * To Return Grace Day
     * @return int $lateInterestGraceDay
     */
    public function getLateInterestGraceDay() {
        return $this->lateInterestGraceDay;
    }

    /**
     * To Set Grace Day
     * @param int $lateInterestGraceDay Grace Day
     * @return \Core\Financial\AccountReceivable\LateInterest\Model\LateInterestModel
     */
    public function setLateInterestGraceDay($lateInterestGraceDay) {
        $this->lateInterestGraceDay = $lateInterestGraceDay;
        return $this;
    }

}

?>