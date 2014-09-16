<?php

namespace Core\Financial\FixedAsset\ItemCategory\Service;

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
 * Class ItemCategoryService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\ItemCategory\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ItemCategoryService extends ConfigClass {

    /**
     * Fixed Asset
     */
    const FIXEDASSET = 4;

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
     * @return void
     */
    function __construct() {
        parent::__construct();
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
    }

    /**
     * Return ItemCategoryAccumulativeDepreciationAccounts
     * Data Actually come from Chart Of Account Table
     * @return array|string
     */
    public function getItemCategoryAccumulativeDepreciationAccounts() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT      `chartofaccount`.`chartOfAccountId`,
                        `chartofaccount`.`chartOfAccountNumber`,
                        `chartofaccount`.`chartOfAccountTitle`,
                        `chartofaccounttype`.`chartOfAccountTypeDescription`
            FROM        `chartofaccount`
            JOIN        `chartofaccounttype`
            USING       (`companyId`,`chartOfAccountTypeId`)
            WHERE       `chartofaccount`.`isActive`  =   1
            AND         `chartofaccount`.`companyId` =   '" . $this->getCompanyId() . "'
            AND         `chartofaccount`.`chartOfAccountTypeId` IN ('" . self::FIXEDASSET . "')
            ORDER BY    `chartofaccount`.`chartOfAccountNumber`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [chartOfAccount].[chartOfAccountId],
                        [chartOfAccount].[chartOfAccountNumber],
                        [chartOfAccount].[chartOfAccountTitle],
                        [chartOfAccountType].[chartOfAccountTypeDescription]
            FROM        [chartOfAccount]
            ON          [chartOfAccount].[companyId]   = [chartOfAccountType].[companyId]
            AND         [chartOfAccount].[chartOfAccountTypeId]   = [chartOfAccountType].[chartOfAccountTypeId]
            WHERE       [chartOfAccount].[isActive]  =   1
            AND         [chartOfAccount].[companyId] =   '" . $this->getCompanyId() . "'
            AND         [chartOfAccount].[chartOfAccountTypeId] IN ('" . self::FIXEDASSET . "')
            ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      CHARTOFACCOUNT.CHARTOFACCOUNTID AS \"chartOfAccountId\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER AS \"chartOfAccountNumber\",
                        CHARTOFACCOUNT.CHARTOFACCOUNTTITLE AS \"chartOfAccountTitle\",
                         CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
            FROM        CHARTOFACCOUNT
            ON          CHARTOFACCOUNT.COMPANYID               =   CHARTOFACCOUNTTYPE.COMPANYID
            AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    =   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
            WHERE       CHARTOFACCOUNT.ISACTIVE    =   1
            AND         CHARTOFACCOUNT.COMPANYID   =   '" . $this->getCompanyId() . "'
            AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID IN ('" . self::FIXEDASSET . "')
            ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER ";
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
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " - " . $row['chartOfAccountTitle'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
            }
        }
        if ($this->getServiceOutput() == 'option') {
            if (strlen($str) > 0) {
                return "<option value=''>" . $this->t['pleaseSelectTextLabel'] . "</option>" . $str;
            } else {
                return "<option value=''>" . $this->t['notAvailableTextLabel'] . "</option>";
            }
        } else {
            if ($this->getServiceOutput() == 'html') {
                return $items;
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Total Asset Category
     * @param int $itemCategoryId
     * @return int|null
     */
    public function getTotalItemCategoryId($itemCategoryId) {
        $sql = null;
        $total = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  COUNT(*) AS `total`
            FROM    `itemtype`
            WHERE   `itemCategoryId`   =   '" . $this->strict($itemCategoryId, 'numeric') . "'";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  COUNT(*) as total
            FROM    [itemType]
            WHERE   [itemCategoryId]   =   '" . $this->strict($itemCategoryId, 'numeric') . "'";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  COUNT(*) as total
            FROM    ITEMTYPE
            WHERE   ITEMCATEGORYID   =   '" . $this->strict($itemCategoryId, 'numeric') . "'";
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
     * Return Total Asset By Category.Expandable analysis y company ,branch  or department or  warehouse or location
     * @param int $itemCategoryId
     * @param null }int $companyId
     * @param null }int  $branchId
     * @param null|int $departmentId
     * @param null|int $warehouseId
     * @param null|int $locationId
     * @return int|null
     */
    public function getTotalItemByGroup(
    $itemCategoryId, $companyId = null, $branchId = null, $departmentId = null, $warehouseId = null, $locationId = null
    ) {
        $sql = null;
        $total = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  COUNT(*) AS `total`
            FROM    `asset`
            WHERE   `itemCategoryId`   =   '" . $this->strict($itemCategoryId, 'numeric') . "'";
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
            WHERE   [itemCategoryId]   =   '" . $this->strict($itemCategoryId, 'numeric') . "'";
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
            WHERE   ITEMCATEGORYID   =   '" . $this->strict($itemCategoryId, 'numeric') . "'";
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
     *  Create
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
     * Update
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