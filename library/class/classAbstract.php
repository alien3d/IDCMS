<?php

namespace Core;

date_default_timezone_set("Asia/Kuala_Lumpur");
// start fake document root. it's absolute path
$x = addslashes(realpath(__FILE__));
// auto detect if \\ consider come from windows else / from linux

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
require_once($newFakeDocumentRoot . "/library/phpexcel/Classes/PHPExcel.php");
require_once($newFakeDocumentRoot . "/library/phpexcel/Classes/PHPExcel/IOFactory.php");
require_once($newFakeDocumentRoot . "/library/phpexcel/Classes/PHPExcel/Cell/AdvancedValueBinder.php");
require_once($newFakeDocumentRoot . "/library/phpexcel/Classes/PHPExcel/Shared/String.php");
require_once($newFakeDocumentRoot . "/library/uas/UASparser.php");
require_once($newFakeDocumentRoot . "/library/geo/geoipcity.inc");
require_once($newFakeDocumentRoot . "/library/geo/geoipregionvars.php");
require_once('classMysql.php');
require_once('class.phpmailer.php');

/**
 * this is main setting files
 *
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser. html LGPL
 */
abstract class ConfigClass {

    /**
     * Salt (encryption/decription)
     */
    private $salt;

    /**
     * Key (encryption/decription)
     */
    private $key;

    /**
     * Mysql Database (open Core)
     * @var string
     */
    const MYSQL = 'mysql';

    /**
     * Microsoft Sql Server Database (Close Source)
     * @var string
     */
    const MSSQL = 'microsoft';

    /**
     * Oracle Database (Close  Source)
     * @var string
     */
    const ORACLE = 'oracle';

    /**
     * Database DB2 IBM ( Close Source)
     * @var string
     */
    const DB2 = 'db2';

    /**
     * Postgress (Open Source)
     * @var string
     */
    const POSTGRESS = 'postgress';

    /**
     * Cubrid Database ? korean database
     * @var string
     */
    const CUBRID = 'cubrid';

    /**
     * Firebird / Interbase
     * @var  string
     */
    const IBASE = 'ibase';

    /**
     * Php Excel Language.Czech
     * @var string
     */
    const Czech = 'Cs';

    /**
     * Php Excel Language.Danish
     * @var string
     */
    const Danish = 'Da';

    /**
     * Php Excel Language.German
     * @var string
     */
    const German = 'De';

    /**
     * Php Excel Language. Spanish
     * @var string
     */
    const Spanish = 'Es';

    /**
     * Php Excel Language.Finnish
     * @var string
     */
    const Finnish = 'Fi';

    /**
     * Php Excel Language.French
     * @var string
     */
    const French = 'Fr';

    /**
     * Php Excel Language.Hungarian
     * @var string
     */
    const Hungarian = 'Hu';

    /**
     * Php Excel Language.Italian
     * @var string
     */
    const Italian = 'It';

    /**
     * Php Excel Language.Dutch
     * @var string
     */
    const Dutch = 'Nl';

    /**
     * Php Excel Language.Norwegian
     * @var string
     */
    const Norwegian = 'No';

    /**
     * Php Excel Language.Polish
     * @var string
     */
    const Polish = 'Pl';

    /**
     * Php Excel Language.Portuguese
     * @var string
     */
    const Portuguese = 'pt';

    /**
     * Php Excel Language.Brazilian
     * @var string
     */
    const Brazilian = 'pt_br';

    /**
     * Php Excel Language.Russian
     * @var string
     */
    const Russian = 'ru';

    /**
     * Php Excel Language. Swedish
     * @var string
     */
    const Swedish = 'Sv';

     /**
* Connection to the database
* @var \Core\Database\Mysql\Vendor
*/
public $q;

    /**
     * Enter description here .
     *
     * @var string
     */
    public $value;

    /**
     * Enter description here .
     *
     * @var string
     */
    public $type;

    /**
     * Message Error Handling
     *
     * @var string
     */
    public $message;

    /**
     * Full Or Sidebar
     *
     * @var string
     */
    public $pageType;

    /**
     * HTML , JSON
     *
     * @var string
     */
    public $pageOutput;

    /**
     * option ,html
     *
     * @var string
     */
    public $serviceOutput;

    /**
     * HTML , JSON
     *
     * @var string
     */
    public $reportMode;

    /**
     * View Name.
     *
     * @var string
     */
    public $viewFilename;

    /**
     * View Path.Not automatic because give flexibility
     *
     * @var string
     */
    public $viewPath;

    /**
     * View Path.Not automatic because give flexibility
     *
     * @var string
     */
    public $viewDetailPath;

    /**
     * Controller Path.
     *
     * @var string
     */
    public $controllerFilename;

    /**
     * Controller Path.Not automatic because give flexibility
     *
     * @var string
     */
    public $controllerPath;

    /**
     * Controller Path.Not automatic because give flexibility
     *
     * @var string
     */
    public $controllerDetailPath;

    /**
     * Service Filename
     *
     * @var string
     */
    public $serviceFilename;

    /**
     * Service Path.Not automatic because give flexibility
     *
     * @var string
     */
    public $servicePath;

    /**
     * Internet Protocol
     * @var string
     */
    public $ip;

    /**
     * Execute Date
     * @var string
     */
    public $executeDate;

    /**
     * Execute Date/Time
     * @var string
     */
    public $executeTime;

    /**
     * CompanyPrimary Key
     *
     * @var int
     */
    private $companyId;

    /**
     * BranchPrimary Key
     *
     * @var int
     */
    private $branchId;

    /**
     * ProgramPrimary Key
     *
     * @var int
     */
    private $pageId;

    /**
     * Application Primary Key
     *
     * @var int
     */
    private $applicationId;

    /**
     *  Module Primary Key
     *
     * @var int
     */
    private $moduleId;

    /**
     * Folder Primary key
     *
     * @var int
     */
    private $folderId;

    /**
     * ProgramPrimary Key
     *
     * @var int
     */
    private $leafId;

    /**
     * UserPrimary Key
     *
     * @var int
     */
    private $staffId;

    /**
     * Language Primary Key
     * @var int
     */
    private $languageId;
    
    /**
     * Country Currency Code
     * @var string
     */
    private $countryCurrencyCode;

    /**
     * Country Currency Locale
     * @var string
     */
    private $countryCurrencyLocale;

    /**
     * RolePrimary Key
     *
     * @var int
     */
    private $roleId;

    /**
     * Role Description
     *
     * @var string
     */
    private $roleDescription;

    /**
     * Staff Name
     *
     * @var string
     */
    private $staffName;

    /**
     * Database Connection
     * @var string
     */
    private $connection;

    /**
     * Database Name
     *
     * @var string
     */
    private $database;

    /**
     * Database Name
     *
     * @var string
     */
    private $username;

    /**
     * User Password.
     *
     * @var string
     */
    private $password;

    /**
     * Database Vendor
     *
     * @var string
     */
    private $vendor;

    /**
     * Field Query UX
     *
     * @var string
     */
    private $fieldQuery;

    /**
     * Grid Filter Plugin
     *
     * @var string
     */
    private $gridQuery;

    /**
     * Character Based Query Style
     *
     * @var string
     */
    private $characterQuery;

    /**
     * Date Filtering Query Style
     *
     * @var string
     */
    private $dateRangeStartQuery;

    /**
     * Date Filtering Query Style
     *
     * @var string
     */
    private $dateRangeEndQuery;

    /**
     * Day,Week,Month,Year
     *
     * @var string
     */
    private $dateRangeTypeQuery;

    /**
     * Previous or Next Day,Week,Month,Year
     *
     * @var string
     */
    private $dateRangeExtraTypeQuery;

    /**
     * Start Day
     *
     * @var string
     */
    private $startDay;

    /**
     * Start Week
     *
     * @var string
     */
    private $startWeek;

    /**
     * Start Month
     *
     * @var string
     */
    private $startMonth;

    /**
     * Start Year
     *
     * @var string
     */
    private $startYear;

    /**
     * End Day
     *
     * @var string
     */
    private $endDay;

    /**
     * End Week
     *
     * @var string
     */
    private $endWeek;

    /**
     * End Month
     * @var string
     */
    private $endMonth;

    /**
     * End Year
     * @var string
     */
    private $endYear;

    /**
     * Filter Month
     * @var string
     */
    private $filterMonth;

    /**
     * Filter Year
     * @var string
     */
    private $filterYear;

    /**
     * Start Record
     * @var string
     */
    private $start;

    /**
     * Limit Record Per Page
     * @var string
     */
    private $limit;

    /**
     * *
     * Ascending ,Descending ASC,DESC
     * @var string
     */
    private $order;

    /**
     * Sort the default field.Mostly consider as primary key default.
     * @var string
     */
    private $sortField;

    /**
     * Default Language : English
     * @var int
     */
    private $defaultLanguageId;

    /**
     * Open To See Audit Column --> approved,new,delete and e.g
     * @var int
     */
    private $isAdmin;

    /**
     * Request Database
     * @var string
     */
    private $requestDatabase;

    /**
     * Return Security Token
     * @var string
     */
    private $securityToken;

    /**
     * Current Database
     * @var $currentDatabase
     */
    private $currentDatabase;

    /**
     * Current table
     * @var string $currentTable
     */
    private $currentTable;

    /**
     * Structured Query Language Log Statement
     * @var bool
     */
    private $log;

    /**
     * Audit Log Statement
     * @var bool
     */
    private $audit;

    /**
     * English Notification
     * @var bool
     */
    private $notification;

    /**
     * Audit Column
     * @var int
     */
    private $auditColumn;

    /**
     * Audit Filter
     * @var string
     */
    private $auditFilter;

