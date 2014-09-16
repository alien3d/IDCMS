<?php

namespace Core\Validation;

/**
 * Abstract Class Validation for Model Purpose.
 * @author hafizan
 *
 */
abstract class ValidationClass
{

    // database property
    /**
     * Database Vendor
     * @var string
     */
    private $vendor;

    /**
     * Table Name
     * @var string
     */
    private $tableName;

    /**
     * Primary Key Name
     * @var string
     */
    private $primaryKeyName;

    /**
     * All Primary key Value
     * @var array
     */
    private $primaryKeyAll;

    /**
     * Master Foreign Key Name or identification
     * @var string
     */
    private $masterForeignKeyName;

    /**
     * Total Record Of Primary Key
     * @var int
     */
    private $total;

    /**
     * Total Record Default
     * @var int
     */
    private $isDefaultTotal;

    /**
     * Total Record Draft
     * @var int
     */
    private $isDraftTotal;

    /**
     * Total Record New
     * @var int
     */
    private $isNewTotal;

    /**
     * Total Record Update
     * @var int
     */
    private $isUpdateTotal;

    /**
     * Total Record Delete
     * @var int
     */
    private $isDeleteTotal;

    /**
     * Total Record Active
     * @var int
     */
    private $isActiveTotal;

    /**
     * Total Record Review
     * @var string
     */
    private $isReviewTotal;

    /**
     * Total Record Approved
     * @var int
     */
    private $isApprovedTotal;

    /**
     * Total Record Post
     * @var int
     */
    private $isPostTotal;

    /**
     * Total Record Reconciled
     * @var int
     */
    private $isReconciledTotal;

    /**
     * Filter Character
     * @var string
     */
    private $filterCharacter;

    /**
     * Filter Date
     * @var string
     */
    private $filterDate;
    // common field value
    /**
     * Default
     * @var int
     */
    public $isDefault;

    /**
     * New
     * @var int
     */
    public $isNew;

    /**
     * Draft
     * @var int
     */
    public $isDraft;

    /**
     * Update
     * @var int
     */
    public $isUpdate;

    /**
     * Active
     * @var int
     */
    public $isActive;

    /**
     * Delete
     * @var int
     */
    public $isDelete;

    /**
     * Approved
     * @var int
     */
    public $isApproved;

    /**
     * Review
     * @var int
     */
    public $isReview;

    /**
     * Post
     * @var int
     */
    public $isPost;

    /**
     * Is Reconciled
     * @var int
     */
    public $isReconciled; // special for  accounting module
    /**
     * Execute By
     * @var int
     */
    public $executeBy;

    /**
     * Execute Time
     * @var mixed
     */
    private $executeTime;

    /**
     * Type
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;
	
	/**
	 * Calendar View
     * @var string
     */
    private $calendarView;
	
	/**
	 * View Type
     * @var string
     */
    private $viewType;
	
	/**
	 * Show Date
     * @var string
     */
    private $showDate;
	/**
	 * From
     * @var string
     */
    private $from;

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
     * Class Loader
     */
    abstract protected function execute();

    /**
     * Out site $_POST create record
     */
    abstract protected function create();

    /**
     * Outside $_POST update record
     */
    abstract protected function update();

    /**
     * Outside $_POST delete record
     */
    abstract protected function delete();

    /**
     * Draft Record From Database
     */
    abstract protected function draft();

    /**
     * Review Record From Database
     */
    abstract protected function review();

    /**
     * Post Record From Database
     */
    abstract protected function post();

    /**
     * Post Record From Database
     */
    abstract protected function approved();

