<?php

namespace Core\Financial\AccountPayable\PurchaseRequest\Service;

use Core\ConfigClass;
use Core\Financial\Ledger\Service\LedgerService;

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
require_once($newFakeDocumentRoot . "v3/financial/shared/service/sharedService.php");

/**
 * Class PurchaseRequestService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseRequest\Service
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseRequestService extends ConfigClass {

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
     * Upload Purchase Invoice Attachment
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
     * Financial Shared Service
     * @var \Core\Financial\Ledger\Service\LedgerService
     */
    public $ledgerService;

    /**
     * Purchase Request Approved Code
     */
    const PURCHASE_REQUEST_APPROVED = "PRAP";

    /**
     * Purchase Request Reject Code
     */
    const PURCHASE_REQUEST_REJECT = "PRRJ";
    
    const APPROVAL_BY_EMPLOYEE_CODE='EMPY';
    const APPROVAL_BY_STAFF_CODE='STAFF';
    const APPROVAL_BY_JOB_CODE='JOB';
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
        if ($_SESSION['companyId']) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            // fall back to default database if anything wrong
            $this->setCompanyId(1);
        }
        if ($_SESSION['staffId']) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            // fall back to default database if anything wrong
            $this->setStaffId(1);
        }
        if ($_SESSION['branchId']) {
            $this->setBranchId($_SESSION['branchId']);
        }
        $this->ledgerService = new LedgerService();
        $this->ledgerService->q = $this->q;
        $this->ledgerService->t = $this->t;
        $this->ledgerService->execute();
    }

    /**
     * Return Branch
     * @param null|int $countryId Country Primary Key
     * @return array|string
     */
    public function getBranch($countryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `branchId`,
							 `branchName`
			FROM        `branch`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND `countryId`='" . $countryId . "'";
            } else {
                $sql.=" AND `countryId`='" . $this->ledgerService->getCountryId() . "'";
            }
            $sql.=" ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [branchId],
			[branchName]
			FROM        [branch]
			WHERE       [isActive]  =   1
			AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND `countryId`='" . $countryId . "'";
            } else {
                $sql.=" AND `countryId`='" . $this->ledgerService->getCountryId() . "'";
            }
            $sql.=" ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BRANCHID AS \"branchId\",
                     branchName AS \"branchName\"
         FROM        BRANCH
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND COUNTRYID='" . $countryId . "'";
            } else {
                $sql.=" AND  COUNTRYID='" . $this->ledgerService->getCountryId() . "'";
            }
            $sql.="ORDER BY    ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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
                    $str .= "<option value='" . $row['branchId'] . "'>" . $d . ". " . $row['branchName'] . "</option>";
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
     * Return Branch Default Value
     * @return int
     */
    public function getBranchDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $branchId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `branchId`
         FROM        	`branch`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [branchId],
         FROM        [branch]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BRANCHID AS \"branchId\",
         FROM        BRANCH
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $branchId = $row['branchId'];
        }
        return $branchId;
    }

    /**
     * Return Department
     * @param null|int $countryId Country Primary Key
     * @param null|int $branchId Branch Primary Key
     * @param null|int $employeeId Branch Primary Key
     * @return array|string
     */
    public function getDepartment($countryId = null, $branchId = null, $employeeId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `department`.`departmentId`,
					    `department`.`departmentDescription`
			FROM        `department`
			WHERE       `department`.`isActive`  =   1
			AND         `department`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND `department`.`countryId`='" . $countryId . "'";
            } else {
                $sql.=" AND `department`.`countryId`='" . $this->ledgerService->getCountryId() . "'";
            }

            if ($branchId) {
                $sql.=" AND `department`.`branchId`='" . $branchId . "'";
            }
            if ($employeeId) {
                $sql.=" AND `employee`.`employeeId`='" . $employeeId . "'";
            }
            /**
              else if(strlen($this->getBranchId())>0) {
              $sql.=" AND `branchId`='".$this->getBranchId()."'";
              }
             * */
            $sql.=" ORDER BY    `department`.`isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [departmentId],
                     [departmentDescription]
         FROM        [department]
         WHERE       [department].[isActive]  =   1
         AND         [department].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND [department].[countryId]='" . $countryId . "'";
            } else {
                $sql.=" AND [department].[countryId]='" . $this->ledgerService->getCountryId() . "'";
            }

            if ($branchId) {
                $sql.=" AND [branch].[branchId]='" . $branchId . "'";
            }
            if ($employeeId) {
                $sql.=" AND [employee].[employeeId]='" . $employeeId . "'";
            }
            /**
              else if(strlen($this->getBranchId())>0) {
              $sql.=" AND [branchId]='".$this->getBranchId()."'";
              }
             * */
            $sql.=" ORDER BY    [department].[isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      DEPARTMENT.DEPARTMENTID AS \"departmentId\",
                     DEPARTMENT.DEPARTMENTDESCRIPTIONRIPTION AS \"departmentDescription\"
         FROM        DEPARTMENT
         WHERE       DEPARTMENT.ISACTIVE    =   1
         AND         DEPARTMENT.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND DEPARTMENT.COUNTRYID='" . $countryId . "'";
            } else {
                $sql.=" AND DEPARTMENT.COUNTRYID='" . $this->ledgerService->getCountryId() . "'";
            }

            if ($branchId) {
                $sql.=" AND DEPARTMENT.BRANCHID='" . $branchId . "'";
            }
            if ($employeeId) {
                $sql.=" AND EMPLOYEE.EMPLOYEEID='" . $branchId . "'";
            }
            /**
              if(strlen($this->getBranchId())>0) {
              $sql.=" AND BRANCHID='".$this->getBranchId()."'";
              }
             * */
            $sql.=" ORDER BY     DEPARTMENT.ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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
                    $str .= "<option value='" . $row['departmentId'] . "'>" . $d . ". " . $row['departmentDescription'] . "</option>";
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
     * Return Department Default Value
     * @return int
     */
    public function getDepartmentDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $departmentId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `departmentId`
         FROM        	`department`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [departmentId],
         FROM        [department]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      DEPARTMENTID AS \"departmentId\",
         FROM        DEPARTMENT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $departmentId = $row['departmentId'];
        }
        return $departmentId;
    }

    /**
     * Return Department Primary Key Based On Employee Primary Key
     * @param int $employeeId Employee Primary Key
     * @return int $departmentId Department Primary Key 
     * */
    public function getBranchByEmployee($employeeId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $branchId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `branchId`
		 FROM		   `employee`
         WHERE			`employeeId`='" . $employeeId . "'		 
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [branchId]
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
		 AND			[employeeId] ='" . $employeeId . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BRANCHID AS \"branchId\",
         FROM        DEPARTMENT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
		  AND			EMPLOYEEID ='" . $employeeId . "'
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $branchId = $row['branchId'];
        }
        return $branchId;
    }

    /**
     * Return Department Primary Key Based On Employee Primary Key
     * @param int $employeeId Employee Primary Key
     * @return int $departmentId Department Primary Key 
     * */
    public function getDepartmentByEmployee($employeeId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $departmentId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `departmentId`
		 FROM		   `employee`
         WHERE		`employeeId`='" . $employeeId . "'		 
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [departmentId]
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
		 AND			[employeeId] ='" . $employeeId . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      DEPARTMENTID AS \"departmentId\",
         FROM        DEPARTMENT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
		  AND			EMPLOYEEID ='" . $employeeId . "'
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $departmentId = $row['departmentId'];
        }
        return $departmentId;
    }

    /**
     * Return Warehouse
     * @param null|int $countryId Country Primary Key
     * @param null|int $branchId Branch Primary Key
     * @param null|int $departmentId Department Primary Key
     * @return array|string
     */
    public function getWarehouse($countryId = null, $branchId = null, $departmentId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `warehouseId`,
                     `warehouseDescription`
         FROM        `warehouse`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND `countryId`='" . $countryId . "'";
            } else {
                $sql.=" AND `countryId`='" . $this->ledgerService->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND `branchId`='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND `branchId`='" . $this->getBranchId() . "'";
            }
            if ($departmentId) {
                $sql.=" AND `departmentId`='" . $departmentId . "'";
            }
            $sql.="ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [warehouseId],
                     [warehouseDescription]
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND [countryId]='" . $countryId . "'";
            } else {
                $sql.=" AND [countryId]='" . $this->ledgerService->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND [branchId]='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND [branchId]='" . $this->getBranchId() . "'";
            }

            if ($departmentId) {
                $sql.=" AND [departmentId]='" . $departmentId . "'";
            }
            $sql.=" ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      WAREHOUSEID AS \"warehouseId\",
                     WAREHOUSEDESCRIPTION AS \"warehouseDescription\"
         FROM        WAREHOUSE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND COUNTRYID='" . $countryId . "'";
            } else {
                $sql.=" AND  COUNTRYID='" . $this->ledgerService->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND  BRANCHID='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND  BRANCHID='" . $this->getBranchId() . "'";
            }

            if ($departmentId) {
                $sql.=" AND DEPARTMENTID='" . $departmentId . "'";
            }
            $sql.="ORDER BY    ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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
                    $str .= "<option value='" . $row['warehouseId'] . "'>" . $d . ". " . $row['warehouseDescription'] . "</option>";
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
     * Return Warehouse Default Value
     * @return int
     */
    public function getWarehouseDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $warehouseId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `warehouseId`
         FROM        	`warehouse`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [warehouseId],
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      WAREHOUSEID AS \"warehouseId\",
         FROM        WAREHOUSE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $warehouseId = $row['warehouseId'];
        }
        return $warehouseId;
    }

    /**
     * Return Product Resources
     * @return array|string
     */
    public function getProductResources() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `productResourcesId`,
                     `productResourcesDescription`
         FROM        `productresources`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [productResourcesId],
                     [productResourcesDescription]
         FROM        [productResources]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PRODUCTRESOURCESID AS \"productResourcesId\",
                     PRODUCTRESOURCESDESCRIPTION AS \"productResourcesDescription\"
         FROM        PRODUCTRESOURCES  
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['productResourcesId'] . "'>" . $d . ". " . $row['productResourcesDescription'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                    $items[] = $row;
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        // fake return
        return $items;
    }

    /**
     * Return ProductResources Default Value
     * @return int
     */
    public function getProductResourcesDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $productResourcesId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `productResourcesId`
         FROM        	`productresources`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [productResourcesId],
         FROM        [productResources]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PRODUCTRESOURCESID AS \"productResourcesId\",
         FROM        PRODUCTRESOURCES  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $productResourcesId = $row['productResourcesId'];
        }
        return $productResourcesId;
    }

    /**
     * Return Employee
     * @param null|int $branchId BRANCH
     * @param null|int $departmentId Department
     * @return array|string
     */
    public function getEmployee($branchId, $departmentId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `employeeId`,
                     `employeeFirstName`
         FROM        `employee`
		 JOIN		`employmentstatus`
		 USING		(`companyId`,`employmentStatusId`)
         WHERE       `employee`.`isActive`  =   1
         AND         `employee`.`companyId` =   '" . $this->getCompanyId() . "'
		 AND			`employmentStatus`.`isPaidSalary`=1 ";
            if ($branchId) {
                $sql.=" AND `branchId`='" . $branchId . "'";
            }
            if ($departmentId) {
                $sql.=" AND `departmentId`='" . $departmentId . "'";
            }
            $sql.="
		 
		 ORDER BY    `employee`.`isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [employeeId],
                        [employeeFirstName]
         FROM        [employee]
		 JOIN		 [employmentStatus]
		 ON		   [employment].[companyId] =  [employmentStatus].[companyId] 
		 AND		   [employment].[employmentStatusId] =  [employmentStatus].[employmentStatusId] 
         WHERE      [employee]. [isActive]  =   1
         AND         [employee].[companyId] =   '" . $this->getCompanyId() . "'
		  AND			[employmentStatus].[isPaidSalary]=1
         ";
            if ($branchId) {
                $sql.=" AND [branchId]='" . $branchId . "'";
            }
            if ($departmentId) {
                $sql.=" AND [departmentId]='" . $departmentId . "'";
            }
            $sql.="
		 
		 ORDER BY    [employee].[isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      EMPLOYEEID AS \"employeeId\",
                     EMPLOYEEFIRSTNAME AS \"employeeFirstName\"
         FROM        EMPLOYEE  
		 	 JOIN		 EMPLOYMENTSTATUS
		 ON		   EMPLOYMENT.COMPANYID =  EMPLOYMENTSTATUS.COMPANYID
		 AND		   EMPLOYMENT.EMPLOYMENTSTATUSID =  EMPLOYMENTSTATUS.EMPLOYMENTSTATUSID 
         WHERE       EMPLOYEE .ISACTIVE    =   1
         AND         EMPLOYEE .COMPANYID   =   '" . $this->getCompanyId() . "'
		  AND			EMPLOYMENTSTATUS .ISPAIDSALARY=1
           ";
            if ($branchId) {
                $sql.=" AND BRANCHID='" . $branchId . "'";
            }
            if ($departmentId) {
                $sql.=" AND DEPARTMENTID='" . $departmentId . "'";
            }
            $sql.=" ORDER BY    EMPLOYEE .ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
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
                    $str .= "<option value='" . $row['employeeId'] . "'>" . $d . ". " . $row['employeeFirstName'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                    $items[] = $row;
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        // fake return
        return $items;
    }

    /**
     * Return Employee Default Value
     * @return int
     */
    public function getEmployeeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $employeeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `employeeId`
         FROM        	`employee`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [employeeId],
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      EMPLOYEEID AS \"employeeId\",
         FROM        EMPLOYEE  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $employeeId = $row['employeeId'];
        }
        return $employeeId;
    }
        /**
     * Return Business Partner Category
     * @param null|int $businessPartnerCategoryId Business Partner Category Primary Key
     * @return array|string
     */
    public function getBusinessPartner($businessPartnerCategoryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `businesspartner`.`businessPartnerId`,
					 `businesspartner`.`businessPartnerCompany`,
					 `businesspartnercategory`.`businessPartnerCategoryDescription`
			FROM        `businesspartner`
			JOIN		 `businesspartnercategory`
			USING		 (`companyId`,`businessPartnerCategoryId`)
			WHERE       `businesspartner`.`isActive`  =   1
			AND			`isCreditor`=1
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND `businesspartner`.`businessPartnerCategoryId`='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
								`businesspartner`.`businessPartnerCompany`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [businessPartner].[businessPartnerId],
				 [businessPartner].[businessPartnerCompany],
				 [businessPartnerCategory].[businessPartnerCategoryDescription]
			FROM        [businessPartner]
			JOIN	     [businessPartnerCategory]
			ON			 [businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
			AND		 [businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
			WHERE       [businessPartner].[isActive]  							=	1
			AND			[isCreditor]=1
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND [businessPartner].[businessPartnerCategoryId]='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
			[businessPartner].[businessPartnerCompany]	";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
							 BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
							 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNER
			JOIN	     	BUSINESSPARTNERCATEGORY
			ON			 BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
			AND		 	BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
			WHERE       BUSINESSPARTNER.COMPANYID = '" . $this->getCompanyId() . "'
			AND			ISCREDITOR=1
			AND         BUSINESSPARTNER.ISACTIVE    						=   1";
            if ($businessPartnerCategoryId) {
                $sql.=" AND BUSINESSPARTNER.BUSINESSPARTNERCATEGORYID='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION ,
			BUSINESSPARTNER.BUSINESSPARTNERCOMPANY";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 0;
            $businessPartnerCategoryDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($businessPartnerCategoryDescription != $row['businessPartnerCategoryDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                }
                $businessPartnerCategoryDescription = $row['businessPartnerCategoryDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . ($d+1) . ". " . $row['businessPartnerCompany'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
            }
            $str .= "</optgroup>";
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
     * Return Business Partner Default Value
     * @return int
     */
    public function getBusinessPartnerDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $businessPartnerId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `businessPartnerId`
         FROM        	`businesspartner`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [businessPartnerId],
         FROM        [businessPartner]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      BUSINESSPARTNERID AS \"businessPartnerId\",
         FROM        BUSINESSPARTNER
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $businessPartnerId = $row['businessPartnerId'];
        }
        return $businessPartnerId;
    }
    /**
     * Return Budget Base On Chart Of Account Primary Key
     * @param int $chartOfAccountId Chart Of Account Primary Key
     * @param string $documentDate Document Date
     * @return double $budgetAmount Budget Amount
     */
    public function getBudget($chartOfAccountId, $documentDate) {
        //initialize dummy value.. no content header.pure html
        $this->ledgerService->setFinancePeriodInformation($documentDate);
        $budgetAmount = $this->ledgerService->getBalanceBudget($chartOfAccountId, $this->ledgerService->getFinanceYearId(), $this->ledgerService->getFinancePeriodRangeId());

        return$budgetAmount;
    }
    /**
     * Return Purchase Request Approval Amount based on Employee
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @param float $amount Amount Requested
     * @return void
     **/
    public function getPurchaseInvoiceApprovalValue($purchaseRequestDetailId, $amount) {
        // first check setting financial system
        $approvalByCode  = $this->getPayableApprovalBy();
        if($approvalByCode == self::APPROVAL_BY_EMPLOYEE_CODE) {
           $value = $this->getPurchaseInvoiceEmployeeApprovalValue($purchaseRequestDetailId, $amount);
        } else if($approvalByCode == self::APPROVAL_BY_STAFF_CODE){
            $value = $this->getPurchaseInvoiceStaffApprovalValue($purchaseRequestDetailId, $amount);
        } else if($approvalByCode == self::APPROVAL_BY_JOB_CODE){
            $value = $this->getPurchaseInvoiceJobApprovalValue($purchaseRequestDetailId, $amount);
        }
        if($value == 1){
             $this->setPurchaseInvoiceApproved($purchaseRequestDetailId, 1);
             echo json_encode(array("success" => true, "message" => $this->t['belowRowHaveBeenApprovedLabel']));
             exit();
        } else {
            $this->setPurchaseInvoiceApproved($purchaseRequestDetailId, 0);
            echo json_encode(array("success" => false, "message" => $this->t['belowRowHaveBeenRejectedLabel']));
            exit();    
        }
    }
            
    /**
     * Return Purchase Request Approval Amount based on Employee
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @param float $amount Amount Requested
     * @return bool
     **/
    private function getPurchaseInvoiceEmployeeApprovalValue($purchaseRequestDetailId, $amount) {
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $employeeId = $this->getEmployeeId($this->getStaffId());
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `purchaseInvoiceAccessEmployeeMinimumAmount` AS `purchaseInvoiceAccessMinimumAmount`,
                        `purchaseInvoiceAccessEmployeeMaximumAmount` AS `purchaseInvoiceAccessMaximumAmount`
            FROM        `purchaseinvoiceaccessemployee`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	`employeeId` =	'" . $employeeId . "'
            LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      TOP 1 
                        [purchaseInvoiceAccessEmployeeMinimumAmount]  AS [purchaseInvoiceAccessMinimumAmount],
                        [purchaseInvoiceAccessEmployeeMaximumAmount] AS [purchaseInvoiceAccessMaximumAmount]
            FROM        [purchaseInvoiceAccessEmployee]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND         [employeeId] =	  '" . $employeeId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICEACCESSEMPLOYEEMINIMUMAMOUNT AS  \"purchaseInvoiceAccessMinimumAmount\",
                        PURCHASEINVOICEACCESSEMPPLOYEEMAXIMUMAMOUNT AS \"purchaseInvoiceAccessMaximumAmount\"
            FROM        PURCHASEINVOICEACCESSEMPLOYEE
            WHERE       ISACTIVE  =   1
            AND         COMPANYID =   '" . $this->getCompanyId() . "'
            AND         EMPLOYEEID =	  '" . $employeeId . "'
            AND         ROWNUM= 1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $purchaseInvoiceAccessMaximumAmount = $row['purchaseInvoiceAccessMaximumAmount'];
            if ($amount > $purchaseInvoiceAccessMaximumAmount) {
                return false;
            }
        }
        return true;
    }
    /**
     * Return Purchase Request Approval Amount based on jobId
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @param float $amount Amount Requested
     * @return bool
     * */
    private function getPurchaseInvoiceStaffApprovalValue($purchaseRequestDetailId, $amount) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `purchaseInvoiceAccessStaffMinimumAmount` AS `purchaseInvoiceAccessMinimumAmount`,
                        `purchaseInvoiceAccessStaffMaximumAmount` AS `purchaseInvoiceAccessMaximumAmount`
            FROM        `purchaseinvoiceaccesstaff`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	`staffId` =	'" . $this->getStaffId(). "'
            LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      TOP 1 
                        [purchaseInvoiceAccessStaffMinimumAmount]  AS [purchaseInvoiceAccessMinimumAmount],
                        [purchaseInvoiceAccessStaffMaximumAmount] AS [purchaseInvoiceAccessMaximumAmount]
            FROM        [purchaseInvoiceAccessStaff]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND         [staffId] =	  '" . $this->getStaffId() . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICEACCESSSSTAFFMINIMUMAMOUNT AS  \"purchaseInvoiceAccessMinimumAmount\",
                        PURCHASEINVOICEACCESSSSTAFFMAXIMUMAMOUNT AS \"purchaseInvoiceAccessMaximumAmount\"
            FROM        PURCHASEINVOICEACCESSSTAFF
            WHERE       ISACTIVE  =   1
            AND         COMPANYID =   '" . $this->getCompanyId() . "'
            AND         STAFFID =	  '" . $this->getStaffId() . "'
            AND         ROWNUM= 1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $purchaseRequestJobApprovalAmount = $row['purchaseRequestJobApprovalAmount'];
            if ($amount > $purchaseRequestJobApprovalAmount) {
                return false;
            }
        }
        return true;
    }
    /**
     * Return Purchase Request Approval Amount based on jobId
     * @param int $purchaseRequestDetailId Purchase Request Detail Primary Key
     * @param float $amount Amount Requested
     *  @return bool
     * */
    private function getPurchaseInvoiceJobApprovalValue($purchaseRequestDetailId, $amount) {
        //initialize dummy value.. no content header.pure html
        $jobId = $this->getJob($this->getStaffId());
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `purchaseInvoiceAccessJobMinimumAmount` AS `purchaseInvoiceAccessMinimumAmount`,
                        `purchaseInvoiceAccessJobMaximumAmount` AS `purchaseInvoiceAccessMaximumAmount`
            FROM        `purchaseinvoiceaccessjob`
            WHERE       `isActive`  =   1
            AND         `companyId` =   '" . $this->getCompanyId() . "'
            AND    	`jobId` =	'" . $jobId . "'
            LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      TOP 1 
                        [purchaseInvoiceAccessJobMinimumAmount]  AS [purchaseInvoiceAccessMinimumAmount],
                        [purchaseInvoiceAccessJobMaximumAmount] AS [purchaseInvoiceAccessMaximumAmount]
            FROM        [purchaseInvoiceAccessJob]
            WHERE       [isActive]  =   1
            AND         [companyId] =   '" . $this->getCompanyId() . "'
            AND         [jobId] =	  '" . $jobId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICEACCESSJOBMINIMUMAMOUNT AS  \"purchaseInvoiceAccessMinimumAmount\",
                        PURCHASEINVOICEACCESSJOBMAXIMUMAMOUNT AS \"purchaseInvoiceAccessMaximumAmount\"
            FROM        PURCHASEINVOICEACCESSJOB
            WHERE       ISACTIVE  =   1
            AND         COMPANYID =   '" . $this->getCompanyId() . "'
            AND         EMPLOYEEID =	  '" . $jobId . "'
            AND         ROWNUM= 1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $purchaseRequestJobApprovalAmount = $row['purchaseRequestJobApprovalAmount'];
            if ($amount > $purchaseRequestJobApprovalAmount) {
                return false;
            }
        }
        return true;
    }

    /**
     * Return Equipment Status
     * @return array|string
     * @throws \Exception
     */
    public function getEquipmentStatus() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `equipmentStatusId`,
                     `equipmentStatusDescription`
         FROM        `equipmentstatus`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [equipmentStatusId],
                     [equipmentStatusDescription]
         FROM        [equipmentStatus]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      EQUIPMENTSTATUSID AS \"equipmentStatusId\",
                     EQUIPMENTSTATUSDESCRIPTION AS \"equipmentStatusDescription\"
         FROM        EQUIPMENTSTATUS  
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
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                if ($this->getServiceOutput() == 'option') {
                    $str.="<option value='" . $row['equipmentStatusId'] . "'>" . $d . ". " . $row['equipmentStatusDescription'] . "</option>";
                } else if ($this->getServiceOutput() == 'html') {
                    $items[] = $row;
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return false;
    }

    /**
     * Return EquipmentStatus Default Value
     * @return int
     * @throws \Exception
     */
    public function getEquipmentStatusDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $equipmentStatusId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `equipmentStatusId`
         FROM        	`equipmentstatus`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [equipmentStatusId],
         FROM        [equipmentStatus]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      EQUIPMENTSTATUSID AS \"equipmentStatusId\",
         FROM        EQUIPMENTSTATUS  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $equipmentStatusId = $row['equipmentStatusId'];
        }
        return $equipmentStatusId;
    }

    /**
     * Approved Request and inform back to requested
     * @param int $purchaseRequestId Purchase Request Primary Key
     * @return void
     */
    public function setPurchaseRequestApproved($purchaseRequestId) {
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `purchaserequest`
            SET     `isApproved`  =  1
            WHERE   `purchaseRequestId` IN (" . $purchaseRequestId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [purchaseRequest]
            SET     [isApproved]  =  1
            WHERE   [purchaseRequestId] IN (" . $purchaseRequestId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PURCHASEREQUEST
            SET     ISAPPROVED  =  1
            WHERE   PURCHASEREQUESTID IN (" . $purchaseRequestId . ")";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->createNotification($this->t['rejectNotificationLabel']);
    }

    /**
     * Reject Request and inform back to requested
     * @param int $purchaseRequestId Purchase Request Primary Key
     * @return void
     */
    public function setPurchaseRequestRejected($purchaseRequestId) {
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `purchaserequest`
            SET     `isReject`  =  1
            WHERE   `purchaseRequestId` IN (" . $purchaseRequestId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [purchaseRequest]
            SET     [isReject]  =  1
            WHERE   [purchaseRequestId] IN (" . $purchaseRequestId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PURCHASEREQUEST
            SET     ISREJECT  =  1
            WHERE   PURCHASEREQUESTID iN (" . $purchaseRequestId . ")";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->createNotification($this->t['rejectNotificationLabel']);
        echo json_encode(array("success" => "true", "message" => $this->t['rejectNotificationLabel']));
        exit();
    }

    /**
     * Reject Request and inform back to requested
     * @param int $purchaseRequestId Purchase Request Primary Key
     * @return void
     */
    public function setPurchaseRequestReview($purchaseRequestId) {
        header('Content-Type:application/json; charset=utf-8');
        //initialize dummy value.. no content header.pure html
        $sql = null;

        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE  `purchaserequest`
            SET     `isReview`  =  1
            WHERE   `purchaseRequestId` IN (" . $purchaseRequestId . ")";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE  [purchaseRequest]
            SET     [isReview]  =  1
            WHERE   [purchaseRequestId] IN (" . $purchaseRequestId . ")";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PURCHASEREQUEST
            SET     ISREVIEW  =  1
            WHERE   PURCHASEREQUESTID IN (" . $purchaseRequestId . ")";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->createNotification($this->t['reviewNotificationLabel']);
        echo json_encode(array("success" => "true", "message" => $this->t['reviewNotificationLabel']));
        exit();
    }

    /**
     * Return Purchase Request Document Number
     * @param int $purchaseRequestId Purchase Request Primary Key
     * @return mixed
     */
    public function getPurchaseRequestDocumentNumber($purchaseRequestId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $documentNumber = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `documentNumber`
         FROM        `purchaserequest`
         WHERE       `purchaseRequestId`  =   '" . $purchaseRequestId . "'
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [documentNumber],
         FROM        [purchaseRequest]
         WHERE       [purchaseRequest] =   '" . $purchaseRequestId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      `DOCUMENTNUMBER`
         FROM        `PURCHASEREQUEST`
         WHERE       `PURCHASEREQUESTID`  =   '" . $purchaseRequestId . "'
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $documentNumber = $row['documentNumber'];
        }
        return $documentNumber;
    }

    /**
     * Return Job based on job
     * @param int $staffId Staff Primary Key
     * @return int $jobId Job Primary Key
     * */
    public function getJob($staffId) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $jobId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `jobId`
			FROM        	`job`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `employeeId` =	  ( SELECT employeeId FROM `employeestaffreference` WHERE `staffId`='" . $staffId . "' LIMIT 1)
			LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      TOP 1 `jobId`
			FROM        	`job`
			WHERE       `isActive`  =   1
			AND         `companyId` =   '" . $this->getCompanyId() . "'
			AND    	  `employeeId` =	  ( SELECT employeeId FROM `employeestaffreference` WHERE `staffId`='" . $staffId . "'  AND ROWNUM=1)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      JOBID AS \"jobId\"
			FROM        	`JOB`
			WHERE       `ISACTIVE`  =   1
			AND         `COMPANYID` =   '" . $this->getCompanyId() . "'
			AND    	  `EMPLOYEEID` =	  ( SELECT EMPLOYEEID FROM `EMPLOYEESTAFFREFERENCE` WHERE `STAFFID`='" . $staffId . "'  AND ROWNUM=1)";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $jobId = $row['jobId'];
        }
        return $jobId;
    }

    /**
     * Assign Vendor For misclenous payment .
     * Assign at Detail Level Not Main table.Requested might request mutiple item from mutiple vendor
     * @param int $purchaseRequestId Purchase Request
     * @param int $businessPartnerId Business Partner
     */
    public function setAssignVendor($purchaseRequestDetailId, $businessPartnerId) {
        header('Content-Type:application/json; charset=utf-8');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            UPDATE `purchaserequestdetail`
            SET    `businessPartnerId`='" . $businessPartnerId . "'
            WHERE  `purchaseRequestDetailId`='" . $purchaseRequestDetailId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            UPDATE [purchaseRequestDetail]
            SET    [businessPartnerId]='" . $businessPartnerId . "'
            WHERE  [purchaseRequestDetailId]='" . $purchaseRequestDetailId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            UPDATE  PURCHASEREQUESTDETAIL
            SET     BUSINESSPARTNERID ='" . $businessPartnerId . "'
            WHERE   PURCHASEREQUESTDETAILID='" . $purchaseRequestDetailId . "'";
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        echo json_encode(array("success" => true, "message" => "complete"));
        exit();
    }

    /**
     * Create New Purchase Order upon approval
     * @param int $purchaseRequestDetailId
     * @return type
     */
    public function setNewPurchaseOrder($purchaseRequestDetailId) {
        // select purchase request detail and main
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT *
            FROM   `purchaserequestdetail`
            JOIN   `purchaserequest`
            USING   (`companyId`,`purchaseRequestId`)
            WHERE  `purchaseRequestDetailId`='" . $purchaseRequestDetailId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT *
            FROM   [purchaseRequestDetail]
            JOIN   [purchaseRequest]
            ON     [purchaseRequestDetail].[companyId]          =   [purchaseRequest].[companyId]
            AND    [purchaseRequestDetail].[purchaseRequestId]  =   [purchaseRequest].[purchaseRequestId]
            WHERE  [purchaseRequestDetailId]='" . $purchaseRequestDetailId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT *
            FROM   PURCHASEREQUESTDETAIL
            JOIN   PURCHASEREQUEST
            ON     PURCHASEREQUESTDETAIL.COMPANYID          =   PURCHASEREQUEST.COMPANYID
            AND    PURCHASEREQUESTDETAIL.PURCHASEREQUESTID  =   PURCHASEREQUEST.PURCHASEREQUESTID
            WHERE  PURCHASEREQUESTDETAILID='" . $purchaseRequestDetailId . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            // main table
            $branchId = $row['branchId'];
            $departmentId =     $row['departmentId'];
            $warehouseId = $row['warehouseId'];
            
            $productResourcesId = $row['productResourcesId'];
            $equipmentStatusId = $row['equipmentStatusId'];
            $documentNumber = $this->getDocumentNumber('APINV');
            $referenceNumber =  $row['documentNumber'];
            $purchaseRequestDate =    $row['purchaseRequestDate'];
            
            $purchaseRequestRequiredDate =    $row['purchaseRequestRequiredDate'];
            $purchaseRequestValidStartDate =    $row['purchaseRequestValidStartDate'];
            $purchaseRequestValidEndDate =    $row['purchaseRequestValidEndDate'];
            
            $purchaseRequestDescription =    $row['purchaseRequestDescription'];
            // main table
            // detail table
            $productId =  $row['productId'];
            $purchaseRequestDetailDescription = $row['purchaseRequestDetailDescription'];
            $purchaseRequestDetailQuantity = $row['purchaseRequestDetailQuantity'];
            $unitOfMeasurementId = $row['unitOfMeasurementId'];
            $businessPartnerId = $row['businessPartnerId'];
            // detail table
            
            // product price 
            $productPrice = $this->getBusinessPartnerProductPrice($productId);
            if($productPrice  ==0 || $productPrice == null || $productPrice==''){
                $productPrice =1;
            }
            $totalProductPrice = $purchaseRequestDetailQuantity * $productPrice;
            // static info
            $isDefault=0;
            $isNew=1;
            $iDraft=1;
            $isUpdate=0;
            $isDelete=0;
            $isActive=1;
            $isApproved=0;
            $isReview=1;
            $isPost=0;
            $purchaseInvoiceProjectId = $this->getPurchaseInvoiceProjectDefaultValue();
            // static info
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
            INSERT INTO `purchaseinvoice` 
            (
                 `companyId`,
                 `businessPartnerId`,
                 `purchaseInvoiceProjectId`,
                 `productResourcesId`,
                 `documentNumber`,
                 `referenceNumber`,
                 `purchaseInvoiceDate`,
                 `purchaseInvoicePrice`,
                 `purchaseInvoiceDescription`,
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
                 '" . $this->getCompanyId() . "',
                 '" . $businessPartnerId . "',
                 '" . $purchaseInvoiceProjectId . "',
                 '" . $productResourcesId . "',
                 '" . $documentNumber . "',
                 '" . $documentNumber . "',
                 '" . $purchaseRequestDate . "',
                 '" . $productPrice . "',
                 '" . $purchaseRequestDescription . "',
                 '" . $isDefault . "',
                 '" . $isNew . "',
                 '" . $iDraft . "',
                 '" . $isUpdate . "',
                 '" . $isDelete . "',
                 '" . $isActive . "',
                 '" . $isApproved . "',
                 '" . $isReview. "',
                 '" . $isPost . "',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
       );";
            } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [purchaseInvoice] 
            (
                 [purchaseInvoiceId],
                 [companyId],
                 [businessPartnerId],
                 [purchaseInvoiceProjectId],
                 [productResourcesId],
                 [documentNumber],
                 [referenceNumber],
                 [purchaseInvoiceDate],
                 [purchaseInvoicePrice],
                 [purchaseInvoiceDescription],
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
                  '" . $this->getCompanyId() . "',
                 '" . $businessPartnerId . "',
                 '" . $purchaseInvoiceProjectId . "',
                 '" . $productResourcesId . "',
                 '" . $documentNumber . "',
                 '" . $documentNumber . "',
                 '" . $purchaseRequestDate . "',
                 '" . $productPrice . "',
                 '" . $purchaseRequestDescription . "',
                 '" . $isDefault . "',
                 '" . $isNew . "',
                 '" . $iDraft . "',
                 '" . $isUpdate . "',
                 '" . $isDelete . "',
                 '" . $isActive . "',
                 '" . $isApproved . "',
                 '" . $isReview. "',
                 '" . $isPost . "',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
            } else if ($this->getVendor() == self::ORACLE) {
                $sql = "
            INSERT INTO PURCHASEINVOICE 
            (
                 COMPANYID,
                 BUSINESSPARTNERID,
                 PURCHASEINVOICEPROJECTID,
                 PRODUCTRESOURCESID,
                 DOCUMENTNUMBER,
                 REFERENCENUMBER,
                 PURCHASEINVOICEDATE,
                 PURCHASEINVOICEPRICE,
                 PURCHASEINVOICEDESCRIPTION,
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
                  '" . $this->getCompanyId() . "',
                 '" . $businessPartnerId . "',
                 '" . $purchaseInvoiceProjectId . "',
                 '" . $productResourcesId . "',
                 '" . $documentNumber . "',
                 '" . $documentNumber . "',
                 '" . $purchaseRequestDate . "',
                 '" . $productPrice . "',
                 '" . $purchaseRequestDescription . "',
                 '" . $isDefault . "',
                 '" . $isNew . "',
                 '" . $iDraft . "',
                 '" . $isUpdate . "',
                 '" . $isDelete . "',
                 '" . $isActive . "',
                 '" . $isApproved . "',
                 '" . $isReview. "',
                 '" . $isPost . "',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
            }
            try {
                $this->q->create($sql);
            } catch (\Exception $e) {
                $this->q->rollback();
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
            $purchaseInvoiceId = $this->q->lastInsertId("purchaseInvoice");
             if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `purchaseinvoicedetail` 
            (
                 `companyId`,
                 `purchaseInvoiceId`,
                 `productId`,
                 `purchaseInvoiceDetailDescription`,
                 `purchaseInvoiceDetailQuantity`,
                 `unitOfMeasurementId`,
                 `purchaseInvoiceDetailPrice`,
                 `purchaseInvoiceDetailTotalPrice`,
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
                  '" . $this->getCompanyId() . "',
                 '" . $purchaseInvoiceId. "',
                 '" . $productId . "',
                 '" . $purchaseRequestDetailDescription . "',
                 '" . $purchaseRequestDetailQuantity . "',
                 '" . $unitOfMeasurementId. "',
                 '" . $productPrice . "',
                 '" . $totalProductPrice . "',
                 '" . $isDefault . "',
                 '" . $isNew . "',
                 '" . $iDraft . "',
                 '" . $isUpdate . "',
                 '" . $isDelete . "',
                 '" . $isActive . "',
                 '" . $isApproved . "',
                 '" . $isReview. "',
                 '" . $isPost . "',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
       );";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO [purchaseInvoiceDetail] 
            (
                 [purchaseInvoiceDetailId],
                 [companyId],
                 [purchaseInvoiceId],
                 [productId],
                 [purchaseInvoiceDetailDescription],
                 [purchaseInvoiceDetailQuantity],
                 [unitOfMeasurementId],
                 [purchaseInvoiceDetailPrice],
                 [purchaseInvoiceDetailTotalPrice],
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
                  '" . $this->getCompanyId() . "',
                 '" . $purchaseInvoiceId. "',
                 '" . $productId . "',
                 '" . $purchaseRequestDetailDescription . "',
                 '" . $purchaseRequestDetailQuantity . "',
                 '" . $unitOfMeasurementId. "',
                 '" . $productPrice . "',
                 '" . $totalProductPrice . "',
                 '" . $isDefault . "',
                 '" . $isNew . "',
                 '" . $iDraft . "',
                 '" . $isUpdate . "',
                 '" . $isDelete . "',
                 '" . $isActive . "',
                 '" . $isApproved . "',
                 '" . $isReview. "',
                 '" . $isPost . "',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            INSERT INTO PURCHASEINVOICEDETAIL 
            (
                 COMPANYID,
                 PURCHASEINVOICEID,
                 PRODUCTID,
                 PURCHASEINVOICEDETAILDESCRIPTION,
                 PURCHASEINVOICEDETAILQUANTITY,
                 UNITOFMEASUREMENTID,
                 CHARTOFACCOUNTID,
                 PURCHASEINVOICEDETAILPRICE,
                 PURCHASEINVOICEDETAILTOTALPRICE,
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
                  '" . $this->getCompanyId() . "',
                 '" . $purchaseInvoiceId. "',
                 '" . $productId . "',
                 '" . $purchaseRequestDetailDescription . "',
                 '" . $purchaseRequestDetailQuantity . "',
                 '" . $unitOfMeasurementId. "',
                 '" . $productPrice . "',
                 '" . $totalProductPrice . "',
                 '" . $isDefault . "',
                 '" . $isNew . "',
                 '" . $iDraft . "',
                 '" . $isUpdate . "',
                 '" . $isDelete . "',
                 '" . $isActive . "',
                 '" . $isApproved . "',
                 '" . $isReview. "',
                 '" . $isPost . "',
                 '" . $this->getStaffId() . "',
                 " . $this->getExecuteTime() . "
            );";
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        
        }
    }

    /**
     * Return Product Price if applicable
     * @param int $productId Product
     * @return double $productPrice
     * */
    public function getBusinessPartnerProductPrice($productId) {
        $productPrice = 0;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `businessPartnerPriceListAmount`
            FROM	`businesspartnerpricelist`
            WHERE	`companyId`='" . $this->getCompanyId() . "'
            AND     `productId`	=	'" . $productId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT  [businessPartnerPriceListAmount]
            FROM    [businessPartnerPriceList]
            WHERE   [companyId]     =   '" . $this->getCompanyId() . "'
            AND     [productId]   =	'" . $productId. "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT businessPartnerPriceListAmount
            FROM    businesspartnerpricelist
            WHERE   companyId   ='" . $this->getCompanyId() . "'
            AND    productId	=	'" . $productId . "'";
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
            $productPrice = $row['businessPartnerPriceListAmount'];
        }
        return $productPrice;
    }

    /**
     * Return Purchase Request Primary Key
     * @param type $purchaseRequestDetailId Purchase Request Detail 
     * @return int $purchaseRequestId Purchase Request
     */
    public function getPurchaseRequestId($purchaseRequestDetailId) {
        $sql = null;
        $purchaseRequestId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `purchaseRequestId`
            FROM   `purchaserequestdetail`
            WHERE  `purchaseRequestDetailId`='" . $purchaseRequestDetailId . "'";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [purchaseRequestId]
            FROM   [purchaseRequestDetail]
            WHERE  [purchaseRequestDetailId]    =   '" . $purchaseRequestDetailId . "'";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT  PURCHASEREQUESTID AS \"purchaseRequestId\"
            FROM    PURCHASEREQUESTDETAIL
            WHERE   PURCHASEREQUESTDETAILID =   '" . $purchaseRequestDetailId . "'";
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
            $purchaseRequestId = $row['purchaseRequestId'];
        }
        return $purchaseRequestId;
    }

    /**
     * Sent Message Request Been Rejected
     * @param int $purchaseRequestId
     */
    public function setMessageReject($purchaseRequestId) {
        $staffId = $this->getPurchaseRequestStaff($purchaseRequestId);
        $this->setNotification($this->getMessage(PURCHASE_REQUEST_REJECT), $staffId);
    }

    /**
     * Sent Message Request Been approved
     * @param int $purchaseRequestId
     */
    public function setMessageApproved($purchaseRequestId) {
        $staffId = $this->getPurchaseRequestStaff($purchaseRequestId);
        $this->setNotification($this->getMessage(PURCHASE_REQUEST_APPROVED), $staffId);
    }

    /**
     * Return Staff Primary Key
     * @param type $purchaseRequestId Purchase Request
     * @return int $staffId Staff
     */
    public function getPurchaseRequestStaff($purchaseRequestId) {
        $sql = null;
        $staffId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT `staffId`
            FROM   `employeestaffreference`
            WHERE  `employeeId` =( 
                                    SELECT  `employeeId` 
                                    FROM    `purchaserequest` 
                                    WHERE   `purchaseRequestId` =   " . $purchaseRequestId . "
                                 )";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT [staffId]
            FROM   [employeestaffreference]
            WHERE  [employeeId] =( 
                                    SELECT  [employeeId] 
                                    FROM    [purchaserequest] 
                                    WHERE   [purchaseRequestId] =   " . $purchaseRequestId . "
                                 )";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT STAFFID AS \"staffId\"
            FROM   EMPLOYEESTAFFREFERENCE
            WHERE  EMPLOYEEID =( 
                                    SELECT  EMPLOYEEID 
                                    FROM    PURCHASEREQUEST 
                                    WHERE   PURCHASEREQUESTID =   " . $purchaseRequestId . "
                                 )";
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
            $staffId = $row['staffId'];
        }
        return $staffId;
    }
     /**
     * Return Purchase Invoice Project Default Value
     * @return int
     */
    public function getPurchaseInvoiceProjectDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchaseInvoiceProjectId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceProjectId`
         FROM        	`purchaseinvoiceproject`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [purchaseInvoiceProjectId],
         FROM        [purchaseInvoiceProject]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEPROJECTID AS \"purchaseInvoiceProjectId\",
         FROM        PURCHASEINVOICEPROJECT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $purchaseInvoiceProjectId = $row['purchaseInvoiceProjectId'];
        }
        return $purchaseInvoiceProjectId;
    }
    /**
     * /* Create
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
     * @return \Core\System\Management\Staff\Service\StaffService
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
     * @return \Core\System\Management\Staff\Service\StaffService
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
     * @return \Core\System\Management\Staff\Service\StaffService
     */
    public function setUploadPath($value) {
        $this->uploadPath = $value;
        return $this;
    }

}

?>