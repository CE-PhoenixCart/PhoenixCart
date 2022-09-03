<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

////
// Get the installed version number
  function tep_get_version() {
    trigger_error('The tep_get_version function has been deprecated.', E_USER_DEPRECATED);
    return Versions::get('Phoenix');
  }

////
// Redirect to another page or site
  function tep_redirect($url) {
    trigger_error('The tep_redirect function has been deprecated.', E_USER_DEPRECATED);
    Href::redirect($url);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    trigger_error('The tep_output_string function has been deprecated.', E_USER_DEPRECATED);
    return $protected ? htmlspecialchars($string) : Text::output($string, $translate);
  }

  function tep_sanitize_string($string) {
    trigger_error('The tep_sanitize_string function has been deprecated.', E_USER_DEPRECATED);
    return Text::sanitize($string);
  }

  function tep_customers_name($customers_id) {
    trigger_error('The tep_customers_name function has been deprecated.', E_USER_DEPRECATED);
    $customer = new customer($customers_id);

    return $customer->get('name');
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

  function tep_get_all_get_params($excludes = []) {
    trigger_error('The tep_get_all_get_params function has been deprecated.', E_USER_DEPRECATED);
    $excludes = array_merge($excludes, [ session_name(), 'error']);

    $get_url = '';
    foreach (array_diff_key($_GET, array_flip($excludes)) as $key => $value) {
      if (rawurlencode($key) === $key) {
        $value = rawurlencode($value);
        $get_url .= "$key=$value&";
      }
    }

    return $get_url;
  }

  function tep_date_long($raw_date) {
    trigger_error('The tep_date_long function has been deprecated.', E_USER_DEPRECATED);
    return Date::expound($raw_date);
  }

  function tep_date_short($raw_date) {
    trigger_error('The tep_date_short function has been deprecated.', E_USER_DEPRECATED);
    return Date::abridge($raw_date);
  }

  function tep_datetime_short($raw_datetime) {
    trigger_error('The tep_datetime_short function has been deprecated.', E_USER_DEPRECATED);
    return (new Date($raw_datetime))->format(DATE_TIME_FORMAT);
  }

  function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $categories = []) {
    trigger_error('The tep_get_category_tree function has been deprecated.', E_USER_DEPRECATED);
    $category_tree =& Guarantor::ensure_global('category_tree');

    if ( (count($categories) < 1) && ($exclude !== '0') ) {
      $categories[] = ['id' => '0', 'text' => TEXT_TOP];
    }

    return $category_tree->get_selections($categories, $parent_id);
  }

  function tep_draw_products_pull_down($name, $parameters = '', $exclude = [], $class = 'class="form-control"') {
    trigger_error('The tep_draw_products_pull_down function has been deprecated.', E_USER_DEPRECATED);
    // replaced by Products::select_discountable
    global $currencies;

    if (!$exclude) {
      $exclude = [];
    }

    $select_string = '<select name="' . $name . '"';

    if (!Text::is_empty($parameters)) {
      $select_string .= " $parameters";
    }
    if (!Text::is_empty($class)) {
      $select_string .= " $class";
    }

    $select_string .= '>';

    $products_query = $GLOBALS['db']->query("SELECT p.products_id, pd.products_name, p.products_price FROM products p, products_description pd WHERE p.products_id = pd.products_id AND pd.language_id = " . (int)$_SESSION['languages_id'] . " ORDER BY products_name");
    while ($products = $products_query->fetch_assoc()) {
      if (!in_array($products['products_id'], $exclude)) {
        $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
      }
    }

    $select_string .= '</select>';

    return $select_string;
  }

  function tep_format_system_info_array($array) {
    trigger_error('The tep_format_system_info_array function has been deprecated.', E_USER_DEPRECATED);
    // replaced by system_info::__toString

    $output = '';
    foreach ($array as $section => $child) {
      $output .= '[' . $section . ']' . "\n";
      foreach ($child as $variable => $value) {
        if (is_array($value)) {
          $output .= $variable . ' = ' . implode(', ', $value) ."\n";
        } else {
          $output .= $variable . ' = ' . $value . "\n";
        }
      }

    $output .= "\n";
    }
    return $output;

  }

  function tep_options_name($options_id) {
    trigger_error('The tep_options_name function has been deprecated.', E_USER_DEPRECATED);
    $options = $GLOBALS['db']->query("SELECT products_options_name FROM products_options WHERE products_options_id = " . (int)$options_id . " AND language_id = " . (int)$_SESSION['languages_id']);
    $options_values = $options->fetch_assoc();

    return $options_values['products_options_name'];
  }

  function tep_values_name($values_id) {
    trigger_error('The tep_values_name function has been deprecated.', E_USER_DEPRECATED);
    $values = $GLOBALS['db']->query("SELECT products_options_values_name FROM products_options_values WHERE products_options_values_id = " . (int)$values_id . " AND language_id = " . (int)$_SESSION['languages_id']);
    $values_values = $values->fetch_assoc();

    return $values_values['products_options_values_name'];
  }

  function tep_info_image($image, $alt, $width = '', $height = '') {
    trigger_error('The tep_info_image function has been deprecated.', E_USER_DEPRECATED);
    return Guarantor::ensure_global('Admin')->catalog_image(
      "images/$image", [], $alt, $width, $height);
  }

  function tep_break_string($string, $len, $break_char = '-') {
    trigger_error('The tep_break_string function has been deprecated.', E_USER_DEPRECATED);
    return Text::break($string, $len, $break_char);
  }

  function tep_get_country_name($country_id) {
    trigger_error('The tep_get_country_name function has been deprecated.', E_USER_DEPRECATED);
    return Country::fetch_name($country_id);
  }

  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    trigger_error('The tep_get_zone_name function has been deprecated.', E_USER_DEPRECATED);
    return Zone::fetch_name($zone_id, $country_id, $default_zone);
  }

  function tep_not_null($value) {
    trigger_error('The tep_not_null function has been deprecated.', E_USER_DEPRECATED);
    if (is_null($value)) {
      return false;
    }

    if (is_array($value)) {
      return count($value) > 0;
    }

    return !Text::is_empty($value);
  }

  function tep_browser_detect($component) {
    trigger_error('The tep_browser_detect function has been deprecated.', E_USER_DEPRECATED);
    return stristr($_SERVER['HTTP_USER_AGENT'], $component);
  }

  function tep_tax_classes_pull_down($parameters, $selected = '') {
    trigger_error('The tep_tax_classes_pull_down function has been deprecated.', E_USER_DEPRECATED);
    $parameters = phoenix_normalize($parameters);
    return (new Select($parameters['name'], 
        Tax::fetch_classes()))->set_selection($selected);
  }

  function tep_geo_zones_pull_down($parameters, $selected = '') {
    trigger_error('The tep_geo_zones_pull_down function has been deprecated.', E_USER_DEPRECATED);
    $zones = $GLOBALS['db']->fetch_all("SELECT geo_zone_id AS id, geo_zone_name AS text FROM geo_zones ORDER BY geo_zone_name");

    $parameters = phoenix_normalize($parameters);
    return (new Select($parameters['name'], $zones))->set_selection($selected);
  }

  function tep_get_geo_zone_name($geo_zone_id) {
    trigger_error('The tep_get_geo_zone_name function has been deprecated.', E_USER_DEPRECATED);
    return geo_zone::fetch_name($geo_zone_id);
  }

  function tep_get_zone_code($country, $zone, $default_state) {
    trigger_error('The tep_get_zone_code function has been deprecated.', E_USER_DEPRECATED);
    return Zone::get_code($zone, $country, $default_state);
  }

  function tep_get_languages() {
    trigger_error('The tep_get_languages function has been deprecated.', E_USER_DEPRECATED);
    return array_values(language::load_all());
  }

  function tep_get_orders_status_name($orders_status_id, $language_id = '') {
    trigger_error('The tep_get_orders_status_name function has been deprecated.', E_USER_DEPRECATED);
    return order_status::fetch_name($orders_status_id, $language_id);
  }

  function tep_get_orders_status() {
    trigger_error('The tep_get_orders_status function has been deprecated.', E_USER_DEPRECATED);
    return order_status::fetch_options();
  }

