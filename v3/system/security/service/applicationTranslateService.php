<?php

namespace Core\System\Security\ApplicationTranslate\Service;

// using absolute path instead of relative path..
// start fake document root. it's absolute path

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
$newFakeDocumentRoot = str_replace("//", "/", $fakeDocumentRoot);
require_once($newFakeDocumentRoot . "library/class/classAbstract.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");

/**
 * Class ApplicationTranslateService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\ApplicationTranslate\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ApplicationTranslateService extends ConfigClass {

    /**
     * Connection to the database
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     * Translate Label
     * @var mixed
     */
    public $t;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
    }

    /**
     * Return Application
     * @return array|string
     */
    public function getApplication() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `applicationId`,
                        `applicationEnglish`
            FROM        `application`
            WHERE       `isActive`=1
            ORDER BY    `applicationSequence`,
                        `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [applicationId],
                        [applicationEnglish]
            FROM        [application]
            WHERE       [isActive]=1
            ORDER BY    [applicationSequence],
                        [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      APPLICATIONID AS \"applicationId\",
                        APPLICATIONENGLISH AS \"applicationEnglish\"
            FROM        APPLICATION
            WHERE       ISACTIVE=1
            ORDER BY    APPLICATIONSEQUENCE,
                        ISDEFAULT";
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
            $items = array();
            $d = 0;
            while ((($row = $this->q->fetchArray($result)) == true) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['applicationId'] . "'>" . $d . ". " . $row['applicationEnglish'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
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
        return $items;
    }

    /**
     * Return Language Data
     * @return string
     */
    public function getLanguage() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `languageId`,
                        `languageDescription`
            FROM        `language`
            WHERE       `isActive`=1                 
            AND         `isBing` =1
            AND         `isImportant`=1
            ORDER BY    `isDefault`;";
        } else
        if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT      [languageId],
                        [languageDescription]
            FROM        [LANGUAGE]
            WHERE       [isActive]=1
            AND         [isBing] =1
            AND         [isImportant]=1
            ORDER BY    [isDefault]";
        } else
        if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT      LANGUAGEID          AS \"languageId\",
                        LANGUAGEDESCRIPTION AS \"languageDescription\"
            FROM        LANGUAGE
            WHERE       ISACTIVE=1
            AND         ISBING =1
            AND         ISIMPORTANT=1
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
            $items = array();
            $d = 1;
            while ((($row = $this->q->fetchArray($result)) == true) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['languageId'] . "'>" . $d . ". " . $row['languageDescription'] . "</option>";
                } else {
                    if ($this->getServiceOutput() == 'html') {
                        $items[] = $row;
                    }
                }
                $d++;
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