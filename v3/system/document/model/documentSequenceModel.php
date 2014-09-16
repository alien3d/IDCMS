<?php

namespace Core\System\Document\DocumentSequence\Model;

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
 * Class DocumentSequence
 * This is documentSequence model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Document\DocumentSequence\Model;
 * @subpackage Document
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DocumentSequenceModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $documentSequenceId;

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
     * Code
     * @var string
     */
    private $documentSequenceCode;

    /**
     * Number
     * @var int
     */
    private $documentSequenceNumber;

    /**
     * Start
     * @var int
     */
    private $documentSequenceStart;

    /**
     * End
     * @var int
     */
    private $documentSequenceEnd;

    /**
     * Description
     * @var string
     */
    private $documentSequenceDescription;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('documentSequence');
        $this->setPrimaryKeyName('documentSequenceId');
        $this->setMasterForeignKeyName('documentSequenceId');
        $this->setFilterCharacter('documentSequenceDescription');
        //$this->setFilterCharacter('documentSequenceNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['documentSequenceId'])) {
            $this->setDocumentSequenceId($this->strict($_POST ['documentSequenceId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['applicationId'])) {
            $this->setApplicationId($this->strict($_POST ['applicationId'], 'integer'));
        }
        if (isset($_POST ['moduleId'])) {
            $this->setModuleId($this->strict($_POST ['moduleId'], 'integer'));
        }
        if (isset($_POST ['documentSequenceCode'])) {
            $this->setDocumentSequenceCode($this->strict($_POST ['documentSequenceCode'], 'string'));
        }
        if (isset($_POST ['documentSequenceNumber'])) {
            $this->setDocumentSequenceNumber($this->strict($_POST ['documentSequenceNumber'], 'integer'));
        }
        if (isset($_POST ['documentSequenceStart'])) {
            $this->setDocumentSequenceStart($this->strict($_POST ['documentSequenceStart'], 'integer'));
        }
        if (isset($_POST ['documentSequenceEnd'])) {
            $this->setDocumentSequenceEnd($this->strict($_POST ['documentSequenceEnd'], 'integer'));
        }
        if (isset($_POST ['documentSequenceDescription'])) {
            $this->setDocumentSequenceDescription($this->strict($_POST ['documentSequenceDescription'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['documentSequenceId'])) {
            $this->setDocumentSequenceId($this->strict($_GET ['documentSequenceId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['applicationId'])) {
            $this->setApplicationId($this->strict($_GET ['applicationId'], 'integer'));
        }
        if (isset($_GET ['moduleId'])) {
            $this->setModuleId($this->strict($_GET ['moduleId'], 'integer'));
        }
        if (isset($_GET ['documentSequenceCode'])) {
            $this->setDocumentSequenceCode($this->strict($_GET ['documentSequenceCode'], 'string'));
        }
        if (isset($_GET ['documentSequenceNumber'])) {
            $this->setDocumentSequenceNumber($this->strict($_GET ['documentSequenceNumber'], 'integer'));
        }
        if (isset($_GET ['documentSequenceStart'])) {
            $this->setDocumentSequenceStart($this->strict($_GET ['documentSequenceStart'], 'integer'));
        }
        if (isset($_GET ['documentSequenceEnd'])) {
            $this->setDocumentSequenceEnd($this->strict($_GET ['documentSequenceEnd'], 'integer'));
        }
        if (isset($_GET ['documentSequenceDescription'])) {
            $this->setDocumentSequenceDescription($this->strict($_GET ['documentSequenceDescription'], 'string'));
        }
        if (isset($_GET ['documentSequenceId'])) {
            $this->setTotal(count($_GET ['documentSequenceId']));
            if (is_array($_GET ['documentSequenceId'])) {
                $this->documentSequenceId = array();
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
            if (isset($_GET ['documentSequenceId'])) {
                $this->setDocumentSequenceId($this->strict($_GET ['documentSequenceId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getDocumentSequenceId($i, 'array') . ",";
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
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setDocumentSequenceId($value, $key, $type) {
        if ($type == 'single') {
            $this->documentSequenceId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->documentSequenceId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setDocumentSequenceId?"
                        )
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
    public function getDocumentSequenceId($key, $type) {
        if ($type == 'single') {
            return $this->documentSequenceId;
        } else {
            if ($type == 'array') {
                return $this->documentSequenceId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getDocumentSequenceId ?"
                        )
                );
                exit();
            }
        }
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
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
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
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
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
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setModuleId($moduleId) {
        $this->moduleId = $moduleId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $documentSequenceCode
     */
    public function getDocumentSequenceCode() {
        return $this->documentSequenceCode;
    }

    /**
     * To Set Code
     * @param string $documentSequenceCode Code
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setDocumentSequenceCode($documentSequenceCode) {
        $this->documentSequenceCode = $documentSequenceCode;
        return $this;
    }

    /**
     * To Return Sequence
     * @return int $documentSequenceNumber
     */
    public function getDocumentSequenceNumber() {
        return $this->documentSequenceNumber;
    }

    /**
     * To Set Sequence
     * @param int $documentSequenceNumber Number
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setDocumentSequenceNumber($documentSequenceNumber) {
        $this->documentSequenceNumber = $documentSequenceNumber;
        return $this;
    }

    /**
     * To Return Start
     * @return int $documentSequenceStart
     */
    public function getDocumentSequenceStart() {
        return $this->documentSequenceStart;
    }

    /**
     * To Set Start
     * @param int $documentSequenceStart Start
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setDocumentSequenceStart($documentSequenceStart) {
        $this->documentSequenceStart = $documentSequenceStart;
        return $this;
    }

    /**
     * To Return End
     * @return int $documentSequenceEnd
     */
    public function getDocumentSequenceEnd() {
        return $this->documentSequenceEnd;
    }

    /**
     * To Set End
     * @param int $documentSequenceEnd End
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setDocumentSequenceEnd($documentSequenceEnd) {
        $this->documentSequenceEnd = $documentSequenceEnd;
        return $this;
    }

    /**
     * To Return Description
     * @return string $documentSequenceDescription
     */
    public function getDocumentSequenceDescription() {
        return $this->documentSequenceDescription;
    }

    /**
     * To Set Description
     * @param string $documentSequenceDescription Description
     * @return \Core\System\Document\DocumentSequence\Model\DocumentSequenceModel
     */
    public function setDocumentSequenceDescription($documentSequenceDescription) {
        $this->documentSequenceDescription = $documentSequenceDescription;
        return $this;
    }

}

?>