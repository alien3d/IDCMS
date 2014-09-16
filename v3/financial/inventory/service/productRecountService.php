<?php

namespace Core\Financial\Inventory\ProductRecount\Service;

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
 * Class ProductRecount
 * this is productRecountservice files.This is extra collection  functions
 * @property ProductRecountModel model
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Inventory\ProductRecount\Service;
 * @subpackage Inventory
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ProductRecountService extends ConfigClass {

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
     * Model
     * @var \Core\Financial\Inventory\ProductRecount\Model\ProductRecountModel
     */
    public $model;

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
     * Return Warehouse
     * @return array|string
     */
    public function getWarehouse() {
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
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [warehouseId],
                     [warehouseDescription]
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      WAREHOUSEID AS \"warehouseId\",
                     WAREHOUSEDESCRIPTION AS \"warehouseDescription\"
         FROM        WAREHOUSE
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
         FROM        `warehouse`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [warehouseId],
         FROM        [warehouse]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            $warehouseId = $row['warehouseId'];
        }
        return $warehouseId;
    }

    /**
     * Return Product
     * @return array|string
     */
    public function getProduct() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `productId`,
                     `productDescription`
         FROM        `product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [productId],
                     [productDescription]
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      PRODUCTID AS \"productId\",
                     PRODUCTDESCRIPTION AS \"productDescription\"
         FROM        PRODUCT
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
                    $str .= "<option value='" . $row['productId'] . "'>" . $d . ". " . $row['productDescription'] . "</option>";
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
     * Return Product Default Value
     * @return int
     */
    public function getProductDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $productId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `productId`
         FROM        `product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [productId],
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      PRODUCTID AS \"productId\",
         FROM        PRODUCT
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
            $productId = $row['productId'];
        }
        return $productId;
    }

    /**
     * Return  Total Product By Warehouse
     * @param null|int $warehouseId Warehouse
     * @param null|string $date Date Recount
     * @return void
     */
    public function createDistinctProductCodeByWarehouse($warehouseId = null, $date = null) {
        header('Content-Type:application/json; charset=utf-8');
        $start = microtime(true);
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            try {
                $this->q->fast($sql);
            } catch (\Exception $e) {
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                exit();
            }
        }
        $this->q->start();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT  DISTINCT `productCode`
					COUNT(*) AS total
			FROM	`product`
			WHERE	`companyId`			=	'" . $this->getCompanyId() . "'
			AND		`warehouseId`		=	'" . $warehouseId . "'
			GROUP	BY `productCode`";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT  	DISTINCT [productCode],

						COUNT(*) AS total
			FROM		[product]
			WHERE		[companyId]		=	'" . $this->getCompanyId() . "'
			AND			[warehouseId]	=	'" . $warehouseId . "'
			GROUP	BY	[productCode]	";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT  	DISTINCT  PRODUCTCODE,
								  PRODUCTDESCRIPTION
						COUNT(*) AS TOTAL
			FROM		PRODUCT`
			WHERE		COMPANYID		=	'" . $this->getCompanyId() . "'
			AND			WAREHOUSEID		=	'" . $warehouseId . "'
			GROUP BY 	PRODUCTCODE,
						PRODUCTNAME";
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $sql = null;
        if (!$this->model->getWarehouseId()) {
            $this->model->setWarehouseId($this->getWarehouseDefaultValue());
        }
        if ($date) {
            $this->model->setProductRecountDate($date);
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                $this->model->setProductCode($row['productDescription']);
                $this->model->setProductDescription($row['productDescription']);
                $this->model->setProductRecountSystemQuantity($row['total']);
                // check if exist recount.
                if (!$this->getExistProductRecount()) {
                    $this->setRecountProduct();
                } else {
                    $this->deleteRecountProduct();
                    $this->setRecountProduct();
                }
            }
        }
        $this->q->commit();
        $end = microtime(true);
        $time = $end - $start;
        echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->t['newRecordTextLabel'],
                    "staffName" => $_SESSION['staffName'],
                    "executeTime" => date('d-m-Y H:i:s'),
                    "time" => $time
                )
        );
        exit();
    }

    /**
     *  Return If Exist The Record
     */
    private function getExistProductRecount() {
        return false;
    }

    /**
     *  Insert Record To Recount Table
     */
    private function setRecountProduct() {

        //$this->model->setDocumentNumber($this->getDocumentNumber);
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `productrecount` 
            (
                 `companyId`,
                 `warehouseId`,
                 `productCode`,
                 `productDescription`,
                 `productRecountDate`,
                 `productRecountSystemQuantity`,
                 `productRecountPhysicalQuantity`,
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
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getProductCode() . "',
                 '" . $this->model->getProductDescription() . "',
                 '" . $this->model->getProductRecountDate() . "',
                 '" . $this->model->getProductRecountSystemQuantity() . "',
                 '" . $this->model->getProductRecountPhysicalQuantity() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
       );";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            INSERT INTO [productRecount]
            (
                 [productRecountId],
                 [companyId],
                 [warehouseId],
                 [productCode],
                 [productDescription],
                 [productRecountDate],
                 [productRecountSystemQuantity],
                 [productRecountPhysicalQuantity],
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
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getProductCode() . "',
                 '" . $this->model->getProductDescription() . "',
                 '" . $this->model->getProductRecountDate() . "',
                 '" . $this->model->getProductRecountSystemQuantity() . "',
                 '" . $this->model->getProductRecountPhysicalQuantity() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            INSERT INTO PRODUCTRECOUNT
            (
                 COMPANYID,
                 WAREHOUSEID,
                 PRODUCTCODE,
                 PRODUCTDESCRIPTION,
                 PRODUCTRECOUNTDATE,
                 PRODUCTRECOUNTSYSTEMQUANTITY,
                 PRODUCTRECOUNTPHYSICALQUANTITY,
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
                 '" . $this->model->getWarehouseId() . "',
                 '" . $this->model->getProductCode() . "',
                 '" . $this->model->getProductDescription() . "',
                 '" . $this->model->getProductRecountDate() . "',
                 '" . $this->model->getProductRecountSystemQuantity() . "',
                 '" . $this->model->getProductRecountPhysicalQuantity() . "',
                 '" . $this->model->getIsDefault(0, 'single') . "',
                 '" . $this->model->getIsNew(0, 'single') . "',
                 '" . $this->model->getIsDraft(0, 'single') . "',
                 '" . $this->model->getIsUpdate(0, 'single') . "',
                 '" . $this->model->getIsDelete(0, 'single') . "',
                 '" . $this->model->getIsActive(0, 'single') . "',
                 '" . $this->model->getIsApproved(0, 'single') . "',
                 '" . $this->model->getIsReview(0, 'single') . "',
                 '" . $this->model->getIsPost(0, 'single') . "',
                 '" . $this->model->getExecuteBy() . "',
                 " . $this->model->getExecuteTime() . "
            );";
                }
            }
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
     * Delete / Update Flag Recount Product
     * return void
     */
    private function deleteRecountProduct() {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
               UPDATE  `productrecount` 
               SET     `isDefault`     		=   '" . $this->model->getIsDefault(0, 'single') . "',
                       `isNew`         		=   '" . $this->model->getIsNew(0, 'single') . "',
                       `isDraft`       		=   '" . $this->model->getIsDraft(0, 'single') . "',
                       `isUpdate`      		=   '" . $this->model->getIsUpdate(0, 'single') . "',
                       `isDelete`      		=   '" . $this->model->getIsDelete(0, 'single') . "',
                       `isActive`      		=   '" . $this->model->getIsActive(0, 'single') . "',
                       `isApproved`    		=   '" . $this->model->getIsApproved(0, 'single') . "',
                       `isReview`      		=   '" . $this->model->getIsReview(0, 'single') . "',
                       `isPost`        		=   '" . $this->model->getIsPost(0, 'single') . "',
                       `executeBy`     		=   '" . $this->model->getExecuteBy() . "',
                       `executeTime`   		=   " . $this->model->getExecuteTime() . "
               WHERE   `productCode`   		=  	'" . $this->model->getProductCode() . "'
			   AND	   `productRecountDate`	=	'" . $this->model->getProductRecountDate() . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
               UPDATE  [productRecount]
               SET     [isDefault]     		=   '" . $this->model->getIsDefault(0, 'single') . "',
                       [isNew]         		=   '" . $this->model->getIsNew(0, 'single') . "',
                       [isDraft]       		=   '" . $this->model->getIsDraft(0, 'single') . "',
                       [isUpdate]      		=   '" . $this->model->getIsUpdate(0, 'single') . "',
                       [isDelete]      		=   '" . $this->model->getIsDelete(0, 'single') . "',
                       [isActive]      		=   '" . $this->model->getIsActive(0, 'single') . "',
                       [isApproved]    		=   '" . $this->model->getIsApproved(0, 'single') . "',
                       [isReview]      		=   '" . $this->model->getIsReview(0, 'single') . "',
                       [isPost]        		=   '" . $this->model->getIsPost(0, 'single') . "',
                       [executeBy]     		=   '" . $this->model->getExecuteBy() . "',
                       [executeTime]   		=   " . $this->model->getExecuteTime() . "
               WHERE   [productCode]   		=  	'" . $this->model->getProductCode() . "'
			   AND	   [productRecountDate]	=	'" . $this->model->getProductRecountDate() . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
               UPDATE  PRODUCTRECOUNT
               SET     ISDEFAULT       		=   '" . $this->model->getIsDefault(0, 'single') . "',
                       ISNEW           		=   '" . $this->model->getIsNew(0, 'single') . "',
                       ISDRAFT         		=   '" . $this->model->getIsDraft(0, 'single') . "',
                       ISUPDATE        		=   '" . $this->model->getIsUpdate(0, 'single') . "',
                       ISDELETE        		=   '" . $this->model->getIsDelete(0, 'single') . "',
                       ISACTIVE        		=   '" . $this->model->getIsActive(0, 'single') . "',
                       ISAPPROVED      		=   '" . $this->model->getIsApproved(0, 'single') . "',
                       ISREVIEW        		=   '" . $this->model->getIsReview(0, 'single') . "',
                       ISPOST          		=   '" . $this->model->getIsPost(0, 'single') . "',
                       EXECUTEBY       		=   '" . $this->model->getExecuteBy() . "',
                       EXECUTETIME     		=   " . $this->model->getExecuteTime() . "
               WHERE   PRODUCTCODE   		=  	'" . $this->model->getProductCode() . "'
			   AND	   PRODUCTRECOUNTDATE	=	'" . $this->model->getProductRecountDate() . "'";
                }
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
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