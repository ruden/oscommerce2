<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  tep_db_query('delete from oscom_app_paypal_log');

  $OSCOM_PayPal->addAlert('Log entries have been successfully deleted.', 'success');

  tep_redirect(tep_href_link('paypal.php', 'action=log'));
?>
