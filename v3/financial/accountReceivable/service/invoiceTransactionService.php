<?php namespace Core\Financial\AccountReceivable\InvoiceTransaction\Service; 
	use Core\ConfigClass;
	$x = addslashes(realpath(__FILE__));
	// auto detect if \ consider come from windows else / from linux
	$pos = strpos($x, "\\");
	if ($pos !== false) {
		$d = explode("\\", $x);
	} else {  
		$d = explode("/", $x);
	}
	$newPath = null;
	for ($i = 0; $i < count($d); $i ++) {
		// if find the library or package then stop
		if ($d[$i] == 'library' || $d[$i] == 'v3') {
			break;
		}
		$newPath[] .= $d[$i] . "/";
	}
	$fakeDocumentRoot = null;
	for ($z = 0; $z < count($newPath); $z ++) {
		$fakeDocumentRoot .= $newPath[$z];
	}
	$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot); // start
require_once ($newFakeDocumentRoot."library/class/classAbstract.php"); 
require_once ($newFakeDocumentRoot."library/class/classShared.php"); 
/** 
 * Class InvoiceTransactionService
 * Contain extra processing function / method.
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package Core\Financial\AccountReceivable\InvoiceTransaction\Service
 * @subpackage AccountReceivable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class InvoiceTransactionService extends ConfigClass { 
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
       if($_SESSION['companyId']){
           $this->setCompanyId($_SESSION['companyId']);
       }else{
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
   * Return Invoice
   * @return array|string
   * @throws \Exception
	*/
	public function getInvoice() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
     $items=array(); 
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `invoiceId`,
                     `invoiceDescription`
         FROM        `invoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      [invoiceId],
                     [invoiceDescription]
         FROM        [invoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      INVOICEID AS \"invoiceId\",
                     INVOICEDESCRIPTION AS \"invoiceDescription\"
         FROM        INVOICE  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         ORDER BY    ISDEFAULT"; 
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
		  $d=1;
         while(($row = $this->q->fetchArray($result))==TRUE) { 
             if($this->getServiceOutput()=='option'){
                  $str.="<option value='".$row['invoiceId']."'>".$d.". ".$row['invoiceDescription']."</option>";
             } else if ($this->getServiceOutput()=='html')  { 
                 $items[] = $row; 
             }
			  $d++;
         }
         unset($d);
     }
     if($this->getServiceOutput()=='option'){
          if (strlen($str) > 0) {
            $str = "<option value=''>".$this->t['pleaseSelectTextLabel']."</option>" . $str; 
         } else {
             $str= "<option value=''>".$this->t['notAvailableTextLabel']."</option>";
         }
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success"=>true,"message"=>"complete","data"=>$str));
         exit();
     } else if ($this->getServiceOutput()=='html')  { 
         return $items; 
      }
         return false; 
  }
  /**
   * Return Invoice Default Value
   * @return int
   * @throws \Exception
	*/
	public function getInvoiceDefaultValue() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
	  $invoiceId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `invoiceId`
         FROM        	`invoice`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [invoiceId],
         FROM        [invoice]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      INVOICEID AS \"invoiceId\",
         FROM        INVOICE  
         WHERE       ISACTIVE    =   1
         AND         COMPANYID   =   '".$this->getCompanyId()."'
         AND    	  ISDEFAULT	  =	   1
         AND 		  ROWNUM	  =	   1";
     }  else {
         header('Content-Type:application/json; charset=utf-8');
         echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
         exit();
     }
     try {
       $result =$this->q->fast($sql);
     } catch (\Exception $e) {
       echo json_encode(array("success" => false, "message" => $e->getMessage()));
       exit();
    }
     if($result) { 
         $row = $this->q->fetchArray($result); 
		  $invoiceId = $row['invoiceId'];
	 }
	 return $invoiceId;
 }
  /**
     * Return Chart Of Account
     * @return array|string
     */
    public function getChartOfAccount() {
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
         ORDER BY    `chartofaccounttype`.`chartOfAccountTypeId`,
                     `chartofaccount`.`chartOfAccountNumber`;";
        } else if ($this->getVendor() == self::MSSQL) {
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
         ORDER BY    [chartOfAccount].[chartOfAccountNumber]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      CHARTOFACCOUNTID               AS  \"chartOfAccountId\",
					 CHARTOFACCOUNTNUMBER           AS  \"chartOfAccountNumber\",
                     CHARTOFACCOUNTTITLE            AS  \"chartOfAccountTitle\",
                     CHARTOFACCOUNTTYPEDESCRIPTION  AS  \"chartOfAccountTypeDescription\"
         FROM        CHARTOFACCOUNT
         JOIN        CHARTOFACCOUNTTYPE
         ON          CHARTOFACCOUNT.COMPANYID               =   CHARTOFACCOUNTTYPE.COMPANYID
         AND         CHARTOFACCOUNT.CHARTOFACCOUNTTYPEID    =   CHARTOFACCOUNTTYPE.CHARTOFACCOUNTTYPEID
         WHERE       CHARTOFACCOUNT.ISACTIVE                =   1
         AND         CHARTOFACCOUNT.COMPANYID               =   '" . $this->getCompanyId() . "'
         ORDER BY    CHARTOFACCOUNT.CHARTOFACCOUNTNUMBER";
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
            $chartOfAccountTypeDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($chartOfAccountTypeDescription != $row['chartOfAccountTypeDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['chartOfAccountTypeDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['chartOfAccountTypeDescription'] . "\">";
                }
                $chartOfAccountTypeDescription = $row['chartOfAccountTypeDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['chartOfAccountId'] . "'>" . $row['chartOfAccountNumber'] . " -  " . $row['chartOfAccountTitle'] . "</option>";
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
     * Return ChartOfAccount Default Value
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [chartOfAccountId],
         FROM        [chartOfAccount]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
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
         ORDER BY    `isDefault`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      [countryId],
                     [countryCurrencyCode],
                     [countryDescription]
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
         SELECT      COUNTRYID AS \"countryId\",
                     COUNTRYCURRENCYCODE AS \"countryCurrencyCode\",
                     COUNTRYDESCRIPTION AS \"countryDescription\"
         FROM        COUNTRY
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
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['countryId'] . "|" . $row['countryCurrencyCode'] . "'>" . $row['countryCurrencyCode'] . " -" . $row['countryDescription'] . "</option>";
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
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
         SELECT      TOP 1 [countryId],
         FROM        [country]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         AND    	  [isDefault] =   1";
        } else if ($this->getVendor() == self::ORACLE) {
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