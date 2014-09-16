<?php

/**
 * @param string $str
 * @return string
 */
function checkString($str)
{
    $i = 0;
    $stop = array();
    $str{0} = strtolower($str{0});

    $strArray = str_split($str);

    $totalStr = count($strArray);

    foreach ($strArray as $alpha) {
        $i++;
        if (strtoupper($alpha) == $alpha) {
            $stop[] = $i;
        }
    }
    if (count($stop) > 0) {
        $startString = substr($str, 0, $stop[0] - 1);
    } else {
        $startString = $str;
    }

    $i = 0;
    if (count($stop) > 2) {
        $strK = null;
        foreach ($stop as $d) {

            $e = $stop[$i + 1] - $stop[$i];

            $strK .= substr($str, $d - 1, $e);
            $strK .= " ";
            $i++;
        }
        $lastString = substr($str, $stop[count($stop) - 1] - 1, $totalStr);
        return ucwords(ucfirst($startString) . " " . ucfirst($strK) . " " . ucfirst($lastString));
    } else if (count($stop) > 0) {
        $lastString = substr($str, $stop[count($stop) - 1] - 1, $totalStr);
        return ucwords(ucfirst($startString) . " " . ucfirst($lastString));
    } else {

        return ucwords($startString);
    }
}

$targetDb = "icore";
mysql_connect("localhost", "root", "123456");

mysql_select_db($targetDb);
mysql_query("SET autocommit=0");
//	mysql_query(" truncate tablemapping");
$sqlTable = "show tables";
//$sqlTable = "show tables where Tables_in_icore LIKE  'race%'";

