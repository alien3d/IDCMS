<?php

namespace Core\Portal\Main\Ticket\Model;

// start fake document root. it's absolute path

use Core\Validation\ValidationClass;

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
require_once($newFakeDocumentRoot . "library/class/classValidation.php");

/**
 * Class TicketModel
 * this is ticket model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\Ticket\Model
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TicketModel extends ValidationClass {

    /**
     * Id
     * @var int
     */
    private $ticketId;

    /**
     * userIdFrom
     * @var int
     */
    private $userIdFrom;

    /**
     * userIdTo
     * @var int
     */
    private $userIdTo;

    /**
     * Text
     * @var string
     */
    private $ticketText;

    /**
     * File
     * @var string
     */
    private $ticketFile;

    /**
     * isSolve
     * @var bool
     */
    private $isSolve;

    /**
     * Class Loader
     * @see ValidationClass::execute()
     */
    public function execute() {
        /**
         *  Basic Information Table
         * */
        $this->setTableName('ticket');
        $this->setPrimaryKeyName('ticketId');
        $this->setMasterForeignKeyName('ticketId');
        $this->setFilterCharacter('ticketDescription');
        //$this->setFilterCharacter('ticketNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['ticketId'])) {
            $this->setTicketId($this->strict($_POST ['ticketId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['userIdFrom'])) {
            $this->setUserIdFrom($this->strict($_POST ['userIdFrom'], 'integer'));
        }
        if (isset($_POST ['userIdTo'])) {
            $this->setUserIdTo($this->strict($_POST ['userIdTo'], 'integer'));
        }
        if (isset($_POST ['ticketText'])) {
            $this->setTicketText($this->strict($_POST ['ticketText'], 'text'));
        }
        if (isset($_POST ['ticketFile'])) {
            $this->setTicketFile($this->strict($_POST ['ticketFile'], 'text'));
        }
        if (isset($_POST ['isSolve'])) {
            $this->setIsSolve($this->strict($_POST ['isSolve'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['ticketId'])) {
            $this->setTicketId($this->strict($_GET ['ticketId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['userIdFrom'])) {
            $this->setUserIdFrom($this->strict($_GET ['userIdFrom'], 'integer'));
        }
        if (isset($_GET ['userIdTo'])) {
            $this->setUserIdTo($this->strict($_GET ['userIdTo'], 'integer'));
        }
        if (isset($_GET ['ticketText'])) {
            $this->setTicketText($this->strict($_GET ['ticketText'], 'text'));
        }
        if (isset($_GET ['ticketFile'])) {
            $this->setTicketFile($this->strict($_GET ['ticketFile'], 'text'));
        }
        if (isset($_GET ['isSolve'])) {
            $this->setIsSolve($this->strict($_GET ['isSolve'], 'bool'));
        }
        if (isset($_GET ['ticketId'])) {
            $this->setTotal(count($_GET ['ticketId']));
            if (is_array($_GET ['ticketId'])) {
                $this->ticketId = array();
            }
        }
        if (isset($_GET ['isDefault'])) {
            $this->setIsDefaultTotal(count($_GET['isDefault']));
            if (is_array($_GET ['isDefault'])) {
                $this->isDefault = array();
            }
        }
        if (isset($_GET ['isNew'])) {
            $this->setIsNewTotal(count($_GET['isNew']));
            if (is_array($_GET ['isNew'])) {
                $this->isNew = array();
            }
        }
        if (isset($_GET ['isDraft'])) {
            $this->setIsDraftTotal(count($_GET['isDraft']));
            if (is_array($_GET ['isDraft'])) {
                $this->isDraft = array();
            }
        }
        if (isset($_GET ['isUpdate'])) {
            $this->setIsUpdateTotal(count($_GET['isUpdate']));
            if (is_array($_GET ['isUpdate'])) {
                $this->isUpdate = array();
            }
        }
        if (isset($_GET ['isDelete'])) {
            $this->setIsDeleteTotal(count($_GET['isDelete']));
            if (is_array($_GET ['isDelete'])) {
                $this->isDelete = array();
            }
        }
        if (isset($_GET ['isActive'])) {
            $this->setIsActiveTotal(count($_GET['isActive']));
            if (is_array($_GET ['isActive'])) {
                $this->isActive = array();
            }
        }
        if (isset($_GET ['isApproved'])) {
            $this->setIsApprovedTotal(count($_GET['isApproved']));
            if (is_array($_GET ['isApproved'])) {
                $this->isApproved = array();
            }
        }
        if (isset($_GET ['isReview'])) {
            $this->setIsReviewTotal(count($_GET['isReview']));
            if (is_array($_GET ['isReview'])) {
                $this->isReview = array();
            }
        }
        if (isset($_GET ['isPost'])) {
            $this->setIsPostTotal(count($_GET['isPost']));
            if (is_array($_GET ['isPost'])) {
                $this->isPost = array();
            }
        }
        $primaryKeyAll = '';
        for ($i = 0; $i < $this->getTotal(); $i++) {
            if (isset($_GET ['ticketId'])) {
                $this->setTicketId($this->strict($_GET ['ticketId'] [$i], 'numeric'), $i, 'array');
            }
            if (isset($_GET ['isDefault'])) {
                if ($_GET ['isDefault'] [$i] == 'true') {
                    $this->setIsDefault(1, $i, 'array');
                } else {
                    if ($_GET ['isDefault'] [$i] == 'false') {
                        $this->setIsDefault(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isNew'])) {
                if ($_GET ['isNew'] [$i] == 'true') {
                    $this->setIsNew(1, $i, 'array');
                } else {
                    if ($_GET ['isNew'] [$i] == 'false') {
                        $this->setIsNew(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isDraft'])) {
                if ($_GET ['isDraft'] [$i] == 'true') {
                    $this->setIsDraft(1, $i, 'array');
                } else {
                    if ($_GET ['isDraft'] [$i] == 'false') {
                        $this->setIsDraft(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isUpdate'])) {
                if ($_GET ['isUpdate'] [$i] == 'true') {
                    $this->setIsUpdate(1, $i, 'array');
                }
                if ($_GET ['isUpdate'] [$i] == 'false') {
                    $this->setIsUpdate(0, $i, 'array');
                }
            }
            if (isset($_GET ['isDelete'])) {
                if ($_GET ['isDelete'] [$i] == 'true') {
                    $this->setIsDelete(1, $i, 'array');
                } else {
                    if ($_GET ['isDelete'] [$i] == 'false') {
                        $this->setIsDelete(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isActive'])) {
                if ($_GET ['isActive'] [$i] == 'true') {
                    $this->setIsActive(1, $i, 'array');
                } else {
                    if ($_GET ['isActive'] [$i] == 'false') {
                        $this->setIsActive(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isApproved'])) {
                if ($_GET ['isApproved'] [$i] == 'true') {
                    $this->setIsApproved(1, $i, 'array');
                } else {
                    if ($_GET ['isApproved'] [$i] == 'false') {
                        $this->setIsApproved(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isReview'])) {
                if ($_GET ['isReview'] [$i] == 'true') {
                    $this->setIsReview(1, $i, 'array');
                } else {
                    if ($_GET ['isReview'] [$i] == 'false') {
                        $this->setIsReview(0, $i, 'array');
                    }
                }
            }
            if (isset($_GET ['isPost'])) {
                if ($_GET ['isPost'] [$i] == 'true') {
                    $this->setIsPost(1, $i, 'array');
                } else {
                    if ($_GET ['isPost'] [$i] == 'false') {
                        $this->setIsPost(0, $i, 'array');
                    }
                }
            }
            $primaryKeyAll .= $this->getTicketId($i, 'array') . ",";
        }
        $this->setPrimaryKeyAll((substr($primaryKeyAll, 0, -1)));
        /**
         * All the $_SESSION Environment
         */
        if (isset($_SESSION ['userId'])) {
            $this->setExecuteBy($_SESSION ['userId']);
        }
        /**
         * TimeStamp Value.
         */
        $this->setExecuteTime("'" . date("Y-m-d H:i:s") . "'");
    }

    /**
     * Return message Primary Key  Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     * */
    public function getTicketId($key, $type) {
        if ($type == 'single') {
            return $this->ticketId;
        } else {
            if ($type == 'array') {
                return $this->ticketId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getticketId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set ticket Primary Key  Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * */
    public function setTicketId($value, $key, $type) {
        if ($type == 'single') {
            $this->ticketId = $value;
        } else {
            if ($type == 'array') {
                $this->ticketId[$key] = $value;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setticketId?")
                );
                exit();
            }
        }
    }

    /**
     * Create
     * @see ValidationClass::create()
     */
    public function create() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Update
     * @see ValidationClass::update()
     */
    public function update() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(1, '', 'single');
        $this->setIsActive(1, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Delete
     * @see ValidationClass::delete()
     */
    public function delete() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(0, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, '', 'single');
        $this->setIsDelete(1, '', 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Draft
     * @see ValidationClass::draft()
     */
    public function draft() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(1, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Approved
     * @see ValidationClass::approved()
     */
    public function approved() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Review
     * @see ValidationClass::review()
     */
    public function review() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(0, 0, 'single');
        $this->setIsReview(1, 0, 'single');
        $this->setIsPost(0, 0, 'single');
    }

    /**
     * Post
     * @see ValidationClass::post()
     */
    public function post() {
        $this->setIsDefault(0, 0, 'single');
        $this->setIsNew(1, 0, 'single');
        $this->setIsDraft(0, 0, 'single');
        $this->setIsUpdate(0, 0, 'single');
        $this->setIsActive(0, 0, 'single');
        $this->setIsDelete(0, 0, 'single');
        $this->setIsApproved(1, 0, 'single');
        $this->setIsReview(0, 0, 'single');
        $this->setIsPost(1, 0, 'single');
    }

    /**
     * To Return userIdFrom
     * @return int $userIdFrom
     */
    public function getUserIdFrom() {
        return $this->userIdFrom;
    }

    /**
     * To Set userIdFrom
     * @param int $userIdFrom
     * */
    public function setUserIdFrom($userIdFrom) {
        $this->userIdFrom = $userIdFrom;
    }

    /**
     * To Return userIdTo
     * @return int $userIdTo
     */
    public function getUserIdTo() {
        return $this->userIdTo;
    }

    /**
     * To Set userIdTo
     * @param int $userIdTo
     * */
    public function setUserIdTo($userIdTo) {
        $this->userIdTo = $userIdTo;
    }

    /**
     * To Return ticketText
     * @return string $ticketText
     */
    public function getTicketText() {
        return $this->ticketText;
    }

    /**
     * To Set ticketText
     * * @param string $ticketText
     * */
    public function setTicketText($ticketText) {
        $this->ticketText = $ticketText;
    }

    /**
     * To Return ticketFile
     * @return string $ticketFile
     */
    public function getTicketFile() {
        return $this->ticketFile;
    }

    /**
     * To Set ticketFile
     * * @param string $ticketFile
     * */
    public function setTicketFile($ticketFile) {
        $this->ticketFile = $ticketFile;
    }

    /**
     * To Return isSolve
     * @return bool $isSolve
     */
    public function getIsSolve() {
        return $this->isSolve;
    }

    /**
     * To Set isSolve
     * @param bool $isSolve
     */
    public function setIsSolve($isSolve) {
        $this->isSolve = $isSolve;
    }

}

?>