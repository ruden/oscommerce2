<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class sm_twitter {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $icon = 'twitter.svg';
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_SOCIAL_MEDIA_TWITTER_TITLE;
      $this->description = MODULE_SOCIAL_MEDIA_TWITTER_DESCRIPTION;

      if ($this->check()) {
        $this->sort_order = MODULE_SOCIAL_MEDIA_TWITTER_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_MEDIA_TWITTER_STATUS == 'True');
      }
    }

    public function getOutput() {
      return '<a href="http://twitter.com/' . tep_output_string_protected(MODULE_SOCIAL_MEDIA_TWITTER_PROFILE_ID) . '"><img src="images/icons/social_media/' . $this->icon . '" style="width: 2rem; height: 2rem;" title="' . tep_output_string_protected($this->title) . '"></a>';
    }

    public function isEnabled() {
      if (!empty(MODULE_SOCIAL_MEDIA_TWITTER_PROFILE_ID)) {
        return $this->enabled;
      }

      return false;
    }

    public function check() {
      return defined('MODULE_SOCIAL_MEDIA_TWITTER_STATUS');
    }

    public function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_SOCIAL_MEDIA_TWITTER_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Profile ID', 'MODULE_SOCIAL_MEDIA_TWITTER_PROFILE_ID', '', 'Profile ID.', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_MEDIA_TWITTER_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    public function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      return array('MODULE_SOCIAL_MEDIA_TWITTER_STATUS', 'MODULE_SOCIAL_MEDIA_TWITTER_PROFILE_ID', 'MODULE_SOCIAL_MEDIA_TWITTER_SORT_ORDER');
    }
  }
