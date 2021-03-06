<?php
/**
 * @file view/theme/frio/php/modes/default.php
 * @brief The default site template
 */
?>
<!DOCTYPE html >
<?php

use Friendica\Core\Config;
use Friendica\Core\PConfig;
use Friendica\Core\System;
use Friendica\Model\Profile;

require_once 'view/theme/frio/php/frio_boot.php';

//	$minimal = is_modal();
if (!isset($minimal)) {
	$minimal = false;
}
?>
<html>
	<head>
		<title><?php if (x($page, 'title')) echo $page['title'] ?></title>
		<meta request="<?php echo htmlspecialchars($_REQUEST['pagename']) ?>">
		<script  type="text/javascript">var baseurl = "<?php echo System::baseUrl(); ?>";</script>
		<script type="text/javascript">var frio = "<?php echo 'view/theme/frio'; ?>";</script>
<?php
	$baseurl = System::baseUrl();
	$frio = "view/theme/frio";
	// Because we use minimal for modals the header and the included js stuff should be only loaded
	// if the page is an standard page (so we don't have it twice for modals)
	//
	/// @todo Think about to move js stuff in the footer
	if (!$minimal && x($page, 'htmlhead')) {
		echo $page['htmlhead'];
	}
	// Add the theme color meta
	// It makes mobile Chrome UI match Frio's top bar color.
	$uid = $a->profile_uid;
	if (is_null($uid)) {
		$uid = Profile::getThemeUid();
	}
	$schema = PConfig::get($uid, 'frio', 'schema');
	if (($schema) && ($schema != '---')) {
		if (file_exists('view/theme/frio/schema/' . $schema . '.php')) {
			$schemefile = 'view/theme/frio/schema/' . $schema . '.php';
			require_once $schemefile;
		}
	} else {
		$nav_bg = PConfig::get($uid, 'frio', 'nav_bg');
	}
	if (!$nav_bg) {
		$nav_bg = "#708fa0";
	}
	echo '
		<meta name="theme-color" content="' . $nav_bg . '" />';

	$is_singleuser = Config::get('system','singleuser');
	$is_singleuser_class = $is_singleuser ? "is-singleuser" : "is-not-singleuser";
?>
	</head>
	<body id="top" class="mod-<?php echo $a->module." ".$is_singleuser_class;?>">
		<a href="#content" class="sr-only sr-only-focusable">Skip to main content</a>
<?php
	if (x($page, 'nav') && !$minimal) {
		echo str_replace(
			"~config.sitename~",
			Config::get('config', 'sitename'),
			str_replace(
				"~system.banner~",
				Config::get('system', 'banner'),
				$page['nav']
			)
		);
	};

	// special minimal style for modal dialogs
	if ($minimal) {
?>
		<section class="minimal" style="margin:0px!important; padding:0px!important; float:none!important;display:block!important;">
			<?php if (x($page, 'content')) echo $page['content']; ?>
			<div id="page-footer"></div>
		</section>
<?php
	} else {
		// the style for all other pages
?>
		<main>
			<div class="container">
				<div class="row">
<?php
					if ((!x($_REQUEST, 'pagename') || $_REQUEST['pagename'] != "lostpass") && ($_SERVER['REQUEST_URI'] != "/")) {
						echo '
					<aside class="col-lg-3 col-md-3 offcanvas-sm offcanvas-xs">';

						if (x($page, 'aside')) {
							echo $page['aside'];
						}

						if (x($page, 'right_aside')) {
							echo $page['right_aside'];
						}

						echo '
					</aside>

					<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" id="content">
						<section class="sectiontop ';
						echo $a->argv[0];
						echo '-content-wrapper">';
						if (x($page, 'content')) {
							echo $page['content'];
						}
						echo '
								<div id="pause"></div> <!-- The pause/resume Ajax indicator -->
						</section>
					</div>
						';
					} else {
						echo '
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="content" style="margin-top:50px;">';
						if (x($page, 'content')) {
							echo $page['content'];
						}
						echo '
					</div>
					';
					}
?>
				</div><!--row-->
			</div><!-- container -->

			<div id="back-to-top" title="back to top">&#8679;</div>
		</main>

		<footer>
			<?php if (x($page, 'footer')) echo $page['footer']; ?>
			<!-- Modal  -->
			<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-full-screen">
					<div class="modal-content">
						<div id="modal-header" class="modal-header">
							<button id="modal-cloase" type="button" class="close" data-dismiss="modal" aria-hidden="true">
								&times;
							</button>
							<h4 id="modal-title" class="modal-title"></h4>
						</div>
						<div id="modal-body" class="modal-body">
							<!-- /# content goes here -->
						</div>
					</div>
				</div>
			</div>

			<!-- Dummy div to append other div's when needed (e.g. used for js function editpost() -->
			<div id="cache-container"></div>

		</footer>
<?php } ?> <!-- End of condition if $minimal else the rest -->
	</body>
