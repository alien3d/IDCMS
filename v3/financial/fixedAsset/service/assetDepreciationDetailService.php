<?php

namespace Core\Financial\FixedAsset\AssetDepreciationDetail\Service;

// using Absolute path instead of relative path..
// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class AssetDepreciationDetailService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetDepreciationDetail\Service
 * @subpackage Asset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetDepreciationDetailService extends ConfigClass {

    /**
     * Asset->Balance Sheet Item
     */
    const ASSET = 'A';

    /**
     * Asset->Balance Sheet Item (SAGA Only)
     */
    const SAGA_ASSET = 'A00000';

    /**
     * Liability->Balance Sheet Item
     */
    const LIABILITY = 'I';

    /**
     * Liability->Balance Sheet Item(SAGA Only)
     */
    const SAGA_LIABILITY = 'L00000';

    /**
     * Equity->Balance Sheet Item
     */
    const EQUITY = 'OE';

    /**
     * Equity->Balance Sheet Item(SAGA only)
     */
    const SAGA_EQUITY = 'E00000';

    /**
     * Income->Profit And Loss
     */
    const INCOME = 'I';

    /**
     * Income->Profit And Loss
     */
    const SAGA_INCOME = 'B00000';

    /**
     * Expenses->Profit And Loss
     */
    const EXPENSES = 'E';

    /**
     * Expenses->Profit And Loss(SAGA only)
     */
    const SAGA_EXPENSES = 'H00000';

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
     * Financial Shared Service
     * @var \Core\Financial\Ledger\Service\LedgerService
     */
    public $ledgerService;

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
        $this->ledgerService = new LedgerService();
        $this->ledgerService->q = $this->q;
        $this->ledgerService->t = $this->t;
        $this->ledgerService->execute();
    }

    /**
     * Return Asset Data
     * @return mixed
     */
    public function getAsset() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `assetId`,
                        `assetDesc`
            FROM        `asset`
            WHERE       `isActive`=1
            AND         `companyId`    ='" . $_SESSION['companyId'] . "'
            ORDER BY    `assetSequence`,
                        `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [assetId],
                        [assetDesc]
            FROM        [asset]
            WHERE       [isActive]=1
            AND         [companyId]    ='" . $_SESSION['companyId'] . "'
            ORDER BY    [assetSequence],
                        [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      ASSETID,
                        ASSETDESC
            FROM        ASSET
            WHERE       ISACTIVE=1
            AND         COMPANYID='" . $_SESSION['companyId'] . "'
            ORDER BY    ASSETSEQUENCE,
                        ISDEFAULT";
        } else {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
            exit();
        }

        $result = $this->q->fast($sql);
        if ($result) {
            $items = array();
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['assetId'] . "'>" . $row['assetDesc'] . "</option>";
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