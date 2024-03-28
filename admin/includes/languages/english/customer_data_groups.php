<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

const HEADING_TITLE = 'Customer Data Groups';

const TABLE_HEADING_CUSTOMER_DATA_GROUP_NAME = 'Customer Data Group';
const TABLE_HEADING_SORT_ORDER = 'Sort Order';
const TABLE_HEADING_WIDTH = 'Width';
const TABLE_HEADING_ACTION = 'Action';

const TEXT_INFO_EDIT_INTRO = 'Please make any necessary changes';
const TEXT_INFO_USE_FIRST_FOR_ALL = 'Check if you want to use the first sort order and width values for all languages.';
const TEXT_INFO_CUSTOMER_DATA_GROUP_NAME = 'Name:';
const TEXT_INFO_SORT_ORDER = 'Sort Order: %s';
const TEXT_INFO_WIDTH = 'Width: %s';
const TEXT_INFO_INSERT_INTRO = 'Please enter the new customer data group with its related data';
const TEXT_INFO_DELETE_INTRO = <<<'EOT'
<p>Are you sure you want to delete this customer data group?</p>

<p class="alert alert-warning">Warning:  if any customer data modules are using this group, deleting the group will leave them orphaned!
Consider if you would be better off just editing this group rather than deleting it and making a new one.
To translate, you would typically edit it instead.</p>
EOT;
const TEXT_INFO_HEADING_NEW_CUSTOMER_DATA_GROUP = 'New Customer Data Group';
const TEXT_INFO_HEADING_EDIT_CUSTOMER_DATA_GROUP = 'Edit Customer Data Group';
const TEXT_INFO_HEADING_DELETE_CUSTOMER_DATA_GROUP = 'Delete Customer Data Group';

const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Customer_Data_Groups';