    /**
     * @param string $v
     * @param string $t
     * @return bool|int|mixed|null|string
     */
    public function strict($v, $t)
    {
        $string = null;
        $this->value = $v;
        $this->type = $t;
        // short form code available
        if ($this->type == 'password' || $this->type == 'p') {
            if (strlen($this->value) != 32) {
                if (empty($this->value)) {
                    $string = null;
                }
            }
            return (addslashes($this->value));
        } elseif ($this->type == 'numeric'
            || $this->type == 'n'
            || $this->type == 'integer'
            || $this->type == 'int'
        ) {
            if (!is_numeric($this->value)) {
                $this->value = 0;
                $string = ($this->value);
            } else {
                $string = (intval($this->value));
            }
        } elseif ($this->type == 'boolean' || $this->type == 'b') {
            if ($this->value == 'TRUE') {
                $string = 1;
            } elseif ($this->value) {
                $string = 0;
            }
        } elseif ($this->type == 'string' || $this->type == 's' || $this->type == 'text') {
            if (empty($this->value) && (strlen($this->value) == 0)) {
                $this->value = null;
                $string = ($this->value);
            } elseif (strlen($this->value) == 0) {
                $this->value = null;
                $string = ($this->value);
            } else {
                $this->value = addslashes($this->value);
                $string = $this->value;
            }
        } else {
            if (($this->type == 'email' || $this->type == 'e') ||
                ($this->type == 'filename' || $this->type == 'f') ||
                ($this->type == 'iconname' || $this->type == 'i') ||
                ($this->type == 'calendar' || $this->type == 'c') ||
                ($this->type == 'username' || $this->type == 'u') ||
                ($this->type == 'web' || $this->type == 'wb')
            ) {
                if (empty($this->value) && (strlen($this->value) == 0)) {
                    $this->value = null;
                    $string = ($this->value);
                } elseif (strlen($this->value) == 0) {
                    $this->value = null;
                    $string = ($this->value);
                } else {
                    $this->value = trim($this->value); // trim any space better for searching issue
                    $string = $this->value;
                }
            } elseif ($this->type == 'wyswyg' || $this->type == 'w') {
                $this->value = addslashes($this->value);
                $string = (htmlspecialchars($this->value));
            } elseif ($this->type == 'blob') {
                $this->value = addslashes($this->value);
                $string = (htmlspecialchars($this->value));
            } elseif ($this->type == 'memo' || $this->type == 'm') {
                $this->value = addslashes($this->value);
                $string = (htmlspecialchars($this->value));
            } elseif ($this->type == 'currency' || $this->type == 'double') {
                $this->value = str_replace("$", "", $this->value);
                $this->value = str_replace(",", "", $this->value);
                $string = ($this->value);
            } elseif ($this->type == 'float' || $this->type == 'f') {
                $this->value = str_replace("$", "", $this->value);
                $this->value = str_replace(",", "", $this->value);
                $string = ($this->value);
            } elseif ($this->type == 'date' || $this->type == 'd') {
                if (empty($this->value)) {
                    $string = (date("Y-m-d"));
                } else {
                    $pos = strpos($this->value,"-");
					if ($pos !== false) {
						$x = explode("-", $this->value);
						$string = $x[2] . "-" . $this->setZero($x[1]) . "-" . $this->setZero($x[0]);
					}
					$pos = strpos($this->value,"/");
					if ($pos !== false) {
						$x = explode("/", $this->value);
						
						//$str = $x[2] . "-" . $this->setZero($x[1]) . "-" . $this->setZero($x[0]);
						$string = $x[2] . "-" . $this->setZero($x[0]) . "-" . $this->setZero($x[1]);
					}
                }
            }
        }
        return $string;
    }

