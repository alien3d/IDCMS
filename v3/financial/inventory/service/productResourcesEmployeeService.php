<?php

namespace Core\Financial\Inventory\ProductResourcesEmployee\Service;

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
require_once ($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class ProductResourcesEmployeeService
 * Contain extra processing function / method.
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package Core\Financial\Inventory\ProductResourcesEmployee\Service
 * @subpackage Inventory 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class ProductResourcesEmployeeService extends ConfigClass {

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
     * get master setting of the counter.. too heavy to render all city
     */
    private function getOverrideCountry() {
        $sql = null;
        $countryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT countryId FROM `systemsetting` WHERE `companyId`='" . $this->getCompanyId() . "'
			";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT [countryId] FROM [systemSetting] WHERE [companyId] ='" . $this->getCompanyId() . "'
			";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT COUNTRYID FROM SYSTEMSETTING WHERE COMPANYID='" . $this->getCompanyId() . "'
			";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $countryId = $row['countryId'];
        } else {
            
        }
        return $countryId;
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
                     `productResourcesTask`
         FROM        `productresources`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [productResourcesId],
                     [productResourcesTask]
         FROM        [productResources]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      PRODUCTRESOURCESID AS \"productResourcesId\",
                     PRODUCTRESOURCESDESCRIPTION AS \"productResourcesTask\"
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
            while (($row = $this->q->fetchArray($result)) == TRUE) {
                if ($this->getServiceOutput() == 'option') {
                    $str.="<option value='" . $row['productResourcesId'] . "'>" . $d . ". " . $row['productResourcesDescription'] . "</option>";
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
     * Return ProductResourcesType
     * @return array|string
     */
    public function getProductResourcesType() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `productResourcesTypeId`,
                     `productResourcesTypeDescription`
         FROM        `productresourcestype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [productResourcesTypeId],
                     [productResourcesTypeDescription]
         FROM        [productResourcesType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      PRODUCTRESOURCESTYPEID AS \"productResourcesTypeId\",
                     PRODUCTRESOURCESTYPEDESCRIPTION AS \"productResourcesTypeDescription\"
         FROM        PRODUCTRESOURCESTYPE  
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
                    $str.="<option value='" . $row['productResourcesTypeId'] . "'>" . $d . ". " . $row['productResourcesTypeDescription'] . "</option>";
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
     * Return ProductResourcesType Default Value
     * @return int
     */
    public function getProductResourcesTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $productResourcesTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `productResourcesTypeId`
         FROM        	`productresourcestype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [productResourcesTypeId],
         FROM        [productResourcesType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      PRODUCTRESOURCESTYPEID AS \"productResourcesTypeId\",
         FROM        PRODUCTRESOURCESTYPE  
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
            $productResourcesTypeId = $row['productResourcesTypeId'];
        }
        return $productResourcesTypeId;
    }

    /**
     * Return Job
     * @param null|int $countryId
     * @return array|string
     */
    public function getJob($countryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT	`job`.`jobId`,
					`job`.`jobTitle`,
					`jobcategory`.`jobCategoryDescription` 
			FROM	`job`
			JOIN	`jobcategory`
			USING	(`companyId`,`jobCategoryId`)
			WHERE	`job`.`isActive`  =   1
			AND		`job`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND	`job`.`countryId`	=	'" . $countryId . "'";
            } else {
                $sql .= "
				AND	`job`.`countryId`='" . $this->getOverrideCountry() . "'";
            }
            $sql .= "
			ORDER BY    `job`.`isDefault` DESC;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT	[job].[jobId],
					[job].[jobTitle],
					[jobCategory].[jobCategoryDescription]
			FROM	[job]
			JOIN	[jobCategory]
			ON		[job].[companyId] 	= [jobCategory].[companyId]
			AND		[job].[jobId] 		= [jobCategory].[jobId]
			WHERE	[job].[isActive]  =   1
			AND		[job].[companyId] =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND	[job].[countryId	=	'" . $countryId . "'";
            } else {
                $sql .= "
				AND	[job].[countryId]='" . $this->getOverrideCountry() . "'";
            }
            $sql .= "
			ORDER BY    [job].[isDefault] DESC";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      JOBID AS \"jobId\",
						JOBTITLE AS \"jobTitle\",
						JOBCATEGORY.JOBCATEGORYDESCRIPTION AS \"jobCategoryDescription\"
			FROM        JOB
			JOIN		JOBCATEGORY
			ON			JOB.COMPANYID 	= JOBCATEGORY.COMPANYID
			AND			JOB.JOBID 		= JOBCATEGORY.JOBID
			WHERE       JOB.ISACTIVE    =   1
			AND         JOB.COMPANYID   =   '" . $this->getCompanyId() . "'";
            if ($countryId) {
                $sql .= "
				AND	 JOB.COUNTRYID	=	'" . $countryId . "'";
            } else {
                $sql .= "
				AND	JOB.COUNTRYID	=	'" . $this->getOverrideCountry() . "'";
            }
            $sql .= "
			ORDER BY    JOB.ISDEFAULT DESC";
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
                    $str.="<option value='" . $row['jobId'] . "'>" . $d . ". " . $row['jobTitle'] . "</option>";
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
     * Return Job Default Value
     * @return int
     */
    public function getJobDefaultValue() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $jobId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
         SELECT      `jobId`
         FROM        	`job`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      TOP 1 [jobId],
         FROM        [job]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = " 
         SELECT      JOBID AS \"jobId\",
         FROM        JOB  
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
            $jobId = $row['jobId'];
        }
        return $jobId;
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = " 
         SELECT      [employeeId],
                     [employeeFirstName]
         FROM        [employee]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
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
                    $str.="<option value='" . $row['employeeId'] . "'>" . $d . ". " . $row['employeeFirstName'] . "</option>";
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
      /* Create
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