<?php
session_start();
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
$newFakeDocumentRoot = str_replace(basename($_SERVER['PHP_SELF']) . "/", "", str_replace("//", "/", $fakeDocumentRoot)); // start
require_once($newFakeDocumentRoot . "v3/portal/main/controller/portalController.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$portal = new \Core\Portal\Controller\PortalControllerClass();
if (isset($_POST['spotlightString']) && strlen($_POST['spotlightString']) > 0) {
    $portal->setSpotlightString($_POST['spotlightString']);
}
$portal->execute();
header('Content-Type:application/json; charset=utf-8');
echo json_encode(array("success" => true, "spotlight" => $portal->spotlight(), "total" => $portal->getSpotlightTotal()));
exit();
?>
