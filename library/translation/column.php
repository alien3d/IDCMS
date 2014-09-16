<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
require_once('./BingTranslateLib/BingTranslate.class.php');

$gt = new BingTranslateWrapper('17ABBA6C7400D761EE28324EC320B5D0093F3557');
//$gt->selfTest();


/* Translate from "English" to "French" */
// update common word first from table mapping translate.
mysql_connect("localhost", "root", "123456");
mysql_select_db("icore");
//mysql_query("SET autocommit = 0 ");
mysql_query("SET NAMES utf8");
$sql = "SELECT * FROM `tableMapping`  ";
// optional if wanted fast
$filter = 'purchaserequest';
//$sql .= " WHERE 1 AND `tableMappingName` like '%" . $filter . "%'";
$result = mysql_query($sql);
if (!$result) {
    echo mysql_error();
}
while (($row = mysql_fetch_array($result)) == TRUE) {
    $sqlLanguage = "select * from `language` where `isBing`=1 AND `isImportant` = 1  AND `languageId` IN	(41,37) ";
    echo "--------------" . $sqlLanguage . "----------------<br>";
    $resultLanguage = mysql_query($sqlLanguage);
    if (!$resultLanguage) {
        echo mysql_error();
    }
    $c = 0;
    while (($rowLanguage = mysql_fetch_array($resultLanguage)) == TRUE) {
        $sqll = "
        SELECT  `tableMappingId`
        FROM    `tablemappingtranslate`
        WHERE   `languageId`='" . $rowLanguage['languageId'] . "'
        AND     `tableMappingId`='" . $row['tableMappingId'] . "'";

        $resultl = mysql_query($sqll);
        if (mysql_num_rows($resultl) == 0) {
            $translated_text = \mysql_real_escape_string($gt->translate($row['tableMappingEnglish'], "en", $rowLanguage['languageCode']));
            echo " Table name :[" . $row['tableMappingName'] . "] : Original:[" . $row['tableMappingEnglish'] . "]. translation:[" . $translated_text . "]<br>";
            $c++;
            // exit();
            $sql = "
			INSERT INTO `tablemappingtranslate`
			(
				`companyId`,
 				`tableMappingId`, 
				`tableMappingNative`, 
				
				`languageId`,  
				`executeBy`, 
				`executeTime`
			) VALUES (
				1,
				'" . $row['tableMappingId'] . "',
				\"" . $translated_text . "\",
				
				" . $rowLanguage['languageId'] . ",
				2,
				NOW())";
            echo "--------------" . $sql . "----------------<br>";
            $x = mysql_query($sql);

            if (!$x) {
                echo mysql_error();
				exit();
            }
        } else {
            echo "allready translate";
        }
    }
    // check if translate english  same or not .. if not same delete it.
    /**
    $sql2 = "
    SELECT 	*
    FROM 	`tablemappingtranslate`
    WHERE 	`languageId`='" . $rowLanguage['languageId'] . "'
    AND 	`tableMappingId`='" . $row['tableMappingId'] . "'
    AND		`languageId`	=21";
    $result2 = mysql_query($sql2);
    $row2 = mysql_fetch_array($result2);
    if ($row2['tableMappingNative'] != $row['tableMappingEnglish']) {
    $sql3 = "
    DELETE	FROM `tablemappingtranslate` WHERE `tableMappingId` ='" . $row['tableMappingId'] . "'";
    $result3 = mysql_query($sql3);
    $sqlLanguage = "select * from `language` where `isBing`=1  ";
    echo "--------------" . $sqlLanguage . "----------------<br>";
    $resultLanguage = mysql_query($sqlLanguage);
    if (!$resultLanguage) {
    echo mysql_error();
    }
    $c = 0;
    while (($rowLanguage = mysql_fetch_array($resultLanguage)) == TRUE) {
    $sqll = "SELECT * FROM `tablemappingtranslate` WHERE `languageId`='" . $rowLanguage['languageId'] . "' AND `tableMappingId`='" . $row['tableMappingId'] . "'";

    $resultl = mysql_query($sqll);
    if (mysql_num_rows($resultl) == 0) {
    $translated_text = \mysql_real_escape_string($gt->translate($row['tableMappingEnglish'], "en", $rowLanguage['languageCode']));
    echo " Table name :[" . $row['tableMappingName'] . "] : Original:[" . $row['tableMappingEnglish'] . "]. translation:[" . $translated_text . "]<br>";
    $c++;
    // exit();
    $sql = "INSERT INTO `tablemappingtranslate`(
    `tableMappingId`, `tableMappingNative`, `languageId`,  `executeBy`, `executeTime`)
    VALUES ('" . $row['tableMappingId'] . "',\"" . $translated_text . "\"," . $rowLanguage['languageId'] . ",2,NOW())";
    echo "--------------" . $sql . "----------------<br>";
    $x = mysql_query($sql);

    if (!$x) {
    echo mysql_error();
    }
    }
    }
    }
     */
}
// mysql_query("SET autocommit = 1 ");
echo "done";
echo "c :" . $c;
?>


</body>
</html>