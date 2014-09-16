<?php

namespace Core\System\Management\EmployeeCertificate\Model;

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
 * Class EmployeeCertificate
 * This is Employee Certificate model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Management\EmployeeCertificate\Model;
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class EmployeeCertificateModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $employeeCertificateId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Certificate
     * @var int
     */
    private $certificateId;

    /**
     * Employee
     * @var int
     */
    private $employeeId;

    /**
     * Employee Certificate Valid   Start
     * @var string
     */
    private $employeeCertificateValidStart;

    /**
     * Valid End
     * @var string
     */
    private $employeeCertificateValidEnd;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('employeeCertificate');
        $this->setPrimaryKeyName('employeeCertificateId');
        $this->setMasterForeignKeyName('employeeCertificateId');
        $this->setFilterCharacter('employeeCertificateDescription');
        //$this->setFilterCharacter('employeeCertificateNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['employeeCertificateId'])) {
            $this->setEmployeeCertificateId($this->strict($_POST ['employeeCertificateId'], 'string'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'string'));
        }
        if (isset($_POST ['certificateId'])) {
            $this->setCertificateId($this->strict($_POST ['certificateId'], 'string'));
        }
        if (isset($_POST ['employeeId'])) {
            $this->setEmployeeId($this->strict($_POST ['employeeId'], 'string'));
        }
        if (isset($_POST ['employeeCertificateValidStart'])) {
            $this->setEmployeeCertificateValidStart($this->strict($_POST ['employeeCertificateValidStart'], 'string'));
        }
        if (isset($_POST ['employeeCertificateValidEnd'])) {
            $this->setEmployeeCertificateValidEnd($this->strict($_POST ['employeeCertificateValidEnd'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['employeeCertificateId'])) {
            $this->setEmployeeCertificateId($this->strict($_GET ['employeeCertificateId'], 'string'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'string'));
        }
        if (isset($_GET ['certificateId'])) {
            $this->setCertificateId($this->strict($_GET ['certificateId'], 'string'));
        }
        if (isset($_GET ['employeeId'])) {
            $this->setEmployeeId($this->strict($_GET ['employeeId'], 'string'));
        }
        if (isset($_GET ['employeeCertificateValidStart'])) {
            $this->setEmployeeCertificateValidStart($this->strict($_GET ['employeeCertificateValidStart'], 'string'));
        }
        if (isset($_GET ['employeeCertificateValidEnd'])) {
            $this->setEmployeeCertificateValidEnd($this->strict($_GET ['employeeCertificateValidEnd'], 'string'));
        }
        if (isset($_GET ['employeeCertificateId'])) {
            $this->setTotal(count($_GET ['employeeCertificateId']));
            if (is_array($_GET ['employeeCertificateId'])) {
                $this->employeeCertificateId = array();
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
            if (isset($_GET ['employeeCertificateId'])) {
                $this->setEmployeeCertificateId(
                        $this->strict($_GET ['employeeCertificateId'] [$i], 'numeric'), $i, 'array'
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
            $primaryKeyAll .= $this->getEmployeeCertificateId($i, 'array') . ",";
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
    public function getEmployeeCertificateId($key, $type) {
        if ($type == 'single') {
            return $this->employeeCertificateId;
        } else {
            if ($type == 'array') {
                return $this->employeeCertificateId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getEmployeeCertificateId ?"
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
     * @return \Core\System\Management\EmployeeCertificate\Model\EmployeeCertificateModel
     */
    public function setEmployeeCertificateId($value, $key, $type) {
        if ($type == 'single') {
            $this->employeeCertificateId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->employeeCertificateId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setEmployeeCertificateId?"
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
     * To Return  Company
     * @return int $companyId
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * To Set Company
     * @param int $companyId Company
     * @return \Core\System\Management\EmployeeCertificate\Model\EmployeeCertificateModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Certificate
     * @return int $certificateId
     */
    public function getCertificateId() {
        return $this->certificateId;
    }

    /**
     * To Set Certificate
     * @param int $certificateId Certificate
     * @return \Core\System\Management\EmployeeCertificate\Model\EmployeeCertificateModel
     */
    public function setCertificateId($certificateId) {
        $this->certificateId = $certificateId;
        return $this;
    }

    /**
     * To Return Employee
     * @return int $employeeId
     */
    public function getEmployeeId() {
        return $this->employeeId;
    }

    /**
     * To Set Employee
     * @param int $employeeId Employee
     * @return \Core\System\Management\EmployeeCertificate\Model\EmployeeCertificateModel
     */
    public function setEmployeeId($employeeId) {
        $this->employeeId = $employeeId;
        return $this;
    }

    /**
     * To Return Certificate Valid Start
     * @return string $employeeCertificateValidStart
     */
    public function getEmployeeCertificateValidStart() {
        return $this->employeeCertificateValidStart;
    }

    /**
     * To Set Certificate Valid Start
     * @param string $employeeCertificateValidStart Employee Certificate Valid   Start
     * @return \Core\System\Management\EmployeeCertificate\Model\EmployeeCertificateModel
     */
    public function setEmployeeCertificateValidStart($employeeCertificateValidStart) {
        $this->employeeCertificateValidStart = $employeeCertificateValidStart;
        return $this;
    }

    /**
     * To Return Valid End
     * @return string $employeeCertificateValidEnd
     */
    public function getEmployeeCertificateValidEnd() {
        return $this->employeeCertificateValidEnd;
    }

    /**
     * To Set Valid End
     * @param string $employeeCertificateValidEnd Valid End
     * @return \Core\System\Management\EmployeeCertificate\Model\EmployeeCertificateModel
     */
    public function setEmployeeCertificateValidEnd($employeeCertificateValidEnd) {
        $this->employeeCertificateValidEnd = $employeeCertificateValidEnd;
        return $this;
    }

}

?>