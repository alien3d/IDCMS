<?php

namespace Core\System\Security\Folder\Service;

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
require_once($newFakeDocumentRoot . "library/translation/BingTranslateLib/BingTranslate.class.php");

/**
 * Class FolderService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\Folder\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class FolderService extends ConfigClass {

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
     * Microsoft Bing Api Translate
     * @var \BingTranslateWrapper
     */
    public $bing;

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
            ORDER BY    `applicationSequence`,
                        `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [applicationId],
                        [applicationEnglish]
            FROM        [application]
            WHERE       [isActive]                  =   1
            AND         [application].[companyId]   =   '" . $this->getCompanyId() . "'
            ORDER BY    [applicationSequence],
                        [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      APPLICATIONID       AS  \"applicationId\",
                        APPLICATIONENGLISH  AS  \"applicationEnglish\"
            FROM        APPLICATION
            WHERE       ISACTIVE                =   1
            AND         APPLICATION.COMPANYID   =   '" . $this->getCompanyId() . "'
            ORDER BY    APPLICATIONSEQUENCE,
                        ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
            }
        }
        $result = $this->q->fast($sql);
        if ($result) {
            $items = array();
            while ((($row = $this->q->fetchArray($result)) == true) == true) {
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
     * Return Module
     * @return array|string
     */
    public function getModule() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `moduleId`,
                        `moduleEnglish`
            FROM        `module`
            WHERE       `isActive`=1
            AND         `module`.`companyId`  = '" . $this->getCompanyId() . "'
            ORDER BY    `moduleSequence`,
                        `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [moduleId],
                        [moduleEnglish]
            FROM        [module]
            WHERE       [isActive]=1
            AND         [module].[companyId]  ='" . $this->getCompanyId() . "'
            ORDER BY    [moduleSequence],
                        [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      MODULEID        AS  \"moduleId\",
                        MODULEENGLISH   AS  \"moduleEnglish\"
            FROM        MODULE
            WHERE       ISACTIVE=1
            AND         MODULE.COMPANYID  = '" . $this->getCompanyId() . "'
            ORDER BY    MODULESEQUENCE,
                        ISDEFAULT";
                } else {
                    header('Content-Type:application/json; charset=utf-8');
                    echo json_encode(array("success" => false, "message" => $this->t['databaseNotFoundMessageLabel']));
                    exit();
                }
            }
        }
        $result = $this->q->fast($sql);
        if ($result) {
            $items = array();
            while ((($row = $this->q->fetchArray($result)) == true) == true) {
                if ($this->getServiceOutput() == 'option') {
                    $str .= "<option value='" . $row['moduleId'] . "'>" . $row['moduleEnglish'] . "</option>";
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
     * Create Access based on new create folderId
     * Distinct to avoid bugs
     * @param int $folderId Folder
     * @return void
     */
    private function setAccess($folderId) {
        //  create a record  in folderAccess.update no effect
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
				FROM 	`folderaccess`
				WHERE 	`isActive`	=	1 
				AND		`companyId`	=	'" . $this->getCompanyId() . "'
                                AND `folderId`='" . $folderId . "'
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
				FROM 	[folderAccess]
				WHERE 	[isActive]	=	1 
				AND		[companyId]	=	'" . $this->getCompanyId() . "'
                                AND [folderId]='" . $folderId . "'
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
				FROM 	FOLDERACCESS
				WHERE 	ISACTIVE	=	1 
				AND		COMPANYID	=	'" . $this->getCompanyId() . "'
                                AND    FOLDERID='" . $folderId . "'
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
				'" . $folderId . "',
				'" . $row ['roleId'] . "',
				'0'
			),";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO	`folderaccess`
			(
				`folderId`,
				`companyId`,
				`roleId`,
				`folderAccessValue`
			) VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO	[folderAccess]
			(
				[folderId],
				[companyId],
				[roleId],
				[folderAccessValue]
			) VALUES";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	FOLDERACCESS
			(
				FOLDERID,
				COMPANYID,
				ROLEID,
				FOLDERACCESSVALUE
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
     * Insert default value to detail folder .English only.For now using bing translation
     * @param int $folderId Folder Primary Key
     * @param string $folderEnglish Default English Translation
     */
    private function setTranslationBing($folderId, $folderEnglish) {

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
            $translatedText = $this->bing->translate($folderEnglish, "en", $row['languageCode']);
            $sqlLooping .= " 
			(
				\"" . $folderId . "\",
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
            INSERT INTO `foldertranslate`
			(
				`folderId`,
				`companyId`,
				`languageId`,
				`folderNative`,
				`isNew`,
				`isActive`,
				`executeBy`,
				`executeTime`
			)VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [folderTranslate]
			(
				[folderId],
				[companyId],
				[languageId],
				[folderNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES  ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	FOLDERTRANSLATE
			(
				FOLDERID,
				COMPANYID,
				LANGUAGEID,
				FOLDERNATIVE,
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
     * @param int $folderId Folder Primary Key
     * @param string $folderEnglish Default English Translation
     * @return void
     */
    private function setTranslationEnglish($folderId, $folderEnglish) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `foldertranslate`
				(
				   `folderId`,
				   `companyId`,
				   `languageId`,
				   `folderNative`,
				   `isNew`,
				   `isActive`,
				   `executeBy`,
				   `executeTime`
				)VALUES (
					\"" . $folderId . "\",
					\"" . $this->getCompanyId() . "\",
					\"" . $this->getLanguageId() . "\",
					\"" . $folderEnglish . "\",
					1,
					1,
					\"" . $this->getStaffId() . "\",
					" . $this->getExecuteTime() . "
				)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [folderTranslate]
			(
				[folderId],
				[companyId],
				[languageId],
				[folderNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES (
				\"" . $folderId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $folderEnglish . "\",
				1,
				1,
				\"" . $this->getStaffId() . "\",
				" . $this->getExecuteTime() . "
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	FOLDERTRANSLATE
			(
				FOLDERID,
				COMPANYID,
				LANGUAGEID,
				FOLDERNATIVE,
				ISNEW,
				ISACTIVE,
				EXECUTEBY,
				EXECUTETIME
			)VALUES (
				\"" . $folderId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $folderEnglish . "\",
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
     * @param int $folderId Folder Primary Key
     * @param string $folderEnglish Default English Translation
     * @return void
     */
    private function setTranslation($folderId, $folderEnglish) {
        $f = @fopen($this->bing->_bingTranslateBaseUrl, "r");
        if ($f) {
            $this->setTranslationBing($folderId, $folderEnglish);
        } else {
            $this->setTranslationEnglish($folderId, $folderEnglish);
        }
    }

    /**
     *
     * @param int $folderId Folder Primary Key
     * @param string $folderEnglish Default English Translation
     * @return void
     */
    public function setFolderAccessAndTranslate($folderId, $folderEnglish) {
        $this->setAccess($folderId);
        $this->setTranslation($folderId, $folderEnglish);
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