    /**
     * Report Title For Php Words,Php Excel
     * @var string
     */
    private $reportTitle;

    /**
     * Filename
     * @var string
     */
    private $filename;

    /**
     * Fake Document Root
     * @var string
     */
    private $fakeDocumentRoot;
    /////////////////////////////
    // finance info
    /**
     * @var int
     */
    private $financeYearId;

    /**
     * Financial Period / Month
     * @var int
     */
    private $financePeriod;

    /**
     * Browser Compability
     * @var string
     */
    private $browserCompability;

    /**
     /**
  * System Format Array
     * @var array
     */
    public $systemFormatArray;

    /////////////////////////////

    /**
     * @version 0.1 filter strict php setting
     */
    function __construct() {
        if (isset($_SESSION['vendor'])) {
            $this->setVendor($_SESSION['vendor']);
        } else {
            //$this->setVendor(self::MYSQL); // testing period only
            $this->setVendor(self::MYSQL);
        }
        // check loaded or not
        $error = null;
        switch ($this->getVendor()) {
            case self::MYSQL:
                if (!extension_loaded('mysql')) {
                    echo "<!DOCTYPE html><link rel=\"stylesheet\"
          href=\"./library/twitter2/docs/assets/css/bootstrap.css\"><style>body {
            padding-top: 60px;
            padding-bottom: 40px;
             padding-left: 70px;
        }
</style><body><br><br><div align=\"center\" style=\"width:90%\">";
                    $this->exceptionMessage('Have you include mysql library in php.ini ?');
                    echo "</div></body></html>";
                    $error = 1;
                } else {
                    //echo "loaded mysql extension";
                }

                break;
            case self::MSSQL:
                if (!extension_loaded('sqlsrv')) {
                    echo "<!DOCTYPE html><link rel=\"stylesheet\"
          href=\"./library/twitter2/docs/assets/css/bootstrap.css\"><style>body {
            padding-top: 60px;
            padding-bottom: 40px;
             padding-left: 70px;
        }
</style><body><br><br><div align=\"center\" style=\"width:90%\">";
                    $this->exceptionMessage('Have you include SQL SRV library in php.ini ?');
                    echo "</div></body></html>";
                    $error = 1;
                } else {
                    // echo "loaded sql server library";
                }


