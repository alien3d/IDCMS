<?php

namespace Core\Document\Trail;

use Core\ConfigClass;
use Core\System\Document\Document\Model\DocumentModel;

if (!isset($_SESSION)) {
    session_start();
}
$x = addslashes(realpath(__FILE__));
// auto detect if \ consider come from windows else / from linux
$pos = strpos($x, "\\");
if ($pos !== false) {
    $d = explode("\\", $x);
} else {
    $d = explode("/", $x);
}
$newPath = null;
for ($i = 0; $i < count($d); $i++) {
    // if find the library or package then stop
    if ($d[$i] == 'library' || $d[$i] == 'package') {
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
require_once($newFakeDocumentRoot . "v3/system/document/model/documentModel.php");
/**
 * Class DocumentTrailClass
 * @package Core\Document\Trail
 */
class DocumentTrailClass extends ConfigClass
{

     /**
* Connection to the database
* @var \Core\Database\Mysql\Vendor
*/
public $q;

    /**
     * Document Model  but as Reference
     * @var \Core\System\Document\Document\Model\DocumentModel
     */
    public $model;
    /**
     * Document Path
     * @var string
     */
    public $path;

    /**
     * Document Path
     * @var string
     */
    private $documentPath;
    /**
     * Filename
     * @var string
     */
    private $documentFilename;

    /**
     /**
* Constructor 
     */
    public function __construct()
    {
        if (isset($_SESSION['companyId'])) {
            $this->setCompanyId($_SESSION['companyId']);
        } else {
            $this->setCompanyId(1);
        }
    }

    /**
     * Class Loader
     */
    function execute()
    {
        parent::__construct();
        $this->model = new DocumentModel();
        $this->model->setVendor($this->getVendor());
        $this->model->execute();
    }

    /**
     * Create
     * @see config::create()
     */
    public function create()
    {
        $this->model->create();
        $this->model->setDocumentCategoryId(3);
        $this->model->setLeafId($this->getLeafId());
        $this->model->setDocumentPath($this->getDocumentPath());
        $this->model->setDocumentFilename($this->getDocumentFilename());
        $this->q->start();
        $sql = null;
        $this->model->setDocumentTitle('Audit Trail File');
        $this->model->setDocumentDescription('Audit Trail File');
        if ($this->getVendor() == self::MYSQL) {
            $sql = "
			INSERT INTO `document`	(
						`documentCategoryId`,			`leafId`,
						`documentTitle`,				`documentDescription`,
						`documentPath`,					`documentFilename`,
						`isDefault`,					`isNew`,
						`isDraft`,						`isUpdate`,
						`isDelete`,						`isActive`,
						`isApproved`,					`executeBy`,
						`executeTime`,                  `companyId`
			)	VALUES	(
						'" . $this->model->getDocumentCategoryId() . "',
						'" . $this->model->getLeafId() . "',
						'" . $this->model->getDocumentTitle() . "',
						'" . $this->model->getDocumentDescription() . "',
						'" . $this->model->getDocumentPath() . "',
						'" . $this->model->getDocumentFilename() . "',
						'" . $this->model->getIsDefault(0, 'single') . "',
						'" . $this->model->getIsNew('', 'single') . "',
						'" . $this->model->getIsDraft(0, 'single') . "',
						'" . $this->model->getIsUpdate(0, 'single') . "',
						'" . $this->model->getIsDelete('', 'single') . "',
						'" . $this->model->getIsActive(0, 'single') . "',
						'" . $this->model->getIsApproved(0, 'single') . "',
						'" . $this->model->getExecuteBy() . "',
						 " . $this->model->getExecuteTime() . ",
						 '" . $this->getCompanyId() . "'
			); ";
        } else {
            if ($this->getVendor() == self::MSSQL) {
                $sql = "
			INSERT INTO [document]	(
						[documentCategoryId],			[leafId],
						[documentTitle],				[documentDescription],
						[documentPath],					[documentFilename],
						[isDefault],					[isNew],
						[isDraft],						[isUpdate],
						[isDelete],						[isActive],
						[isApproved],					[executeBy],
						[executeTime],                  [companyId]
			)	VALUES	(
						'" . $this->model->getDocumentCategoryId() . "',
						'" . $this->model->getLeafId() . "',
						'" . $this->model->getDocumentTitle() . "',
						'" . $this->model->getDocumentDescription() . "',
						'" . $this->model->getDocumentPath() . "',
						'" . $this->model->getDocumentFilename() . "',
						'" . $this->model->getIsDefault(0, 'single') . "',
						'" . $this->model->getIsNew('', 'single') . "',
						'" . $this->model->getIsDraft(0, 'single') . "',
						'" . $this->model->getIsUpdate(0, 'single') . "',
						'" . $this->model->getIsDelete('', 'single') . "',
						'" . $this->model->getIsActive(0, 'single') . "',
						'" . $this->model->getIsApproved(0, 'single') . "',
						'" . $this->model->getExecuteBy() . "',
						 " . $this->model->getExecuteTime() . ",
						 '" . $this->getCompanyId() . "'
			); ";
            } else {
                if ($this->getVendor() == self::ORACLE) {
                    $sql = "
			INSERT INTO DOCUMENT	(
						DOCUMENTCATEGORYID,			APPLICATIONID,
						FOLDERID,                   MODULEID,
						LEAFID,
						DOCUMENTTITLE,				DOCUMENTDESCRIPTION,
						DOCUMENTPATH,				DOCUMENTFILENAME,
						ISDEFAULT,					ISNEW,
						ISDRAFT,					ISUPDATE,
						ISDELETE,				    ISACTIVE,
						ISAPPROVED,					ISREVIEW,
						ISPOST,                     EXECUTEBY,

						EXECUTETIME,                COMPANYID
			)	VALUES	(
						'" . $this->strict($this->model->getDocumentCategoryId(), 'numeric') . "',
					    '" . $this->getApplicationId(). "',
						'" . $this->getModuleId() . "',
						'" . $this->getFolderId() . "',
						'" . $this->getLeafId() . "',
						'" . $this->strict($this->model->getDocumentTitle(), 'string') . "',
						'" . $this->strict($this->model->getDocumentDescription(), 'string') . "',
						'" . $this->strict($this->model->getDocumentPath(), 'string') . "',
						'" . $this->strict($this->model->getDocumentFilename(), 'string') . "',
						'" . $this->strict($this->model->getIsDefault(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsNew(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsDraft(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsUpdate(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsDelete('', 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsActive(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsApproved(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsReview(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getIsPost(0, 'single'), 'numeric') . "',
						'" . $this->strict($this->model->getExecuteBy(), 'numeric') . "',
						" . $this->model->getExecuteTime() . ",
						'" . $this->strict($this->getCompanyId(), 'numeric') . "'
			); ";
                }
            }
        }
        try {
            $this->q->create($sql);
        } catch (\Exception $e) {
            $this->q->rollback();
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
            exit();
        }
        $this->q->commit();

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

    /**
     * Reporting
     * @see config::excel()
     */
    public function excel()
    {

    }

    /**
     * File Information
     * @param string $filename Filename
     * @return mixed
     */
    public function fileExtension($filename)
    {
        $path_info = pathinfo($filename);
        return $path_info['extension'];
    }

    /**
     * Remove File Extension
     *
     * @param string $filename
     * @return mixed
     */
    public function removeExtension($filename)
    {
        return preg_replace('/(.+)\..*$/', '$1', $filename);
    }

    /**
     * Document Audit Trail
     *
     * @param int $leafId Leaf Primary Key
     * @param string $path
     * @param string $filename
     */
    public function createTrail($leafId, $path, $filename)
    {
        $this->setLeafId($leafId);
        $this->setDocumentPath($path);
        $this->setDocumentFilename($filename);
        $this->create();
    }

    /**
     * Set File Path
     * @param string $path Path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Return Path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
	 * Set Document Filename
     * @param string $documentFilename
	 * @return $this
     */
    public function setDocumentFilename($documentFilename)
    {
        $this->documentFilename = $documentFilename;
		return $this;
    }

    /**
	 * Return Document Filename
     * @return string
     */
    public function getDocumentFilename()
    {
        return $this->documentFilename;
    }

    /**
	 * Return Document Path
     * @param string $documentPath
	 * @return $this
     */
    public function setDocumentPath($documentPath)
    {
        $this->documentPath = $documentPath;
		return $this;
    }

    /**
	 * Return Document Path
     * @return string
     */
    public function getDocumentPath()
    {
        return $this->documentPath;
    }


}