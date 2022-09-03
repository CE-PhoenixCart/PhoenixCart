<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  function tep_get_version() {
    trigger_error('The tep_get_version function has been deprecated.', E_USER_DEPRECATED);
    return Versions::get('Phoenix');
  }

  function tep_redirect($url) {
    trigger_error('The tep_redirect function has been deprecated.', E_USER_DEPRECATED);
    Href::redirect($url);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    trigger_error('The tep_output_string function has been deprecated.', E_USER_DEPRECATED);
    if ($protected) {
      trigger_error('Calling the tep_output_string function with $protected true has been deprecated.', E_USER_DEPRECATED);
      return htmlspecialchars($string);
    }

    return Text::output($string, $translate);
  }

  function tep_sanitize_string($string) {
    trigger_error('The tep_sanitize_string function has been deprecated.', E_USER_DEPRECATED);
    return Text::sanitize($string);
  }

  function tep_get_products_name($product_id, $language_id = null) {
    trigger_error('The tep_get_products_name function has been deprecated.', E_USER_DEPRECATED);
    return Product::fetch_name($product_id, $language_id);
  }

  function tep_get_products_special_price($product_id) {
    trigger_error('The tep_get_products_special_price function has been deprecated.', E_USER_DEPRECATED);
    return product_by_id::build(Product::build_prid($product_id))->get('specials_new_products_price');
  }

  function tep_get_products_stock($products_id) {
    trigger_error('The tep_get_products_stock function has been deprecated.', E_USER_DEPRECATED);

    return product_by_id::build(Product::build_prid($products_id))->get('in_stock');
  }

  function tep_check_stock($products_id, $products_quantity) {
    trigger_error('The tep_check_stock function has been deprecated.', E_USER_DEPRECATED);
    return product_by_id::build(Product::build_prid($products_id))->lacks_stock($products_quantity);
  }

////
// Return all HTTP GET variables, except those passed as a parameter
  function tep_get_all_get_params($excludes = []) {
    trigger_error('The tep_get_all_get_params function has been deprecated.', E_USER_DEPRECATED);
    $excludes = array_merge($excludes, [ session_name(), 'error', 'x', 'y' ]);

    $get_url = '';
    foreach ($_GET ?? [] as $key => $value) {
      if ( is_string($value) && (strlen($value) > 0) && !in_array($key, $excludes) ) {
        $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
      }
    }

    return $get_url;
  }

  function tep_get_countries($countries_id = '', $with_iso_codes = false) {
    trigger_error('The tep_get_countries function has been deprecated.', E_USER_DEPRECATED);
    if (Text::is_empty($countries_id)) {
      return Country::fetch_all();
    }

    if ($with_iso_codes) {
      return Country::fetch($countries_id);
    }

    return ['countries_name' => Country::fetch_name($countries_id)];
  }

  function tep_get_path($current_category_id = '') {
    trigger_error('The tep_get_path function has been deprecated.', E_USER_DEPRECATED);

    if (empty($GLOBALS['cPath_array'])) {
      $cPath_new = $current_category_id;
    } elseif ('' === $current_category_id) {
      $cPath_new = implode('_', $GLOBALS['cPath_array']);
    } else {
      $cPath_new = Guarantor::ensure_global('category_tree')->find_path($current_category_id);
    }

    return 'cPath=' . $cPath_new;
  }

  function tep_get_country_name($country_id) {
    trigger_error('The tep_get_country_name function has been deprecated.', E_USER_DEPRECATED);
    return Country::fetch_name($country_id);
  }

  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    trigger_error('The tep_get_zone_name function has been deprecated.', E_USER_DEPRECATED);
   return Zone::fetch_name($zone_id, $country_id, $default_zone);
  }

  function tep_get_zone_code($country_id, $zone_id, $default_zone) {
    trigger_error('The tep_get_zone_code function has been deprecated.', E_USER_DEPRECATED);
    return Zone::fetch_code($zone_id, $country_id, $default_zone);
  }

  function tep_round($number, $precision) {
    trigger_error('The tep_round function has been deprecated.', E_USER_DEPRECATED);
    return currencies::round($number, $precision);
  }

  function tep_get_tax_rate($class_id, $country_id = null, $zone_id = null) {
    trigger_error('The tep_get_tax_rate function has been deprecated.', E_USER_DEPRECATED);
    return Tax::get_rate($class_id, $country_id, $zone_id);
  }

  function tep_get_tax_description($class_id, $country_id, $zone_id) {
    trigger_error('The tep_get_tax_description function has been deprecated.', E_USER_DEPRECATED);
    return Tax::get_description($class_id, $country_id, $zone_id);
  }

  function tep_add_tax($price, $tax) {
    trigger_error('The tep_add_tax function has been deprecated.', E_USER_DEPRECATED);
    return Tax::price($price, $tax);
  }

  function tep_calculate_tax($price, $tax) {
    trigger_error('The tep_calculate_tax function has been deprecated.', E_USER_DEPRECATED);
    return Tax::calculate($price, $tax);
  }

  function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
    trigger_error('The tep_get_categories function has been deprecated.', E_USER_DEPRECATED);
    return Guarantor::ensure_global('category_tree')->get_selections($categories_array, $parent_id, $indent);
  }

  function tep_get_manufacturers($manufacturers = []) {
    trigger_error('The tep_get_manufacturers function has been deprecated.', E_USER_DEPRECATED);
    return array_merge($manufacturers, $GLOBALS['db']->fetch_all("SELECT manufacturers_id AS id, manufacturers_name AS text FROM manufacturers ORDER BY manufacturers_name"));
  }

  function tep_get_subcategories(&$subcategories, $parent_id = 0) {
    trigger_error('The tep_get_subcategories function has been deprecated.', E_USER_DEPRECATED);
    $subcategories = array_merge($subcategories, Guarantor::ensure_global('category_tree')->get_descendants($parent_id));
  }

  function tep_date_long($raw_date) {
    trigger_error('The tep_date_long function has been deprecated.', E_USER_DEPRECATED);
    return Date::expound($raw_date);
  }

  function tep_date_short($raw_date) {
    trigger_error('The tep_date_short function has been deprecated.', E_USER_DEPRECATED);
    return Date::abridge($raw_date);
  }

  function tep_parse_search_string($search_str = '', &$objects = []) {
    trigger_error('The tep_parse_search_string function has been deprecated.', E_USER_DEPRECATED);
    $search = new Search($search_str);
    $objects = $search->ensure_operator_separated();
    return Search::is_balanced($objects);
  }

