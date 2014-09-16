<?php

namespace Core\System\Security\Module\Service;

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
require_once($newFakeDocumentRoot . "library/translation/BingTranslateLib/BingTranslate.class.php");

/**
 * Class ModuleService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\Module\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ModuleService extends ConfigClass {

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
     * Microsoft Bing Api Translate
     * @var \BingTranslateWrapper
     */
    public $bing;

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
        $this->bing = new \BingTranslateWrapper('17ABBA6C7400D761EE28324EC320B5D0093F3557');
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
         WHERE       `isActive`  =   1
         AND         `companyId` =   '" . $this->getCompanyId() . "'
         ORDER BY    `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
         SELECT      [applicationId],
                     [applicationEnglish]
         FROM        [application]
         WHERE       [isActive]  =   1
         AND         [companyId] =   '" . $this->getCompanyId() . "'
         ORDER BY    [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
         SELECT      APPLICATIONID AS \"applicationId\",
                     APPLICATIONENGLISH AS \"applicationEnglish\"
         FROM        APPLICATION
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
            while (($row = $this->q->fetchArray($result)) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['applicationId'] . "'>" . $row['applicationEnglish'] . "</option>";
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
     * Create Access based on new create moduleId
     * Distinct to avoid bugs
     * @param int $moduleId Module
     * @return void
     */
    private function setAccess($moduleId) {
        //  create a record  in moduleAccess.update no effect
        // loop the group
        $sqlLooping = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT 	`roleId`
            FROM 	`role`
            WHERE 	`isActive`	=	1 
			AND		`companyId`	=	'" . $this->getCompanyId() . "'
			AND		`roleId`	NOT IN
			(
				SELECT 	DISTINCT `roleId`
				FROM 	`moduleaccess`
				WHERE 	`isActive`	=	1 
				AND		`companyId`	=	'" . $this->getCompanyId() . "'
                                AND     `moduleId`='" . $moduleId . "'
			)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT 	[roleId]
            FROM 	[role]
            WHERE 	[isActive]	=	1 
			AND		[companyId]	=	'" . $this->getCompanyId() . "'
			AND		[roleId]	NOT IN
			(
				SELECT 	DISTINCT [roleId]
				FROM 	[moduleAccess]
				WHERE 	[isActive]	=	1 
				AND		[companyId]	=	'" . $this->getCompanyId() . "'
                                    AND     [moduleId]='" . $moduleId . "'
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT 	ROLEID AS \"roleId\"
            FROM 	ROLE
            WHERE 	ISACTIVE	=	1 
			AND		COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		ROLEID	NOT IN
			(
				SELECT 	DISTINCT ROLEID
				FROM 	MODULEACCESS
				WHERE 	ISACTIVE	=	1 
				AND		COMPANYID	=	'" . $this->getCompanyId() . "'
                                    AND     MODULEID='" . $moduleId . "'
			)";
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }

        $data = $this->q->activeRecord();

        foreach ($data as $row) {
            $sqlLooping .= "
			(
				'" . $moduleId . "',
				'" . $row ['roleId'] . "',
				'0'
			),";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO	`moduleaccess`
			(
				`moduleId`,
				`companyId`,
				`roleId`,
				`moduleAccessValue`
			) VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO	[moduleAccess]
			(
				[moduleId],
				[companyId],
				[roleId],
				[moduleAccessValue]
			) VALUES";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	MODULEACCESS
			(
				MODULEID,
				COMPANYID,
				ROLEID,
				MODULEACCESSVALUE
			) VALUES";
        }

        // combine SQL Statement
        $sql .= substr($sqlLooping, 0, -1);
        //  $this->exceptionMessage($sql);
        //  exit();
        try {
            $this->q->update($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     * Insert default value to detail module .English only.For now using bing translation
     * @param int $moduleId Module Primary Key
     * @param string $moduleEnglish Default English Translation
     */
    private function setTranslationBing($moduleId, $moduleEnglish) {

        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  * 
            FROM    `language` 
            WHERE   `isBing`        =   1 
            AND     `isImportant`   =   1";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			SELECT 	* 
			FROM 	[language] 
			WHERE 	[isBing]		=	1 
			AND     [isImportant]   =   1";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			SELECT 	* 
			FROM 	LANGUAGE 
			WHERE 	ISBING		=	1 
			AND     ISIMPORTANT	=	1";
        }
        try {
            $this->q->read($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $data = $this->q->activeRecord();
        $translatedText = null;
        $sqlLooping = null;
        foreach ($data as $row) {
            $translatedText = $this->bing->translate($moduleEnglish, "en", $row['languageCode']);
            $sqlLooping .= " 
			(
				\"" . $moduleId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $row['languageId'] . "\",
				\"" . $translatedText . "\",
				1,
				1,
				\"" . $this->getExecuteBy() . "\",
				" . $this->getExecuteTime() . "
			),";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `moduletranslate`
			(
				`moduleId`,
				`companyId`,
				`languageId`,
				`moduleNative`,
				`isNew`,
				`isActive`,
				`executeBy`,
				`executeTime`
			)VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [moduleTranslate]
			(
				[moduleId],
				[companyId],
				[languageId],
				[moduleNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES  ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	MODULETRANSLATE
			(
				MODULEID,
				COMPANYID,
				LANGUAGEID,
				MODULENATIVE,
				ISNEW,
				ISACTIVE,
				EXECUTEBY,
				EXECUTETIME
			) VALUES ";
        }
        // combine SQL Statement
        $sql .= substr($sqlLooping, 0, -1);
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     *
     * @param int $moduleId Module Primary Key
     * @param string $moduleEnglish Default English Translation
     * @return void
     */
    private function setTranslationEnglish($moduleId, $moduleEnglish) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `moduletranslate`
				(
				   `moduleId`,
				   `companyId`,
				   `languageId`,
				   `moduleNative`,
				   `isNew`,
				   `isActive`,
				   `executeBy`,
				   `executeTime`
				)VALUES (
					\"" . $moduleId . "\",
					\"" . $this->getCompanyId() . "\",
					\"" . $this->getLanguageId() . "\",
					\"" . $moduleEnglish . "\",
					1,
					1,
					\"" . $this->getStaffId() . "\",
					" . $this->getExecuteTime() . "
				)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [moduleTranslate]
			(
				[moduleId],
				[companyId],
				[languageId],
				[moduleNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES (
				\"" . $moduleId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $moduleEnglish . "\",
				1,
				1,
				\"" . $this->getStaffId() . "\",
				" . $this->getExecuteTime() . "
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	MODULETRANSLATE
			(
				MODULEID,
				COMPANYID,
				LANGUAGEID,
				MODULENATIVE,
				ISNEW,
				ISACTIVE,
				EXECUTEBY,
				EXECUTETIME
			)VALUES (
				\"" . $moduleId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $moduleEnglish . "\",
				1,
				1,
				\"" . $this->getStaffId() . "\",
				" . $this->getExecuteTime() . "
			)";
        }
        // combine SQL Statement
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
    }

    /**
     *
     * @param int $moduleId Module Primary Key
     * @param string $moduleEnglish Default English Translation
     * @return void
     */
    private function setTranslation($moduleId, $moduleEnglish) {
        $f = @fopen($this->bing->_bingTranslateBaseUrl, "r");
        if ($f) {
            $this->setTranslationBing($moduleId, $moduleEnglish);
        } else {
            $this->setTranslationEnglish($moduleId, $moduleEnglish);
        }
    }

    /**
     *
     * @param int $moduleId Module Primary Key
     * @param string $moduleEnglish Default English Translation
     * @return void
     */
    public function setModuleAccessAndTranslate($moduleId, $moduleEnglish) {
        $this->setAccess($moduleId);
        $this->setTranslation($moduleId, $moduleEnglish);
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