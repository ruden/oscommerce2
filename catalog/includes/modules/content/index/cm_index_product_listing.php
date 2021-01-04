<?php
/*
$Id$

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2020 osCommerce

Released under the GNU General Public License
*/

class cm_index_product_listing {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_PRODUCT_LISTING_TITLE;
    $this->description = MODULE_CONTENT_INDEX_PRODUCT_LISTING_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_INDEX_PRODUCT_LISTING_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_PRODUCT_LISTING_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $languages_id, $current_category_id, $currencies, $PHP_SELF;

    if (!isset($listing_sql)) {
      $where_str = '';
      if (tep_has_category_subcategories($current_category_id) === true) {
        $subcategories_array = array();
        tep_get_subcategories($subcategories_array, $current_category_id);

        for ($i = 0, $n = sizeof($subcategories_array); $i < $n; $i++) {
          $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
        }
      }

      $listing_sql = "select p.*, pd.*, m.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price from products_description pd, products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id left join specials s on p.products_id = s.products_id, products_to_categories p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and (p2c.categories_id = '" . (int)$current_category_id . "'$where_str)";
    }

    $sort_blocks = $oscTemplate->getBlocks('sort_by');

    $listing_split = new splitPageResults($listing_sql . $oscTemplate->_data[$this->group]['order'], MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

    ob_start();

    if ($listing_split->number_of_rows > MAX_DISPLAY_PAGE_LINKS) {
      echo $sort_blocks;
    }

    include('includes/modules/product_listing.php');

    $oscTemplate->addContent(ob_get_clean(), $this->group);
  }

  public function isEnabled() {
    global $cPath_array;

    if (isset($cPath_array)) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_CONTENT_INDEX_PRODUCT_LISTING_STATUS');
  }

  public function install() {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_CONTENT_INDEX_PRODUCT_LISTING_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_PRODUCT_LISTING_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_PRODUCT_LISTING_STATUS', 'MODULE_CONTENT_INDEX_PRODUCT_LISTING_SORT_ORDER');
  }
}
