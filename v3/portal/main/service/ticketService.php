<?php

namespace Core\Portal\Main\Ticket\Service;

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
 * Class TicketService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\Ticket\Service
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TicketService extends ConfigClass {

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
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return New Ticket
     * @return int $total
     */
    function getNewTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `isNew` = 1";
            if ($_SESSION['isAdmin'] == 0) {
                $sql .= "
                AND `userIdFrom`='" . $this->getStaffId() . "'
                AND `companyId` =   '" . $this->getCompanyId() . "'    ";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM   [ticket]
            WHERE  [isNew] = 1";
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                AND [userIdFrom]    =   '" . $this->getStaffId() . "'
                AND [companyId]     =   '" . $this->getCompanyId() . "'    ";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   ISNEW = 1 ";
                    if ($_SESSION['isAdmin'] == 0) {
                        $sql .= "
                AND USERIDFROM      =   '" . $this->getStaffId() . "'
                AND COMPANYID       =   '" . $this->getCompanyId() . "'    ";
                    }
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
     * @return int $total
     */
    function getReviewTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `isReview`=1 ";
            if ($_SESSION['isAdmin'] == 0) {
                $sql .= "
                AND `userIdFrom`='" . $this->getStaffId() . "'
                AND `companyId` =   '" . $this->getCompanyId() . "'    ";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM   [ticket]
            WHERE  [isReview] = 1";
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                AND [userIdFrom]    =   '" . $this->getStaffId() . "'
                AND [companyId]     =   '" . $this->getCompanyId() . "'    ";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   ISREVIEW = 1 ";
                    if ($_SESSION['isAdmin'] == 0) {
                        $sql .= "
                AND USERIDFROM      =   '" . $this->getStaffId() . "'
                AND COMPANYID       =   '" . $this->getCompanyId() . "'    ";
                    }
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
     * @return int $total
     */
    function getSolveTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   `isSolve` = 1";
            if ($_SESSION['isAdmin'] == 0) {
                $sql .= "
                AND `userIdFrom`='" . $this->getStaffId() . "'
                AND `companyId` =   '" . $this->getCompanyId() . "'    ";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM   [ticket]
            WHERE  [isSolve] = 1";
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                AND [userIdFrom]    =   '" . $this->getStaffId() . "'
                AND [companyId]     =   '" . $this->getCompanyId() . "'    ";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   ISSOLVE = 1 ";
                    if ($_SESSION['isAdmin'] == 0) {
                        $sql .= "
                AND USERIDFROM      =   '" . $this->getStaffId() . "'
                AND COMPANYID       =   '" . $this->getCompanyId() . "'    ";
                    }
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
     * @return int $total
     */
    function getTotalTicket() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  count(*) AS Rows
            FROM    `ticket`
            WHERE   1 ";
            if ($_SESSION['isAdmin'] == 0) {
                $sql .= "
                AND `userIdFrom`='" . $this->getStaffId() . "'
                AND `companyId` =   '" . $this->getCompanyId() . "'    ";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  count(*) AS Rows
            FROM    [ticket]
            WHERE   1=1 ";
                if ($_SESSION['isAdmin'] == 0) {
                    $sql .= "
                AND [userIdFrom]    =   '" . $this->getStaffId() . "'
                AND [companyId]     =   '" . $this->getCompanyId() . "'    ";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) AS ROWS
            FROM    TICKET
            WHERE   1=1 ";
                    if ($_SESSION['isAdmin'] == 0) {
                        $sql .= "
                AND USERIDFROM      =   '" . $this->getStaffId() . "'
                AND COMPANYID       =   '" . $this->getCompanyId() . "'    ";
                    }
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