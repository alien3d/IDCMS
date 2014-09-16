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
require_once($newFakeDocumentRoot . "package/portal/main/controller/portalController.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$portal = new \Core\Portal\Controller\PortalControllerClass();
$portal->execute();
$notificationArray = $portal->getNotification();
$securityToken = $portal->getSecurityToken(); 
?>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</li>
<li class="divider"></li>
<?php
if (is_array($notificationArray)) {
    $total = count($notificationArray);
    if ($total > 0) {
        foreach ($notificationArray as $row) {
            ?>
            <li><a href="#">
                    <img width="50" height="50" style="float:left;border:1px solid #000000;"
                         src="./package/portal/main/images/<?php echo $row['userAvatar']; ?>">

                    &nbsp;&nbsp;&nbsp;<img src="./images/icons/user.png"> <b><?php echo $row['userName'] ?></b>
                    <br>
                    &nbsp;&nbsp;&nbsp;<img
                        src="./images/icons/balloon.png"> <?php echo substr($row['notificationMessage'], 0, 20); ?>
                    <br>
                    &nbsp;&nbsp;&nbsp;<img src="./images/icons/clock-history.png"> <abbr class="timeago"
                                                                                         title="<?php echo date(DATE_ISO8601, strtotime($row['executeTime'])); ?>"><?php echo date('l, F, d, Y \a\t h:i:s a', strtotime($row['executeTime'])); ?></abbr>

                </a></li>
            <li class="divider"></li>
        <?php
        }
    } else {
        ?>
    <?php
    }
} else {
    ?>

<?php } ?>
<li>
    <div align="center"><a href="javascript:void(0);" onClick="loadLeft(183,'<?php echo $securityToken; ?>');"><img
                src="./images/icons/balloon--arrow.png"> See all</a></div>
</li>
<li class="divider"></li>
<script type="text/javascript">
    $(document).ready(function () {
        $("abbr.timeago").timeago();
    });
	function loadLeft(leafId, securityToken) {
                
                var url = './package/portal/main/controller/portalController.php';
                var data;
                $("#centerViewport")
                        .html('').empty()
                        .load(url,
                        {
                            start: 0,
                            limit: 10,
                            method: 'read',
                            type: 'list',
                            detail: 'body',
                            leafId: leafId,
                            pageType: 'leaf',
                            securityToken: securityToken
                        },
                function(response, status, xhr) {
                    if (status === "error") {
                        var msg = "Sorry but there was an error: ";
                        $("#centerViewport")
                                .html('').empty()
                                .html("<div id=infoPanel><div class='alert alert-error'><a class='close' data-dismiss='alert'>×</a>" + msg + xhr.status + " " + xhr.statusText + "</div></div>");
                    } else {
                        var x = response.search("false");
                        if (x > 0) {
                            if (data) {
                                data = json_parse(response);
                                if (data.success === false) {
                                    $("#centerViewport")
                                            .html('').empty()
                                            .html("<div id=infoPanel><div class=\'alert alert-error\'><a class='close' data-dismiss='alert'>×</a><img src=\'./images/icons/smiley-roll-sweat.png\'> " + data.message + "</div></div>");
                                }
                            }
                        }
                    }
                }
                );
            }
</script>