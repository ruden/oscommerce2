<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="robots" content="noindex,nofollow">
<title><?php echo TITLE; ?></title>
<base href="<?php echo ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN; ?>" />
<link rel="shortcut icon" href="<?php echo tep_catalog_href_link('favicon.ico'); ?>" />
<!--[if IE]><script src="<?php echo tep_catalog_href_link('ext/flot/excanvas.min.js'); ?>"></script><![endif]-->
<link rel="stylesheet" href="<?php echo tep_catalog_href_link('ext/jquery/ui/redmond/jquery-ui.min.css'); ?>">
<script src="<?php echo tep_catalog_href_link('ext/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo tep_catalog_href_link('ext/jquery/ui/jquery-ui.min.js'); ?>"></script>

<?php
  if (tep_not_null(JQUERY_DATEPICKER_I18N_CODE)) {
?>
<script src="<?php echo tep_catalog_href_link('ext/jquery/ui/i18n/jquery.ui.datepicker-' . JQUERY_DATEPICKER_I18N_CODE . '.js'); ?>"></script>
<script>
$.datepicker.setDefaults($.datepicker.regional['<?php echo JQUERY_DATEPICKER_I18N_CODE; ?>']);
</script>
<?php
  }
?>

<script src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.min.js'); ?>"></script>
<script src="<?php echo tep_catalog_href_link('ext/flot/jquery.flot.time.min.js'); ?>"></script>
<link rel="stylesheet" href="includes/stylesheet.css">
<script src="includes/general.js"></script>
</head>
<body>

<?php require('includes/header.php'); ?>

<?php
  if (tep_session_is_registered('admin')) {
    include('includes/column_left.php');
  } else {
?>

<style>
#contentText {
  margin-left: 0;
}
</style>

<?php
  }
?>

<div id="contentText">
