<?php

namespace Core\shared;

use Core\ConfigClass;

require_once("classAbstract.php");

/**
 * Class SharedTemplate
 * @package Core\shared
 */
class SharedTemplate
{

    /**
     * For Form  11 For Grid 9
     * @var int $spanBreadCrumb
     */
    private $spanBreadCrumb;

    /**
     * Get the layout screen either it was form or grid. grid =1 ,form =2
     * @var string $layout
     */
    private $layout;

    /**
     * Return Template String
     * @var string
     */
    public $str;

    /**
     * Twitter Bootstrap Breadcrumb
     * @param string $applicationNative
     * @param string $moduleNative
     * @param string $folderNative
     * @param string $leafNative
     * @param null $securityToken
     * @param null $applicationId
     * @param null $moduleId
     * @param null $folderId
     * @param null $leafId
     * @return string
     */
    function breadcrumb(
        $applicationNative,
        $moduleNative,
        $folderNative,
        $leafNative,
        $securityToken = null,
        $applicationId = null,
        $moduleId = null,
        $folderId = null,
        $leafId = null
    ) {

        $this->str = "<div id=\"ribbon\">
		<ol class=\"breadcrumb\">
		
            <li>" . ucfirst($applicationNative) . "</li>
            <li>
                <a href=\"javascript:void(0)\" onClick=\"loadSidebar('" . $applicationId . "','" . $moduleId . "')\">" . ucfirst($moduleNative) . "</a>
            </li>
            <li>
                <a href=\"javascript:void(0)\" onClick=\"loadSidebar('" . $applicationId . "','" . $moduleId . "')\">" . ucfirst($folderNative) . "</a>
            </li>
            <li class=\"active\">
                <a href=\"javascript:void(0)\" onClick=\"loadLeft('" . $leafId . "','" . $securityToken . "')\">" . ucfirst($leafNative) . " </a> 
				
			</li>
			<li align=\"right\"><div id=\"infoPanel\" class=\"pull-right\"></div></li>
        </ol></div><br>";
        return $this->str;
    }

    /**
     * Set Span Breadcrumb
     * @param string $value
     */
    function setSpanBreadCrumb($value)
    {
        $this->spanBreadCrumb = $value;
    }

    /**
     * Return Span Breadcrumb
     * @return string
     */
    function getSpanbreadCrumb()
    {
        return $this->spanBreadCrumb;
    }

    /**
     * Set Layout 1 -Grid ,2 -Form , 3 -Master Detail Form
     * @param int $value
     */
    function setLayout($value)
    {
        $this->layout = $value;
    }

    /**
     * Return Layout 1 -Grid ,2 -Form , 3 -Master Detail Form
     * @return int
     */
    function getLayout()
    {
        return $this->layout;
    }

}

/**
 * Class SharedClass
 * @package Core\shared
 */
class SharedClass extends ConfigClass
{

    /**
     * Database connection object
     * @var \Core\Database\Mysql\Vendor
     */
    public $q;

    /**
     /**
* Constructor 
     */
    function __construct()
    {
        //  echo "constructor loaded<br>";
        parent::__construct();
        if (isset($_SESSION['staffId'])) {
            $this->setStaffId($_SESSION['staffId']);
        } else {
            $this->setStaffId(9);
        }
        if (isset($_SESSION['roleId'])) {
            $this->setRoleId($_SESSION['roleId']);
        } else {
            $this->setRoleId(7);
        }
        if (isset($_SESSION['languageId'])) {
            $this->setLanguageId($_SESSION['languageId']);
        } else {
            $this->setLanguageId(21);
        }

    }

    /**
     * Class Loader
     */
    public function execute()
    {

        parent::__construct();
        if (!(is_object($this->q))) {
            if ($this->getVendor() == self::MYSQL) {
                $this->q = new \Core\Database\Mysql\Vendor();
            } else if ($this->getVendor() == self::MSSQL) {
                $this->q = new \Core\Database\Mssql\Vendor();
            } else if ($this->getVendor() == self::ORACLE) {
                $this->q = new \Core\Database\Oracle\Vendor();
            }
            $this->q->connect();
		}
		// check if old code issue
		if(strlen($this->getFilename())>0 && !$this->getLeafId()) {
			$this->setLeafId($this->getFindLeafId($this->getFilename()));
			//debug_print_backtrace();
		}

    }

