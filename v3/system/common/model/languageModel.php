<?php

namespace Core\System\Common\Language\Model;

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
 * Class Language
 * This is language model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\System\Common\Language\Model;
 * @subpackage Common
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LanguageModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $languageId;

    /**
     * Company
     * @var int
     */
    private $companyId;

    /**
     * Code
     * @var string
     */
    private $languageCode;

    /**
     * Description
     * @var string
     */
    private $languageDescription;

    /**
     * Icon
     * @var string
     */
    private $languageIcon;

    /**
     * Is Google
     * @var bool
     */
    private $isGoogle;

    /**
     * Is Bing
     * @var bool
     */
    private $isBing;

    /**
     * Is Lite
     * @var bool
     */
    private $isSpeakLite;

    /**
     * Is Services
     * @var bool
     */
    private $isWebServices;

    /**
     * Is Important
     * @var bool
     */
    private $isImportant;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('language');
        $this->setPrimaryKeyName('languageId');
        $this->setMasterForeignKeyName('languageId');
        $this->setFilterCharacter('languageDescription');
        //$this->setFilterCharacter('languageNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['languageId'])) {
            $this->setLanguageId($this->strict($_POST ['languageId'], 'int'), 0, 'single');
        }
        if (isset($_POST ['companyId'])) {
            $this->setCompanyId($this->strict($_POST ['companyId'], 'int'));
        }
        if (isset($_POST ['languageCode'])) {
            $this->setLanguageCode($this->strict($_POST ['languageCode'], 'string'));
        }
        if (isset($_POST ['languageDescription'])) {
            $this->setLanguageDescription($this->strict($_POST ['languageDescription'], 'string'));
        }
        if (isset($_POST ['languageIcon'])) {
            $this->setLanguageIcon($this->strict($_POST ['languageIcon'], 'string'));
        }
        if (isset($_POST ['isGoogle'])) {
            $this->setIsGoogle($this->strict($_POST ['isGoogle'], 'bool'));
        }
        if (isset($_POST ['isBing'])) {
            $this->setIsBing($this->strict($_POST ['isBing'], 'bool'));
        }
        if (isset($_POST ['isSpeakLite'])) {
            $this->setIsSpeakLite($this->strict($_POST ['isSpeakLite'], 'bool'));
        }
        if (isset($_POST ['isWebServices'])) {
            $this->setIsWebServices($this->strict($_POST ['isWebServices'], 'bool'));
        }
        if (isset($_POST ['isImportant'])) {
            $this->setIsImportant($this->strict($_POST ['isImportant'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['languageId'])) {
            $this->setLanguageId($this->strict($_GET ['languageId'], 'int'), 0, 'single');
        }
        if (isset($_GET ['companyId'])) {
            $this->setCompanyId($this->strict($_GET ['companyId'], 'int'));
        }
        if (isset($_GET ['languageCode'])) {
            $this->setLanguageCode($this->strict($_GET ['languageCode'], 'string'));
        }
        if (isset($_GET ['languageDescription'])) {
            $this->setLanguageDescription($this->strict($_GET ['languageDescription'], 'string'));
        }
        if (isset($_GET ['languageIcon'])) {
            $this->setLanguageIcon($this->strict($_GET ['languageIcon'], 'string'));
        }
        if (isset($_GET ['isGoogle'])) {
            $this->setIsGoogle($this->strict($_GET ['isGoogle'], 'bool'));
        }
        if (isset($_GET ['isBing'])) {
            $this->setIsBing($this->strict($_GET ['isBing'], 'bool'));
        }
        if (isset($_GET ['isSpeakLite'])) {
            $this->setIsSpeakLite($this->strict($_GET ['isSpeakLite'], 'bool'));
        }
        if (isset($_GET ['isWebServices'])) {
            $this->setIsWebServices($this->strict($_GET ['isWebServices'], 'bool'));
        }
        if (isset($_GET ['isImportant'])) {
            $this->setIsImportant($this->strict($_GET ['isImportant'], 'bool'));
        }
        if (isset($_GET ['languageId'])) {
            $this->setTotal(count($_GET ['languageId']));
            if (is_array($_GET ['languageId'])) {
                $this->languageId = array();
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
            if (isset($_GET ['languageId'])) {
                $this->setLanguageId($this->strict($_GET ['languageId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getLanguageId($i, 'array') . ",";
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
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setLanguageId($value, $key, $type) {
        if ($type == 'single') {
            $this->languageId = $value;
            return $this;
        } else {
            if ($type == 'array') {
                $this->languageId[$key] = $value;
                return $this;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setLanguageId?")
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
    public function getLanguageId($key, $type) {
        if ($type == 'single') {
            return $this->languageId;
        } else {
            if ($type == 'array') {
                return $this->languageId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getLanguageId ?")
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
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setCompanyId($companyId) {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * To Return Code
     * @return string $languageCode
     */
    public function getLanguageCode() {
        return $this->languageCode;
    }

    /**
     * To Set Code
     * @param string $languageCode Code
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setLanguageCode($languageCode) {
        $this->languageCode = $languageCode;
        return $this;
    }

    /**
     * To Return Description
     * @return string $languageDescription
     */
    public function getLanguageDescription() {
        return $this->languageDescription;
    }

    /**
     * To Set Description
     * @param string $languageDescription Description
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setLanguageDescription($languageDescription) {
        $this->languageDescription = $languageDescription;
        return $this;
    }

    /**
     * To Return Icon
     * @return string $languageIcon
     */
    public function getLanguageIcon() {
        return $this->languageIcon;
    }

    /**
     * To Set Icon
     * @param string $languageIcon Icon
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setLanguageIcon($languageIcon) {
        $this->languageIcon = $languageIcon;
        return $this;
    }

    /**
     * To Return Is Google
     * @return bool $isGoogle
     */
    public function getIsGoogle() {
        return $this->isGoogle;
    }

    /**
     * To Set Is Google
     * @param bool $isGoogle Is Google
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setIsGoogle($isGoogle) {
        $this->isGoogle = $isGoogle;
        return $this;
    }

    /**
     * To Return Is Bing
     * @return bool $isBing
     */
    public function getIsBing() {
        return $this->isBing;
    }

    /**
     * To Set Is Bing
     * @param bool $isBing Is Bing
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setIsBing($isBing) {
        $this->isBing = $isBing;
        return $this;
    }

    /**
     * To Return Is SpeakLite
     * @return bool $isSpeakLite
     */
    public function getIsSpeakLite() {
        return $this->isSpeakLite;
    }

    /**
     * To Set Is SpeakLite
     * @param bool $isSpeakLite Is Lite
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setIsSpeakLite($isSpeakLite) {
        $this->isSpeakLite = $isSpeakLite;
        return $this;
    }

    /**
     * To Return Is WebServices
     * @return bool $isWebServices
     */
    public function getIsWebServices() {
        return $this->isWebServices;
    }

    /**
     * To Set Is WebServices
     * @param bool $isWebServices Is Services
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setIsWebServices($isWebServices) {
        $this->isWebServices = $isWebServices;
        return $this;
    }

    /**
     * To Return Is Important
     * @return bool $isImportant
     */
    public function getIsImportant() {
        return $this->isImportant;
    }

    /**
     * To Set Is Important
     * @param bool $isImportant Is Important
     * @return \Core\System\Common\Language\Model\LanguageModel
     */
    public function setIsImportant($isImportant) {
        $this->isImportant = $isImportant;
        return $this;
    }

}

?>