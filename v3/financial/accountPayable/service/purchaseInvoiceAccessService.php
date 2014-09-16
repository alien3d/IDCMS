<?php namespace Core\Financial\AccountPayable\PurchaseInvoiceAccess\Service; 
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
 * Class PurchaseInvoiceAccessService
 * Contain extra processing function / method.
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package Core\Financial\AccountPayable\PurchaseInvoiceAccess\Service
 * @subpackage AccountPayable 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */ 
class PurchaseInvoiceAccessService extends ConfigClass { 
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
   * Return Staff
   * @return array|string
   * @throws \Exception
	*/
	public function getStaff() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
     $items=array(); 
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `staffId`,
                     `staffFirstName` AS `staffName`
         FROM        `staff`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         ORDER BY    `isDefault`;"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      [staffId],
                     [staffName]
         FROM        [staff]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         ORDER BY    [isDefault]"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      STAFFID AS \"staffId\",
                     STAFFNAME AS \"staffName\"
         FROM        STAFF  
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
                  $str.="<option value='".$row['staffId']."'>".$d.". ".$row['staffName']."</option>";
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
   * Return Staff Default Value
   * @return int
   * @throws \Exception
	*/
	public function getStaffDefaultValue() { 
     //initialize dummy value.. no content header.pure html  
     $sql=null; 
     $str=null; 
	  $staffId=null;
     if($this->getVendor()==self::MYSQL) { 
         $sql ="  
         SELECT      `staffId`
         FROM        	`staff`
         WHERE       `isActive`  =   1
         AND         `companyId` =   '".$this->getCompanyId()."'
         AND    	  `isDefault` =	  1
         LIMIT 1"; 
     } else if ($this->getVendor()==self::MSSQL) { 
         $sql =" 
         SELECT      TOP 1 [staffId],
         FROM        [staff]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '".$this->getCompanyId()."'
         AND    	  [isDefault] =   1"; 
     } else if ($this->getVendor()==self::ORACLE) { 
         $sql =" 
         SELECT      STAFFID AS \"staffId\",
         FROM        STAFF  
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
		  $staffId = $row['staffId'];
	 }
	 return $staffId;
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