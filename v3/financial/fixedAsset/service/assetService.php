<?php

namespace Core\Financial\FixedAsset\Asset\Service;

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

/**
 * Class Asset
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\Asset\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetService extends ConfigClass {

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
                $sql.=" AND `countryId`='" . $this->getCountryId() . "'";
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
                $sql.=" AND  COUNTRYID='" . $this->getCountryId() . "'";
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
     * @return array|string
     */
    public function getDepartment($countryId = null, $branchId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `departmentId`,
                     `departmentDescription`
         FROM        `department`
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
            $sql.=" ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [departmentId],
                     [departmentDescription]
         FROM        [department]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND [countryId]='" . $countryId . "'";
            } else {
                $sql.=" AND [countryId]='" . $this->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND [branchId]='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND [branchId]='" . $this->getBranchId() . "'";
            }
            $sql.=" ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      DEPARTMENTID AS \"departmentId\",
                     DEPARTMENTDESCRIPTIONRIPTION AS \"departmentDescription\"
         FROM        DEPARTMENT
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND `countryId`='" . $countryId . "'";
            } else {
                $sql.=" AND `countryId`='" . $this->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND BRANCHID='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND BRANCHID='" . $this->getBranchId() . "'";
            }
            $sql.=" ORDER BY    ISDEFAULT";
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
                $sql.=" AND `countryId`='" . $this->getCountryId() . "'";
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
                $sql.=" AND [countryId]='" . $this->getCountryId() . "'";
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
                $sql.=" AND  COUNTRYID='" . $this->getCountryId() . "'";
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
     * Return Location
     * @param null|int $countryId Country Primary Key
     * @param null|int $branchId Branch Primary Key
     * @param null|int $departmentId Department Primary Key
     * @param null|int $warehouseId Warehouse Primary Key
     * @return array|string
     */
    public function getLocation($countryId = null, $branchId = null, $departmentId = null, $warehouseId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `locationId`,
                     `locationDescription`
         FROM        `location`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND `countryId`='" . $countryId . "'";
            } else {
                $sql.=" AND `countryId`='" . $this->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND `branchId`='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND `branchId`='" . $this->getBranchId() . "'";
            }

            if ($departmentId) {
                $sql.=" AND `departmentId`='" . $departmentId . "'";
            }
            if ($warehouseId) {
                $sql.=" AND `warehouseId`='" . $warehouseId . "'";
            }
            $sql.="
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [locationId],
                     [locationDescription]
         FROM        [location]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND [countryId]='" . $countryId . "'";
            } else {
                $sql.=" AND [countryId]='" . $this->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND [branchId]='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND [branchId]='" . $this->getBranchId() . "'";
            }

            if ($departmentId) {
                $sql.=" AND [departmentId]='" . $departmentId . "'";
            }
            if ($warehouseId) {
                $sql.=" AND [warehouseId]='" . $warehouseId . "'";
            }
            $sql.="
        
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      LOCATIONID AS \"locationId\",
                     LOCATIONDESCRIPTION AS \"locationDescription\"
         FROM        LOCATION
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql.=" AND COUNTRYID='" . $countryId . "'";
            } else {
                $sql.=" AND COUNTRYID='" . $this->getCountryId() . "'";
            }
            if ($branchId) {
                $sql.=" AND BRANCHID ='" . $branchId . "'";
            } else if (strlen($this->getBranchId()) > 0) {
                $sql.=" AND BRANCHID='" . $this->getBranchId() . "'";
            }

            if ($departmentId) {
                $sql.=" AND DEPARTMENTID='" . $departmentId . "'";
            }
            if ($warehouseId) {
                $sql.=" AND WAREHOUSEID='" . $warehouseId . "'";
            }
            $sql.="
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
                    $str .= "<option value='" . $row['locationId'] . "'>" . $d . ". " . $row['locationDescription'] . "</option>";
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
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Location Default Value
     * @return int
     */
    public function getLocationDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $locationId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `locationId`
         FROM        	`location`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [locationId],
         FROM        [location]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      LOCATIONID AS \"locationId\",
         FROM        LOCATION
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
            $locationId = $row['locationId'];
        }
        return $locationId;
    }

    /**
     * Return Item Category
     * @return array|string
     */
    public function getItemCategory() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemCategoryId`,
                     `itemCategoryDescription`
         FROM        `itemcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [itemCategoryId],
                     [itemCategoryDescription]
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ITEMCATEGORYID AS \"itemCategoryId\",
                     ITEMCATEGORYDESCRIPTION AS \"itemCategoryDescription\"
         FROM        ITEMCATEGORY
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
                    $str .= "<option value='" . $row['itemCategoryId'] . "'>" . $d . ". " . $row['itemCategoryDescription'] . "</option>";
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
     * Return Item Category Default Value
     * @return int
     */
    public function getItemCategoryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $itemCategoryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemCategoryId`
         FROM        	`itemcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [itemCategoryId],
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ITEMCATEGORYID AS \"itemCategoryId\",
         FROM        ITEMCATEGORY
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
            $itemCategoryId = $row['itemCategoryId'];
        }
        return $itemCategoryId;
    }

    /**
     * Return ItemType
     * @return array|string
     */
    public function getItemType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemTypeId`,
                     `itemTypeDescription`
         FROM        `itemtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [itemTypeId],
                     [itemTypeDescription]
         FROM        [itemType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ITEMTYPEID AS \"itemTypeId\",
                     ITEMTYPEDESCRIPTION AS \"itemTypeDescription\"
         FROM        ITEMTYPE
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
                    $str .= "<option value='" . $row['itemTypeId'] . "'>" . $d . ". " . $row['itemTypeDescription'] . "</option>";
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
     * Return ItemType Default Value
     * @return int
     */
    public function getItemTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $itemTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemTypeId`
         FROM        	`itemtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [itemTypeId],
         FROM        [itemType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      ITEMTYPEID AS \"itemTypeId\",
         FROM        ITEMTYPE
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
            $itemTypeId = $row['itemTypeId'];
        }
        return $itemTypeId;
    }

    /**
     * Return Business Partner
     * @param null|int $businessPartnerId Business Partner Primary Key
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
			WHERE       BUSINESSPARTNER.ISACTIVE    						=   1";
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
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $d . ". " . $row['businessPartnerCompany'] . "</option>";
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
     * Return BusinessPartner Default Value
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
     * Return Unit Of Measurement
     * @return array|string
     */
    public function getUnitOfMeasurement() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `unitofmeasurement`.`unitOfMeasurementId`,
                     `unitofmeasurement`.`unitOfMeasurementDescription`,
					 `unitofmeasurementcategory`.`unitOfMeasurementCategoryDescription`
         FROM        `unitofmeasurement`
		 JOIN		 `unitofmeasurementcategory`
		 USING		 (`companyId`,`unitOfMeasurementCategoryId`)
         WHERE       `unitofmeasurement`.`isActive`  =   1
         AND         `unitofmeasurement`.`companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `unitofmeasurement`.`isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [unitOfMeasurement].[unitOfMeasurementId],
                     [unitOfMeasurement].[unitOfMeasurementDescription],
					 [unitOfMeasurementCategory].[unitOfMeasurementCategoryDescription]
         FROM        [unitOfMeasurement]
		 JOIN		 [unitOfMeasurementCategory]
		 ON          [unitOfMeasurement].[companyId] 					= 	[unitOfMeasurementCategory].[companyId]
		 AND		 [unitOfMeasurement].[unitOfMeasurementCategoryId] 	= 	[unitOfMeasurementCategory].[unitOfMeasurementCategoryId]
         WHERE       [unitOfMeasurement].[isActive]  					=   1
         AND         [unitOfMeasurement].[companyId] 					=   '" . $this->getCompanyId() . "'
         ORDER BY    [unitOfMeasurement].[isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      UNITOFMEASUREMENT.IUNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
                     UNITOFMEASUREMENT.IUNITOFMEASUREMENTDESCRIPTION AS \"unitOfMeasurementDescription\",
					 UNITOFMEASUREMENTCATEGORY.IUNITOFMEASUREMENTCATEGORYDESCRIPTION AS \"unitOfMeasurementCategoryDescription\"
         FROM        UNITOFMEASUREMENT
		 JOIN		 UNITOFMEASUREMENTCATEGORY
		 ON          UNITOFMEASUREMENT.COMPANYID 					=	UNITOFMEASUREMENTCATEGORY.COMPANYID
		 AND		 UNITOFMEASUREMENT.UNITOFMEASUREMENTCATEGORYID 	= 	UNITOFMEASUREMENTCATEGORY.UNITOFMEASUREMENTCATEGORYID
         WHERE       UNITOFMEASUREMENT.IISACTIVE    				=   1
         AND         UNITOFMEASUREMENT.ICOMPANYID   				=   '" . $this->getCompanyId() . "'
         ORDER BY    UNITOFMEASUREMENT.ISDEFAULT";
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
                    $str .= "<option value='" . $row['unitOfMeasurementId'] . "'>" . $d . ". " . $row['unitOfMeasurementDescription'] . "</option>";
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
     * Return UnitOfMeasurement Default Value
     * @return int
     */
    public function getUnitOfMeasurementDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $unitOfMeasurementId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `unitOfMeasurementId`
         FROM        	`unitofmeasurement`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [unitOfMeasurementId],
         FROM        [unitOfMeasurement]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      UNITOFMEASUREMENTID AS \"unitOfMeasurementId\",
         FROM        UNITOFMEASUREMENT
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
            $unitOfMeasurementId = $row['unitOfMeasurementId'];
        }
        return $unitOfMeasurementId;
    }

    /**
     * Return Purchase Invoice
     * @param null|int $businessPartnerId Business Partner
     * @return array|string
     */
    public function getPurchaseInvoice($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `purchaseInvoiceId`,
                        `purchaseInvoiceDescription`,
                        `purchaseInvoiceProjectTitle`
            FROM        `purchaseinvoice`
            JOIN        `purchaseinvoiceproject`
            USINg       (`companyId`,`purchaseInvoiceProjectId`)
            WHERE       `purchaseinvoice`.`isActive`  =   1
            AND         `purchaseinvoice`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND `purchaseinvoice`.`businessPartnerId`='" . $businessPartnerId . "'";
            }
            $sql.="ORDER BY    `purchaseinvoice`.`isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [purchaseInvoice].[purchaseInvoiceId],
                        [purchaseInvoice].[purchaseInvoiceDescription],
                        [purchaseInvoiceProject].[purchaseInvoiceProjectTitle]
            FROM        [purchaseInvoice]
            JOIN        [purchaseInvoiceProject]
            ON          [purchaseInvoice].[companyId] = [purchaseInvoiceProject].[companyId]
            AND         [purchaseInvoice].[purchaseInvoiceProjectId] = [purchaseInvoiceProject].[purchaseInvoiceProjectId]
            WHERE       [purchaseInvoice].[isActive]  =   1
            AND         [purchaseInvoice].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND [purchaseInvoice].[businessPartnerId]='" . $businessPartnerId . "'";
            }
            $sql.="
            ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      PURCHASEINVOICE.PURCHASEINVOICEID AS \"purchaseInvoiceId\",
                        PURCHASEINVOICE.PURCHASEINVOICECEDESCRIPTION AS \"purchaseInvoiceDescription\",
                        PURCHASEINVOICEPROJECT.PURCHASEINVOICEPROJECTTITLE AS \"purchaseInvoiceProjectTitle\"
            FROM        PURCHASEINVOICE
            JOIN        PURCHASEINVOICEPROJECT
            ON          PURCHASEINVOICE.COMPANYID = PURCHASEINVOICEPROJECT.COMPANYID
            AND         PURCHASEINVOICE.PURCHASEINVOICEPROJECTID = PURCHASEINVOICEPROJECT.PURCHASEINVOICEPROJECTID
            WHERE       PURCHASEINVOICE.ISACTIVE    =   1
            AND         PURCHASEINVOICE.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerId) {
                $sql.=" AND PURCHASEINVOICE.BUSINESSPARTNERID='" . $businessPartnerId . "'";
            }
            $sql.="
            ORDER BY    PURCHASEINVOICE.ISDEFAULT";
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
            $purchaseInvoiceProjectTitle = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($purchaseInvoiceProjectTitle != $row['purchaseInvoiceProjectTitle']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['purchaseInvoiceProjectTitle'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['purchaseInvoiceProjectTitle'] . "\">";
                }
                $purchaseInvoiceProjectTitle = $row['purchaseInvoiceProjectTitle'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['purchaseInvoiceId'] . "'>" . $d . ". " . $row['purchaseInvoiceDescription'] . "</option>";
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
        } else if ($this->getServiceOutput() == 'html') {
            return $items;
        }
        return false;
    }

    /**
     * Return Purchase Invoice Default Value
     * @return int
     */
    public function getPurchaseInvoiceDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $purchaseInvoiceId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `purchaseInvoiceId`
         FROM        	`purchaseinvoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [purchaseInvoiceId],
         FROM        [purchaseInvoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      PURCHASEINVOICEID AS \"purchaseInvoiceId\",
         FROM        PURCHASEINVOICE
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
            $purchaseInvoiceId = $row['purchaseInvoiceId'];
        }
        return $purchaseInvoiceId;
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