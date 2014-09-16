<?php

namespace Core\Financial\GeneralLedger\TaxType\Service;

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
require_once ($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once ($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class TaxTypeService
 * Contain extra processing function / method.
 * @name IDCMS 
 * @version 2 
 * @author hafizan 
 * @package Core\Financial\GeneralLedger\TaxType\Service
 * @subpackage GeneralLedger 
 * @link http://www.hafizan.com 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL 
 */
class TaxTypeService extends ConfigClass {

  
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
* Create
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
     * Delete
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