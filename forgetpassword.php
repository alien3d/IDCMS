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
$newFakeDocumentRoot = str_replace("forgetpassword.php/", "", str_replace("//", "/", $fakeDocumentRoot)); // start
require_once($newFakeDocumentRoot . "v3/portal/main/controller/portalController.php");
require_once($newFakeDocumentRoot . "library/class/classShared.php");
$translator = new \Core\shared\SharedClass();
$translator->setCurrentTable('notification', 'ticket', 'ticketThread');
$translator->execute();
$t = $translator->getDefaultTranslation(); // short because code too long
$portal = new \Core\Portal\Controller\PortalControllerClass();
$portal->execute();
$application = $portal->getApplicationArray();
$applicationId = $_GET['applicationId'];
$portal->setApplicationLog($applicationId);
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
<title> IDCMS Application Suite</title>
		<meta name="description" content="Accounting,Human Resources,Property Solution">
		<meta name="author" content="IDCMS">

		<!-- Use the correct meta names below for your web application
			 Ref: http://davidbcalhoun.com/2010/viewport-metatag 
			 
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">-->
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="./library/css/bootstrap.min.css">	
		<link rel="stylesheet" type="text/css" media="screen" href="./library/css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
		<link rel="stylesheet" type="text/css" media="screen" href="./library/css/smartadmin-production.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./library/css/smartadmin-skins.css">	
		<!-- FAVICONS -->
		<link rel="shortcut icon" href="./library/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="./library/img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<style>
			@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 300;
  src: local('Open Sans Light'), local('OpenSans-Light'), url('./library/fonts/open_sans_normal_300.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 400;
  src: local('Open Sans'), local('OpenSans'), url('./library/fonts/open_sans_normal_400.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: 700;
  src: local('Open Sans Bold'), local('OpenSans-Bold'), url('./library/fonts/open_sans_normal_700.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: italic;
  font-weight: 400;
  src: local('Open Sans Italic'), local('OpenSans-Italic'), url('./library/fonts/open_sans_italic_400.woff') format('woff');
}
@font-face {
  font-family: 'Open Sans';
  font-style: italic;
  font-weight: 700;
  src: local('Open Sans Bold Italic'), local('OpenSans-BoldItalic'), url('./library/fonts/open_sans_italic_700.woff') format('woff');
  }
		</style>

	</head>
	<body id="login" class="animated fadeInDown">
		<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
		<header id="header">
			<!--<span id="logo"></span>-->

			<div id="logo-group">
				<span id="logo"> <img src="img/logo.png" alt="SmartAdmin"> </span>

				<!-- END AJAX-DROPDOWN -->
			</div>

			<span id="login-header-space"> <span class="hidden-mobile">Need an account?</span> <a href="register.html" class="btn btn-danger">Creat account</a> </span>

		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
						<h1 class="txt-color-red login-header-big">SmartAdmin</h1>
						<div class="hero">

							<div class="pull-left login-desc-box-l">
								<h4 class="paragraph-header">It's Okay to be Smart. Experience the simplicity of SmartAdmin, everywhere you go!</h4>
								<div class="login-app-icons">
									<a href="javascript:void(0);" class="btn btn-danger btn-sm">Frontend Template</a>
									<a href="javascript:void(0);" class="btn btn-danger btn-sm">Find out more</a>
								</div>
							</div>
							
							<img src="img/demo/iphoneview.png" class="pull-right display-image" alt="" style="width:210px">

						</div>

						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<h5 class="about-heading">About SmartAdmin - Are you up to date?</h5>
								<p>
									Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.
								</p>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<h5 class="about-heading">Not just your average template!</h5>
								<p>
									Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi voluptatem accusantium!
								</p>
							</div>
						</div>

					</div>
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
						<div class="well no-padding">
							<form action="login.html" id="login-form" class="smart-form client-form">
								<header>
									<?php echo ucfirst($t['resetPasswordTextLabel']); ?>
								</header>

								<fieldset>
									
									<section>
										<label class="label"><?php echo ucfirst($t['enterEmailAddressTextLabel']); ?></label>
										<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="email" name="email">
											<b class="tooltip tooltip-top-right"><i class="fa fa-envelope txt-color-teal"></i><?php echo ucfirst($t['emailTextLabel']); ?></b></label>
									</section>
									<section>
										<span class="timeline-seperator text-center text-primary"> <span class="font-sm"><?php echo ucfirst($t['orTextLabel']); ?></span></span>
									</section>
									<section>
										<label class="label"><?php echo ucfirst($t['enterUsernameTextLabel']); ?></label>
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="username">
											<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i><?php echo ucfirst($t['enterUsernameTextLabel']); ?></b> </label>
										<div class="note">
											<a href="login.html">I remembered my password!</a>
										</div>
									</section>

								</fieldset>
								<footer>
									<button type="submit" class="btn btn-primary">
										<i class="fa fa-refresh"></i> <?php echo ucfirst($t['submitButtonLabel']); ?>
									</button>
								</footer>
							</form>

						</div>
						
					</div>
				</div>
			</div>

		</div>

	
		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="./library/js/plugin/pace/pace.min.js"></script>-->

		<!-- Link to Google CDNs jQuery + jQueryUI; fall back to local -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="./library/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="./library/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
		
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

		<!-- MAIN APP JS FILE -->
		<script type="text/javascript">
$("#resetPasswordButton").click(function () {
	window.location.href = 'resend_password.php';
});
$("#loginButton").click(function () {
    var message;
    if ($("#username").val().length === 0 && $("#password").val().length === 0) {
        message = "Please fill the username and password field lor";
        $('#infoPanel')
			.html('').empty()
			.html('<div class=\'alert alert-danger fade in\' ><a class="close" data-dismiss=\'alert\'>×</a>' + message + '</div>')
			.show();
    } else if ($("#username").val().length === 0 && $("#password").val().length > 0) {
        message = "Please fill the username Field First ";
        $('#infoPanel')
			.html('').empty()
			.html('<div class=\'alert alert-danger fade in\'><a class="close" data-dismiss=\'alert\'>×</a>' + message + '</div>')
			.show();
    } else if ($("#password").val().length === 0 && $("#username").val().length > 0) {
        message = "Please fill the password field first la";
        $('#infoPanel')
			.html('').empty()
			.html('<div class=\'alert alert-danger fade in\'><a class="close" data-dismiss=\'alert\'>×</a>' + message + '</div>')
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
            beforeSend: function () {
                // $('#infoPanel').html('<div class=\"progress\">  <div class=\"bar\" style=\"width: 100%;\">Wait Ya</div></div>');
                $('#infoPanel').show();
            },
            success: function (data) {
                // $('#infoPanel').html('<div class=\'alert alert-info\'>Loading Complete</div>');
                if (data.success === true) {
					window.location.href = 'main.php';				
                } else if (data.success === false) {
                    $('#infoPanel')
						.html('').empty()
						.html('<div class=\'alert alert-danger fade in\'><a class="close" data-dismiss=\'alert\'>×</a>' + data.message + '</div>')
						.show();
                }
            },
            error: function () {
                // failed request; give feedback to user
                $('#infoPanel')
					.html('').empty()
					.html('<div class=\'alert alert-danger fade in\'><a class="close" data-dismiss=\'alert\'>×</a>Error Could Load The Request Page</div>')
					.show();
            }
        });
    }
    $("#closeAlertError").click(function () {
        $("#infoPanel").hide();
    });
});
</script>
	</body>
</html>