<?php

namespace Core\System\Security\TableMappingTranslate\Model;

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
 * Class TableMappingTranslateModel
 * This is Table Mapping Translate Model file.
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Security\TableMappingTranslate\Model
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TableMappingTranslateModel extends ValidationClass {

    /**
     * tableMappingTranslateId
     * @var int
     */
    private $tableMappingTranslateId;

    /**
     * Database Name
     * @var string
     */
    private $databaseName;

    /**
     * Column Name
     * @var string
     */
    private $tableMappingName;

    /**
     * Table Mapping
     * @var int
     */
    private $tableMappingId;

    /**
     * Native Translation
     * @var string
     */
    private $tableMappingNative;

    /**
     * Language
     * @var int
     */
    private $languageId;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('tablemappingtranslate');
        $this->setPrimaryKeyName('tableMappingTranslateId');
        $this->setMasterForeignKeyName('tableMappingTranslateId');
        $this->setFilterCharacter('tablemappingtranslateDescription');
        //$this->setFilterCharacter('tablemappingtranslateNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['tableMappingTranslateId'])) {
            $this->setTableMappingTranslateId(
                    $this->strict($_POST ['tableMappingTranslateId'], 'integer'), 0, 'single'
            );
        }
        if (isset($_POST['databaseName'])) {
            $this->setDatabaseName($_POST['databaseName']);
        }
        if (isset($_POST['tableMappingName'])) {
            $this->setTableMappingName($_POST['tableMappingName']);
        }
        if (isset($_POST ['tableMappingId'])) {
            $this->setTableMappingId($this->strict($_POST ['tableMappingId'], 'integer'));
        }

        if (isset($_POST ['tableMappingNative'])) {
            $this->setTableMappingNative($this->strict($_POST ['tableMappingNative'], 'text'));
        }
        if (isset($_POST ['languageId'])) {
            $this->setLanguageId($this->strict($_POST ['languageId'], 'integer'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['tableMappingTranslateId'])) {
            $this->setTableMappingTranslateId($this->strict($_GET ['tableMappingTranslateId'], 'integer'), 0, 'single');
        }
        if (isset($_GET['databaseName'])) {
            $this->setDatabaseName($_GET['databaseName']);
        }
        if (isset($_GET['tableMappingName'])) {
            $this->setTableMappingName($_GET['tableMappingName']);
        }
        if (isset($_GET ['tableMappingId'])) {
            $this->setTableMappingId($this->strict($_GET ['tableMappingId'], 'integer'));
        }

        if (isset($_GET ['tableMappingNative'])) {
            $this->setTableMappingNative($this->strict($_GET ['tableMappingNative'], 'text'));
        }
        if (isset($_GET ['languageId'])) {
            $this->setLanguageId($this->strict($_GET ['languageId'], 'integer'));
        }
        if (isset($_GET ['tableMappingTranslateId'])) {
            $this->setTotal(count($_GET ['tableMappingTranslateId']));
            if (is_array($_GET ['tableMappingTranslateId'])) {
                $this->tableMappingTranslateId = array();
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
            if (isset($_GET ['tableMappingTranslateId'])) {
                $this->setTableMappingTranslateId(
                        $this->strict($_GET ['tableMappingTranslateId'] [$i], 'numeric'), $i, 'array'
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
            $primaryKeyAll .= $this->getTableMappingTranslateId($i, 'array') . ",";
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
     * Set Primary Key Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public function setTableMappingTranslateId($value, $key, $type) {
        if ($type == 'single') {
            $this->tableMappingTranslateId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->tableMappingTranslateId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:setTableMappingTranslateId?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * Return tablemappingtranslate  Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     * */
    public function getTableMappingTranslateId($key, $type) {
        if ($type == 'single') {
            return $this->tableMappingTranslateId;
        } else {
            if ($type == 'array') {
                return $this->tableMappingTranslateId [$key];
            } else {
                echo json_encode(
                        array(
                            "success" => false,
                            "message" => "Cannot Identify Type String Or Array:getTableMappingTranslateId ?"
                        )
                );
                exit();
            }
        }
    }

    /**
     * To Return table Mapping Primary Key
     * @return int $tableMappingId Table Mapping Primary Key
     */
    public function getTableMappingId() {
        return $this->tableMappingId;
    }

    /**
     * To Set Table Mapping Primary Key
     * @param int $tableMappingId Table Mapping Primary Key
     * @return \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public function setTableMappingId($tableMappingId) {
        $this->tableMappingId = $tableMappingId;
        return $this;
    }

    /**
     * To Return Native Translation
     * @return string $tableMappingNative Native Translation
     */
    public function getTableMappingNative() {
        return $this->tableMappingNative;
    }

    /**
     * To Set Native Translation
     * @param string $tableMappingNative Native Translation
     * @return \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public function setTableMappingNative($tableMappingNative) {
        $this->tableMappingNative = $tableMappingNative;
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
     * To Set language Primary Key
     * @param int $languageId Language Primary Key
     * @return \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
        return $this;
    }

    /**
     * To Return Database Name
     * @return string $databaseName Database Name
     */
    public function getDatabaseName() {
        return $this->databaseName;
    }

    /**
     * To Set Database name
     * @param string $value Database Name
     * @return \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public function setDatabaseName($value) {
        $this->databaseName = $value;
        return $this;
    }

    /**
     * To Return Table Mapping  Name
     * @return string $tableMappingColumnName
     */
    public function getTableMappingName() {
        return $this->tableMappingName;
    }

    /**
     * To Set Table Name
     * @param string $value Table Name
     * @return \Core\System\Security\TableMappingTranslate\Model\TableMappingTranslateModel
     */
    public function setTableMappingName($value) {
        $this->tableMappingName = $value;
        return $this;
    }

}

?>