////
// Return the manufacturers URL in the needed language
// TABLES: manufacturers_info
  function tep_get_manufacturer_url($manufacturer_id, $language_id) {
    trigger_error('The tep_get_manufacturer_url function has been deprecated.', E_USER_DEPRECATED);
    $manufacturer_query = $GLOBALS['db']->query("SELECT manufacturers_url FROM manufacturers_info WHERE manufacturers_id = " . (int)$manufacturer_id . " AND languages_id = " . (int)$language_id);
    $manufacturer = $manufacturer_query->fetch_assoc();

    return $manufacturer['manufacturers_url'];
  }

////
// Returns an array with countries
// TABLES: countries
  function tep_get_countries($default = '') {
    trigger_error('The tep_get_countries function has been deprecated.', E_USER_DEPRECATED);
    $countries = Country::fetch_options();
    if ($default) {
      $countries = array_merge([[
        'id' => '',
        'text' => $default,
      ]], $countries);
    }

    return $countries;
  }

////
// return an array with country zones
  function tep_get_country_zones($country_id) {
    trigger_error('The tep_get_country_zones function has been deprecated.', E_USER_DEPRECATED);
    return Zone::fetch_by_country($country_id);
  }

  function tep_prepare_country_zones_pull_down($country_id = '') {
    trigger_error('The tep_prepare_country_zones_pull_down function has been deprecated.', E_USER_DEPRECATED);
    $zones = Zone::fetch_by_country($country_id);

    if (count($zones) > 0) {
      $zones = array_merge([['id' => '', 'text' => PLEASE_SELECT]], $zones);
    } else {
      $zones = [['id' => '', 'text' => TYPE_BELOW]];
      if ( (!tep_browser_detect('MSIE')) && (tep_browser_detect('Mozilla/4')) ) {
// preset the width of the drop-down for Netscape
// create dummy options for Netscape to preset the height of the drop-down
        $zones += array_fill(1, 9,
          ['id' => '', 'text' => str_repeat('&nbsp;', 45)]);
      }
    }

    return $zones;
  }

