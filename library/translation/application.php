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
$sql = "SELECT * FROM `application` ";
$result = mysql_query($sql);
if (!$result) {
    echo mysql_error();
}
while (($row = mysql_fetch_array($result)) == TRUE) {
    $sqlLanguage = "select * from `language` where `isBing`=1 and `isImportant` = 1 ";
    echo "--------------" . $sqlLanguage . "----------------<br>";
    $resultLanguage = mysql_query($sqlLanguage);
    if (!$resultLanguage) {
        echo mysql_error();
    }
    $c = 0;
    while (($rowLanguage = mysql_fetch_array($resultLanguage)) == TRUE) {
        $sqll = "SELECT * FROM `applicationtranslate` WHERE `languageId`='" . $rowLanguage['languageId'] . "' AND `applicationId`='" . $row['applicationId'] . "'";
        $resultl = mysql_query($sqll);
        if (mysql_num_rows($resultl) == 0) {
            $translated_text = $gt->translate($row['applicationEnglish'], "en", $rowLanguage['languageCode']);
            echo " Table name :[" . $row['applicationName'] . "] : Original:[" . $row['applicationEnglish'] . "]. translation:[" . $translated_text . "]<br>";
            $c++;
            // exit();
            $sql = "INSERT INTO `applicationtranslate`(
 `companyId`,`applicationId`, `applicationNative`, `languageId`,  `executeBy`, `executeTime`) 
    VALUES (
    '1',
    '" . $row['applicationId'] . "',\"" . $translated_text . "\"," . $rowLanguage['languageId'] . ",2,NOW())";
            echo "--------------" . $sql . "----------------<br>";
            $x = mysql_query($sql);

            if (!$x) {
                echo mysql_error();
                exit();
            }
        }
        // exit(); // tengok if jepun support
    }
}
// mysql_query("SET autocommit = 1 ");
echo "done";
echo "c :" . $c;
?>


</body>
</html>