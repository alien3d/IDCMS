<?php

namespace Core\HumanResource\Loan\Loan\Service;

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
 * Class LoanService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\HumanResource\Loan\Loan\Service
 * @subpackage Loan
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LoanService extends ConfigClass {

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
     * Return LoanCategory
     * @return array|string
     */
    public function getLoanCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `loanCategoryId`,
                     `loanCategoryDescription`
         FROM        `loancategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [loanCategoryId],
                     [loanCategoryDescription]
         FROM        [loanCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      LOANCATEGORYID AS \"loanCategoryId\",
                     LOANCATEGORYDESCRIPTION AS \"loanCategoryDescription\"
         FROM        LOANCATEGORY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['loanCategoryId'] . "'>" . $d . ". " . $row['loanCategoryDescription'] . "</option>";
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
     * Return LoanType
     * @return array|string
     */
    public function getLoanType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `loanTypeId`,
                     `loanTypeDescription`
         FROM        `loantype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [loanTypeId],
                     [loanTypeDescription]
         FROM        [loanType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      LOANTYPEID AS \"loanTypeId\",
                     LOANTYPEDESCRIPTION AS \"loanTypeDescription\"
         FROM        LOANTYPE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['loanTypeId'] . "'>" . $d . ". " . $row['loanTypeDescription'] . "</option>";
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
     * Return Employee
     * @return array|string
     */
    public function getEmployee() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `employeeId`,
                     `employeeFirstName` AS `employeeName`
         FROM        `employee`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [employeeId],
                     COALESCE([employeeLastName],'')+
					 CASE WHEN [employeeLastName]+[FirstName] IS NOT NULL THEN ', '
					 END+
					 COALESCE([employeeFirstName],'') AS [employeeName]
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      EMPLOYEEID AS \"employeeId\",
                     EMPLOYEEFIRSTNAME AS \"employeeName\"
         FROM        EMPLOYEE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
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
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['employeeId'] . "'>" . $d . ". " . $row['employeeName'] . "</option>";
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