$resultTable = mysql_query($sqlTable);
echo "<br>----------------------" . $sqlTable . "--------------------------<br>";
while (($rowTable = mysql_fetch_assoc($resultTable)) == TRUE) {
    echo "<pre>";
    echo print_r($rowTable);
    echo "</pre>";
    $tableName = $rowTable['Tables_in_' . $targetDb];
    $sqlTableMapping = "select * from tableMapping where tableMapping.tableMappingName='" . $tableName . "'";
    echo "<br>----------------------" . $sqlTableMapping . "--------------------------<br>";
    $resultTableMapping = mysql_query($sqlTableMapping) or die(mysql_error());
    $totalTableMapping = intval(@mysql_num_rows($resultTableMapping));
    // if ($totalTableMapping == 0) {
    // insert into detail table mapping translation
    $sqlFieldTable = "describe `" . $targetDb . "`.`" . $tableName . "`";
    echo "<br>----------------------" . $sqlFieldTable . "--------------------------<br>";
    $resultFieldTable = mysql_query($sqlFieldTable);
    while (($rowFieldTable = mysql_fetch_array($resultFieldTable)) == TRUE) {
        $sqlTableMappingColumn = "
             select * 
             from   tableMapping
             where  tableMapping.tableMappingName='" . $tableName . "'
             and    tableMapping.tableMappingColumnName='" . $rowFieldTable['Field'] . "'";
        echo "<br>----------------------" . $sqlTableMappingColumn . "--------------------------<br>";
        $resultColumn = mysql_query($sqlTableMappingColumn) or die(mysql_error());
        $totalColumn = intval(mysql_num_rows($resultColumn));
        echo " total Column" . $totalColumn;
        $columnName = $rowFieldTable['Field'];

        if ($totalColumn == 0) {
            // insert into table mapping table
            switch ($columnName) {
                case 'isDefault':
                    $tableMappingEnglish = 'Default';
                    break;
                case 'isNew':
                    $tableMappingEnglish = 'New';
                    break;
                case 'isDraft':
                    $tableMappingEnglish = 'Draft';
                    break;
                case 'isUpdate':
                    $tableMappingEnglish = 'Update';
                    break;
                case 'isDelete':
                    $tableMappingEnglish = 'Delete';
                    break;
                case 'isActive':
                    $tableMappingEnglish = 'Active';
                    break;
                case 'isApproved':
                    $tableMappingEnglish = 'Approved';
                    break;
                case 'isReview':
                    $tableMappingEnglish = 'Review';
                    break;
                case 'isPost':
                    $tableMappingEnglish = 'Post';
                    break;
                case 'executeBy':
                    $tableMappingEnglish = 'By';
                    break;
                case 'executeTime':
                    $tableMappingEnglish = 'Time';
                    break;
                default:
                    // not common field
                    $pos = strpos('Id', $columnName);


                    if ($pos === false) {
                        $tableMappingEnglish = checkString(str_replace(($tableName), "", ($columnName)));
                    } else {
                        $tableMappingEnglish = str_replace("Id", "", $columnName);
                    }

                    break;
            }
            $tableMappingNative = $tableMappingEnglish;
            $sqlInsertTableMapping = "
	
						
						INSERT INTO `tablemapping`(
						`tableMappingId`, 											`tableMappingName`, 
						`tableMappingColumnName`, 									`tableMappingEnglish`, 
						`isDefault`, 												`isNew`, 
						`isDraft`, 													`isUpdate`, 
						`isDelete`, 												`isActive`, 
						`isApproved`, 												`isReview`, 
						`isPost`, 													`executeBy`, 
						`executeTime`,                                              `companyId`
					)
			VALUES
					(
						null,														'" . $tableName . "',
						'" . $columnName . "', 										'" . $tableMappingEnglish . "',
						'0',		'0',
						'0',		'0',
						'0',		'0',
						'0',		'0',
						'0',			2,
						'" . date("Y-m-d H:i:s") . "',1


					);";
            echo "<br>----------------------" . $sqlInsertTableMapping . "--------------------------<br>";

            $miau = mysql_query($sqlInsertTableMapping);
            if (!$miau) {
                echo mysql_error() . "Sql " . $sqlInsertTableMapping;
                exit();
            }
            $lastId = mysql_insert_id();

            $sqlInsertTableMappingTranslate = "
			INSERT INTO `tableMappingTranslate`
					(
						`tableMappingId`,													`tableMappingNative`,
						`languageId`,
						`isDefault`,														`isNew`,
						`isDraft`,															`isUpdate`,
						`isDelete`,															`isActive`,
						`isApproved`,														`executeBy`,
						`executeTime`,                                                      `companyId`
					)
			VALUES
					(
						'" . $lastId . "',					'" . $tableMappingNative . "',
						'21',			
						'0',				'0',
						'0',			'0',
						'0',		'0',
						'0',				2,
						'" . date("Y-m-d H:i:s") . "',1);";
            echo "<br>----------------------" . $sqlInsertTableMappingTranslate . "--------------------------<br>";

            $miau2 = mysql_query($sqlInsertTableMappingTranslate);
            if (!$miau2) {
                echo mysql_error() . "aaa" . $sqlInsertTableMappingTranslate;
                exit();
            }

        } else {
            // check if exist detail table translate if translate or not
            $sqlTableMappingColumnX = "
             select *,
					tablemapping.tableMappingId as tableMappingId
             from   tableMapping
			 left join  tablemappingtranslate
			 using	(tablemappingid)
             where  tableMapping.tableMappingName='" . $tableName . "'
             and    tableMapping.tableMappingColumnName='" . $rowFieldTable['Field'] . "'";
            echo "<br>----------------------" . $sqlTableMappingColumn . "--------------------------<br>";
            $resultColumnX = mysql_query($sqlTableMappingColumnX) or die(mysql_error());
            $totalColumnX = intval(mysql_num_rows($resultColumnX));
            echo " total Column" . $totalColumn;
            $columnName = $rowFieldTable['Field'];


            // insert into table mapping table
            switch ($columnName) {
                case 'isDefault':
                    $tableMappingEnglish = 'Default';
                    break;
                case 'isNew':
                    $tableMappingEnglish = 'New';
                    break;
                case 'isDraft':
                    $tableMappingEnglish = 'Draft';
                    break;
                case 'isUpdate':
                    $tableMappingEnglish = 'Update';
                    break;
                case 'isDelete':
                    $tableMappingEnglish = 'Delete';
                    break;
                case 'isActive':
                    $tableMappingEnglish = 'Active';
                    break;
                case 'isApproved':
                    $tableMappingEnglish = 'Approved';
                    break;
                case 'isReview':
                    $tableMappingEnglish = 'Review';
                    break;
                case 'isPost':
                    $tableMappingEnglish = 'Post';
                    break;
                case 'executeBy':
                    $tableMappingEnglish = 'By';
                    break;
                case 'executeTime':
                    $tableMappingEnglish = 'Time';
                    break;
                default:
                    // not common field
                    $pos = strpos('Id', $columnName);


                    if ($pos === false) {
                        $tableMappingEnglish = checkString(str_replace(($tableName), "", ($columnName)));
                    } else {
                        $tableMappingEnglish = str_replace("Id", "", $columnName);
                    }

                    break;
            }
            $tableMappingNative = $tableMappingEnglish;
            $rowX = mysql_fetch_array($resultColumnX);
            $lastId = $rowX['tableMappingId'];
            // check if exist detail
            $sqlCheckTableMappingDetail = "
			SELECT  * FROM`tablemappingtranslate` WHERE `tableMappingId` =  '" . $lastId . "' AND `languageId`=21";
            $resultCheckTableMappingDetail = mysql_query($sqlCheckTableMappingDetail);
            $totalCheckTableMappingDetail = @mysql_num_rows($resultCheckTableMappingDetail);
            if ($totalCheckTableMappingDetail == 0) {
                $sqlInsertTableMappingTranslate = "
			INSERT INTO `tableMappingTranslate`
					(
						`tableMappingId`,													`tableMappingNative`,
						`languageId`,
						`isDefault`,														`isNew`,
						`isDraft`,															`isUpdate`,
						`isDelete`,															`isActive`,
						`isApproved`,														`executeBy`,
						`executeTime`,`companyId`
					)
			VALUES
					(
						'" . $lastId . "',					'" . $tableMappingNative . "',
						'21',			
						'0',				'0',
						'0',			'0',
						'0',		'0',
						'0',				2,
						'" . date("Y-m-d H:i:s") . "',1);";
                echo "<br>----------------------" . $sqlInsertTableMappingTranslate . "--------------------------<br>";

                $miau4 = mysql_query($sqlInsertTableMappingTranslate);
                if (!$miau4) {
                    echo mysql_error();
                    exit();
                }
            } else {
                echo "<br>----------------------" . $tableName . "------------- allready translated<br>";
            }
        }
    }
    // } else {
    //     echo "<br>----------------------" . $tableName . "------------- allready translated<br>";
    //  }
}

mysql_query("SET autocommit=1");
?>