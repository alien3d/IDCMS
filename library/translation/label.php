<html lang="en">
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
require_once('./BingTranslateLib/BingTranslate.class.php');
$c = 0;
$gt = new BingTranslateWrapper('17ABBA6C7400D761EE28324EC320B5D0093F3557');
//$gt->selfTest();


/* Translate from "English" to "French" */
// update common word first from table mapping translate.
mysql_connect("localhost", "root", "123456");
mysql_select_db("icore");
//   mysql_query("SET autocommit = 0 ");
mysql_query("SET NAMES utf8");
$sql = "SELECT * FROM `defaultLabel` WHERE 1";
$result = mysql_query($sql);
if (!$result) {
    echo mysql_error();
    exit();
}
while (($row = mysql_fetch_array($result)) == TRUE) {
    $sqlLanguage = "select * from `language` where `isBing`=1  ";
    echo "--------------" . $sqlLanguage . "----------------<br>";
    $resultLanguage = mysql_query($sqlLanguage);
    if (!$resultLanguage) {
        echo mysql_error();
        exit();
    }
    while (($rowLanguage = mysql_fetch_array($resultLanguage)) == TRUE) {
        // check if exist that language id . if  got don't  update just insert
        $sqll = "SELECT * FROM `defaultLabelTranslate` WHERE `languageId`='" . $rowLanguage['languageId'] . "' AND `defaultLabelId`='" . $row['defaultLabelId'] . "'";
        $resultl = mysql_query($sqll);
        if (mysql_num_rows($resultl) == 0) {
            $translated_text = $gt->translate($row['defaultLabelEnglish'], "en", $rowLanguage['languageCode']);
            echo "Original " . $row['defaultLabelEnglish'] . " translation:[" . $translated_text . "]<br>";
            // exit();
            $c++;
            $sql = "INSERT INTO `defaultLabeltranslate`(
 `defaultLabelId`, `defaultLabelNative`, `languageId`) 
    VALUES (\"" . intval($row['defaultLabelId']) . "\",
            \"" . mysql_real_escape_string($translated_text) . "\",
            \"" . intval($rowLanguage['languageId']) . "\")";
            echo "--------------" . $sql . "----------------<br>";
            $resultn = mysql_query($sql);

            if (!$resultn) {
                echo mysql_error();
                exit();
            }
        }
        // exit();
    }
}
//mysql_query("SET autocommit = 1 ");
echo "c :" . $c;
?>


</body>
</html>