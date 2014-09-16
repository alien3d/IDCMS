<?php namespace Core\Financial\BusinessPartner\BusinessPartnerPriceList\Service; 
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
 * Class BusinessPartnerPriceListService
 * Contain extra processing function / method.
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package Core\Financial\BusinessPartner\BusinessPartnerPriceList\Service
 * @subpackage BusinessPartner 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class BusinessPartnerPriceListService extends ConfigClass { 
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
     * Return Business Partner
     * @param null|int $businessPartnerCategoryId Business Partner Category Primary Key
     * @return array|string
     */
    public function getBusinessPartner($businessPartnerCategoryId = null) {
        //initialize dummy value.. no content header.pure html
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			SELECT      `businesspartner`.`businessPartnerId`,
					 `businesspartner`.`businessPartnerCompany`,
							`businesspartner`.`businessPartnerRegistrationNumber`,
					 `businesspartnercategory`.`businessPartnerCategoryDescription`
			FROM        `businesspartner`
			JOIN		 `businesspartnercategory`
			USING		 (`companyId`,`businessPartnerCategoryId`)
			WHERE       `businesspartner`.`isActive`  =   1
			AND			`isCreditor`=1
			AND         `businesspartner`.`companyId` =   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND `businesspartner`.`businessPartnerCategoryId`='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    `businesspartnercategory`.`businessPartnerCategoryDescription`,
								`businesspartner`.`businessPartnerCompany`;";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT      [businessPartner].[businessPartnerId],
				 [businessPartner].[businessPartnerCompany],
				 [businessPartner].[businessPartnerRegistrationNumber],
				 [businessPartnerCategory].[businessPartnerCategoryDescription]
			FROM        [businessPartner]
			JOIN	     [businessPartnerCategory]
			ON			 [businessPartnerCategory].[companyId] 					= 	[businessPartner].[companyId]
			AND		 [businessPartnerCategory].[businessPartnerCategoryId] 	= 	[businessPartner].[businessPartnerCategoryId]
			WHERE       [businessPartner].[isActive]  							=	1
			AND			[isCreditor]=1
			AND         [businessPartner].[companyId] 							=   '" . $this->getCompanyId() . "'";
            if ($businessPartnerCategoryId) {
                $sql.=" AND [businessPartner].[businessPartnerCategoryId]='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    [businessPartnerCategory].[businessPartnerCategoryDescription],
			[businessPartner].[businessPartnerCompany]	";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT      BUSINESSPARTNER.BUSINESSPARTNERID AS \"businessPartnerId\",
							 BUSINESSPARTNER.BUSINESSPARTNERCOMPANY AS \"businessPartnerCompany\",
							 BUSINESSPARTNER.BUSINESSPARTNERREGISTRATIONNUMBER AS \"businessPartnerRegistrationNumber\",
							 BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION AS \"businessPartnerCategoryDescription\"
			FROM        BUSINESSPARTNER
			JOIN	     	BUSINESSPARTNERCATEGORY
			ON			 BUSINESSPARTNERCATEGORY.COMPANYID 					= 	BUSINESSPARTNER.COMPANYID
			AND		 	BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYID 	= 	BUSINESSPARTNER.BUSINESSPARTNERCATEGORYIID
			WHERE       BUSINESSPARTNER.COMPANYID = '" . $this->getCompanyId() . "'
			AND			ISCREDITOR=1
			AND         BUSINESSPARTNER.ISACTIVE    						=   1";
            if ($businessPartnerCategoryId) {
                $sql.=" AND BUSINESSPARTNER.BUSINESSPARTNERCATEGORYID='" . $businessPartnerCategoryId . "'";
            }
            $sql.="
			ORDER BY    BUSINESSPARTNERCATEGORY.BUSINESSPARTNERCATEGORYDESCRIPTION ,
			BUSINESSPARTNER.BUSINESSPARTNERCOMPANY";
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
            $businessPartnerCategoryDescription = null;
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($d != 0) {
                    if ($businessPartnerCategoryDescription != $row['businessPartnerCategoryDescription']) {
                        $str .= "</optgroup><optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                    }
                } else {
                    $str .= "<optgroup label=\"" . $row['businessPartnerCategoryDescription'] . "\">";
                }
                $businessPartnerCategoryDescription = $row['businessPartnerCategoryDescription'];

                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['businessPartnerId'] . "'>" . $row['businessPartnerRegistrationNumber'] . ". " . $row['businessPartnerCompany'] . "</option>";
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
   * Return Business Partner Default Value
   * @return int
   * @throws \Exception
	*/
	public function getBusinessPartnerDefaultValue() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
	  $businessPartnerId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `businessPartnerId`
         FROM        	`businesspartner`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [businessPartnerId],
         FROM        [businessPartner]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      BUSINESSPARTNERID AS \"businessPartnerId\",
         FROM        BUSINESSPARTNER  
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
		  $businessPartnerId = $row['businessPartnerId'];
	 }
	 return $businessPartnerId;
 }
  /**
   * Return Product
   * @return array|string
   * @throws \Exception
	*/
	public function getProduct() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
     $items=array(); 
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `productId`,
                     `productDescription`
         FROM        `product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      [productId],
                     [productDescription]
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      PRODUCTID AS \"productId\",
                     PRODUCTDESCRIPTION AS \"productDescription\"
         FROM        PRODUCT  
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
                  $str.="<option value='".$row['productId']."'>".$d.". ".$row['productDescription']."</option>";
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
   * Return Product Default Value
   * @return int
   * @throws \Exception
	*/
	public function getProductDefaultValue() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
	  $productId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `productId`
         FROM        	`product`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [productId],
         FROM        [product]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      PRODUCTID AS \"productId\",
         FROM        PRODUCT  
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
		  $productId = $row['productId'];
	 }
	 return $productId;
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