                break;
            case self::ORACLE:
                if (!extension_loaded('oci')) {
                    echo "<!DOCTYPE html><link rel=\"stylesheet\"
          href=\"./library/twitter2/docs/assets/css/bootstrap.css\"><style>body {
            padding-top: 60px;
            padding-bottom: 40px;
            padding-left: 70px;
        }
</style><body><br><br><div align=\"center\" style=\"width:90%\">";
                    $this->exceptionMessage('Have you include oracle library in php.ini ?');
                    echo "</div></body></html>";
                    $error = 1;
                } else {
                    // echo "loaded oracle library";
                }
                break;
            default:
                $this->exceptionMessage('Weird Something Wrong');
                $error = 1;
        }
        if ($error == 1) {

            exit();
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(1);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(2);
        }

        if ($this->getVendor() == self::MYSQL) {
            // echo "include Mysql Server Class";
            require_once('classMysql.php');
            // set per page.please override at the controller if  don't want the same.
        } else if ($this->getVendor() == self::MSSQL) {
            //echo "include Microsor Sql Server Class";
            require_once('classMssql.php');
        } else {
            if ($this->getVendor() == self::ORACLE) {
                //echo "include oracle class";
                require_once('classOracle.php');
            } else {
                //echo "what database is it?";
            }
        }
        $this->setSecurityToken(sha1('chak chak bonjour hacker'));
        $this->setSalt($this->getSecurityToken());
        $this->setVersionCompability();
    }

    /**
     * Internet Protocol Information
     * @return mixed $browserInformation
     */
    public function getInternetProtocolInformation() {
		$record= null;
        $gi = geoip_open($this->getFakeDocumentRoot() . "library/geo/GeoLiteCity.dat", GEOIP_STANDARD);
        //preventing up looping on local testing
        if ($this->getInternetProtocolAddress() != '::1' && $this->getInternetProtocolAddress() != '127.0.0.1') {
            $record = geoip_record_by_addr($gi, $this->getInternetProtocolAddress());
        }
        return $record;
    }

    /**
     * Browser Information
     * @return mixed $browserInformation
     */
    public function getBrowserInformation() {
        $parser = new \UASparser();
        $parser->SetCacheDir($this->getFakeDocumentRoot() . "library/uas/cache/");
        return $parser->Parse();
    }

    /**
     * Return Vendor Database
     * @return string
     */
    public function getVendor() {
        return $this->vendor;
    }

    /**
     * Set Vendor Database
     * @param string $value Vendor Database
     * @return $this
     */
    public function setVendor($value) {
        $this->vendor = $value;
        return $this;
    }

    /**
     * Block of html error message
     * @param string $message
     */
    function exceptionMessage($message) {
        $this->setMessage($message);
        if (strlen($this->getMessage()) > 0) {
            echo "<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><img src='./images/icons/smiley-nerd.png'> " . $this->getMessage(
            ) . "</div>";
            //debug_print_backtrace();
            //exit();
        }
    }

    /**
     *
     * @return string $message
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Set Message
     * @param string $message
     * @return ConfigClass
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * Encrypt text
     * @param string $decrypted text
     * @return string
     */
    function encrypt($decrypted) {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $this->getSalt() . $this->getKey(), true);
        // Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
        srand();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22){
            return false;
        }
        // Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
        // We're done!
        return $iv_base64 . $encrypted;
    }

    /**
     * Encrypt text
     * @param string $encrypted text
     * @return string
     */
    function decrypt($encrypted) {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $this->getSalt() . $this->getKey(), true);
        // Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
        $iv = base64_decode(substr($encrypted, 0, 22) . '==');
        // Remove $iv from $encrypted.
        $encrypted = substr($encrypted, 22);
        // Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
        // Retrieve $hash which is the last 32 characters of $decrypted.
        $hash = substr($decrypted, -32);
        // Remove the last 32 characters from $decrypted.
        $decrypted = substr($decrypted, 0, -32);
        // Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
        if (md5($decrypted) != $hash){
            return false;
        }
        // Yay!
        return $decrypted;
    }

    /**
     * Insert a notification and push it to the wall
     * @param string $message Message
     * @param null|int $staffId Staff
     * @return void
     */
    function createNotification($message, $staffId = null) {
        if (empty($staffId)) {
            $staffId = 2;
        }
        $x = addslashes(realpath(__FILE__));
        // auto detect if \\ consider come from windows else / from linux

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
        for ($z = 0; $z < count($newPath); $z++) {
            $fakeDocumentRoot .= $newPath[$z];
        }
        $fakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
        // end fake document root. it's absolute path
        $sql = null;
        $userId = $this->getStaffId();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        INSERT INTO `notification`
            (
                `notificationFrom`,             `notificationMessage`,
                `staffId`,                      `isDefault`,
                `isNew`,                        `isDraft`,
                `isUpdate`,                     `isDelete`,
                `isActive`,                     `isApproved`,
                `isReview`,                     `isPost`,
                `executeBy`,                    `executeTime`,
                `companyId`
             ) VALUES (
               '" . $userId . "',                 '" . $this->strict($message, 'w') . "',
                " . $staffId . ",   0,
                1,                              0,
                0,                              0,
                1,                              0,
                0,                              0,
                '" . $this->getStaffId() . "',                  " . $this->getExecuteTime() . ",
                '" . $_SESSION['companyId'] . "'
             );
        ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
        INSERT INTO `notification`
            (
                [notificationFrom],             [notificationMessage],
                [staffId],                      [isDefault],
                [isNew],                        [isDraft],
                [isUpdate],                     [isDelete],
                [isActive],                     [isApproved],
                [isReview],                     [isPost],
                [executeBy],                    [executeTime],
                [companyId]
             ) VALUES (
               '" . $userId . "',                 '" . $this->strict($message, 'w') . "',
                " . $staffId . ",   0,
                1,                              0,
                0,                              0,
                1,                              0,
                0,                              0,
                '" . $this->getStaffId() . "',                  " . $this->getExecuteTime() . ",
                '" . $_SESSION['companyId'] . "'
             );
        ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
        INSERT INTO NOTIFICATION
            (
                NOTIFICATIONFROM,             NOTIFICATIONMESSAGE,
                STAFFID,                      ISDEFAULT,
                ISNEW,                        ISDRAFT,
                ISUPDATE,                     ISDELETE,
                ISACTIVE,                     ISAPPROVED,
                ISREVIEW,                     ISPOST,
                EXECUTEBY,                    EXECUTETIME,
                COMPANYID
             ) VALUES (
               '" . $userId . "',                 '" . $this->strict($message, 'w') . "',
                " . $staffId . ",   0,
                1,                              0,
                0,                              0,
                1,                              0,
                0,                              0,
                '" . $this->getStaffId() . "',                  " . $this->getExecuteTime() . ",
                '" . $this->getCompanyId() . "'
             );
        ";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Return Financial Year And Financial Period Based On Date Specified
     * @param string $date
     */
    public function getFinancialYearInfo($date) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financeYearId`,
                   `financePeriodRangePeriod`
            FROM   `financePeriodRange`
            WHERE  `companyId`  =   '" . $this->getCompanyId() . "'
            AND    `isActive`   = 1
            AND    `" . $date . "` BETWEEN `financePeriodRangeStartDate` AND  `financePeriodRangeEndDate`
            ";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [financeYearId],
                   [financePeriodRangePeriod]
            FROM   [financePeriodRange]
            WHERE  [companyId]  =   '" . $this->getCompanyId() . "'
            AND    [isActive]   = 1
            AND    [" . $date . "] BETWEEN [financePeriodRangeStartDate] AND  [financePeriodRangeEndDate]
            ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT FINANCEYEARID AS \"financeYearId\",
                   FINANCEPERIODRANGEPERIOD AS \"financePeriodRangePeriod\"
            FROM   FINANCEPERIODRANGE
            WHERE  COMPANYID  =   '" . $this->getCompanyId() . "'
            AND    ISACTIVE   = 1
            AND    \"" . $date . "\" BETWEEN FINANCEPERIODRANGESTARTDATE AND  FINANCEPERIODRANGEENDDATE
            ";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setFinanceYearId(floatval($row['financeYearId']));
            $this->setFinancePeriod(intval($row['financePeriodRangePeriod']));
        }
    }

    /**
     * to filter data type.
     *
     * @param string $v
     *            value variable come from server request or variable
     * @param string $t
     *            Available data type password or p ,numeric or n,bool or
     *            b,string or s, wyswg or w,memo or m,float or f,date or
     *            d,username or u,username or u, * web or wb
     * @return int mixed null string
     */
    public function strict($v, $t) {
        $str = "";
        $this->value = $v;
        $this->type = $t;
        // short form code available
        if ($this->type == 'password' || $this->type == 'p') {
            if (strlen($this->value) != 32) {
                if (empty($this->value)) {
                    $str = null;
                }
            }
            return (addslashes($this->value));
        } elseif ($this->type == 'numeric' || $this->type == 'n' ||
                $this->type == 'int' || $this->type == 'integer'
        ) {
            if (!is_numeric($this->value)) {
                $this->value = 0;
                $str = ($this->value) * 1;
            } else {
                $str = (intval($this->value)) * 1;
            }
        } elseif ($this->type == 'boolean' || $this->type == 'b' ||
                $this->type == 'bool'
        ) {
            if (!is_bool($this->value)) {
                $str = 0;
            }
            if ($this->value == 'TRUE') {
                $str = 1;
            } elseif ($this->value) {
                $str = 0;
            }
        } elseif ($this->type == 'string' || $this->type == 's' ||
                $this->type == 'text'
        ) {
            if (empty($this->value) && (strlen($this->value) == 0)) {
                $this->value = null;
                // prevent oracle bugs.
                $str = " " . ($this->value);
            } elseif (strlen($this->value) == 0) {
                $this->value = null;
                $str = ($this->value);
            } else {
                //$this->value = addslashes($this->value);
                $str = trim($this->value);
            }
        } else
        if (($this->type == 'email' || $this->type == 'e') ||
                ($this->type == 'filename' || $this->type == 'f') ||
                ($this->type == 'icon' || $this->type == 'i') ||
                ($this->type == 'calendar' || $this->type == 'c') ||
                ($this->type == 'username' || $this->type == 'u') ||
                ($this->type == 'web' || $this->type == 'wb')
        ) {
            if (empty($this->value) && (strlen($this->value) == 0)) {
                $this->value = null;
                $str = ($this->value);
            } elseif (strlen($this->value) == 0) {
                $this->value = null;
                $str = ($this->value);
            } else {
                $this->value = trim($this->value); // trim any space better
                // for searching issue
                $str = $this->value;
            }
        } elseif ($this->type == 'wyswyg' || $this->type == 'w') {
            // just return back
            // add slashes will destroy the code
            $this->value = addslashes($this->value);
            $str = (htmlspecialchars($this->value));
        } elseif ($this->type == 'blob') {
            // this is easy for php/mysql developer
            $this->value = addslashes($this->value);
            $str = (htmlspecialchars($this->value));
        } elseif ($this->type == 'memo' || $this->type == 'm') {
            // this is easy for vb/access developer
            $this->value = addslashes($this->value);
            $str = (htmlspecialchars($this->value));
        } elseif ($this->type == 'currency' || $this->type == 'double') {
            // make easier for vb.net programmer to understand float value
            $this->value = str_replace("$", "", $this->value); // filter for
            // if exist
            $this->value = str_replace(",", "", $this->value);
            $str = ($this->value);
        } elseif ($this->type == 'float' || $this->type == 'f') {
            // make easier c programmer to understand float value
            $this->value = str_replace("$", "", $this->value); // filter for
            // if exist
            $this->value = str_replace(",", "", $this->value);
            $str = ($this->value);
        } elseif ($this->type == 'date' || $this->type == 'd') {
            // ext date like this mm/dd yy03/03/07
            // ext date mm/dd/yy mysql date YY/mm/dd
            // ext all ready validate date at javascript runtime
            // check either the date empty or not if empty key in today
            // value
            if (empty($this->value)) {
                $str = (date("Y-m-d"));
            } else {
                $pos = strpos($this->value, "-");
                if ($pos !== false) {
                    $x = explode("-", $this->value);
                    $str = $x[2] . "-" . $this->setZero($x[1]) . "-" . $this->setZero($x[0]);
                }
                $pos = strpos($this->value, "/");
                if ($pos !== false) {
                    $x = explode("/", $this->value);
                    //$str = $x[2] . "-" . $this->setZero($x[1]) . "-" . $this->setZero($x[0]);
                    $str = $x[2] . "-" . $this->setZero($x[0]) . "-" . $this->setZero($x[1]);
                }
            }
        }
        return $str;
    }

    /**
     * Add 0 figure to the string
     * @param string $str String Date
     * @return string
     */
    public function setZero($str) {
        $value = intval($str); // should be numeric only
        if (strlen($value) == 1) {
            return "0" . $value;
        } else {
            return $value;
        }
    }

    /**
     * Create  a record of bookmark so . will push latest  5 or 10 visited application famously
     * @param null|int $applicationId ApplicationPrimary Key
     * @param null|int $moduleId ModulePrimary Key
     * @param null|int $folderId FolderPrimary Key
     * @param null|int $leafId LeafPrimary Key
     * @return void
     */
    function setApplicationLog($applicationId = null, $moduleId = null, $folderId = null, $leafId = null) {
        $this->createLeafBookmark($applicationId, $moduleId, $folderId, $leafId, 1);
    }

    /**
     * Create  a record of bookmark so . will push latest  5 or 10 visited application famously
     * @param null|int $applicationId ApplicationPrimary Key
     * @param null|int $moduleId ModulePrimary Key
     * @param null|int $folderId FolderPrimary Key
     * @param null|int $leafId LeafPrimary Key
     * @return void
     */
    function setModuleLog($applicationId = null, $moduleId = null, $folderId = null, $leafId = null) {
        $this->createLeafBookmark($applicationId, $moduleId, $folderId, $leafId, 2);
    }

    /**
     * Create  a record of bookmark so . will push latest  5 or 10 visited application famously
     * @param null|int $applicationId ApplicationPrimary Key
     * @param null|int $moduleId ModulePrimary Key
     * @param null|int $folderId FolderPrimary Key
     * @param null|int $leafId LeafPrimary Key
     * @return void
     */
    function setFolderLog($applicationId = null, $moduleId = null, $folderId = null, $leafId = null) {
        $this->createLeafBookmark($applicationId, $moduleId, $folderId, $leafId, 3);
    }

    /**
     * Create  a record of bookmark so . will push latest  5 or 10 visited application famously
     * @param null|int $applicationId ApplicationPrimary Key
     * @param null|int $moduleId ModulePrimary Key
     * @param null|int $folderId FolderPrimary Key
     * @param null|int $leafId LeafPrimary Key
     * @return void
     */
    function setLeafLog($applicationId = null, $moduleId = null, $folderId = null, $leafId = null) {
        $this->createLeafBookmark($applicationId, $moduleId, $folderId, $leafId, 4);
    }

    /**
     * Create  a record of bookmark so . will push latest  5 or 10 visited application famously
     * @param null|int $applicationId ApplicationPrimary Key
     * @param null|int $moduleId ModulePrimary Key
     * @param null|int $folderId FolderPrimary Key
     * @param null|int $leafId LeafPrimary Key
     * @return void
     */
    function createDashboardLog($applicationId = null, $moduleId = null, $folderId = null, $leafId = null) {
        $this->createLeafBookmark($applicationId, $moduleId, $folderId, $leafId, 5);
    }

    /**
     * Create  a record of bookmark so . will push latest  5 or 10 visited application famously
     * @param null|int $applicationId ApplicationPrimary Key
     * @param null|int $moduleId ModulePrimary Key
     * @param null|int $folderId FolderPrimary Key
     * @param null|int $leafId LeafPrimary Key
     * @param null|int $type 1 -> Appplication ,2 ->Module,3 -> Folder ,4 - Leaf ,5 ->Dashboard
     */
    function createLeafBookmark($applicationId = null, $moduleId = null, $folderId = null, $leafId = null, $type = null) {
        $sql = null;
        if ($applicationId == null || empty($applicationId)) {
            $applicationId = 0;
        }
        if ($moduleId == null || empty($moduleId)) {
            $moduleId = 0;
        }
        if ($folderId == null || empty($folderId)) {
            $folderId = 0;
        }
        if ($leafId == null || empty($leafId)) {
            $leafId = 0;
        }
        // by default is leaf
        if ($type == null || empty($type)) {
            $type = 4;
        }
        $record = $this->getInternetProtocolInformation();
		 $country_code =null;
		$country_name = null;
		$region = null;
		$latitude = null;
		$longtitude = null;
		if (isset($record)) {
            if (is_object($record)) {
                // preventing bugs on ip looping
                $country_code = $record->country_code;
                $country_name = $record->country_name;
                $region = $record->region;
                $latitude = $record->latitude;
                $longtitude = $record->longtitude;
            }
        }
        $ret = $this->getBrowserInformation();
		$ua_type=null;
		if(isset($ret['ua_type'])) {
			$ua_type= $ret['ua_type'] ;
		}
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
       INSERT INTO `leafstaffbookmark`
           (
                `companyId`,
                
                `applicationId`,
                `moduleId`,
                `folderId`,
                `leafId`,
                
                `roleId`,
                `staffId`,
                
                `ua_type`, 
                `ua_family`, 
                `ua_name`, 
                `ua_version`, 
                
                `ua_url`, 
                `ua_company`, 
                `ua_company_url`, 
                `ua_icon`, 
                
                `ua_info_url`,
				
                `os_family`, 
                `os_name`, 
                `os_url`, 
				`os_company`,
				
                `os_company_url`,  			
                `os_icon`, 
                
                `ip_v4`, 
                `ip_country_code`, 
                `ip_country_name`, 
                `ip_region_name`, 
				
                `ip_latitude`, 				
                `ip_longtitude`
           ) VALUES (

                '" . $this->getCompanyId() . "',
                    
                " . $applicationId . ",
                " . $moduleId . ",
                " . $folderId . ",
                " . $leafId . ",
				
                '" . $this->getRoleId() . "',
                '" . $this->getStaffId() . "',
                    
                '" . $ua_type. "',
                '" . $ret['ua_family'] . "',
                '" . $ret['ua_name'] . "',
                '" . $ret['ua_version'] . "',
                    
                '" . $ret['ua_url'] . "',
                '" . $ret['ua_company'] . "',
                '" . $ret['ua_company_url'] . "',
                '" . $ret['ua_icon'] . "',
                    
                '" . $ret['ua_info_url'] . "',
            
                '" . $ret['os_family'] . "',
                '" . $ret['os_name'] . "',
                '" . $ret['os_url'] . "',
                '" . $ret['os_company'] . "',
				
                '" . $ret['os_company_url'] . "',           
                '" . $ret['os_icon'] . "',
            
                '" . $this->getInternetProtocolAddress() . "',
                '" . $country_code . "',
                '" . $country_name . "',
                '" . $region . "',
				
                '" . $latitude . "',
                '" . $longtitude . "'

            
           )";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
       INSERT INTO [leafStaffBookmark]
           (
                [companyId],
                [applicationId],
                [moduleId],
                [folderId],
                [leafId],
                [roleId],
                [staffId],
                
                [ua_type], 
                [ua_family], 
                [ua_name], 
                [ua_version], 
                
                [ua_url], 
                [ua_company], 
                [ua_company_url], 
                [ua_icon], 
                
                [ua_info_url], 
                
                [os_family], 
                [os_name], 
                [os_url], 
				[os_company],
                [os_company_url],
                
                [os_icon], 
                
                [ip_v4], 
                [ip_country_code], 
                [ip_country_name], 
                [ip_region_name], 
                [ip_latitude], 
                [ip_longtitude]
           ) VALUES (

                '" . $this->getCompanyId() . "',
                " . $applicationId . ",
                " . $moduleId . ",
                " . $folderId . ",
                " . $leafId . ",
                '" . $this->getRoleId() . "',
                '" . $this->getStaffId() . "',
                    
                '" . $ret['type'] . "',
                '" . $ret['ua_family'] . "',
                '" . $ret['ua_name'] . "',
                '" . $ret['ua_version'] . "',
                    
                '" . $ret['ua_url'] . "',
                '" . $ret['ua_company'] . "',
                '" . $ret['ua_company_url'] . "',
                '" . $ret['ua_icon'] . "',
                    
                '" . $ret['ua_info_url'] . "',
            
                '" . $ret['os_family'] . "',
                '" . $ret['os_name'] . "',
                '" . $ret['os_url'] . "',
                '" . $ret['os_company'] . "',
                '" . $ret['os_company_url'] . "',
            
                '" . $ret['os_icon'] . "',
            
                '" . $this->getInternetProtocolAddress() . "',
                            '" . $country_code . "',
                '" . $country_name . "',
                '" . $region . "',
				
                '" . $latitude . "',
                '" . $longtitude . "'
           )";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
       INSERT INTO LEAFSTAFFBOOKMARK
           (
                COMPANYID,
                APPLICATIONID,
                MODULEID,
                FOLDERID,
                LEAFID,
                ROLEID,
                STAFFID,
                
                UA_TYPE, 
                UA_FAMILY, 
                UA_NAME, 
                UA_VERSION, 
                
                UA_URL, 
                UA_COMPANY, 
                UA_COMPANY_URL, 
                UA_ICON, 
                
                UA_INFO_URL, 
                
                OS_FAMILY, 
                OS_NAME, 
                OS_URL, 
				OS_COMPANY,
                OS_COMPANY_URL,
                
                OS_ICON, 
                
                IP_V4, 
                IP_COUNTRY_CODE, 
                IP_COUNTRY_NAME, 
                IP_REGION_NAME, 
                IP_LATITUDE, 
                IP_LONGTITUDE
           ) VALUES (

                '" . intval($this->getCompanyId()) . "',
                " . intval($applicationId) . ",
                " . intval($moduleId) . ",
                " . intval($folderId) . ",
                " . intval($leafId) . ",
                '" . intval($this->getRoleId()) . "',
                '" . intval($this->getStaffId()) . "',
                    
                '" . $ret['type'] . "',
                '" . $ret['ua_family'] . "',
                '" . $ret['ua_name'] . "',
                '" . $ret['ua_version'] . "',
                    
                '" . $ret['ua_url'] . "',
                '" . $ret['ua_company'] . "',
                '" . $ret['ua_company_url'] . "',
                '" . $ret['ua_icon'] . "',
                    
                '" . $ret['ua_info_url'] . "',
            
                '" . $ret['os_family'] . "',
                '" . $ret['os_name'] . "',
                '" . $ret['os_url'] . "',
                '" . $ret['os_company'] . "',
                '" . $ret['os_company_url'] . "',
            
                '" . $ret['os_icon'] . "',
            
                '" . $this->getInternetProtocolAddress() . "',
                '" . $country_code . "',
                '" . $country_name . "',
                '" . $region . "',
				
                '" . $latitude . "',
                '" . $longtitude . "'
           )";
        }

        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Generate Document no
     *
     */

    /**
     * Return CompanyPrimary Key
     * @return int
     */
    public function getCompanyId() {
        return $this->companyId;
    }

    /**
     * Set CompanyPrimary Key
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setCompanyId($value) {
        $this->companyId = $value;
        return $this;
    }

    /**
     * Return BranchPrimary Key
     * @return int
     */
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * Set BranchPrimary Key
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setBranchId($value) {
        $this->branchId = $value;
        return $this;
    }

    /**
     * Return RolePrimary Key
     *
     * @return int
     */
    function getRoleId() {
        return $this->roleId;
    }

    /**
     * Set RolePrimary Key
     *
     * @param int $value
     * @return \Core\ConfigClass
     */
    function setRoleId($value) {
        $this->roleId = $value;
        return $this;
    }

    /**
     * Return UserPrimary Key
     *
     * @return int
     */
    public function getStaffId() {
        return $this->staffId;
    }

    /**
     * Set UserPrimary Key
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setStaffId($value) {
        $this->staffId = $value;
        return $this;
    }

    /**
     * Generete  Document
     * @param null $documentSequenceCode
     * @return mixed
     */
    public function getDocumentNumber($documentSequenceCode = null) {

        return $this->documentNumbering($documentSequenceCode);
    }

    /**
     * Generate Document no
     * @param null $documentSequenceCode
     */
    public function documentNumbering($documentSequenceCode = null) {
        // this is to generate doc_no
        $sqlDocumentNumberingUpdate = null;
        $sqlDocumentNumbering = null;
        if ($this->getVendor() == self::MYSQL) {
            $sqlDocumentNumberingUpdate = "
            UPDATE	`documentsequence`
            SET     `documentSequenceNumber`    =   `documentSequenceNumber` + 1
            WHERE   `companyId`                 =   '" . $this->getCompanyId() . "'";
            if ($documentSequenceCode) {
                $sqlDocumentNumberingUpdate .= "
                AND `documentSequenceCode`  =   '" . $documentSequenceCode . "'";
            } else {
                $sqlDocumentNumberingUpdate .= "
                AND `documentSequenceId` =
                (
                    SELECT  `documentSequenceId`
                    FROM  	`documentsetting`
                    WHERE 	`leafId`    =   '" . $this->getleafId() . "'
                    AND     `companyId` =   '" . $this->getCompanyId() . "'
					LIMIT 1
                )";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sqlDocumentNumberingUpdate = "
            UPDATE	[documentSequence]
            SET     [documentSequenceNumber]    =   [documentSequenceNumber] + 1
            WHERE   [companyId]                 =   '" . $this->getCompanyId() . "'";
            if ($documentSequenceCode) {
                $sqlDocumentNumberingUpdate .= "
                AND [documentSequenceCode]      =   '" . $documentSequenceCode . "'";
            } else {
                $sqlDocumentNumberingUpdate .= "
                AND [documentSequenceId] =
                (
                    SELECT  TOP 1 [documentSequenceId]
                    FROM  	[documentSetting]
                    WHERE 	[leafId]    =   '" . $this->getleafId() . "'
                )";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sqlDocumentNumberingUpdate = "
            UPDATE	DOCUMENTSEQUENCE
            SET     DOCUMENTSEQUENCENUMBER  =   DOCUMENTSEQUENCENUMBER + 1
            WHERE   COMPANYID               =   '" . $this->getCompanyId() . "'";
            if ($documentSequenceCode) {
                $sqlDocumentNumberingUpdate .= "
                AND DOCUMENTSEQUENCECODE  =   '" . $documentSequenceCode . "'";
            } else {
                $sqlDocumentNumberingUpdate .= "
                AND DOCUMENTSEQUENCEID =
                (
                    SELECT  DOCUMENTSEQUENCEID
                    FROM  	DOCUMENTSETTING
                    WHERE 	LEAFID      =   '" . $this->getleafId() . "'
                    AND     COMPANYID   =   '" . $this->getCompanyId() . "'
					AND ROWNUMS=1
                )";
            }
        }
        try {
            $this->q->update($sqlDocumentNumberingUpdate);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($this->getVendor() == self::MYSQL) {
            $sqlDocumentNumbering = "
            SELECT	CONCAT(	`documentSequencecode`,
                            `documentSequenceNumber`) AS `documentNumber`
            FROM  	`documentsequence`
            WHERE	`companyId`   ='" . $this->getCompanyId() . "'";
            if ($documentSequenceCode) {
                $sqlDocumentNumbering .= "
                AND `documentSequenceCode` = '" . $documentSequenceCode . "'";
            } else {
                $sqlDocumentNumbering .=
                        "AND documentSequenceId =
                    (
                        SELECT  `documentSequenceId`
                        FROM  	`documentsetting`
                        WHERE 	`leafId`='" . intval($this->getleafId()) . "'
                    AND		`companyId`=	'" . $this->getCompanyId() . "'
					LIMIT 1
                )";
            }
        } else if ($this->getVendor() == self::MSSQL) {
            $sqlDocumentNumbering = "
            SELECT	CONCAT(	[documentSequencecode],
                            [documentSequenceNumber]) AS [documentNumber]
            FROM  	[documentSequence]
            WHERE	[companyId]   ='" . $this->getCompanyId() . "'";
            if ($documentSequenceCode) {
                $sqlDocumentNumbering .= "
                AND [documentSequenceCode] = '" . $documentSequenceCode . "'";
            } else {
                $sqlDocumentNumbering .= "documentSequenceId =
                AND (
                    SELECT  TOP 1[documentSequenceId]
                    FROM  	[documentSetting]
                    WHERE 	[leafId]    =   '" . intval($this->getleafId()) . "'
                    AND		[companyId] =	'" . $this->getCompanyId() . "'
                )";
            }
        } else if ($this->getVendor() == self::ORACLE) {
            $sqlDocumentNumbering = "
            SELECT	CONCAT(DOCUMENTSEQUENCECODE,DOCUMENTSEQUENCENUMBER) AS  \"documentNumber\"
            FROM  	DOCUMENTSEQUENCE
            WHERE	COMPANYID   ='" . $this->getCompanyId() . "'";
            if ($documentSequenceCode) {
                $sqlDocumentNumbering .= "
                AND DOCUMENTSEQUENCECODE = '" . $documentSequenceCode . "'";
            } else {
                $sqlDocumentNumbering .= "
                AND DOCUMENTSEQUENCEID =
                (
                    SELECT  DOCUMENTSEQUENCEID
                    FROM  	DOCUMENTSETTING
                    WHERE 	LEAFID='" . intval($this->getleafId()) . "'
                    AND     COMPANYID = '" . $this->getCompanyId() . "'
					AND ROWNUMS=1
                )";
            }
        }
        try {
            $resultDocumentNumbering = $this->q->fast($sqlDocumentNumbering);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        $rowDocumentNumbering = $this->q->fetchArray($resultDocumentNumbering);
        $documentNumber = $rowDocumentNumbering['documentNumber'];
        return $documentNumber;
    }

    /**
     * Return Application Primary Key
     * @return int
     */
    public function getApplicationId() {
        return $this->applicationId;
    }

    /**
     * Set  Application Primary Key
     * @param int $value value
     * @return \Core\ConfigClass
     */
    public function setApplicationId($value) {
        $this->applicationId = $value;
        return $this;
    }

    /**
     * Return Module Primary Key
     * @return int
     */
    public function getModuleId() {
        return $this->moduleId;
    }

    /**
     * Set Module Primary Key
     * @param int $value value
     * @return \Core\ConfigClass
     */
    public function setModuleId($value) {
        $this->moduleId = $value;
        return $this;
    }

    /**
     * Return Folder Primary Key
     * @return int
     */
    public function getFolderId() {
        return $this->folderId;
    }

    /**
     * Set  Folder Primary key
     * @param int $value value
     * @return \Core\ConfigClass
     */
    public function setFolderId($value) {
        $this->folderId = $value;
        return $this;
    }

    /**
     * Return Leaf  Primary Key
     * @return int
     */
    public function getLeafId() {
        return $this->leafId;
    }

    /**
     * Set leaf  Primary Key
     * @param int $value value
     * @return \Core\ConfigClass
     */
    public function setLeafId($value) {
        $this->leafId = $value;
        return $this;
    }

    /**
     * Return Latest Currency using Google Calculate api.If the user does not have internet we assume 1 .. to avoid user mistake
     * @param string $from_Currency
     * @param string $to_Currency
     * @param float $amount
     * @return float
     */
    function getCurrency($from_Currency, $to_Currency, $amount) {
        $amount = urlencode($amount);
        $from_Currency = urlencode($from_Currency);
        $to_Currency = urlencode($to_Currency);

        $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";

        $ch = curl_init();
        $timeout = 0;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $rawData = curl_exec($ch);
        curl_close($ch);
        if (strlen($rawData) > 0) {
            $data = explode("bld>", $rawData);

            if (is_array($data) && (count($data) > 0)) {
                $data = explode($to_Currency, $data[1]);
                $value = $data[0];
            } else {
                $value = 1;
            }
        } else {
            $value = 1;
        }
        // echo round($data[0], 2);
        return $value;
    }

    /**
     * Check Date Format
     * @return mixed
     */
    function checkDateFormat() {
        $systemSettingDateFormat = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT `systemSettingDateFormat`
			FROM   `systemsetting`
			WHERE  `compnyId`   =''..''";
        } else if ($this->getVendor() == self::MSSQL) {
            
        } else if ($this->getVendor() == self::ORACLE) {
            
        }
        $result = $this->q->fast($sql);
        if ($result) {
            $total = intval($this->q->numberRows($result));
            if ($total > 0) {
                $row = $this->q->fetchAssoc($result);
                if (is_array($row)) {
                    $systemSettingDateFormat = $row['systemSettingDateFormat'];
                }
            }
        }
        return $systemSettingDateFormat;
    }

    /**
     * Mirror getInternetProtocolAddress
     * @return \icore\getInternetProtocolAddress
     */
    public function getIpAddress() {
        return $this->getInternetProtocolAddress();
    }

    /**
     * Retrieves the best guess of the client's actual IP address.
     * Takes into account numerous HTTP proxy headers due to variations
     * in how different ISPs handle IP addresses in headers between hops.
     * @return mixed;
     */
    public function getInternetProtocolAddress() {
        // check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->getValidateInternetProtocol($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ipList as $ip) {
                if ($this->getValidateInternetProtocol($ip)){
                    return $ip;
                }
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->getValidateInternetProtocol($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->getValidateInternetProtocol($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->getValidateInternetProtocol($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }
        if (!empty($_SERVER['HTTP_FORWARDED']) && $this->getValidateInternetProtocol($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * @param string $ip
     * @return bool
     */
    public function getValidateInternetProtocol($ip) {
        if (filter_var(
                        $ip, FILTER_getValidateInternetProtocol, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE |
                        FILTER_FLAG_NO_RES_RANGE
                ) === false
        ){
            return false;
        }
        $this->ip = $ip;
        return true;
    }

    /**
     * Calculate File Size
     * @param string $size
     * @return float|string
     */
    public function getFileSize($size) {
        $sizeOriginal = $size;
        $size = round($size / 1024, -1);
        if (strlen(size) < 6) {
            $size = $size . " KB ";
        }
        if (strlen($size) > 6) {
            $size = ($size / 1000) . " MB";
        }
        if ($size == 0) {
            $size = $sizeOriginal . " B ";
        }
        return ($size);
    }

    /**
     * Block of html error message
     * @param string $message
     * @return string
     */
    function exceptionMessageReturn($message) {
        $this->message = $message;
        if (strlen($this->message) > 0) {
            return "<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><img src='./images/icons/smiley-nerd.png'> " . $this->message . "</div>";
        }
    }

    /**
     * Block of html error message
     * @param string $message .Message of the error
     */
    function exceptionMessageArray($message) {
        $this->message = $message;
        if (is_array($message)) {
            echo "<pre class=\"prettyprint linenums\" style=\"margin-bottom: 9px;\">" . print_r($message) . "</pre>";
        } else {
            echo "<pre class=\"prettyprint linenums lang-sql\" id=\"sql-lang\" style=\"margin-bottom: 9px;\">" . ($message) . "</pre>";
        }
    }

    /**
     * Block of html error message
     * @param string $message .Message of the error
     */
    function exceptionMessageObject($message) {
        $this->message = $message;
        if (is_object($message)) {
            echo "<pre class=\"prettyprint linenums\" style=\"margin-bottom: 9px;\">" . var_dump($message) . "</pre>";
        }
    }

    /**
     * Create a line graph using high chart
     *
     * @param int $containerName
     * @param int $titleXAxis
     * @param int $titleYAxis
     * @param int $xAxis
     * @param null $series
     */
    function lineGraphs($containerName, $titleXAxis, $titleYAxis, $xAxis, $series = null) {
        echo "chart" . rand(0, 100) . " = new Highcharts.Chart({
                            chart: {
                                renderTo: '" . $containerName . "',
                                zoomType: 'xy'
                            },
                            title: {
                                text: '" . $titleXAxis . "'
                            },

                            xAxis: [" . $xAxis . "],
                            yAxis: {
                title: {
                    text: '" . $titleYAxis . "'
                }
            },
                            legend: {
                                layout: 'vertical',
                                align: 'left',
                                x: 60,
                                verticalAlign: 'top',
                                y: 60,
                                floating: true,
                                backgroundColor: '#FFFFFF'
                            },
                            series: [" . $series . "]
                        });";
    }

    /**
     * Ipay88 Signature
     *
     * @param string $source
     * @return string
     */
    function iPay88_signature($source) {
        return base64_encode(hex2bin(sha1($source)));
    }

    /**
     * Return Ipay88 hex
     *
     * @param string $hexSource
     * @return string
     */
    function hex2bin($hexSource) {
        $bin = "";
        for ($i = 0; $i < strlen($hexSource); $i = $i + 2) {
            $bin .= chr(hexdec(substr($hexSource, $i, 2)));
        }
        return $bin;
    }

    /**
     * Ipay88 Respond String
     *
     * @param string $MerchantCode
     * @param string $RefNo
     * @param int $Amount
     * @return string
     */
    function Requery($MerchantCode, $RefNo, $Amount) {
        $errno=null;
        $errstr=null;
        $query = "http://www.mobile88.com/epayment/enquiry.asp?MerchantCode=" .
                $MerchantCode . "&RefNo=" . str_replace(" ", "%20", $RefNo) .
                "&Amount=" . $Amount;

        $url = parse_url($query);
        $host = $url["host"];
        $path = $url["path"] . "?" . $url["query"];
        $timeout = 1;
        $fp = fsockopen($host, 80, $errno, $errstr, $timeout);
        $buffer = null;
        if ($fp) {
            fputs($fp, "GET $path HTTP/1.0\nHost: " . $host . "\n\n");
            while (!feof($fp)) {
                $buffer .= fgets($fp, 128);
            }
            $lines = explode("\n", $buffer);
            $Result = $lines[count($lines) - 1];
            fclose($fp);
        } else {
            // enter error handing code here
        }
        if (!empty($Result)) {
            return $Result;
        }
    }

    /**
     * Return Information column in MYSQL database only!
     * @param string $tableName Table/Tablespace Name
     * @param string $columnName Column Name
     * @return string
     */
    function getDescribeColumnMysql($tableName, $columnName) {
        $infoColumn = array();

        $sql = "DESCRIBE " . $tableName . " " . $columnName . " ";
        $result = $this->q->fast($sql);
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $findChar = 'char';
                $posChar = strpos($row['Type'], $findChar);
                if ($posChar !== false) {

                    $infoColumn['formType'] = "text";
                }
                $findText = 'text';
                $posText = strpos($row['Type'], $findText);
                if ($posText !== false) {
                    $infoColumn['formType'] = "text";
                }
                $findInt = 'int';
                $posInt = strpos($row['Type'], $findInt);
                if ($posInt !== false) {
                    $infoColumn['formType'] = "int";
                }
                $findDate = 'date';
                $posDate = strpos($row['Type'], $findDate);
                if ($posDate !== false) {
                    $infoColumn['formType'] = "date";
                }
                $findDateTime = 'datetime';
                $posDateTime = strpos($row['Type'], $findDateTime);
                if ($posDateTime !== false) {
                    $infoColumn['formType'] = "datetime";
                }
                $findTiny = 'tiny';
                $posTiny = strpos($row['Type'], $findTiny);
                if ($posTiny !== false) {
                    $infoColumn['formType'] = "tiny";
                }

                $findDouble = 'double';
                $posDouble = strpos($row['Type'], $findDouble);
                if ($posDouble !== false) {
                    $infoColumn['formType'] = "double";
                }
                if ($infoColumn['formType'] == '' || $infoColumn['formType'] == null) {
                    $infoColumn['formType'] = " miau Tell me this type : [" . $row['Type'] . "] [" . $row['Field'] . "]<br>";
                }
            }
        }
        return $infoColumn;
    }
   
    /**
     * Return Salt
     * @return int $salt salt
     */
    function getSalt() {
        return $this->staffName;
    }

    /**
     * Set Salt
     * @param string $value Salt
     * @return \Core\ConfigClass
     */
    function setSalt($value) {
        $this->salt = $value;
        return $this;
    }

    /**
     * Return Key
     * @return int $key Key
     */
    function getKey() {
        return $this->key;
    }

    /**
     * Set Key
     * @param string $value Key
     * @return \Core\ConfigClass
     */
    function setKey($value) {
        $this->key = $value;
        return $this;
    }

    /**
     * Return Staff Name
     * @return int $staffName Staff Name
     */
    function getStaffName() {
        return $this->staffName;
    }

    /**
     * Set Staff Name
     * @param string $value Staff Name
     * @return \Core\ConfigClass
     */
    function setStaffName($value) {
        $this->staffName = $value;
        return $this;
    }

    /**
     * Set Connection
     *
     * @return string
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Set Connection
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setConnection($value) {
        $this->connection = $value;
        return $this;
    }

    /**
     * Return Database
     *
     * @return string
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * Set Database
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setDatabase($value) {
        $this->database = $value;
        return $this;
    }

    /**
     * Return Username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set Username
     *
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setUsername($value) {
        $this->username = $value;
        return $this;
    }

    /**
     * Return Password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set Password
     *
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setPassword($value) {
        $this->password = $value;
        return $this;
    }

    /**
     * Return Language Primary Key
     * @return int
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    /**
     * Set LanguagePrimary Key
     * @param int $value Value
     * @return \Core\ConfigClass
     */
    public function setLanguageId($value) {
        $this->languageId = $value;
        return $this;
    }
    
    /**
     * Return Country Currency Code
     * @return int
     */
    public function getCountryCurrencyCode() {
        return $this->countryCurrencyCode;
    }

    /**
     * Set Country Currency Code
     * @param int $value Value Value
     * @return \Core\ConfigClass
     */
    public function setCountryCurrencyCode($value) {
        $this->countryCurrencyCode = $value;
        return $this;
    }

    /**
     * Return Country Currency Locale.E.g Malaysia my-En Will output Two hundred three ringgit
     * @return int
     */
    public function getCountryCurrencyLocale() {
        return $this->countryCurrencyLocle;
    }

    /**
     * Set Country Currency Locale
     * @param int $value Value Value
     * @return \Core\ConfigClass
     */
    public function setCountryCurrencyLocale($value) {
        $this->countryCurrencyLocale = $value;
        return $this;
    }

    /**
     * Return Role Description
     * @return int $roleDescription Role Description
     */
    function getRoleDesc() {
        return $this->roleDescription;
    }

    /**
     * Set Role Description
     * @param string $value Role Description
     * @return \Core\ConfigClass
     */
    function setRoleDesc($value) {
        $this->roleDescription = $value;
        return $this;
    }

    /**
     * Return Is Admin
     * @return bool
     */
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    /**
     * Set Is Admin Value
     * @param bool $value Value
     * @return \Core\ConfigClass
     */
    public function setIsAdmin($value) {
        $this->isAdmin = $value;
        return $this;
    }

    /**
     * Return Field Query
     * @return string
     */
    public function getFieldQuery() {
        return $this->fieldQuery;
    }

    /**
     * Set Filter Query
     *
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setFieldQuery($value) {
        $this->fieldQuery = $value;
        return $this;
    }

    /**
     * Return Grid Query Filtering
     *
     * @return string
     */
    public function getGridQuery() {
        return $this->gridQuery;
    }

    /**
     * Set Grid Query Filtering
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setGridQuery($value) {
        $this->gridQuery = $value;
        return $this;
    }

    /**
     * Return Start Number Per Page
     * @return int
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Set Start Number Per Page
     * @param int $value Value
     * @return \Core\ConfigClass
     */
    public function setStart($value) {
        $this->start = $value;
        return $this;
    }

    /**
     * Return limit Per Page
     * @return int
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * Set limit Per Page
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setLimit($value) {
        $this->limit = $value;
        return $this;
    }

    /**
     * Return Sql Statement Ordering
     * @return string
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * Set Sql Statement Ordering
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setOrder($value) {
        $this->order = $value;
        return $this;
    }

    /**
     * Return Sql Statement Sorting
     * @return string
     */
    public function getSortField() {
        return $this->sortField;
    }

    /**
     * Set Sql Statement Sorting
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setSortField($value) {
        $this->sortField = $value;
        return $this;
    }

    /**
     * Return Default Language
     * @return int $defaultLanguageId Default Language
     */
    public function getDefaultLanguageId() {
        return $this->defaultLanguageId;
    }

    /**
     * Set Default Language
     * @param int $defaultLanguageId Default Language
     * @return \Core\ConfigClass
     */
    public function setDefaultLanguageId($defaultLanguageId) {
        $this->defaultLanguageId = $defaultLanguageId;
        return $this;
    }

    /**
     * Return Request Database
     * @return string $requestDatabase
     */
    public function getRequestDatabase() {
        return $this->requestDatabase;
    }

    /**
     * Set Request Database
     * @param string $requestDatabase
     * @return \Core\ConfigClass
     */
    public function setRequestDatabase($requestDatabase) {
        $this->requestDatabase = $requestDatabase;
        return $this;
    }

    /**
     * Return Character Query
     * @return string
     */
    public function getCharacterQuery() {
        return $this->characterQuery;
    }

    /**
     * Set Character Query
     * @param string $value Character Query
     * @return \Core\ConfigClass
     */
    public function setCharacterQuery($value) {
        $this->characterQuery = $value;
        return $this;
    }

    /**
     * Return Date Range Start Query
     * @return string
     */
    public function getDateRangeStartQuery() {
        return $this->dateRangeStartQuery;
    }

    /**
     * Set Date Range Start Query
     * @param string $dateRangeStartQuery
     * @return \Core\ConfigClass
     */
    public function setDateRangeStartQuery($dateRangeStartQuery) {
        $this->dateRangeStartQuery = $dateRangeStartQuery;
        return $this;
    }

    /**
     * Return Date Range End Query
     * @return string
     */
    public function getDateRangeEndQuery() {
        return $this->dateRangeEndQuery;
    }

    /**
     * Set Date Range End Query
     * @param string $dateRangeEndQuery
     * @return \Core\ConfigClass
     */
    public function setDateRangeEndQuery($dateRangeEndQuery) {
        $this->dateRangeEndQuery = $dateRangeEndQuery;
        return $this;
    }

    /**
     * Return Date Range Type Query
     * @return string
     */
    public function getDateRangeTypeQuery() {
        return $this->dateRangeTypeQuery;
    }

    /**
     * Set Date Range Type Query
     * @param string $value Date Range Type Query
     * @return \Core\ConfigClass
     */
    public function setDateRangeTypeQuery($value) {
        $this->dateRangeTypeQuery = $value;
        return $this;
    }

    /**
     * Return Date Range Extra Type
     * @return string
     */
    public function getDateRangeExtraTypeQuery() {
        return $this->dateRangeExtraTypeQuery;
    }

    /**
     * Set Date Range Extra Type
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setDateRangeExtraTypeQuery($value) {
        $this->dateRangeExtraTypeQuery = $value;
        return $this;
    }

    /**
     * Return Start Day
     *
     * @return int
     */
    public function getStartDay() {
        return $this->startDay;
    }

    /**
     * Set Start Day
     * @param int $startDay
     * @return \Core\ConfigClass
     */
    public function setStartDay($startDay) {
        $this->startDay = $startDay;
        return $this;
    }

    /**
     * Return Start Week
     * @return int
     */
    public function getStartWeek() {
        return $this->startWeek;
    }

    /**
     * Set Start Week
     * @param int $startWeek
     * @return \Core\ConfigClass
     */
    public function setStartWeek($startWeek) {
        $this->startWeek = $startWeek;
        return $this;
    }

    /**
     * Return Start Month
     * @return int
     */
    public function getStartMonth() {
        return $this->startMonth;
    }

    /**
     * Return Start Month
     * @param int $startMonth
     * @return \Core\ConfigClass
     */
    public function setStartMonth($startMonth) {
        $this->startMonth = $startMonth;
        return $this;
    }

    /**
     * Return Start Year
     * @return int
     */
    public function getStartYear() {
        return $this->startYear;
    }

    /**
     * Set Start Year
     * @param int $startYear
     * @return \Core\ConfigClass
     */
    public function setStartYear($startYear) {
        $this->startYear = $startYear;
        return $this;
    }

    /**
     * Return End Day
     * @return int
     */
    public function getEndDay() {
        return $this->endDay;
    }

    /**
     * Set End Day
     * @param int $endDay
     * @return \Core\ConfigClass
     */
    public function setEndDay($endDay) {
        $this->endDay = $endDay;
        return $this;
    }

    /**
     * Return End Week
     * @return int
     */
    public function getEndWeek() {
        return $this->endWeek;
    }

    /**
     * Set End Week
     * @param int $endWeek
     * @return \Core\ConfigClass
     */
    public function setEndWeek($endWeek) {
        $this->endWeek = $endWeek;
        return $this;
    }

    /**
     * Return End Month
     * @return int
     */
    public function getEndMonth() {
        return $this->endMonth;
    }

    /**
     * Set End Month
     * @param int $endMonth End Month
     * @return \Core\ConfigClass
     */
    public function setEndMonth($endMonth) {
        $this->endMonth = $endMonth;
        return $this;
    }

    /**
     * Return End Year
     * @return int
     */
    public function getEndYear() {
        return $this->endYear;
    }

    /**
     * Set End Year
     * @param int $endYear
     * @return \Core\ConfigClass
     */
    public function setEndYear($endYear) {
        $this->endYear = $endYear;
        return $this;
    }

    /**
     * Return Value
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set Value
     * @param string $value Value
     * @return \Core\ConfigClass
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * Get Type
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set Type
     * @param string $value Type
     * @return \Core\ConfigClass
     */
    public function setType($value) {
        $this->type = $value;
        return $this;
    }

    /**
     * Return Filter Month
     * @return int
     */
    public function getFilterMonth() {
        return $this->filterMonth;
    }

    /**
     * Set Filter Month
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setFilterMonth($value) {
        $this->filterMonth = $value;
        return $this;
    }

    /**
     * Return Filter Year
     * @return int
     */
    public function getFilterYear() {
        return $this->filterYear;
    }

    /**
     * Set Filter Year
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setFilterYear($value) {
        $this->filterYear = $value;
        return $this;
    }

    /**
     * Return PagePrimary Key
     *
     * @return int
     */
    public function getPageId() {
        return $this->pageId;
    }

    /**
     * Set PagePrimary Key
     * @param int $value
     * @return \Core\ConfigClass
     */
    public function setPageId($value) {
        $this->pageId = $value;
        return $this;
    }

    /**
     * Set Page Type.folder or leaf
     *
     * @return string
     */
    public function getPageType() {
        return $this->pageType;
    }

    /**
     * Set Page Output.html or json
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setPageType($value) {
        $this->pageType = $value;
        return $this;
    }

    /**
     * Return Page Output application,folder or leaf
     * @return string
     */
    public function getPageOutput() {
        return $this->pageOutput;
    }

    /**
     * Set Page Output .
     * html or json
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setPageOutput($value) {
        $this->pageOutput = $value;
        return $this;
    }

    /**
     * Return Service Output.html or json
     * @return string
     */
    public function getServiceOutput() {
        return $this->serviceOutput;
    }

    /**
     * Set Service Output
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setServiceOutput($value) {
        $this->serviceOutput = $value;
        return $this;
    }

    /**
     * Return Controller Filename
     * @return string
     */
    public function getControllerFilename() {
        return $this->controllerFilename;
    }

    /**
     * Set Controller Filename
     * @param string $value Controller Filename
     * @return \Core\ConfigClass
     */
    public function setControllerFilename($value) {
        $this->controllerFilename = $value;
        return $this;
    }

    /**
     * Return Controller Path
     * @return string
     */
    public function getControllerPath() {
        return $this->controllerPath;
    }

    /**
     * Set Controller Path
     * @param string $value Controller Path
     * @return \Core\ConfigClass
     */
    public function setControllerPath($value) {
        $this->controllerPath = $value;
        return $this;
    }

    /**
     * Return Controller Path
     * @return string
     */
    public function getControllerDetailPath() {
        return $this->controllerDetailPath;
    }

    /**
     * Set Controller Path
     * @param string $value Controller Path
     * @return \Core\ConfigClass
     */
    public function setControllerDetailPath($value) {
        $this->controllerDetailPath = $value;
        return $this;
    }

    /**
     * Return View Path
     * @return string
     */
    public function getViewPath() {
        return $this->viewPath;
    }

    /**
     * Set View Path
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setViewPath($value) {
        $this->viewPath = $value;
        return $this;
    }

    /**
     * Return View Path
     * @return string
     */
    public function getViewDetailPath() {
        return $this->viewDetailPath;
    }

    /**
     * Set View Path
     * @param string $value View Path
     * @return \Core\ConfigClass
     */
    public function setViewDetailPath($value) {
        $this->viewDetailPath = $value;
        return $this;
    }

    /**
     * Return View Filename
     * @return string
     */
    public function getViewFilename() {
        return $this->viewFilename;
    }

    /**
     * Set View Filename
     * @param string $value
     * @return \Core\ConfigClass
     */
    public function setViewFilename($value) {
        $this->viewFilename = $value;
        return $this;
    }

    /**
     * Return Service Path
     * @return string
     */
    public function getServicePath() {
        return $this->viewPath;
    }

    /**
     * Set Service Path
     *
     * @param string $value Service Path
     * @return \Core\ConfigClass
     */
    public function setServicePath($value) {
        $this->servicePath = $value;
        return $this;
    }

    /**
     * Return Security Token
     * @return string
     */
    function getSecurityToken() {
        return $this->securityToken;
    }

    /**
     * Set Controller Filename
     *
     * @param string $value Filename
     * @return \Core\ConfigClass
     */
    function setSecurityToken($value) {
        $this->securityToken = $value;
        return $this;
    }

    /**
     * Return Current Database
     * @return string
     */
    function getCurrentDatabase() {
        return $this->currentDatabase;
    }

    /**
     * Set Current Database Name
     * @param string $value
     * @return \Core\ConfigClass
     */
    function setCurrentDatabase($value) {
        $this->currentDatabase = $value;
        return $this;
    }

    /**
     * Return Current Table
     * @return string|array
     */
    function getCurrentTable() {
        return $this->currentTable;
    }

    /**
     * Set Current Table Name.Field  to lower because to prevent PhpStorm Error
     * @param string $value
c     * @return \Core\ConfigClass
     */
    function setCurrentTable($value) {
        if(!is_array($value)) {
            if (strlen($value) > 0) {
                $this->currentTable = strtolower($value);
            } else {
                $this->currentTable = $value;
            }
        } else {
			// this is array
			$this->currentTable= $value;
		}
        return $this;
    }

    function changeZero($value) {
        if (strlen($value) == 1) {
            $value = "0" . $value;
        }
        return $value;
    }

    /**
     * Report mode Either it Was Normal Excel 2007(zip),office 2003
     * Compatibility pack,pdf,csv
     * return string $reportMode
     */
    function getReportMode() {
        return $this->reportMode;
    }

    /**
     * Set Report mode Either it Was Normal Excel 2007(zip),office 2003
     * Compatibility pack,pdf,csv
     * @param string $value
     * @return \Core\ConfigClass
     */
    function setReportMode($value) {
        $this->reportMode = $value;
        return $this;
    }

    /**
     * Return Bool
     * @return bool
     */
    function getAudit() {
        return $this->audit;
    }

    /**
     * Set Audit
     *
     * @param bool $value Audit
     * @return \Core\ConfigClass
     */
    function setAudit($value) {
        $this->audit = $value;
        return $this;
    }

    /**
     * Return Log
     * @return int
     */
    function getLog() {
        return $this->log;
    }

    /**
     * Set Log
     * @param bool $value Log
     * @return \Core\ConfigClass
     */
    function setLog($value) {
        $this->log = $value;
        return $this;
    }

    /**
     * Return Notification
     * @return string
     */
    function getNotification() {
        return $this->notification;
    }

    /**
     * Set Notification
     * @param string $value Notification
     * @return \Core\ConfigClass
     */
    function setNotification($value) {
        $this->notification = $value;
        return $this;
    }

    /**
     * Set Audit Filter
     * @return int
     */
    function getAuditFilter() {
        return $this->auditFilter;
    }

    /**
     * Return Audit Filter
     *
     * @param int $value Audit Filter
     * @return \Core\ConfigClass
     */
    function setAuditFilter($value) {
        $this->auditFilter = $value;
        return $this;
    }

    /**
     * Set Audit Column
     *
     * @return string
     */
    function getAuditColumn() {
        return $this->auditColumn;
    }

    /**
     * Return Audit Column
     *
     * @param string $value Audit Column
     * @return \Core\ConfigClass
     */
    function setAuditColumn($value) {
        $this->auditColumn = $value;
        return $this;
    }

    /**
     * Set Report Title
     *
     * @return string
     */
    function getReportTitle() {
        return $this->reportTitle;
    }

    /**
     * Return Report Title
     *
     * @param string $value Report Title
     * @return \Core\ConfigClass
     */
    function setReportTitle($value) {
        $this->reportTitle = $value;
        return $this;
    }

    /**
     * Set Filename
     *
     * @return string
     */
    function getFilename() {
        return $this->filename;
    }

    /**
     * Set Filename
     * @param string $value Filename
     * @return \Core\ConfigClass
     */
    function setFilename($value) {
        $this->filename = $value;
        return $this;
    }

    /**
     * Return Service Filename
     * @return string $serviceFilename
     */
    public function getServiceFilename() {
        return $this->serviceFilename;
    }

    /**
     * Set Service Filename
     * @param string $serviceFilename
     * @return \Core\ConfigClass
     */
    public function setServiceFilename($serviceFilename) {
        $this->serviceFilename = $serviceFilename;
        return $this;
    }

    /**
     * Return Fake  Document Root
     * @return string $fakeDocumentRoot
     */
    public function getFakeDocumentRoot() {
        // start fake document root. it's absolute path
        $x = addslashes(realpath(__FILE__));
        // auto detect if \\ consider come from windows else / from linux

        $pos = strpos($x, "\\");
        if ($pos !== false) {
            $d = explode("\\", $x);
        } else {

            $d = explode("/", $x);
        }
        $newPath = null;
        for ($i = 0; $i < count($d); $i++) {
            // if find the library or package then stop
            if ($d[$i] == 'library' || $d[$i] == 'package') {
                break;
            }
            $newPath[] .= $d[$i] . "/";
        }
        $fakeDocumentRoot = null;
        for ($z = 0; $z < count($newPath); $z++) {
            $fakeDocumentRoot .= $newPath[$z];
        }
        return $this->fakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
    }

    /**
     * Set Fake Document root
     */
    public function setFakeDocumentRoot() {
        // start fake document root. it's absolute path
        $x = addslashes(realpath(__FILE__));
        // auto detect if \\ consider come from windows else / from linux

        $pos = strpos($x, "\\");
        if ($pos !== false) {
            $d = explode("\\", $x);
        } else {

            $d = explode("/", $x);
        }
        $newPath = null;
        for ($i = 0; $i < count($d); $i++) {
            // if find the library or package then stop
            if ($d[$i] == 'library' || $d[$i] == 'package') {
                break;
            }
            $newPath[] .= $d[$i] . "/";
        }
        $fakeDocumentRoot = null;
        for ($z = 0; $z < count($newPath); $z++) {
            $fakeDocumentRoot .= $newPath[$z];
        }
        $this->fakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
    }

    /**
     * Return Internet Protocol Address
     * @return string ip
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set Internet Protocol Address
     * @param string $ip
     * @return \Core\ConfigClass
     */
    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Return Execute Time
     * @return string
     */
    public function getExecuteDate() {
        if ($this->getVendor() == self::MYSQL) {
            $this->setExecuteDate("'" . date("Y-m-d") . "'");
        } else if ($this->getVendor() == self::MSSQL) {
            $this->setExecuteDate("'" . date("Y-m-d") . "'");
        } else if ($this->getVendor() == self::ORACLE) {
            $this->setExecuteDate("to_date('" . date("Y-m-d") . "','YYYY-MM-DD')");
        }
        return $this->executeDate;
    }

    /**
     * Set Execute Date/Time
     * @param string $executeTime
     * @return \Core\ConfigClass
     */
    public function setExecuteDate($executeDate) {
        $this->executeDate = $executeDate;
        return $this;
    }

    /**
     * Return Execute Date/Time
     * @return string
     */
    public function getExecuteTime() {
        if ($this->getVendor() == self::MYSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s") . "'");
        } else if ($this->getVendor() == self::MSSQL) {
            $this->setExecuteTime("'" . date("Y-m-d H:i:s.u") . "'");
        } else if ($this->getVendor() == self::ORACLE) {
            $this->setExecuteTime("to_date('" . date("Y-m-d H:i:s") . "','YYYY-MM-DD HH24:MI:SS')");
        }
        return $this->executeTime;
    }

    /**
     * Set Execute Time
     * @param string $executeTime
     * @return \Core\ConfigClass
     */
    public function setExecuteTime($executeTime) {
        $this->executeTime = $executeTime;
        return $this;
    }

    /**
     * Return Role Description
     * @return string
     */
    public function getRoleDescription() {
        return $this->roleDescription;
    }

    /**
     * Set Role Description
     * @param string $roleDescription
     * @return \Core\ConfigClass
     */
    public function setRoleDescription($roleDescription) {
        $this->roleDescription = $roleDescription;
        return $this;
    }

    /**
     * Set Finance Period
     * @param int $financePeriod Finance Period
     * @return \Core\ConfigClass
     */
    public function setFinancePeriod($financePeriod) {
        $this->financePeriod = $financePeriod;
        return $this;
    }

    /**
     * Set Finance Period
     * @return int
     */
    public function getFinancePeriod() {
        return $this->financePeriod;
    }

    /**
     * Return Finance Year
     * @param int $financeYearId Financia Year Primary Key
     * @return \Core\ConfigClass
     */
    public function setFinanceYearId($financeYearId) {
        $this->financeYearId = $financeYearId;
        return $this;
    }

    /**
     * Return Finance Year
     * @return int
     */
    public function getFinanceYearId() {
        return $this->financeYearId;
    }

    /**
     * Return Browser Compability
     * @return \Core\ConfigClass
     */
    public function setBrowserCompability() {

        $data = $this->getBrowserInformation();
        if ($data['ua_family'] == 'IE') {
            switch ($data['ua_name']) {
                case 'IE5.5':
                case 'IE6.0':
                    $browserCompability = 'ERROR';
                    break;
                case 'IE7.0':
                case 'IE8.0':
                    $browserCompability = FALSE;
                    break;
                case 'IE9.0':
                case 'IE10.0':
                case 'IE11.0':
                    $browserCompability = TRUE;
                    break;
                default:
                    // send message ie6 break
                    $browserCompability = FALSE;
            }
        } else {
            // don't check other then Internet Explorer.
            $browserCompability = TRUE;
        }
        $this->browserCompability = $browserCompability;
        return $this;
    }

    /**
     * Browser Compability Internet Explorer Or not
     * @return bool 
     */
    public function getBrowserCompability() {
        return $this->browserCompability;
    }

    /**
     * Version Compability Internet Explorer Or not
     * @return \Core\ConfigClass
     */
    public function setVersionCompability() {
        // redirect version to INTERNET EXPLORER only
        if ($this->getBrowserCompability() === TRUE) {
            $this->versionCompability = 'v2';
        } else if ($this->getBrowserCompability() === FALSE) {
            $this->versionCompability = 'v3';
        } else if ($this->getBrowserCompability() == 'ERROR') {
            $this->versionCompability = 'redirect';
        } else {
            $this->versionCompability = 'v3';
        }
        return $this;
    }

    /**
     * Return Version Compability
     * @return string
     */
    public function getVersionCompability() {
        return $this->versionCompability;
    }

    /**
     * Create Record From Database
     */
    abstract protected function create();

    /**
     * Read Record From Database
     */
    abstract protected function read();

    /**
     * Update Record From Database
     */
    abstract protected function update();

    /**
     * Delete Record From Database
     */
    abstract protected function delete();

    /**
     * Microsoft Excel 2007 Output File Generation
     */
    abstract protected function excel();
}

?>
