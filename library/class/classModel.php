<?php
namespace Core\Model;

/**
 * Description of classModel
 *
 * @author alien3d
 */
class coreModel
{
    //put your code here
    // database property
    private $vendor;
    private $tableName;
    private $primaryKeyName;
    private $primaryKeyAll;
    private $masterForeignKeyName;
    private $total;
    // filter field
    private $filterCharacter;
    private $filterDate;
    // common field value
    private $isDefault;
    private $isNew;
    private $isDraft;
    private $isUpdate;
    private $isActive;
    private $isDelete;
    private $isApproved;
    private $isReview;
    private $isPost;
    private $isReconciled; // special for  accounting module
    private $executeBy;
    private $executeTime;
    /*
	 * Mysql Database
	 * @var string
	 */
    const MYSQL = 'mysql';
    /**
     * Microsoft Sql Server Database
     * @var string
     */
    const MSSQL = 'microsoft';
    /**
     * Oracle Database
     * @var string
     */
    const ORACLE = 'oracle';

    /**
     * Return Database Vendor
     * @param string $value
     */
    public function setVendor($value)
    {
        $this->vendor = $value;
    }

    /**
     * Return Database Vendor
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set Table Name
     * @param string $value
     */
    public function setTableName($value)
    {
        $this->tableName = $value;
    }

    /**
     * Return Table Name
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Set Primary Name
     * @param string $value
     */
    public function setPrimaryKeyName($value)
    {
        $this->primaryKeyName = $value;
    }

    /**
     * Return Primary Name
     * @return string
     */
    public function getPrimaryKeyName()
    {
        return $this->primaryKeyName;
    }

    /**
     * Set Master Detail Foreign Key Identification
     * @param string $value
     */
    public function setMasterForeignKeyName($value)
    {
        $this->masterForeignKeyName = $value;
    }

    /**
     * Return Master Detail Foreign Key Identification
     * @return string
     */
    public function getMasterForeignKeyName()
    {
        return $this->masterForeignKeyName;
    }

    /**
     * Set Primary Key All
     * @param string $value
     */
    public function setPrimaryKeyAll($value)
    {
        $this->primaryKeyAll = $value;
    }

    /**
     * Return Primary Key All
     * @return string
     */
    public function getPrimaryKeyAll()
    {
        return $this->primaryKeyAll;
    }

    /**
     * Set Total Record of Table
     * @param  int $value
     */
    public function setTotal($value)
    {
        $this->total = $value;
    }

    /**
     * Return Total Record of table
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set isDefault Value
     * @param bool|array $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsDefault($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isDefault = $value;
        } else {
            if ($type == 'array') {
                $this->isDefault[$key] = $value;

            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsDefault ?"
                    )
                );
                exit();
            }
        }
    }

    /**
     * Return isDefault Value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsDefault($key, $type)
    {
        if ($type == 'single') {
            return $this->isDefault;
        } else
            if ($type == 'array') {
                return $this->isDefault[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsDefault ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isNew value
     * @param bool|array $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsNew($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isNew = $value;
        } else
            if ($type == 'array') {
                $this->isNew[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsNew ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isNew value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsNew($key, $type)
    {
        if ($type == 'single') {
            return $this->isNew;
        } else
            if ($type == 'array') {
                return $this->isNew[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsNew ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set IsDraft Value
     * @param bool|array $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsDraft($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isDraft = $value;
        } else
            if ($type == 'array') {
                $this->isDraft[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsDraft ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isDraftValue
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsDraft($key, $type)
    {
        if ($type == 'single') {
            return $this->isDraft;
        } else
            if ($type == 'array') {
                return $this->isDraft[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsDraft ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isUpdate Value
     * @param bool|array $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsUpdate($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isUpdate = $value;
        } else
            if ($type == 'array') {
                $this->isUpdate[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsUpdate ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isUpdate Value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsUpdate($key, $type)
    {
        if ($type == 'single') {
            return $this->isUpdate;
        } else
            if ($type == 'array') {
                return $this->isUpdate[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsUpdate ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isActive Value
     * @param bool|array $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsActive($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isActive = $value;
        } else
            if ($type == 'array') {
                $this->isActive[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsActive ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isActive value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsActive($key, $type)
    {
        if ($type == 'single') {
            return $this->isActive;
        } else
            if ($type == 'array') {
                return $this->isActive[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsActive ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isDelete Value
     * @param bool|array $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsDelete($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isDelete = $value;
        } else
            if ($type == 'array') {
                $this->isDelete[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsDelete ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isDelete Value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsDelete($key, $type)
    {
        if ($type == 'single') {
            return $this->isDelete;
        } else
            if ($type == 'array') {
                return $this->isDelete[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsDelete ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isApproved Value
     * @param bool $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsApproved($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isApproved = $value;
        } else
            if ($type == 'array') {
                $this->isApproved[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsApproved ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isApproved Value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsApproved($key, $type)
    {
        if ($type == 'single') {
            return $this->isApproved;
        } else
            if ($type == 'array') {
                return $this->isApproved[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsApproved ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isReview Value
     * @param bool $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsReview($value, $key, $type)
    {
        if ($type == 'single') {
            $this->isReview = $value;
        } else
            if ($type == 'array') {
                $this->isReview[$key] = $value;
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsReview ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isReview Value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsReview($key, $type)
    {
        if ($type == 'single') {
            return $this->isReview;
        } else
            if ($type == 'array') {
                return $this->isReview[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsReview ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set isPost Value
     * @param bool $value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     */
    public function setIsPost($value, $key, $type)
    {

        if ($type == 'single') {
            $this->isPost = $value;
        } else
            if ($type == 'array') {
                $this->isPost[$key] = $value;

            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsPost ?"
                    )
                );
                exit();
            }
    }

    /**
     * Return isPost Value
     * @param array [int]int $key List Of Primary Key.
     * @param array [int]string $type  List Of Type.0 As 'single' 1 As 'array'
     * @return bool|array
     */
    public function getIsPost($key, $type)
    {
        if ($type == 'single') {
            return $this->isPost;
        } else
            if ($type == 'array') {
                return $this->isPost[$key];
            } else {
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:getIsPost ?"
                    )
                );
                exit();
            }
    }

    /**
     * Set Activity User
     * @param int $value
     */
    public function setExecuteBy($value)
    {
        $this->executeBy = $value;
    }

    /**
     * Get Activity User
     * @return int
     */
    public function getExecuteBy()
    {
        return $this->executeBy;
    }

    /**
     * Set Time Activity User
     * @param string $value
     */
    public function setExecuteTime($value)
    {
        $this->executeTime = $value;
    }

    /**
     * Return Time Activity User
     * @return string Date Time
     */
    public function getExecuteTime()
    {
        return $this->executeTime;
    }

    public function getFilterCharacter()
    {
        return $this->filterCharacter;
    }

    public function setFilterCharacter($filterCharacter)
    {
        $this->filterCharacter = $filterCharacter;
    }

    public function getFilterDate()
    {
        return $this->filterDate;
    }

    public function setFilterDate($filterDate)
    {
        $this->filterDate = $filterDate;
    }

    /**
     *
     * @return
     */
    public function getIsReconciled()
    {
        return $this->isReconciled;
    }

    /**
     *
     * @param $isReconciled
     */
    public function setIsReconciled($isReconciled)
    {
        $this->isReconciled = $isReconciled;
    }
}

?>
