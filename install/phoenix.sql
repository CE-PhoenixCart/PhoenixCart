# $Id$
#
# CE Phoenix, E-Commerce made Easy
# https://phoenixcart.org
#
# Copyright (c) 2021 Phoenix Cart
#
# Released under the GNU General Public License
#
# NOTE: * Please make any modifications to this file by hand!
#       * DO NOT use a mysqldump created file for new changes!
#       * Please take note of the table structure, and use this
#         structure as a standard for future modifications!

DROP TABLE IF EXISTS action_recorder;
CREATE TABLE action_recorder (
  id int NOT NULL auto_increment,
  module varchar(255) NOT NULL,
  user_id int,
  user_name varchar(255),
  identifier varchar(255) NOT NULL,
  success char(1),
  date_added datetime NOT NULL,
  PRIMARY KEY (id),
  KEY idx_action_recorder_module_date (module, date_added),
  KEY idx_action_recorder_user_id (user_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS address_book;
CREATE TABLE address_book (
   address_book_id int NOT NULL auto_increment,
   customers_id int NOT NULL,
   entry_gender char(1),
   entry_company varchar(255),
   entry_firstname varchar(255) NOT NULL,
   entry_lastname varchar(255) NOT NULL,
   entry_street_address varchar(255) NOT NULL,
   entry_suburb varchar(255),
   entry_postcode varchar(255) NOT NULL,
   entry_city varchar(255) NOT NULL,
   entry_state varchar(255),
   entry_country_id int DEFAULT '0' NOT NULL,
   entry_zone_id int DEFAULT '0' NOT NULL,
   PRIMARY KEY (address_book_id),
   KEY idx_address_book_customers_id (customers_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS address_format;
CREATE TABLE address_format (
  address_format_id int NOT NULL auto_increment,
  address_format varchar(128) NOT NULL,
  address_summary varchar(48) NOT NULL,
  PRIMARY KEY (address_format_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS administrators;
CREATE TABLE administrators (
  id int NOT NULL auto_increment,
  user_name varchar(127) NOT NULL,
  user_password varchar(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_administrator_user_name (user_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS analytics_events;
CREATE TABLE analytics_events (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id INT UNSIGNED DEFAULT NULL,
  merchant_id INT UNSIGNED NOT NULL DEFAULT 0,
  event_type VARCHAR(255) NOT NULL,
  product_id INT UNSIGNED DEFAULT NULL,
  payload LONGTEXT NOT NULL,
  page_url TEXT DEFAULT NULL,
  referrer TEXT DEFAULT NULL,
  domain VARCHAR(255) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL,
  ip_address VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_event_type (event_type),
  INDEX idx_product (product_id),
  INDEX idx_merchant (merchant_id),
  INDEX idx_created_at (created_at),
  INDEX idx_customer_id (customer_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS advert;
CREATE TABLE advert (
  advert_id int NOT NULL auto_increment,
  advert_title varchar(64) NOT NULL,
  advert_url varchar(255) NOT NULL,
  advert_fragment varchar(255) NOT NULL,
  advert_image varchar(64) NOT NULL,
  advert_group varchar(64) NOT NULL,
  date_added datetime NOT NULL,
  date_status_change datetime DEFAULT NULL,
  sort_order int(3),
  status int(1) DEFAULT '1' NOT NULL,
  PRIMARY KEY (advert_id),
  KEY idx_advert_group (advert_group)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS advert_info;
CREATE TABLE advert_info (
  advert_id int NOT NULL,
  languages_id int NOT NULL,
  advert_html_text text,
  PRIMARY KEY (advert_id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
   categories_id int NOT NULL auto_increment,
   categories_image varchar(255),
   parent_id int DEFAULT '0' NOT NULL,
   sort_order int(3),
   date_added datetime,
   last_modified datetime,
   PRIMARY KEY (categories_id),
   KEY idx_categories_parent_id (parent_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS categories_description;
CREATE TABLE categories_description (
   categories_id int DEFAULT '0' NOT NULL,
   language_id int DEFAULT '1' NOT NULL,
   categories_name varchar(255) NOT NULL,
   categories_description TEXT NULL,
   categories_seo_description TEXT NULL,
   categories_seo_title VARCHAR(255) NULL,
   PRIMARY KEY (categories_id, language_id),
   KEY idx_categories_name (categories_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS configuration;
CREATE TABLE configuration (
  configuration_id int NOT NULL auto_increment,
  configuration_title varchar(255) NOT NULL,
  configuration_key varchar(255) NOT NULL,
  configuration_value text NOT NULL,
  configuration_description text NOT NULL,
  configuration_group_id int NOT NULL,
  sort_order int(5) NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  use_function varchar(255) NULL,
  set_function varchar(255) NULL,
  PRIMARY KEY (configuration_id),
  UNIQUE KEY uq_configuration_key (configuration_key)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS configuration_group;
CREATE TABLE configuration_group (
  configuration_group_id int NOT NULL auto_increment,
  configuration_group_title varchar(64) NOT NULL,
  configuration_group_description varchar(255) NOT NULL,
  configuration_group_help_link varchar(255) NULL,
  configuration_group_addons_links text NULL,
  sort_order int(5) NULL,
  visible int(1) DEFAULT '1' NULL,
  PRIMARY KEY (configuration_group_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS countries;
CREATE TABLE countries (
  countries_id int NOT NULL auto_increment,
  countries_name varchar(255) NOT NULL,
  countries_iso_code_2 char(2) NOT NULL,
  countries_iso_code_3 char(3) NOT NULL,
  address_format_id int NOT NULL,
  status int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (countries_id),
  KEY IDX_COUNTRIES_NAME (countries_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS currencies;
CREATE TABLE currencies (
  currencies_id int NOT NULL auto_increment,
  title varchar(32) NOT NULL,
  code char(3) NOT NULL,
  symbol_left varchar(12),
  symbol_right varchar(12),
  decimal_point char(1),
  thousands_point char(1),
  decimal_places char(1),
  value float(13,8),
  last_updated datetime NULL,
  PRIMARY KEY (currencies_id),
  KEY idx_currencies_code (code)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customers;
CREATE TABLE customers (
   customers_id int NOT NULL auto_increment,
   customers_gender char(1),
   customers_firstname varchar(255) NOT NULL,
   customers_lastname varchar(255) NOT NULL,
   customers_dob date NULL,
   customers_email_address varchar(255) NOT NULL,
   customers_default_address_id int,
   customers_telephone varchar(255) NOT NULL,
   customers_fax varchar(255),
   customers_password varchar(255) NOT NULL,
   customers_newsletter char(1),
   PRIMARY KEY (customers_id),
   UNIQUE KEY uq_customers_email_address (customers_email_address)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customers_basket;
CREATE TABLE customers_basket (
  customers_basket_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  products_id tinytext NOT NULL,
  customers_basket_quantity int(2) NOT NULL,
  final_price decimal(15,4),
  customers_basket_date_added char(8),
  PRIMARY KEY (customers_basket_id),
  KEY idx_customers_basket_customers_id (customers_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customers_basket_attributes;
CREATE TABLE customers_basket_attributes (
  customers_basket_attributes_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  products_id tinytext NOT NULL,
  products_options_id int NOT NULL,
  products_options_value_id int NOT NULL,
  PRIMARY KEY (customers_basket_attributes_id),
  KEY idx_customers_basket_att_customers_id (customers_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customer_data_groups;
CREATE TABLE customer_data_groups (
  customer_data_groups_id int(11) NOT NULL,
  language_id int(11) NOT NULL,
  customer_data_groups_name varchar(255) NOT NULL,
  cdg_vertical_sort_order int(11) NOT NULL,
  customer_data_groups_width varchar(255) NOT NULL,
  PRIMARY KEY (language_id, customer_data_groups_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customer_data_groups_sequence;
CREATE TABLE customer_data_groups_sequence (
  customer_data_groups_id int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY(customer_data_groups_id)
)  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customers_gdpr;
CREATE TABLE customers_gdpr (
   gdpr_id int NOT NULL auto_increment,
   customers_id int NOT NULL,
   page varchar(255) NOT NULL,
   slug varchar(255) NOT NULL,
   pages_title varchar(255) NOT NULL,
   pages_text text NOT NULL,
   language varchar(255) NOT NULL,
   timestamp datetime NOT NULL,
   date_added datetime NOT NULL,
   PRIMARY KEY (gdpr_id),
   KEY idx_customers_id (customers_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS customers_info;
CREATE TABLE customers_info (
  customers_info_id int NOT NULL,
  customers_info_date_of_last_logon datetime,
  customers_info_number_of_logons int(5),
  customers_info_date_account_created datetime,
  customers_info_date_account_last_modified datetime,
  global_product_notifications int(1) DEFAULT '0',
  password_reset_key char(40),
  password_reset_date datetime,
  PRIMARY KEY (customers_info_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS geo_zones;
CREATE TABLE geo_zones (
  geo_zone_id int NOT NULL auto_increment,
  geo_zone_name varchar(32) NOT NULL,
  geo_zone_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (geo_zone_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS hooks;
CREATE TABLE hooks (
  hooks_id INT NOT NULL AUTO_INCREMENT,
  hooks_site VARCHAR(63) NOT NULL,
  hooks_group VARCHAR(63) NOT NULL,
  hooks_action VARCHAR(255) NOT NULL,
  hooks_code VARCHAR(127) NOT NULL,
  hooks_class VARCHAR(255) NOT NULL,
  hooks_method VARCHAR(255) NOT NULL,
  PRIMARY KEY (hooks_id),
  KEY idx_hooks_site_group (hooks_site, hooks_group)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS importers;
CREATE TABLE importers (
  importers_id int NOT NULL auto_increment,
  importers_name varchar(255) NOT NULL,
  importers_image varchar(255),
  importers_address TEXT NULL,
  importers_email varchar(255),
  date_added datetime NULL,
  last_modified datetime NULL,
  PRIMARY KEY (importers_id),
  KEY IDX_IMPORTERS_NAME (importers_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS importers_info;
CREATE TABLE importers_info (
  importers_id int NOT NULL,
  languages_id int NOT NULL,
  importers_url varchar(255) NOT NULL,
  url_clicked int(5) NOT NULL default '0',
  date_last_click datetime NULL,
  importers_description TEXT NULL,
  PRIMARY KEY (importers_id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  languages_id int NOT NULL auto_increment,
  name varchar(32)  NOT NULL,
  code char(2) NOT NULL,
  image varchar(64),
  directory varchar(32),
  sort_order int(3),
  PRIMARY KEY (languages_id),
  KEY IDX_LANGUAGES_NAME (name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS manufacturers;
CREATE TABLE manufacturers (
  manufacturers_id int NOT NULL auto_increment,
  manufacturers_name varchar(255) NOT NULL,
  manufacturers_image varchar(255),
  manufacturers_address TEXT NULL,
  manufacturers_email varchar(255),
  date_added datetime NULL,
  last_modified datetime NULL,
  PRIMARY KEY (manufacturers_id),
  KEY IDX_MANUFACTURERS_NAME (manufacturers_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS manufacturers_info;
CREATE TABLE manufacturers_info (
  manufacturers_id int NOT NULL,
  languages_id int NOT NULL,
  manufacturers_url varchar(255) NOT NULL,
  url_clicked int(5) NOT NULL default '0',
  date_last_click datetime NULL,
  manufacturers_description TEXT NULL,
  manufacturers_seo_description TEXT NULL,
  manufacturers_seo_title VARCHAR(255) NULL,
  PRIMARY KEY (manufacturers_id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS newsletters;
CREATE TABLE newsletters (
  newsletters_id int NOT NULL auto_increment,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  module varchar(255) NOT NULL,
  date_added datetime NOT NULL,
  date_sent datetime,
  status int(1),
  locked int(1) DEFAULT '0',
  PRIMARY KEY (newsletters_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
  orders_id int NOT NULL auto_increment,
  customers_id int NOT NULL,
  customers_name varchar(255) NOT NULL,
  customers_company varchar(255),
  customers_street_address varchar(255) NOT NULL,
  customers_suburb varchar(255),
  customers_city varchar(255) NOT NULL,
  customers_postcode varchar(255) NOT NULL,
  customers_state varchar(255),
  customers_country varchar(255) NOT NULL,
  customers_country_id int NOT NULL,
  customers_telephone varchar(255) NOT NULL,
  customers_email_address varchar(255) NOT NULL,
  customers_address_format_id int(5) NOT NULL,
  delivery_name varchar(255) NOT NULL,
  delivery_company varchar(255),
  delivery_street_address varchar(255) NOT NULL,
  delivery_suburb varchar(255),
  delivery_city varchar(255) NOT NULL,
  delivery_postcode varchar(255) NOT NULL,
  delivery_state varchar(255),
  delivery_country varchar(255) NOT NULL,
  delivery_country_id int NOT NULL,
  delivery_address_format_id int(5) NOT NULL,
  billing_name varchar(255) NOT NULL,
  billing_company varchar(255),
  billing_street_address varchar(255) NOT NULL,
  billing_suburb varchar(255),
  billing_city varchar(255) NOT NULL,
  billing_postcode varchar(255) NOT NULL,
  billing_state varchar(255),
  billing_country varchar(255) NOT NULL,
  billing_country_id int NOT NULL,
  billing_address_format_id int(5) NOT NULL,
  payment_method varchar(255) NOT NULL,
  cc_type varchar(20),
  cc_owner varchar(255),
  cc_number varchar(32),
  cc_expires varchar(4),
  last_modified datetime,
  date_purchased datetime,
  orders_status int(5) NOT NULL,
  orders_date_finished datetime,
  currency char(3),
  currency_value decimal(14,6),
  PRIMARY KEY (orders_id),
  KEY idx_orders_customers_id (customers_id),
  KEY idx_orders_orders_status (orders_status)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders_products;
CREATE TABLE orders_products (
  orders_products_id int NOT NULL auto_increment,
  orders_id int NOT NULL,
  products_id int NOT NULL,
  products_model varchar(255),
  products_name varchar(255) NOT NULL,
  products_price decimal(15,4) NOT NULL,
  final_price decimal(15,4) NOT NULL,
  products_tax decimal(7,4) NOT NULL,
  products_quantity int(2) NOT NULL,
  PRIMARY KEY (orders_products_id),
  KEY idx_orders_products_orders_id (orders_id),
  KEY idx_orders_products_products_id (products_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders_status;
CREATE TABLE orders_status (
   orders_status_id int DEFAULT '0' NOT NULL,
   language_id int DEFAULT '1' NOT NULL,
   orders_status_name varchar(32) NOT NULL,
   public_flag int DEFAULT '1',
   downloads_flag int DEFAULT '0',
   PRIMARY KEY (orders_status_id, language_id),
   KEY idx_orders_status_name (orders_status_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders_status_history;
CREATE TABLE orders_status_history (
   orders_status_history_id int NOT NULL auto_increment,
   orders_id int NOT NULL,
   orders_status_id int(5) NOT NULL,
   date_added datetime NOT NULL,
   customer_notified int(1) DEFAULT '0',
   comments text,
   PRIMARY KEY (orders_status_history_id),
   KEY idx_orders_status_history_orders_id (orders_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders_products_attributes;
CREATE TABLE orders_products_attributes (
  orders_products_attributes_id int NOT NULL auto_increment,
  orders_id int NOT NULL,
  orders_products_id int NOT NULL,
  products_options varchar(255) NOT NULL,
  products_options_values varchar(255) NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (orders_products_attributes_id),
  KEY idx_orders_products_att_orders_id (orders_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders_products_download;
CREATE TABLE orders_products_download (
  orders_products_download_id int NOT NULL auto_increment,
  orders_id int NOT NULL default '0',
  orders_products_id int NOT NULL default '0',
  orders_products_filename varchar(255) NOT NULL default '',
  download_maxdays int(2) NOT NULL default '0',
  download_count int(2) NOT NULL default '0',
  PRIMARY KEY  (orders_products_download_id),
  KEY idx_orders_products_download_orders_id (orders_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS orders_total;
CREATE TABLE orders_total (
  orders_total_id int unsigned NOT NULL auto_increment,
  orders_id int NOT NULL,
  title varchar(255) NOT NULL,
  text varchar(255) NOT NULL,
  value decimal(15,4) NOT NULL,
  class varchar(32) NOT NULL,
  sort_order int NOT NULL,
  PRIMARY KEY (orders_total_id),
  KEY idx_orders_total_orders_id (orders_id),
  KEY idx_orders_total_orders_id_class (orders_id, class)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS pages;
CREATE TABLE pages (
  pages_id int NOT NULL auto_increment,
  date_added datetime,
  last_modified datetime,
  pages_status tinyint(1) NOT NULL default '1',
  slug varchar(255) NOT NULL,
  sort_order int(11) NULL,
  PRIMARY KEY (pages_id),
  UNIQUE KEY uq_slug (slug)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS pages_description;
CREATE TABLE pages_description (
  pages_id int NOT NULL,
  languages_id int NOT NULL,
  pages_title varchar(255) NOT NULL,
  pages_text text NOT NULL,
  navbar_title varchar(255) NOT NULL,
  PRIMARY KEY (pages_id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products;
CREATE TABLE products (
  products_id int NOT NULL auto_increment,
  products_quantity int(4) NOT NULL,
  products_model varchar(255),
  products_image varchar(255),
  products_price decimal(15,4) NOT NULL,
  products_date_added datetime NOT NULL,
  products_last_modified datetime,
  products_date_available datetime,
  products_weight decimal(5,2) NOT NULL,
  products_status tinyint(1) NOT NULL,
  products_tax_class_id int NOT NULL,
  manufacturers_id int NULL,
  products_ordered int NOT NULL default '0',
  products_gtin CHAR(14) NULL,
  importers_id int NULL,
  PRIMARY KEY (products_id),
  KEY idx_products_model (products_model),
  KEY idx_products_date_added (products_date_added)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_attributes;
CREATE TABLE products_attributes (
  products_attributes_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  options_id int NOT NULL,
  options_values_id int NOT NULL,
  options_values_price decimal(15,4) NOT NULL,
  price_prefix char(1) NOT NULL,
  PRIMARY KEY (products_attributes_id),
  KEY idx_products_attributes_products_id (products_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_attributes_download;
CREATE TABLE products_attributes_download (
  products_attributes_id int NOT NULL,
  products_attributes_filename varchar(255) NOT NULL default '',
  products_attributes_maxdays int(2) default '0',
  products_attributes_maxcount int(2) default '0',
  PRIMARY KEY  (products_attributes_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_description;
CREATE TABLE products_description (
  products_id int NOT NULL auto_increment,
  language_id int NOT NULL default '1',
  products_name varchar(255) NOT NULL default '',
  products_description text,
  products_url varchar(255) default NULL,
  products_seo_description text NULL,
  products_seo_keywords varchar(255) NULL,
  products_seo_title varchar(255) NULL,
  PRIMARY KEY  (products_id,language_id),
  KEY products_name (products_name)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_images;
CREATE TABLE products_images (
  id int NOT NULL auto_increment,
  products_id int NOT NULL,
  image varchar(255),
  htmlcontent text,
  sort_order int NOT NULL,
  PRIMARY KEY (id),
  KEY products_images_prodid (products_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_notifications;
CREATE TABLE products_notifications (
  products_id int NOT NULL,
  customers_id int NOT NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (products_id, customers_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_options;
CREATE TABLE products_options (
  products_options_id int NOT NULL default '0',
  language_id int NOT NULL default '1',
  products_options_name varchar(255) NOT NULL default '',
  sort_order int(3),
  PRIMARY KEY  (products_options_id,language_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_options_values;
CREATE TABLE products_options_values (
  products_options_values_id int NOT NULL default '0',
  language_id int NOT NULL default '1',
  products_options_values_name varchar(255) NOT NULL default '',
  sort_order int(3),
  PRIMARY KEY  (products_options_values_id,language_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_options_values_to_products_options;
CREATE TABLE products_options_values_to_products_options (
  products_options_values_to_products_options_id int NOT NULL auto_increment,
  products_options_id int NOT NULL,
  products_options_values_id int NOT NULL,
  PRIMARY KEY (products_options_values_to_products_options_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS products_to_categories;
CREATE TABLE products_to_categories (
  products_id int NOT NULL,
  categories_id int NOT NULL,
  PRIMARY KEY (products_id,categories_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
  reviews_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  customers_id int,
  customers_name varchar(255) NOT NULL,
  reviews_rating int(1),
  date_added datetime,
  last_modified datetime,
  reviews_status tinyint(1) NOT NULL default '0',
  reviews_read int(5) NOT NULL default '0',
  is_anon enum('y','n') default 'n' NOT NULL,
  PRIMARY KEY (reviews_id),
  KEY idx_reviews_products_id (products_id),
  KEY idx_reviews_customers_id (customers_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS reviews_description;
CREATE TABLE reviews_description (
  reviews_id int NOT NULL,
  languages_id int NOT NULL,
  reviews_text text NOT NULL,
  PRIMARY KEY (reviews_id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS sec_directory_whitelist;
CREATE TABLE sec_directory_whitelist (
  id int NOT NULL auto_increment,
  directory varchar(255) NOT NULL,
  PRIMARY KEY (id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
  sesskey varchar(128) NOT NULL,
  expiry int(11) unsigned NOT NULL,
  value text NOT NULL,
  PRIMARY KEY (sesskey)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS specials;
CREATE TABLE specials (
  specials_id int NOT NULL auto_increment,
  products_id int NOT NULL,
  specials_new_products_price decimal(15,4) NOT NULL,
  specials_date_added datetime,
  specials_last_modified datetime,
  expires_date datetime,
  date_status_change datetime,
  status int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (specials_id),
  KEY idx_specials_products_id (products_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS tax_class;
CREATE TABLE tax_class (
  tax_class_id int NOT NULL auto_increment,
  tax_class_title varchar(32) NOT NULL,
  tax_class_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_class_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS tax_rates;
CREATE TABLE tax_rates (
  tax_rates_id int NOT NULL auto_increment,
  tax_zone_id int NOT NULL,
  tax_class_id int NOT NULL,
  tax_priority int(5) DEFAULT 1,
  tax_rate decimal(7,4) NOT NULL,
  tax_description varchar(255) NOT NULL,
  last_modified datetime NULL,
  date_added datetime NOT NULL,
  PRIMARY KEY (tax_rates_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS testimonials;
CREATE TABLE testimonials (
  testimonials_id int NOT NULL auto_increment,
  customers_id INT(11) NOT NULL DEFAULT '0',
  customers_name varchar(255) NOT NULL,
  date_added datetime,
  last_modified datetime,
  testimonials_status tinyint(1) NOT NULL default '1',
  is_anon enum('y','n') default 'n' NOT NULL,
  PRIMARY KEY (testimonials_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS testimonials_description;
CREATE TABLE testimonials_description (
  testimonials_id int NOT NULL,
  languages_id int NOT NULL,
  testimonials_text text NOT NULL,
  PRIMARY KEY (testimonials_id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS whos_online;
CREATE TABLE whos_online (
  customer_id int,
  full_name varchar(255) NOT NULL,
  session_id varchar(128) NOT NULL,
  ip_address varchar(255) NOT NULL,
  time_entry varchar(14) NOT NULL,
  time_last_click varchar(14) NOT NULL,
  last_page_url text NOT NULL,
  PRIMARY KEY (session_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS zones;
CREATE TABLE zones (
  zone_id int NOT NULL auto_increment,
  zone_country_id int NOT NULL,
  zone_code varchar(32) NOT NULL,
  zone_name varchar(255) NOT NULL,
  PRIMARY KEY (zone_id),
  KEY idx_zones_country_id (zone_country_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS zones_to_geo_zones;
CREATE TABLE zones_to_geo_zones (
   association_id int NOT NULL auto_increment,
   zone_country_id int NOT NULL,
   zone_id int NULL,
   geo_zone_id int NULL,
   last_modified datetime NULL,
   date_added datetime NOT NULL,
   PRIMARY KEY (association_id),
   KEY idx_zones_to_geo_zones_country_id (zone_country_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS outgoing;
CREATE TABLE outgoing (
  id int(11) NOT NULL AUTO_INCREMENT,
  languages_id INT NOT NULL,
  customer_id int(11) NOT NULL,
  identifier varchar(255) NULL DEFAULT NULL,
  send_at datetime NOT NULL,
  fname varchar(255) NOT NULL,
  lname varchar(255) NOT NULL,
  email_address varchar(255) NOT NULL,
  slug varchar(255) NOT NULL,
  merge_tags longtext,
  date_added datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS outgoing_tpl;
CREATE TABLE outgoing_tpl (
  id int(11) NOT NULL AUTO_INCREMENT,
  slug varchar(255) NOT NULL,
  date_added datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_modified datetime DEFAULT NULL,
  PRIMARY KEY (id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE outgoing_tpl_info (
  id INT NOT NULL,
  languages_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  text LONGTEXT NOT NULL,
  PRIMARY KEY (id, languages_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# data

# 1 - Default, 2 - USA, 3 - Spain, 4 - Singapore, 5 - Germany
INSERT INTO address_format VALUES (1, '$name$cr$streets$cr$city, $postcode$cr$statecomma$country', '$city / $country');
INSERT INTO address_format VALUES (2, '$name$cr$streets$cr$city, $state    $postcode$cr$country', '$city, $state / $country');
INSERT INTO address_format VALUES (3, '$name$cr$streets$cr$city$cr$postcode - $statecomma$country', '$state / $country');
INSERT INTO address_format VALUES (4, '$name$cr$streets$cr$city ($postcode)$cr$country', '$postcode / $country');
INSERT INTO address_format VALUES (5, '$name$cr$streets$cr$postcode $city$cr$country', '$city / $country');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Name', 'STORE_NAME', 'CE Phoenix', 'The name of my store', '1', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Owner', 'STORE_OWNER', 'You', 'The name of my store owner', '1', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'you@yours', 'The e-mail address of my store owner', '1', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Template Selection', 'TEMPLATE_SELECTION', 'override', 'The template to use to display the shop.', 1, 5, 'Config::select_template(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br><br><strong>Note: Please remember to update the store zone.</strong>', 1, 6, 'Country::fetch_name', 'Config::select_country(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Zone', 'STORE_ZONE', '18', 'The zone in which my store is located', 1, 7, 'Config::get_zone_name', 'Config::select_zone_by(STORE_COUNTRY, ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Switch To Default Language Currency', 'USE_DEFAULT_LANGUAGE_CURRENCY', 'false', 'Automatically switch to the language\'s currency when it is changed', 1, 10, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', '1', '11', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Cart After Adding Product', 'DISPLAY_CART', 'true', 'Display the shopping cart after adding a product (or return back to their origin)', 1, 14, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Default Search Operator', 'ADVANCED_SEARCH_DEFAULT_OPERATOR', 'and', 'Default search operators', 1, 17, 'Config::select_one([\'and\', \'or\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Address', 'STORE_ADDRESS', 'Address Line 1\nAddress Line 2\nCountry', 'This is the Address of my store used on printable documents and displayed online', 1, 18, 'Config::draw_textarea(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Phone', 'STORE_PHONE', '555-1234', 'This is the phone number of my store used on printable documents and displayed online', 1, 19, 'Config::draw_textarea(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Tax ID', 'STORE_TAX_ID', '', 'This is the Tax ID of my business.', 1, 19, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', '1', '20', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', 'false', 'Display prices with tax included (true) or add the tax at the end (false)', 1, 21, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow all Reviews?', 'ALLOW_ALL_REVIEWS', 'false', 'Allow customers to leave reviews on all products (true) or only on products they have purchased (false)', 1, 22, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Checkout Redirect', 'CHECKOUT_REDIRECT', 'create_account.php', 'At checkout, redirect a new (not logged in) customer to create_account or login page?', 1, 23, 'Config::select_one([\'create_account.php\', \'login.php\'], ', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '5', 'Maximum address book entries a customer is allowed to have', '3', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Admin Items Per Page', 'MAX_DISPLAY_SEARCH_RESULTS', '20', 'Amount of items to list', '3', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', '3', '18', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Product Quantities In Shopping Cart', 'MAX_QTY_IN_CART', '99', 'Maximum number of product quantities that can be added to the shopping cart (0 for no limit)', '3', '19', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Image Required', 'IMAGE_REQUIRED', 'true', 'Enable to display broken images. Good for development.', 4, 8, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Image', 'DEFAULT_IMAGE', '', 'The default image to show if the image is not a valid file.  Leave blank not to show a default.', '4', '5', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_PAYMENT_INSTALLED', 'cod.php;moneyorder.php', 'List of payment module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: cod.php;pm2_checkout.php)', 6, 0, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ORDER_TOTAL_INSTALLED', 'ot_subtotal.php;ot_shipping.php;ot_tax.php;ot_total.php', 'List of order_total module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_SHIPPING_INSTALLED', 'flat.php', 'List of shipping module filenames separated by a semi-colon. This is automatically updated. No need to edit. (Example: ups.php;flat.php;item.php)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ACTION_RECORDER_INSTALLED', 'ar_admin_login.php;ar_contact_us.php;ar_reset_password.php', 'List of action recorder module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_CONTENT_NAVBAR_INSTALLED', 'nb_hamburger_button.php;nb_brand.php;nb_currencies.php;nb_account.php;nb_shopping_cart.php;nb_special_offers.php;nb_search.php', 'List of navbar module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Cash On Delivery Module', 'MODULE_PAYMENT_COD_STATUS', 'True', 'Do you want to accept Cash On Delevery payments?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Payment Zone', 'MODULE_PAYMENT_COD_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 2, 'geo_zone::fetch_name', 'Config::select_geo_zone(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort order of display.', 'MODULE_PAYMENT_COD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Order Status', 'MODULE_PAYMENT_COD_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 1, 'Config::select_order_status(', 'order_status::fetch_name', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Check/Money Order Module', 'MODULE_PAYMENT_MONEYORDER_STATUS', 'True', 'Do you want to accept Check/Money Order payments?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Make Payable to:', 'MODULE_PAYMENT_MONEYORDER_PAYTO', 'Your Store', 'Who should payments be made payable to?', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort order of display.', 'MODULE_PAYMENT_MONEYORDER_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Payment Zone', 'MODULE_PAYMENT_MONEYORDER_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', 6, 4, 'geo_zone::fetch_name', 'Config::select_geo_zone(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Order Status', 'MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', 6, 5, 'Config::select_order_status(', 'order_status::fetch_name', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Flat Shipping', 'MODULE_SHIPPING_FLAT_STATUS', 'True', 'Do you want to offer flat rate shipping?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Shipping Cost', 'MODULE_SHIPPING_FLAT_COST', '5.00', 'The shipping cost for all orders using this shipping method.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Tax Class', 'MODULE_SHIPPING_FLAT_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', 6, 3, 'geo_zone::fetch_name', 'Config::select_tax_class(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Shipping Zone', 'MODULE_SHIPPING_FLAT_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', 6, 4, 'geo_zone::fetch_name', 'Config::select_geo_zone(', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_SHIPPING_FLAT_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Language', 'DEFAULT_LANGUAGE', 'en', 'Default Language', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Delivery Cost', 'MODULE_ORDER_TOTAL_SHIPPING_STATUS', 'True', 'Do you want to display the order delivery cost?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER', '20', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Free Delivery', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING', 'False', 'Do you want to allow free delivery?', 6, 3, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) VALUES ('Free Delivery For Orders Over', 'MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER', '50', 'Provide free delivery for orders over the set amount.', '6', '4', 'currencies->format', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Provide Free Delivery For Orders Made', 'MODULE_ORDER_TOTAL_SHIPPING_DESTINATION', 'national', 'Provide free delivery for orders sent to the set destination.', 6, 5, 'Config::select_one([\'national\', \'international\', \'both\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Sub-Total', 'MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'True', 'Do you want to display the order sub-total cost?', 6, 1,'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '10', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Tax', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'True', 'Do you want to display the order tax value?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '30', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Total', 'MODULE_ORDER_TOTAL_TOTAL_STATUS', 'True', 'Do you want to display the total order value?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '40', 'Sort order of display.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Minutes Per E-Mail', 'MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES', '15', 'Minimum number of minutes to allow 1 e-mail to be sent (eg, 15 for 1 e-mail every 15 minutes)', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Minutes', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_MINUTES', '5', 'Number of minutes to allow login attempts to occur.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Attempts', 'MODULE_ACTION_RECORDER_ADMIN_LOGIN_ATTEMPTS', '3', 'Number of login attempts to allow within the specified period.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Minutes', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES', '5', 'Number of minutes to allow password resets to occur.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Allowed Attempts', 'MODULE_ACTION_RECORDER_RESET_PASSWORD_ATTEMPTS', '1', 'Number of password reset attempts to allow within the specified period.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', '7', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '0', 'What is the weight of typical packaging of small to medium packages?', '7', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '0', 'For 10% enter 10', '7', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Orders not covered by any shipping modules', 'SHIPPING_ALLOW_UNDEFINED_ZONES', 'False', 'Should orders be allowed to shipping addresses not matching defined shipping module shipping zones?', 7, 5, 'Config::select_one([\'True\', \'False\'], ', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Products Per Row', 'IS_PRODUCT_PRODUCTS_DISPLAY_ROW', 'row row-cols-2 row-cols-sm-3 row-cols-md-4', 'How many products should display per Row per viewport?  Default:  XS 2, SM 3, MD and above 4', '8', '110', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Manufacturer Name (0=disable; 1=enable)','PRODUCT_LIST_MANUFACTURER', '0', 'Allow sorting by Manufacturer Name?', '8', '200', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Model (0=disable; 1=enable)', 'PRODUCT_LIST_MODEL', '0', 'Allow sorting by Product Model?', '8', '210', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Name (0=disable; 1=enable)', 'PRODUCT_LIST_NAME', '1', 'Allow sorting by Product Name?', '8', '220', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Price (0=disable; 1=enable)', 'PRODUCT_LIST_PRICE', '1', 'Allow sorting by Product Price', '8', '230', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Stock (0=disable; 1=enable)', 'PRODUCT_LIST_QUANTITY', '0', 'Allow sorting by Product Quantity (Stock)?', '8', '240', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Weight (0=disable; 1=enable)', 'PRODUCT_LIST_WEIGHT', '0', 'Allow sorting by Product Weight?', '8', '250', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Latest Added (0=disable; 1=enable)', 'PRODUCT_LIST_ID', '1', 'Allow sorting by Latest Added?', '8', '260', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Option: Sales (0=disable; 1=enable)', 'PRODUCT_LIST_ORDERED', '1', 'Allow sorting by Number of Sales?', '8', '270', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display buttons in product listing', 'PRODUCT_LIST_BUTTONS', 'False', 'Do you want to display buy and view buttons in the product listing', 8, 290, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Category/Manufacturer Filter (0=disable; 1=enable)', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', '8', '300', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '2', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', '8', '310', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Products Per Page', 'MAX_DISPLAY_PRODUCTS_PER_PAGE', '20', 'Amount of products to list per page', 8, 292, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Page Links', 'MAX_DISPLAY_PAGE_LINKS', '5', 'Number of \'number\' links use for page-sets', 8, 294, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check stock level', 'STOCK_CHECK', 'true', 'Check to see if sufficent stock is available', 9, 1, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', 'true', 'Subtract product in stock by product orders', 9, 2, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', 'true', 'Allow customer to checkout even if there is insufficient stock', 9, 3, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '<i class="fas fa-times fa-2x text-danger"></i>', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Page Parse Time', 'STORE_PAGE_PARSE_TIME', 'false', 'Store the time it takes to parse a page', 10, 1, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Log Destination', 'STORE_PAGE_PARSE_TIME_LOG', '/var/log/www/tep/page_parse_time.log', 'Directory and filename of the page parse time log', '10', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Log Date Format', 'STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S', 'The date format', '10', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display The Page Parse Time', 'DISPLAY_PAGE_PARSE_TIME', 'true', 'Display the page parse time (store page parse time must be enabled)', 10, 4, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Database Queries', 'STORE_DB_TRANSACTIONS', 'false', 'Store the database queries in the page parse time log', 10, 5, 'Config::select_one([\'true\', \'false\'], ', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', 12, 1, 'Config::select_one([\'sendmail\', \'smtp\'],', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', 12, 2, 'Config::select_one([\'LF\', \'CRLF\'],', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', 'false', 'Send e-mails in HTML format', 12, 3, 'Config::select_one([\'true\', \'false\'],', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', 'false', 'Verify e-mail address through a DNS server', 12, 4, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Send E-Mails', 'SEND_EMAILS', 'true', 'Send out e-mails', 12, 5, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail From', 'EMAIL_FROM', 'root@localhost', 'All e-mails will be sent from this address', 12, 6, NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable download', 'DOWNLOAD_ENABLED', 'true', 'Enable the products download functions.', 13, 1, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Download by redirect', 'DOWNLOAD_BY_REDIRECT', 'true', 'Use browser redirection for download. Disable on non-Unix systems.', 13, 2, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Expiry delay (days)' ,'DOWNLOAD_MAX_DAYS', '7', 'Set number of days before the download link expires. 0 means no limit.', 13, 3, '', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Maximum number of downloads' ,'DOWNLOAD_MAX_COUNT', '5', 'Set the maximum number of downloads. 0 means no download authorized.', 13, 4, '', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable GZip Compression', 'GZIP_COMPRESSION', 'false', 'Enable HTTP GZip compression.', 14, 1, 'Config::select_one([\'true\', \'false\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Compression Level', 'GZIP_LEVEL', '5', 'Use this compression level 0-9 (0 = minimum, 9 = maximum).', '14', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Force Cookie Use', 'SESSION_FORCE_COOKIE_USE', 'False', 'Force the use of sessions when cookies are only enabled.', 15, 2, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check SSL Session ID', 'SESSION_CHECK_SSL_SESSION_ID', 'False', 'Validate the SSL_SESSION_ID on every secure HTTPS page request.', 15, 3, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check User Agent', 'SESSION_CHECK_USER_AGENT', 'False', 'Validate the clients browser user agent on every page request.', 15, 4, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Check IP Address', 'SESSION_CHECK_IP_ADDRESS', 'False', 'Validate the clients IP address on every page request.', 15, 5, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Prevent Spider Sessions', 'SESSION_BLOCK_SPIDERS', 'True', 'Prevent known spiders from starting a session.', 15, 6, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Recreate Session', 'SESSION_RECREATE', 'True', 'Recreate the session to generate a new session ID when the customer logs on or creates an account.', 15, 7, 'Config::select_one([\'True\', \'False\'], ', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Last Update Check Time', 'LAST_UPDATE_CHECK_TIME', '', 'Last time a check for new versions of CE Phoenix was run', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Logo', 'STORE_LOGO', 'store_logo.png', 'This is the filename of your Store Logo.  This should be updated at admin > configuration > Store Logo', 6, 1000, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mini Logo', 'MINI_LOGO', 'mini_logo.png', 'This is the filename of your Mini Logo.  This should be updated at admin > configuration > Store Logo', 6, 1100, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Favicon', 'FAVICON_LOGO', 'favicon.png', 'This is the filename of your Favicon.  This should be updated at admin > configuration > Store Logo', 6, 1200, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Bootstrap Container', 'BOOTSTRAP_CONTAINER', 'container-xl', 'What type of container should the page content be shown in? See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/layout/containers/"><u>layout/#containers</u></a>.', 16, 1, 'Config::select_one([\'container\', \'container-sm\', \'container-md\', \'container-lg\', \'container-xl\', \'container-xxl\', \'container-fluid\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Bootstrap Content', 'BOOTSTRAP_CONTENT', '8', 'What width should the page content default to?  (8 = two thirds width, 6 = half width, 4 = one third width) Note that the Side Column(s) will adjust automatically.', 16, 2, 'Config::select_one([\'10\', \'8\', \'6\', \'5\', \'3\', \'2\'], ', NOW());

INSERT INTO configuration_group VALUES ('1', 'My Store', 'General information about my store', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=My_Store', '{"ADDONS_FREE":"https:\/\/phoenixcart.org\/forum\/app.php\/addons\/free\/templates-28","ADDONS_COMMERCIAL":"https:\/\/phoenixcart.org\/forum\/app.php\/addons\/commercial\/templates-31","ADDONS_PRO":"https:\/\/phoenixcart.org\/forum\/app.php\/addons\/supporters\/templates-47"}', '1', '1');
INSERT INTO configuration_group VALUES ('3', 'Maximum Values', 'The maximum values for functions / data', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Maximum_Values', '', '3', '1');
INSERT INTO configuration_group VALUES ('4', 'Images', 'Image parameters', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Images', '', '4', '1');
INSERT INTO configuration_group VALUES ('6', 'Module Options', 'Hidden from configuration', 'https://phoenixcart.org/phoenixcartwiki/index.php', '', '6', '0');
INSERT INTO configuration_group VALUES ('7', 'Shipping/Packaging', 'Shipping options available at my store', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Shipping/Packaging', '', '7', '1');
INSERT INTO configuration_group VALUES ('8', 'Product Listing', 'Product Listing configuration options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Product_Listing', '', '8', '1');
INSERT INTO configuration_group VALUES ('9', 'Stock', 'Stock configuration options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Stock', '', '9', '1');
INSERT INTO configuration_group VALUES ('10', 'Logging', 'Logging configuration options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Logging', '', '10', '1');
INSERT INTO configuration_group VALUES ('11', 'Cache', 'Caching configuration options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Cache', '', '11', '0');
INSERT INTO configuration_group VALUES ('12', 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=E-Mail_Options', '', '12', '1');
INSERT INTO configuration_group VALUES ('13', 'Download', 'Downloadable products options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Download', '', '13', '1');
INSERT INTO configuration_group VALUES ('14', 'GZip Compression', 'GZip compression options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=GZip_Compression', '', '14', '1');
INSERT INTO configuration_group VALUES ('15', 'Sessions', 'Session options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Sessions', '', '15', '1');
INSERT INTO configuration_group VALUES ('16', 'Bootstrap Setup', 'Basic Bootstrap Options', 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Bootstrap_Setup', '', '16', '1');

INSERT INTO countries VALUES (1,'Afghanestan','AF','AFG','1','1');
INSERT INTO countries VALUES (2,'Shqipëria','AL','ALB','1','1');
INSERT INTO countries VALUES (3,'Dzayer','DZ','DZA','1','1');
INSERT INTO countries VALUES (4,'American Samoa','AS','ASM','1','1');
INSERT INTO countries VALUES (5,'Andorra','AD','AND','1','1');
INSERT INTO countries VALUES (6,'Angola','AO','AGO','1','1');
INSERT INTO countries VALUES (7,'Anguilla','AI','AIA','1','1');
INSERT INTO countries VALUES (8,'Antarctica','AQ','ATA','1','1');
INSERT INTO countries VALUES (9,'Antigua and Barbuda','AG','ATG','1','1');
INSERT INTO countries VALUES (10,'Argentina','AR','ARG','1','1');
INSERT INTO countries VALUES (11,'Hayastán','AM','ARM','1','1');
INSERT INTO countries VALUES (12,'Aruba','AW','ABW','1','1');
INSERT INTO countries VALUES (13,'Australia','AU','AUS','1','1');
INSERT INTO countries VALUES (14,'Österreich','AT','AUT','5','1');
INSERT INTO countries VALUES (15,'Azərbaycan','AZ','AZE','1','1');
INSERT INTO countries VALUES (16,'Bahamas','BS','BHS','1','1');
INSERT INTO countries VALUES (17,'Al-Bahrayn','BH','BHR','1','1');
INSERT INTO countries VALUES (18,'Bangladesh','BD','BGD','1','1');
INSERT INTO countries VALUES (19,'Barbados','BB','BRB','1','1');
INSERT INTO countries VALUES (20,'Bielaruś','BY','BLR','1','1');
INSERT INTO countries VALUES (21,'België','BE','BEL','1','1');
INSERT INTO countries VALUES (22,'Belize','BZ','BLZ','1','1');
INSERT INTO countries VALUES (23,'Bénin','BJ','BEN','1','1');
INSERT INTO countries VALUES (24,'Bermuda','BM','BMU','1','1');
INSERT INTO countries VALUES (25,'Druk Yul','BT','BTN','1','1');
INSERT INTO countries VALUES (26,'Bolivia','BO','BOL','1','1');
INSERT INTO countries VALUES (27,'Bosnia I Hercegovína','BA','BIH','1','1');
INSERT INTO countries VALUES (28,'Botswana','BW','BWA','1','1');
INSERT INTO countries VALUES (29,'Bouvet Island','BV','BVT','1','1');
INSERT INTO countries VALUES (30,'Brasil','BR','BRA','1','1');
INSERT INTO countries VALUES (31,'British Indian Ocean Territory','IO','IOT','1','1');
INSERT INTO countries VALUES (32,'Brunei Darussalam','BN','BRN','1','1');
INSERT INTO countries VALUES (33,'Bulgariya','BG','BGR','1','1');
INSERT INTO countries VALUES (34,'Burkina Faso','BF','BFA','1','1');
INSERT INTO countries VALUES (35,'Burundi','BI','BDI','1','1');
INSERT INTO countries VALUES (36,'Kampuchea','KH','KHM','1','1');
INSERT INTO countries VALUES (37,'Cameroon','CM','CMR','1','1');
INSERT INTO countries VALUES (38,'Canada','CA','CAN','1','1');
INSERT INTO countries VALUES (39,'Cabo Verde','CV','CPV','1','1');
INSERT INTO countries VALUES (40,'Cayman Islands','KY','CYM','1','1');
INSERT INTO countries VALUES (41,'République Centrafricaine','CF','CAF','1','1');
INSERT INTO countries VALUES (42,'Tchad','TD','TCD','1','1');
INSERT INTO countries VALUES (43,'Chile','CL','CHL','1','1');
INSERT INTO countries VALUES (44,'Zhōngguó','CN','CHN','1','1');
INSERT INTO countries VALUES (45,'Christmas Island','CX','CXR','1','1');
INSERT INTO countries VALUES (46,'Cocos (Keeling) Islands','CC','CCK','1','1');
INSERT INTO countries VALUES (47,'Colombia','CO','COL','1','1');
INSERT INTO countries VALUES (48,'Comoros','KM','COM','1','1');
INSERT INTO countries VALUES (49,'République du Congo','CG','COG','1','1');
INSERT INTO countries VALUES (50,'Cook Islands','CK','COK','1','1');
INSERT INTO countries VALUES (51,'Costa Rica','CR','CRI','1','1');
INSERT INTO countries VALUES (52,'Côte d\'Ivoire','CI','CIV','1','1');
INSERT INTO countries VALUES (53,'Hrvatska','HR','HRV','1','1');
INSERT INTO countries VALUES (54,'Cuba','CU','CUB','1','1');
INSERT INTO countries VALUES (55,'Κύπρος','CY','CYP','1','1');
INSERT INTO countries VALUES (56,'Česko','CZ','CZE','1','1');
INSERT INTO countries VALUES (57,'Danmark','DK','DNK','1','1');
INSERT INTO countries VALUES (58,'Djibouti','DJ','DJI','1','1');
INSERT INTO countries VALUES (59,'Dominica','DM','DMA','1','1');
INSERT INTO countries VALUES (60,'República Dominicana','DO','DOM','1','1');
INSERT INTO countries VALUES (61, 'Timor-Lester', 'TL', 'TLS', '1','1');
INSERT INTO countries VALUES (62,'Ecuador','EC','ECU','1','1');
INSERT INTO countries VALUES (63,'Misr','EG','EGY','1','1');
INSERT INTO countries VALUES (64,'El Salvador','SV','SLV','1','1');
INSERT INTO countries VALUES (65,'Guinea Ecuatorial','GQ','GNQ','1','1');
INSERT INTO countries VALUES (66,'Iritriya','ER','ERI','1','1');
INSERT INTO countries VALUES (67,'Eesti','EE','EST','1','1');
INSERT INTO countries VALUES (68,'Ityop\'ia','ET','ETH','1','1');
INSERT INTO countries VALUES (69,'Falkland Islands (Malvinas)','FK','FLK','1','1');
INSERT INTO countries VALUES (70,'Faroe Islands','FO','FRO','1','1');
INSERT INTO countries VALUES (71,'Fiji','FJ','FJI','1','1');
INSERT INTO countries VALUES (72,'Suomi','FI','FIN','1','1');
INSERT INTO countries VALUES (73,'France','FR','FRA','1','1');
INSERT INTO countries VALUES (75,'Guyanne française','GF','GUF','1','1');
INSERT INTO countries VALUES (76,'Polynésie française','PF','PYF','1','1');
INSERT INTO countries VALUES (77,'Terres australes et antarctiques françaises','TF','ATF','1','1');
INSERT INTO countries VALUES (78,'République gabonaise','GA','GAB','1','1');
INSERT INTO countries VALUES (79,'Gambia','GM','GMB','1','1');
INSERT INTO countries VALUES (80,'Sak\'art\'velo','GE','GEO','1','1');
INSERT INTO countries VALUES (81,'Deutschland','DE','DEU','5','1');
INSERT INTO countries VALUES (82,'Ghana','GH','GHA','1','1');
INSERT INTO countries VALUES (83,'Gibraltar','GI','GIB','1','1');
INSERT INTO countries VALUES (84,'Ελλάς','GR','GRC','1','1');
INSERT INTO countries VALUES (85,'Greenland','GL','GRL','1','1');
INSERT INTO countries VALUES (86,'Grenada','GD','GRD','1','1');
INSERT INTO countries VALUES (87,'Guadeloupe','GP','GLP','1','1');
INSERT INTO countries VALUES (88,'Guam','GU','GUM','1','1');
INSERT INTO countries VALUES (89,'Guatemala','GT','GTM','1','1');
INSERT INTO countries VALUES (90,'Guinée','GN','GIN','1','1');
INSERT INTO countries VALUES (91,'Guiné-Bissau','GW','GNB','1','1');
INSERT INTO countries VALUES (92,'Guyana','GY','GUY','1','1');
INSERT INTO countries VALUES (93,'Haïti','HT','HTI','1','1');
INSERT INTO countries VALUES (94,'Heard and Mc Donald Islands','HM','HMD','1','1');
INSERT INTO countries VALUES (95,'Honduras','HN','HND','1','1');
INSERT INTO countries VALUES (96,'Hong Kong','HK','HKG','1','1');
INSERT INTO countries VALUES (97,'Magyarország','HU','HUN','1','1');
INSERT INTO countries VALUES (98,'Ísland','IS','ISL','1','1');
INSERT INTO countries VALUES (99,'India','IN','IND','1','1');
INSERT INTO countries VALUES (100,'Indonesia','ID','IDN','1','1');
INSERT INTO countries VALUES (101,'Īrān (Islamic Republic of)','IR','IRN','1','1');
INSERT INTO countries VALUES (102,'Al-\'Iraq','IQ','IRQ','1','1');
INSERT INTO countries VALUES (103,'Éire','IE','IRL','1','1');
INSERT INTO countries VALUES (104,'Yisrā\'el','IL','ISR','1','1');
INSERT INTO countries VALUES (105,'Italia','IT','ITA','1','1');
INSERT INTO countries VALUES (106,'Jamaica','JM','JAM','1','1');
INSERT INTO countries VALUES (107,'Nippon','JP','JPN','1','1');
INSERT INTO countries VALUES (108,'Al-\'Urdun','JO','JOR','1','1');
INSERT INTO countries VALUES (109,'Qazaqstan Қазақстан','KZ','KAZ','1','1');
INSERT INTO countries VALUES (110,'Kenya','KE','KEN','1','1');
INSERT INTO countries VALUES (111,'Tungaru','KI','KIR','1','1');
INSERT INTO countries VALUES (112,'Chosŏn','KP','PRK','1','1');
INSERT INTO countries VALUES (113,'Hanguk','KR','KOR','1','1');
INSERT INTO countries VALUES (114,'Dawlat ul-Kuwayt','KW','KWT','1','1');
INSERT INTO countries VALUES (115,'Kyrgyzstan Кыргызстан','KG','KGZ','1','1');
INSERT INTO countries VALUES (116,'Lao','LA','LAO','1','1');
INSERT INTO countries VALUES (117,'Latvija','LV','LVA','1','1');
INSERT INTO countries VALUES (118,'Lubnān','LB','LBN','1','1');
INSERT INTO countries VALUES (119,'Lesotho','LS','LSO','1','1');
INSERT INTO countries VALUES (120,'Liberia','LR','LBR','1','1');
INSERT INTO countries VALUES (121,'Libya','LY','LBY','1','1');
INSERT INTO countries VALUES (122,'Liechtenstein','LI','LIE','1','1');
INSERT INTO countries VALUES (123,'Lietuva','LT','LTU','1','1');
INSERT INTO countries VALUES (124,'Luxembourg','LU','LUX','1','1');
INSERT INTO countries VALUES (125,'Macau','MO','MAC','1','1');
INSERT INTO countries VALUES (126,'North Macedonia','MK','MKD','1','1');
INSERT INTO countries VALUES (127,'Madagascar','MG','MDG','1','1');
INSERT INTO countries VALUES (128,'Malawi','MW','MWI','1','1');
INSERT INTO countries VALUES (129,'Malaysia','MY','MYS','1','1');
INSERT INTO countries VALUES (130,'Dhivehi Raajje','MV','MDV','1','1');
INSERT INTO countries VALUES (131,'Mali','ML','MLI','1','1');
INSERT INTO countries VALUES (132,'Malta','MT','MLT','1','1');
INSERT INTO countries VALUES (133,'Marshall Islands','MH','MHL','1','1');
INSERT INTO countries VALUES (134,'Martinique','MQ','MTQ','1','1');
INSERT INTO countries VALUES (135,'Muritan','MR','MRT','1','1');
INSERT INTO countries VALUES (136,'Mauritius','MU','MUS','1','1');
INSERT INTO countries VALUES (137,'Mayotte','YT','MYT','1','1');
INSERT INTO countries VALUES (138,'México','MX','MEX','1','1');
INSERT INTO countries VALUES (139,'Micronesia, Federated States of','FM','FSM','1','1');
INSERT INTO countries VALUES (140,'Moldova','MD','MDA','1','1');
INSERT INTO countries VALUES (141,'Monaca','MC','MCO','1','1');
INSERT INTO countries VALUES (142,'Mongol Uls Монгол Улс','MN','MNG','1','1');
INSERT INTO countries VALUES (143,'Montserrat','MS','MSR','1','1');
INSERT INTO countries VALUES (144,'Al-maɣréb','MA','MAR','1','1');
INSERT INTO countries VALUES (145,'Moçambique','MZ','MOZ','1','1');
INSERT INTO countries VALUES (146,'Myanma','MM','MMR','1','1');
INSERT INTO countries VALUES (147,'Namibia','NA','NAM','1','1');
INSERT INTO countries VALUES (148,'Naoero','NR','NRU','1','1');
INSERT INTO countries VALUES (149,'Nepāl','NP','NPL','1','1');
INSERT INTO countries VALUES (150,'Nederland','NL','NLD','1','1');
INSERT INTO countries VALUES (151,'Netherlands Antilles','AN','ANT','1','1');
INSERT INTO countries VALUES (152,'Nouvelle-Calédonie','NC','NCL','1','1');
INSERT INTO countries VALUES (153,'New Zealand','NZ','NZL','1','1');
INSERT INTO countries VALUES (154,'Nicaragua','NI','NIC','1','1');
INSERT INTO countries VALUES (155,'Niger','NE','NER','1','1');
INSERT INTO countries VALUES (156,'Nigeria','NG','NGA','1','1');
INSERT INTO countries VALUES (157,'Niue','NU','NIU','1','1');
INSERT INTO countries VALUES (158,'Norfolk Island','NF','NFK','1','1');
INSERT INTO countries VALUES (159,'Northern Mariana Islands','MP','MNP','1','1');
INSERT INTO countries VALUES (160,'Norge','NO','NOR','1','1');
INSERT INTO countries VALUES (161,'\'Umān','OM','OMN','1','1');
INSERT INTO countries VALUES (162,'Pākistān','PK','PAK','1','1');
INSERT INTO countries VALUES (163,'Palau','PW','PLW','1','1');
INSERT INTO countries VALUES (164,'Panamá','PA','PAN','1','1');
INSERT INTO countries VALUES (165,'Papua New Guinea','PG','PNG','1','1');
INSERT INTO countries VALUES (166,'Paraguay','PY','PRY','1','1');
INSERT INTO countries VALUES (167,'Perú','PE','PER','1','1');
INSERT INTO countries VALUES (168,'Philippines','PH','PHL','1','1');
INSERT INTO countries VALUES (169,'Pitcairn','PN','PCN','1','1');
INSERT INTO countries VALUES (170,'Polska','PL','POL','1','1');
INSERT INTO countries VALUES (171,'Portugal','PT','PRT','1','1');
INSERT INTO countries VALUES (172,'Puerto Rico','PR','PRI','1','1');
INSERT INTO countries VALUES (173,'Qaṭar','QA','QAT','1','1');
INSERT INTO countries VALUES (174,'Reunion','RE','REU','1','1');
INSERT INTO countries VALUES (175,'România','RO','ROM','1','1');
INSERT INTO countries VALUES (176,'Россия','RU','RUS','1','1');
INSERT INTO countries VALUES (177,'Rwanda','RW','RWA','1','1');
INSERT INTO countries VALUES (178,'Saint Kitts and Nevis','KN','KNA','1','1');
INSERT INTO countries VALUES (179,'Saint Lucia','LC','LCA','1','1');
INSERT INTO countries VALUES (180,'Saint Vincent and the Grenadines','VC','VCT','1','1');
INSERT INTO countries VALUES (181,'Samoa','WS','WSM','1','1');
INSERT INTO countries VALUES (182,'San Marino','SM','SMR','1','1');
INSERT INTO countries VALUES (183,'São Tomé e Principe','ST','STP','1','1');
INSERT INTO countries VALUES (184,'Al-\'Arabiyyah as Sa\'ūdiyyah','SA','SAU','1','1');
INSERT INTO countries VALUES (185,'Sénégal','SN','SEN','1','1');
INSERT INTO countries VALUES (186,'Seychelles','SC','SYC','1','1');
INSERT INTO countries VALUES (187,'Sierra Leone','SL','SLE','1','1');
INSERT INTO countries VALUES (188,'Singapore','SG','SGP', '4','1');
INSERT INTO countries VALUES (189,'Slovensko','SK','SVK','1','1');
INSERT INTO countries VALUES (190,'Slovenija','SI','SVN','1','1');
INSERT INTO countries VALUES (191,'Solomon Islands','SB','SLB','1','1');
INSERT INTO countries VALUES (192,'Soomaaliya aş-Şūmāl','SO','SOM','1','1');
INSERT INTO countries VALUES (193,'South Africa','ZA','ZAF','1','1');
INSERT INTO countries VALUES (194,'South Georgia and the South Sandwich Islands','GS','SGS','1','1');
INSERT INTO countries VALUES (195,'España','ES','ESP','3','1');
INSERT INTO countries VALUES (196,'Sri Lankā','LK','LKA','1','1');
INSERT INTO countries VALUES (197,'St. Helena','SH','SHN','1','1');
INSERT INTO countries VALUES (198,'St. Pierre and Miquelon','PM','SPM','1','1');
INSERT INTO countries VALUES (199,'As-Sudan','SD','SDN','1','1');
INSERT INTO countries VALUES (200,'Surinam','SR','SUR','1','1');
INSERT INTO countries VALUES (201,'Svalbard and Jan Mayen Islands','SJ','SJM','1','1');
INSERT INTO countries VALUES (202,'eSwatini','SZ','SWZ','1','1');
INSERT INTO countries VALUES (203,'Sverige','SE','SWE','1','1');
INSERT INTO countries VALUES (204,'Switzerland','CH','CHE','1','1');
INSERT INTO countries VALUES (205,'Suriyah','SY','SYR','1','1');
INSERT INTO countries VALUES (206,'Taiwan','TW','TWN','1','1');
INSERT INTO countries VALUES (207,'Tojikistan','TJ','TJK','1','1');
INSERT INTO countries VALUES (208,'Tanzania, United Republic of','TZ','TZA','1','1');
INSERT INTO countries VALUES (209,'Prathet Thai','TH','THA','1','1');
INSERT INTO countries VALUES (210,'Togo','TG','TGO','1','1');
INSERT INTO countries VALUES (211,'Tokelau','TK','TKL','1','1');
INSERT INTO countries VALUES (212,'Tonga','TO','TON','1','1');
INSERT INTO countries VALUES (213,'Trinidad and Tobago','TT','TTO','1','1');
INSERT INTO countries VALUES (214,'Tunes','TN','TUN','1','1');
INSERT INTO countries VALUES (215,'Türkiye','TR','TUR','1','1');
INSERT INTO countries VALUES (216,'Türkmenistan','TM','TKM','1','1');
INSERT INTO countries VALUES (217,'Turks and Caicos Islands','TC','TCA','1','1');
INSERT INTO countries VALUES (218,'Tuvalu','TV','TUV','1','1');
INSERT INTO countries VALUES (219,'Uganda','UG','UGA','1','1');
INSERT INTO countries VALUES (220,'Ukraїna','UA','UKR','1','1');
INSERT INTO countries VALUES (221,'Al-\'Imārat Al-\'Arabiyyah Al-Muttaḥidah','AE','ARE','1','1');
INSERT INTO countries VALUES (222,'United Kingdom','GB','GBR','1','1');
INSERT INTO countries VALUES (223,'United States','US','USA', '2','1');
INSERT INTO countries VALUES (224,'United States Minor Outlying Islands','UM','UMI','1','1');
INSERT INTO countries VALUES (225,'Uruguay','UY','URY','1','1');
INSERT INTO countries VALUES (226,'O\'zbekiston','UZ','UZB','1','1');
INSERT INTO countries VALUES (227,'Vanuatu','VU','VUT','1','1');
INSERT INTO countries VALUES (228,'Città del Vaticano','VA','VAT','1','1');
INSERT INTO countries VALUES (229,'Venezuela','VE','VEN','1','1');
INSERT INTO countries VALUES (230,'Việt Nam','VN','VNM','1','1');
INSERT INTO countries VALUES (231,'Virgin Islands (British)','VG','VGB','1','1');
INSERT INTO countries VALUES (232,'Virgin Islands (U.S.)','VI','VIR','1','1');
INSERT INTO countries VALUES (233,'Wallis and Futuna Islands','WF','WLF','1','1');
INSERT INTO countries VALUES (234,'Western Sahara','EH','ESH','1','1');
INSERT INTO countries VALUES (235,'Al-Yaman','YE','YEM','1','1');
INSERT INTO countries VALUES (236, 'Srbija', 'RS', 'SRB', '1','1');
INSERT INTO countries VALUES (237, 'République démocratique du Congo', 'CD', 'COD', '1','1');
INSERT INTO countries VALUES (238,'Zambia','ZM','ZMB','1','1');
INSERT INTO countries VALUES (239,'Zimbabwe','ZW','ZWE','1','1');
INSERT INTO countries VALUES (240, 'Åland Islands', 'AX', 'ALA', '1','1');
INSERT INTO countries VALUES (241, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'BES', '1','1');
INSERT INTO countries VALUES (242, 'Curaçao', 'CW', 'CUW', '1','1');
INSERT INTO countries VALUES (243, 'Crna Gora', 'ME', 'MNE', '1','1');
INSERT INTO countries VALUES (244, 'Filasṭīn', 'PS', 'PSE', '1','1');
INSERT INTO countries VALUES (245, 'Saint Barthélemy', 'BL', 'BLM', '1','1');
INSERT INTO countries VALUES (246, 'Saint Martin (French part)', 'MF', 'MAF', '1','1');
INSERT INTO countries VALUES (247, 'Sint Maarten (Dutch part)', 'SX', 'SXM', '1','1');
INSERT INTO countries VALUES (248, 'South Sudan', 'SS', 'SSD', '1','1');
INSERT INTO countries VALUES (249, 'Timor-Lester', 'TL', 'TLS', '1','0');
INSERT INTO countries VALUES (250, 'Guernsey', 'GG', 'GGY', '1', '1');
INSERT INTO countries VALUES (251, 'Jersey', 'JE', 'JEY', '1', '1');
INSERT INTO countries VALUES (252, 'Isle of Man', 'IM', 'IMN', '1', '1');

INSERT INTO currencies VALUES (1,'U.S. Dollar','USD','$','','.',',','2','1.0000', now());
INSERT INTO currencies VALUES (2,'Euro','EUR','','€','.',',','2','0.8522', now());

INSERT INTO customer_data_groups_sequence (customer_data_groups_id) VALUES (1), (2), (3), (4), (5), (6);

INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) VALUES (1, 1, 'Your Personal Information', 10, 'col-12 col-md-6');
INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) VALUES (2, 1, 'Your Address', 20, 'col-12 col-md-6');
INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) VALUES (3, 1, 'Your Contact Information', 30, 'col-12 col-md-6');
INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) VALUES (4, 1, 'Company Details', 15, 'col-12 col-md-6');
INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) VALUES (5, 1, 'Options', 50, 'col-12 col-md-6');
INSERT INTO customer_data_groups (customer_data_groups_id, language_id, customer_data_groups_name, cdg_vertical_sort_order, customer_data_groups_width) VALUES (6, 1, 'Your Password', 60, 'col-12 col-md-6');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('admin', 'system', 'hrefLink', '_01_href_link', '', 'Href::hook');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'hrefLink', '_01_href_link', '', 'Href::hook');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_01_project_version', 'application_surface', 'project_version');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_02_request', 'application_surface', 'request');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_03_read_configuration', 'application_surface', 'read_configuration');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_04_linker', 'Loader', 'Linker');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_09_gzip', 'application_surface', 'gzip');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_10_start_session', 'application_surface', 'start_session');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_11_check_ssl_session_id', 'Application', 'check_ssl_session_id');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_12_check_user_agent', 'Application', 'check_user_agent');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_14_check_ip', 'Application', 'check_ip');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_15_cart', 'Application', 'ensure_session_cart');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_16_set_session_language', 'Application', 'set_session_language');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_17_fix_numeric_locale', 'Application', 'fix_numeric_locale');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_18_set_currency', 'currencies', 'set_currency');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_19_ensure_navigation_history', 'Application', 'ensure_navigation_history');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_20_messageStack', 'Loader', 'messageStack');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_21_customer_data', 'Loader', 'customer_data');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_22_customer', 'Application', 'set_customer_if_identified');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_23_parse_actions', 'application_surface', 'parse_actions');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_24_whos_online', '', 'whos_online::update');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_26_template_title', 'Application', 'set_template_title');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_27_expire_specials', '', 'specials::expire');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_29_category_path', 'application_surface', 'category_path');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'system', 'startApplication', '_30_register_page_hook', 'hooks', 'register_page');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'siteWide', 'injectProductCard', '_10_inject_product_card', '', 'product_card::inject');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout', 'checkoutStart', '_01_register_stages', 'Checkout', 'register_stages');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout', 'startCheckout', '_01_require_login', '', 'Login::require');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation_stage', 'startCheckout', '_01_require_login', 'Checkout', 'require_login');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout', 'startCheckout', '_02_guarantee_cart', 'Checkout', 'guarantee_cart');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_shipping', 'startCheckout', '_03_guarantee_cart_id', 'Checkout', 'guarantee_cart_id');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_shipping', 'startCheckout', '_04_validate', 'Checkout', 'validate_sendto');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_payment_stage', 'startCheckout', '_04_validate', 'Checkout', 'validate');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_payment', 'startCheckout', '_05_validate_payment', 'Checkout', 'validate_billto');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation', 'startCheckout', '_05_validate_payment', 'Checkout', 'guarantee_payment');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_05_validate_payment', 'Checkout', 'validate_payment');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation_stage', 'startCheckout', '_06_initialize_payment_module', 'Checkout', 'initialize_payment_module');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation_stage', 'startCheckout', '_07_initialize_shipping_module', 'Checkout', 'initialize_shipping_module');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout', 'startCheckout', '_08_order', 'Loader', 'order');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_payment_stage', 'startCheckout', '_09_check_stock', 'checkout_surface', 'check_stock');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_shipping', 'startCheckout', '_10_virtual_shipping', 'Checkout', 'skip_shipping');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_shipping_address', 'startCheckout', '_10_virtual_shipping', 'Checkout', 'skip_shipping');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_payment', 'startCheckout', '_10_initialize_payment_modules', 'Checkout', 'initialize_payment_modules');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation_stage', 'startCheckout', '_10_update_payment_modules', 'Checkout', 'update_payment_module');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation_stage', 'startCheckout', '_11_set_order_totals', 'Checkout', 'set_order_totals');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation', 'startCheckout', '_12_prepare_payment', 'Checkout', 'preconfirm_payment');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_12_prepare_payment', 'Checkout', 'prepare_payment');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_14_insert_order', 'checkout_surface', 'insert_order');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_20_after', 'pipeline_surface', 'after');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'after', 'afterStart', '_21_update_stock', 'Checkout', 'update_stock');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'after', 'afterStart', '_22_update_products_ordered', 'Checkout', 'update_products_ordered');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'after', 'afterStart', '_23_notify', 'Checkout', 'notify');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_30_insert_history', 'checkout_surface', 'insert_history');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_31_conclude_payment', 'Checkout', 'conclude_payment');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', '_40_reset', 'pipeline_surface', 'reset');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reset', 'resetStart', '_41_reset_cart', 'Checkout', 'reset_cart');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reset', 'resetStart', '_42_unset_sendto', 'session_eraser', 'sendto');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reset', 'resetStart', '_43_unset_billto', 'session_eraser', 'billto');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reset', 'resetStart', '_44_unset_shipping', 'session_eraser', 'shipping');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reset', 'resetStart', '_45_unset_payment', 'session_eraser', 'payment');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reset', 'resetStart', '_46_unset_comments', 'session_eraser', 'comments');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_process', 'startCheckout', 'zz_redirect_success', 'Checkout', 'redirect_success');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'siteWide', 'postRegistration', '_01_post_login', 'Login', 'hook');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'siteWide', 'postLogin', '_01_recreate_session', '', 'Session::recreate');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'create_account', 'postLogin', '_02_set_customer_id', 'Login', 'add_customer_id');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'login', 'postLogin', '_02_set_customer_id', 'Login', 'set_customer_id');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'login', 'postLogin', '_03_log', 'Login', 'log');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'siteWide', 'postLogin', '_04_reset_token', '', 'Form::reset_session_token');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'login', 'postLogin', '_05_restore_cart', 'cart', 'restore_contents');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'login', 'postLogin', 'zz_redirect', 'navigation', 'redirect_to_snapshot');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'create_account', 'postRegistration', '_02_restore_cart', 'cart', 'restore_contents');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'create_account', 'postRegistration', '_03_notify', 'Login', 'notify');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'create_account', 'postRegistration', 'zz_redirect', 'Login', 'redirect_success');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'logoff', 'resetStart', '_40_unset_customer_id', 'session_eraser', 'customer_id');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'logoff', 'resetStart', '_41_unset_customer', 'global_eraser', 'customer');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_success', 'siteWideStart', 'notify', 'cm_cs_product_notifications', 'process');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'index', 'siteWideStart', 'category_depth', '', 'category_tree::set_global_depth');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'loginRequired', 'loginRequiredStart', 'zz_redirect', '', 'Login::require');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reviewable', 'reviewableStart', '_01_not_reviewed', '', 'Reviews::verify_not_reviewed');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'reviewable', 'reviewableStart', '_02_bought', '', 'Reviews::verify_buyer');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('admin', 'action_recorder', 'expireAction', 'zz_expire', '', 'actionRecorderAdmin::notify_expiration');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('admin', 'modules', 'injectBodyStart', 'update_table_definition', '', 'cfg_modules::hook_injectBodyStart');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('admin', 'orders', 'updateOrderAction', 'zz_message_update', '', 'Orders::message_update');

INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'advanced_search_result', 'productListing', 'filter_category_brand', 'product_searcher', 'hook');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation', 'injectFormDisplay', 'display_matc', 'cd_matc', 'hook');
INSERT INTO hooks (hooks_site, hooks_group, hooks_action, hooks_code, hooks_class, hooks_method) VALUES ('shop', 'checkout_confirmation', 'injectFormVerify', 'verify_matc', 'cd_matc', 'is_checked');

INSERT INTO languages VALUES (1,'English','en','icon.gif','english',1);

INSERT INTO orders_status VALUES ( '1', '1', 'Pending', '1', '0');
INSERT INTO orders_status VALUES ( '2', '1', 'Processing', '1', '1');
INSERT INTO orders_status VALUES ( '3', '1', 'Delivered', '1', '1');

INSERT INTO sec_directory_whitelist values (null, 'images');
INSERT INTO sec_directory_whitelist values (null, 'includes/work');
INSERT INTO sec_directory_whitelist values (null, 'pub');

INSERT INTO tax_class VALUES (1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', now(), now());

# USA/Florida
INSERT INTO tax_rates VALUES (1, 1, 1, 1, 7.0, 'FL TAX 7.0%', now(), now());
INSERT INTO geo_zones (geo_zone_id,geo_zone_name,geo_zone_description,date_added) VALUES (1,"Florida","Florida local sales tax zone",now());
INSERT INTO zones_to_geo_zones (association_id,zone_country_id,zone_id,geo_zone_id,date_added) VALUES (1,223,18,1,now());

# USA
INSERT INTO zones VALUES (1,223,'AL','Alabama');
INSERT INTO zones VALUES (2,223,'AK','Alaska');
INSERT INTO zones VALUES (3,223,'AS','American Samoa');
INSERT INTO zones VALUES (4,223,'AZ','Arizona');
INSERT INTO zones VALUES (5,223,'AR','Arkansas');
INSERT INTO zones VALUES (6,223,'AF','Armed Forces Africa');
INSERT INTO zones VALUES (7,223,'AA','Armed Forces Americas');
INSERT INTO zones VALUES (8,223,'AC','Armed Forces Canada');
INSERT INTO zones VALUES (9,223,'AE','Armed Forces Europe');
INSERT INTO zones VALUES (10,223,'AM','Armed Forces Middle East');
INSERT INTO zones VALUES (11,223,'AP','Armed Forces Pacific');
INSERT INTO zones VALUES (12,223,'CA','California');
INSERT INTO zones VALUES (13,223,'CO','Colorado');
INSERT INTO zones VALUES (14,223,'CT','Connecticut');
INSERT INTO zones VALUES (15,223,'DE','Delaware');
INSERT INTO zones VALUES (16,223,'DC','District of Columbia');
INSERT INTO zones VALUES (17,223,'FM','Federated States Of Micronesia');
INSERT INTO zones VALUES (18,223,'FL','Florida');
INSERT INTO zones VALUES (19,223,'GA','Georgia');
INSERT INTO zones VALUES (20,223,'GU','Guam');
INSERT INTO zones VALUES (21,223,'HI','Hawaii');
INSERT INTO zones VALUES (22,223,'ID','Idaho');
INSERT INTO zones VALUES (23,223,'IL','Illinois');
INSERT INTO zones VALUES (24,223,'IN','Indiana');
INSERT INTO zones VALUES (25,223,'IA','Iowa');
INSERT INTO zones VALUES (26,223,'KS','Kansas');
INSERT INTO zones VALUES (27,223,'KY','Kentucky');
INSERT INTO zones VALUES (28,223,'LA','Louisiana');
INSERT INTO zones VALUES (29,223,'ME','Maine');
INSERT INTO zones VALUES (30,223,'MH','Marshall Islands');
INSERT INTO zones VALUES (31,223,'MD','Maryland');
INSERT INTO zones VALUES (32,223,'MA','Massachusetts');
INSERT INTO zones VALUES (33,223,'MI','Michigan');
INSERT INTO zones VALUES (34,223,'MN','Minnesota');
INSERT INTO zones VALUES (35,223,'MS','Mississippi');
INSERT INTO zones VALUES (36,223,'MO','Missouri');
INSERT INTO zones VALUES (37,223,'MT','Montana');
INSERT INTO zones VALUES (38,223,'NE','Nebraska');
INSERT INTO zones VALUES (39,223,'NV','Nevada');
INSERT INTO zones VALUES (40,223,'NH','New Hampshire');
INSERT INTO zones VALUES (41,223,'NJ','New Jersey');
INSERT INTO zones VALUES (42,223,'NM','New Mexico');
INSERT INTO zones VALUES (43,223,'NY','New York');
INSERT INTO zones VALUES (44,223,'NC','North Carolina');
INSERT INTO zones VALUES (45,223,'ND','North Dakota');
INSERT INTO zones VALUES (46,223,'MP','Northern Mariana Islands');
INSERT INTO zones VALUES (47,223,'OH','Ohio');
INSERT INTO zones VALUES (48,223,'OK','Oklahoma');
INSERT INTO zones VALUES (49,223,'OR','Oregon');
INSERT INTO zones VALUES (50,223,'PW','Palau');
INSERT INTO zones VALUES (51,223,'PA','Pennsylvania');
INSERT INTO zones VALUES (52,223,'PR','Puerto Rico');
INSERT INTO zones VALUES (53,223,'RI','Rhode Island');
INSERT INTO zones VALUES (54,223,'SC','South Carolina');
INSERT INTO zones VALUES (55,223,'SD','South Dakota');
INSERT INTO zones VALUES (56,223,'TN','Tennessee');
INSERT INTO zones VALUES (57,223,'TX','Texas');
INSERT INTO zones VALUES (58,223,'UT','Utah');
INSERT INTO zones VALUES (59,223,'VT','Vermont');
INSERT INTO zones VALUES (60,223,'VI','Virgin Islands');
INSERT INTO zones VALUES (61,223,'VA','Virginia');
INSERT INTO zones VALUES (62,223,'WA','Washington');
INSERT INTO zones VALUES (63,223,'WV','West Virginia');
INSERT INTO zones VALUES (64,223,'WI','Wisconsin');
INSERT INTO zones VALUES (65,223,'WY','Wyoming');

# Canada
INSERT INTO zones VALUES (66,38,'AB','Alberta');
INSERT INTO zones VALUES (67,38,'BC','British Columbia');
INSERT INTO zones VALUES (68,38,'MB','Manitoba');
INSERT INTO zones VALUES (69,38,'NL','Newfoundland');
INSERT INTO zones VALUES (70,38,'NB','New Brunswick');
INSERT INTO zones VALUES (71,38,'NS','Nova Scotia');
INSERT INTO zones VALUES (72,38,'NT','Northwest Territories');
INSERT INTO zones VALUES (73,38,'NU','Nunavut');
INSERT INTO zones VALUES (74,38,'ON','Ontario');
INSERT INTO zones VALUES (75,38,'PE','Prince Edward Island');
INSERT INTO zones VALUES (76,38,'QC','Quebec');
INSERT INTO zones VALUES (77,38,'SK','Saskatchewan');
INSERT INTO zones VALUES (78,38,'YT','Yukon Territory');

# Germany
INSERT INTO zones VALUES (79,81,'NDS','Niedersachsen');
INSERT INTO zones VALUES (80,81,'BAW','Baden-Württemberg');
INSERT INTO zones VALUES (81,81,'BAY','Bayern');
INSERT INTO zones VALUES (82,81,'BER','Berlin');
INSERT INTO zones VALUES (83,81,'BRG','Brandenburg');
INSERT INTO zones VALUES (84,81,'BRE','Bremen');
INSERT INTO zones VALUES (85,81,'HAM','Hamburg');
INSERT INTO zones VALUES (86,81,'HES','Hessen');
INSERT INTO zones VALUES (87,81,'MEC','Mecklenburg-Vorpommern');
INSERT INTO zones VALUES (88,81,'NRW','Nordrhein-Westfalen');
INSERT INTO zones VALUES (89,81,'RHE','Rheinland-Pfalz');
INSERT INTO zones VALUES (90,81,'SAR','Saarland');
INSERT INTO zones VALUES (91,81,'SAS','Sachsen');
INSERT INTO zones VALUES (92,81,'SAC','Sachsen-Anhalt');
INSERT INTO zones VALUES (93,81,'SCN','Schleswig-Holstein');
INSERT INTO zones VALUES (94,81,'THE','Thüringen');

# Austria
INSERT INTO zones VALUES (95,14,'WI','Wien');
INSERT INTO zones VALUES (96,14,'NO','Niederösterreich');
INSERT INTO zones VALUES (97,14,'OO','Oberösterreich');
INSERT INTO zones VALUES (98,14,'SB','Salzburg');
INSERT INTO zones VALUES (99,14,'KN','Kärnten');
INSERT INTO zones VALUES (100,14,'ST','Steiermark');
INSERT INTO zones VALUES (101,14,'TI','Tirol');
INSERT INTO zones VALUES (102,14,'BL','Burgenland');
INSERT INTO zones VALUES (103,14,'VB','Voralberg');

# Switzerland
INSERT INTO zones VALUES (104,204,'AG','Aargau');
INSERT INTO zones VALUES (105,204,'AI','Appenzell Innerrhoden');
INSERT INTO zones VALUES (106,204,'AR','Appenzell Ausserrhoden');
INSERT INTO zones VALUES (107,204,'BE','Bern');
INSERT INTO zones VALUES (108,204,'BL','Basel-Landschaft');
INSERT INTO zones VALUES (109,204,'BS','Basel-Stadt');
INSERT INTO zones VALUES (110,204,'FR','Freiburg');
INSERT INTO zones VALUES (111,204,'GE','Genf');
INSERT INTO zones VALUES (112,204,'GL','Glarus');
INSERT INTO zones VALUES (113,204,'JU','Graubünden');
INSERT INTO zones VALUES (114,204,'JU','Jura');
INSERT INTO zones VALUES (115,204,'LU','Luzern');
INSERT INTO zones VALUES (116,204,'NE','Neuenburg');
INSERT INTO zones VALUES (117,204,'NW','Nidwalden');
INSERT INTO zones VALUES (118,204,'OW','Obwalden');
INSERT INTO zones VALUES (119,204,'SG','St. Gallen');
INSERT INTO zones VALUES (120,204,'SH','Schaffhausen');
INSERT INTO zones VALUES (121,204,'SO','Solothurn');
INSERT INTO zones VALUES (122,204,'SZ','Schwyz');
INSERT INTO zones VALUES (123,204,'TG','Thurgau');
INSERT INTO zones VALUES (124,204,'TI','Tessin');
INSERT INTO zones VALUES (125,204,'UR','Uri');
INSERT INTO zones VALUES (126,204,'VD','Waadt');
INSERT INTO zones VALUES (127,204,'VS','Wallis');
INSERT INTO zones VALUES (128,204,'ZG','Zug');
INSERT INTO zones VALUES (129,204,'ZH','Zürich');

# Spain
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'A Coruña','A Coruña');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Alava','Alava');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Albacete','Albacete');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Alicante','Alicante');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Almeria','Almeria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Asturias','Asturias');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Avila','Avila');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Badajoz','Badajoz');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Baleares','Baleares');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Barcelona','Barcelona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Burgos','Burgos');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Caceres','Caceres');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cadiz','Cadiz');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cantabria','Cantabria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Castellon','Castellon');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ceuta','Ceuta');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ciudad Real','Ciudad Real');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cordoba','Cordoba');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Cuenca','Cuenca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Girona','Girona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Granada','Granada');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Guadalajara','Guadalajara');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Guipuzcoa','Guipuzcoa');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Huelva','Huelva');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Huesca','Huesca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Jaen','Jaen');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'La Rioja','La Rioja');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Las Palmas','Las Palmas');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Leon','Leon');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Lleida','Lleida');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Lugo','Lugo');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Madrid','Madrid');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Malaga','Malaga');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Melilla','Melilla');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Murcia','Murcia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Navarra','Navarra');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Ourense','Ourense');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Palencia','Palencia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Pontevedra','Pontevedra');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Salamanca','Salamanca');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Santa Cruz de Tenerife','Santa Cruz de Tenerife');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Segovia','Segovia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Sevilla','Sevilla');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Soria','Soria');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Tarragona','Tarragona');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Teruel','Teruel');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Toledo','Toledo');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Valencia','Valencia');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Valladolid','Valladolid');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Vizcaya','Vizcaya');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Zamora','Zamora');
INSERT INTO zones (zone_country_id, zone_code, zone_name) VALUES (195,'Zaragoza','Zaragoza');

# Header Tags
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_HEADER_TAGS_INSTALLED', 'ht_category_seo.php;ht_manufacturer_seo.php;ht_pages_seo.php;ht_manufacturer_title.php;ht_category_title.php;ht_product_title.php;ht_robot_noindex.php;ht_table_click_jquery.php;ht_product_meta.php;ht_product_opengraph.php;ht_product_schema.php;ht_outgoing.php;ht_canonical.php', 'List of header tag module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Meta Module', 'MODULE_HEADER_TAGS_PRODUCT_META_STATUS', 'True', 'Do you want to allow product meta tags to be added to the page header?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Keyword Search Engine', 'MODULE_HEADER_TAGS_PRODUCT_META_KEYWORDS_STATUS', 'True', 'Enable Keyword Search Engine', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_META_SORT_ORDER', '910', 'Sort order of display. Lowest is displayed first.', 6, 3, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product OpenGraph Module', 'MODULE_HEADER_TAGS_PRODUCT_OPENGRAPH_STATUS', 'True', 'Do you want to allow Open Graph Meta Tags (good for Facebook and Pinterest and other sites) to be added to your product page?  Note that your product thumbnails MUST be at least 200px by 200px.', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_OPENGRAPH_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', 6, 2, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Schema Module', 'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_STATUS', 'True', 'Do you want to add a product schema to your product page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Schema Module', 'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_PLACEMENT', 'Header', 'Where should the code be placed?', 6, 1, 'Config::select_one([\'Header\', \'Footer\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_SCHEMA_SORT_ORDER', '950', 'Sort order of display. Lowest is displayed first.', 6, 2, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Category Meta Module', 'MODULE_HEADER_TAGS_CATEGORY_SEO_STATUS', 'True', 'Do you want to allow Category Meta Tags to be added to the page header?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Category Meta Description', 'MODULE_HEADER_TAGS_CATEGORY_SEO_DESCRIPTION_STATUS', 'True', 'These help your site and your sites visitors.', 6, 2, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_CATEGORY_SEO_SORT_ORDER', '210', 'Sort order of display. Lowest is displayed first.', 6, 3, now()); 

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Manufacturer Meta Module', 'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_STATUS', 'True', 'Do you want to allow Manufacturer meta tags to be added to the page header?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Manufacturer Meta Description', 'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_DESCRIPTION_STATUS', 'True', 'Manufacturer Descriptions help your site and your sites visitors.', 6, 2, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_MANUFACTURERS_SEO_SORT_ORDER', '110', 'Sort order of display. Lowest is displayed first.', 6, 3, now()); 

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Pages SEO Module', 'MODULE_HEADER_TAGS_PAGES_SEO_STATUS', 'True', 'Do you want to allow this module to write SEO to your Pages?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PAGES_SEO_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', 6, 3, now()); 

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Category Title Module', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_STATUS', 'True', 'Do you want to allow category titles to be added to the page title?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('SEO Title Override?', 'MODULE_HEADER_TAGS_CATEGORY_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow category titles to be over-ridden by your SEO Titles (if set)?', 6, 3, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Manufacturer Title Module', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS', 'True', 'Do you want to allow manufacturer titles to be added to the page title?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('SEO Title Override?', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow manufacturer names to be over-ridden by your SEO Titles (if set)?', 6, 0, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Title Module', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_STATUS', 'True', 'Do you want to allow product titles to be added to the page title?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SORT_ORDER', '920', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('SEO Title Override?', 'MODULE_HEADER_TAGS_PRODUCT_TITLE_SEO_TITLE_OVERRIDE', 'True', 'Do you want to allow product titles to be over-ridden by your SEO Titles (if set)?', 6, 0, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Robot NoIndex Module', 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_STATUS', 'True', 'Do you want to enable the Robot NoIndex module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_PAGES', 'account.php;account_edit.php;account_history.php;account_history_info.php;account_newsletters.php;account_notifications.php;account_password.php;address_book.php;address_book_process.php;checkout_confirmation.php;checkout_payment.php;checkout_payment_address.php;checkout_process.php;checkout_shipping.php;checkout_shipping_address.php;checkout_success.php;cookie_usage.php;create_account.php;create_account_success.php;login.php;logoff.php;password_forgotten.php;password_reset.php;shopping_cart.php;ssl_check.php', 'The pages to add the meta robot noindex tag to.', '6', '0', 'page_selection::_show_pages', 'page_selection::_edit_pages(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_ROBOT_NOINDEX_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Clickable Table Rows Module', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_PAGES', 'checkout_payment.php;checkout_shipping.php', 'The pages to add the necessary javascript to.', 6, 2, 'page_selection::_show_pages', 'page_selection::_edit_pages(', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Background Colour', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_TR_BACKGROUND', 'table-success', 'The background colour of the clicked Row.  See  https://getbootstrap.com/docs/5.3/content/tables/#variants', 6, 3, now());       
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_TABLE_CLICK_JQUERY_SORT_ORDER', '800', 'Sort order of display. Lowest is displayed first.', 6, 4, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Queued E-mail Module', 'MODULE_HEADER_TAGS_O_STATUS', 'True', 'Do you want to enable the this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_O_SORT_ORDER', '810', 'Sort order of display. Lowest is displayed first.', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Canonical Module', 'MODULE_HEADER_TAGS_CANONICAL_STATUS', 'True', 'Do you want to enable the this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_CANONICAL_SORT_ORDER', '820', 'Sort order of display. Lowest is displayed first.', 6, 2, now());

# Administration Tool Dashboard
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_ADMIN_DASHBOARD_INSTALLED', 'd_total_revenue.php;d_monthly_sales.php;d_orders.php;d_customers.php;d_phoenix_addons.php;d_addons.php;d_security_checks.php;d_admin_logins.php;d_version_check.php;d_reviews.php', 'List of Administration Tool Dashboard module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STATUS', 'True', 'Do you want to show this module on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Days', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_DAYS', '7,30', 'Days to display.  Comma separated list will display each period in a tabbed interface.', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Step Size', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_STEP', '0', 'This is the Y Axis Step Size in Currency Units.  Make this a number that is about half or so of your average daily sales, you can play with this to suit the Graph output.', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 4, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ADMIN_DASHBOARD_TOTAL_REVENUE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', 6, 5, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_STATUS', 'True', 'Do you want to show this module on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Months', 'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_MONTHS', '3,6,12', 'Months to display.  Comma separated list will display each period in a tabbed interface.', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Step Size', 'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_STEP', '0', 'This is the Y Axis Step Size in Currency Units.  Make this a number that is about half or so of your average monthly sales, you can play with this to suit the Graph output.', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 4, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_ADMIN_DASHBOARD_MONTHLY_SALES_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', 6, 5, NOW());


INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Orders Module', 'MODULE_ADMIN_DASHBOARD_ORDERS_STATUS', 'True', 'Do you want to show the latest orders on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Orders to display', 'MODULE_ADMIN_DASHBOARD_ORDERS_DISPLAY', '5', 'This number of Orders will display, ordered by most recent.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_ORDERS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_ORDERS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Customers Module', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_STATUS', 'True', 'Do you want to show the newest customers on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Customers to display', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_DISPLAY', '5', 'This number of Customers will display, ordered by most recent sign up.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_CUSTOMERS_SORT_ORDER', '400', 'Sort order of display. Lowest is displayed first.', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_STATUS', 'True', 'Do you want to show the latest Partner news on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_PHOENIX_ADDONS_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_ADMIN_DASHBOARD_ADDONS_STATUS', 'True', 'Do you want to show this module on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Addons to display', 'MODULE_ADMIN_DASHBOARD_ADDONS_DISPLAY', '5', 'This number of Addons will display, ordered by most recent.', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_ADDONS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_ADDONS_SORT_ORDER', '550', 'Sort order of display. Lowest is displayed first.', 6, 4, NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Security Checks Module', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_STATUS', 'True', 'Do you want to run the security checks for this installation?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_SECURITY_CHECKS_SORT_ORDER', '600', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Administrator Logins Module', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_STATUS', 'True', 'Do you want to show the latest administrator logins on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Logins to display', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_DISPLAY', '5', 'This number of Logins will display, ordered by latest access.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_ADMIN_LOGINS_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Version Check Module', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_STATUS', 'True', 'Do you want to show the version check results on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_VERSION_CHECK_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Reviews Module', 'MODULE_ADMIN_DASHBOARD_REVIEWS_STATUS', 'True', 'Do you want to show the latest reviews on the dashboard?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Reviews to display', 'MODULE_ADMIN_DASHBOARD_REVIEWS_DISPLAY', '5', 'This number of Reviews will display, ordered by latest added.', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_ADMIN_DASHBOARD_REVIEWS_CONTENT_WIDTH', 'col-md-6 mb-2', 'What container should the content be shown in? (Default: XS-SM full width, MD and above half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_REVIEWS_SORT_ORDER', '800', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Boxes
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_BOXES_INSTALLED', '', 'List of box module filenames separated by a semi-colon. This is automatically updated. No need to edit.', '6', '0', now());

# Template Block Groups
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Installed Template Block Groups', 'TEMPLATE_BLOCK_GROUPS', 'boxes;header_tags', 'This is automatically updated. No need to edit.', '6', '0', now());

# Content Modules
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_CONTENT_INSTALLED', 'account/cm_account_gdpr;account/cm_account_title;account/cm_account_gdpr_nuke;account/cm_account_set_password;checkout_success/cm_cs_title;checkout_success/cm_cs_redirect_old_order;checkout_success/cm_cs_thank_you;checkout_success/cm_cs_product_notifications;checkout_success/cm_cs_downloads;checkout_success/cm_cs_continue_button;contact_us/cm_cu_title;contact_us/cm_cu_modular;create_account_success/cm_cas_title;create_account_success/cm_cas_message;create_account_success/cm_cas_continue_button;footer/cm_footer_information_links;footer/cm_footer_account;footer/cm_footer_contact_us;footer/cm_footer_text;footer_suffix/cm_footer_extra_copyright;footer_suffix/cm_footer_extra_icons;gdpr/cm_gdpr_intro;gdpr/cm_gdpr_personal_details;gdpr/cm_gdpr_contact_details;gdpr/cm_gdpr_contact_addresses;gdpr/cm_gdpr_site_details;gdpr/cm_gdpr_acceptance_data;gdpr/cm_gdpr_site_actions;gdpr/cm_gdpr_orders;gdpr/cm_gdpr_reviews;gdpr/cm_gdpr_testimonials;gdpr/cm_gdpr_cookies;gdpr/cm_gdpr_ip_addresses;gdpr/cm_gdpr_notifications;gdpr/cm_gdpr_cart;gdpr/cm_gdpr_port_my_data;header/cm_header_messagestack;header/cm_header_breadcrumb;header/cm_header_menu;index/cm_i_slider;index/cm_i_card_products;index/cm_i_modular;index_nested/cm_in_title;index_nested/cm_in_category_description;index_nested/cm_in_category_listing;index_nested/cm_in_card_products;index_products/cm_ip_title;index_products/cm_ip_category_manufacturer_description;index_products/cm_ip_product_listing;info/cm_info_title;info/cm_info_text;login/cm_login_form;login/cm_forgot_password;login/cm_create_account_link;navigation/cm_navbar;product_info/cm_pi_name;product_info/cm_pi_price;product_info/cm_pi_review_stars;product_info/cm_pi_modular;product_info/cm_pi_description;product_info/cm_pi_reviews;product_info_not_found/cm_pinf_message;shopping_cart/cm_sc_title;shopping_cart/cm_sc_no_products;shopping_cart/cm_sc_product_listing;shopping_cart/cm_sc_order_subtotal;shopping_cart/cm_sc_stock_notice;shopping_cart/cm_sc_checkout;testimonials/cm_t_title;testimonials/cm_t_list;testimonials/cm_t_write', 'This is automatically updated. No need to edit.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Set Account Password', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_STATUS', 'True', 'Do you want to enable the Set Account Password module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Allow Local Passwords', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_ALLOW_PASSWORD', 'True', 'Allow local account passwords to be set.', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_ACCOUNT_SET_PASSWORD_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Redirect Old Order Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_STATUS', 'True', 'Should customers be redirected when viewing old checkout success orders?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Redirect Minutes', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_MINUTES', '60', 'Redirect customers to the My Account page after an order older than this amount is viewed.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_REDIRECT_OLD_ORDER_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Thank You Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_STATUS', 'True', 'Should the thank you block be shown on the checkout success page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_CONTENT_WIDTH', 'col-sm-7', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_THANK_YOU_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Notifications Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_STATUS', 'True', 'Should the product notifications block be shown on the checkout success page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_CONTENT_WIDTH', 'col-sm-5', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Downloads Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_STATUS', 'True', 'Should ordered product download links be shown on the checkout success page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_CONTENT_WIDTH', 'col-sm-12 mt-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_DOWNLOADS_SORT_ORDER', '3000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Login Form Module', 'MODULE_CONTENT_LOGIN_FORM_STATUS', 'True', 'Do you want to enable the login form module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_LOGIN_FORM_CONTENT_WIDTH', 'col-sm-6 offset-sm-3', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_LOGIN_FORM_SORT_ORDER', '1000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable New User Module', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_STATUS', 'True', 'Do you want to enable the new user module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_CONTENT_WIDTH', 'col-sm-6 offset-sm-3 mt-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CREATE_ACCOUNT_LINK_SORT_ORDER', '2000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Forgot Password Module', 'MODULE_CONTENT_FORGOT_PASSWORD_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FORGOT_PASSWORD_CONTENT_WIDTH', 'col-sm-6 offset-sm-3 mt-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FORGOT_PASSWORD_SORT_ORDER', '1500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Load Navbar Modules, let the shopowner install the rest per his/her needs
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Hamburger Button Module', 'MODULE_NAVBAR_HAMBURGER_BUTTON_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement', 'MODULE_NAVBAR_HAMBURGER_BUTTON_CONTENT_PLACEMENT', 'Home', 'This module must be placed in the Home area of the Navbar.', 6, 1, 'Config::select_one([\'Home\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_HAMBURGER_BUTTON_SORT_ORDER', '500', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Brand Module', 'MODULE_NAVBAR_BRAND_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement', 'MODULE_NAVBAR_BRAND_CONTENT_PLACEMENT', 'Home', 'This module must be placed in the Home area of the Navbar.', 6, 1, 'Config::select_one([\'Home\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_BRAND_SORT_ORDER', '505', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart Module', 'MODULE_NAVBAR_SHOPPING_CART_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement', 'MODULE_NAVBAR_SHOPPING_CART_CONTENT_PLACEMENT', 'Right', 'Should the module be loaded in the Left or Right or the Home area of the Navbar?', 6, 1, 'Config::select_one([\'Home\', \'Left\', \'Center\', \'Right\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_SHOPPING_CART_SORT_ORDER', '550', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Currencies Module', 'MODULE_NAVBAR_CURRENCIES_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement', 'MODULE_NAVBAR_CURRENCIES_CONTENT_PLACEMENT', 'Right', 'Should the module be loaded in the Left or Right or the Home area of the Navbar?', 6, 2, 'Config::select_one([\'Home\', \'Left\', \'Center\', \'Right\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_CURRENCIES_SORT_ORDER', '530', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Account Module', 'MODULE_NAVBAR_ACCOUNT_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement', 'MODULE_NAVBAR_ACCOUNT_CONTENT_PLACEMENT', 'Right', 'Should the module be loaded in the Left or Right or the Home area of the Navbar?', 6, 2, 'Config::select_one([\'Home\', \'Left\', \'Center\', \'Right\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_ACCOUNT_SORT_ORDER', '540', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Special Offers Module', 'MODULE_NAVBAR_SPECIAL_OFFERS_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement Group', 'MODULE_NAVBAR_SPECIAL_OFFERS_CONTENT_PLACEMENT', 'Left', 'Where should the module be loaded?  Lowest is loaded first, per Group.', 6, 2, 'Config::select_one([\'Home\', \'Left\', \'Center\', \'Right\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_NAVBAR_SPECIAL_OFFERS_SORT_ORDER', '530', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_NAVBAR_SEARCH_STATUS', 'True', 'Do you want to add the module to your Navbar?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Content Placement Group', 'MODULE_NAVBAR_SEARCH_CONTENT_PLACEMENT', 'Left', 'Where should the module be loaded?  Lowest is loaded first, per Group.', 6, 2, 'Config::select_one([\'Home\', \'Left\', \'Center\', \'Right\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_NAVBAR_SEARCH_SORT_ORDER', '525', 'Sort order of display. Lowest is displayed first.', 6, 3, now());

# Navbar
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Navbar Module', 'MODULE_CONTENT_NAVBAR_STATUS', 'True', 'Should the Navbar be shown? ', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Background Colour Scheme', 'MODULE_CONTENT_NAVBAR_STYLE_BG', 'bg-light navbar-light bg-body-secondary border-bottom', 'What background and foreground colour should the Navbar have?  See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/utilities/background/#background-color"><u>background/#background-color</u></a> and <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#color-schemes"><u>navbar/#color-schemes</u></a>', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Placement', 'MODULE_CONTENT_NAVBAR_FIXED', 'sticky-top', 'Should the Navbar be Fixed/Sticky/Default behaviour? See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#placement"><u>navbar/#placement</u></a>', 6, 4, 'Config::select_one([\'fixed-top\', \'fixed-bottom\', \'sticky-top\', \'floating\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Placement Offset', 'MODULE_CONTENT_NAVBAR_OFFSET', '4rem', 'Offset if using fixed-* Placement.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Collapse', 'MODULE_CONTENT_NAVBAR_COLLAPSE', 'navbar-expand-sm', 'When should the Navbar Show? See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/components/navbar/#how-it-works"><u>navbar/#how-it-works</u></a>', 6, 6, 'Config::select_one([\'navbar-expand\', \'navbar-expand-sm\', \'navbar-expand-md\', \'navbar-expand-lg\', \'navbar-expand-xl\', \'navbar-expand-xxl\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_NAVBAR_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Stack
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Message Stack Notifications Module', 'MODULE_CONTENT_HEADER_MESSAGESTACK_STATUS', 'True', 'Should the Message Stack Notifications be shown in the header when needed? ', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_HEADER_MESSAGESTACK_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_MESSAGESTACK_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Breadcrumb
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Header Breadcrumb Module', 'MODULE_CONTENT_HEADER_BREADCRUMB_STATUS', 'True', 'Do you want to enable the Breadcrumb content module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_HEADER_BREADCRUMB_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_HEADER_BREADCRUMB_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Location?', 'MODULE_CONTENT_HEADER_BREADCRUMB_LOCATION', 'Schema', 'Where you want the breadcrumb to be used.  Display in the Header, post as Schema entries, or Both.', 6, 4, 'Config::select_one([\'Header\', \'Schema\', \'Both\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Category SEO Override?', 'MODULE_CONTENT_HEADER_BREADCRUMB_CATEGORY_SEO_OVERRIDE', 'True', 'Do you want to allow category titles to be over-ridden by your SEO Titles (if set)?', 6, 5, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Manufacturer SEO Override?', 'MODULE_CONTENT_HEADER_BREADCRUMB_MANUFACTURER_SEO_OVERRIDE', 'True', 'Do you want to allow manufacturer names in the breadcrumb to be over-ridden by your SEO Titles (if set)?', 6, 6, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Product SEO Override?', 'MODULE_CONTENT_HEADER_BREADCRUMB_PRODUCT_SEO_OVERRIDE', 'True', 'Do you want to allow product names in the breadcrumb to be over-ridden by your SEO Titles (if set)?', 6, 7, 'Config::select_one([\'True\', \'False\'], ', NOW());

# Horizontal Menu
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Horizontal Menu Module', 'MODULE_CONTENT_HEADER_MENU_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_HEADER_MENU_CONTENT_WIDTH', 'col-sm-12 mb-1', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Colour Scheme', 'MODULE_CONTENT_HEADER_MENU_STYLE', '', 'What colour scheme should this Navigation Bar have?  See https://getbootstrap.com/docs/5.3/components/navbar/#color-schemes', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Collapse Breakpoint', 'MODULE_CONTENT_HEADER_MENU_COLLAPSE', 'navbar-expand-sm', 'When should this Navigation Bar Show? See https://getbootstrap.com/docs/5.3/components/navbar/#how-it-works', 6, 4, 'Config::select_one([\'navbar-expand\', \'navbar-expand-sm\', \'navbar-expand-md\', \'navbar-expand-lg\', \'navbar-expand-xl\', \'navbar-expand-xxl\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Manufacturers', 'MODULE_CONTENT_HEADER_MENU_MANUFACTURERS', 'True', 'Manufacturers will display on the Right Hand Side of the Horizontal Menu, if True', 6, 5, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_HEADER_MENU_SORT_ORDER', '900', 'Sort order of display. Lowest is displayed first.', 6, 6, NOW());

# Footer
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Information Links Footer Module', 'MODULE_CONTENT_FOOTER_INFORMATION_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FOOTER_INFORMATION_CONTENT_WIDTH', 'col-sm-6 col-md-3 mb-2 mb-sm-0', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_INFORMATION_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Account Footer Module', 'MODULE_CONTENT_FOOTER_ACCOUNT_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FOOTER_ACCOUNT_CONTENT_WIDTH', 'col-sm-6 col-md-3 mb-2 mb-sm-0', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_ACCOUNT_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Contact Us Footer Module', 'MODULE_CONTENT_FOOTER_CONTACT_US_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FOOTER_CONTACT_US_CONTENT_WIDTH', 'col-sm-6 col-md-3 mb-2 mb-sm-0', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_CONTACT_US_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Generic Text Footer Module', 'MODULE_CONTENT_FOOTER_TEXT_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FOOTER_TEXT_CONTENT_WIDTH', 'col-sm-6 col-md-3 mb-2 mb-sm-0', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_TEXT_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# Footer Suffix
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Copyright Details Footer Module', 'MODULE_CONTENT_FOOTER_EXTRA_COPYRIGHT_STATUS', 'True', 'Do you want to enable the Copyright content module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FOOTER_EXTRA_COPYRIGHT_CONTENT_WIDTH', 'col-sm-6 text-center text-sm-left text-sm-start', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_EXTRA_COPYRIGHT_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Payment Icons Footer Module', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_STATUS', 'True', 'Do you want to enable the Payment Icons content module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_CONTENT_WIDTH', 'col-sm-6 text-center text-sm-right text-sm-end', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Icons', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_DISPLAY', 'fab fa-paypal fa-lg,fab fa-cc-visa fa-lg', 'Icons to display.', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_FOOTER_EXTRA_ICONS_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# ModularAccount
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_ACCOUNT_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_ACCOUNT_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_ACCOUNT_TITLE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

#ModularCheckoutSuccess
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_CHECKOUT_SUCCESS_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CHECKOUT_SUCCESS_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CHECKOUT_SUCCESS_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Continue Button', 'MODULE_CONTENT_CS_CONTINUE_BUTTON_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CS_CONTINUE_BUTTON_CONTENT_WIDTH', 'col-sm-12 my-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CS_CONTINUE_BUTTON_SORT_ORDER', '5000', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

# ModularIndex
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Slider Module', 'MODULE_CONTENT_I_SLIDER_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_I_SLIDER_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Advert Group', 'MODULE_CONTENT_I_SLIDER_GRP', '', 'Choose which Advert Group this module should display..', 6, 3, NOW(), 'adverts::advert_get_group', 'adverts::advert_pull_down_groups(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Controls', 'MODULE_CONTENT_I_SLIDER_CONTROLS', 'True', 'Do you want to show Left/Right Arrows?', 6, 4, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Indicators', 'MODULE_CONTENT_I_SLIDER_INDICATORS', 'True', 'Do you want to show Left/Right Arrows?', 6, 5, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display Style', 'MODULE_CONTENT_I_SLIDER_FADE', 'Fade', 'Slide from the right or Fade In?', 6, 6, 'Config::select_one([\'Fade\', \'Slide\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Interval', 'MODULE_CONTENT_I_SLIDER_INTERVAL', '10000', 'How long a slide is seen before the next.  10000 = 10 seconds.', 6, 7, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_I_SLIDER_SORT_ORDER', '75', 'Sort order of display. Lowest is displayed first.', 6, 8, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable New Products Module', 'MODULE_CONTENT_CARD_PRODUCTS_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CARD_PRODUCTS_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Display', 'MODULE_CONTENT_CARD_PRODUCTS_MAX_DISPLAY', '4', 'Maximum Number of products that should show in this module?', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_CARD_PRODUCTS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable &pi; Modular index', 'MODULE_CONTENT_I_MODULAR_STATUS', 'True', 'Should this module be shown on the index page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_I_MODULAR_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: A', 'MODULE_CONTENT_I_MODULAR_A_WIDTH', 'col-sm-12', 'What width should Slot A be?  Note that Slots in a Row should totalise 12.', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order,  date_added) VALUES ('Slot Width: B', 'MODULE_CONTENT_I_MODULAR_B_WIDTH', 'col-sm-12', 'What width should Slot B be?  Note that Slots in a Row should totalise 12.', 6, 4, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: C', 'MODULE_CONTENT_I_MODULAR_C_WIDTH', 'col-sm-6', 'What width should Slot C be?  Note that Slots in a Row should totalise 12.', 6, 5, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: D', 'MODULE_CONTENT_I_MODULAR_D_WIDTH', 'col-sm-6', 'What width should Slot D be?  Note that Slots in a Row should totalise 12.', 6, 6, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: E', 'MODULE_CONTENT_I_MODULAR_E_WIDTH', 'col-sm-4', 'What width should Slot E be?  Note that Slots in a Row should totalise 12.', 6, 7, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: F', 'MODULE_CONTENT_I_MODULAR_F_WIDTH', 'col-sm-4', 'What width should Slot F be?  Note that Slots in a Row should totalise 12.', 6, 8, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: G', 'MODULE_CONTENT_I_MODULAR_G_WIDTH', 'col-sm-4', 'What width should Slot G be?  Note that Slots in a Row should totalise 12.', 6, 9, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: H', 'MODULE_CONTENT_I_MODULAR_H_WIDTH', 'col-sm-6', 'What width should Slot H be?  Note that Slots in a Row should totalise 12.', 6, 10, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: I', 'MODULE_CONTENT_I_MODULAR_I_WIDTH', 'col-sm-6', 'What width should Slot I be?  Note that Slots in a Row should totalise 12.', 6, 11, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_I_MODULAR_SORT_ORDER', '310', 'Sort order of display. Lowest is displayed first.', 6, 12, NOW());

#ModularIndex Nested
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_IN_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IN_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Category Description Module', 'MODULE_CONTENT_IN_CATEGORY_DESCRIPTION_STATUS', 'True', 'Should this module be enabled?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IN_CATEGORY_DESCRIPTION_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_CATEGORY_DESCRIPTION_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Category Listing Module', 'MODULE_CONTENT_IN_CATEGORY_LISTING_STATUS', 'True', 'Should this module be enabled?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IN_CATEGORY_LISTING_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Categories Per Row', 'MODULE_CONTENT_IN_CATEGORY_LISTING_DISPLAY_ROW', 'row row-cols-2 row-cols-sm-3 row-cols-md-4', 'How many categories should display per Row per viewport?  Default:  XS 2, SM 3, MD and above 4', '6', '4', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_CATEGORY_LISTING_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '8', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable New Products Module', 'MODULE_CONTENT_IN_CARD_PRODUCTS_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IN_CARD_PRODUCTS_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximum Display', 'MODULE_CONTENT_IN_CARD_PRODUCTS_MAX_DISPLAY', '6', 'Maximum Number of products that should show in this module?', '6', '3', now());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IN_CARD_PRODUCTS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

#ModularIndex Products
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_IP_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IP_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IP_TITLE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Category/Manufacturer Description Module', 'MODULE_CONTENT_IP_CATEGORY_DESCRIPTION_STATUS', 'True', 'Should this module be enabled?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IP_CATEGORY_DESCRIPTION_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IP_CATEGORY_DESCRIPTION_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '2', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Product Listing Module', 'MODULE_CONTENT_IP_PRODUCT_LISTING_STATUS', 'True', 'Should this module be enabled?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_IP_PRODUCT_LISTING_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_IP_PRODUCT_LISTING_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '4', now());

#Modular Product Page
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Message Module', 'MODULE_CONTENT_PINF_MESSAGE_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PINF_MESSAGE_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PINF_MESSAGE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Name Module', 'MODULE_CONTENT_PI_NAME_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PI_NAME_CONTENT_WIDTH', 'col-sm-7', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_NAME_SORT_ORDER', '40', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Price Module', 'MODULE_CONTENT_PI_PRICE_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PI_PRICE_CONTENT_WIDTH', 'col-sm-5 text-start text-sm-end', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_PRICE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Review Stars/Link Module', 'MODULE_CONTENT_PI_REVIEW_STARS_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PI_REVIEW_STARS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_REVIEW_STARS_SORT_ORDER', '55', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Description Module', 'MODULE_CONTENT_PI_DESCRIPTION_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PI_DESCRIPTION_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PI_DESCRIPTION_SORT_ORDER', '60', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

#Product Reviews
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Reviews Module', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_STATUS', 'True', 'Should the reviews block be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Review Container', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_WIDTH_EACH', 'col-sm-6', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 1, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Reviews', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_CONTENT_LIMIT', '99', 'How many reviews should be shown?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_ORDER', 'reviews_rating', 'Display Reviews by Rating (High to Low) or Date Added (New to Old)', 6, 1, 'Config::select_one([\'reviews_rating\', \'date_added\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_REVIEWS_SORT_ORDER', '120', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

#Modular Cart Page
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart Title Module', 'MODULE_CONTENT_SC_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_SC_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart No Products Message', 'MODULE_CONTENT_SC_NO_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_SC_NO_PRODUCTS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_NO_PRODUCTS_SORT_ORDER', '110', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart Product Listing', 'MODULE_CONTENT_SC_PRODUCT_LISTING_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_SC_PRODUCT_LISTING_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_PRODUCT_LISTING_SORT_ORDER', '120', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart Order SubTotal', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_CONTENT_WIDTH', 'col-sm-12 mt-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_ORDER_SUBTOTAL_SORT_ORDER', '130', 'Sort order of display. Lowest is displayed first.', '6', '0', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart Stock Notice', 'MODULE_CONTENT_SC_STOCK_NOTICE_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_SC_STOCK_NOTICE_CONTENT_WIDTH', 'col-sm-12 mt-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_STOCK_NOTICE_SORT_ORDER', '140', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Shopping Cart Checkout Button', 'MODULE_CONTENT_SC_CHECKOUT_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_SC_CHECKOUT_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_SC_CHECKOUT_SORT_ORDER', '150', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_TESTIMONIALS_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_TESTIMONIALS_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_TESTIMONIALS_TITLE_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable List Module', 'MODULE_CONTENT_TESTIMONIALS_LIST_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_TESTIMONIALS_LIST_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('View Testimonials', 'MODULE_CONTENT_TESTIMONIALS_LIST_ALL', 'Language Specific', 'Do you want to show all Testimonials or language specific Testimonials?', 6, 3, 'Config::select_one([\'All\', \'Language Specific\'], ', NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Number of Testimonials', 'MODULE_CONTENT_TESTIMONIALS_LIST_PAGING', '12', 'How many Testimonials to display per page.', '6', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Item Width', 'MODULE_CONTENT_TESTIMONIALS_LIST_CONTENT_WIDTH_EACH', 'col-sm-6 mb-2', 'What container should each Testimonial be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_TESTIMONIALS_LIST_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '5', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Write Testimonial Module', 'MODULE_CONTENT_TESTIMONIALS_WRITE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_TESTIMONIALS_WRITE_CONTENT_WIDTH', 'col-sm-6', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_TESTIMONIALS_WRITE_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', 6, 3, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable &pi; Modular product_info', 'MODULE_CONTENT_PI_MODULAR_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_PI_MODULAR_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: A', 'MODULE_CONTENT_PI_MODULAR_A_WIDTH', 'col-sm-12', 'What width should Slot A be?  Note that Slots in a Row should totalise 12.', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: B', 'MODULE_CONTENT_PI_MODULAR_B_WIDTH', 'col-sm-7', 'What width should Slot B be?  Note that Slots in a Row should totalise 12.', 6, 4, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: C', 'MODULE_CONTENT_PI_MODULAR_C_WIDTH', 'col-sm-5', 'What width should Slot C be?  Note that Slots in a Row should totalise 12.', 6, 5, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: D', 'MODULE_CONTENT_PI_MODULAR_D_WIDTH', 'col-sm-6', 'What width should Slot D be?  Note that Slots in a Row should totalise 12.', 6, 6, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: E', 'MODULE_CONTENT_PI_MODULAR_E_WIDTH', 'col-sm-6', 'What width should Slot E be?  Note that Slots in a Row should totalise 12.', 6, 7, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: F', 'MODULE_CONTENT_PI_MODULAR_F_WIDTH', 'col-sm-12', 'What width should Slot F be?  Note that Slots in a Row should totalise 12.', 6, 8, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: G', 'MODULE_CONTENT_PI_MODULAR_G_WIDTH', 'col-sm-6', 'What width should Slot G be?  Note that Slots in a Row should totalise 12.', 6, 9, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: H', 'MODULE_CONTENT_PI_MODULAR_H_WIDTH', 'col-sm-6', 'What width should Slot H be?  Note that Slots in a Row should totalise 12.', 6, 10, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: I', 'MODULE_CONTENT_PI_MODULAR_I_WIDTH', 'col-sm-12', 'What width should Slot I be?  Note that Slots in a Row should totalise 12.', 6, 11, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_PI_MODULAR_SORT_ORDER', '59', 'Sort order of display. Lowest is displayed first.', 6, 12, now());

# Customer Data module default installs
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_CUSTOMER_DATA_INSTALLED', 'cd_address_book_id.php;cd_firstname.php;cd_lastname.php;cd_email_address.php;cd_street_address.php;cd_city.php;cd_state.php;cd_postcode.php;cd_country.php;cd_date_account_created.php;cd_default_address_id.php;cd_email_username.php;cd_id.php;cd_password.php;cd_matc.php;cd_name_2.php;cd_password_reset.php;cd_sortable_name_2.php;cd_traditional_address.php;cd_traditional_short_name.php', 'This is automatically updated. No need to edit.', 6, 0, NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Address Book ID module', 'MODULE_CUSTOMER_DATA_ADDRESS_BOOK_ID_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable First Name module', 'MODULE_CUSTOMER_DATA_FIRSTNAME_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_FIRSTNAME_GROUP', '1', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require First Name', 'MODULE_CUSTOMER_DATA_FIRSTNAME_REQUIRED', 'True', 'Do you want the first name to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'ENTRY_FIRST_NAME_MIN_LENGTH', '2', 'Minimum length of first name', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_FIRSTNAME_PAGES', 'account_edit;address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_FIRSTNAME_SORT_ORDER', '2030', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_FIRSTNAME_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Last Name module', 'MODULE_CUSTOMER_DATA_LASTNAME_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_LASTNAME_GROUP', '1', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require Last Name module (if enabled)', 'MODULE_CUSTOMER_DATA_LASTNAME_REQUIRED', 'True', 'Do you want the last name to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'ENTRY_LAST_NAME_MIN_LENGTH', '2', 'Minimum length of last name', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_LASTNAME_PAGES', 'account_edit;address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_LASTNAME_SORT_ORDER', '2070', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_LASTNAME_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Traditional Short Name module', 'MODULE_CUSTOMER_DATA_TRADITIONAL_SHORT_NAME_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Two Part Name module', 'MODULE_CUSTOMER_DATA_NAME_2_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Street Address module', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_GROUP', '2', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require Street Address module (if enabled)', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_REQUIRED', 'True', 'Do you want the street address to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_MIN_LENGTH', '3', 'Minimum length of street address', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_PAGES', 'address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_SORT_ORDER', '4200', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_STREET_ADDRESS_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable City module', 'MODULE_CUSTOMER_DATA_CITY_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_CITY_GROUP', '2', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require City module (if enabled)', 'MODULE_CUSTOMER_DATA_CITY_REQUIRED', 'True', 'Do you want the city to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'MODULE_CUSTOMER_DATA_CITY_MIN_LENGTH', '3', 'Minimum length of city', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_CITY_PAGES', 'address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_CITY_SORT_ORDER', '4500', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_CITY_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Date Account Created module', 'MODULE_CUSTOMER_DATA_DATE_ACCOUNT_CREATED_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Default Address ID module', 'MODULE_CUSTOMER_DATA_DEFAULT_ADDRESS_ID_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Two Part Sortable Name module', 'MODULE_CUSTOMER_DATA_SORTABLE_NAME_2_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable State module', 'MODULE_CUSTOMER_DATA_STATE_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_STATE_GROUP', '2', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require State module (if enabled)', 'MODULE_CUSTOMER_DATA_STATE_REQUIRED', 'True', 'Do you want the state to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'ENTRY_STATE_MIN_LENGTH', '2', 'Minimum length of state', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_STATE_PAGES', 'address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_STATE_SORT_ORDER', '4600', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_STATE_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Post Code module', 'MODULE_CUSTOMER_DATA_POST_CODE_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_POST_CODE_GROUP', '2', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require Post Code module (if enabled)', 'MODULE_CUSTOMER_DATA_POST_CODE_REQUIRED', 'True', 'Do you want the post code to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'MODULE_CUSTOMER_DATA_POST_CODE_MIN_LENGTH', '3', 'Minimum length of post code', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_POST_CODE_PAGES', 'address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_POST_CODE_SORT_ORDER', '4800', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_POST_CODE_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Country module', 'MODULE_CUSTOMER_DATA_COUNTRY_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_COUNTRY_GROUP', '2', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require Country module (if enabled)', 'MODULE_CUSTOMER_DATA_COUNTRY_REQUIRED', 'True', 'Do you want the country to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_COUNTRY_PAGES', 'address_book;checkout_new_address;create_account;customers', 'On what pages should this appear?', '6', '4', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_COUNTRY_SORT_ORDER', '4900', 'Sort order of display. Lowest is displayed first.', '6', '5', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_COUNTRY_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Email Address module', 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_GROUP', '1', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require Email Address module (if enabled)', 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_REQUIRED', 'True', 'Do you want the email address to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'ENTRY_EMAIL_ADDRESS_MIN_LENGTH', '6', 'Minimum length of email address', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_PAGES', 'account_edit;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_SORT_ORDER', '2100', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_EMAIL_ADDRESS_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Email Username module', 'MODULE_CUSTOMER_DATA_EMAIL_USERNAME_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Password module', 'MODULE_CUSTOMER_DATA_PASSWORD_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_PASSWORD_GROUP', '6', 'In what group should this appear?', 6, 2, NOW(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Require Password module (if enabled)', 'MODULE_CUSTOMER_DATA_PASSWORD_REQUIRED', 'True', 'Do you want the password to be required in customer registration?', 6, 3, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Minimum Length', 'MODULE_CUSTOMER_DATA_PASSWORD_MIN_LENGTH', '5', 'Minimum length of password', '6', '4', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_PASSWORD_PAGES', 'account_password;create_account;customers', 'On what pages should this appear?', '6', '5', now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_PASSWORD_SORT_ORDER', '6200', 'Sort order of display. Lowest is displayed first.', '6', '6', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Template', 'MODULE_CUSTOMER_DATA_PASSWORD_TEMPLATE', 'includes/modules/customer_data/cd_whole_row_input.php', 'What template should be used to surround this input?', '6', '7', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Password Reset module', 'MODULE_CUSTOMER_DATA_PASSWORD_RESET_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Identifier module', 'MODULE_CUSTOMER_DATA_ID_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enable Traditional Address module', 'MODULE_CUSTOMER_DATA_TRADITIONAL_ADDRESS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable MATC Module', 'MODULE_CUSTOMER_DATA_MATC_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Customer data group', 'MODULE_CUSTOMER_DATA_MATC_GROUP', '6', 'In what group should this appear?', 6, 2, now(), 'customer_data_group::fetch_name', 'Config::select_customer_data_group(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Pages', 'MODULE_CUSTOMER_DATA_MATC_PAGES', 'create_account', 'On what pages should this appear?', 6, 3, now(), 'abstract_module::list_exploded', 'Customers::select_pages(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Checkout Page', 'MODULE_CUSTOMER_DATA_MATC_CHECKOUT', 'False', 'Should the MATC also show on checkout_confirmation?', 6, 4, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CUSTOMER_DATA_MATC_SORT_ORDER', '6800', 'Sort order of display. Lowest is displayed first.', 6, 5, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable GDPR Link', 'MODULE_CONTENT_ACCOUNT_GDPR_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Countries', 'MODULE_CONTENT_ACCOUNT_GDPR_COUNTRIES', '', 'Restrict the Link to Account Holders in these Countries.  Leave Blank to show link to all Countries!', 6, 2, 'gdpr_show_countries', 'Config::select_multiple(Country::fetch_options(), ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_ACCOUNT_GDPR_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Intro Module', 'MODULE_CONTENT_GDPR_INTRO_STATUS', 'True', 'Should this module be shown on the GDPR page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_INTRO_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_INTRO_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Personal Details Module', 'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_STATUS', 'True', 'Should this module be shown on the GDPR page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_PERSONAL_DETAILS_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_CAS_TITLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Module', 'MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_STATUS', 'True', 'Do you want to enable this module?', 6, 1, NOW(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_ACCEPTANCE_DATA_SORT_ORDER', '205', 'Sort order of display. Lowest is displayed first.', 6, 3, NOW());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CAS_TITLE_CONTENT_WIDTH', 'col-sm-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_CAS_TITLE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Message Module', 'MODULE_CONTENT_CAS_MESSAGE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CAS_MESSAGE_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_CAS_MESSAGE_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Button Module', 'MODULE_CONTENT_CAS_CONTINUE_BUTTON_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CAS_CONTINUE_BUTTON_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_CAS_CONTINUE_BUTTON_SORT_ORDER', '30', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

# Notification modules
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES('Installed Modules', 'MODULE_NOTIFICATIONS_INSTALLED', 'n_checkout.php;n_create_account.php;n_update_order.php', 'This is automatically updated. No need to edit.', 6, 0, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES('Enable Checkout Notification module', 'MODULE_NOTIFICATIONS_CHECKOUT_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES('Enable Account Creation Notification module', 'MODULE_NOTIFICATIONS_CREATE_ACCOUNT_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES('Enable Order Status Update Notification module', 'MODULE_NOTIFICATIONS_UPDATE_ORDER_STATUS', 'True', 'Do you want to add the module to your shop?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());

# Layout modules
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id,sort_order, date_added) VALUES (NULL, 'Installed Modules', 'MODULE_CONTENT_I_INSTALLED', 'i_adverts.php;i_brand_icons.php;i_hero.php', 'List of &pi; Index child modules separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Adverts Module', 'I_ADVERTS_STATUS', 'True', 'Should this module be shown?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'I_ADVERTS_GROUP', 'A', 'Where should this module display on the index page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'I_ADVERTS_CONTENT_WIDTH', 'col-sm-10 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function, use_function) VALUES ('Advert Group', 'I_ADVERTS_LINK', 'True', 'Choose which Advert Group this module should display..', 6, 4, NOW(), 'adverts::advert_pull_down_groups(', 'adverts::advert_get_group');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'I_ADVERTS_SORT_ORDER', '85', 'Sort order of display. Lowest is displayed first.', 6, 5, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Brand Icons Module', 'I_BRAND_ICONS_STATUS', 'True', 'Should this module be shown?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'I_BRAND_ICONS_GROUP', 'A', 'Where should this module display on the index page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'I_BRAND_ICONS_CONTENT_WIDTH', 'col-sm-2 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Brands', 'I_BRAND_ICONS_CSV', '', 'Choose which Brands this module should display..', 6, 4, NOW(), 'i_show_brands', 'i_select_brands(');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Chunk', 'I_BRAND_ICONS_XS_CHUNK', '2', 'At SM and below, the display will change to a Carousel.  This number determines how many icons are in each slide.', 6, 5, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'I_BRAND_ICONS_SORT_ORDER', '87', 'Sort order of display. Lowest is displayed first.', 6, 5, now());
	  
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Hero Module', 'I_HERO_STATUS', 'True', 'Should this module be shown?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'I_HERO_GROUP', 'B', 'Where should this module display on the index page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'I_HERO_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'I_HERO_SORT_ORDER', '89', 'Sort order of display. Lowest is displayed first.', 6, 4, now());

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES (NULL, 'Installed Modules', 'MODULE_CONTENT_PI_INSTALLED', 'pi_gallery.php;pi_gallery_images.php;pi_img_disclaimer.php;pi_options_attributes.php;pi_date_available.php;pi_qty_input.php;pi_buy_button.php', 'List of &pi; Product Info child modules separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Buy Button', 'PI_BUY_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'PI_BUY_GROUP', 'C', 'Where should this module display on the product info page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_BUY_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_BUY_SORT_ORDER', '320', 'Sort order of display. Lowest is displayed first.', 6, 4, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Gallery Module', 'PI_GALLERY_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'PI_GALLERY_GROUP', 'B', 'Where should this module display on the product info page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_GALLERY_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Modal Popup Size', 'PI_GALLERY_MODAL_SIZE', 'modal-md', 'Choose the size of the Popup.  sm = small, md = medium etc.', 6, 5, 'Config::select_one([\'modal-sm\', \'modal-md\', \'modal-lg\', \'modal-xl\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Show Swipe Arrows', 'PI_GALLERY_SWIPE_ARROWS', 'True', 'Swipe Arrows make for a better User Experience in some cases.', 6, 6, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Show Indicators', 'PI_GALLERY_INDICATORS', 'True', 'Indicators allow users to jump from image to image without having to swipe.', 6, 7, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_GALLERY_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', 6, 8, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'PI_GALLERY_IMAGES_STATUS', 'True', 'Do you want to enable this module?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'PI_GALLERY_IMAGES_GROUP', 'B', 'Where should this module display on the product info page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_GALLERY_IMAGES_CONTENT_WIDTH', 'col-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_GALLERY_IMAGES_CONTENT_WIDTH_EACH', 'col-4 col-sm-6 col-lg-4 mb-1', 'What container should each thumbnail be shown in?', 6, 4, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_GALLERY_IMAGES_SORT_ORDER', '210', 'Sort order of display. Lowest is displayed first.', 6, 5, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Image Disclaimer Module', 'PI_IMG_DISCLAIMER_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'PI_IMG_DISCLAIMER_GROUP', 'B', 'Where should this module display on the product info page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_IMG_DISCLAIMER_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_IMG_DISCLAIMER_SORT_ORDER', '230', 'Sort order of display. Lowest is displayed first.', 6, 4, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Options & Attributes', 'PI_OA_STATUS', 'True', 'Should this module be shown on the product info page?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Module Display', 'PI_OA_GROUP', 'C', 'Where should this module display on the product info page?', 6, 2, 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_OA_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Add Helper Text', 'PI_OA_HELPER', 'True', 'Should first option in dropdown be Helper Text?', 6, 4, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enforce Selection', 'PI_OA_ENFORCE', 'True', 'Should customer be forced to select option(s)?', 6, 5, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_OA_SORT_ORDER', '310', 'Sort order of display. Lowest is displayed first.', 6, 6, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Qty Module', 'PI_QTY_INPUT_STATUS', 'True', 'Should this module be shown in the &pi; layout?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Module Display', 'PI_QTY_INPUT_GROUP', 'C', 'Where should this module display on the product info page?', 6, 2, now(), 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_QTY_INPUT_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Add Spinner Buttons', 'PI_QTY_INPUT_BUTTONS', 'True', 'Add -/+ buttons onto the number input?', 6, 4, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_QTY_INPUT_SORT_ORDER', '319', 'Sort order of display. Lowest is displayed first.', 6, 5, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Module', 'PI_DATE_AVAILABLE_STATUS', 'True', 'Do you want to enable this module?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Module Display', 'PI_DATE_AVAILABLE_GROUP', 'C', 'Where should this module display on the product info page?', 6, 2, now(), 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'PI_DATE_AVAILABLE_CONTENT_WIDTH', 'col-sm-12 mb-2', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Date Style', 'PI_DATE_AVAILABLE_STYLE', 'Long', 'How should the date look?', 6, 4, now(), 'Config::select_one([\'Long\', \'Short\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'PI_DATE_AVAILABLE_SORT_ORDER', '315', 'Sort order of display. Lowest is displayed first.', 6, 5, now());

INSERT INTO pages (pages_id, date_added, pages_status, slug, sort_order) VALUES (1, now(), 0, 'privacy', 10);
INSERT INTO pages (pages_id, date_added, pages_status, slug, sort_order) VALUES (2, now(), 0, 'conditions', 20);
INSERT INTO pages (pages_id, date_added, pages_status, slug, sort_order) VALUES (3, now(), 0, 'shipping', 30);
INSERT INTO pages_description (pages_id, languages_id, pages_title, pages_text, navbar_title) VALUES (1, 1, 'Privacy & Cookie Policy', 'Put here your Privacy/Cookie Policies Text.', 'Privacy & Cookie Policy');
INSERT INTO pages_description (pages_id, languages_id, pages_title, pages_text, navbar_title) VALUES (2, 1, 'Terms & Conditions', 'Put here your Terms & Conditions Text.', 'Terms & Conditions');
INSERT INTO pages_description (pages_id, languages_id, pages_title, pages_text, navbar_title) VALUES (3, 1, 'Shipping & Returns', 'Put here your Shipping & Returns Text.', 'Shipping & Returns');

INSERT INTO pages (pages_id, date_added, pages_status, slug, sort_order) VALUES (4, now(), 0, 'ssl_check', 40);
INSERT INTO pages_description (pages_id, languages_id, pages_title, pages_text, navbar_title) VALUES (4, 1, 'Security Check', '<div class=\"row\">\r\n<div class=\"col-md-6\">\r\n<div class=\"card mb-2\">\r\n<div class=\"card-header\">Privacy and Security</div>\r\n<div class=\"card-body\">\r\n<p class=\"card-text\">We validate the SSL Session ID automatically generated by your browser on every secure page request made to this server.</p>\r\n<p class=\"card-text\">This validation assures that it is you who is navigating on this site with your profile and not somebody else.</p>\r\n</div>\r\n</div>\r\n</div>\r\n  <div class=\"col-md-6\">\r\n<div class=\"card mb-2 text-white bg-danger\">\r\n<div class=\"card-body\">\r\n<p class=\"card-text\">We have detected that your browser has generated a different SSL Session ID used throughout our secure pages.</p>\r\n<p class=\"card-text\">For security purposes you will need to sign in to your profile again to continue shopping.</p>\r\n<p class=\"card-text\">Some browsers do not have the capability of generating a secure SSL Session ID automatically. If you use such a browser, we recommend switching to a more modern browser such as <a class=\"alert-link\" href=\"https://www.microsoft.com/en-us/edge/download?form=MA13FJ\" target=\"_blank\" rel=\"noreferrer\">Microsoft Edge</a> or <a class=\"alert-link\" href=\"https://support.google.com/chrome/answer/95346\" target=\"_blank\" rel=\"noreferrer\">Google Chrome</a> or <a class=\"alert-link\" href=\"https://www.mozilla.org/en-GB/firefox/\" target=\"_blank\" rel=\"noreferrer\">Mozilla Firefox</a> to continue using our site.</p>\r\n<p class=\"card-text\">We have taken this security measure for your benefit, and apologize for any inconvenience caused.  Please contact us if you have any questions about this requirement.</p>\r\n      </div>\r\n</div>\r\n</div>\r\n</div>', 'Security Check');

INSERT INTO pages (pages_id, date_added, pages_status, slug, sort_order) VALUES (5, now(), 0, 'cookie_usage', 50);
INSERT INTO pages_description (pages_id, languages_id, pages_title, pages_text, navbar_title) VALUES (5, 1, 'Cookie Usage', '<div class=\"row\">\r\n<div class=\"col-md-6\">\r\n<div class=\"card mb-2\">\r\n<div class=\"card-header\">Cookie Privacy and Security</div>\r\n      <div class=\"card-body\">\r\n<p class=\"card-text\">Cookies must be enabled to purchase online on this store to embrace privacy and security related issues regarding your visit to this site.</p>\r\n<p class=\"card-text\">By enabling cookie support on your browser, the communication between you and this site is strengthened to be certain it is you who are making transactions on your own behalf, and to prevent leakage of your privacy information.</p>\r\n</div>\r\n</div>\r\n</div>\r\n<div class=\"col-md-6\">\r\n<div class=\"card mb-2 text-white bg-danger\">\r\n<div class=\"card-body\">\r\n<p class=\"card-text\">We have detected that your browser does not support cookies, or has set cookies to be disabled.</p>\r\n<p class=\"card-text\">To continue shopping online, we encourage you to enable cookies on your browser.</p>\r\n<p class=\"card-text\">For <strong>Internet Explorer</strong> browsers, please follow these instructions:</p>\r\n<ol>\r\n<li>Click on the Tools menubar, and select Internet Options</li>\r\n<li>Select the Security tab, and reset the security level to Medium</li>\r\n</ol>\r\n<p class=\"card-text\">We have taken this security measure for your benefit, and apologize for any inconvenience caused.  Please contact us if you have any questions about this requirement.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', 'Cookie Usage');

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Title Module', 'MODULE_CONTENT_INFO_TITLE_STATUS', 'True', 'Should this module be shown?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_INFO_TITLE_CONTENT_WIDTH', 'col-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INFO_TITLE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Text Module', 'MODULE_CONTENT_INFO_TEXT_STATUS', 'True', 'Should this module be shown?', 6, 1, 'Config::select_one([\'True\', \'False\'], ', NOW());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_INFO_TEXT_CONTENT_WIDTH', 'col-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INFO_TEXT_SORT_ORDER', '20', 'Sort order of display. Lowest is displayed first.', 6, 3, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Title Module', 'MODULE_CONTENT_CU_TITLE_STATUS', 'True', 'Should this module be shown?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CU_TITLE_CONTENT_WIDTH', 'col-12 mb-4', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_CU_TITLE_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', 6, 3, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_CONTENT_CU_INSTALLED', 'cu_form.php', 'This is automatically updated. No need to edit.', 6, 0, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable &pi; Modular contact_us', 'MODULE_CONTENT_CU_MODULAR_STATUS', 'True', 'Should this module be shown?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_CU_MODULAR_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 2, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: A', 'MODULE_CONTENT_CU_MODULAR_A_WIDTH', 'col-sm-12', 'What width should Slot A be?  Note that Slots in a Row should totalise 12.', 6, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: B', 'MODULE_CONTENT_CU_MODULAR_B_WIDTH', 'col-sm-4', 'What width should Slot B be?  Note that Slots in a Row should totalise 12.', 6, 4, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: C', 'MODULE_CONTENT_CU_MODULAR_C_WIDTH', 'col-sm-4', 'What width should Slot C be?  Note that Slots in a Row should totalise 12.', 6, 5, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: D', 'MODULE_CONTENT_CU_MODULAR_D_WIDTH', 'col-sm-4', 'What width should Slot D be?  Note that Slots in a Row should totalise 12.', 6, 6, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: E', 'MODULE_CONTENT_CU_MODULAR_E_WIDTH', 'col-sm-4', 'What width should Slot E be?  Note that Slots in a Row should totalise 12.', 6, 7, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: F', 'MODULE_CONTENT_CU_MODULAR_F_WIDTH', 'col-sm-4', 'What width should Slot F be?  Note that Slots in a Row should totalise 12.', 6, 8, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: G', 'MODULE_CONTENT_CU_MODULAR_G_WIDTH', 'col-sm-4', 'What width should Slot G be?  Note that Slots in a Row should totalise 12.', 6, 9, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: H', 'MODULE_CONTENT_CU_MODULAR_H_WIDTH', 'col-sm-12', 'What width should Slot H be?  Note that Slots in a Row should totalise 12.', 6, 10, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Slot Width: I', 'MODULE_CONTENT_CU_MODULAR_I_WIDTH', 'col-sm-12', 'What width should Slot I be?  Note that Slots in a Row should totalise 12.', 6, 11, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_CU_MODULAR_SORT_ORDER', '110', 'Sort order of display. Lowest is displayed first.', 6, 12, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Installed Modules', 'MODULE_LAYOUT_INSTALLED', '', 'This is automatically updated. No need to edit.', 6, 0, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Form', 'CU_FORM_STATUS', 'True', 'Should this module be shown?', 6, 1, now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Module Display', 'CU_FORM_GROUP', 'A', 'Where should this module display?', 6, 2, now(), 'Config::select_one([\'A\', \'B\', \'C\', \'D\', \'E\', \'F\', \'G\', \'H\', \'I\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'CU_FORM_CONTENT_WIDTH', 'col-12 mb-3', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', 6, 3, now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'CU_FORM_SORT_ORDER', '115', 'Sort order of display. Lowest is displayed first.', 6, 4, now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Module', 'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_STATUS', 'True', 'Do you want to enable this module?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_CONTACT_ADDRESSES_SORT_ORDER', '150', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Module', 'MODULE_CONTENT_GDPR_CONTACT_DETAILS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_CONTACT_DETAILS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_CONTACT_DETAILS_SORT_ORDER', '125', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Module', 'MODULE_CONTENT_GDPR_SITE_ACTIONS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_SITE_ACTIONS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_SITE_ACTIONS_SORT_ORDER', '225', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Module', 'MODULE_CONTENT_GDPR_SITE_DETAILS_STATUS', 'True', 'Do you want to enable this module?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_SITE_DETAILS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_SITE_DETAILS_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Cart Contents Module', 'MODULE_CONTENT_GDPR_CART_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_CART_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_CART_SORT_ORDER', '800', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Cookies Module', 'MODULE_CONTENT_GDPR_COOKIES_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_COOKIES_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_COOKIES_SORT_ORDER', '700', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable IP Address Module', 'MODULE_CONTENT_GDPR_IP_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_IP_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_IP_SORT_ORDER', '700', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Notifications Module', 'MODULE_CONTENT_GDPR_NOTIFICATIONS_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_NOTIFICATIONS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_NOTIFICATIONS_SORT_ORDER', '750', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Orders Module', 'MODULE_CONTENT_GDPR_ORDERS_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_ORDERS_CONTENT_WIDTH', 'col-sm-12', 'What width container should the content be shown in?', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_ORDERS_SORT_ORDER', '450', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Port Data (to JSON) Module', 'MODULE_CONTENT_GDPR_PORT_MY_DATA_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_PORT_MY_DATA_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_PORT_MY_DATA_SORT_ORDER', '5000', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Enable Reviews Module', 'MODULE_CONTENT_GDPR_REVIEWS_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), NULL, 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_REVIEWS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_REVIEWS_SORT_ORDER', '550', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Testimonials Module', 'MODULE_CONTENT_GDPR_TESTIMONIALS_STATUS', 'True', 'Should this module be shown on the GDPR page?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Content Container', 'MODULE_CONTENT_GDPR_TESTIMONIALS_CONTENT_WIDTH', 'col-sm-12', 'What container should the content be shown in? (col-*-12 = full width, col-*-6 = half width).', '6', '2', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_GDPR_TESTIMONIALS_SORT_ORDER', '555', 'Sort order of display. Lowest is displayed first.', '6', '3', now());
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('Enable Nuke Account Module', 'MODULE_CONTENT_ACCOUNT_GDPR_NUKE_STATUS', 'True', 'Do you want to enable this module?', '6', '1', now(), 'Config::select_one([\'True\', \'False\'], ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES ('Countries', 'MODULE_CONTENT_ACCOUNT_GDPR_NUKE_COUNTRIES', '', 'Restrict the Link to Account Holders in these Countries.  Leave Blank to show link to all Countries!', '6', '2', now(), 'cm_account_gdpr_nuke::show_countries', 'Config::select_multiple(Country::fetch_options(), ');
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_ACCOUNT_GDPR_NUKE_SORT_ORDER', '50', 'Sort order of display. Lowest is displayed first.', '6', '3', now());

# Info Pages Layout Modules 
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES (NULL, 'Installed Modules', 'MODULE_CONTENT_INFO_INSTALLED', '', 'List of &pi; Info Pages child modules separated by a semi-colon. This is automatically updated. No need to edit.', 6, 0, now());

# bootstrap5
INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Bootstrap Theme', 'BOOTSTRAP_THEME', 'auto', 'What theme (colour mode) should your site default to? See <a target="_blank" rel="noreferrer" href="https://getbootstrap.com/docs/5.3/customize/color-modes/"><u>/customize/color-modes/</u></a>.  <div class="alert alert-danger">This is an option for Bootstrap 5 only.</div>', 16, 3, 'Config::select_one([\'dark\', \'light\', \'auto\'], ', NOW());

INSERT INTO outgoing_tpl (id, slug, date_added) VALUES ('1', 'no_checkout', NOW());
INSERT INTO outgoing_tpl (id, slug, date_added) VALUES ('2', 'order_thanks', NOW());
INSERT INTO outgoing_tpl (id, slug, date_added) VALUES ('3', 'winback', NOW());

INSERT INTO outgoing_tpl_info (id, languages_id, title, text) VALUES (1, 1, '{{FNAME}}, no checkout?', 'Hi {{FNAME}}\r\n\r\nWe noticed you registered on our site back on the {{SIGN_UP_DAY}} of {{SIGN_UP_MONTH}} but you did not checkout.\r\n\r\nIf you had a problem with our site, please do not hesitate to contact us.');
INSERT INTO outgoing_tpl_info (id, languages_id, title, text) VALUES (2, 1, '{{FNAME}} thank you for Order #{{ORDER_ID}}', 'Hi {{FNAME}}\r\n\r\nThank you for Order #{{ORDER_ID}} made on {{ORDER_DATE}}.  We are working to pick and pack your Order and will update you at each stage of the process.\r\n\r\nYou ordered:\r\n{{ORDER_PRODUCTS}}\r\n\r\nIf you have any questions about this Order or our site, please do not hesitate to contact us.');
INSERT INTO outgoing_tpl_info (id, languages_id, title, text) VALUES (3, 1, '{{FNAME}}, we\'ve missed you', 'Hi {{FNAME}}\r\n\r\nWe really hope you enjoyed your products that you ordered back on {{ORDER_DATE}}\r\n\r\nWe have new products we think you might be interested in.');


