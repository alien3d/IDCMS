<?php

namespace Core\Financial\FixedAsset\AssetSpecification\Service;

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
 * Class AssetSpecification
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\FixedAsset\AssetSpecification\Service
 * @subpackage FixedAsset
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class AssetSpecificationService extends ConfigClass {

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
     * Asset->Balance Sheet Item
     */
    const ASSET = 'A';

    /**
     * Liability->Balance Sheet Item
     */
    const LIABILITY = 'I';

    /**
     * Equity->Balance Sheet Item
     */
    const EQUITY = 'OE';

    /**
     * Income->Profit And Loss
     */
    const INCOME = 'I';

    /**
     * Expenses->Profit And Loss
     */
    const EXPENSES = 'E';

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
     * Return AssetDepreciationType
     * @return array|string
     */
    public function getAssetDepreciationType() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetDepreciationTypeId`,
                     `assetDepreciationTypeDescription`
         FROM        `assetdepreciationtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [assetDepreciationTypeId],
                     [assetDepreciationTypeDescription]
         FROM        [assetDepreciationType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETDEPRECIATIONTYPEID AS \"assetDepreciationTypeId\",
                     ASSETDEPRECIATIONTYPEDESCRIPTION AS \"assetDepreciationTypeDescription\"
         FROM        ASSETDEPRECIATIONTYPE
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
                    $str .= "<option value='" . $row['assetDepreciationTypeId'] . "'>" . $d . ". " . $row['assetDepreciationTypeDescription'] . "</option>";
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
     * Return AssetDepreciationType Default Value
     * @return int
     */
    public function getAssetDepreciationTypeDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $assetDepreciationTypeId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `assetDepreciationTypeId`
         FROM        	`assetdepreciationtype`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [assetDepreciationTypeId],
         FROM        [assetDepreciationType]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      ASSETDEPRECIATIONTYPEID AS \"assetDepreciationTypeId\",
         FROM        ASSETDEPRECIATIONTYPE
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
            $assetDepreciationTypeId = $row['assetDepreciationTypeId'];
        }
        return $assetDepreciationTypeId;
    }

    /**
     * Return Chart Of Account.Filter Liability Account. Deposit Account
     * @return array|string
     */
    public function getChartOfAccount() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT		`chartofaccount`.`chartOfAccountId`,
							`chartofaccount`.`chartOfAccountNumber`,
							`chartofaccount`.`chartOfAccountTitle`,
							`chartofaccounttype`.`chartOfAccountTypeDescription`
			FROM        `chartofaccount`
			JOIN        `chartofaccounttype`
			USING       (`companyId`,`chartOfAccountCategoryId`,`chartOfAccountTypeId`)
			JOIN        `chartofaccountcategory`
			USING       (`companyId`,`chartOfAccountCategoryId`)
			WHERE       `chartofaccount`.`isActive`  					=   1
			AND            `chartofaccount`.`companyId` 					=   '" . $this->getCompanyId() . "'
			AND		 	  `chartofaccountcategory`.`chartOfAccountCategoryCode`	=	'" . self::INCOME . "'
			ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
			`chartofaccount`.`chartOfAccountNumber`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [chartOfAccount].[chartOfAccountId],
							[chartOfAccount].[chartOfAccountNumber],
							[chartOfAccount].[chartOfAccountTitle],
							[chartOfAccountType].[chartOfAccountTypeDescription]
			FROM        [chartOfAccount]

			JOIN			[chartOfAccountCategory]
			ON          	[chartOfAccount].[companyId]   				= 	[chartOfAccountType].[companyId]
			AND         	[chartOfAccount].[chartOfAccountCategoryId]   		= 	[chartOfAccountType].[chartOfAccountCategoryId]

			JOIN			[chartOfAccountType]
			ON          	[chartOfAccount].[companyId]   				= 	[chartOfAccountType].[companyId]
			AND         	[chartOfAccount].[chartOfAccountTypeId]   		= 	[chartOfAccountType].[chartOfAccountTypeId]
			AND         	[chartOfAccount].[chartOfAccountCategoryId]   		= 	[chartOfAccountType].[chartOfAccountCategoryId]

			WHERE       [chartOfAccount].[isActive]  					=   1
			AND         [chartOfAccount].[companyId] 					=   '" . $this->getCompanyId() . "'
			AND		 [chartOfAccount].[chartOfAccountCategoryCode]	=	'" . self::INCOME . "'
			ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
				SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
				CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
				CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
				CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
				FROM        CHARTOFACCOUNT

				JOIN        CHARTOFACCOUNTCATEGORY
				ON          CHARTOFACCOUNT.COMPANYID               	=   CHARTOFACCOUNTTYPE.COMPANYID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID

				JOIN        CHARTOFACCOUNTTYPE
				ON          CHARTOFACCOUNT.COMPANYID               	=   CHARTOFACCOUNTTYPE.COMPANYID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
				AND         CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYID    	=   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTCATEGORYID

				WHERE       CHARTOFACCOUNT.ISACTIVE                	=   1
				AND         CHARTOFACCOUNT.COMPANYID               	=   '" . $this->getCompanyId() . "'
				AND		 CHARTOFACCOUNT.CHARTOFACCOUNTCATEGORYCODE	=	'" . self::INCOME . "'
				ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER";
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
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " - "
                            . $row['chartOfAccountTitle'] . "</option>";
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
     * Return Chart Of Account Default Value
     * @return int
     */
    public function getChartOfAccountDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $chartOfAccountId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `chartOfAccountId`
         FROM        	`chartofaccount`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      CHARTOFACCOUNTID AS \"chartOfAccountId\",
         FROM        CHARTOFACCOUNT
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
            $chartOfAccountId = $row['chartOfAccountId'];
        }
        return $chartOfAccountId;
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