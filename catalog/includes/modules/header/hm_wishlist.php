<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class hm_wishlist {
    public $code;
    public $group = 'header';
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_HEADER_WISHLIST_TITLE;
      $this->description = MODULE_HEADER_WISHLIST_DESCRIPTION;

      if ($this->check()) {
        $this->sort_order = MODULE_HEADER_WISHLIST_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_WISHLIST_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $wishlist;

      $wishlist_count_list = $wishlist->count_list();

      ob_start();
      include('includes/modules/' . $this->group . '/templates/wishlist.php');

      $oscTemplate->addBlock(ob_get_clean(), $this->group);
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_HEADER_WISHLIST_STATUS');
    }

    public function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_HEADER_WISHLIST_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_WISHLIST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    public function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      return array('MODULE_HEADER_WISHLIST_STATUS', 'MODULE_HEADER_WISHLIST_SORT_ORDER');
    }
  }