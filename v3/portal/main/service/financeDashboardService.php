<?php

namespace Core\Portal\Main\Dashboard\Finance\Service;

// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class generalLedgerLinkService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class generalLedgerLinkService extends ConfigClass {

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
    public function __construct() {
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * @param string $leafCode
     * @return int
     */
    public function getGridLeafCode($leafCode) {
        $sql = null;
        $leafId = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `leafId`
            FROM   `leaf`
            WHERE  `companyId`     =   '" . $this->getCompanyId() . "'
            AND    `leafCode`      =   '" . $leafCode . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [leafId]
            FROM   [leaf]
            WHERE  [companyId]     =   '" . $this->getCompanyId() . "'
            AND    [leafCode]      =   '" . $leafCode . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT LEAFID
            FROM   LEAF
            WHERE  COMPANYID     =   '" . $this->getCompanyId() . "'
            AND    LEAFCODE      =   '" . $leafCode . "'";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $leafId = $row['leafId'];
        }
        return $leafId;
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

/**
 * Class DashboardGeneralLedgerService
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class chartOfAccountCategoryDashboardService extends ConfigClass {

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
     * Year
     * @var int
     */
    private $year;

    /**
     * Constructor
     */
    public function __construct() {
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        $this->getOverrideFinanceYear();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideFinanceYear() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financeyear`.`financeYearYear`
            FROM   `financesetting`
            JOIN   `financeyear`
            USING   (`companyId`,`financeYearId`)
            WHERE  `financesetting`.`companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [financeYear]
            ON     [financeSetting].[companyId]     =   [financeYear].[companyId]
            AND    [financeSetting].[financeYearId] =   [financeYear].[financeYearId]
            WHERE  [financeSetting].[companyId]     =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            JOIN   FINANCEYEAR
            ON     FINANCESETTING.COMPANYID     =   FINANCEYEAR.COMPANYID
            AND    FINANCESETTING.FINANCEYEARID =   FINANCEYEAR.FINANCEYEARID
            WHERE  FINANCESETTING.COMPANYID     =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setYear($row['financeYearYear']);
        }
    }

    /**
     * Return Either Figure / Total  Group By Chart Of Account Category -> Asset.Income,Equity,Expenses,Liability
     * @param string $chartOfAccountCategoryCode
     * @param null $mode
     * @return int|double
     */
    function getChartOfAccountCategory($chartOfAccountCategoryCode, $mode = null) {
        $sql = null;
        $total = 0;
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountCategoryCode` 	=	'" . $chartOfAccountCategoryCode . "'," . $filterBy . ",0)),0) as total
        FROM    `generalledger`
        WHERE   `companyId`                 =   '" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`)   =   '" . $this->getYear() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
        SELECT  COALESCE(   (
                                CASE WHEN [chartOfAccountCategoryCode] 	=	'" . $chartOfAccountCategoryCode . "'
                                THEN SUM('" . $filterBy . "') END
                             )
                ,0)
        FROM    [generalLedger]
        WHERE   [companyId]                 =   '" . $this->getCompanyId() . "'
		AND		YEAR([generalLedgerDate])   =   '" . $this->getYear() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
        SELECT  IFNULL(SUM(IF(CHARTOFACCOUNTCATEGORYCODE 	=	'" . $chartOfAccountCategoryCode . "'," . $filterBy . ",0)),0)
        FROM    GENERALLEDGER
        WHERE   COMPANYID                   =   '" . $this->getCompanyId() . "'
		AND		YEAR(`GENERALLEDGERDATE)    =   '" . $this->getYear() . "'";
                }
            }
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    /**
     * @return int
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

class chartOfAccountTypeDashboardService extends ConfigClass {

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
     * Year
     * @var int
     */
    private $year;

    /**
     * Constructor
     */
    public function __construct() {
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        // default for portal visitor
        $this->translate = array();

        if (isset($_SESSION['companyId'])) {
            $this->setRoleId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }
        $this->getOverrideFinanceYear();
    }

    /**
     * Get Default Company Country
     */
    public function getOverrideFinanceYear() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `financeyear`.`financeYearYear`
            FROM   `financesetting`
            JOIN   `financeyear`
            USING   (`companyId`,`financeYearId`)
            WHERE  `financesetting`.`companyId`     =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT [financeSetting].[financeYearId]
            FROM   [financeSetting]
            JOIN   [financeYear]
            ON     [financeSetting].[companyId]     =   [financeYear].[companyId]
            AND    [financeSetting].[financeYearId] =   [financeYear].[financeYearId]
            WHERE  [financeSetting].[companyId]     =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT FINANCESETTING.FINANCEYEARID AS \"financeYearId\"
            FROM   FINANCESETTING
            JOIN   FINANCEYEAR
            ON     FINANCESETTING.COMPANYID     =   FINANCEYEAR.COMPANYID
            AND    FINANCESETTING.FINANCEYEARID =   FINANCEYEAR.FINANCEYEARID
            WHERE  FINANCESETTING.COMPANYID     =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $this->setYear($row['financeYearYear']);
        }
    }

    /**
     * @param string $chartOfAccountTypeCode
     * @param null|string $mode
     * @return int|double
     */
    function getChartOfAccountType($chartOfAccountTypeCode, $mode = null) {
        $sql = null;
        $total = 0;
        if ($mode == 1) {
            $filterBy = "`localAmount`";
        } else {
            $filterBy = "1";
        }
        // might be  the type is not exist  . if not exist  take dimension code instead
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `chartOfAccountTypeCode`
            FROM    `chartofaccounttype`
            WHERE   `chartOfAccountTypeCode`='" . $chartOfAccountTypeCode . "'
            AND     `companyId` ='" . $this->getCompanyId() . "'
            ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $totalRecord = $this->q->numberRows($result);
        if ($totalRecord > 0) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
        SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'" . $chartOfAccountTypeCode . "'," . $filterBy . ",0)),0) as `total`
        FROM    `generalledger`
        WHERE   `companyId`='" . $this->getCompanyId() . "'
		AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        
                    }
                }
            }
            try {
                $result = $this->q->fast($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            if ($result) {
                if ($this->q->numberRows($result)) {
                    $row = $this->q->fetchArray($result);
                    $total = $row['total'];
                }
            }
        } else {
            // take dimension code instead
        }
        return $total;
    }

    /**
     * Return Year
     * @return int
     */
    function getYear() {
        return $this->year;
    }

    /**
     * Set Year
     * @param int $value
     */
    function setYear($value) {
        $this->year = $value;
    }

    /**
     * Create
     * @see config::create()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

/**
 * Class DashboardCashbookService
 * Analysis Data Based On Current Year
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class cashbookService extends ConfigClass {

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
        
    }

    public function getCashBalance() {
        /**
          $total = 0;
          $sql = null;
          if ($this->getVendor() == self::MYSQL) {
          $sql = "
          SELECT  IFNULL(SUM(IF(`chartOfAccountTypeCode` 	=	'" . $chartOfAccountTypeCode . "'," . $filterBy . ",0)),0) as `total`
          FROM    `generalledger`
          WHERE   `companyId`='" . $this->getCompanyId() . "'
          AND		YEAR(`generalLedgerDate`) =  '" . $this->getYear() . "'";
          } else {
          if ($this->getVendor() == self::MSSQL) {

          } else {
          if ($this->getVendor() == self::ORACLE) {

          }
          }
          }
          try {
          $result = $this->q->fast($sql);
          } catch (\Exception $e) {
          $this->q->rollback();
          echo json_encode(array("success" => false, "message" => $e->getMessage()));
          exit();
          }
          if ($result) {
          if ($this->q->numberRows($result)) {
          $row = $this->q->fetchArray($result);
          $total = $row['total'];
          }
          }
          return $total;
         * */
    }

    public function getCashReceipt() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getCashPayment() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getBankBalance() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getNewCollection() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getCollectionPosted() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getCollectionCancelled() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getNewPaymentVoucher() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getPaymentVoucherPrinted() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getPaymentVoucherPosted() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    public function getPaymentVoucherCancelled() {
        $total = 0;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            
        } else {
            if ($this->getVendor() == self::MSSQL) {
                
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            if ($this->q->numberRows($result)) {
                $row = $this->q->fetchArray($result);
                $total = $row['total'];
            }
        }
        return $total;
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

/**
 * Class DashboardAccountReceivableService
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class accountReceivableService extends ConfigClass {

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
        
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

/**
 * Class DashboardAccountPayableService
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class accountPayableService extends ConfigClass {

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
        
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

/**
 * Class Dashboard Project Accounting Service
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class projectAccountingService extends ConfigClass {

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
        
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

/**
 * Class Bank Service
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Finance\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class bankService extends ConfigClass {

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
        
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Create
     * @see config::read()
     */
    public function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public function update() {
        
    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel() {
        
    }

}

?>
