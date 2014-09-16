<?php

namespace Core\Financial\FixedAsset\AssetShared\Service;

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
 * Class AssetSharedService
 * This is Shared Service.The common call,application programming interface(api) library  fo Fixed Asset Module
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetShared\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetSharedService extends ConfigClass {

    /**
     * Write Off Asset
     */
    const WRITE_OFF = 'WRITE_OFF';

    /**
     * Dispose Asset
     */
    const DISPOSE = 'DISPOSE';

    /**
     * Readjust  or Revalue figure of the asset.
     */
    const ADJUST = 'ADJUST';

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
                     `itemCategoryDesc`
         FROM        `itemcategory`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `itemCategorySequence`,
                         `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [itemCategoryId],
                     [itemCategoryDesc]
         FROM        [itemCategory]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [itemCategorySequence],
                         [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ITEMCATEGORYID AS \"itemCategoryId\",
                     ITEMCATEGORYDESC AS \"itemCategoryDesc\"
         FROM        ITEMCATEGORY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    ITEMCATEGORYSEQUENCE,
                     ISDEFAULT";
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
                    $str .= "<option value='" . $row['itemCategoryId'] . "'>" . $row['itemCategoryDesc'] . "</option>";
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
     * Return ItemType
     * @param null|int $itemCategoryId
     * @return array|string
     */
    public function getItemType($itemCategoryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `itemTypeId`,
                     `itemTypeDesc`
         FROM        `itemtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($itemCategoryId) {
                $sql .= " AND `itemCategoryId`='" . $this->strict($itemCategoryId, 'numeric') . "'";
            }
            $sql .= "
         ORDER BY    `itemTypeSequence`,
                         `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [itemTypeId],
                     [itemTypeDesc]
         FROM        [itemType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
                if ($itemCategoryId) {
                    $sql .= " AND [itemCategoryId]='" . $this->strict($itemCategoryId, 'numeric') . "'";
                }
                $sql .= "
         ORDER BY    [itemTypeSequence],
                         [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ITEMTYPEID AS \"itemTypeId\",
                     ITEMTYPEDESC AS \"itemTypeDesc\"
         FROM        ITEMTYPE
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
                    if ($itemCategoryId) {
                        $sql .= " AND ITEMCATEGORYID='" . $this->strict($itemCategoryId, 'numeric') . "'";
                    }
                    $sql .= "
         ORDER BY    ITEMTYPESEQUENCE,
                     ISDEFAULT";
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
                    $str .= "<option value='" . $row['itemTypeId'] . "'>" . $row['itemTypeDesc'] . "</option>";
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
            } else {
                echo "undefined  service output";
            }
        }
        // fake return
        return $items;
    }

    /**
     * Return Asset
     * @param null|int $itemCategoryId
     * @param null|int $itemTypeId
     * @return array|string
     */
    public function getAsset($itemCategoryId = null, $itemTypeId = null) {
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
         AND         `companyId` =   '" . $this->getCompanyId() . "'";
            if ($itemCategoryId) {
                $sql .= " AND `itemCategoryId`='" . $this->strict($itemCategoryId, 'numeric') . "'";
            }
            if ($itemTypeId) {
                $sql .= " AND `itemTypeId`='" . $this->strict($itemTypeId, 'numeric') . "'";
            }
            $sql .= "
         ORDER BY    `assetSequence`,
                         `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [assetId],
                     [assetName]
         FROM        [asset]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'";
                if ($itemCategoryId) {
                    $sql .= " AND [itemCategoryId]='" . $this->strict($itemCategoryId, 'numeric') . "'";
                }
                if ($itemTypeId) {
                    $sql .= " AND [itemTypeId]='" . $this->strict($itemCategoryId, 'numeric') . "'";
                }
                $sql .= "ORDER BY    [assetSequence],
                         [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETID AS \"assetId\",
                     ASSETNAME AS \"assetName\"
         FROM        ASSET
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'";
                    if ($itemCategoryId) {
                        $sql .= " AND `ITEMCATEGORYID`='" . $this->strict($itemCategoryId, 'numeric') . "'";
                    }
                    if ($itemTypeId) {
                        $sql .= " AND `ITEMTYPEID`='" . $this->strict($itemCategoryId, 'numeric') . "'";
                    }
                    $sql .= "ORDER BY    ASSETSEQUENCE,
                     ISDEFAULT";
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
                    $str .= "<option value='" . $row['assetId'] . "'>" . $row['assetName'] . "</option>";
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
     * Update Fixed Asset Status
     * @param int $assetId Asset
     * @param string $status Status Of Asset ->Write Off,Dispose Revalue
     */
    public function updateFixedAssetStatus($assetId, $status) {
        $sql = null;
        if ($status == self::WRITE_OFF) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                UPDATE `asset`
                SET    `isWriteOff`     =   1,
                       `isActive`       =   0,
                       `isDelete`       =   1
                WHERE  `assetId`        =   '" . $this->strict($assetId, 'numeric') . "'";
            } else {
                if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                UPDATE  [asset]
                SET     [isWriteOff]    =   1,
                        [isActive]      =   0,
                        [isDelete]      =   1
                WHERE   [assetId]       =   '" . $this->strict($assetId, 'numeric') . "'";
                } else {
                    if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                UPDATE  ASSET
                SET     ISWRITEOFF      =   1,
                        ISACTIVE        =   0,
                        ISDELETE        =   1
                WHERE   ASSETID         =   '" . $this->strict($assetId, 'numeric') . "'";
                    }
                }
            }
        } else {
            if ($status == self::DISPOSE) {
                if ($this->getVendor() == self::MYSQL) {
                    $sql = "
                UPDATE `asset`
                SET    `isDispose`      =   1,
                       `isActive`       =   0,
                       `isDelete`       =   1
                WHERE  `assetId`        =   '" . $this->strict($assetId, 'numeric') . "'";
                } else {
                    if ($this->getVendor() == self::MSSQL) {
                        $sql = "
                UPDATE  [asset]
                SET     [isDispose]     =   1,
                        [isActive]      =   0,
                        [isDelete]      =   1
                WHERE   [assetId]       =   '" . $this->strict($assetId, 'numeric') . "'";
                    } else {
                        if ($this->getVendor() == self::ORACLE) {
                            $sql = "
                UPDATE  ASSET
                SET     ISDISPOSE       =   1,
                        ISACTIVE        =   0,
                        ISDELETE        =   1
                WHERE   ASSETID         =   '" . $this->strict($assetId, 'numeric') . "'";
                        }
                    }
                }
            } else {
                if ($status == self::ADJUST) {
                    if ($this->getVendor() == self::MYSQL) {
                        $sql = "
                UPDATE `asset`
                SET    `isAdjust`       =   1,
                       `isActive`       =   0,
                       `isDelete`       =   1
                WHERE  `assetId`        =   '" . $this->strict($assetId, 'numeric') . "'";
                    } else {
                        if ($this->getVendor() == self::MSSQL) {
                            $sql = "
                UPDATE  [asset]
                SET     [isAdjust]      =   1,
                        [isActive]      =   0,
                        [isDelete]      =   1
                WHERE   [assetId]       =   '" . $this->strict($assetId, 'numeric') . "'";
                        } else {
                            if ($this->getVendor() == self::ORACLE) {
                                $sql = "
                UPDATE  ASSET
                SET     ISADJUST        =   1,
                        ISACTIVE        =   0,
                        ISDELETE        =   1
                WHERE   ASSETID         =   '" . $this->strict($assetId, 'numeric') . "'";
                            }
                        }
                    }
                }
            }
        }
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Create General Ledger
     * @param array $type
     */
    function createGeneralLedgerTransaction($type) {
        // insert into gl transaction
    }

    /**
     *  Create
     * @see config::create()
     */
    public
            function create() {
        
    }

    /**
     * Read
     * @see config::read()
     */
    public
            function read() {
        
    }

    /**
     * Update
     * @see config::update()
     */
    public
            function update() {
        
    }

    /**
     * Update
     * @see config::delete()
     */
    public
            function delete() {
        
    }

    /**
     * Reporting
     * @see config::excel()
     */
    public
            function excel() {
        
    }

}

?>