////
// Get list of address_format_id values
  function tep_get_address_formats() {
    trigger_error('The tep_get_address_formats function has been deprecated.', E_USER_DEPRECATED);
    return $GLOBALS['db']->fetch_all("SELECT address_format_id AS id, address_format_id AS text FROM address_format ORDER BY address_format_id");
  }

  function tep_cfg_pull_down_country_list($country_id) {
    trigger_error('The tep_cfg_pull_down_country_list function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_country($country_id);
  }

  function tep_cfg_pull_down_zone_list($zone_id) {
    trigger_error('The tep_cfg_pull_down_zone_list function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_zone_by(STORE_COUNTRY, $zone_id);
  }

  function tep_cfg_pull_down_tax_classes($tax_class_id, $key = '') {
    trigger_error('The tep_cfg_pull_down_tax_classes function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_tax_class($tax_class_id, $key);
  }

  function tep_cfg_pull_down_customer_data_groups($customer_data_group_id, $key) {
    trigger_error('The tep_cfg_pull_down_customer_data_groups function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_customer_data_group($customer_data_group_id, $key);
  }

  function tep_get_customer_data_group_title($customer_data_group_id) {
    trigger_error('The tep_get_customer_data_group_title function has been deprecated.', E_USER_DEPRECATED);
    return customer_data_group::fetch_name($customer_data_group_id);
  }

  function tep_cfg_textarea($text) {
    trigger_error('The tep_cfg_textarea function has been deprecated.', E_USER_DEPRECATED);
    return Config::draw_textarea($text);
  }

  function tep_cfg_get_zone_name($zone_id) {
    trigger_error('The tep_cfg_get_zone_name function has been deprecated.', E_USER_DEPRECATED);
    return Zone::fetch_name($zone_id);
  }

////
// Can't be used in safe mode.
  function tep_set_time_limit($limit) {
    trigger_error('The tep_set_time_limit function has been deprecated.', E_USER_DEPRECATED);
    set_time_limit($limit);
  }

  function tep_cfg_select_option($select_options, $key_value, $key = '') {
    trigger_error('The tep_cfg_select_option function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_one($select_options, $key_value, $key);
  }

////
// set_function for checkbox selections
  function tep_cfg_multiple_select_option($selections, $key_values, $key_name = null) {
    trigger_error('The tep_cfg_multiple_select_option function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_multiple($selections, $key_values, $key_name);
  }

  function tep_cfg_select_template($key_value, $key = null) {
    trigger_error('The tep_cfg_select_template function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_template($key_value, $key);
  }

  function tep_get_system_information() {
    trigger_error('The tep_get_system_information function has been deprecated.', E_USER_DEPRECATED);
    // use the system_information class directly
    return (new system_information())->get_data();
  }

  function tep_generate_category_path($id, $from = 'category', $categories = [], $index = 0) {
    trigger_error('The tep_generate_category_path function has been deprecated.', E_USER_DEPRECATED);
    if ($from === 'product') {
      return Categories::generate_paths(product_by_id::build($id)->get('categories'));
    } elseif ($from === 'category') {
      return [Categories::generate_path($id)];
    }

    return [];
  }

  function tep_output_generated_category_path($id, $from = 'category') {
    trigger_error('The tep_output_generated_category_path function has been deprecated.', E_USER_DEPRECATED);
    return Categories::draw_breadcrumbs(
      ('category' === $from)
      ? [$id]
      : product_by_id::build($id)->get('categories')
    ) ?: TEXT_TOP;
  }

  function tep_get_generated_category_path_ids($id, $from = 'category') {
    trigger_error('The tep_get_generated_category_path_ids function has been deprecated.', E_USER_DEPRECATED);
    return Categories::build_paths(
      ('category' === $from)
      ? [$id]
      : product_by_id::build($id)->get('categories')
    ) ?: TEXT_TOP;
  }

  function tep_remove_category($category_id) {
    trigger_error('The tep_remove_category function has been deprecated.', E_USER_DEPRECATED);
    Categories::remove($category_id);
  }

  function tep_remove_product($product_id) {
    trigger_error('The tep_remove_product function has been deprecated.', E_USER_DEPRECATED);
    Products::remove($product_id);
  }

  function tep_remove_order($order_id, $restock = false) {
    trigger_error('The tep_remove_order function has been deprecated.', E_USER_DEPRECATED);
    order::remove($order_id);
  }

  function tep_remove($source) {
    trigger_error('The tep_remove function has been deprecated.', E_USER_DEPRECATED);
    global $messageStack, $tep_remove_error;

    if (isset($tep_remove_error)) {
      $tep_remove_error = false;
    }

    if (is_dir($source)) {
      if (!Path::remove($source)) {
        $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
        $tep_remove_error = true;
      }
    } else {
      if (!File::remove($source)) {
        $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
        $tep_remove_error = true;
      }
    }
  }

  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    trigger_error('The tep_display_tax_value function has been deprecated.', E_USER_DEPRECATED);
    return Tax::format($value, $padding);
  }

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    trigger_error('The tep_mail function has been deprecated.', E_USER_DEPRECATED);
    return Notifications::mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address);
  }

  function tep_notify($trigger, $subject) {
    trigger_error('The tep_notify function has been deprecated.', E_USER_DEPRECATED);
    return Notifications::notify($trigger, $subject);
  }

  function tep_get_tax_class_title($tax_class_id) {
    trigger_error('The tep_get_tax_class_title function has been deprecated.', E_USER_DEPRECATED);
    return Tax::get_class_title($tax_class_id);
  }

  function tep_round($value, $precision) {
    trigger_error('The tep_round function has been deprecated.', E_USER_DEPRECATED);
    return currencies::round($value, $precision);
  }

  function tep_add_tax($price, $tax, $override = false) {
    trigger_error('The tep_add_tax function has been deprecated.', E_USER_DEPRECATED);
    return $override
         ? Tax::add($price, $tax)
         : Tax::price($price, $tax);
  }

  function tep_calculate_tax($price, $tax) {
    trigger_error('The tep_calculate_tax function has been deprecated.', E_USER_DEPRECATED);
    return Tax::calculate($price, $tax);
  }

  function tep_get_tax_rate($class_id, $country_id = null, $zone_id = null) {
    trigger_error('The tep_get_tax_rate function has been deprecated.', E_USER_DEPRECATED);
    return Tax::get_rate($class_id, $country_id, $zone_id);
  }

  function tep_get_tax_rate_value($class_id) {
    trigger_error('The tep_get_tax_rate_value function has been deprecated.', E_USER_DEPRECATED);
    return Tax::get_rate($class_id);
  }

  function tep_get_zone_class_title($zone_class_id) {
    trigger_error('The tep_get_zone_class_title function has been deprecated.', E_USER_DEPRECATED);
    return geo_zone::fetch_name($zone_class_id);
  }

  function tep_cfg_pull_down_zone_classes($zone_class_id, $key = '') {
    trigger_error('The tep_cfg_pull_down_zone_classes function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_geo_zone($zone_class_id, $key);
  }

  function tep_cfg_pull_down_order_statuses($order_status_id, $key = '') {
    trigger_error('The tep_cfg_pull_down_order_statuses function has been deprecated.', E_USER_DEPRECATED);
    return Config::select_order_status($order_status_id, $key);
  }

  function tep_get_order_status_name($order_status_id, $language_id = '') {
    trigger_error('The tep_get_order_status_name function has been deprecated.', E_USER_DEPRECATED);
    if ($order_status_id < 1) {
      return TEXT_DEFAULT;
    }

    if (!is_numeric($language_id)) {
      $language_id = $_SESSION['languages_id'];
    }

    $status_query = $GLOBALS['db']->query("SELECT orders_status_name FROM orders_status WHERE orders_status_id = " . (int)$order_status_id . " AND language_id = " . (int)$language_id);
    $status = $status_query->fetch_assoc();

    return $status['orders_status_name'];
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    trigger_error('The tep_rand function has been deprecated.', E_USER_DEPRECATED);
    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function tep_convert_linefeeds($from, $to, $string) {
    trigger_error('The tep_convert_linefeeds function has been deprecated.', E_USER_DEPRECATED);
    return str_replace($from, $to, $string);
  }

  function tep_parse_category_path($cPath) {
    trigger_error('The tep_parse_category_path function has been deprecated.', E_USER_DEPRECATED);
    return array_unique(array_map('intval', explode('_', $cPath)));
  }

  function tep_validate_ip_address($ip_address) {
    trigger_error('The tep_validate_ip_address function has been deprecated.', E_USER_DEPRECATED);
    return filter_var($ip_address, FILTER_VALIDATE_IP, ['flags' => FILTER_FLAG_IPV4]);
  }

  function tep_get_ip_address() {
    trigger_error('The tep_get_ip_address function has been deprecated.', E_USER_DEPRECATED);
    return Request::get_ip();
  }

////
// Windows compatible is_writable()
  function tep_is_writable($file) {
    trigger_error('The tep_is_writable function has been deprecated.', E_USER_DEPRECATED);
    return Path::is_writable($file);
  }

  function tep_get_manufacturer_description($manufacturer_id, $language_id) {
    trigger_error('The tep_get_manufacturer_description function has been deprecated.', E_USER_DEPRECATED);
    $manufacturer_query = $GLOBALS['db']->query("SELECT manufacturers_description FROM manufacturers_info WHERE manufacturers_id = " . (int)$manufacturer_id . " AND languages_id = " . (int)$language_id);
    $manufacturer = $manufacturer_query->fetch_assoc();

    return $manufacturer['manufacturers_description'];
  }

  function tep_get_manufacturer_seo_description($manufacturer_id, $language_id) {
    trigger_error('The tep_get_manufacturer_seo_description function has been deprecated.', E_USER_DEPRECATED);
    $manufacturer_query = $GLOBALS['db']->query("SELECT manufacturers_seo_description FROM manufacturers_info WHERE manufacturers_id = " . (int)$manufacturer_id . " AND languages_id = " . (int)$language_id);
    $manufacturer = $manufacturer_query->fetch_assoc();

    return $manufacturer['manufacturers_seo_description'];
  }

  function tep_get_manufacturer_seo_title($manufacturer_id, $language_id) {
    trigger_error('The tep_get_manufacturer_seo_title function has been deprecated.', E_USER_DEPRECATED);
    $manufacturer_query = $GLOBALS['db']->query("SELECT manufacturers_seo_title FROM manufacturers_info WHERE manufacturers_id = " . (int)$manufacturer_id . " AND languages_id = " . (int)$language_id);
    $manufacturer = $manufacturer_query->fetch_assoc();

    return $manufacturer['manufacturers_seo_title'];
  }

  function tep_draw_products($name, $parameters = '', $exclude = [], $class = 'class="form-control"') {
    trigger_error('The tep_draw_products function has been deprecated.', E_USER_DEPRECATED);
    $select = Products::select($name, phoenix_normalize($parameters), $exclude);
    phoenix_append_css($class, $select);

    return $select;
  }

  function tep_generate_customers() {
    trigger_error('The tep_generate_customers function has been deprecated.', E_USER_DEPRECATED);
    yield from Customers::generate();
  }

  function tep_draw_customers($name, $parameters = '', $selected = '', $class = null) {
    trigger_error('The tep_draw_customers function has been deprecated.', E_USER_DEPRECATED);
    $select = Customers::select($name, phoenix_normalize($parameters), $selected);
    phoenix_append_css($class, $select);

    return $select;
  }

  function tep_draw_account_edit_pages($key_values, $key_name = null) {
    trigger_error('The tep_draw_account_edit_pages function has been deprecated.', E_USER_DEPRECATED);
    return Customers::select_pages($key_values, $key_name);
  }

  function tep_block_form_processing() {
    trigger_error('The tep_block_form_processing function has been deprecated.', E_USER_DEPRECATED);
    Form::block_processing();
  }

  function tep_form_processing_is_valid() {
    trigger_error('The tep_form_processing_is_valid function has been deprecated.', E_USER_DEPRECATED);
    return Form::is_valid();
  }
