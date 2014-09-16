<?php

namespace Core\Financial\AccountPayable\PurchaseInvoiceFollowUp\Service;

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
 * Class PurchaseInvoiceFollowUpService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\AccountPayable\PurchaseInvoiceFollowUp\Service
 * @subpackage AccountPayable
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class PurchaseInvoiceFollowUpService extends ConfigClass {

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
			AND			`isCreditor`=1
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
			AND			[isCreditor]=1
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
			AND			ISCREDITOR=1
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
     * Return PurchaseInvoice Default Value
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
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
     * Return FollowUp
     * @return array|string
     */
    public function getFollowUp() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `followUpId`,
                     `followUpDescription`
         FROM        `followup`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [followUpId],
                     [followUpDescription]
         FROM        [followUp]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      FOLLOWUPID AS \"followUpId\",
                     FOLLOWUPDESCRIPTION AS \"followUpDescription\"
         FROM        FOLLOWUP  
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
            header('Content-Type:application/json; charset=utf-8');
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        if ($result) {
            $d = 1;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['followUpId'] . "'>" . $d . ". " . $row['followUpDescription'] . "</option>";
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
     * Return FollowUp Default Value
     * @return int
     */
    public function getFollowUpDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $followUpId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `followUpId`
         FROM        	`followup`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [followUpId],
         FROM        [followUp]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      FOLLOWUPID AS \"followUpId\",
         FROM        FOLLOWUP  
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
            $followUpId = $row['followUpId'];
        }
        return $followUpId;
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

}

?>