<?php

namespace Core\Financial\FixedAsset\AssetMaintenance\Service;

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
 * Class AssetMaintenanceService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetMaintenance\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetMaintenanceService extends ConfigClass {

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
     * Return Business Partner
     * @param null|int $businessPartnerId Business Partner
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [businessPartnerId],
         FROM        [businessPartner]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            $businessPartnerId = $row['businessPartnerId'];
        }
        return $businessPartnerId;
    }

    /**
     * Return Asset
     * @return array|string
     */
    public function getAsset() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetId`,
                     `assetName`
         FROM        `asset`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [assetId],
                     [assetName]
         FROM        [asset]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETID AS \"assetId\",
                     ASSETNAME AS \"assetName\"
         FROM        ASSET
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
                    $str .= "<option value='" . $row['assetId'] . "'>" . $d . ". " . $row['assetName'] . "</option>";
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
     * Return Asset Default Value
     * @return int
     */
    public function getAssetDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $assetId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetId`
         FROM        	`asset`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [assetId],
         FROM        [asset]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETID AS \"assetId\",
         FROM        ASSET
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
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
            $row = $this->q->fetchArray($result);
            $assetId = $row['assetId'];
        }
        return $assetId;
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
                     `employeeFirstName`
         FROM        `employee`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [employeeId],
                     [employeeFirstName]
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      EMPLOYEEID AS \"employeeId\",
                     EMPLOYEEFIRSTNAME AS \"employeeFirstName\"
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
                    $str .= "<option value='" . $row['employeeId'] . "'>" . $d . ". " . $row['employeeFirstName'] . "</option>";
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [employeeId],
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            $employeeId = $row['employeeId'];
        }
        return $employeeId;
    }

    /**
     * Return AssetMaintenanceCode
     * @return array|string
     */
    public function getAssetMaintenanceCode() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetMaintenanceCodeId`,
                     `assetMaintenanceCodeDescription`
         FROM        `assetmaintenancecode`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [assetMaintenanceCodeId],
                     [assetMaintenanceCodeDescription]
         FROM        [assetMaintenanceCode]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETMAINTENANCECODEID AS \"assetMaintenanceCodeId\",
                     ASSETMAINTENANCECODEDESCRIPTION AS \"assetMaintenanceCodeDescription\"
         FROM        ASSETMAINTENANCECODE
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
                    $str .= "<option value='" . $row['assetMaintenanceCodeId'] . "'>" . $d . ". " . $row['assetMaintenanceCodeDescription'] . "</option>";
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
     * Return AssetMaintenanceCode Default Value
     * @return int
     */
    public function getAssetMaintenanceCodeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $assetMaintenanceCodeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetMaintenanceCodeId`
         FROM        	`assetmaintenancecode`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [assetMaintenanceCodeId],
         FROM        [assetMaintenanceCode]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETMAINTENANCECODEID AS \"assetMaintenanceCodeId\",
         FROM        ASSETMAINTENANCECODE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
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
            $row = $this->q->fetchArray($result);
            $assetMaintenanceCodeId = $row['assetMaintenanceCodeId'];
        }
        return $assetMaintenanceCodeId;
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