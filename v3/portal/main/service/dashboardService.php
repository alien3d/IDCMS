<?php

namespace Core\Portal\Dashboard\Portal\Service;

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
 * Class DashboardBrowserService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Portal\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DashboardBrowserService extends ConfigClass {

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
     * Browser Type
     * @var mixed
     */
    public $browserType;

    /**
     * Constructor
     */
    function __construct() {
        $this->browserType = array(
            "robot",
            "browser",
            "mobile browser",
            "email client",
            "wap browser",
            "offline browser",
            "ua anonymizer",
            "library",
            "other"
        );
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Get The Browser Type
     * @param array|mixed|string $table log or staffWebAccess
     * @param null|int $staffId
     * @return array
     * @throws \Exception
     */
    function getDistinctBrowserType($table, $staffId = null) {
        $data = array();
        $sql = "
        SELECT  COUNT( * ) AS `Rows` , 
                `ua_type` 
        FROM    `" . $table . "`";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= "
        GROUP BY `ua_type`
        ORDER BY `ua_type`";
        $result = $this->q->fast($sql);
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($row['ua_type'] == 'robot') {
                    $data['robot'] = $row['Rows'];
                } else {
                    if ($row['ua_type'] == 'browser') {
                        $data['browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'mobile browser') {
                        $data['mobile browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'email client') {
                        $data['email client'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'wap browser') {
                        $data['wap browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'offline browser') {
                        $data['offline browser'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'ua anonymizer') {
                        $data['ua anonymizer'] = $row['Rows'];
                    } elseif ($row['ua_type'] == 'library') {
                        $data['library'] = $row['Rows'];
                    } else {
                        if ($row['ua_type'] == 'other') {
                            $data['other'] = $row['Rows'];
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Return Popular Browser
     * @param $table
     * @param null|int $staffId
     * @return array
     * @throws \Exception
     */
    function getPopularBrowser($table, $staffId = null) {
        $data = array();
        // firefox
        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `ua_family` = 'Firefox'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['firefox'] = $row['Rows'];

        // firefox
        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `ua_family` = 'Chrome'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['chrome'] = $row['Rows'];

        // firefox
        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `ua_family` = 'Opera'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['Opera'] = $row['Rows'];

        // firefox
        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE 1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `ua_family` = 'Safari'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['Safari'] = $row['Rows'];

        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE 1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `ua_family` = 'IE'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['IE'] = $row['Rows'];

        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `ua_family` not in ('IE','Safari','Opera','Chrome','Firefox');";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['others'] = $row['Rows'];

        return $data;
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
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package System
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DashboardOperatingSystemService extends ConfigClass {

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
     * Get popular operating system
     * @param string $table
     * @param null|int $staffId
     * @return mixed
     * @throws \Exception
     */
    public function getPopularOperatingSystem($table, $staffId = null) {
        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `os_family` = 'Windows'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['Windows'] = $row['Rows'];

        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `os_family` = 'Linux'";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['Linux'] = $row['Rows'];

        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `os_family` in ('Mac OS X','Mac OS');";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['mac'] = $row['Rows'];

        $sql = "select count(*) as Rows,
                `ua_type` 
        FROM    `" . $table . "`
        WHERE   1";
        if ($staffId) {
            $sql .= " AND `staffId`='" . $staffId . "'";
        }
        $sql .= " AND `os_family` not in  ('Windows','Linux','Mac OS X','Mac OS');";
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $data['others'] = $row['Rows'];
        return $data;
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
 * Class DashboardInternetProtocolService
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Portal\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DashboardInternetProtocolService extends ConfigClass {

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
 * Class DashboardTicketingService
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Dashboard\Portal\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DashboardTicketingService extends ConfigClass {

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
     * Return New Ticket
     * @return int $total Total
     * @throws \Exception
     */
    function getNewTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `isNew`     =   1
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [ticket]
            WHERE   [isNew]     =   1
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   ISNEW       =   1
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Review Ticket
     * @return int $total Total
     * @throws \Exception
     */
    function getReviewTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `isReview`  =   1
            AND     `companyId` =   '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [ticket]
            WHERE   [isReview]  =   1
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   ISREVIEW    =   1
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Solved Ticket
     * @return int $total Total
     * @throws \Exception
     */
    function getSolveTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `isSolve`=1
            AND     `companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [ticket]
            WHERE   [isSolve]   =   1
            AND     [companyId] =   '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   ISSOLVE     =   1
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Total Ticket
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [ticket]
            WHERE   [companyId] ='" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   COMPANYID ='" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
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
 * this is role setting files.This sample template file for master record
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package System
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class DashboardManagementService extends ConfigClass {

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
     * Return Total Application
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalApplication() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  count(*) AS Rows
        FROM    `application`
        WHERE   `isActive`  =   1
        AND     `companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
        SELECT  count(*) AS Rows
        FROM    [application]
        WHERE   [isActive]  =   1
        AND     [companyId] ='" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
        SELECT  COUNT(*) AS ROWS
        FROM   `APPLICATION
        WHERE   ISACTIVE  =   1
        AND     COMPANYID ='" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Total Module
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalModule() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  count(*) AS Rows
        FROM    `module`
        WHERE   `isActive`  =   1
        AND     `companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
        SELECT  count(*) AS Rows
        FROM    [module]
        WHERE   [isActive]  =   1
        AND     [companyId] ='" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
        SELECT  COUNT(*) AS ROWS
        FROM    MODULE
        WHERE   ISACTIVE  =   1
        AND     COMPANYID ='" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Total Leaf
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalLeaf() {
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  count(*) AS Rows
        FROM    `leaf` 
        WHERE   `isActive`  =   1
        AND     `companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
        SELECT  count(*) AS Rows
        FROM    `leaf`
        WHERE   `isActive`  =   1
        AND     `companyId` ='" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
        SELECT  COUNT(*) AS ROWS
        FROM    LEAF
        WHERE   ISACTIVE  =   1
        AND     COMPANYID ='" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Total Admin
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalAdmin() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `staff`
            JOIN    `role`
            USING   (`companyId`,`roleId`)
            WHERE   `staff`.`companyId` =   '" . $this->getCompanyId() . "'
            AND     `role`.`isAdmin`    =   1
            AND     `staff`.`isActive`  =   1
            AND     `role`.`isActive`   =   1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [staff]
            JOIN    [role]
            ON      [staff].[companyId] = [role].[companyId]
            AND     [staff].[roleId]    = [role].[roleId]
            WHERE   [staff].[companyId] =  '" . $this->getCompanyId() . "'
            AND     [role].[isAdmin]    =   1
            AND     [staff].[isActive]  =   1
            AND     [role].[isActive]   =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    STAFF
            JOIN    ROLE
            ON      STAFF.COMPANYID =   ROLE.COMPANYID
            AND     STAFF.ROLEID    =   ROLE.ROLEID
            WHERE   STAFF.COMPANYID =   '" . $this->getCompanyId() . "'
            AND     ROLE.ISADMIN    =   1
            AND     STAFF.ISACTIVE  =   1
            AND     ROLE.ISACTIVE   =   1";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return  Total Non Admin
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalNonAdmin() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `staff`
            JOIN    `role`
            USING   (`companyId`,`roleId`)
            WHERE   `staff`.`companyId` =   '" . $this->getCompanyId() . "'
            AND     `role`.`isAdmin`    =   0
            AND     `staff`.`isActive`  =   1
            AND     `role`.`isActive`   =   1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [staff]
            JOIN    [role]
            ON      [staff].[companyId] = [role].[companyId]
            AND     [staff].[roleId]    = [role].[roleId]
            WHERE   [staff].[companyId] =  '" . $this->getCompanyId() . "'
            AND     [role].[isAdmin]    =   0
            AND     [staff].[isActive]  =   1
            AND     [role].[isActive]   =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    STAFF
            JOIN    ROLE
            ON      STAFF.COMPANYID =   ROLE.COMPANYID
            AND     STAFF.ROLEID    =   ROLE.ROLEID
            WHERE   STAFF.COMPANYID =   '" . $this->getCompanyId() . "'
            AND     ROLE.ISADMIN    =   0
            AND     STAFF.ISACTIVE  =   1
            AND     ROLE.ISACTIVE   =   1";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Total Customer
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalCustomer() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `staff`
            JOIN    `role`
            USING   (`companyId`,`roleId`)
            WHERE   `staff`.`companyId` =   '" . $this->getCompanyId() . "'
            AND     `role`.`isCustomer` =   1
            AND     `staff`.`isActive`  =   1
            AND     `role`.`isActive`   =   1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [staff]
            JOIN    [role]
            ON      [staff].[companyId] = [role].[companyId]
            AND     [staff].[roleId]    = [role].[roleId]
            WHERE   [staff].[companyId] =  '" . $this->getCompanyId() . "'
            AND     [role].[isCustomer] =   1
            AND     [staff].[isActive]  =   1
            AND     [role].[isActive]   =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    STAFF
            JOIN    ROLE
            ON      STAFF.COMPANYID =   ROLE.COMPANYID
            AND     STAFF.ROLEID    =   ROLE.ROLEID
            WHERE   STAFF.COMPANYID =   '" . $this->getCompanyId() . "'
            AND     ROLE.ISCUSTOMER =   1
            AND     STAFF.ISACTIVE  =   1
            AND     ROLE.ISACTIVE   =   1";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
    }

    /**
     * Return Total Staff
     * @return int $total
     * @throws \Exception
     */
    function getTotalStaff() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  *
            FROM    `staff`
            WHERE   `isActive`=1
            AND     `companyId` ='" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  *
            FROM    [staff]
            WHERE   [isActive]=1
            AND     [companyId] ='" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  *
            FROM    STAFF`
            WHERE   ISACTIVE    =   1
            AND     COMPANYID   =   '" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);

        $total = $this->q->numberRows($result);
        return $total;
    }

    /**
     * Return Total Language
     * @return int $total Total
     * @throws \Exception
     */
    function getTotalLanguage() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
        SELECT  DISTINCT `languageId` AS `Rows`
        FROM    `applicationtranslate`
        WHERE   `companyId`=  '" . $this->getCompanyId() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
        SELECT  DISTINCT [languageId] AS [Rows]
        FROM    [applicationtranslate]
        WHERE   [companyId] =  '" . $this->getCompanyId() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
        SELECT  DISTINCT LANGUAGEID AS \"rows\"
        FROM    APPLICATIONTRANSLATE
        WHERE   COMPANYID=  '" . $this->getCompanyId() . "'";
                }
            }
        }
        $result = $this->q->fast($sql);
        $row = $this->q->fetchArray($result);
        $total = $row['Rows'];
        return $total;
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

?>