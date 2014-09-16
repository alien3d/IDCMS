<?php

namespace Core\Portal\Main\StaffWebAccess\Model;

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
 * Class StaffWebAccessModel
 * this is document model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\StaffWebAccess\Model
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class StaffWebAccessModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $staffWebAccessId;

    /**
     * Staff
     * @var int
     */
    private $staffId;

    /**
     * Log In
     * @var string
     */
    private $staffWebAccessLogIn;

    /**
     * Log out
     * @var string
     */
    private $staffWebAccessLogOut;

    /**
     * Session
     * @var string
     */
    private $phpSession;

    /**
     * Web Browser Type
     * @var string
     */
    private $ua_type;

    /**
     * Web Browser Family
     * @var string
     */
    private $ua_family;

    /**
     * Web Browser Name
     * @var string
     */
    private $ua_name;

    /**
     * Web Browser Version
     * @var string
     */
    private $ua_version;

    /**
     * Web Browser url
     * @var string
     */
    private $ua_url;

    /**
     * Web Browser Company
     * @var string
     */
    private $ua_company;

    /**
     * Web Browser Company Url
     * @var string
     */
    private $ua_company_url;

    /**
     * Web Browser Icon
     * @var string
     */
    private $ua_icon;

    /**
     * Web Browser Info Url
     * @var string
     */
    private $ua_info_url;

    /**
     * Operating System Family
     * @var string
     */
    private $os_family;

    /**
     * Operating System Name
     * @var string
     */
    private $os_name;

    /**
     * Operating System Url
     * @var string
     */
    private $os_url;

    /**
     * Operating System Company
     * @var string
     */
    private $os_company;

    /**
     * Operating System Company Url
     * @var string
     */
    private $os_company_url;

    /**
     * Operating System Icon
     * @var string
     */
    private $os_icon;

    /**
     * Internet Protocol V4
     * @var string
     */
    private $ip_v4;

    /**
     * Internet Protocol V6
     * @var string
     */
    private $ip_v6;

    /**
     * Internet Protocol Country Code
     * @var string
     */
    private $ip_country_code;

    /**
     * Internet Protocol Country Name
     * @var string
     */
    private $ip_country_name;

    /**
     * Internet Protocol Region Name
     * @var string
     */
    private $ip_region_name;

    /**
     * Internet Protocol Latitude
     * @var string
     */
    private $ip_latitude;

    /**
     * Internet Protocol Longtitude
     * @var string
     */
    private $ip_longtitude;

    /**
     * Class Loader to load outside variable and test it suppose variable type
     */
    function execute() {
        /*
         *  Basic Information Table
         */
        $this->setTableName('staffWebAcess');
        $this->setPrimaryKeyName('staffWebAcessId');
        $this->setPhpSession(session_id());
        $this->setFilterDate('staffWebAccessLogIn');
        /**
         * All the $_POST Environment.
         */
        if (isset($_POST ['staffWebAccessId'])) {
            $this->setStaffWebAccessId($this->strict($_POST ['staffWebAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['staffId'])) {
            $this->setStaffId($this->strict($_POST ['staffId'], 'integer'));
        }
        if (isset($_POST ['staffWebAccessLogIn'])) {
            $this->setStaffWebAccessLogIn($this->strict($_POST ['staffWebAccessLogIn'], 'datetime'));
        }
        if (isset($_POST ['staffWebAccessLogOut'])) {
            $this->setStaffWebAccessLogOut($this->strict($_POST ['staffWebAccessLogOut'], 'datetime'));
        }
        if (isset($_POST ['phpSession'])) {
            $this->setPhpSession($this->strict($_POST ['phpSession'], 'string'));
        }
        if (isset($_POST ['ua_type'])) {
            $this->setUa_type($this->strict($_POST ['ua_type'], 'string'));
        }
        if (isset($_POST ['ua_family'])) {
            $this->setUa_family($this->strict($_POST ['ua_family'], 'string'));
        }
        if (isset($_POST ['ua_name'])) {
            $this->setUa_name($this->strict($_POST ['ua_name'], 'string'));
        }
        if (isset($_POST ['ua_version'])) {
            $this->setUa_version($this->strict($_POST ['ua_version'], 'string'));
        }
        if (isset($_POST ['ua_url'])) {
            $this->setUa_url($this->strict($_POST ['ua_url'], 'string'));
        }
        if (isset($_POST ['ua_company'])) {
            $this->setUa_company($this->strict($_POST ['ua_company'], 'string'));
        }
        if (isset($_POST ['ua_company_url'])) {
            $this->setUa_company_url($this->strict($_POST ['ua_company_url'], 'string'));
        }
        if (isset($_POST ['ua_icon'])) {
            $this->setUa_icon($this->strict($_POST ['ua_icon'], 'string'));
        }
        if (isset($_POST ['ua_info_url'])) {
            $this->setUa_info_url($this->strict($_POST ['ua_info_url'], 'string'));
        }
        if (isset($_POST ['os_family'])) {
            $this->setOs_family($this->strict($_POST ['os_family'], 'string'));
        }
        if (isset($_POST ['os_name'])) {
            $this->setOs_name($this->strict($_POST ['os_name'], 'string'));
        }
        if (isset($_POST ['os_url'])) {
            $this->setOs_url($this->strict($_POST ['os_url'], 'string'));
        }
        if (isset($_POST ['os_company'])) {
            $this->setOs_company($this->strict($_POST ['os_company'], 'string'));
        }
        if (isset($_POST ['os_company_url'])) {
            $this->setOs_company_url($this->strict($_POST ['os_company_url'], 'string'));
        }
        if (isset($_POST ['os_icon'])) {
            $this->setOs_icon($this->strict($_POST ['os_icon'], 'string'));
        }
        if (isset($_POST ['ip_v4'])) {
            $this->setIp_v4($this->strict($_POST ['ip_v4'], 'string'));
        }
        if (isset($_POST ['ip_v6'])) {
            $this->setIp_v6($this->strict($_POST ['ip_v6'], 'string'));
        }
        if (isset($_POST ['ip_country_code'])) {
            $this->setIp_country_code($this->strict($_POST ['ip_country_code'], 'string'));
        }
        if (isset($_POST ['ip_country_name'])) {
            $this->setIp_country_name($this->strict($_POST ['ip_country_name'], 'string'));
        }
        if (isset($_POST ['ip_region_name'])) {
            $this->setIp_region_name($this->strict($_POST ['ip_region_name'], 'string'));
        }
        if (isset($_POST ['ip_latitude'])) {
            $this->setIp_latitude($this->strict($_POST ['ip_latitude'], 'string'));
        }
        if (isset($_POST ['ip_longtitude'])) {
            $this->setIp_longtitude($this->strict($_POST ['ip_longtitude'], 'string'));
        }
        /**
         * All the $_GET Environment.
         */
        if (isset($_GET ['staffWebAccessId'])) {
            $this->setStaffWebAccessId($this->strict($_GET ['staffWebAccessId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['staffId'])) {
            $this->setStaffId($this->strict($_GET ['staffId'], 'integer'));
        }
        if (isset($_GET ['staffWebAccessLogIn'])) {
            $this->setStaffWebAccessLogIn($this->strict($_GET ['staffWebAccessLogIn'], 'datetime'));
        }
        if (isset($_GET ['staffWebAccessLogOut'])) {
            $this->setStaffWebAccessLogOut($this->strict($_GET ['staffWebAccessLogOut'], 'datetime'));
        }
        if (isset($_GET ['phpSession'])) {
            $this->setPhpSession($this->strict($_GET ['phpSession'], 'string'));
        }
        if (isset($_GET ['ua_type'])) {
            $this->setUa_type($this->strict($_GET ['ua_type'], 'string'));
        }
        if (isset($_GET ['ua_family'])) {
            $this->setUa_family($this->strict($_GET ['ua_family'], 'string'));
        }
        if (isset($_GET ['ua_name'])) {
            $this->setUa_name($this->strict($_GET ['ua_name'], 'string'));
        }
        if (isset($_GET ['ua_version'])) {
            $this->setUa_version($this->strict($_GET ['ua_version'], 'string'));
        }
        if (isset($_GET ['ua_url'])) {
            $this->setUa_url($this->strict($_GET ['ua_url'], 'string'));
        }
        if (isset($_GET ['ua_company'])) {
            $this->setUa_company($this->strict($_GET ['ua_company'], 'string'));
        }
        if (isset($_GET ['ua_company_url'])) {
            $this->setUa_company_url($this->strict($_GET ['ua_company_url'], 'string'));
        }
        if (isset($_GET ['ua_icon'])) {
            $this->setUa_icon($this->strict($_GET ['ua_icon'], 'string'));
        }
        if (isset($_GET ['ua_info_url'])) {
            $this->setUa_info_url($this->strict($_GET ['ua_info_url'], 'string'));
        }
        if (isset($_GET ['os_family'])) {
            $this->setOs_family($this->strict($_GET ['os_family'], 'string'));
        }
        if (isset($_GET ['os_name'])) {
            $this->setOs_name($this->strict($_GET ['os_name'], 'string'));
        }
        if (isset($_GET ['os_url'])) {
            $this->setOs_url($this->strict($_GET ['os_url'], 'string'));
        }
        if (isset($_GET ['os_company'])) {
            $this->setOs_company($this->strict($_GET ['os_company'], 'string'));
        }
        if (isset($_GET ['os_company_url'])) {
            $this->setOs_company_url($this->strict($_GET ['os_company_url'], 'string'));
        }
        if (isset($_GET ['os_icon'])) {
            $this->setOs_icon($this->strict($_GET ['os_icon'], 'string'));
        }
        if (isset($_GET ['ip_v4'])) {
            $this->setIp_v4($this->strict($_GET ['ip_v4'], 'string'));
        }
        if (isset($_GET ['ip_v6'])) {
            $this->setIp_v6($this->strict($_GET ['ip_v6'], 'string'));
        }
        if (isset($_GET ['ip_country_code'])) {
            $this->setIp_country_code($this->strict($_GET ['ip_country_code'], 'string'));
        }
        if (isset($_GET ['ip_country_name'])) {
            $this->setIp_country_name($this->strict($_GET ['ip_country_name'], 'string'));
        }
        if (isset($_GET ['ip_region_name'])) {
            $this->setIp_region_name($this->strict($_GET ['ip_region_name'], 'string'));
        }
        if (isset($_GET ['ip_latitude'])) {
            $this->setIp_latitude($this->strict($_GET ['ip_latitude'], 'string'));
        }
        if (isset($_GET ['ip_longtitude'])) {
            $this->setIp_longtitude($this->strict($_GET ['ip_longtitude'], 'string'));
        }
        if (isset($_GET ['staffWebAccessId'])) {
            $this->setTotal(count($_GET ['staffWebAccessId']));
            if (is_array($_GET ['staffWebAccessId'])) {
                $this->staffWebAccessId = array();
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
            if (isset($_GET ['staffWebAccessId'])) {
                $this->setStaffWebAccessId($this->strict($_GET ['staffWebAccessId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getStaffWebAccessId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, -1)));
        /**
         * All the $_SESSION Environment.
         */
        if (isset($_SESSION ['staffId'])) {
            $this->setExecuteBy($_SESSION ['staffId']);
        }

        /**
         * TimeStamp Value.
         */
        if ($this->getVendor() == self::MYSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s") . "'");
            $this->setStaffWebAccessLogIn("'" . date("Y-m-d H:i:s") . "'");
            $this->setStaffWebAccessLogOut("'" . date("Y-m-d H:i:s") . "'");
            ;
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
                $this->setStaffWebAccessLogIn("'" . date("Y-m-d H:i:s.u") . "'");
                $this->setStaffWebAccessLogOut("'" . date("Y-m-d H:i:s.u") . "'");
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                    $this->setStaffWebAccessLogIn("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                    $this->setStaffWebAccessLogOut("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS');");
                }
            }
        }
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getStaffWebAccessId($key, $type) {
        if ($type == 'single') {
            return $this->staffWebAccessId;
        } else {
            if ($type == 'array') {
                return $this->staffWebAccessId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getstaffWebAccessId ?")
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
     */
    public function setStaffWebAccessId($value, $key, $type) {
        if ($type == 'single') {
            $this->staffWebAccessId = $value;
        } else {
            if ($type == 'array') {
                $this->staffWebAccessId[$key] = $value;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setstaffWebAccessId?")
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
     * To Return staffId
     * @return string $staffId
     */
    public function getStaffId() {
        return $this->staffId;
    }

    /**
     * To Set staffId
     * @param int $staffId
     */
    public function setStaffId($staffId) {
        $this->staffId = $staffId;
    }

    /**
     * To Return staffWebAccessLogIn
     * @return string $staffWebAccessLogIn
     */
    public function getStaffWebAccessLogIn() {
        return $this->staffWebAccessLogIn;
    }

    /**
     * To Set staffWebAccessLogIn
     * @param string $staffWebAccessLogIn
     */
    public function setStaffWebAccessLogIn($staffWebAccessLogIn) {
        $this->staffWebAccessLogIn = $staffWebAccessLogIn;
    }

    /**
     * To Return staffWebAccessLogOut
     * @return string $staffWebAccessLogOut
     */
    public function getStaffWebAccessLogOut() {
        return $this->staffWebAccessLogOut;
    }

    /**
     * To Set staffWebAccessLogOut
     * @param string $staffWebAccessLogOut
     */
    public function setStaffWebAccessLogOut($staffWebAccessLogOut) {
        $this->staffWebAccessLogOut = $staffWebAccessLogOut;
    }

    /**
     * To Return phpSession
     * @return string $phpSession
     */
    public function getPhpSession() {
        return $this->phpSession;
    }

    /**
     * To Set phpSession
     * @param string $phpSession
     */
    public function setPhpSession($phpSession) {
        $this->phpSession = $phpSession;
    }

    /**
     * To Return Web Browser Family
     * @return string $ua_family
     */
    public function getUa_family() {
        return $this->ua_family;
    }

    /**
     * To Set Web Browser Family
     * @param string $ua_family
     */
    public function setUa_family($ua_family) {
        $this->ua_family = $ua_family;
    }

    /**
     * To Return Web Browser Name
     * @return string $ua_name
     */
    public function getUa_name() {
        return $this->ua_name;
    }

    /**
     * To Set Web Browser Name
     * @param string $ua_name
     */
    public function setUa_name($ua_name) {
        $this->ua_name = $ua_name;
    }

    /**
     * To Return Web Browser Version
     * @return string $ua_version
     */
    public function getUa_version() {
        return $this->ua_version;
    }

    /**
     * To Set Web Browser Version
     * @param string $ua_version
     */
    public function setUa_version($ua_version) {
        $this->ua_version = $ua_version;
    }

    /**
     * To Return Web Browser Url
     * @return string $ua_url
     */
    public function getUa_url() {
        return $this->ua_url;
    }

    /**
     * To Set Web Browser Url
     * @param string $ua_url
     */
    public function setUa_url($ua_url) {
        $this->ua_url = $ua_url;
    }

    /**
     * To Return Web Browser Company
     * @return string $ua_company
     */
    public function getUa_company() {
        return $this->ua_company;
    }

    /**
     * To Set Web Browser Company
     * @param string $ua_company
     */
    public function setUa_company($ua_company) {
        $this->ua_company = $ua_company;
    }

    /**
     * To Return Web Browser Company Url
     * @return string $ua_company_url
     */
    public function getUa_company_url() {
        return $this->ua_company_url;
    }

    /**
     * To Set Web Browser Company Url
     * @param string $ua_company_url
     */
    public function setUa_company_url($ua_company_url) {
        $this->ua_company_url = $ua_company_url;
    }

    /**
     * To Return Web Browser Icon
     * @return string $ua_icon
     */
    public function getUa_icon() {
        return $this->ua_icon;
    }

    /**
     * To Set Web Browser Icon
     * @param string $ua_icon
     */
    public function setUa_icon($ua_icon) {
        $this->ua_icon = $ua_icon;
    }

    /**
     * To Return Web Browser Info Url
     * @return string $ua_info_url
     */
    public function getUa_info_url() {
        return $this->ua_info_url;
    }

    /**
     * To Set Web Browser Info Url
     * @param string $ua_info_url
     */
    public function setUa_info_url($ua_info_url) {
        $this->ua_info_url = $ua_info_url;
    }

    /**
     * To Return Operating System Family
     * @return string $os_family
     */
    public function getOs_family() {
        return $this->os_family;
    }

    /**
     * To Set Operating System Family
     * @param string $os_family
     */
    public function setOs_family($os_family) {
        $this->os_family = $os_family;
    }

    /**
     * To Return Operating System Name
     * @return string $os_name
     */
    public function getOs_name() {
        return $this->os_name;
    }

    /**
     * To Set Operating System Name
     * @param string $os_name
     */
    public function setOs_name($os_name) {
        $this->os_name = $os_name;
    }

    /**
     * To Return Operating System Url
     * @return string$os_url
     */
    public function getOs_url() {
        return $this->os_url;
    }

    /**
     * To Set Operating System Url
     * @param string $os_url
     */
    public function setOs_url($os_url) {
        $this->os_url = $os_url;
    }

    /**
     * To Return Operating System Company
     * @return string $os_company
     */
    public function getOs_company() {
        return $this->os_company;
    }

    /**
     * To Set Operating System Company
     * @param string $os_company
     */
    public function setOs_company($os_company) {
        $this->os_company = $os_company;
    }

    /**
     * To Return Operating System Company Url
     * @return string $os_company_url
     */
    public function getOs_company_url() {
        return $this->os_company_url;
    }

    /**
     * To Set Operating System Company Url
     * @param string $os_company_url
     */
    public function setOs_company_url($os_company_url) {
        $this->os_company_url = $os_company_url;
    }

    /**
     * To Return Operating System Icon
     * @return string $os_icon
     */
    public function getOs_icon() {
        return $this->os_icon;
    }

    /**
     * To Set Operating System Icon
     * @param string $os_icon
     */
    public function setOs_icon($os_icon) {
        $this->os_icon = $os_icon;
    }

    /**
     * To Return Internet Protocol V4
     * @return string $ip_v4
     */
    public function getIp_v4() {
        return $this->ip_v4;
    }

    /**
     * To Set Internet Protocol V4
     * @param string $ip_v4
     */
    public function setIp_v4($ip_v4) {
        $this->ip_v4 = $ip_v4;
    }

    /**
     * To Return Internet Protocol V6
     * @return string $ip_v6
     */
    public function getIp_v6() {
        return $this->ip_v6;
    }

    /**
     * To Set Internet Protocol V6
     * @param string $ip_v6
     */
    public function setIp_v6($ip_v6) {
        $this->ip_v6 = $ip_v6;
    }

    /**
     * To Return Internet Protocol Country Code
     * @return string $ip_country_code
     */
    public function getIp_country_code() {
        return $this->ip_country_code;
    }

    /**
     * To Set Internet Protocol Country Code
     * @param string $ip_country_code
     */
    public function setIp_country_code($ip_country_code) {
        $this->ip_country_code = $ip_country_code;
    }

    /**
     * To Return Internet Protocol Country Name
     * @return string $ip_country_name
     */
    public function getIp_country_name() {
        return $this->ip_country_name;
    }

    /**
     * To Set Internet Protocol Country Name
     * @param string $ip_country_name
     */
    public function setIp_country_name($ip_country_name) {
        $this->ip_country_name = $ip_country_name;
    }

    /**
     * To Return Internet Protocol Region Name
     * @return string $ip_region_name
     */
    public function getIp_region_name() {
        return $this->ip_region_name;
    }

    /**
     * To Set Internet Protocol Region Name
     * @param string $ip_region_name
     */
    public function setIp_region_name($ip_region_name) {
        $this->ip_region_name = $ip_region_name;
    }

    /**
     * To Return Internet Protocol Latitude
     * @return string $ip_latitude
     */
    public function getIp_latitude() {
        return $this->ip_latitude;
    }

    /**
     * To Set Internet Protocol Latitude
     * @param string $ip_latitude
     */
    public function setIp_latitude($ip_latitude) {
        $this->ip_latitude = $ip_latitude;
    }

    /**
     * To Return Internet Protocol Longtitude
     * @return string $ip_longtitude
     */
    public function getIp_longtitude() {
        return $this->ip_longtitude;
    }

    /**
     * To Set Internet Protocol Longtitude
     * @param string $ip_longtitude
     */
    public function setIp_longtitude($ip_longtitude) {
        $this->ip_longtitude = $ip_longtitude;
    }

    /**
     * To Return Web Browser Type
     * @return string $ua_type
     */
    public function getUa_type() {
        return $this->ua_type;
    }

    /**
     * To Set Web Browser Type
     * @param string $ua_type
     */
    public function setUa_type($ua_type) {
        $this->ua_type = $ua_type;
    }

}
