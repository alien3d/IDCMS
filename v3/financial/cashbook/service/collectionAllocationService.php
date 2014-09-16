<?php

namespace Core\Financial\Cashbook\CollectionAllocation\Service;

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
 * Class CollectionAllocationService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\Financial\Cashbook\CollectionAllocation\Service
 * @subpackage Cashbook
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class CollectionAllocationService extends ConfigClass {

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
     * Return Country
     * @return array|string
     */
    public function getCountry() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`,
					 `countryCurrencyCode`,
                     `countryDescription`
         FROM        `country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `countryDescription`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [countryId],
					 [countryCurrencyCode],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [countryDescription]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      COUNTRYID AS \"countryId\",
					 COUNTRYCURRENCYCODE AS \"countryCurrencyCode\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '" . $this->getCompanyId() . "'
         ORDER BY    COUNTRYDESCRIPTION";
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
                    $str .= "<option value='" . $row['countryId'] . "'>" . $row['countryDescription'] . " - " . $row['countryDescription'] . "</option>";
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
     * Return Country Default Value
     * @return int
     */
    public function getCountryDefaultValue() {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $countryId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `countryId`
         FROM        	`country`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         AND    	  `isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      COUNTRYID AS \"countryId\",
         FROM        COUNTRY
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
            $countryId = $row['countryId'];
        }
        return $countryId;
    }

    /**
     * Return Invoice
     * @param null|int $businessPartnerId
     * @return array|string
     */
    public function getInvoice($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `invoiceId`,
                     `invoiceDescription`
         FROM        `invoice`
         WHERE       `isActive`  			=   1
         AND         `companyId` 			=   '" . $this->getCompanyId() . "'
		 AND		 `businessPartnerId`	=	'" . $businessPartnerId . "'
         ORDER BY    `invoiceDescription`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [invoiceId],
                     [invoiceDescription]
         FROM        [invoice]
         WHERE       [isActive]  			=   1
         AND         [companyId] 			=   '" . $this->getCompanyId() . "'
		 AND		 [businessPartnerId]	=	'" . $businessPartnerId . "'
         ORDER BY    [invoiceDescription]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      INVOICEID AS \"invoiceId\",
                     INVOICEDESCRIPTION AS \"invoiceDescription\"
         FROM        INVOICE
         WHERE       ISACTIVE    		=   1
         AND         COMPANYID   		=   '" . $this->getCompanyId() . "'
		 AND		 BUSINESSPARTNERID	=	'" . $businessPartnerId . "'
         ORDER BY    INVOICEDESCRIPTION";
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
                    $str .= "<option value='" . $row['invoiceId'] . "'>" . $d . ". " . $row['invoiceDescription'] . "</option>";
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
     * Return Invoice Default Value
     * @param null|int $businessPartnerId
     * @return int
     */
    public function getInvoiceDefaultValue($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $invoiceId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT		`invoiceId`
         FROM		`invoice`
         WHERE		`isActive`  =   1
         AND		`companyId` =   '" . $this->getCompanyId() . "'
		 AND		`businessPartnerId`	=	'" . $businessPartnerId . "'
         AND		`isDefault` =	  1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [invoiceId],
         FROM		[invoice]
         WHERE		[isActive]  =   1
         AND		[companyId] =   '" . $this->getCompanyId() . "'
		 AND		[businessPartnerId]	=	'" . $businessPartnerId . "'
         AND		[isDefault] =   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT		INVOICEID AS \"invoiceId\",
         FROM		INVOICE
         WHERE		ISACTIVE    =   1
         AND		COMPANYID   =   '" . $this->getCompanyId() . "'
         AND		ISDEFAULT	  =	   1
         AND		ROWNUM	  =	   1
		 AND		BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
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
            $invoiceId = $row['invoiceId'];
        }
        return $invoiceId;
    }

    /**
     * Return Collection
     * @param null|int $businessPartnerId
     * @return array|string
     */
    public function getCollection($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT      `collectionId`,
                     `collectionDescription`
         FROM        `collection`
         WHERE       `isActive`  			=   1
         AND         `companyId` 			=   '" . $this->getCompanyId() . "'
		 AND		 `businessPartnerId`	=	'" . $businessPartnerId . "'
         ORDER BY    `collectionDescription`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [collectionId],
                     [collectionDescription]
         FROM        [collection]
         WHERE       [isActive]  			=   1
         AND         [companyId] 			=   '" . $this->getCompanyId() . "'
		 AND		 [businessPartnerId]	=	'" . $businessPartnerId . "'
         ORDER BY    [collectionDescription]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      COLLECTIONID AS \"collectionId\",
                     COLLECTIONDESCRIPTION AS \"collectionDescription\"
         FROM        COLLECTION
         WHERE       ISACTIVE    		=   1
         AND         COMPANYID   		=   '" . $this->getCompanyId() . "'
		 AND		 BUSINESSPARTNERID	=	'" . $businessPartnerId . "'
         ORDER BY    COLLECTIONDESCRIPTION ";
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
                    $str .= "<option value='" . $row['collectionId'] . "'>" . $d . ". " . $row['collectionDescription'] . "</option>";
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
     * Return Collection Default Value
     * @param null|int $businessPartnerId
     * @return int
     */
    public function getCollectionDefaultValue($businessPartnerId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $collectionId = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
         SELECT		`collectionId`
         FROM		`collection`
         WHERE		`isActive`  		=   1
         AND		`companyId` 		=   '" . $this->getCompanyId() . "'
		 AND		`businessPartnerId`	=	'" . $businessPartnerId . "'
         AND		`isDefault` 		=	1
         LIMIT 1";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      TOP 1 [collectionId],
         FROM		[collection]
         WHERE		[isActive]  		=   1
         AND		[companyId] 		=   '" . $this->getCompanyId() . "'
		 AND		[businessPartnerId]	=	'" . $businessPartnerId . "'
         AND		[isDefault] 		=   1";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT		COLLECTIONID AS \"collectionId\",
         FROM		COLLECTION
         WHERE		ISACTIVE    		=   1
         AND		COMPANYID   		=   '" . $this->getCompanyId() . "'
         AND		ISDEFAULT	  		=	   1
         AND		ROWNUM	  			=	   1
		 AND		BUSINESSPARTNERID	=	'" . $businessPartnerId . "'";
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
            $collectionId = $row['collectionId'];
        }
        return $collectionId;
    }

    /**
     * Return Business Partner.
     * @return array|string
     */
    public function getBusinessPartner() {
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
			/*
			JOIN		 `purchaseinvoice`
			USING		 (`companyId`,`businessPartnerId`)
			*/
			WHERE       `businesspartner`.`isActive`  =   1
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'
			/*
			AND		 	`purchaseinvoice`.`isPost`	  =	1
			AND			`purchaseinvoice`.`isActive`  =	1
			*/
			ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
						`businesspartner`.`businessPartnerCompany`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			SELECT      [businessPartner].[businessPartnerId],
						[businessPartner].[businessPartnerCompany],
						[businessPartnerCategory].[businessPartnerCategoryDescription]
			FROM        [businessPartner]
			JOIN	    [businessPartnerCategory]
			ON			[businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
			AND		 	[businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
			WHERE       [businessPartner].[isActive]  							=	1
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'
			ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
						[businessPartner].[businessPartnerCompany]	";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
						BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
						BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNER
			JOIN	    BUSINESSPARTNERCATEGORY
			ON			BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
			AND		 	BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
			WHERE       BUSINESSPARTNER.ISACTIVE    						=   1
			AND         BUSINESSPARTNER.COMPANYID   						=   '" . $this->getCompanyId() . "'
			ORDER BY    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION ,
						BUSINESSPARTNER.BUSINESSPARTNERCOMPANY";
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
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $d . ". " . $row['businessPartnerCompany'] . "</option>";
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