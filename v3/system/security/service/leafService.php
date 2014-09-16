<?php

namespace Core\System\Security\Leaf\Service;

use Core\ConfigClass;

// using absolute path instead of relative path..
// start fake document root. it's absolute path
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
 * Class LeafService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\Leaf\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class LeafService extends ConfigClass {

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
            WHERE       `isActive`=1
            ORDER BY    `applicationSequence`,
                        `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [applicationId],
                        [applicationEnglish]
            FROM        [application]
            WHERE       [isActive]=1
            ORDER BY    [applicationSequence],
                        [isDefault]";
            } else {
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
            ORDER BY    `moduleSequence`,
                        `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [moduleId],
                        [moduleEnglish]
            FROM        [module]
            WHERE       [isActive]=1
            ORDER BY    [moduleSequence],
                        [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      MODULEID AS \"moduleId\",
                        MODULEENGLISH AS \"moduleEnglish\"
            FROM        MODULE
            WHERE       ISACTIVE=1
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
     * Return Folder
     * @return array|string
     */
    public function getFolder() {
        //initialize dummy value.. no content header.pure html  
        $sql = null;
        $str = null;
        $items = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "  
            SELECT      `folderId`,
                        `folderEnglish`
            FROM        `folder`
            WHERE       `isActive`=1
            ORDER BY    `folderSequence`,
                        `isDefault`;";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT      [folderId],
                        [folderEnglish]
            FROM        [folder]
            WHERE       [isActive]=1
            ORDER BY    [folderSequence],
                        [isDefault]";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT      FOLDERID AS \"folderId\",
                        FOLDERENGLISH AS \"folderEnglish\"
            FROM        FOLDER
            WHERE       ISACTIVE=1
            ORDER BY    FOLDERSEQUENCE,
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
                    $str .= "<option value='" . $row['folderId'] . "'>" . $row['folderEnglish'] . "</option>";
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
     * Create Access based on new create leafId
     * Distinct to avoid bugs
     * @param int $leafId Leaf
     * @return void
     */
    private function setAccess($leafId) {
        //  create a record  in leafAccess.update no effect
        // loop the group
        $sqlLooping = null;
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT 	`staffId`
            FROM 	`staff`
            WHERE 	`isActive`	=	1 
			AND		`companyId`	=	'" . $this->getCompanyId() . "'
			AND		`staffId`	NOT IN
			(
				SELECT 	DISTINCT `staffId`
				FROM 	`leafaccess`
				WHERE 	`isActive`	=	1 
				AND     `companyId`	=	'" . $this->getCompanyId() . "'
                                AND     `leafId`    =   '" . $leafId . "'
			)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            SELECT 	[staffId]
            FROM 	[staff]
            WHERE 	[isActive]	=	1 
			AND		[companyId]	=	'" . $this->getCompanyId() . "'
			AND		[staffId]	NOT IN
			(
				SELECT 	DISTINCT [staffId]
				FROM 	[leafAccess]
				WHERE 	[isActive]	=	1 
				AND		[companyId]	=	'" . $this->getCompanyId() . "'
                                AND    [leafId]    =   '" . $leafId . "'    
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
            SELECT 	STAFFID AS \"staffId\"
            FROM 	ROLE
            WHERE 	ISACTIVE	=	1 
			AND		COMPANYID	=	'" . $this->getCompanyId() . "'
			AND		ROLEID	NOT IN
			(
				SELECT 	DISTINCT STAFFID
				FROM 	LEAFACCESS
				WHERE 	ISACTIVE	=	1 
				AND		COMPANYID	=	'" . $this->getCompanyId() . "'
                                AND     LEAFID   =   '" . $leafId . "'         
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
				'" . $leafId . "',
				'" . $row ['staffId'] . "',
                                    
				0,
                                0,
                                0,
                                0,
                                
                                0,
                                0,
                                0,
                                0,
                                
                                0,
                                0
                                
			),";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO	`leafaccess`
			(
				`leafId`,
				`companyId`,
				`staffId`,
				`leafAccessDraftValue`,
                                `leafAccessCreateValue`,
                                `leafAccessReadValue`,
                                `leafAccessUpdateValue`,
                                
                                `leafAccessDeleteValue`,
                                `leafAccessReviewValue`,
                                `leafAccessApprovedValue`,
                                
                                `leafAccessPostValue`,
                                `leafAccessPrintValue`
			) VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO	[leafAccess]
			(
				[leafId],
				[companyId],
				[staffId],
				[leafAccessDraftValue],
                                [leafAccessCreateValue],
                                [leafAccessReadValue],
                                [leafAccessUpdateValue],
                                
                                [leafAccessDeleteValue],
                                [leafAccessReviewValue],
                                [leafAccessApprovedValue],
                                
                                [leafAccessPostValue],
                                [leafAccessPrintValue]
			) VALUES";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	LEAFACCESS
			(
				LEAFID,
				COMPANYID,
				ROLEID,
				LEAFACCESSDRAFTVALUE,
                                LEAFACCESSCREATEVALUE,
                                LEAFACCESSREADVALUE,
                                LEAFACCESSUPDATEVALUE,
                                
                                LEAFACCESSDELETEVALUE,
                                LEAFACCESSREVIEWVALUE,
                                LEAFACCESSAPPROVEDVALUE,
                                
                                LEAFACCESSPOSTVALUE,
                                LEAFACCESSPRINTVALUE
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
     * Insert default value to detail leaf .English only.For now using bing translation
     * @param int $leafId Leaf Primary Key
     * @param string $leafEnglish Default English Translation
     */
    private function setTranslationBing($leafId, $leafEnglish) {

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
            $translatedText = $this->bing->translate($leafEnglish, "en", $row['languageCode']);
            $sqlLooping .= " 
			(
				\"" . $leafId . "\",
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
            INSERT INTO `leaftranslate`
			(
				`leafId`,
				`companyId`,
				`languageId`,
				`leafNative`,
				`isNew`,
				`isActive`,
				`executeBy`,
				`executeTime`
			)VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [leafTranslate]
			(
				[leafId],
				[companyId],
				[languageId],
				[leafNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES  ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	LEAFTRANSLATE
			(
				LEAFID,
				COMPANYID,
				LANGUAGEID,
				LEAFNATIVE,
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
     * @param int $leafId Leaf Primary Key
     * @param string $leafEnglish Default English Translation
     * @return void
     */
    private function setTranslationEnglish($leafId, $leafEnglish) {
        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `leaftranslate`
				(
				   `leafId`,
				   `companyId`,
				   `languageId`,
				   `leafNative`,
				   `isNew`,
				   `isActive`,
				   `executeBy`,
				   `executeTime`
				)VALUES (
					\"" . $leafId . "\",
					\"" . $this->getCompanyId() . "\",
					\"" . $this->getLanguageId() . "\",
					\"" . $leafEnglish . "\",
					1,
					1,
					\"" . $this->getStaffId() . "\",
					" . $this->getExecuteTime() . "
				)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [leafTranslate]
			(
				[leafId],
				[companyId],
				[languageId],
				[leafNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES (
				\"" . $leafId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $leafEnglish . "\",
				1,
				1,
				\"" . $this->getStaffId() . "\",
				" . $this->getExecuteTime() . "
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	LEAFTRANSLATE
			(
				LEAFID,
				COMPANYID,
				LANGUAGEID,
				LEAFNATIVE,
				ISNEW,
				ISACTIVE,
				EXECUTEBY,
				EXECUTETIME
			)VALUES (
				\"" . $leafId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $leafEnglish . "\",
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
     * @param int $leafId Leaf Primary Key
     * @param string $leafEnglish Default English Translation
     * @return void
     */
    private function setTranslation($leafId, $leafEnglish) {
        $f = @fopen($this->bing->_bingTranslateBaseUrl, "r");
        if ($f) {
            $this->setTranslationBing($leafId, $leafEnglish);
        } else {
            $this->setTranslationEnglish($leafId, $leafEnglish);
        }
    }

    /**
     *
     * @param int $leafId Leaf Primary Key
     * @param string $leafEnglish Default English Translation
     * @return void
     */
    public function setLeafAccessAndTranslate($leafId, $leafEnglish) {
        $this->setAccess($leafId);
        $this->setTranslation($leafId, $leafEnglish);
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