////
// Return table heading with sorting capabilities
  function tep_create_sort_heading($sortby, $colnum, $heading) {
    trigger_error('The tep_create_sort_heading function has been deprecated.', E_USER_DEPRECATED);
    return splitPageResult::create_sort_heading($sortby, $colnum, $heading);
  }

  function tep_get_parent_categories(&$categories, $categories_id) {
    trigger_error('The tep_get_parent_categories function has been deprecated.', E_USER_DEPRECATED);
    $parent_categories_query = $GLOBALS['db']->query("SELECT parent_id FROM categories WHERE categories_id = " . (int)$categories_id);
    while ($parent_categories = $parent_categories_query->fetch_assoc()) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[count($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

  function tep_get_product_path($products_id) {
    trigger_error('The tep_get_product_path function has been deprecated.', E_USER_DEPRECATED);
    $cPath = '';

    $category_query = $GLOBALS['db']->query("SELECT p2c.categories_id FROM products p, products_to_categories p2c WHERE p.products_id = " . (int)$products_id . " AND p.products_status = 1 AND p.products_id = p2c.products_id LIMIT 1");
    if (mysqli_num_rows($category_query)) {
      $category = $category_query->fetch_assoc();

      $categories = [];
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (!Text::is_empty($cPath)) {
        $cPath .= '_';
      }
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

  function tep_get_uprid($prid, $params) {
    trigger_error('The tep_get_uprid function has been deprecated.', E_USER_DEPRECATED);
    return Product::build_uprid($prid, $params);
  }

  function tep_get_prid($uprid) {
    trigger_error('The tep_get_prid function has been deprecated.', E_USER_DEPRECATED);
    return Product::build_prid($uprid);
  }

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    trigger_error('The tep_mail function has been deprecated.', E_USER_DEPRECATED);
    return Notifications::mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
  }

  function tep_notify($trigger, $subject) {
    trigger_error('The tep_notify function has been deprecated.', E_USER_DEPRECATED);
    return Notifications::notify($trigger, $subject);
  }

  function tep_has_product_attributes($products_id) {
    trigger_error('The tep_has_product_attributes function has been deprecated.', E_USER_DEPRECATED);
    return product_by_id::build($products_id)->get('has_attributes') === '1';
  }

  function tep_count_modules($modules = '') {
    trigger_error('The tep_count_modules function has been deprecated.', E_USER_DEPRECATED);
    if (empty($modules)) {
      return 0;
    }

    $count = 0;
    foreach (explode(';', $modules) as $module) {
      $class = pathinfo($module, PATHINFO_FILENAME);

      if (isset($GLOBALS[$class]) && $GLOBALS[$class] instanceof $class && $GLOBALS[$class]->enabled) {
        $count++;
      }
    }

    return $count;
  }

  function tep_count_payment_modules() {
    trigger_error('The tep_count_payment_modules function has been deprecated.', E_USER_DEPRECATED);
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
  }

  function tep_count_shipping_modules() {
    trigger_error('The tep_count_shipping_modules function has been deprecated.', E_USER_DEPRECATED);
    return $GLOBALS['shipping_modules']->count();
  }

  function tep_create_random_value($length, $type = 'mixed') {
    trigger_error('The tep_create_random_value function has been deprecated.', E_USER_DEPRECATED);
    if ('chars' === $type) {
      trigger_error('Calling the tep_create_random_value function with $type chars has been deprecated.', E_USER_DEPRECATED);
      $type = 'letters';
    }

    return Password::create_random($length, $type);
  }

  function tep_array_to_string($array, $excludes = [], $equals = '=', $separator = '&') {
    trigger_error('The tep_array_to_string function has been deprecated.', E_USER_DEPRECATED);
    $get_string = '';
    foreach (array_diff($array, array_unique(array_merge($excludes, ['x', 'y']))) as $key => $value) {
      $get_string .= "$key$equals$value$separator";
    }

    return Text::rtrim_once($get_string, $separator);
  }

  function tep_not_null($value) {
    trigger_error('The tep_not_null function has been deprecated.', E_USER_DEPRECATED);
    if (is_array($value)) {
      return count($value) > 0;
    }

    if (is_null($value)) {
      return false;
    }

    return !Text::is_empty("$value");
  }

////
// Output the tax percentage with optional padded decimals
  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    trigger_error('The tep_display_tax_value function has been deprecated.', E_USER_DEPRECATED);
    return Tax::format($value, $padding);
  }

  function tep_parse_category_path($cPath) {
    trigger_error('The tep_parse_category_path function has been deprecated.', E_USER_DEPRECATED);
    return array_unique(array_map('intval', explode('_', $cPath)), SORT_NUMERIC);
  }

  function tep_rand($min = null, $max = null) {
    trigger_error('The tep_rand function has been deprecated.', E_USER_DEPRECATED);
    if (isset($min, $max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
    trigger_error('The tep_setcookie function has been deprecated.', E_USER_DEPRECATED);
    setcookie($name, $value, $expire, $path, (Text::is_empty($domain) ? '' : $domain), $secure);
  }

  function tep_validate_ip_address($ip_address) {
    trigger_error('The tep_validate_ip_address function has been deprecated.', E_USER_DEPRECATED);
    return filter_var($ip_address, FILTER_VALIDATE_IP, ['flags' => FILTER_FLAG_IPV4]);
  }

  function tep_get_ip_address() {
    trigger_error('The tep_get_ip_address function has been deprecated.', E_USER_DEPRECATED);
    return Request::get_ip();
  }

  function tep_count_customer_orders($id = '', $check_session = true) {
    trigger_error('The tep_count_customer_orders function has been deprecated.', E_USER_DEPRECATED);
    if (!is_numeric($id)) {
      $id = $_SESSION['customer_id'] ?? 0;
    }

    if (isset($GLOBALS['customer']) && ($GLOBALS['customer'] instanceof customer) && ($GLOBALS['customer']->get_id() == $id)) {
      return $GLOBALS['customer']->count_orders();
    }

    if ($check_session && ($id !== ($_SESSION['customer_id'] ?? null)) ) {
      return 0;
    }

    return (new customer($id))->count_orders();
  }

  function tep_delete_order($order_id) {
    trigger_error('The tep_delete_order function has been deprecated.', E_USER_DEPRECATED);
    order::remove($order_id, false);
  }

  function tep_validate_form_action_is($action = 'process', $level = 1) {
    trigger_error('The tep_validate_form_action_is function has been deprecated.', E_USER_DEPRECATED);
    return Form::validate_action_is($action);
  }

  /**
   * For use by injectFormVerify hooks and Apps that need to block form processing.
   */
  function tep_block_form_processing() {
    trigger_error('The tep_block_form_processing function has been deprecated.', E_USER_DEPRECATED);
    Form::block_processing();
  }

  function tep_form_processing_is_valid() {
    trigger_error('The tep_form_processing_is_valid function has been deprecated.', E_USER_DEPRECATED);
    return Form::is_valid();
  }

  function tep_require_login($parameters = null) {
    trigger_error('The tep_require_login function has been deprecated.', E_USER_DEPRECATED);
    Login::require($parameters);
  }
