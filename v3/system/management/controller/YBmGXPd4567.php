<?php

namespace Core\System\Management\Staff\Controller;

use Core\ConfigClass;
use Core\shared\SharedClass;

if (!isset($_SESSION)) {
    session_start();
}
// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classRecordSet.php");
require_once($newFakeDocumentRoot . "library/class/classDate.php");
require_once($newFakeDocumentRoot . "library/class/classDocumentTrail.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
require_once($newFakeDocumentRoot . "v3/system/management/model/staffModel.php");
require_once($newFakeDocumentRoot . "v3/system/management/service/staffService.php");

/**
 * This abstract class contain link to new registration/forget password.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package System
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PortalStaffClass extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Php Word Generate Microsoft Excel 2007 Output.Format : docxs
     * @var \PHPWord
     */
    //private $word; 
    /**
     * Php Excel Generate Microsoft Excel 2007 Output.Format : xlsx/pdf
     * @var \PHPExcel
     */
    private $excel;

    /**
     * Php Powerpoint Generate Microsoft Excel 2007 Output.Format : xlsx
     * @var \PHPPowerPoint
     */
    //private $powerPoint; 
    /**
     * Record Pagination
     * @var \Core\RecordSet\RecordSet
     */
    private $recordSet;

    /**
     * Document Trail Audit.
     * @var \Core\Document\Trail\DocumentTrailClass
     */
    private $documentTrail;

    /**
     * Model
     * @var \Core\System\Management\Staff\Model\StaffModel
     */
    public $model;

    /**
     * Service-Business Application Process or other ajax request
     * @var \Core\System\Management\Staff\Service\StaffService
     */
    public $service;

    /**
     * Translation
     * @var string
     */
    public $t;

    /**
     * Translation Array
     * @var string
     */
    public $translate;

    /**
     * System Format
     * @var bool
     */
    public $systemFormat;

    /**
     * System Format Array
     * @var array
     */
    public $systemFormatArray;

    /**
     * Unverified Company Set As Demo Company.Assign Upon Approval only
     */
    const UNVERIFIED_COMPANY = 5;

    /**
     * Constructor
     */
    function __construct() {
        $this->translate = array();
        $this->setViewPath("./v3/system/management/view/staff.php");
        $this->setControllerPath("./v3/system/management/controller/staffController.php");
        $this->setServicePath("./v3/system/management/service/staffService.php");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->setAudit(0);
        $this->setLog(1);
        $this->model = new \Core\System\Management\Staff\Model\StaffModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
        if ($this->getVendor() == self::MYSQL) {
            $this->q = new \Core\Database\Mysql\Vendor();
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $this->q = new \Core\Database\Mssql\Vendor();
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $this->q = new \Core\Database\Oracle\Vendor();
                }
            }
        }
        $this->setVendor($this->getVendor());
        // $this->q->setApplicationId($this->getApplicationId()); 
        // $this->q->setModuleId($this->getModuleId()); 
        //$this->q->setFolderId($this->getFolderId());
        $this->q->setLeafId($this->getLeafId());
        $this->q->connect($this->getConnection(), $this->getUsername(), $this->getDatabase(), $this->getPassword());

        $this->service = new \Core\System\Management\Staff\Service\StaffService();
        $this->service->q = $this->q;
        $this->service->setVendor($this->getVendor());
        $this->service->setServiceOutput($this->getServiceOutput());

        $this->service->execute();

        $translator = new SharedClass();
        $translator->setCurrentTable($this->model->getTableName());
        $translator->setLeafId($this->getLeafId());
        $translator->execute();
        $this->translate = $translator->getLeafTranslation(); // short because code too long  
        $arrayInfo = $translator->getFileInfo();
        $applicationNative = $arrayInfo['applicationNative'];
        $folderNative = $arrayInfo['folderNative'];
        $moduleNative = $arrayInfo['moduleNative'];
        $leafNative = $arrayInfo['leafNative'];
    }

    /**
     * Verify User
     * @return User
     */
    public function getVerifyUser() {
        $this->service->getVerifyUser($this->model->getStaffId(), $this->model->getVerificationCode());
    }

    /**
     * Resend Forgeted Password
     * @return void
     */
    public function getResendPassword() {
        $this->service->getResendPassword($this->model->getStaffName(), $this->model->getStaffEmail(), $this->model->getSecurityTokenWeb());
    }

    /**
     * Set Unsubscribe / Delete User
     * Using HASH to prevent hacker to changed the variable
     * @return void
     */
    public function setUnSubscribe() {
        $this->service->setUnSubscribe($this->model->getStaffEmail(), $this->model->setUnsubscribeHash());
    }

    /**
     * Create
     * @see config::create()
     * @return void
     */
    public function create() {
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $this->q->start();
        $this->model->setCompanyId(self::UNVERIFIED_COMPANY);
        $this->model->create();
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `staff` 
            (
                 `roleId`,
                 `companyId`,
                 `languageId`,
                 `themeId`,
                 `staffPassword`,
                 `staffName`,
                 `staffEmail`,
                 `staffAvatar`,
                 `isDefault`,
                 `isNew`,
                 `isDraft`,
                 `isUpdate`,
                 `isDelete`,
                 `isActive`,
                 `isApproved`,
                 `isReview`,
                 `isPost`,
                 `executeBy`,
                 `executeTime`
            ) VALUES ( 
                 '" . $this->model->getRoleId() . "',
                 '" . $this->model->getCompanyId() . "',
                 '" . $this->model->getLanguageId() . "',
                 '" . $this->model->getThemeId() . "',
                 '" . $this->model->getStaffPassword() . "',
                 '" . $this->model->getStaffName() . "',
                 '" . $this->model->getStaffEmail() . "',
                 '" . $this->model->getStaffAvatar() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [staff]
            (
                 [staffId],
                 [roleId],
                 [companyId],
                 [languageId],
                 [themeId],
                 [staffPassword],
                 [staffName],
                 [staffEmail],
                 [staffAvatar],
                 [isDefault],
                 [isNew],
                 [isDraft],
                 [isUpdate],
                 [isDelete],
                 [isActive],
                 [isApproved],
                 [isReview],
                 [isPost],
                 [executeBy],
                 [executeTime]
) VALUES (
                 '" . $this->model->getRoleId() . "',
                 '" . $this->model->getCompanyId() . "',
                 '" . $this->model->getLanguageId() . "',
                 '" . $this->model->getThemeId() . "',
                 '" . $this->model->getStaffPassword() . "',
                 '" . $this->model->getStaffName() . "',
                 '" . $this->model->getStaffEmail() . "',
                 '" . $this->model->getStaffAvatar() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO STAFF
            (
                 ROLEID,
                 COMPANYID,
                 LANGUAGEID,
                 THEMEID,
                 STAFFPASSWORD,
                 STAFFNAME,
                 STAFFEMAIL,
                 STAFFAVATAR,
                 ISDEFAULT,
                 ISNEW,
                 ISDRAFT,
                 ISUPDATE,
                 ISDELETE,
                 ISACTIVE,
                 ISAPPROVED,
                 ISREVIEW,
                 ISPOST,
                 EXECUTEBY,
                 EXECUTETIME
            ) VALUES (
                 '" . $this->model->getRoleId() . "',
                 '" . $this->model->getCompanyId() . "',
                 '" . $this->model->getLanguageId() . "',
                 '" . $this->model->getThemeId() . "',
                 '" . $this->model->getStaffPassword() . "',
                 '" . $this->model->getStaffName() . "',
                 '" . $this->model->getStaffEmail() . "',
                 '" . $this->model->getStaffAvatar() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
                }
            }
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $staffId = $this->q->lastInsertId();
        $this->service->registerBusinessPartners($staffId, $this->model->getStaffEmail());
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "time" => $time
                )
        );
        exit();
    }

    /**
     * Read
     * @see config::read()
     * @return void
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     * @return void
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     * @return void
     */
    public function delete() {
        
    }

    /**
     * Excel
     * @see config::delete()
     * @return void
     */
    public function excel() {
        
    }

}

if (isset($_GET['securityToken'])) {
    $portalStaffObject = new PortalStaffClass();
    if ($_GET['securityToken'] != $portalStaffObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    $portalStaffObject->execute();
}
if (isset($_POST['securityToken'])) {
    $portalStaffObject = new PortalStaffClass();
    if ($_POST['securityToken'] != $portalStaffObject->getSecurityToken()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array("success" => false, "message" => "Something wrong with the system.Hola hackers"));
        exit();
    }
    $portalStaffObject->execute();
    if (isset($_POST['method'])) {
        if ($_POST['method'] == 'register') {
            $portalStaffObject->create();
        }
        if ($_POST['method'] == 'resendPassword') {
            $portalStaffObject->getResendPassword();
        }
        if ($_POST['method'] == 'verifyUser') {
            $portalStaffObject->getVerifyUser();
        }
        if ($_POST['method'] == 'unSubscribe') {
            $portalStaffObject->setUnSubscribe();
        }
    }
}
?>