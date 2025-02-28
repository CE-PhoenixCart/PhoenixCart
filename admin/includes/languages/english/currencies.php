<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2022 Phoenix Cart

  Released under the GNU General Public License
*/

const HEADING_TITLE = 'Currencies';

const TABLE_HEADING_CURRENCY_NAME = 'Currency';
const TABLE_HEADING_CURRENCY_CODES = 'Code';
const TABLE_HEADING_CURRENCY_VALUE = 'Value';
const TABLE_HEADING_ACTION = 'Action';

const TEXT_INFO_EDIT_INTRO = 'Please make any necessary changes';
const TEXT_INFO_COMMON_CURRENCIES = '-- Common Currencies --';
const TEXT_INFO_CURRENCY_TITLE = 'Title: %s';
const TEXT_INFO_CURRENCY_CODE = 'Code: %s';
const TEXT_INFO_CURRENCY_SYMBOL_LEFT = 'Symbol Left: %s';
const TEXT_INFO_CURRENCY_SYMBOL_RIGHT = 'Symbol Right: %s';
const TEXT_INFO_CURRENCY_DECIMAL_POINT = 'Decimal Point: %s';
const TEXT_INFO_CURRENCY_THOUSANDS_POINT = 'Thousands Point: %s';
const TEXT_INFO_CURRENCY_DECIMAL_PLACES = 'Decimal Places: %s';
const TEXT_INFO_CURRENCY_LAST_UPDATED = 'Last Updated: %s';
const TEXT_INFO_CURRENCY_VALUE = 'Value: %s';
const TEXT_INFO_CURRENCY_EXAMPLE = 'Example Output: %s =  %s';

const TEXT_INFO_INSERT_INTRO = 'Please enter the new currency with its related data';
const TEXT_INFO_DELETE_INTRO = 'Are you sure you want to delete this currency?';
const TEXT_INFO_HEADING_NEW_CURRENCY = 'New Currency';
const TEXT_INFO_HEADING_EDIT_CURRENCY = 'Edit Currency';
const TEXT_INFO_HEADING_DELETE_CURRENCY = 'Delete Currency';
const TEXT_INFO_SET_AS_DEFAULT = TEXT_SET_DEFAULT . ' (requires a manual update of currency values)';
const TEXT_INFO_CURRENCY_UPDATED = 'The exchange rate for %s (%s) was updated successfully via %s.';

const ERROR_REMOVE_DEFAULT_CURRENCY = '<strong>Error:</strong> The default currency can not be removed. Please set another currency as default, and try again.';
const ERROR_CURRENCY_INVALID = '<strong>Error:</strong> The exchange rate for %s (%s) was not updated via %s. Is it a valid currency code?';
const WARNING_PRIMARY_SERVER_FAILED = '<strong>Warning:</strong> The primary exchange rate server (%s) failed for %s (%s) - trying the secondary exchange rate server.';

const ERROR_INSTALL_CURRENCY_CONVERTER = 'You do not have a Currency Conversion module installed.  <a class="alert-link fw-bold" href="%s">Install Now</a>';

const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Currencies';