    /**
     * Create
     * @see config::create
     */
    public function create()
    {

    }

    /**
     * Read
     * @see config::read()
     */
    public function read()
    {

    }

    /**
     * Update
     * @see config::update()
     */
    public function update()
    {

    }

    /**
     * Delete
     * @see config::delete()
     */
    public function delete()
    {

    }

    /**  Reporting
     * @see config::excel()
     */

    public function excel()
    {

    }

    /**
     * Return System Format
     * @return mixed
     * @throws \Exception
     */
    function getSystemFormat()
    {
        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES \"utf8\"";
            $this->q->fast($sql);
        }
        $sql = null;
        $result = null;
        /**
         *  Basic System Information ,Date and  Currency Format
         */
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
	        SELECT *
	        FROM   `systemsetting`
	        WHERE  `companyId`  = '" . $this->getCompanyId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
	        SELECT *
	        FROM   [systemsetting]
	        WHERE  [companyId]  = '" . $this->getCompanyId() . "'";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
	        SELECT  SYSTEMSETTING.SYSTEMSETTINGID         AS  \"systemSettingId\",
                    SYSTEMSETTING.COMPANYID               AS  \"companyId\",
                    SYSTEMSETTING.COUNTRYID               AS  \"countryId\",
                    SYSTEMSETTING.LANGUAGEID              AS  \"languageId\",
                    SYSTEMSETTING.LANGUAGECODE            AS  \"languageCode\",
                    SYSTEMSETTING.SYSTEMSETTINGDATEFORMAT AS  \"systemSettingDateFormat\",
                    SYSTEMSETTING.SYSTEMSETTINGTIMEFORMAT AS  \"systemSettingTimeFormat\",
                    SYSTEMSETTING.SYSTEMSETTINGWEEKSTART  AS  \"systemSettingWeekStart\"
	        FROM    SYSTEMSETTING
	        WHERE   COMPANYID = '" . $this->getCompanyId() . "'";
        }

        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->exceptionMessage($e->getMessage());
        }
        if ($result) {
            $row = $this->q->fetchAssoc($result);
            return $row;
        }
        return false;
    }

    /**
     * Return Leaf Translation
     * @return array
     * @throws \Exception
     */
    function getLeafTranslation()
    {

        if ($this->getVendor() == self::MYSQL) {
            $sql = "SET NAMES utf8";
            $this->q->fast($sql);
        }
        $sql = null;
        $result = null;
        $data = array();
        if (is_array($this->getCurrentTable())) {
            // initialize dummy value
            $tableName = null;
            $sqlTableIn = null;
            $tableList = $this->getCurrentTable();
            $sqlTable = "IN (";
            foreach ($tableList as $tableName) {
               $sqlTableIn .= "'" . strtolower($tableName) . "',";
            }
            $sqlTable .= substr($sqlTableIn, 0, -1) . ")";
        } else{
			//debug_print_backtrace();
            $sqlTable = "IN ('" . $this->getCurrentTable() . "')";
        }
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT  `tablemapping`.`tableMappingColumnName`,
                    `tablemappingtranslate`.`tableMappingNative`
            FROM    `tablemapping`
            JOIN    `tablemappingtranslate`
            USING   (`tableMappingId`)
            WHERE   `tablemappingtranslate`.`languageId`	=   '" . $this->getLanguageId() . "'
            AND     `tablemappingName`                         " . $sqlTable . "";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT  [tableMapping].[tableMappingColumnName],
                    [tableMappingtranslate].[tableMappingNative]
            FROM    [tableMapping]
            JOIN    [tableMappingtranslate]
            ON      [tableMapping].[tableMappingId]         =   [tableMappingtranslate].[tableMappingId]
            WHERE   [tableMappingtranslate].[languageId]    =   '" . $this->getLanguageId() . "'
            AND     [tableMappingName]                         " . $sqlTable . "";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  TABLEMAPPING.TABLEMAPPINGNAME               AS  \"tableMappingName\",
                    TABLEMAPPING.TABLEMAPPINGCOLUMNNAME         AS  \"tableMappingColumnName\",
                    TABLEMAPPINGTRANSLATE.TABLEMAPPINGNATIVE    AS  \"tableMappingNative\"
            FROM    TABLEMAPPING
            JOIN    TABLEMAPPINGTRANSLATE
            ON      TABLEMAPPING.TABLEMAPPINGID         =   TABLEMAPPINGTRANSLATE.TABLEMAPPINGID
            WHERE   TABLEMAPPINGTRANSLATE.LANGUAGEID    =   '" . $this->getLanguageId() . "'
            AND     TABLEMAPPINGNAME                         " . $sqlTable . "";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->exceptionMessage($e->getMessage());
        }

        if ($result) {
            while (($row = $this->q->fetchAssoc($result)) == true) {
                //  echo $row['tableMappingNative']."<br>";
                $data[$row ['tableMappingColumnName'] . "Label"] = $row ['tableMappingNative'];
            }
        }
        $result = null;
        return $data;
    }

    /**
     *
     * @return array
     */
    function getDefaultTranslation()
    {
        $sql = null;
        $result = null;
        $data = array();
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
            SELECT	*
            FROM 	`defaultlabel`
            JOIN 	`defaultlabeltranslate`
            USING 	(`defaultLabelId`)
            WHERE 	`defaultlabeltranslate`.`languageId`	=	'" . $this->getLanguageId() . "'";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
            SELECT	*
            FROM 	[defaultLabel]
            JOIN 	[defaultLabelTranslate]
            ON      [defaultLabel].[defaultLabelId] = [defaultLabelTranslate].[defaultLabelId]
            WHERE 	[defaultLabelTranslate].[languageId]	=	'" . $this->getLanguageId() . "'";
            } else  if ($this->getVendor() == self::ORACLE) {
                    $sql = "
            SELECT  DEFAULTLABELTRANSLATE.DEFAULTLABELNATIVE    AS  \"defaultLabelNative\",
                    DEFAULTLABEL.DEFAULTLABEL                   AS  \"defaultLabel\"
            FROM    DEFAULTLABEL
            JOIN    DEFAULTLABELTRANSLATE
            ON      DEFAULTLABEL.DEFAULTLABELID         =   DEFAULTLABELTRANSLATE.DEFAULTLABELID
            WHERE   DEFAULTLABELTRANSLATE.LANGUAGEID 	=	'" . $this->getLanguageId() . "'";
        }
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->exceptionMessage($e->getMessage());
        }
        if ($result) {
            while (($row = $this->q->fetchAssoc($result)) == true) {
                $data[$row ['defaultLabel']] = $row ['defaultLabelNative'];
            }
        }
        return $data;
    }
	/**
     * Return Leaf Id
     * @return int $leafId
     */
    public function getFindLeafId()
    {
        // initialize dummy value
        $sql = null;
        $result = null;
		$leafId=0;
        if (isset($_SERVER ['PHP_SELF']) && isset($_SESSION['staffId']) && isset($_SESSION['roleId'])) {

            if ($this->getVendor() == self::MYSQL) {
                $sql = "
            SELECT	`leafId`
	        FROM	`leaf`
	        WHERE  	`leaf`.`leafId`					=	'" . $this->getFilename()  . "'";
            } else  if ($this->getVendor() == self::MSSQL) {
                    $sql = "
            SELECT	[leafId]
	        FROM	[leaf]
	        WHERE  	[leaf].[leafId]					=	'" . $this->getFilename()  . "'";
                } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                SELECT	LEAF.LEAFID                         AS  \"leafId\"
	            FROM	LEAF
	            WHERE  	LEAF.LEAFFILENAME					=	'" . $this->getFilename() . "'";
			}

            try {
                $result = $this->q->fast($sql);
            } catch (\Exception $e) {
                $this->exceptionMessage($e->getMessage());
            }
            if ($result) {
                $row = $this->q->fetchAssoc($result);
                $leafId = $row['leafId'];
            }
        }
		return $leafId;
    }
    /**
     * Return Leaf Access
     * @return mixed
     */
    public function getLeafAccess()
    {
        // initialize dummy value
        $sql = null;
        $result = null;
        if (isset($_SERVER ['PHP_SELF']) && isset($_SESSION['staffId']) && isset($_SESSION['roleId'])) {

            if ($this->getVendor() == self::MYSQL) {
                $sql = "
            SELECT	*
	        FROM	`leaf`
	        JOIN	`leafaccess`
	        USING 	(`companyId`,`leafId`)
	        JOIN 	`leaftranslate`
	        USING	(`companyId`,`leafId`)
	        WHERE  	`leaf`.`leafId`					=	'" . $this->getLeafId() . "'
	        AND  	`leafaccess`.`staffId`			=	'" . $this->getStaffId() . "'
	        AND     `leaftranslate`.`languageId`	=	'" . $this->getLanguageId() . "'";
            } else  if ($this->getVendor() == self::MSSQL) {
                    $sql = "
            SELECT	*
	        FROM	[leaf]
	        JOIN	[leafAccess]
	        ON      [leaf].[companyId]              =   [leafAccess].[companyId]
	        AND     [leaf].[leafId]                 =   [leafAccess].[leafId]
	        JOIN 	[leafTranslate]
	        ON      [leaf].[companyId]              =   [leafTranslate].[companyId]
	        AND     [leaf].[leafId]                 =   [leafTranslate].[leafId]
	        WHERE  	[leaf].[leafId]					=	'" . $this->getLeafId() . "'
	        AND  	[leafAccess].[staffId]			=	'" . $this->getStaffId() . "'
	        AND     [leafTranslate].[languageId]	=	'" . $this->getLanguageId() . "'";
                } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                SELECT	LEAF.LEAFID                         AS  \"leafId\",
                        LEAF.COMPANYID                      AS  \"companyId\",
                        LEAF.APPLICATIONID                  AS  \"applicationId\",
                        LEAF.MODULEID                       AS  \"moduleId\",
                        LEAF.FOLDERID                       AS  \"folderId\",
                        LEAF.LEAFSEQUENCE                   AS  \"leafSequence\",
                        LEAF.LEAFCODE                       AS  \"leafCode\",
                        LEAF.LEAFTITLE                      AS  \"leafTitle\",
                        LEAF.LEAFDESCRIPTION                AS  \"leafDescription\",
                        LEAF.LEAFFILENAME                   AS  \"leafFilename\",
                        LEAF.LEAFENGLISH                    AS  \"leafEnglish\",
                        LEAFACCESS.LEAFACCESSID             AS  \"leafAccessId\",
                        LEAFACCESS.STAFFID                  AS  \"staffId\",
                        LEAFACCESS.LEAFACCESSDRAFTVALUE     AS  \"leafAccessDraftValue\",
                        LEAFACCESS.LEAFACCESSCREATEVALUE    AS  \"leafAccessCreateValue\",
                        LEAFACCESS.LEAFACCESSREADVALUE      AS  \"leafAccesssReadValue\",
                        LEAFACCESS.LEAFACCESSUPDATEVALUE    AS  \"leafAccessUpdateValue\",
                        LEAFACCESS.LEAFACCESSDELETEVALUE    AS  \"leafAccessDeleteValue\",
                        LEAFACCESS.LEAFACCESSREVIEWVALUE    AS  \"leafAccessReviewValue\",
                        LEAFACCESS.LEAFACCESSAPPROVEDVALUE  AS  \"leafAccessApprovedValue\",
                        LEAFACCESS.LEAFACCESSPOSTVALUE      AS  \"leafAccessPostValue\",
                        LEAFACCESS.LEAFACCESSPRINTVALUE     AS  \"leafAccessPrintValue\",
                        LEAFTRANSLATE.LEAFTRANSLATEID       AS  \"leafTranslateId\",
                        LEAFTRANSLATE.LANGUAGEID            AS  \"languageId\",
                        LEAFTRANSLATE.LEAFNATIVE            AS  \"leafNative\"
	            FROM	LEAF
	            JOIN	LEAFACCESS
	            ON      LEAF.COMPANYID              =   LEAFACCESS.COMPANYID
                AND     LEAFf.LEAFID                 =   LEAFACCESS.LEAFID
	            JOIN 	LEAFTRANSLATE
	            ON      LEAF.COMPANYID              =   LEAFTRANSLATE.COMPANYID
                AND     LEAF.LEAFID                 =   LEAFTRANSLATE.LEAFID
	            WHERE  	LEAF.LEAFID					=	'" . $this->getLeafId() . "'
	            AND  	LEAFACCESS.STAFFID			=	'" . $this->getStaffId() . "'
	            AND     LEAFTRANSLATE.LANGUAGEID	=	'" . $this->getLanguageId() . "'";
			}

            try {
                $result = $this->q->fast($sql);
            } catch (\Exception $e) {
                $this->exceptionMessage($e->getMessage());
            }
            if ($result) {
                $row = $this->q->fetchAssoc($result);
                return $row;
            }
        }
        return false;
    }

    /**
     * Return User /Staff Access
     * @return array|bool
     * @throws \Exception
     */
    public function getAdminAccess()
    {
        $sql = null;
        $resultAdmin = null;
        $data = array();
        if (isset($_SESSION['staffId']) && isset($_SESSION['roleId'])) {
            if ($this->getVendor() == self::MYSQL) {
                $sql = "
                        SELECT	`role`.`isAdmin`
                        FROM 	`staff`
                        JOIN	`role`
                        USING	(`companyId`,`roleId`)
                        WHERE 	`staff`.`staffId`	=	'" . $this->getStaffId() . "'
                        AND		`role`.`roleId`		=	'" . $this->getRoleId() . "'
                        AND		`staff`.`isActive`	=	1
                        AND		`role`.`isActive`	=	1
                        AND     `staff`.`companyId`  =   '" . $this->getCompanyId() . "'";
            } else if ($this->getVendor() == self::MSSQL) {
                    $sql = "
                        SELECT	[role].[isAdmin]
                        FROM 	[staff]
                        JOIN	[role]
                        ON      [staff].[companyId] = [role].[companyId]
                        AND     [role].[roleId]     = [user].[roleId]
                        WHERE 	[staff].[staffId]	=	'" . $this->getStaffId() . "'
                        AND		[role].[roleId]		=	'" . $this->getRoleId() . "'
                        AND		[staff].[isActive]	=	1
                        AND		[role].[isActive]	=	1";
                } else if ($this->getVendor() == self::ORACLE) {
                        $sql = "
                        SELECT	ROLE.ISADMIN AS \"isAdmin\"
                        FROM 	STAFF
                        JOIN	ROLE
                        ON      STAFF.COMPANYID = ROLE.COMPANYID
                        AND     STAFF.ROLEID    = ROLE.ROLEID
                        WHERE 	STAFF.STAFFID	=	'" . $this->getStaffId() . "'
                        AND		ROLE.ROLEID		=	'" . $this->getRoleId() . "'
                        AND		STAFF.ISACTIVE	=	1
                        AND		ROLE.ISACTIVE	=	1";
						
            }

            try {
                $resultAdmin = $this->q->fast($sql);
            } catch (\Exception $e) {
                $this->exceptionMessage($e->getMessage());
            }
            if ($this->q->numberRows($resultAdmin, $sql) > 0) {
                $rowAdmin = $this->q->fetchAssoc($resultAdmin);
                if ($rowAdmin['isAdmin'] == 1) {
                    $data['isDefaultHidden'] = false;
                    $data['isNewHidden'] = false;
                    $data['isDraftHidden'] = false;
                    $data['isUpdateHidden'] = false;
                    $data['isDeleteHidden'] = false;
                    $data['isActiveHidden'] = false;
                    $data['isApprovedHidden'] = false;
                    $data['isReviewHidden'] = false;
                    $data['isPostHidden'] = false;
                    $data['auditButtonLabelDisabled'] = false;
                } else {
                    $data['isDefaultHidden'] = true;
                    $data['isNewHidden'] = true;
                    $data['isDraftHidden'] = true;
                    $data['isUpdateHidden'] = true;
                    $data['isDeleteHidden'] = true;
                    $data['isActiveHidden'] = true;
                    $data['isApprovedHidden'] = true;
                    $data['isReviewHidden'] = true;
                    $data['isPostHidden'] = true;
                    $data['auditButtonLabelDisabled'] = true;
                }
                return $data;
            }
        }
        return false;
    }

    /**
     * Return identification value and native name
     * @return mixed
     */
    public function getFileInfo()
    {
        $sql = null;
        $result = null;
        $row =array();
        if ($this->getVendor() == self::MYSQL) {
          $sql = "
        SELECT  `leaf`.`applicationId`,
                `leaf`.`moduleId`,
                `leaf`.`folderId`,
                `leaf`.`leafId`,
                `applicationtranslate`.`applicationNative`,
                `foldertranslate`.`folderNative`,
                `moduletranslate`.`moduleNative`,
                `leaftranslate`.`leafNative`
        FROM    `leaf`
        JOIN    `leaftranslate`
        USING   (`companyId`,`leafId`)

        JOIN    `foldertranslate`
        USING   (`companyId`,`folderId`)

        JOIN    `moduletranslate`
        USING   (`companyId`,`moduleId`)

        JOIN    `applicationtranslate`
        USING   (`companyId`,`applicationId`)
        WHERE   `leaf`.`leafId`               =   '" . $this->getLeafId()  . "'
        AND     `leaftranslate`.`languageId`        =   '" . $this->getLanguageId() . "'
        AND     `leaftranslate`.`isActive`          =   1

        AND     `applicationtranslate`.`languageId` =   '" . $this->getLanguageId() . "'
        AND     `applicationtranslate`.`isActive`   =   1
        
        AND     `moduletranslate`.`languageId`      =   '" . $this->getLanguageId() . "'
        AND     `moduletranslate`.`isActive`        =   1
        
        AND     `foldertranslate`.`languageId`      =   '" . $this->getLanguageId() . "'
        AND     `foldertranslate`.`isActive`        =   1";
        } else if ($this->getVendor() == self::MSSQL) {
                $sql = "
        SELECT  [leaf].[applicationId],
                [leaf].[moduleId],
                [leaf].[folderId],
                [leaf].[leafId],
                [applicationTranslate].[applicationNative],
                [folderTranslate].[folderNative],
                [moduleTranslate].[moduleNative],
                [leafTranslate].[leafNative]
        FROM    [leaf]
        JOIN    [leafTranslate]
        ON      [leaf].[companyId]  =   [leafTranslate].[companyId]
        AND     [leaf].[leafId]     =   [leafTranslate].[leafId]

        JOIN    [folderTranslate]
        ON      [leaf].[companyId]  =   [folderTranslate].[companyId]
        AND     [leaf].[folderId]   =   [folderTranslate].[folderId]

        JOIN    [moduleTranslate]
        ON      [leaf].[companyId]  =   [moduleTranslate].[companyId]
        AND     [leaf].[moduleId]   =   [moduleTranslate].[moduleId]

        JOIN    [applicationTranslate]
        ON      [leaf].[companyId]      =   [applicationTranslate].[companyId]
        AND     [leaf].[applicationId]  =   [applicationTranslate].[applicationId]

        WHERE   [leaf].[leafId]           =   '" . $this->getLeafId() . "'
        AND     [leafTranslate].[languageId]    =   '" . $this->getLanguageId() . "'
        AND     [leafTranslate].[isActive]      =   1

        AND     [applicationTranslate].[languageId] =   '" . $this->getLanguageId() . "'
        AND     [applicationTranslate].[isActive]   =   1

        AND     [moduleTranslate].[languageId]  =   '" . $this->getLanguageId() . "'
        AND     [moduleTranslate].[isActive]    =   1

        AND     [folderTranslate].[languageId]  =   '" . $this->getLanguageId() . "'
        AND     [folderTranslate].[isActive]    =   1";
            } else if ($this->getVendor() == self::ORACLE) {
                    $sql = "
        SELECT  LEAF.APPLICATIONID                      AS  \"applicationId\",
                LEAF.MODULEID                           AS  \"moduleId\",
                LEAF.FOLDERID                           AS  \"folderId\",
                LEAF.LEAFID                             AS  \"leafId\",
                APPLICATIONTRANSLATE.APPLICATIONNATIVE  AS  \"applicationNative\",
                FOLDERTRANSLATE.FOLDERNATIVE            AS  \"folderNative\",
                MODULETRANSLATE.MODULENATIVE            AS  \"moduleNative\",
                LEAFTRANSLATE.LEAFNATIVE                AS  \"leafNative\"
        FROM    LEAF

        JOIN    LEAFTRANSLATE
        ON      LEAF.COMPANYID      =   LEAFTRANSLATE.COMPANYID
        AND     LEAF.LEAFID         =   LEAFTRANSLATE.LEAFID

        JOIN    FOLDERTRANSLATE
        ON      LEAF.COMPANYID      =   FOLDERTRANSLATE.COMPANYID
        AND     LEAF.FOLDERID       =   FOLDERTRANSLATE.FOLDERID

        JOIN    MODULETRANSLATE
        ON      LEAF.COMPANYID      =   MODULETRANSLATE.COMPANYID
        AND     LEAF.MODULEID       =   MODULETRANSLATE.MODULEID

        JOIN    APPLICATIONTRANSLATE
        ON      LEAF.COMPANYID                      =   APPLICATIONTRANSLATE.COMPANYID
        AND     APPLICATIONTRANSLATE.APPLICATIONID  =   APPLICATIONTRANSLATE.APPLICATIONID

        WHERE   LEAF.LEAFID           =   '" . $this->getLeafId() . "'
        AND     LEAFTRANSLATE.LANGUAGEID    =   '" . $this->getLanguageId() . "'
        AND     LEAFTRANSLATE.ISACTIVE      =   1


        AND     APPLICATIONTRANSLATE.LANGUAGEID =   '" . $this->getLanguageId() . "'
        AND     APPLICATIONTRANSLATE.ISACTIVE   =   1

        AND     MODULETRANSLATE.LANGUAGEID  =   '" . $this->getLanguageId() . "'
        AND     MODULETRANSLATE.ISACTIVE    =   1

        AND     FOLDERTRANSLATE.LANGUAGEID  =   '" . $this->getLanguageId() . "'
        AND     FOLDERTRANSLATE.ISACTIVE    =   1";
              
        }
        if (isset($_SESSION['isDebug']) == 1) {
            $this->exceptionMessage($sql);
        }

	//echo $sql."<br>";
        try {
            $result = $this->q->fast($sql);
        } catch (\Exception $e) {
            $this->q->rollback(); // optional
            $this->exceptionMessage($e->getMessage());
        }
        if ($result) {
            $row = $this->q->fetchArray($result);
        }
	return $row;
    }

}

if (isset($_GET['method'])) {
    $translate = new SharedClass();
    if (isset($_GET['table'])) {
        if (is_array($_GET['table'])) {
            $translate->setCurrentTable(array($_GET['table'][0], $_GET['table'][1]));
        } else {
            $translate->setCurrentTable($_GET['table']);
        }
    }
    $translate->execute();


    if (isset($_GET['method'])) {
        if ($_GET['method'] == 'default') {
            echo "var t =" . json_encode($translate->getDefaultTranslation());
        }
        if ($_GET['method'] == 'table') {
            echo "var leafTranslation=" . json_encode($translate->getLeafTranslation());
        }
    }
}
?>
