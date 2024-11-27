<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

const HEADING_TITLE = 'Outgoing E-mail Templates';

const BUTTON_INSERT_NEW_SLUG = 'Insert New E-mail Template';
const HEADING_DELETE_SLUG = 'Delete This E-mail Template';
const HEADING_NEW_SLUG = 'New E-mail Template';

const SLUG_SELECT = '--- Please Select ---';

const TABLE_HEADING_SLUG = 'Template Name';
const TABLE_HEADING_TITLE = 'Title';
const TABLE_HEADING_DATE_ADDED = 'Date Added';	
const TABLE_HEADING_ACTION = 'Action';

const TEXT_OUTGOING_SLUG = 'Template Name';
const TEXT_OUTGOING_SLUG_TITLE = 'Email Title';
const TEXT_OUTGOING_SLUG_TEXT = 'Email Text';

const MISSING_SLUGS = '<div class="alert alert-danger d-flex justify-content-between">
<span>Attention:  These Scheduler Modules exist and MUST have a Template made for them!<br><b>%s</b></span>
<span>%s</span>
</div>';

const TEXT_HEADING_NEW_OUTGOING_EMAIL = 'Add New E-mail Template';
const TEXT_HEADING_EDIT_OUTGOING_EMAIL = 'Edit E-mail Template';
const TEXT_HEADING_DELETE_OUTGOING_EMAIL = 'Delete E-mail from Queue';

const TEXT_OUTGOING_DATE = 'Send At:';
const TEXT_OUTGOING_EMAIL = 'Email Address:';

const TEXT_NEW_INTRO = 'Please fill out the following information for the new E-mail';
const TEXT_EDIT_INTRO = 'Please make any necessary changes';
const TEXT_DELETE_INTRO = 'Are you certain you wish to delete this E-mail?';

const TEXT_DATE_ADDED = 'Date Added: %s';
const TEXT_LAST_MODIFIED = 'Last Modified: %s';

const TEXT_DISPLAY_NUMBER_OF_OUTGOING = 'Displaying <b>%s</b> to <b>%s</b> of <b>%s</b> E-mail Templates';
const IMAGE_VIEW_EMAIL = 'View This E-mail';

$available_merge_tags = ['{{FNAME}}' => 'First Name',
                         '{{LNAME}}' => 'Last Name',
                         '{{EMAIL}}' => 'Email Address'];

const GET_HELP_LINK = 'https://phoenixcart.org/phoenixcartwiki/index.php?title=Queued_Emails';
const GET_ADDONS_LINKS = [ADDONS_COMMERCIAL => 'https://phoenixcart.org/forum/app.php/addons/commercial/queued_emails-48',];
