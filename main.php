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
$newFakeDocumentRoot = str_replace("main.php/", "", str_replace("//", "/", $fakeDocumentRoot)); // start
require_once($newFakeDocumentRoot . "v3/portal/main/controller/portalController.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$translator->setCurrentTable('notification', 'ticket', 'ticketThread');
$translator->execute();
$t = $translator->getDefaultTranslation(); // short because code too long

$portal = new \Core\Portal\Controller\PortalControllerClass();
$portal->execute();
$application = $portal->getApplicationArray();
$languageArray = $portal->getLanguage();
$totalLanguage = count($languageArray);

$notificationArray = $portal->getNotification();
$totalNotification = count($notificationArray);

// @todo message and task  total
$additionalStory = $portal->getStory();
if(($_SESSION['staffId'] ==9) || empty($_SESSION['staffId'])) {
	echo "<script>";
	echo "window.location.href='index.php'";
	echo "</script>";
	header("index.php");
	exit();
	// two way redirect
} 
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="utf-8">
<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
<title>IDCMSAPP</title>
<meta name="description" content="">
<meta name="author" content="">
<!-- Use the correct meta names below for your web application
			 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
			 
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Basic Styles -->
<link rel="stylesheet" type="text/css" media="screen" href="./css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="./css/font-awesome.min.css">
<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
<link rel="stylesheet" type="text/css" media="screen" href="./css/smartadmin-production.css">
<link rel="stylesheet" type="text/css" media="screen" href="./css/smartadmin-skins.css">

<link rel="stylesheet" type="text/css" media="screen" href="./library/chosen/chosen.css">
<link rel="stylesheet" type="text/css" media="screen" href="./library/chosen-bootstrap-master/chosen.bootstrap.min.css">
<link rel="stylesheet" href="./library/twitter3/datepicker/css/datepicker.css">
<link rel="stylesheet" href="./library/upload/fineuploader-3.5.0.css" id="cssUpload">
<!-- SmartAdmin RTL Support is under construction
		<link rel="stylesheet" type="text/css" media="screen" href="./library/css/smartadmin-rtl.css"> -->
<!-- We recommend you use "your_style.css" to override SmartAdmin
		specific styles this will also ensure you retrain your customization
		with each SmartAdmin update.
<link rel="stylesheet" href="./library/twitter3/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css">
<link rel="stylesheet" type="text/css" media="screen" href="./library/css/demo.css">
<!-- FAVICONS -->
<link rel="shortcut icon" href="./img/favicon/favicon.ico" type="image/x-icon">
<link rel="icon" href="./img/favicon/favicon.ico" type="image/x-icon">
<style>
	@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 300;
  src: local('Open Sans Light'), local('OpenSans-Light'), url('./fonts/open_sans_normal_300.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: local('Open Sans'), local('OpenSans'), url('./fonts/open_sans_normal_400.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 700;
  src: local('Open Sans Bold'), local('OpenSans-Bold'), url('./fonts/open_sans_normal_700.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: italic;
  font-weight: 400;
  src: local('Open Sans Italic'), local('OpenSans-Italic'), url('./fonts/open_sans_italic_400.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: italic;
  font-weight: 700;
  src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url('./fonts/open_sans_italic_700.woff') format('woff');
  }
</style>
</head>
<style>
ul.scroll-menu {
	position: absolute;
	/*display: inherit !important;*/
                overflow-x: auto;
	width: 100%;
	height: auto;
	max-height: 400px;
	max-width: 300px;
	margin-left: 15px;
	border-radius: 3px;
	-webkit-border-radius: 0 !important;
	-moz-border-radius: 0 !important;
	-ms-border-radius: 0 !important;
	-o-border-radius: 0 !important;
	border-radius: 0 !important;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	-ms-box-shadow: none;
	-o-box-shadow: none;
	box-shadow: none;
	background:#f6f6f6;
}
ul.scroll-menu-2x {
	max-height: 230px;
}
.messages-dropdown .dropdown-menu .message-preview .avatar,  .messages-dropdown .dropdown-menu .message-preview .name,  .messages-dropdown .dropdown-menu .message-preview .message,  .messages-dropdown .dropdown-menu .message-preview .time {
	display: block;
}
.messages-dropdown .dropdown-menu .message-preview .avatar {
	float: left;
	margin-right: 15px;
	margin-top:0px;
}
.messages-dropdown .dropdown-menu .message-preview .name {
	font-weight: bold;
}
.messages-dropdown .dropdown-menu .message-preview .message {
	font-size: 12px;
}
.messages-dropdown .dropdown-menu .message-preview .time {
	font-size: 12px;
}
.messages-dropdown .dropdown-menu {
	min-width: 300px;
	max-width: 450px;
}
.messages-dropdown .dropdown-menu li a {
	white-space: normal;
}
.searchResult {
	min-width: 300px;
	max-width: 450px;
}
.qq-upload-list {
	text-align: left;
}
/* For the bootstrapped demos */
            li.alert-success {
	background-color: #DFF0D8;
}
li.alert-error {
	background-color: #F2DEDE;
}
.alert-error .qq-upload-failed-text {
	display: inline;
}
ul.nav li.dropdown:hover ul.dropdown-menu {
	display: block;
}
a.menu:after, .dropdown-toggle:after {
	content: none;
}
div.btn-group:hover:enabled ul.dropdown-menu {
	display: block;
}
</style>
<body class="">
<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
<!-- HEADER -->
<header id="header">
  <div id="logo-group">
    <!-- PLACE YOUR LOGO HERE -->
    <span id="logo"> <img src="./img/logo.png" alt="IDCMS Application Suite"> </span>
    <!-- END LOGO PLACEHOLDER -->
    <!-- Note: The activity badge color changes when clicked and resets the number to 0
				Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
    <span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b> </span>
    <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
    <div class="ajax-dropdown">
      <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
      <div class="btn-group btn-group-justified" data-toggle="buttons">
        <label class="btn btn-default">
        <input type="radio" name="activity" id="./library/portal/main/view/mail.php">
        <?php echo ucfirst($t['messageTextLabel']); ?> (14) </label>
        <label class="btn btn-default">
        <input type="radio" name="activity" id="./library/portal/main/view/notification.php">
        <?php echo ucfirst($t['notificationTextLabel']); ?> (3) </label>
        <label class="btn btn-default">
        <input type="radio" name="activity" id="./library/portal/main/view/task.php">
        <?php echo ucfirst($t['taskTextLabel']); ?> (4) </label>
      </div>
      <!-- notification content -->
      <div class="ajax-notifications custom-scroll">
        <div class="alert alert-transparent">
          <h4>Click a button to show messages here</h4>
          This blank page message helps protect your privacy, or you can show the first message here automatically. </div>
        <i class="fa fa-lock fa-4x fa-border"></i> </div>
      <!-- end notification content -->
      <!-- footer: refresh area -->
      <span> Last updated on: 12/12/2013 9:43AM
      <button type="button" data-loading-text=" Loading..."  class="btn btn-xs btn-default pull-right"> <i class="fa fa-refresh"></i> </button>
      </span>
      <!-- end footer -->
    </div>
    <!-- END AJAX-DROPDOWN -->
  </div>
  <!-- history dropdown -->
  <div id="project-context"> <span class="label">Projects:</span> <span id="project-selector" class="popover-trigger-element dropdown-toggle" data-toggle="dropdown"><?php echo $t['historyTextLabel']; ?><i class="fa fa-angle-down"></i></span>
    <!-- Suggestion: populate this list with fetch and push technique -->
    <ul class="dropdown-menu">
      <li> <a href="javascript:void(0);">Cetak Screen</a> </li>
      <li> <a href="javascript:void(0);">Notes on pipeline upgradee</a> </li>
      <li> <a href="javascript:void(0);">Assesment Report for merchant account</a> </li>
      <li class="divider"></li>
      <li> <a href="javascript:void(0);"><i class="fa fa-power-off"></i> Clear</a> </li>
    </ul>
    <!-- end dropdown-menu-->
  </div>
  <!-- end history dropdown -->
  <!-- pulled right: nav area -->
  <div class="pull-right">
    <!-- collapse menu button -->
    <div id="hide-menu" class="btn-header pull-right"> <span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span> </div>
    <!-- end collapse menu -->
    <!-- logout button -->
    <div id="logout" class="btn-header transparent pull-right"> <span> <a href="logout.php" title="Sign Out"><i class="fa fa-sign-out"></i></a> </span> </div>
    <!-- end logout button -->
    <!-- search mobile button (this is hidden till mobile view port) -->
    <div id="search-mobile" class="btn-header transparent pull-right"> <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span> </div>
    <!-- end search mobile button -->
    <!-- input: search field -->
    <form action="#ajax/search.php" class="header-search pull-right">
      <ul class="typeahead typeahead-long dropdown-menu hide" id="searchResult" style="width:300px">
      </ul>
      <input class="search-query dropdown-toggle form-control" type="text" placeholder=" <?php echo $t['searchButtonLabel']; ?>" name="spotLightText" id="spotlightText" onKeyUp="spotlight('<?php echo $portal->getSecurityToken(); ?>');"  style="width:300px">
      <button type="button"> <i class="fa fa-search"></i> </button>
      <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
    </form>
    <!-- end input: search field -->
    <!-- multiple lang dropdown : find all flags in the image folder -->
    <ul class="header-dropdown-list hidden-xs">
      <li> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img alt="" src="./images/country/<?php echo $_SESSION['languageIcon']; ?>"> <span> <?php echo $_SESSION['languageDescription']; ?> </span> <i class="fa fa-angle-down"></i> </a>
        <ul class="dropdown-menu pull-right">
          <?php for($i=0;$i<$totalLanguage;$i++) {  ?>
          <li <?php if($_SESSION['languageId'] == $languageArray[$i]['languageId']) { ?>class="active"<?php } ?>> <a href="javascript:void(0);" onClick="setLanguage(<?php echo $languageArray[$i]['languageId']; ?>)"><img alt="<?php echo $languageArray[$i]['languageDescription']; ?>" src="./images/country/<?php echo $languageArray[$i]['languageIcon']; ?>"> <?php echo $languageArray[$i]['languageDescription']; ?></a> </li>
          <?php }  ?>
        </ul>
      </li>
    </ul>
    <!-- end multiple lang -->
  </div>
  <!-- end pulled right: nav area -->
</header>
<!-- END HEADER -->
<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">
  <!-- User info -->
  <div class="login-info"> <span>
    <!-- User image size is adjusted inside CSS, it should stay as it -->
    <img src="./v3/system/management/images/<?php echo $_SESSION['staffAvatar']; ?>" alt="me" class="online" /> <a href="javascript:void(0);" id="show-shortcut"> <?php echo $_SESSION['staffName']; ?> <i class="fa fa-angle-down"></i> </a> </span> </div>
  <!-- end user info -->
  <!-- NAVIGATION : This navigation is also responsive

			To make this navigation dynamic please make sure to link the node
			(the reference to the nav > ul) after page load. Or the navigation
			will not initialize.
			-->
  <nav>
    <!-- NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional href="" links. See documentation for details.
				-->
    <ul>
      <li class=""> <a href="ajax/dashboard.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a> </li>
      <?php /*
					<li>
						<a href="ajax/inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span><span class="badge pull-right inbox-badge">14</span></a>
					</li>
						<li>
						<a href="ajax/calendar.html"><i class="fa fa-lg fa-fw fa-calendar"><em>3</em></i> <span class="menu-item-parent">Calendar</span></a>
					</li>
					<li>
						<a href="ajax/gallery.html"><i class="fa fa-lg fa-fw fa-picture-o"></i> <span class="menu-item-parent">Gallery</span></a>
					</li>
					*/ ?>
      <?php
                        if (isset($application)) {
                            $totalApplication = count($application);
                            for ($i = 0; $i < $totalApplication; $i++) {
                                $totalModule = 0;
                                if (isset($application[$i]['module'])) {
                                    $totalModule = count($application[$i]['module']);
                                }
                                if ($totalModule == 0) {
                                    ?>
      <li class=""><a href="javascript:void(0);" onClick="loadBelow('<?php echo intval($application[$i]['applicationId']); ?>', '', '', '', 'application');" title="<?php	if (isset($application[$i]['applicationNative'])) { echo ucwords($application[$i]['applicationNative']);  } ?>"><i class="fa fa-lg fa-fw <?php echo $application[$i]['applicationFontAweSome']; ?>"></i> <span class="menu-item-parent">
        <?php	if (isset($application[$i]['applicationNative'])) { echo ucwords($application[$i]['applicationNative']);  } ?>
        </span></a></li>
      <?php } else { ?>
      <li class="dropdown"><a href="javascript:void(0);" title="<?php echo $application[$i]['applicationNative']; ?>"><i class="fa fa-lg fa-fw <?php echo $application[$i]['applicationFontAweSome']; ?>"></i> <span class="menu-item-parent"><?php echo $application[$i]['applicationNative']; ?></span></a>
        <ul>
          <?php
                                            for ($j = 0; $j < $totalModule; $j++) {
                                                if ($application[$i]['module'][$j]['isSingle'] == 1) {
                                                    ?>
          <li><a href="javascript:void(0);" onClick="loadBelow('<?php echo intval($application[$i]['applicationId']); ?>', '<?php echo intval($application[$i]['module'][$j]['moduleId']); ?>', '', '', 'module');" title="<?php echo ucwords($application[$i]['module'][$j]['moduleNative']); ?>"><i class="fa fa-lg fa-fw  <?php echo $application[$i]['module'][$j]['moduleFontAweSome']; ?>"></i> <?php echo ucwords($application[$i]['module'][$j]['moduleNative']); ?></a></li>
          <?php } else {
															$totalFolder = count($application[$i]['module'][$j]['folder']); ?>
          <li><a href="javascript:void(0);" onClick="loadSidebar('<?php echo intval($application[$i]['applicationId']); ?>', '<?php echo $application[$i]['module'][$j]['moduleId']; ?>');" title="<?php echo ucwords($application[$i]['module'][$j]['moduleNative']); ?>"><i class="fa fa-lg fa-fw <?php echo $application[$i]['module'][$j]['moduleFontAweSome']; ?>"></i> <?php echo ucwords($application[$i]['module'][$j]['moduleNative']); ?></a>
            <?php if ($totalFolder > 0) { ?>
            <ul>
              <?php
                                                            }
                                                            if ($totalFolder > 0) {
                                                                for ($n = 0; $n < $totalFolder; $n++) {
                                                                    $totalLeaf = count($application[$i]['module'][$j]['folder'][$n]['leaf']);
                                                                    ?>
              <li><a href="javascript:void(0);" title="<?php echo ucwords($application[$i]['module'][$j]['folder'][$n]['folderNative']); ?>"><i class="fa fa-lg fa-fw  <?php echo $application[$i]['module'][$j]['folder'][$n]['folderFontAweSome']; ?>"></i> <?php echo ucwords($application[$i]['module'][$j]['folder'][$n]['folderNative']); ?></a>
                <?php if ($totalLeaf > 0) { ?>
                <ul>
                  <?php
                                                                            }
                                                                            if ($totalLeaf > 0) {
                                                                                for ($h = 0; $h < $totalLeaf; $h++) {
                                                                                    ?>
                  <li><a href="javascript:void(0);" onClick="loadLeft('<?php echo intval($application[$i]['module'][$j]['folder'][$n]['leaf'][$h]['leafId']); ?>', '<?php echo md5("chak chak"); ?>');" title="<?php echo ucwords($application[$i]['module'][$j]['folder'][$n]['leaf'][$h]['leafNative']); ?>"><i class="fa fa-lg fa-fw  <?php echo $application[$i]['module'][$j]['folder'][$n]['leaf'][$h]['leafFontAweSome']; ?>"></i> <?php echo ucwords($application[$i]['module'][$j]['folder'][$n]['leaf'][$h]['leafNative']); ?></a></li>
                  <?php
                                                                                }
                                                                            }
                                                                            if ($totalLeaf > 0) {
                                                                                ?>
                </ul>
                <?php } ?>
              </li>
              <?php
                                                                }
                                                            }
                                                            ?>
              <?php if ($totalFolder > 0) { ?>
            </ul>
            <?php } ?>
          </li>
          <?php
                                                }
                                            }
                                            ?>
        </ul>
      </li>
      <?php
                                }
                            }
                        } ?>
    </ul>
  </nav>
  <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span> </aside>
<!-- END NAVIGATION -->
<!-- MAIN PANEL -->
<div id="main" role="main">
  <div id="centerViewport" style="width:100%"> </div>
  <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
<div id="shortcut">
  <ul>
    <li> <a href="#ajax/inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a> </li>
    <li> <a href="#ajax/calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a> </li>
    <li> <a href="#ajax/gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Task</span> </span> </a> </li>
    <li> <a href="#ajax/invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Document<span class="label pull-right bg-color-darken">99</span></span> </span> </a> </li>
    <li> <a href="#ajax/gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a> </li>
    <li> <a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a> </li>
  </ul>
</div>
<!-- END SHORTCUT AREA -->
<!--================================================== -->
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="./library/js/plugin/pace/pace.min.js"></script>-->
<!-- Link to Google CDNs jQuery + jQueryUI; fall back to local -->
		<script src="./library/libs/jquery-2.0.2.min.js"></script>
		<script src="./library/libs/jquery-ui-1.10.3.min.js"></script>
<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
		<script src="./library/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->
<!-- BOOTSTRAP JS -->
<script src="./library/bootstrap/bootstrap.min.js"></script>
<!-- CUSTOM NOTIFICATION -->
<script src="./library/notification/SmartNotification.min.js"></script>
<!-- JARVIS WIDGETS -->
<script src="./library/smartwidgets/jarvis.widget.min.js"></script>
<!-- EASY PIE CHARTS -->
<script src="./library/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<!-- SPARKLINES -->
<script src="./library/plugin/sparkline/jquery.sparkline.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="./library/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="./library/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY SELECT2 INPUT -->
<script src="./library/plugin/select2/select2.min.js"></script>
<!-- JQUERY UI + Bootstrap Slider -->
<script src="./library/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
<!-- browser msie issue fix -->
<script src="./library/plugin/msie-fix/jquery.mb.browser.min.js"></script>
<!-- SmartClick: For mobile devices -->
<script src="./library/plugin/smartclick/smartclick.js"></script>
<script type="text/javascript" src="./library/highchart/js/highcharts.js"></script>
<script src="./library/plugin/summernote/summernote.js"></script>
<script src="./library/plugin/select2/select2.min.js"></script>
<!--[if IE 7]>

		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

		<![endif]-->
<!-- Demo purpose only -->
<!-- MAIN APP JS FILE -->
<script src="./library/app.js"></script>
<script src="./library/global.js"></script>
<script type="text/javascript" src="./library/timeago/jquery.timeago.js"></script>
<script type="text/javascript" src="./library/sha1.js"></script>
<script type="text/javascript" src="./library/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="./library/twitter3/bootstrap-switch-master/dist/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="./library/twitter3/datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="./library/upload/jquery.fineuploader-3.5.0.min.js"></script>
<script>
				
            $(document).ready(function() {
                $("abbr.timeago").timeago();
                $('.submenu').hover(function() {
                    $(this).children('ul').removeClass('submenu-hide').addClass('submenu-show');
                }, function() {
                    $(this).children('ul').removeClass('.submenu-show').addClass('submenu-hide');
                }).find("a:first").append(" &raquo; ");

                $('.submenu-left').hover(function() {
                    $(this).children('ul').removeClass('submenu-hide-left').addClass('submenu-show-left');
                }, function() {
                    $(this).children('ul').removeClass('.submenu-show-left').addClass('submenu-hide-left');
                }).find("a:first").append(" &raquo; ");
            });
<?php /* if ($_SESSION['staffId']) { ?>
  // diffirent timing to prevent ajax request blocking
  var dog = woof('tnUUeNpdqH');
  setInterval(function() {
  $.ajax({
  url		: 	'session.php',
  succes	:	function(shepherd) {
  if (shepherd != dog){
  }
  }
  });
  }, 5000);

  <?php
  // notification for admin only or can implement on uat purpose only
  if ($_SESSION['isAdmin'] == 1) {
  /*  ?>
  setInterval(function() {
  // ajax request check how much isNew from email table.

  $.ajax({
  url		: 'totalTicketNotification.php',
  success	:	function(ciwawa) {
  document.title=ciwawa.title;
  $("#totalTicket")
  .html('').empty()
  .html(ciwawa.totalTicket);

  $("#totalNotification")
  .html('').empty()
  .html(ciwawa.totalNotification);
  }
  });
  }, 25000);
  <?php } */ ?>
<?php /*
  setInterval(function() {
  $.ajax({
  url: 'ticket.php',
  success: function(Terrier) {
  $("#CoreTicket")
  .html('').empty()
  .html(Terrier);
  }
  });
  }, 20000);
  setInterval(function() {
  $.ajax({
  url		: 	'notification.php',
  success	:	function(Greyhound) {
  $("#CoreNotification");
  .html('').empty()
  .html(Greyhound);
  }
  });
  }, 25000);
 */
?>

            function chowsin() {
                window.location.href = 'index.php';
            }
<?php
//}
?>
            $("#loginButton").click(function() {
                var message;
                if ($("#username").val().length === 0 && $("#password").val().length === 0) {
                    message = "Please field the username and password field lor";
                    $('#infoPanel')
                            .html('').empty()
                            .html('<div class=\'alert alert-error\' ><a class="close" data-dismiss=\'alert\'>×</a>' + message + '</div>')
                            .show();
                } else if ($("#username").val().length === 0 && $("#password").val().length > 0) {
                    message = "Please field the username field first la";
                    $('#infoPanel')
                            .html('').empty()
                            .html('<div class=\'alert alert-error\'><a class="close" data-dismiss=\'alert\'>×</a>' + message + '</div>')
                            .show();
                } else if ($("#password").val().length === 0 && $("#username").val().length > 0) {
                    message = "Please field the password field first la";
                    $('#infoPanel')
                            .html('').empty()
                            .html('<div class=\'alert alert-error\'><a class="close" data-dismiss=\'alert\'>×</a>' + message + '</div>')
                            .show();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: './v3/portal/main/controller/portalController.php',
                        data: {
                            username: $("#username").val(),
                            password: $("#password").val(),
                            tokenKey: '<?php echo md5("you have been cheated"); ?>'
                        },
                        beforeSend: function() {
                            $('#infoPanel').show();
                        },
                        success: function(data) {
                            if (data.success === true) {
                                $('#infoPanel')
                                        .html('').empty()
                                        .html('<div class=\'alert alert-info\'><a class="close" data-dismiss=\'alert\'>×</a>Lai  lai.. come in Welcome</div>')
                                        .show();
                                $("#centerViewport")
                                        .html("").empty();
                                $("#menu")
                                        .html("").empty();
                                window.location.href = 'index.php';
                            } else if (data.success === false) {
                                $("#leftViewport").hide();
                                $('#infoPanel')
                                        .html('').empty()
                                        .html('<div class=\'alert alert-error\'><a class="close" data-dismiss=\'alert\'>×</a>' + data.message + '</div>')
                                        .show();
                            }
                        },
                        error: function() {
                            $('#infoPanel')
                                    .html('').empty()
                                    .html('<div class=\'alert alert-error\'><a class="close" data-dismiss=\'alert\'>×</a>Error Could Load The Request Page</div>')
                                    .show();
                        }
                    });
                }
                $("#closeAlertError").click(function() {
                    $("#infoPanel").hide();
                });
            });
            function loadBelow(pageId, moduleId, folderId, leafId, pageType) {
                $("#searchResult")
                        .removeClass().addClass("typeahead typeahead-long dropdown-menu hide");
                var url = './v3/portal/main/controller/portalController.php';
                $("#centerViewport")
                        .html('').empty()
                        .load(url,
                        {
                            method: 'read',
                            type: 'list',
                            detail: 'body',
                            pageId: pageId,
                            moduleId: moduleId,
                            folderId: folderId,
                            leafId: leafId,
                            pageType: pageType
                        },
                function(response, status, xhr) {
                    var data = json_parse(response);
                    if (data.success === false) {
                        $('#infoPanel')
                                .html('').empty();
                        $("#centerViewport")
                                .html('').empty()
                                .html("<div id=infoPanel><div class=\'alert alert-error\'><a class='close' data-dismiss='alert'>×</a><img src=\'./images/icons/smiley-roll-sweat.png\'> " + data.message + "</div></div>");
                    }
                    if (status === "error") {
                        var msg = "Sorry but there was an error: ";
                        $('#infoPanel')
                                .html('').empty()
                                .html('<div class=\'alert alert-error\'><a class="close" data-dismiss=\'alert\'>×</a>aik' + msg + xhr.status + " " + xhr.statusText + '</div>');
                    }
                });
            }
            function loadSidebar(applicationId, moduleId) {
                $("#searchResult")
                        .removeClass().addClass("typeahead typeahead-long dropdown-menu hide");
                $('#infoPanel')
                        .html('').empty()
                        .html('<div class=\'alert alert-info\'><a class="close" data-dismiss=\'alert\'>×</a>Lai  lai.. come in Welcome</div>')
                        .show();
                $("#centerViewport")
                        .html("").empty()
                        .removeAttr("style");
                $.ajax({
                    type: 'POST',
                    url: './v3/portal/main/controller/portalController.php',
                    data: {
                        method: 'read',
                        pageType: 'sidebar',
                        applicationId: applicationId,
                        moduleId: moduleId,
                        securityTocken: '<?php echo md5("You have been cheated"); ?>'
                    },
                    success: function(data) {
                        $("#centerViewport")
                                .html('').empty()
                                .html(data);
                    },
                    error: function() {
                        $('#infoPanel')
                                .html('').empty()
                                .html('<div class=\'alert alert-error\'><a class="close" data-dismiss=\'alert\'>×</a>Error Could Load The Request Page</div>')
                                .show();
                    }

                });
            }
            function loadLeft(leafId, securityToken) {
                $("#searchResult")
                        .removeClass().addClass("typeahead typeahead-long dropdown-menu hide");
                var url = './v3/portal/main/controller/portalController.php';
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
            function loadTicket(leafId, securityToken, ticketId) {
                var url = './v3/portal/main/controller/portalController.php';
                //    $('#infoPanel').html('<div class="progress"><img src="./images/loading.gif" alt="Loading..." /></div>');
                $("#centerViewport")
                        .html("").empty()
                        .load(url,
                        {
                            start: 0,
                            limit: 10,
                            method: 'read',
                            type: 'detail',
                            leafId: leafId,
                            pageType: 'leaf',
                            securityToken: securityToken,
                            ticketId: ticketId
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
                            var data = json_parse(response);
                            if (data.success === false) {
                                $("#centerViewport")
                                        .html('').empty()
                                        .html("<div id=infoPanel><div class=\'alert alert-error\'><a class='close' data-dismiss='alert'>×</a><img src=\'./images/icons/smiley-roll-sweat.png\'> " + data.message + "</div></div>");
                            }
                        }
                    }
                }
                );
            }
            function loadNotification(leafId, securityToken) {
                var url = './v3/portal/main/controller/portalController.php';
                //    $('#infoPanel').html('<div class="progress"><img src="./images/loading.gif" alt="Loading..." /></div>');
                $("#centerViewport")
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
                            var data = json_parse(response);
                            if (data.success === false) {
                                $("#centerViewport")
                                        .html('').empty()
                                        .html("<div id=infoPanel><div class=\'alert alert-error\'><a class='close' data-dismiss='alert'>×</a><img src=\'./images/icons/smiley-roll-sweat.png\'> " + data.message + "</div></div>");
                            }
                        }
                    }
                }
                );
            }
            function newTicket(url, urlList, securityToken, staffId) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'create',
                        output: 'json',
                        staffIdFrom: staffId,
                        staffIdTo: 3,
                        ticketText: $('#ticketText').val(),
                        securityToken: securityToken,
                        leafId: 23
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel')
                                .html('').empty()
                                .html('<div class=\'alert col-lg-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '</div>');
                    },
                    success: function(data) {
                        // successful request; do something with the data
                        if (data.success === true) {
                            showMeModal('ticketModal', 0);
                            showGrid(23, urlList, securityToken, 0, 10);
                        } else if (data.success === false) {
                            $('#infoPanel')
                                    .html('').empty()
                                    .html('<div class=\'alert alert-error col-lg-12\'>' + data.message + '</div>');
                        } else {
                            $('#infoPanel')
                                    .html('').empty()
                                    .html('<div class=\'alert alert-error col-lg-12\'>' + data.message + '</div>');
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel')
                                .html('').empty()
                                .html('<div class=\'alert alert-error col-lg-12\'><img src=\'./images/icons/smiley-roll-sweat.png\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }
                });
            }
            function showGrid(leafId, page, securityToken, offset, limit) {
                $.ajax({
                    type: 'POST',
                    url: page,
                    data: {
                        offset: offset,
                        limit: limit,
                        method: 'read',
                        type: 'list',
                        detail: 'body',
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel')
                                .html('').empty()
                                .html('<div class=\'alert col-lg-12\'><img src=\'./images/icons/smiley-roll.png\'>' + decodeURIComponent(t['loadingTextLabel']) + '....</div>');
                    },
                    success: function(data) {
                        if (data.success === false) {
                            $('#centerViewport')
                                    .html('').empty()
                                    .html('<div class=\'alert alert-error col-lg-12\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');
                        } else {
                            $('#centerViewport')
                                    .html('').empty()
                                    .append(data);
                            $('#infoPanel')
                                    .html('').empty()
                                    .html('We have received your message. Please hold back until our customer support respond back.');
                        }
                    },
                    error: function() {
                        // failed request; give feedback to user
                        $('#infoPanel')
                                .html('').empty()
                                .html('<div class=\'alert alert-error col-lg-12\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                    }
                });
            }
            function spotlight(securityToken) {
                var spotlight = $("#spotlightText").val();
                if (spotlight.length === 0) {
                    $("#searchResult")
                            .removeClass().addClass("typeahead typeahead-long dropdown-menu hide");
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'spotlight.php',
                        data: {
                            method: 'read',
                            type: 'spotlight',
                            securityToken: securityToken,
                            spotlightString: $("#spotlightText").val()
                        },
                        beforeSend: function() {
                        },
                        success: function(data) {
                            if(data.spotlight){
                                var spotlight = data.spotlight;
                            }
                            $("#searchResult")
                                    .removeClass().addClass("typeahead typeahead-long dropdown-menu hide scroll-menu")
                                    .html('').empty();
                            if (data.success === false) {
                                $('#centerViewport')
                                        .html('').empty()
                                        .html('<div class=\'alert alert-error col-lg-12\'><img src=\'./images/icons/smiley-roll.png\'>' + data.message + '</div>');

                            } else {
                                if (data.total === 0) {
                                    $("#searchResult")
                                            .removeClass().addClass("typeahead typeahead-long dropdown-menu hide scroll-menu")
                                            .html('').empty();
                                } else {
                                    $("#searchResult")
                                            .removeClass().addClass("typeahead typeahead-long dropdown-menu scroll-menu")
                                            .html('').empty()
                                            .append(spotlight)
                                            .show();
                                }
                            }
                        },
                        error: function() {
                            $('#infoPanel')
                                    .html('').empty()
                                    .html('<div class=\'alert alert-error col-lg-12\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    });
                }
            }
			function setLanguage(languageId,securityToken) {
                    $.ajax({
                        type: 'POST',
                        url: 'setLanguage.php',
                        data: {
                            method: 'update',
                            securityToken: securityToken,
                            languageId: languageId
                        },
                        beforeSend: function() {
                        },
                        success: function(data) {
                            if(data.success === true){
                                window.location.href = "main.php";
                            }
                        },
                        error: function() {
                            $('#infoPanel')
                                    .html('').empty()
                                    .html('<div class=\'alert alert-error col-lg-12\'>' + decodeURIComponent(t['loadingErrorTextLabel']) + '</div>');
                        }
                    });
                }
                </script>
</body>
</html>
