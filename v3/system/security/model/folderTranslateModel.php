<?php

namespace Core\System\Security\FolderTranslate\Model;

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
 * Class FolderTranslateModel
 * this is foldertranslate model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\FolderTranslate\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FolderTranslateModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $folderTranslateId;

    /**
     * Application Primary Key (** For Filtering Only)
     * @var int
     */
    private $applicationId;

    /**
     * Module   Primary Key (** For Filtering  Only)
     * @var  int
     */
    private $moduleId;

    /**
     * folderId
     * @var int
     */
    private $folderId;

    /**
     * languageId
     * @var int
     */
    private $languageId;

    /**
     * folderNative
     * @var string
     */
    private $folderNative;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('folderTranslate');
        $this->setPrimaryKeyName('folderTranslateId');
        $this->setMasterForeignKeyName('folderTranslateId');
        $this->setFilterCharacter('folderTranslateDescription');
        //$this->setFilterCharacter('folderTranslateNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['folderTranslateId'])) {
            $this->setFolderTranslateId($this->strict($_POST ['folderTranslateId'], 'integer'), 0, 'single');
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
        if (isset($_POST ['languageId'])) {
            $this->setLanguageId($this->strict($_POST ['languageId'], 'integer'));
        }
        if (isset($_POST ['folderNative'])) {
            $this->setFolderNative($this->strict($_POST ['folderNative'], 'text'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['folderTranslateId'])) {
            $this->setFolderTranslateId($this->strict($_GET ['folderTranslateId'], 'integer'), 0, 'single');
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
        if (isset($_GET ['languageId'])) {
            $this->setLanguageId($this->strict($_GET ['languageId'], 'integer'));
        }
        if (isset($_GET ['folderNative'])) {
            $this->setFolderNative($this->strict($_GET ['folderNative'], 'text'));
        }
        if (isset($_GET ['folderTranslateId'])) {
            $this->setTotal(count($_GET ['folderTranslateId']));
            if (is_array($_GET ['folderTranslateId'])) {
                $this->folderTranslateId = array();
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
            if (isset($_GET ['folderTranslateId'])) {
                $this->setFolderTranslateId($this->strict($_GET ['folderTranslateId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getFolderTranslateId($i, 'array') . ",";
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
     * Create Setting
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
     * Update Setting
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
     * Delete Setting
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
     * Draft Setting
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
     * Approved Setting
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
     * Review Setting
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
     * Post Setting
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
     * Set Folder Translate Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public function setFolderTranslateId($value, $key, $type) {
        if ($type == 'single') {
            $this->folderTranslateId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->folderTranslateId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setFolderTranslateId?")
                );
                exit();
            }
        }
    }

    /**
     * Return Folder Translate Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     * */
    public function getFolderTranslateId($key, $type) {
        if ($type == 'single') {
            return $this->folderTranslateId;
        } else {
            if ($type == 'array') {
                return $this->folderTranslateId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getFolderTranslateId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Set Appliction Primary Key
     * @param  int $value Application Primary Key
     * @return \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Return Application Primary Key
     * @return int $applicationId Application Primary Key
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set Module Primary Key Value
     * @param  int $value Prumary Key
     * @return \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Return Module Primary Key
     * @return int $moduleId Module Primary Key
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * To Return Folder Primary Key
     * @return int $folderId folder Primary Key
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * To Set Folder Primary Key
     * @param int $folderId Folder Primary Key
     * @return \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public function setFolderId($folderId) {
        $this->folderId = $folderId;
        return $this;
    }

    /**
     * To Return Language Primary Key
     * @return int $languageId Language Primary Key
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    /**
     * To Set Language Primary Key
     * @param int $languageId Language Primary Key
     * @return \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * To Return Native Translation
     * @return string $folderNative Native Translation
     */
    public function getFolderNative() {
        return $this->folderNative;
    }

    /**
     * To Set Native Translation
     * @param string $folderNative Native Translation
     * @return \Core\System\Security\FolderTranslate\Model\FolderTranslateModel
     */
    public function setFolderNative($folderNative) {
        $this->folderNative = $folderNative;
        return $this;
    }

}

?>