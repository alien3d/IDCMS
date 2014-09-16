<?php
session_start();
use Core\Portal\Controller\PortalControllerClass;
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
    if ($d[$i] == 'library' || $d[$i] == 'v2' || $d[$i] == 'v3') {
        break;
    }
    $newPath[] .= $d[$i] . "/";
}
$fakeDocumentRoot = null;
for ($z = 0; $z < count($newPath); $z++) {
    $fakeDocumentRoot .= $newPath[$z];
}
$newFakeDocumentRoot = str_replace(basename($_SERVER['PHP_SELF'])."/", "", str_replace("//", "/", $fakeDocumentRoot)); // start
require_once ($newFakeDocumentRoot . "v3/portal/main/controller/portalController.php");
$portal = new PortalControllerClass();
$portal->execute();
// only valid can update
if(isset($_POST['languageId']) && isset($_SESSION['staffId'])){ 
	// hacker will try injection query here. filter first
	$portal->setChangeLanguage($portal->strict($_POST['languageId'],'numeric'));
}else{
	header('Content-Type:application/json; charset=utf-8');
	echo json_encode(array("success"=>false,"message"=>"Something wrong setup language and staff"));
	exit();
} 
?>