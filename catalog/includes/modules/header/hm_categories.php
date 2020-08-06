<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class hm_categories {
  public $code;
  public $group = 'header';
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_HEADER_CATEGORIES_TITLE;
    $this->description = MODULE_HEADER_CATEGORIES_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_HEADER_CATEGORIES_SORT_ORDER;
      $this->enabled = (MODULE_HEADER_CATEGORIES_STATUS == 'True');

      $this->group = 'header_menu';
    }
  }

  public function getData() {
    $categories_array = $this->categoryTree(0);

    ob_start();
    include 'includes/modules/header/templates/categories.php';

    return ob_get_clean();
  }

  public function execute() {
    global $SID, $oscTemplate;

    if ((USE_CACHE == 'true') && empty($SID)) {
      $output = $this->cache();
    } else {
      $output = $this->getData();
    }

    $oscTemplate->addBlock($output, $this->group);
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_HEADER_CATEGORIES_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_HEADER_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_HEADER_CATEGORIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_HEADER_CATEGORIES_STATUS', 'MODULE_HEADER_CATEGORIES_SORT_ORDER');
  }

  public function categoryTree($parent_id) {
    global $languages_id;

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from categories c, categories_description cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");

    $category_tree_array = array();

    while ($categories = tep_db_fetch_array($categories_query)) {
      $category_tree_array[$categories['categories_id']]['categories_name'] = $categories['categories_name'];
      $category_children_array = $this->categoryTree($categories['categories_id']);

      if (count($category_children_array) > 0) {
        $category_tree_array[$categories['categories_id']]['parent'] = $category_children_array;
      }
    }

    return $category_tree_array;
  }

  function cache($auto_expire = false, $refresh = false) {
    global $cPath, $language;

    $cache_output = '';

    if (($refresh == true) || !read_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath, $auto_expire)) {
      $cache_output = $this->getData();

      write_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath);
    }

    return $cache_output;
  }
}
