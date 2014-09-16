<?php

namespace Core\System\Document\Document\Model;

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
 * Class Document
 * This is Document Model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Document\Document\Model;
 * @subpackage Document
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DocumentModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $documentId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Category
     * @var int
     */
    private $documentCategoryId;

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
     * Title
     * @var string
     */
    private $documentTitle;

    /**
     * Description
     * @var string
     */
    private $documentDescription;

    /**
     * Path
     * @var string
     */
    private $documentPath;

    /**
     * Filename
     * @var string
     */
    private $documentFilename;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('document');
        $this->setPrimaryKeyName('documentId');
        $this->setMasterForeignKeyName('documentId');
        $this->setFilterCharacter('documentDescription');
        //$this->setFilterCharacter('documentNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['documentId'])) {
            $this->setDocumentId($this->strict($_POST ['documentId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'integer'));
        }
        if (isset($_POST ['documentCategoryId'])) {
            $this->setDocumentCategoryId($this->strict($_POST ['documentCategoryId'], 'integer'));
        }
        if (isset($_POST ['applicationId'])) {
            $this->setApplicationId($this->strict($_POST ['applicationId'], 'integer'));
        }
        if (isset($_POST ['moduleId'])) {
            $this->setModuleId($this->strict($_POST ['moduleId'], 'integer'));
        }
        if (isset($_POST ['folderId'])) {
            $this->setFolderId($this->strict($_POST ['folderId'], 'integer'));
        }
        if (isset($_POST ['leafId'])) {
            $this->setLeafId($this->strict($_POST ['leafId'], 'integer'));
        }
        if (isset($_POST ['documentTitle'])) {
            $this->setDocumentTitle($this->strict($_POST ['documentTitle'], 'string'));
        }
        if (isset($_POST ['documentDescription'])) {
            $this->setDocumentDescription($this->strict($_POST ['documentDescription'], 'string'));
        }
        if (isset($_POST ['documentPath'])) {
            $this->setDocumentPath($this->strict($_POST ['documentPath'], 'string'));
        }
        if (isset($_POST ['documentFilename'])) {
            $this->setDocumentFilename($this->strict($_POST ['documentFilename'], 'string'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['documentId'])) {
            $this->setDocumentId($this->strict($_GET ['documentId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'integer'));
        }
        if (isset($_GET ['documentCategoryId'])) {
            $this->setDocumentCategoryId($this->strict($_GET ['documentCategoryId'], 'integer'));
        }
        if (isset($_GET ['applicationId'])) {
            $this->setApplicationId($this->strict($_GET ['applicationId'], 'integer'));
        }
        if (isset($_GET ['moduleId'])) {
            $this->setModuleId($this->strict($_GET ['moduleId'], 'integer'));
        }
        if (isset($_GET ['folderId'])) {
            $this->setFolderId($this->strict($_GET ['folderId'], 'integer'));
        }
        if (isset($_GET ['leafId'])) {
            $this->setLeafId($this->strict($_GET ['leafId'], 'integer'));
        }
        if (isset($_GET ['documentTitle'])) {
            $this->setDocumentTitle($this->strict($_GET ['documentTitle'], 'string'));
        }
        if (isset($_GET ['documentDescription'])) {
            $this->setDocumentDescription($this->strict($_GET ['documentDescription'], 'string'));
        }
        if (isset($_GET ['documentPath'])) {
            $this->setDocumentPath($this->strict($_GET ['documentPath'], 'string'));
        }
        if (isset($_GET ['documentFilename'])) {
            $this->setDocumentFilename($this->strict($_GET ['documentFilename'], 'string'));
        }
        if (isset($_GET ['documentId'])) {
            $this->setTotal(count($_GET ['documentId']));
            if (is_array($_GET ['documentId'])) {
                $this->documentId = array();
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
            if (isset($_GET ['documentId'])) {
                $this->setDocumentId($this->strict($_GET ['documentId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getDocumentId($i, 'array') . ",";
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
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setDocumentId($value, $key, $type) {
        if ($type == 'single') {
            $this->documentId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->documentId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setDocumentId?")
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
    public function getDocumentId($key, $type) {
        if ($type == 'single') {
            return $this->documentId;
        } else {
            if ($type == 'array') {
                return $this->documentId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getDocumentId ?")
                );
                exit();
            }
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
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Category
     * @return int $documentCategoryId
     */
    public function getDocumentCategoryId() {
        return $this->documentCategoryId;
    }

    /**
     * To Set Category
     * @param int $documentCategoryId Category
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setDocumentCategoryId($documentCategoryId) {
        $this->documentCategoryId = $documentCategoryId;
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
     * @return \Core\System\Document\Document\Model\DocumentModel
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
     * @return \Core\System\Document\Document\Model\DocumentModel
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
     * @return \Core\System\Document\Document\Model\DocumentModel
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
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setLeafId($leafId) {
        $this->leafId = $leafId;
        return $this;
    }

    /**
     * To Return Title
     * @return string $documentTitle
     */
    public function getDocumentTitle() {
        return $this->documentTitle;
    }

    /**
     * To Set Title
     * @param string $documentTitle Title
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setDocumentTitle($documentTitle) {
        $this->documentTitle = $documentTitle;
        return $this;
    }

    /**
     * To Return Description
     * @return string $documentDescription
     */
    public function getDocumentDescription() {
        return $this->documentDescription;
    }

    /**
     * To Set Description
     * @param string $documentDescription Description
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setDocumentDescription($documentDescription) {
        $this->documentDescription = $documentDescription;
        return $this;
    }

    /**
     * To Return Path
     * @return string $documentPath
     */
    public function getDocumentPath() {
        return $this->documentPath;
    }

    /**
     * To Set Path
     * @param string $documentPath Path
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setDocumentPath($documentPath) {
        $this->documentPath = $documentPath;
        return $this;
    }

    /**
     * To Return Filename
     * @return string $documentFilename
     */
    public function getDocumentFilename() {
        return $this->documentFilename;
    }

    /**
     * To Set Filename
     * @param string $documentFilename Filename
     * @return \Core\System\Document\Document\Model\DocumentModel
     */
    public function setDocumentFilename($documentFilename) {
        $this->documentFilename = $documentFilename;
        return $this;
    }

}

?>