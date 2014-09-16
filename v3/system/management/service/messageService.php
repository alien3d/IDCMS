<?php

namespace Core\System\Management\Message\Service;

use Core\ConfigClass;

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
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class Message
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Message\Service;
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class MessageService extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;

    /**
     * Upload Staff Avatar
     * @var int
     */
    private $sizeLimit;

    /**
     * Upload Staff Avatar Type
     * @var string
     */
    private $allowedExtensions;

    /**
     * @var string
     */
    private $uploadPath;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $this->allowedExtensions = array("jpg", "jpeg", "xml", "bmp", "png");
        // max file size in bytes
        $this->setSizeLimit((8 * 1024 * 1024));
        // set upload path
        $this->setUploadPath($this->getFakeDocumentRoot() . "v3/system/management/images/");
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return StaffFrom
     * @return array|string
     */
    public function getStaff() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `staffId`,
                     `staffName`
         FROM        `staff`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [staffId],
                     [staffName]
         FROM        [staff]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      STAFFID AS \"staffId\",
                     STAFFNAME AS \"staffName\"
         FROM        STAFF
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['staffId'] . "'>" . $d . ". " . $row['staffFrom'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
            }
            unset($d);
        }
        if ($this->getServiceOutput() == 'option') {
            if (strlen($str) > 0) {
                $str = "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                $str = "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => true, "message" => "complete", "data" => $str));
            exit();
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Upload Message Attachment
     * @return void
     */
    function setMessageAttachment() {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $qqfile = null;
        $uploader = new \qqFileUploader($this->getAllowedExtensions(), $this->getSizeLimit());
        $result = $uploader->handleUpload($this->getUploadPath());
        if (isset($_GET['qqfile'])) {
            $qqfile = $_GET['qqfile'];
        }
// to pass data through iframe you will need to encode all html tags
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        INSERT INTO `messageuploadtemp`(
             `companyId`,
             `staffId`,
             `leafId`,
             `messageUploadTempName`, 
             `isNew`, 
             `executeBy`, 
             `executeTime`
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            120,
            '" . $this->strict($qqfile, 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
        INSERT INTO [messageUploadTemp](
             [companyId],
             [staffId],
             [leafId],
             [messageUploadTempName],
             [isNew],
             [executeBy],
             [executeTime]
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
           INSERT INTO MESSAGEUPLOADTEMP(
             COMPANYID,
             STAFFID,
             LEAFID,
             MESSAGEUPLOADTEMPNAME,
             ISNEW,
             EXECUTEBY,
             EXECUTETIME
        ) VALUES (
            '" . $this->getCompanyId() . "',
            '" . $this->getStaffId() . "',
            120,
            '" . $this->strict($_GET['qqfile'], 'w') . "',
            1,
            '" . $_SESSION['staffId'] . "',NOW())";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        exit();
    }

    /**
     * Take the last temp file
     * @param int $staffId
     */
    function transferMessage($staffId) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      * 
            FROM        `messageUploadTemp` 
            WHERE       `isNew`=1
            AND         `staffId`='" . $staffId . "'
            ORDER BY    `imageTempId` DESC
            LIMIT        1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      * 
            FROM        [messageUploadTemp] 
            WHERE       [isNew]=1
            AND         [staffId]='" . $staffId . "'
            ORDER BY    [imageTempId] DESC
            LIMIT        1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      IMAGETEMPNAME AS \"imageTempName\"
            FROM        MESSAGEUPLOADTEMP
            WHERE       ISNEW=1
            AND         STAFFID='" . $staffId . "'
            AND         ROWNUM =        1     
            ORDER BY    IMAGETEMPID DESC
            ";
        }
        $result = $this->q->fast($sql);
        if ($result) {
            $row = $this->q->fetchArray($result);
            $sql = "
            UPDATE `staff`
            SET    `staffAvatar`    = '" . $row['imageTempName'] . "'
            WHERE  `staffId`        = '" . $staffId . "'";
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            // update back  the last image file to 0 preventing update the same thing again
            $sql = "
            UPDATE `imageTemp`
            SET    `isNew`    = '0'
            WHERE  `staffId`        = '" . $_SESSION['staffId'] . "'";
            try {
                $this->q->update($sql);
            } catch (\Exception $e) {
                header('Content-Type:application/json; charset=utf-8');
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
    }

    /**
     * Return Allowed Extension
     * @return array|string
     */
    public function getAllowedExtensions() {
        return $this->allowedExtensions;
    }

    /**
     * Set Allowed Extensions
     * @param $value
     * @return \Core\System\Management\Message\Service\MessageService
     */
    public function setAllowedExtensions($value) {
        $this->allowedExtensions = $value;
        return $this;
    }

    /**
     * Return size Limit Of Staff Upload File
     * @return int
     */
    public function getSizeLimit() {
        return $this->sizeLimit;
    }

    /**
     * Set size Limit Of Staff Upload File
     * @param int $value
     * @return \Core\System\Management\Message\Service\MessageService
     */
    public function setSizeLimit($value) {
        $this->sizeLimit = $value;
        return $this;
    }

    /**
     * Return Upload PAth
     * @return string
     */
    public function getUploadPath() {
        return $this->uploadPath;
    }

    /**
     * Set Upload Path
     * @param string $value
     * @return \Core\System\Management\Message\Service\MessageService
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
    }

    /**
     *  Create
     * @see config::create()
     * @return void
     */
    public function create() {
        
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
     * Update
     * @see config::delete()
     * @return void
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     * @return void
     */
    public function excel() {
        
    }

}

/**
 * Class Message
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Management\Message\Service;
 * @subpackage Management
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class MessageStatisticService extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var string
     */
    public $t;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Draft Email
     * @return int $draftEmail Draft Email
     */
    public function getSumDraftEmail() {
        $sql = null;
        $draftEmail = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  `total`
            FROM    `message` 
            WHERE   `isPost` = 0 
            AND     `isDelete`  =   0
            AND     `isActive`  = 1
            AND     `isDraft`   =1
            AND     `staffIdFrom`='" . $this->getStaffId() . "'
            AND     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  [total]
            FROM    [message] 
            WHERE   [isPost] = 0  
            AND     [isDelete]  = 0 
            AND     [isActive] = 1
            AND     [isDraft]=1
            AND     [staffIdFrom]='" . $this->getStaffId() . "'
            AND     [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT COUNT(MESSAGEID) AS  \"total\"
            FROM    MESSAGE 
            WHERE   ISPOST = 0  
            AND     ISDELETE=0
            AND     ISACTIVE = 1
            AND     ISDRAFT=1
            AND     STAFFIDFROM='" . $this->getStaffId() . "'
            AND     COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows();
        if ($total > 0) {
            $row = $this->q->fetchArray($result);
            $draftEmail = intval($row['total'] + 0);
        }
        return $draftEmail;
    }

    /**
     * Send Email
     * @return int $sendEmail Send Email
     */
    public function getSumSendEmail() {
        $sql = null;
        $sendEmail = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  `total`
            FROM    `message` 
            WHERE   `isPost` = 1
            AND     `isActive`  = 1
            AND     `staffIdFrom`='" . $this->getStaffId() . "'
            AND     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  [total]
            FROM    [message]
            WHERE   [isPost] = 1  
            AND     [isActive] = 1
            AND     [staffIdFrom]='" . $this->getStaffId() . "'
            AND     [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT COUNT(MESSAGEID) AS  \"total\"
            FROM    MESSAGE 
            WHERE   ISPOST = 1  
            AND     ISACTIVE = 1
            AND     STAFFIDFROM='" . $this->getStaffId() . "'
            AND     COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows();
        if ($total > 0) {
            $row = $this->q->fetchArray($result);
            $sendEmail = intval($row['total'] + 0);
        }
        return $sendEmail;
    }

    /**
     * Delete Email
     * @return int $deleteEmail Delete Email
     */
    public function getSumDeleteEmail() {
        $sql = null;
        $deleteEmail = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  `total`
            FROM    `message` 
            WHERE   `isDelete` = 1  
            AND     `staffIdTo`='" . $this->getStaffId() . "'
            AND     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  [total]
            FROM    [message] 
            WHERE   [isDelete] = 1 
            AND     [staffIdTo]='" . $this->getStaffId() . "'
            AND     [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT COUNT(MESSAGEID) AS  \"total\"
            FROM    MESSAGE 
            WHERE   ISDELETE = 1 
            AND     STAFFIDTO='" . $this->getStaffId() . "'
            AND     COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows();
        if ($total > 0) {
            $row = $this->q->fetchArray($result);
            $deleteEmail = intval($row['total'] + 0);
        }
        return $deleteEmail;
    }

    /**
     * Return new email which have been not read yet.
     * @return int $newEmail
     */
    public function getSumNewEmail() {
        $sql = null;
        $newEmail = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  `total`
            FROM    `message` 
            WHERE   `isRead` = 0 
            AND     `isActive`  = 1
            AND     `staffIdTo`='" . $this->getStaffId() . "'
            AND     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  [total]
            FROM    [message] 
            WHERE   [isRead] = 0 
            AND     [isActive] = 1
            AND     [staffIdTo]='" . $this->getStaffId() . "'
            AND     [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT COUNT(MESSAGEID) AS  \"total\"
            FROM    MESSAGE 
            WHERE   ISREAD = 0 
            AND     ISACTIVE = 1
            AND     STAFFIDTO='" . $this->getStaffId() . "'
            AND     COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows();
        if ($total > 0) {
            $row = $this->q->fetchArray($result);
            $newEmail = intval($row['total'] + 0);
        }
        return $newEmail;
    }

    /**
     * Return Email which have been read.
     * @return int $readEmail
     */
    public function getSumReadEmail() {
        $sql = null;
        $readEmail = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  `total`
            FROM    `message` 
            WHERE   `isRead` = 1  
            AND     `isActive`  = 1
            AND     `staffIdTo`='" . $this->getStaffId() . "'
            AND     `companyId`='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT COUNT(messageId) AS  [total]
            FROM    [message] 
            WHERE   [isRead] = 1  
            AND     [isActive] = 1
            AND     [staffIdTo]='" . $this->getStaffId() . "'
            AND     [companyId]='" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT COUNT(MESSAGEID) AS  \"total\"
            FROM    MESSAGE 
            WHERE   ISREAD = 1  
            AND     ISACTIVE = 1
            AND     STAFFIDTO='" . $this->getStaffId() . "'
            AND     COMPANYID='" . $this->getCompanyId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $total = $this->q->numberRows();
        if ($total > 0) {
            $row = $this->q->fetchArray($result);
            $readEmail = intval($row['total'] + 0);
        }
        return $readEmail;
    }

    /**
     *  Create
     * @see config::create()
     * @return void
     */
    public function create() {
        
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
     * Update
     * @see config::delete()
     * @return void
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     * @return void
     */
    public function excel() {
        
    }

}

?>