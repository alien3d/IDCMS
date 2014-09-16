<?php

namespace Core\System\Security\Application\Service;

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
 * Class ApplicationService
 * Contain extra processing function / method.
 * @name IDCMS
 * @version 2
 * @author hafizan
 * @package Core\System\Security\Application\Service
 * @subpackage Security
 * @link http://www.hafizan.com
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class ApplicationService extends ConfigClass {

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
     * Constructor
     */
    function construct() {
        
    }

    /**
     * Class Loader
     */
    function execute() {
        parent::__construct();
        $this->bing = new \BingTranslateWrapper('17ABBA6C7400D761EE28324EC320B5D0093F3557');
    }

    /**
     * Create Access based on new create applicationId
     * Distinct to avoid bugs
     * @param int $applicationId Application
     * @return void
     */
    private function setAccess($applicationId) {
        //  create a record  in applicationAccess.update no effect
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
				FROM 	`applicationaccess`
				WHERE 	`isActive`	=	1 
				AND     `companyId`	=	'" . $this->getCompanyId() . "'
                                AND     `applicationId` =   '" . $applicationId . "'
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
				FROM 	[applicationAccess]
				WHERE 	[isActive]	=	1 
				AND     [companyId]	=	'" . $this->getCompanyId() . "'
                                    AND     [applicationId] =   '" . $applicationId . "'
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
				FROM 	APPLICATIONACCESS
				WHERE 	ISACTIVE	=	1 
				AND		COMPANYID	=	'" . $this->getCompanyId() . "'
                                AND     APPLICATIONID=   '" . $applicationId . "'
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
				'" . $applicationId . "',
				'" . $row ['roleId'] . "',
				'0'
			),";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO	`applicationaccess`
			(
				`applicationId`,
				`companyId`,
				`roleId`,
				`applicationAccessValue`
			) VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
            INSERT INTO	[applicationAccess]
			(
				[applicationId],
				[companyId],
				[roleId],
				[applicationAccessValue]
			) VALUES";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	APPLICATIONACCESS
			(
				APPLICATIONID,
				COMPANYID,
				ROLEID,
				APPLICATIONACCESSVALUE
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
     * Insert default value to detail application .English only.For now using bing translation
     * @param int $applicationId Application Primary Key
     * @param string $applicationEnglish Default English Translation
     */
    private function setTranslationBing($applicationId, $applicationEnglish) {

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
            $translatedText = $this->bing->translate($applicationEnglish, "en", $row['languageCode']);
            $sqlLooping .= " 
			(
				\"" . $applicationId . "\",
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
            INSERT INTO `applicationtranslate`
			(
				`applicationId`,
				`companyId`,
				`languageId`,
				`applicationNative`,
				`isNew`,
				`isActive`,
				`executeBy`,
				`executeTime`
			)VALUES";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [applicationTranslate]
			(
				[applicationId],
				[companyId],
				[languageId],
				[applicationNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES  ";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	APPLICATIONTRANSLATE
			(
				APPLICATIONID,
				COMPANYID,
				LANGUAGEID,
				APPLICATIONNATIVE,
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
     * @param int $applicationId Application Primary Key
     * @param string $applicationEnglish Default English Translation
     * @return void
     */
    private function setTranslationEnglish($applicationId, $applicationEnglish) {

        $sql = null;
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            INSERT INTO `applicationtranslate`
				(
				   `applicationId`,
				   `companyId`,
				   `languageId`,
				   `applicationNative`,
				   `isNew`,
				   `isActive`,
				   `executeBy`,
				   `executeTime`
				)VALUES (
					\"" . $applicationId . "\",
					\"" . $this->getCompanyId() . "\",
					\"" . $this->getLanguageId() . "\",
					\"" . $applicationEnglish . "\",
					1,
					1,
					\"" . $this->getStaffId() . "\",
					" . $this->getExecuteTime() . "
				)";
        } else if ($this->getVendor() == self::MSSQL) {
            $sql = "
			INSERT INTO  [applicationTranslate]
			(
				[applicationId],
				[companyId],
				[languageId],
				[applicationNative],
				[isNew],
				[isActive],
				[executeBy],
				[executeTime]
			)VALUES (
				\"" . $applicationId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $applicationEnglish . "\",
				1,
				1,
				\"" . $this->getStaffId() . "\",
				" . $this->getExecuteTime() . "
			)";
        } else if ($this->getVendor() == self::ORACLE) {
            $sql = "
			INSERT INTO	APPLICATIONTRANSLATE
			(
				APPLICATIONID,
				COMPANYID,
				LANGUAGEID,
				APPLICATIONNATIVE,
				ISNEW,
				ISACTIVE,
				EXECUTEBY,
				EXECUTETIME
			)VALUES (
				\"" . $applicationId . "\",
				\"" . $this->getCompanyId() . "\",
				\"" . $this->getLanguageId() . "\",
				\"" . $applicationEnglish . "\",
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
     * @param int $applicationId Application Primary Key
     * @param string $applicationEnglish Default English Translation
     * @return void
     */
    private function setTranslation($applicationId, $applicationEnglish) {
        $f = @fopen($this->bing->_bingTranslateBaseUrl, "r");
        if ($f) {
            $this->setTranslationBing($applicationId, $applicationEnglish);
        } else {
            $this->setTranslationEnglish($applicationId, $applicationEnglish);
        }
    }

    /**
     *
     * @param int $applicationId Application Primary Key
     * @param string $applicationEnglish Default English Translation
     * @return void
     */
    public function setApplicationAccessAndTranslate($applicationId, $applicationEnglish) {
        $this->setAccess($applicationId);
        $this->setTranslation($applicationId, $applicationEnglish);
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