    /**
     * Add 0 figure to the string
     * @param int $str
     * @return string
     */
    public function setZero($str)
    {
        $value = intval($str);
        if (strlen($value) == 1) {
            return "0" . $value;
        } else {
            return $value;
        }
    }

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
        } else {
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
        } else {
            if ($type == 'array') {
                $this->isNew[$key] = $value;
            } else {
                debug_print_backtrace();
                echo "<br>";
                echo json_encode(
                    array(
                        "success" => false,
                        "message" => "Cannot IdentifyType String Or Array:setIsNew ?"
                    )
                );
                exit();
            }
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
        } else {
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
    }

    /**
     * Set Activity User
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setExecuteBy($value)
    {
        $this->executeBy = $value;
        return $this;
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
     * @return \Core\Validation\ValidationClass
     */
    public function setExecuteTime($value)
    {
        $this->executeTime = $value;
        return $this;
    }

    /**
     * Return Time Activity User
     * @return string
     */
    public function getExecuteTime()
    {
        return $this->executeTime;
    }

    /**
     * Return Filter Character
     * @return string
     */
    public function getFilterCharacter()
    {
        return $this->filterCharacter;
    }

    /**
     * Set Filter Character
     * @param string $filterCharacter
     * @return \Core\Validation\ValidationClass
     */
    public function setFilterCharacter($filterCharacter)
    {
        $this->filterCharacter = $filterCharacter;
        return $this;
    }

    /**
     * Return Filter Date
     * @return mixed
     */
    public function getFilterDate()
    {
        return $this->filterDate;
    }

    /**
     *
     * @param mixed $filterDate
     * @return \Core\Validation\ValidationClass
     */
    public function setFilterDate($filterDate)
    {
        $this->filterDate = $filterDate;
        return $this;
    }

    /**
     * Return IsReconciled
     * @return int
     */
    public function getIsReconciled()
    {
        return $this->isReconciled;
    }

    /**
     * Return IsReconciled
     * @param int $isReconciled
     * @return \Core\Validation\ValidationClass
     */
    public function setIsReconciled($isReconciled)
    {
        $this->isReconciled = $isReconciled;
        return $this;
    }

    /**
     * Return Is New Total
     * @return int
     */
    public function getIsNewTotal()
    {
        return $this->isNewTotal;
    }

    /**
     * Return IsNew total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsNewTotal($value)
    {
        $this->isNewTotal = $value;
        return $this;
    }

    /**
     * Return Is Update Total
     * @return int
     */
    public function getIsUpdateTotal()
    {
        return $this->isUpdateTotal;
    }

    /**
     * Return IsUpdate Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsUpdateTotal($value)
    {
        $this->isUpdateTotal = $value;
        return $this;
    }

    /**
     * Return IsDelete Total
     * @return int
     */
    public function getIsDeleteTotal()
    {
        return $this->isDeleteTotal;
    }

    /**
     * Return IsDelete Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsDeleteTotal($value)
    {
        $this->isDeleteTotal = $value;
        return $this;
    }

    /**
     * Return IsActive Total
     * @return int
     */
    public function getIsActiveTotal()
    {
        return $this->isActiveTotal;
    }

    /**
     * Set Is Active Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsActiveTotal($value)
    {
        $this->isActiveTotal = $value;
        return $this;
    }

    /**
     * Return Is Review Total
     * @return int
     */
    public function getIsReviewTotal()
    {
        return $this->isReviewTotal;
    }

    /**
     * Set IsReview Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsReviewTotal($value)
    {
        $this->isReviewTotal = $value;
        return $this;
    }

    /**
     * Return Is Post Total
     * @return int
     */
    public function getIsPostTotal()
    {
        return $this->isPostTotal;
    }

    /**
     * Set IsPost Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsPostTotal($value)
    {
        $this->isPostTotal = $value;
        return $this;
    }

    /**
     * Return IsDraft Total
     * @return int
     */
    public function getIsDraftTotal()
    {
        return $this->isDraftTotal;
    }

    /**
     * Set IsDraft Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsDraftTotal($value)
    {
        $this->isDraftTotal = $value;
        return $this;
    }

    /**
     * REturn Is Reconciled Total
     * @return int
     */
    public function getIsReconciledTotal()
    {
        return $this->isReconciledTotal;
    }

    /**
     * Set IsReconciled Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsReconciledTotal($value)
    {
        $this->isReconciledTotal = $value;
        return $this;
    }

    /**
     * Return IsDefault Total
     * @return int
     */
    public function getIsDefaultTotal()
    {
        return $this->isDefaultTotal;
    }

    /**
     * Set Is Default Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsDefaultTotal($value)
    {
        $this->isDefaultTotal = $value;
        return $this;
    }

    /**
     * Return Is approved Total
     * @return int
     */
    public function getIsApprovedTotal()
    {
        return $this->isApprovedTotal;
    }

    /**
     * Return Is Approved Total
     * @param int $value
     * @return \Core\Validation\ValidationClass
     */
    public function setIsApprovedTotal($value)
    {
        $this->isApprovedTotal = $value;
        return $this;
    }
	/**
     * Return Calendar View
     * @return string
     */
    public function getCalendarView()
    {
        return $this->calendarView;
    }

    /**
     * Return Calendar View
     * @param string $value option ('time','day','month','week','year')
     * @return \Core\Validation\ValidationClass
     */
    public function setCalendarView($value)
    {
        $this->calendarView = $value;
        return $this;
    }
	/**
     * Return View Type
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    /**
     * Return View Type
     * @param string $value option ('time','day','month','week','year')
     * @return \Core\Validation\ValidationClass
     */
    public function setViewType($value)
    {
        $this->viewType = $value;
        return $this;
    }
	/**
     * Return Show Date
     * @return string
     */
    public function getShowDate()
    {
        return $this->showDate;
    }

    /**
     * Return Show Date
     * @param string $value
     * @return \Core\Validation\ValidationClass
     */
    public function setShowDate($value)
    {
        $this->showDate = $value;
        return $this;
    }
	/**
     * Return From
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Return From
     * @param string $value
     * @return \Core\Validation\ValidationClass
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

}