<?php

namespace Core\Portal\Main\TicketThread\Model;

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
 * Class TicketThreadModel
 * this is messagethread model file.This is to ensure strict setting enable for all variable enter to database
 *
 * @name IDCMS .
 * @version 2
 * @author hafizan
 * @package Core\Portal\Main\TicketThread\Model
 * @subpackage Management
 * @link http://www.idcms.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 */
class TicketThreadModel extends ValidationClass {

    /**
     * Primary Key
     * @var int
     */
    private $ticketThreadId;

    /**
     * ticketId
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
     * ticketText
     * @var string
     */
    private $ticketText;

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
        $this->setTableName('messageThread');
        $this->setPrimaryKeyName('messageThreadId');
        $this->setMasterForeignKeyName('messageThreadId');
        $this->setFilterCharacter('messageThreadDescription');
        //$this->setFilterCharacter('messageThreadNote');
        $this->setFilterDate('executeTime');
        /**
         * All the $_POST Environment
         */
        if (isset($_POST ['ticketThreadId'])) {
            $this->setTicketThreadId($this->strict($_POST ['ticketThreadId'], 'integer'), 0, 'single');
        }
        if (isset($_POST ['ticketId'])) {
            $this->setticketId($this->strict($_POST ['ticketId'], 'integer'));
        }
        if (isset($_POST ['userIdFrom'])) {
            $this->setUserIdFrom($this->strict($_POST ['userIdFrom'], 'integer'));
        }
        if (isset($_POST ['userIdTo'])) {
            $this->setUserIdTo($this->strict($_POST ['userIdTo'], 'integer'));
        }
        if (isset($_POST ['ticketText'])) {
            $this->setticketText($this->strict($_POST ['ticketText'], 'text'));
        }
        if (isset($_POST ['isSolve'])) {
            $this->setIsSolve($this->strict($_POST ['isSolve'], 'bool'));
        }
        /**
         * All the $_GET Environment
         */
        if (isset($_GET ['ticketThreadId'])) {
            $this->setTicketThreadId($this->strict($_GET ['ticketThreadId'], 'integer'), 0, 'single');
        }
        if (isset($_GET ['ticketId'])) {
            $this->setticketId($this->strict($_GET ['ticketId'], 'integer'));
        }
        if (isset($_GET ['userIdFrom'])) {
            $this->setUserIdFrom($this->strict($_GET ['userIdFrom'], 'integer'));
        }
        if (isset($_GET ['userIdTo'])) {
            $this->setUserIdTo($this->strict($_GET ['userIdTo'], 'integer'));
        }
        if (isset($_GET ['ticketText'])) {
            $this->setticketText($this->strict($_GET ['ticketText'], 'text'));
        }
        if (isset($_GET ['isSolve'])) {
            $this->setIsSolve($this->strict($_GET ['isSolve'], 'bool'));
        }
        if (isset($_GET ['messageThreadId'])) {
            $this->setTotal(count($_GET ['messageThreadId']));
            if (is_array($_GET ['messageThreadId'])) {
                $this->ticketThreadId = array();
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
            if (isset($_GET ['messageThreadId'])) {
                $this->setTicketThreadId($this->strict($_GET ['messageThreadId'] [$i], 'numeric'), $i, 'array');
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
            $primaryKeyAll .= $this->getTicketThreadId($i, 'array') . ",";
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
        $this->setExecuteTime(date("Y-m-d H:i:s"));
    }

    /**
     * Return Primary Key Value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     * */
    public function getTicketThreadId($key, $type) {
        if ($type == 'single') {
            return $this->ticketThreadId;
        } else {
            if ($type == 'array') {
                return $this->ticketThreadId [$key];
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:getmessageThreadId ?")
                );
                exit();
            }
        }
    }

    /**
     * Set  Primary Key  Value
     * @param int|array $value
     * @param array|int $key List Of Primary Key.
     * @param array|int|string $type  List Of Type.0 As 'single' 1 As 'array'
     * */
    public function setTicketThreadId($value, $key, $type) {
        if ($type == 'single') {
            $this->ticketThreadId = $value;
        } else {
            if ($type == 'array') {
                $this->ticketThreadId[$key] = $value;
            } else {
                echo json_encode(
                        array("success" => false, "message" => "Cannot Identify Type String Or Array:setmessageThreadId?")
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
     * To Return ticketId
     * @return int $ticketId
     */
    public function getTicketId() {
        return $this->ticketId;
    }

    /**
     * To Set ticketId
     * @param int $ticketId
     * @return $this;
     */
    public function setTicketId($ticketId) {
        $this->ticketId = $ticketId;
        return $this;
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
     * @return $this
     */
    public function setUserIdFrom($userIdFrom) {
        $this->userIdFrom = $userIdFrom;
        return $this;
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
     * @return $this;
     */
    public function setUserIdTo($userIdTo) {
        $this->userIdTo = $userIdTo;
        return $this;
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
     * @param string $ticketText
     * @return $this
     */
    public function setTicketText($ticketText) {
        $this->ticketText = $ticketText;
        return $this;
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
     * @return $this;
     */
    public function setIsSolve($isSolve) {
        $this->isSolve = $isSolve;
        return $this;
    }

}

?>