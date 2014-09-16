<?php

namespace Core\Financial\FixedAsset\ItemType\Service;

// using Absolute path instead of relative path..
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
 * Class ItemTypeService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\ItemType\Service
 * @subpackage Asset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ItemTypeService extends ConfigClass {

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
     * Return ItemCategory
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
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [itemCategoryId],
                     [itemCategoryDescription]
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
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
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['itemCategoryId'] . "'>" . $row['itemCategoryDescription'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
            }
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
     * Return Total Asset By Type. Expandable analysis y company ,branch  or department or  warehouse or location
     * @param int $itemTypeId
     * @param null }int $companyId
     * @param null }int  $branchId
     * @param null|int $departmentId
     * @param null|int $warehouseId
     * @param null|int $locationId
     * @return int|null
     */
    public function getTotalAssetByType(
    $itemTypeId, $companyId = null, $branchId = null, $departmentId = null, $warehouseId = null, $locationId = null
    ) {
        $sql = null;
        $total = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  COUNT(*) AS `total`
            FROM    `asset`
            WHERE   `itemTypeId`   =   '" . $this->strict($itemTypeId, 'numeric') . "'";
            if ($companyId) {
                $sql .= " AND `companyId`='" . $this->strict($companyId, 'numeric') . "'";
            }
            if ($branchId) {
                $sql .= " AND `branchId`='" . $this->strict($branchId, 'numeric') . "'";
            }
            if ($departmentId) {
                $sql .= " AND `departmentId`='" . $this->strict($departmentId, 'numeric') . "'";
            }
            if ($warehouseId) {
                $sql .= " AND `warehouseId`='" . $this->strict($warehouseId, 'numeric') . "'";
            }
            if ($locationId) {
                $sql .= " AND `locationId`='" . $this->strict($locationId, 'numeric') . "'";
            }
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  COUNT(*) as total
            FROM    [asset]
            WHERE   [itemTypeId]   =   '" . $this->strict($itemTypeId, 'numeric') . "'";
                if ($companyId) {
                    $sql .= " AND [companyId]='" . $this->strict($companyId, 'numeric') . "'";
                }
                if ($branchId) {
                    $sql .= " AND [branchId]='" . $this->strict($branchId, 'numeric') . "'";
                }
                if ($departmentId) {
                    $sql .= " AND [departmentId]='" . $this->strict($departmentId, 'numeric') . "'";
                }
                if ($warehouseId) {
                    $sql .= " AND [warehouseId]='" . $this->strict($warehouseId, 'numeric') . "'";
                }
                if ($locationId) {
                    $sql .= " AND [locationId]='" . $this->strict($locationId, 'numeric') . "'";
                }
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) as total
            FROM    ASSET
            WHERE   ITEMTYPEID   =   '" . $this->strict($itemTypeId, 'numeric') . "'";
                    if ($companyId) {
                        $sql .= " AND COMPANYID='" . $this->strict($companyId, 'numeric') . "'";
                    }
                    if ($branchId) {
                        $sql .= " AND BRANCHID='" . $this->strict($branchId, 'numeric') . "'";
                    }
                    if ($departmentId) {
                        $sql .= " AND DEPARTMENTID='" . $this->strict($departmentId, 'numeric') . "'";
                    }
                    if ($warehouseId) {
                        $sql .= " AND WAREHOUSEID='" . $this->strict($warehouseId, 'numeric') . "'";
                    }
                    if ($locationId) {
                        $sql .= " AND LOCATIONID='" . $this->strict($locationId, 'numeric') . "'";
                    }
                }
            }
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
            $total = intval($row['total']);
        }
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