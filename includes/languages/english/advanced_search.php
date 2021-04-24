<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

const NAVBAR_TITLE_1 = 'Advanced Search';
const NAVBAR_TITLE_2 = 'Search Results';

const HEADING_TITLE_1 = 'Advanced Search';
const HEADING_TITLE_2 = 'Products meeting the search criteria';

const HEADING_SEARCH_CRITERIA = 'Search Criteria';

const TEXT_SEARCH_IN_DESCRIPTION = 'Search In Product Descriptions';
const ENTRY_CATEGORIES = 'Categories';
const ENTRY_INCLUDE_SUBCATEGORIES = 'Include Subcategories';
const ENTRY_MANUFACTURERS = 'Manufacturers';
const ENTRY_PRICE_FROM = 'Price From';
const ENTRY_PRICE_TO = 'Price To';
const ENTRY_DATE_FROM = 'Date From';
const ENTRY_DATE_TO = 'Date To';

const ENTRY_PRICE_FROM_TEXT = 'From';
const ENTRY_PRICE_TO_TEXT = 'To';

const ENTRY_DATE = 'Date';
const ENTRY_PRICE = 'Price';

const TEXT_SEARCH_HELP_LINK = '<i class="fas fa-info-circle"></i> Search Help';

const TEXT_ALL_CATEGORIES = 'All Categories';
const TEXT_ALL_MANUFACTURERS = 'All Manufacturers';

const HEADING_SEARCH_HELP = 'Search Help';
const TEXT_SEARCH_HELP = <<<'EOT'
Keywords may be separated by AND and/or OR statements for greater control of the search results.<br>
<br>
For example, <u>Fiacre AND pear</u> would generate a result set that contain both words. However, for <u>orange OR lemon</u>, the result set returned would contain both or either words.<br>
<br>
Exact matches can be searched by enclosing keywords in double-quotes.<br>
<br>
For example, <u>"green apple"</u> would generate a result set which match the exact string.<br>
<br>
Brackets can be used for further control on the result set.<br>
<br>
For example, <u>Pixabay and (lime or red or "green apple")</u>.
EOT;
const TEXT_CLOSE_WINDOW = '<u>Close Window</u> [x]';

const TEXT_NO_PRODUCTS = 'There is no product that matches the search criteria.';

const ERROR_AT_LEAST_ONE_INPUT = 'At least one of the fields in the search form must be entered.';
const ERROR_INVALID_FROM_DATE = 'Invalid From Date.';
const ERROR_INVALID_TO_DATE = 'Invalid To Date.';
const ERROR_TO_DATE_LESS_THAN_FROM_DATE = 'To Date must be greater than or equal to From Date.';
const ERROR_PRICE_FROM_MUST_BE_NUM = 'Price From must be a number.';
const ERROR_PRICE_TO_MUST_BE_NUM = 'Price To must be a number.';
const ERROR_PRICE_TO_LESS_THAN_PRICE_FROM = 'Price To must be greater than or equal to Price From.';
const ERROR_INVALID_KEYWORDS = 'Invalid keywords.';

const DATE_FORMAT_STRING = 'mm/dd/yyyy';
